#!/bin/bash

DIST_TYPE='full'
NAME="ezpublish"
DEST_ROOT="/tmp/ez-$USER"
DEFAULT_SVN_SERVER="http://svn.ez.no/svn/ezpublish"
DEFAULT_SVN_RELEASE_PATH="releases"
DEFAULT_SVN_VERSION_PATH="versions"
DIST_SRC=`pwd`
EDITOR='vi'

FULL_EXTRA_DIRS="settings/override var/cache var/storage"
SDK_EXTRA_DIRS="settings/override var/carhe var/storage doc/generated/html"

FILTER_FILES="settings/site.ini settings/content.ini settings/setup.ini settings/i18n.ini settings/layout.ini settings/template.ini settings/texttoimage.ini settings/units.ini settings/siteaccess/admin/site.ini.append.php settings/siteaccess/admin/override.ini.append.php settings/siteaccess/admin/icon.ini.append.php settings/siteaccess/admin/toolbar.ini.append.php settings/webdav.ini settings/image.ini"
FILTER_FILES2="bin/modfix.sh"
PACKAGE_DIR="packages"

BUILD_NUMBER=1
CACHE=".ezp.cache"

SVN_EXPORT="undef"

LICENSE_TYPE="GPL"
LICENSES_DIR="$DEST_ROOT/licenses"
LICENSES_SVN_SERVER="http://svn.ez.no/svn/commercial/core/licenses"

# Read in cache file if it exists
if [ -f $CACHE ]; then
    . $CACHE
fi

. ./bin/shell/common.sh
. ./bin/shell/distcommon.sh
. ./bin/shell/sqlcommon.sh
. ./bin/shell/packagescommon.sh
. ./bin/shell/extensionscommon.sh

# Initialise several database related variables, see sqlcommon.sh
ezdist_db_init
[ -z "$DIST_DB_NAME" ] && DIST_DB_NAME="undef"


if ! which php &>/dev/null; then
    echo "No PHP executable found, please add it to the path"
    exit 1
fi

# Writes all cached values to the cache file, should be done at the end of the script
# or when certain input variables are read
function ezdist_write_cache_file
{
    echo -n '' > $CACHE
    echo '# Cache file for eZ Publish makedist.sh' >> $CACHE

    ezdist_is_def "$MYSQL_USER" && echo "MYSQL_USER=\"$MYSQL_USER\"" >> $CACHE
    ezdist_is_def "$MYSQL_PASSWD" && echo "MYSQL_PASSWD=\"$MYSQL_PASSWD\"" >> $CACHE
    ezdist_is_def "$MYSQL_SOCKET" && echo "MYSQL_SOCKET=\"$MYSQL_SOCKET\"" >> $CACHE
    ezdist_is_def "$MYSQL_HOST" && echo "MYSQL_HOST=\"$MYSQL_HOST\"" >> $CACHE
    ezdist_is_def "$MYSQL_PORT" && echo "MYSQL_PORT=\"$MYSQL_PORT\"" >> $CACHE
    ezdist_is_def "$MYSQL_NAME" && echo "MYSQL_NAMe=\"$MYSQL_NAME\"" >> $CACHE

    ezdist_is_def "$POSTGRESQL_USER" && echo "POSTGRESQL_USER=\"$POSTGRESQL_USER\"" >> $CACHE
    ezdist_is_def "$POSTGRESQL_PASSWD" && echo "POSTGRESQL_PASSWD=\"$POSTGRESQL_PASSWD\"" >> $CACHE
    ezdist_is_def "$POSTGRESQL_HOST" && echo "POSTGRESQL_HOST=\"$POSTGRESQL_HOST\"" >> $CACHE
    ezdist_is_def "$POSTGRESQL_PORT" && echo "POSTGRESQL_PORT=\"$POSTGRESQL_PORT\"" >> $CACHE
    ezdist_is_def "$POSTGRESQL_NAME" && echo "POSTGRESQL_NAME=\"$POSTGRESQL_NAME\"" >> $CACHE

    ezdist_is_def "$SVN_EXPORT" && echo "SVN_EXPORT=\"$SVN_EXPORT\"" >> $CACHE
    ezdist_is_def "$DIST_DB_NAME" && echo "DIST_DB_NAME=\"$DIST_DB_NAME\"" >> $CACHE

    echo "BUILD_NUMBER=\"$BUILD_NUMBER\"" >> $CACHE
}

function make_dir
{
    local DIR
    DIR=`echo "$1" | sed 's#^\./##'`
    if [ ! -d "$DEST/$DIR" ]; then
	mkdir "$DEST/$DIR"
    fi
}

function copy_file
{
    local SRC_FILE DST_FILE
    SRC_FILE=`echo $1 | sed 's#^\./##'`
    DST_FILE="$SRC_FILE"
    cp -f "$SRC_FILE" "$DEST/$DST_FILE"

}

function scan_dir_normal
{
    local file
    local DIR

    DIR=$1
#    echo "Scanning dir $DIR normally"
    for file in $DIR/*; do
	if [ -e "$file" -a ! "$file" = "$DIR/.svn" -a ! "$file" = "$DIR/.." -a ! "$file" = "$DIR/." ]; then
# 	if ! echo $file | grep "/\*" &>/dev/null; then
	    if [ -d "$file" ]; then
	        # Do not include .svn dirs
		if [ "$file" != ".svn" ]; then
		    make_dir "$file"
		    scan_dir_normal "$file"
		fi
	    else
	        # Do not include temporary files
		if ! echo "$file" | grep '[~#]$' &>/dev/null; then
		    copy_file "$file"
		fi
	    fi
	fi
    done
}

function scan_dir
{
    local file
    local DIR
    local DIST_PROP_TYPE
    local DIST_DIR

    DIR=$1
#    echo "Scanning dir $DIR"
    for file in $DIR/* $DIR/.*; do
#	if ! echo $file | grep "/\*" &>/dev/null; then
	if [ -e "$file" -a ! "$file" = "$DIR/.svn" -a ! "$file" = "$DIR/.." -a ! "$file" = "$DIR/." ]; then
	    DIST_PROP_TYPE=`svn propget $DIST_PROP $file 2>/dev/null`
#	    if [ $? -eq 0 && ! -z "$DIST_PROP_TYPE" ]; then
	    if  [[ $? -eq 0 && ! -z "$DIST_PROP_TYPE" ]] || [[ $file = "./licenses" ]] || [[ $file = "./licenses/PROPRIETARY_USE_LICENSE_v1.0" ]] || [[ $file = "./licenses/license-notice-ezpul.yaml" ]]; then
#    		if echo $DIST_PROP_TYPE | grep $DIST_TYPE &>/dev/null; then
    		    DIST_DIR=`svn propget $DIST_DIR_PROP $file 2>/dev/null`
    		    DIST_DIR_RECURSIVE=""
	    	    if [ $? -eq 0 ] && [ ! -z "$DIST_DIR" ]; then
        			if echo $DIST_DIR | grep $DIST_TYPE &>/dev/null; then
#		        	    echo "Found include all marker for $file"
			            DIST_DIR_RECURSIVE=$DIST_TYPE
        			fi
		        fi
    		    if [ -d "$file" ]; then
	    		    echo -n " "`$SETCOLOR_DIR`"$file"`$SETCOLOR_NORMAL`"/"
    	    		make_dir "$file"
	    	    	if [ -z $DIST_DIR_RECURSIVE ]; then
        			    scan_dir "$file"
	        		else
		        	    echo -n "*"
        			    scan_dir_normal "$file"
	        		fi
    		    else
        			echo -n " "`$SETCOLOR_FILE`"$file"`$SETCOLOR_NORMAL`
        			copy_file "$file"
    		    fi
#    		fi
	    fi
	fi
    done
}

function update_license
{
    REV=`svn info | grep 'Revision:' | sed 's/Revision: //'`
    . ./bin/shell/updatelicense.sh --licenses-dir="$LICENSES_DIR" --target-dir="$DEST" --license-type="$LICENSE_TYPE" --version="$VERSION" --revision="$REV"
}

SVN_SERVER=""
REPOS_RELEASE="trunk"

TMP_DB_NAME="ez_tmp_makedist"

# Check parameters
for arg in $*; do
    case $arg in
        --help|-h)
            echo "Usage: $0 [options]"
            echo
            echo "Options: -h"
            echo "         --help                     This message"
            echo "         --final                    Makes the release a final release"
            echo "         --build-root=DIR           Set build root, default is /tmp"
    #       echo "         --build-rc                 Make a release candidate, this will add a build number to the name"
            echo "         --build-snapshot           Make a snapshot, this will add a build number to the name"
            echo "         --with-svn-server[=SERVER] Checkout fresh repository"
            echo "         --with-release=NAME        Checkout a previous release, default is trunk"
            echo "         --license-type=TYPE        What license to use: gpl(default), pul_v1, pl_v2"
            echo "         --skip-license-update      Do not update license and php-headers."
	    echo "         --skip-isbn13-check        Do not check last update of ISBN13 data"
    #       echo "         --skip-site-creation       Do not build sites*"
            echo "         --skip-version-check       Do not check version numbers*"
            echo "         --skip-php-check           Do not check PHP for syntax correctnes*"
            echo "         --skip-code-template-check Do not check code templates*"
            echo "         --skip-unit-tests          Do not run unit tests*"
            echo "         --skip-db-schema           Do not create db schema (requires mysql and postgresql)*"
            echo "         --skip-db-update           Do not run db update check*"
            echo "         --skip-db-check            Do not run db schema check*"
            echo "         --skip-translation         Do not run translation check*"
            echo "         --skip-styles              Do not create style packages*"
            echo "         --skip-addons              Do not create addon packages*"
            echo "         --skip-test-framework      Do not copy test framework*"
            echo "         --skip-core-files          Do not copy kernel/lib files*"
            echo "         --skip-changelogs          Do not changelogs from earlier versions*"
            echo "         --skip-sql-generation      Do not generate SQL files*"
            echo "         --skip-extensions          Do not package extensions*"
            echo "         --db-name=databasename     MySQL DB name ( default: ez_tmp_makedist )"
            echo

            # Show options for database
            ezdist_db_show_options

            echo "SVN options:"
            echo "         --use-svn-server           Do all operation using SVN server"
            echo "         --use-working-copy         Do all operations on working copy, SVN server is not contacted"
            echo
            echo "* Warning: Using these options will not make a valid distribution"
            echo
            echo "Example:"
            echo "$0 --release-sdk --with-svn-server"
            exit 1
            ;;
        --build-root=*)
            if echo $arg | grep -e "--build-root=" >/dev/null; then
                    DEST_ROOT=`echo $arg | sed 's/--build-root=//'`
            fi
            ;;
#       --build-rc)
#           BUILD_RC="1"
#           ;;
        --build-snapshot)
            BUILD_SNAPSHOT="1"
            ;;
        --final)
            FINAL="1"
            ;;
        --with-svn-server*)
            if echo $arg | grep -e "--with-svn-server=" >/dev/null; then
                SVN_SERVER=`echo $arg | sed 's/--with-svn-server=//'`
            else
                SVN_SERVER=$DEFAULT_SVN_SERVER
            fi
            ;;
        --license-type*)
            if echo $arg | grep -e "--license-type=" >/dev/null; then
                LICENSE_TYPE=`echo $arg | sed 's/--license-type=//'`
            fi
            ;;
        --with-release*)
            if echo $arg | grep -e "--with-release=" >/dev/null; then
                REPOS_RELEASE=`echo $arg | sed 's/--with-release=//'`
            else
                REPOS_RELEASE="trunk"
            fi
            ;;
        --use-svn-server)
            SVN_EXPORT="svn"
            ;;
        --use-working-copy)
            SVN_EXPORT="wc"
            ;;
        --db-name=*)
            if echo $arg | grep -e "--mysql-db=" >/dev/null; then
                TMP_DB_NAME=`echo $arg | sed 's/--mysql-db=//'`
            fi
            ;;

	--skip-isbn13-check)
	    SKIPISBN13CHECK="1"
	    ;;

        --skip-site-creation)
            SKIPSITECREATION="1"
            ;;

#       --skip-site-creation)
#           SKIPSITECREATION="1"
#           ;;
        --skip-all-checks)
            SKIPCHECKVERSION="1"
            SKIPCHECKPHP="1"
            SKIPDBSCHEMA="1"
            SKIPDBCHECK="1"
            SKIPDBUPDATE="1"
            SKIPUNITTESTS="1"
            SKIPTRANSLATION="1"
            SKIP_LICENSE_UPDATE="1"
            ;;
        --skip-styles)
            SKIPSTYLECREATION="1"
            ;;
        --skip-addons)
            SKIPADDONCREATION="1"
            ;;
        --skip-version-check)
            SKIPCHECKVERSION="1"
            ;;
        --skip-php-check)
            SKIPCHECKPHP="1"
            ;;
        --skip-code-template-check)
            SKIPCODETEMPLATECHECK="1"
            ;;
        --skip-db-schema)
            SKIPDBSCHEMA="1"
            ;;
        --skip-db-check)
            SKIPDBCHECK="1"
            ;;
        --skip-db-update)
            SKIPDBUPDATE="1"
            ;;
        --skip-unit-tests)
            SKIPUNITTESTS="1"
            ;;
        --skip-translation)
            SKIPTRANSLATION="1"
            ;;
        --skip-test-framework)
            SKIP_TEST_FRAMEWORK="1"
            ;;
        --skip-core-files)
            SKIP_CORE_FILES="1"
            ;;
        --skip-changelogs)
            SKIP_CHANGELOGS="1"
            ;;
        --skip-sql-generation)
            SKIP_SQL_GENERATION="1"
            ;;
        --skip-extensions)
            SKIP_EXTENSIONS="1"
            ;;
        --skip-license-update)
            SKIP_LICENSE_UPDATE="1"
            ;;



        --*)
            # Check for DB options
            ezdist_db_check_options "$arg"

            if [ $? -ne 0 ]; then
                echo "$arg: unknown long option specified"
                echo
                echo "Type '$0 --help\` for a list of options to use."
                exit 1
            fi
            ;;
        -*)
            # Check for DB options
            ezdist_db_check_short_options "$arg"

            if [ $? -ne 0 ]; then
                echo "$arg: unknown option specified"
                echo
                echo "Type '$0 --help\` for a list of options to use."
                exit 1
            fi
            ;;
        *)
            echo "$arg: unknown argument specified"
            echo
            echo "Type '$0 --help\` for a list of options to use."
            exit 1
            ;;
    esac;
done
BASE="$NAME-$DIST_TYPE-$VERSION"

# Read in MySQL data if they are missing
ezdist_mysql_read_info
# Read in PostgreSQL data if they are missing
ezdist_postgresql_read_info

function ezdist_svn_read_info
{
    local type

    while ezdist_is_undef "$SVN_EXPORT"; do
        echo -n "SVN: Do you wish to use the live SVN server or the working copy (wc) [Svn|wc]: "
        read type
        type=`echo $type | tr A-Z a-z`
        [ -z "$type" ] && type="svn"
        case $type in
            wc)
                SVN_EXPORT="wc"
                ;;

            svn)
                SVN_EXPORT="svn"
                ;;
            q)
                exit 1
                ;;
            *)
                echo "Unknown type $type"
                ;;
        esac
    done
}

function ezdist_dbname_read_info
{
    local name

    while ezdist_is_undef "$DIST_DB_NAME"; do
        echo -n "DB: Which database should be used for creating addon packages: "
        read name
        if [ -z "$name" ]; then
            DIST_DB_NAME="none"
        else
            DIST_DB_NAME="$name"
        fi
    done
}

function ezdist_check_isbn13_data
{
    ISBN13_DBA_FILE="kernel/classes/datatypes/ezisbn/share/db_data.dba"

    LAST_MOD_DATE=`stat -c "%Y" "$ISBN13_DBA_FILE"`
    DATE=`date +"%s"`
    EXPIRY_DATE=$(($LAST_MOD_DATE+604800))  # 604800 = 7*24*60*60 = one week

    if [ $EXPIRY_DATE -lt $DATE ] ; then
        echo "You need to update ISBN13 data"
        echo "run ./bin/shell/updateisbn13.sh"
        exit 1
    fi
}

if [ -z $SKIPISBN13CHECK ]; then
    ezdist_check_isbn13_data
fi

ezdist_svn_read_info
ezdist_dbname_read_info

ezdist_write_cache_file

ezdist_mysql_prepare_params
ezdist_postgresql_prepare_params

if [ "$DIST_TYPE" == "sdk" ]; then
    echo "Creating SDK release"
elif [ "$DIST_TYPE" == "full" ]; then
    echo "Creating full release"
    BASE="$NAME-$VERSION"
else
    echo "Unknown release"
    exit 1
fi

# We append a build number when creating RCs (release candidate)
# The build number is also increase and stored at the end if everything was successful
if [ "$BUILD_SNAPSHOT" == "1" ]; then
    echo "Creating RC build #$BUILD_NUMBER"
    CURRENT_BUILD_NUMBER=$BUILD_NUMBER
    BASE="$BASE-build$CURRENT_BUILD_NUMBER"
    BUILD_NUMBER=`expr $BUILD_NUMBER + 1`
fi

DEST="$DEST_ROOT/$BASE"
DEST_EXTENSION_ARCHIVE="$DEST_ROOT/$BASE-extensions"

#
# *****   Handle translations and locale   *****
#

if [ -z $SKIPTRANSLATION ]; then
    ezdist_check_ezlupdate

    # Make sure ezlupdate is up to date, in case of source changes
    echo -n "Updating ezlupdate"
    EZLUPDATE_LOG=`ezdist_update_ezlupdate 2>/dev/stdout`
    ez_result_output $? "$EZLUPDATE_LOG" || exit 1
fi


#
# *****   Make sure common.sh contains correct branch info *****
#

CUR_SVN_PATH=`svn info | grep 'URL:' | sed 's#URL: '$REPOSITORY_BASE_URL'/##'`
if [ "$CUR_SVN_PATH" != "$REPOSITORY_BRANCH_PATH" ]; then
    echo "The repository branch path defined in bin/shell/common.sh is not correct."
    echo "The variable `$SETCOLOR_EMPHASIZE`REPOSITORY_BRANCH_PATH`$SETCOLOR_NORMAL` is set to `$SETCOLOR_NEW`$REPOSITORY_BRANCH_PATH`$SETCOLOR_NORMAL`"
    echo "The correct path is `$SETCOLOR_NEW`$CUR_SVN_PATH`$SETCOLOR_NORMAL`, change the setting to:"
    echo "REPOSITORY_BRANCH_PATH=\"$CUR_SVN_PATH\""
    echo "and commit the changes before restarting makedist.sh"
    exit 1
fi

echo "Connecting to MySQL using `ezdist_mysql_show_config`"
echo "Connecting to PostgreSQL using `ezdist_postgresql_show_config`"

if [ "$SVN_SERVER" != "" ]; then
    echo "Checking out from server $SVN_SERVER"
    if [ "$REPOS_RELEASE" == "" ]; then
        SVN_PATH="$SVN_SERVER/DEFAULT_SVN_RELEASE_PATH/$REPOS_RELEASE"
        echo "Checking out release $REPOS_RELEASE"
    else
        SVN_PATH="$SVN_SERVER/trunk"
        echo "Checking out trunk"
    fi
    echo "SVN_PATH=$SVN_PATH"
    DIST_SRC="/tmp/nextgen-$REPOS_RELEASE"
else
    echo "Using local copy from `$SETCOLOR_DIR``pwd``$SETCOLOR_NORMAL`"
fi

if [ "$SVN_EXPORT" == "svn" ]; then
    echo "All operations are done from SVN server, official builds are possible"
else
    echo "All operations are done from working copy (WC), only test builds are possible"
fi

echo "Distribution source files taken from `$SETCOLOR_DIR`$DIST_SRC`$SETCOLOR_NORMAL`"

if [ -z $SKIPCHECKVERSION ]; then
    echo -n "Checking db update version numbers"
    ./bin/php/checkdbfiles.php &>/dev/null
    if [ $? -ne 0 ]; then
        echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
        echo
        echo "`$SETCOLOR_FAILURE`************** WARNING **************`$SETCOLOR_NORMAL`"
        echo
        echo "There are problems with the db update files"
        echo "Run the following command to see what must be fixed"
        echo "`$SETCOLOR_EXE`./bin/php/checkdbfiles.php`$SETCOLOR_EXE`"
        echo
        echo "`$SETCOLOR_FAILURE`************** WARNING **************`$SETCOLOR_NORMAL`"
        echo
        exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"

    echo -n "Checking version numbers"
    ./bin/shell/checkversionnumbers.sh --exit-at-once &>/dev/null
    if [ $? -ne 0 ]; then
        echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
        echo
        echo "`$SETCOLOR_FAILURE`************** WARNING **************`$SETCOLOR_NORMAL`"
        echo
        echo "There are problems with the version numbers"
        echo "Run the following command to see what must be fixed"
        echo "`$SETCOLOR_EXE`./bin/shell/checkversionnumbers.sh`$SETCOLOR_EXE`"
        echo
        echo "`$SETCOLOR_FAILURE`************** WARNING **************`$SETCOLOR_NORMAL`"
        echo
        exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"
fi

if [ -z $SKIPCODETEMPLATECHECK ]; then
    echo -n "Checking code templates"
    ./bin/php/ezapplytemplate.php --all --check-only -q
    if [ $? -ne 0 ]; then
        echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
        echo "Some PHP files were updated by code templates"
        echo "Run the following command to find the files"
        echo "./bin/php/ezapplytemplate.php --all"
        exit 1
    fi

    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"
fi

if [ -z $SKIPCHECKPHP ]; then
    echo -n "Checking syntax of PHP files"
    ./bin/shell/phpcheck.sh --exit-on-error -q cronjobs kernel lib support update tests/classes benchmarks/classes
    if [ $? -ne 0 ]; then
        echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
        echo "Some PHP files have syntax errors"
        echo "Run the following command to find the files"
        echo "./bin/shell/phpcheck.sh --errors-only bin/php cronjobs kernel lib support update tests/classes benchmarks/classes"
        exit 1
    fi

    ./bin/php/ezcheckphptag.php -q --no-print cronjobs kernel lib support update tests/classes benchmarks/classes
    if [ $? -ne 0 ]; then
        echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
        echo "Some PHP files have bad PHP starting and ending tag usage"
        echo "Run the following command to find the files"
        echo "./bin/php/ezcheckphptag.php cronjobs bin/php kernel lib support update tests/classes benchmarks/classes"
        exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"
fi

if [ -z $SKIPUNITTESTS ]; then
    echo -n "Running unit tests"
    ./tests/testunits.php -q eztemplate ezxml ezi18n
    if [ $? -ne 0 ]; then
        echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
        echo "Some unit tests failed"
        echo "Run the following command to find out which one failed"
        echo "./tests/testunits.php eztemplate ezxml ezi18n"
        exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"
fi

if [ -z $SKIPDBCHECK ]; then
    echo -n "Checking database schemas"
    ./bin/shell/checkdbschema.sh $PARAM_EZ_MYSQL_ALL $PARAM_EZ_POSTGRESQL_ALL "$TMP_DB_NAME" &>/dev/null
    if [ $? -ne 0 ]; then
        echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
        echo "The database schema check failed"
        echo "Run the following command to find out what is wrong"
        echo "./bin/shell/checkdbschema.sh $PARAM_EZ_MYSQL_ALL $PARAM_EZ_POSTGRESQL_ALL $TMP_DB_NAME"
        exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"
fi

if [ -z $SKIPDBUPDATE ]; then
    echo -n "Checking MySQL database updates"
    # Only check stable->current update when it's a final release
    if [ "$VERSION_FINAL" == "true" ]; then
        ./bin/shell/checkdbupdate.sh --check-stable --mysql $PARAM_EZ_MYSQL_ALL "$TMP_DB_NAME" &>/dev/null
        if [ $? -ne 0 ]; then
            echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
            echo "The database update check for MySQL failed"
            echo "Run the following command to find out what is wrong"
            echo "./bin/shell/checkdbupdate.sh --check-stable --mysql $PARAM_EZ_MYSQL_ALL $TMP_DB_NAME"
            exit 1
        fi
    fi
    ./bin/shell/checkdbupdate.sh --check-previous --mysql $PARAM_EZ_MYSQL_ALL "$TMP_DB_NAME" &>/dev/null
    if [ $? -ne 0 ]; then
        echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
        echo "The database update check for MySQL failed"
        echo "Run the following command to find out what is wrong"
        echo "./bin/shell/checkdbupdate.sh --check-previous --mysql $PARAM_EZ_MYSQL_ALL $TMP_DB_NAME"
        exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"

    echo -n "Checking PostgreSQL database updates"
    # Only check stable->current update when it's a final release
    if [ "$VERSION_FINAL" == "true" ]; then
        ./bin/shell/checkdbupdate.sh --check-stable --postgresql $PARAM_EZ_POSTGRESQL_ALL "$TMP_DB_NAME" &>/dev/null
        if [ $? -ne 0 ]; then
            echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
            echo "The database update check for Postgresql failed"
            echo "Run the following command to find out what is wrong"
            echo "./bin/shell/checkdbupdate.sh --check-stable --postgresql $PARAM_EZ_POSTGRESQL_ALL $TMP_DB_NAME"
            exit 1
	fi
    fi
     ./bin/shell/checkdbupdate.sh --check-previous --postgresql $PARAM_EZ_POSTGRESQL_ALL "$TMP_DB_NAME" &>/dev/null
    if [ $? -ne 0 ]; then
        echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
        echo "The database update check for Postgresql failed"
        echo "Run the following command to find out what is wrong"
        echo "./bin/shell/checkdbupdate.sh --check-previous --postgresql $PARAM_EZ_POSTGRESQL_ALL $TMP_DB_NAME"
        exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"
fi

echo -n "Dist: `ez_color_dir $DEST`"

if [ -d $DEST ]; then
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Recreated ]`$SETCOLOR_NORMAL`"
    rm -rf $DEST
    mkdir -p $DEST
else
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Created ]`$SETCOLOR_NORMAL`"
    mkdir -p $DEST
fi

echo -n "Ext:  `ez_color_dir $DEST_EXTENSION_ARCHIVE`"

if [ -d "$DEST_EXTENSION_ARCHIVE" ]; then
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Recreated ]`$SETCOLOR_NORMAL`"
    rm -rf "$DEST_EXTENSION_ARCHIVE"
    mkdir -p "$DEST_EXTENSION_ARCHIVE"
else
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Created ]`$SETCOLOR_NORMAL`"
    mkdir -p "$DEST_EXTENSION_ARCHIVE"
fi

echo -n "Licenses dir: `ez_color_dir $LICENSES_DIR`"

#
# *****   Copy the distribution files (using SVN property)   *****
#

if [ -z "$SKIP_CORE_FILES" ]; then
    echo
    echo "Copying directories and files"
    echo -n "`$SETCOLOR_COMMENT`Copying`$SETCOLOR_NORMAL` "

    (cd $DIST_SRC && scan_dir .)
    echo
    echo
fi

#
# *****   Copy the test framework   *****
#

if [ -z "$SKIP_TEST_FRAMEWORK" ]; then
    if [ "$DEVELOPMENT" == "true" ]; then
        echo -n "Copying `ez_color_em UnitTest` framework"
        if [ "$SVN_EXPORT" == "svn" ]; then
            svn export "$CURRENT_URL/tests" "$DEST/tests" &>.export.log
        else
            svn export "tests" "$DEST/tests" &>.export.log
        fi
        ez_result_file $? .export.log || exit 1
        rm .export.log
    fi
fi

#
# *****   Copy the current features docs   *****
#

if [ -z "$SKIP_TEST_FRAMEWORK" ]; then
    if [ "$DEVELOPMENT" == "true" ]; then
        if [ -d "doc/features/$VERSION_ONLY" ]; then
            echo -n "Copying `ez_color_em feature docs` framework"
            mkdir -p "$DEST/doc/features"
            if [ "$SVN_EXPORT" == "svn" ]; then
                svn export "$CURRENT_URL/doc/features/$VERSION_ONLY" "$DEST/doc/features/$VERSION_ONLY" &>.export.log
            else
                svn export "doc/features/$VERSION_ONLY" "$DEST/doc/features/$VERSION_ONLY" &>.export.log
            fi
            ez_result_file $? .export.log || exit 1
            rm .export.log
        fi
    fi
fi

#
# *****   Check for missing settings files   *****
#

if [ -z "$SKIP_CORE_FILES" ]; then
    echo -n "Examining `ez_color_dir settings` directory"
    for settingsfile in settings/*; do
        if [ -d "$settingsfile" ]; then
            continue
        fi
        if echo $settingsfile | grep -E "^.*~$" &>/dev/null; then
            continue
        fi
        file=`echo $settingsfile | sed 's#^settings/##'`
        if [ ! -f "$DEST/$settingsfile" ]; then
            MISSING_FILES="$MISSING_FILES $settingsfile"
        fi
    done

    if [ -n "$MISSING_FILES" ]; then
        ez_result_output 1 ""
        echo "Some files are missing in the created distribution"
        echo "You should make sure the files have proper distribution properties set"
        echo
        echo "The files are:"
        for file in $MISSING_FILES; do
            echo "`$SETCOLOR_FILE`$file`$SETCOLOR_NORMAL`"
        done
        exit 1
    fi
    ez_result_output 0 ""
fi

# Make sure share directory exists
mkdir -p "$DEST/share"

#
# *****   Copy translations (SVN or WC)   *****
#

# Make sure share directory exists
mkdir -p "$DEST/share"

echo -n "Exporting translations"
rm -rf "$DEST/share/translations"
#if [ "$SVN_EXPORT" == "svn" ]; then
#    svn export "$TRANSLATION_URL" "$DEST/share/translations" &>/dev/null
#    ez_result_output $? "
#svn export $TRANSLATION_URL $DEST/share/translations &>/dev/null
#Failed to check out translations from svn" || exit 1
#else
    svn export "share/translations" "$DEST/share/translations" &>/dev/null
    ez_result_output $? "
svn export share/translations $DEST/share/translations &>/dev/null
Failed to check out translations from WC" || exit 1
#fi

dir=`pwd`
# We do not validate the translations if SKIPTRANSLATION is set
if [ -z "$SKIPTRANSLATION" ]; then
    cp -R -f $DEST/share/translations $DEST/share/translations.org &>/dev/null
    if [ $? -ne 0 ]; then
        echo "Failed to make copy of translations"
        exit 1
    fi
    TR_COUNTER=0
    TR_TOTAL=0
    last_len=0
    echo -n "Processing: "
    cd $DEST/share/translations
    echo -n "`ez_store_pos`"
    translations=""
    for translation in *; do
        if echo "$translation" | grep -E '^([a-zA-Z][a-zA-Z][a-zA-Z]-[a-zA-Z][a-zA-Z]|untranslated)$' &>/dev/null; then
            translations="$translations $translation"
            TR_TOTAL=`expr $TR_TOTAL + 1`
        fi
    done
    for translation in $translations; do
        TR_COUNTER=`expr $TR_COUNTER + 1`
        current_len=${#translation}
        if [ $last_len -eq 0 ]; then
            last_len=$current_len
        fi
        text="$translation"
        iterator=$current_len
        while [ $iterator -lt $last_len ]; do
            text="$text "
            iterator=`expr $iterator + 1`
        done
        echo -n "`ez_restore_pos``ez_store_pos`[$TR_COUNTER/$TR_TOTAL] `$SETCOLOR_EMPHASIZE`$text`$SETCOLOR_NORMAL`"

        if [ -z $SKIPTRANSLATION ]; then
            if [ "$translation" == "untranslated" ]; then
                (cd  $DEST && $dir/bin/linux/ezlupdate -u -d "$dir/design" &>/dev/null )
                if [ $? -ne 0 ]; then
                    ez_result_output 1 "Error updating translations for untranslated" || exit 1
                fi
            else
                (cd  $DEST && $dir/bin/linux/ezlupdate "$translation" -d "$dir/design" &>/dev/null )
                if [ $? -ne 0 ]; then
                    ez_result_output 1 "Error updating translations for $translation" || exit 1
                fi
            fi
        fi
        echo -n "`ez_restore_pos``ez_store_pos`[$TR_COUNTER/$TR_TOTAL] `$SETCOLOR_COMMENT`$text`$SETCOLOR_NORMAL`"
        last_len=$current_len
    done
    cd $dir
    text=""
    iterator=0
    while [ $iterator -lt $last_len ]; do
        text="$text "
        iterator=`expr $iterator + 1`
    done
    echo -n "`ez_restore_pos``ez_store_pos`$text"
    echo -n "`ez_restore_pos`[$TR_TOTAL/$TR_TOTAL] translations"
    ez_result_output 0 "" || exit 1

    echo -n "Validating"
    diff -U3 -r $DEST/share/translations.org $DEST/share/translations &>/dev/null
    ez_result_output $? "The translations are not up to date
You must update the translations in the repository using the ezlupdate program or with bin/shell/updatetranslations.sh" || exit 1

    rm -rf $DEST/share/translations.org

    echo -n "Removing obsolete strings: "
    cd $DEST/share/translations
    TR_COUNTER=0
    last_len=0
    echo -n "`ez_store_pos`"
    for translation in $translations; do
        TR_COUNTER=`expr $TR_COUNTER + 1`
        current_len=${#translation}
        if [ $last_len -eq 0 ]; then
            last_len=$current_len
        fi
        text="$translation"
        iterator=$current_len
        while [ $iterator -lt $last_len ]; do
            text="$text "
            iterator=`expr $iterator + 1`
        done
        echo -n "`ez_restore_pos``ez_store_pos`[$TR_COUNTER/$TR_TOTAL] `$SETCOLOR_EMPHASIZE`$text`$SETCOLOR_NORMAL`"
        if [ "$translation" == "untranslated" ]; then
            (cd  $DEST && $dir/bin/linux/ezlupdate -no -u -d "$dir/design" &>/dev/null)
            if [ $? -ne 0 ]; then
                ez_result_output 1 "Error removing obsolete entries for untranslated" || exit 1
            fi
        else
            (cd  $DEST && $dir/bin/linux/ezlupdate -no "$translation" -d "$dir/design" &>/dev/null)
            if [ $? -ne 0 ]; then
                ez_result_output 1 "Error removing obsolete entries for $translation" || exit 1
            fi
        fi
        echo -n "`ez_restore_pos``ez_store_pos`[$TR_COUNTER/$TR_TOTAL] `$SETCOLOR_COMMENT`$text`$SETCOLOR_NORMAL`"
        last_len=$current_len
    done
    cd $dir

    text=""
    iterator=0
    while [ $iterator -lt $last_len ]; do
        text="$text "
        iterator=`expr $iterator + 1`
    done
    echo -n "`ez_restore_pos`[$TR_COUNTER/$TR_TOTAL] $text"
    ez_result_output 0 "" || exit 1
fi

echo -n "Exporting locales"
rm -rf "$DEST/share/locale"
#if [ "$SVN_EXPORT" == "svn" ]; then
#    svn export "$LOCALE_URL" "$DEST/share/locale" &>/dev/null
#    ez_result_output $? "
#svn export $LOCALE_URL $DEST/share/locale &>/dev/null
#Failed to check out locale from trunk" || exit 1
#else
    svn export "share/locale" "$DEST/share/locale" &>/dev/null
    ez_result_output $? "
svn export share/locale $DEST/share/locale &>/dev/null
Failed to check out locale from WC" || exit 1
#fi

#
# *****   Handle changelogs from earlier versions   *****
#

if [ -z "$SKIP_CHANGELOGS" -a "$SVN_EXPORT" == "svn" ]; then
    echo -n "Changelogs:"
    for version in $STABLE_VERSIONS; do
#        changelog_url="$REPOSITORY_BASE_URL/$REPOSITORY_STABLE_BRANCH_PATH/$version/doc/changelogs/$version"
        changelog_url="doc/changelogs/$version"
        rm -rf "$DEST/doc/changelogs/$version"
        echo -n " `ez_store_pos`$version"
        svn export "$changelog_url" "$DEST/doc/changelogs/$version" &>/dev/null
        if [ $? -ne 0 ]; then
            ez_result_output 1 "Failed to check out changelogs for version `$SETCOLOR_EMPHASIZE`$version`$SETCOLOR_NORMAL`"
            exit 1
        fi
        echo -n "`ez_restore_pos``$SETCOLOR_EMPHASIZE`$version`$SETCOLOR_NORMAL`"
    done
    ez_result_output 0 ""
elif [ "$SVN_EXPORT" != "svn" ]; then
    echo -n "Changelogs:"
    ez_result_output_skipped
fi

#
# *****   Generate SQL files   *****
#

if [ -z "$SKIP_SQL_GENERATION" ]; then
    echo -n "Updating MySQL SQL schema"
    ./bin/php/ezsqldumpschema.php --type=mysql --compatible-sql --table-type=innodb --output-sql --format=local "share/db_schema.dba" "$DEST/kernel/sql/mysql/kernel_schema.sql" 2>.dump.log
    ez_result_file $? .dump.log || exit 1

    echo -n "Updating PostgreSQL SQL schema"
    ./bin/php/ezsqldumpschema.php --type=postgresql --compatible-sql --output-sql --format=local "share/db_schema.dba" "$DEST/kernel/sql/postgresql/kernel_schema.sql" 2>.dump.log
    ez_result_file $? .dump.log || exit 1

    echo -n "Updating generic cleandata SQL"
    ./bin/php/ezsqldumpschema.php --type=mysql --compatible-sql --output-sql --format=generic --output-types=data --schema-file="share/db_schema.dba" "share/db_data.dba" "$DEST/kernel/sql/common/cleandata.sql" 2>.dump.log
    ez_result_file $? .dump.log || exit 1

    echo -n "Updating MySQL cleandata SQL"
    ./bin/php/ezsqldumpschema.php --type=mysql --compatible-sql --output-sql --allow-multi-insert --format=local --output-types=data --schema-file="share/db_schema.dba" "share/db_data.dba" "$DEST/kernel/sql/mysql/cleandata.sql" 2>.dump.log
    ez_result_file $? .dump.log || exit 1

    echo -n "Updating PostgreSQL cleandata SQL"
    ./bin/php/ezsqldumpschema.php --type=postgresql --compatible-sql --output-sql --format=local --output-types=data --schema-file="share/db_schema.dba" "share/db_data.dba" "$DEST/kernel/sql/postgresql/cleandata.sql" 2>.dump.log
    ez_result_file $? .dump.log || exit 1
fi


#
# *****   Create some directories which should be present   *****
#

echo -n "Extra directories"
EXTRA_DIRS=""
if [ "$DIST_TYPE" == "sdk" ]; then
    EXTRA_DIRS=$SDK_EXTRA_DIRS
else
    EXTRA_DIRS=$FULL_EXTRA_DIRS
fi

for file in $EXTRA_DIRS; do
    mkdir -p "$DEST/$file"
    if [ $? -ne 0 ]; then
        ez_result_file 1 "Failed to create directory $DEST/$file"
        exit 1
    fi
done
ez_result_file 0 ""

#
# *****   Handles addons and styles   *****
#

#if [ -z $SKIPSITECREATION ]; then
#    echo
#    echo "Creating and exporting sites"
#    rm -rf "$DEST/kernel/setup/packages"
#    mkdir -p "$DEST/kernel/setup/packages" || exit 1
#    echo -n "Site:"
#    for site in $ALL_PACKAGES; do
#       echo -n " `ez_store_pos`$site"
#       ./bin/shell/makesitepackages.sh -q --export-path="$DEST/kernel/setup/packages" --site=$site
#       if [ $? -ne 0 ]; then
#           echo
#           echo "The package creation of $site failed"
#           echo "Run the following command to see what went wrong"
#           echo "./bin/shell/makesitepackages.sh --site=$site"
#           exit 1
#       else
#           echo -n "`ez_restore_pos``$SETCOLOR_EMPHASIZE`$site`$SETCOLOR_NORMAL`"
#       fi
#    done
#fi
#echo

#if [ -z $SKIPADDONCREATION ]; then
#   echo -n "Creating and exporting addons"
#   rm -rf "$DEST/packages/addons"
#   mkdir -p "$DEST/packages/addons" || exit 1
#   ezdist_db_prepare_params_from_mysql
#   ezdist_is_nonempty "$DIST_DB_NAME" && PARAM_EZ_DB_NAME="--db-name=$DIST_DB_NAME"
#   ./bin/shell/makeaddonpackages.sh -q --export-path="$DEST/packages/addons" --db-type=mysql $PARAM_EZ_DB_NAME $PARAM_EZ_DB_ALL
#   ez_result_output $? "The creation of addon packages failed
#Run the following command to see what went wrong
#./bin/shell/makeaddonpackages.sh --export-path=\"$DEST/packages/addons\" --db-type=mysql $PARAM_EZ_DB_NAME $PARAM_EZ_DB_ALL" || exit 1
#fi

#if [ -z $SKIPSTYLECREATION ]; then
#   echo -n "Creating and exporting styles"
#   rm -rf "$DEST/packages/styles"
#   mkdir -p "$DEST/packages/styles" || exit 1
#   ./bin/shell/makestylepackages.sh -q --export-path="$DEST/packages/styles"
#   ez_result_output $? "The creation of the style packages failed
#Run the following command to see what went wrong
#./bin/shell/makestylepackages.sh --export-path=\"$DEST/packages/styles\"" || exit 1
#fi


#
# *****   Remove some comments in old SQL files *****
#


if [ -z "$SKIP_CORE_FILES" ]; then
    echo -n "Applying filters"
    for filter in $FILTER_FILES; do
        cat "$DEST/$filter" | sed 's,^#!\(.*\)$,\1,' | grep -v '^..*##!' > "$DEST/$filter.tmp" && mv -f "$DEST/$filter.tmp" "$DEST/$filter"
        if [ $? -ne 0 ]; then
            ez_result_output 1 "Failed to filter $DEST/$filter"
            exit 1
        fi
    done

    for filter in $FILTER_FILES2; do
        cat "$DEST/$filter" | sed 's,^##!\(.*\)$,\1,' | grep -v '^..*##!' > "$DEST/$filter.tmp" && mv -f "$DEST/$filter.tmp" "$DEST/$filter"
        if [ $? -ne 0 ]; then
            ez_result_output 1 "Failed to filter $DEST/$filter"
            exit 1
        fi
    done
    ez_result_output 0 ""

    echo -n "Checking `$SETCOLOR_EMPHASIZE`SQL`$SETCOLOR_COMMENT` files for correctness"

    function scan_sql_file()
    {
        sqlfile=$1
        grep -n -H -i -E -q '(^--.*$)|(^#.*$)|(^/\*.*\*/$)' $sqlfile
        if [ $? -eq 0 ]; then
            return 1
        else
            return 0
        fi
    }

    function cleanup_sql_file()
    {
        sqlfile=$1
        perl -pi -e "s%(^--.*$)|(^#.*$)|(^/\*.*\*/$)%%g" $sqlfile
    }

    MAJOR_VERSIONS="3.0"
    SQL_DIRS=""
    KERNEL_SQL_DIR="kernel/sql"
    UPDATE_SQL_DIR="update/database"
    DATABASES="mysql postgresql"

    for database in $DATABASES; do
        for major_version in $MAJOR_VERSIONS; do
            SQL_DIRS="$SQL_DIRS $UPDATE_SQL_DIR/$database/$major_version"
        done
    done


    for database in $DATABASES; do
        SQL_DIRS="$SQL_DIRS $KERNEL_SQL_DIR/$database"
    done

    BAD_SQL_FILES=""

    for sql_dir in $SQL_DIRS; do
        if [ -d "$DEST/$sql_dir" ]; then
            for sql_file in `(cd $DEST && ls $sql_dir/*.sql)`; do
                scan_sql_file $DEST/$sql_file
                if [ $? -ne 0 ]; then
                    BAD_SQL_FILES="$BAD_SQL_FILES $sql_file"
                fi
            done
        fi
    done

    if [ "$BAD_SQL_FILES" != "" ]; then
        ez_result_output 1 "`$SETCOLOR_EMPHASIZE`The following sql files has comments in them and should be fixed.`$SETCOLOR_NORMAL`
`$SETCOLOR_DIR`$BAD_SQL_FILES`$SETCOLOR_NORMAL`"
        read -p "`$SETCOLOR_EMPHASIZE`Do want to fix this for the release?`$SETCOLOR_NORMAL` Yes/No [N]" FIX_SQL
        if [ "$FIX_SQL" == "" ]; then
            FIX_SQL="N"
        fi
        case "$FIX_SQL" in
            Y|y|Yes|yes|YES)
                for bad_sql_file in $BAD_SQL_FILES; do
                    echo -n "Fixing `$SETCOLOR_FILE`$DEST/$bad_sql_file`$SETCOLOR_NORMAL`"
                    cleanup_sql_file "$DEST/$bad_sql_file"
                    ez_result_output $? "Failed to fix comments in $DEST/$bad_sql_file" || exit 1
                done
                ;;
            *)
                echo "You will have to fix the sql files manually before creating the distribution."
                exit 1
                ;;
        esac
    else
        ez_result_output 0 ""
    fi
fi


# cat index.php | sed 's/index.php/index_sdk.php/' > $DEST/index_sdk.php
# cp -f index.php $DEST/index.php

echo -n "Looking for `$SETCOLOR_DIR`.svn`$SETCOLOR_NORMAL` directories"
(cd $DEST
    find . -name .svn -print &> .find.log
    if [ $? -ne 0 ]; then
        ez_result_output 1 "The following .svn directories was found"
        cat .find.log
        rm .find.log
        exit 1
    fi
    ez_result_output 0 ""
    rm .find.log ) || exit 1

echo -n "Looking for `$SETCOLOR_COMMENT`temp`$SETCOLOR_NORMAL` files"
(cd $DEST
    TEMPFILES=`find . -name '*[~#]' -print`
    echo $TEMPFILES | grep -e '[~#]' -q
    if [ $? -eq 0 ]; then
        ez_result_output 1 "Cannot create distribution, the following temporary files were found:"
        for tempfile in $TEMPFILES; do
            echo "`$SETCOLOR_FAILURE`$tempfile`$SETCOLOR_NORMAL`"
        done
        echo "The files must be removed before the distribution can be made"
        exit 1
    fi
    ez_result_output 0 "" ) || exit 1

if [ -z "$SKIP_CORE_FILES" ]; then
    if [ -f $DEST/bin/modfix.sh ]; then
        echo -n "Applying `$SETCOLOR_EXE`executable`$SETCOLOR_NORMAL` properties"
        (cd $DEST/bin
            chmod a+x modfix.sh
            ez_result_output $? "Failed to apply executable property to modifix.sh" || exit 1 ) || exit 1
    fi
fi

if [ ! -d "$DEST/extension" ]; then
    (cd $DEST && mkdir extension)
fi

#
# *****   Remove some elements which should not be in dist   *****
#

echo -n "Removing non-distribution files"
if [ -d $DEST/kernel/sql/oracle ]; then
    rm -rf "$DEST/kernel/sql/oracle"
    if [ $? -ne 0 ]; then
        ez_result_output 1 "Failed to remove `ez_color_dir $DEST/kernel/sql/oracle`"
        exit 1
    fi
fi

if [ -f $DEST/kernel/sql/mysql/doc.sql ]; then
    rm -f "$DEST/kernel/sql/mysql/doc.sql"
    if [ $? -ne 0 ]; then
        ez_result_output 1 "Failed to remove `ez_color_file $DEST/kernel/sql/mysql/doc.sql`"
        exit 1
    fi
fi

if [ -f $DEST/kernel/sql/postgresql/doc.sql ]; then
    rm -f "$DEST/kernel/sql/postgresql/doc.sql"
    if [ $? -ne 0 ]; then
        ez_result_output 1 "Failed to remove `ez_color_file $DEST/kernel/sql/postgresql/doc.sql`"
        exit 1
    fi
fi

if [ -f $DEST/support/ezlupdate/Makefile ]; then
    (cd "$DEST/support/ezlupdate" && \
        qmake &>/dev/null && \
        make clean &>/dev/null && \
        rm -rf Makefile moc obj)
    if [ $? -ne 0 ]; then
        ez_result_output 1 "Failed to cleanup ezlupdate temporary files in `ez_color_dir $DEST/support/ezlupdate`"
        exit 1
    fi
fi


ez_result_output 0 ""

# Remove old archives
if [ -f "$DEST_ROOT/$BASE.tar.gz" ]; then
    rm -f "$DEST_ROOT/$BASE.tar.gz";
fi

if [ -f "$DEST_ROOT/$BASE.tar.bz2" ]; then
    rm -f "$DEST_ROOT/$BASE.tar.bz2";
fi

if [ -f "$DEST_ROOT/$BASE.zip" ]; then
    rm -f "$DEST_ROOT/$BASE.zip";
fi

#
# *****   Build package files for extensions *****
#

if [ -z "$SKIP_EXTENSIONS" -a "$SVN_EXPORT" == "svn" ]; then
    echo
    echo "`ez_color_h1 'Building extension packages'`"

    EXTENSION_FILES=""
    for extension in $EXTENSIONS; do
        touch .ez.extension-name .ez.extension-id
        # FIXME: make an option to (not) use SVN when exporting extensions
        [ "$BUILD_SNAPSHOT" = 1 ] && build_number_opt="--build-number=$CURRENT_BUILD_NUMBER"
        [ "$SVN_EXPORT" == "svn" ] && use_svn_opt='--svn'
        bin/shell/packext.sh $use_svn_opt --dist-type=$DIST_TYPE --output-dir=$DEST_EXTENSION_ARCHIVE $build_number_opt $extension
        if [ $? -ne 0 ]; then
            echo "Failed to build extension $extension"
            exit 1
        fi

        # Store extension's archive name
        EXTENSION_IDENTIFIER=""
        EXTENSION_TGZFILE=""
        [ -s .ez.extension-id ] && EXTENSION_IDENTIFIER=`cat .ez.extension-id`
        [ -s .ez.extension-name ] && EXTENSION_TGZFILE=`cat .ez.extension-name`

        #
        # *****   Unpack current extension as part of standard dist   *****
        #

        EXTENSION_ARCHIVE="$DEST_EXTENSION_ARCHIVE/$EXTENSION_TGZFILE"
        echo -n "Unpacking `ez_color_em $EXTENSION_IDENTIFIER` extension"
        (cd "$DEST/extension" && tar xfz "$EXTENSION_ARCHIVE")
        ez_result_output $? "Failed to unpack $EXTENSION_ARCHIVE" || exit 1

        [ -s .ez.extension-name ] && EXTENSION_FILES="$EXTENSION_FILES `cat .ez.extension-name`"
        rm -f .ez.extension-name .ez.extension-id
    done
elif [ "$SVN_EXPORT" != "svn" ]; then
    echo
    echo -n "`ez_color_h1 'Building extension packages'`"
    ez_result_output_skipped
fi

#
# *****   Update license file and php's headers   *****
#

if [ -z $SKIP_LICENSE_UPDATE ];then
    echo -n "Applying '$LICENSE_TYPE' license"

    if [ "$SVN_EXPORT" == "svn" ]; then
        rm -rf "$LICENSES_DIR"
        svn export "$LICENSES_SVN_SERVER" "$LICENSES_DIR" &>.export.log
        ez_result_file $? .export.log || exit 1
        rm .export.log
    fi

    update_license

    ez_result_output $? "Failed to update license"|| exit 1
fi

#
# *****   Create MD5 check sums   *****
#

if [ -z "$SKIP_CORE_FILES" ]; then
    echo -n "Creating MD5 checksums"
    (cd $DEST
       MD5_FILES=`find * -name "*.php" -or -name "*.ini" -or -name "*.sh" -or -name "*.sql"`

       for MD5_FILE in $MD5_FILES; do
           md5sum $MD5_FILE >> share/filelist.md5
       done

       MD5_FILES=`find design/* -name "*.tpl"`

       for MD5_FILE in $MD5_FILES; do
           md5sum $MD5_FILE >> share/filelist.md5

       done
    )
    ez_result_output $? "Failed to create MD5 checksums"|| exit 1
fi

#
# *****   Create archives   *****
#

TGZFILE="$BASE-$LICENSE_TYPE.tar.gz"
TBZFILE="$BASE-$LICENSE_TYPE.tar.bz2"
ZIPFILE="$BASE-$LICENSE_TYPE.zip"

echo -n "Creating `$SETCOLOR_FILE`tar.gz`$SETCOLOR_NORMAL` file"
(cd $DEST_ROOT
    tar cfz $TGZFILE $BASE
    echo ",  `$SETCOLOR_EMPHASIZE`$DEST_ROOT/$TGZFILE`$SETCOLOR_NORMAL`")

echo -n "Creating `$SETCOLOR_FILE`tar.bz2`$SETCOLOR_NORMAL` file"
(cd $DEST_ROOT
    tar cf $BASE-$LICENSE_TYPE.tar $BASE
    if [ -f $TBZFILE ]; then
	rm -f $TBZFILE
    fi
    bzip2 $BASE-$LICENSE_TYPE.tar
    echo ", `$SETCOLOR_EMPHASIZE`$DEST_ROOT/$TBZFILE`$SETCOLOR_NORMAL`")

if [ "which zip &>/dev/null" ]; then
    echo -n "Creating `$SETCOLOR_FILE`zip`$SETCOLOR_NORMAL` file"
    (cd $DEST_ROOT
        zip -9 -r -q $ZIPFILE $BASE
        echo ",     `$SETCOLOR_EMPHASIZE`$DEST_ROOT/$ZIPFILE`$SETCOLOR_NORMAL`")
else
    echo "`SETCOLOR_WARNING`Could not create `$SETCOLOR_FILE`zip`$SETCOLOR_WARNING` file, `$SETCOLOR_EXE`zip`$SETCOLOR_NORMAL` program not found.`SETCOLOR_NORMAL`"
fi

if [ -z "$SKIP_EXTENSIONS" ]; then
    echo
    if [ -n "EXTENSION_FILES" ]; then
        echo "The following extensions have been packaged:"
        for extension_file in $EXTENSION_FILES; do
            echo "`ez_color_em $DEST_EXTENSION_ARCHIVE/$extension_file`"
        done
        echo
    fi
fi

if [ "$BUILD_SNAPSHOT" == "1" ]; then
    DISTROOT="$HOME/ezpublish-dist"
    VERSIONROOT="$DISTROOT/$VERSION_ONLY/$VERSION/builds/build-$CURRENT_BUILD_NUMBER"
    mkdir -p $VERSIONROOT

    echo "Copying `$SETCOLOR_FILE`$TGZFILE`$SETCOLOR_NORMAL` to `$SETCOLOR_DIR`$VERSIONROOT`$SETCOLOR_NORMAL`"
    cp "$DEST_ROOT/$TGZFILE" "$VERSIONROOT/"
    if [ ! -f "$VERSIONROOT/filelist.md5" ]; then
	touch "$VERSIONROOT/filelist.md5"
    fi
    (cd "$VERSIONROOT/"; md5sum -b "$TGZFILE" >> filelist.md5)

    if [ -z "$SKIP_EXTENSIONS" ]; then
        EXTENSION_VERSIONROOT="$VERSIONROOT/extensions"
        if [ ! -d "$EXTENSION_VERSIONROOT" ]; then
            mkdir -p "$EXTENSION_VERSIONROOT"
        fi
        for extension_file in $EXTENSION_FILES; do
            echo "Copying `ez_color_file $extension_file` to `ez_color_dir $EXTENSION_VERSIONROOT`"
            cp "$DEST_EXTENSION_ARCHIVE/$extension_file" "$EXTENSION_VERSIONROOT/"
            if [ ! -f "$VERSIONROOT/filelist.md5" ]; then
                touch "$VERSIONROOT/filelist.md5"
            fi
            (cd "$VERSIONROOT/"; md5sum -b "extensions/$extension_file" >> filelist.md5)
        done
    fi

    echo
    echo -n "Do you wish to add some comments on the build? (yes/No)? "
    read add_comment
    add_comment=`echo $add_comment | tr 'A-Z' 'a-z'`
    if [ "$add_comment" == "" ]; then
        add_comment="n"
    fi
    case "$add_comment" in
        y|yes)
            $EDITOR "$VERSIONROOT/$TGZFILE"".summary"
            ;;
    esac

fi
if [ -n "$FINAL" ]; then
    DISTROOT="$HOME/ezpublish-dist"
    VERSIONROOT="$DISTROOT/$VERSION_ONLY/$VERSION"
    mkdir -p $VERSIONROOT
    if [ ! -f "$VERSIONROOT/filelist.md5" ]; then
	touch "$VERSIONROOT/filelist.md5"
    fi
    echo "Archiving files to directory `$SETCOLOR_DIR`$VERSIONROOT`$SETCOLOR_NORMAL`"
    cp "$DEST_ROOT/$TGZFILE" "$VERSIONROOT/"
    (cd "$VERSIONROOT/"; md5sum -b "$TGZFILE" >> filelist.md5)
    echo "Copied `$SETCOLOR_FILE`$TGZFILE`$SETCOLOR_NORMAL`"
    cp "$DEST_ROOT/$TBZFILE" "$VERSIONROOT/"
    (cd "$VERSIONROOT/"; md5sum -b "$TBZFILE" >> filelist.md5)
    echo "Copied `$SETCOLOR_FILE`$TBZFILE`$SETCOLOR_NORMAL`"
    cp "$DEST_ROOT/$ZIPFILE" "$VERSIONROOT/"
    (cd "$VERSIONROOT/"; md5sum -b "$ZIPFILE" >> filelist.md5)
    echo "Copied `$SETCOLOR_FILE`$ZIPFILE`$SETCOLOR_NORMAL`"

    if [ -z "$SKIP_EXTENSIONS" ]; then
        EXTENSION_VERSIONROOT="$VERSIONROOT/extensions"
        if [ ! -d "$EXTENSION_VERSIONROOT" ]; then
            mkdir -p "$EXTENSION_VERSIONROOT"
        fi
        for extension_file in $EXTENSION_FILES; do
            echo "Copying `ez_color_file $extension_file` to `ez_color_dir $EXTENSION_VERSIONROOT`"
            cp "$DEST_EXTENSION_ARCHIVE/$extension_file" "$EXTENSION_VERSIONROOT/"
            if [ ! -f "$VERSIONROOT/filelist.md5" ]; then
                touch "$VERSIONROOT/filelist.md5"
            fi
            (cd "$VERSIONROOT/"; md5sum -b "extensions/$extension_file" >> filelist.md5)
        done
    fi

    CURRENT_SVN_PATH=`svn info | grep 'URL:' | sed 's/URL: //'`

    echo
    echo "The following command will be run to tag the release"
    echo "`$SETCOLOR_EMPHASIZE`svn cp $CURRENT_SVN_PATH $DEFAULT_SVN_SERVER/$DEFAULT_SVN_VERSION_PATH/$VERSION`$SETCOLOR_NORMAL`"
    echo -n "Do you wish to do this (yes/No)? "
    read tag_release
    tag_release=`echo $tag_release | tr [A-Z] [a-z]`
    if [ "$tag_release" == "yes" ]; then
        tag_release="y"
    fi
    if [ "$tag_release" == "y" ]; then
	svn cp $CURRENT_SVN_PATH $DEFAULT_SVN_SERVER/$DEFAULT_SVN_VERSION_PATH/$VERSION
    fi

    current_date=`date '+%e-%b-%Y'`
    from_rev=`svn info | grep 'Revision:' | sed 's/Revision: //'`
    echo "The following must be added to the doc/release-overview in trunk"
    echo
    echo "$VERSION:"
    echo "- From revision=$from_rev"
    echo "- Version url=$DEFAULT_SVN_SERVER/$DEFAULT_SVN_VERSION_PATH/$VERSION"
    echo "- Date $current_date"
    echo
    echo "You should also note the revision from the tag above, it should say"
    echo "- Revision=REV"
    echo "Where REV is the revision number"
fi

ezdist_write_cache_file

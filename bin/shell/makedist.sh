#!/bin/bash

DIST_PROP="ez:distribution"
DIST_DIR_PROP="ez:distribution_include_all"
DIST_TYPE='full'
NAME="ezpublish"
DEST_ROOT="/tmp/ez-$USER"
DEFAULT_SVN_SERVER="http://zev.ez.no/svn/nextgen"
DEFAULT_SVN_RELEASE_PATH="releases"
DEFAULT_SVN_VERSION_PATH="versions"
DIST_SRC=`pwd`
EDITOR='vi'

FULL_EXTRA_DIRS="settings/override var/cache var/storage"
SDK_EXTRA_DIRS="settings/override var/carhe var/storage doc/generated/html"

FILTER_FILES="settings/site.ini settings/content.ini settings/setup.ini settings/i18n.ini settings/layout.ini settings/template.ini settings/texttoimage.ini settings/units.ini settings/siteaccess/admin/site.ini.append settings/siteaccess/admin/override.ini.append settings/webdav.ini settings/image.ini"
FILTER_FILES2="bin/modfix.sh"
PACKAGE_DIR="packages"

BUILD_NUMBER=1
CACHE=".ezp.cache"

if [ -f $CACHE ]; then
    . $CACHE
fi

. ./bin/shell/common.sh
. ./bin/shell/packagescommon.sh
. ./bin/shell/extensionscommon.sh

if ! which php &>/dev/null; then
    echo "No PHP executable found, please add it to the path"
    exit 1
fi

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
	    if [ $? -eq 0 ] && [ ! -z "$DIST_PROP_TYPE" ]; then
		if echo $DIST_PROP_TYPE | grep $DIST_TYPE &>/dev/null; then
		    DIST_DIR=`svn propget $DIST_DIR_PROP $file 2>/dev/null`
		    DIST_DIR_RECURSIVE=""
		    if [ $? -eq 0 ] && [ ! -z "$DIST_DIR" ]; then
			if echo $DIST_DIR | grep $DIST_TYPE &>/dev/null; then
#			    echo "Found include all marker for $file"
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
		fi
	    fi
	fi
    done
}

SVN_SERVER=""
REPOS_RELEASE="trunk"

DB_USER="root"
DB_NAME="ez_tmp_makedist"
DB_SERVER="localhost"
DB_PASSWORD=""

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
# 	    echo "         --build-rc                 Make a release candidate, this will add a build number to the name"
	    echo "         --build-snapshot           Make a snapshot, this will add a build number to the name"
	    echo "         --with-svn-server[=SERVER] Checkout fresh repository"
	    echo "         --with-release=NAME        Checkout a previous release, default is trunk"
#	    echo "         --skip-site-creation       Do not build sites*"
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
	    echo "         --db-server=server         Mysql DB server ( default: localhost )"
            echo "         --db-user=user             Mysql DB user ( default: root )"
            echo "         --db-name=databasename     Mysql DB name ( default: ez_tmp_makedist )"
            echo "         --db-password=password     Mysql DB password ( default: <empty> )"
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
#	--build-rc)
#	    BUILD_RC="1"
#	    ;;
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
	--with-release*)
	    if echo $arg | grep -e "--with-release=" >/dev/null; then
		REPOS_RELEASE=`echo $arg | sed 's/--with-release=//'`
	    else
		REPOS_RELEASE="trunk"
	    fi
	    ;;
	--db-server*)
	    if echo $arg | grep -e "--db-server=" >/dev/null; then
		DB_SERVER=`echo $arg | sed 's/--db-server=//'`
	    fi
	    ;;
	--db-user*)
	    if echo $arg | grep -e "--db-user=" >/dev/null; then
		DB_USER=`echo $arg | sed 's/--db-user=//'`
	    fi
	    ;;
	--db-name*)
	    if echo $arg | grep -e "--db-name=" >/dev/null; then
		DB_NAME=`echo $arg | sed 's/--db-name=//'`
	    fi
	    ;;
	--db-password*)
	    if echo $arg | grep -e "--db-password=" >/dev/null; then
		DB_PASSWORD=`echo $arg | sed 's/--db-password=//'`
	    fi
	    ;;
#	--skip-site-creation)
#	    SKIPSITECREATION="1"
#	    ;;
	--skip-all-checks)
	    SKIPCHECKVERSION="1"
	    SKIPCHECKPHP="1"
	    SKIPDBSCHEMA="1"
	    SKIPDBCHECK="1"
	    SKIPDBUPDATE="1"
	    SKIPUNITTESTS="1"
	    SKIPTRANSLATION="1"
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
	*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
    esac;
done

BASE="$NAME-$DIST_TYPE-$VERSION"

if [ "$DIST_TYPE" == "sdk" ]; then
    echo "Creating SDK release"
elif [ "$DIST_TYPE" == "full" ]; then
    echo "`$SETCOLOR_EMPHASIZE`Creating full release`$SETCOLOR_NORMAL`"
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
    ./tests/testunits.php -q eztemplate ezxml
    if [ $? -ne 0 ]; then
	echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
	echo "Some unit tests failed"
	echo "Run the following command to find out which one failed"
	echo "./tests/testunits.php eztemplate ezxml"
	exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"
fi

if [ -z $SKIPDBCHECK ]; then
    echo -n "Checking database schemas"
    ./bin/shell/checkdbschema.sh "$DB_NAME" &>/dev/null
    if [ $? -ne 0 ]; then
	echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
	echo "The database schema check failed"
	echo "Run the following command to find out what is wrong"
	echo "./bin/shell/checkdbschema.sh $DB_NAME"
	exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"
fi

if [ -z $SKIPDBUPDATE ]; then
    echo -n "Checking MySQL database updates"
    ./bin/shell/checkdbupdate.sh --mysql "$DB_NAME" &>/dev/null
    if [ $? -ne 0 ]; then
	echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
	echo "The database update check for MySQL failed"
	echo "Run the following command to find out what is wrong"
	echo "./bin/shell/checkdbupdate.sh --mysql $DB_NAME"
	exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"

    echo -n "Checking PostgreSQL database updates"
    ./bin/shell/checkdbupdate.sh --postgresql "$DB_NAME" &>/dev/null
    if [ $? -ne 0 ]; then
	echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
	echo "The database update check for Postgresql failed"
	echo "Run the following command to find out what is wrong"
	echo "./bin/shell/checkdbupdate.sh --postgresql $DB_NAME"
	exit 1
    fi
    echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"
fi

echo
echo "Making distribution in `ez_color_dir $DEST`"

if [ -d $DEST ]; then
    echo "`$SETCOLOR_COMMENT`Removing old distribution`$SETCOLOR_NORMAL`"
    rm -rf $DEST
    mkdir -p $DEST
else
    echo "`$SETCOLOR_NEW`Creating distribution directory`$SETCOLOR_NEW`"
    mkdir -p $DEST
fi

echo "Making extensions in `ez_color_dir $DEST_EXTENSION_ARCHIVE`"

if [ -d "$DEST_EXTENSION_ARCHIVE" ]; then
    echo "`$SETCOLOR_COMMENT`Removing old extension archive`$SETCOLOR_NORMAL`"
    rm -rf "$DEST_EXTENSION_ARCHIVE"
    mkdir -p "$DEST_EXTENSION_ARCHIVE"
else
    echo "`$SETCOLOR_NEW`Creating extension archive`$SETCOLOR_NEW`"
    mkdir -p "$DEST_EXTENSION_ARCHIVE"
fi

#
# *****   Copy the distribution files (using SVN property)   *****
#

if [ -z "$SKIP_CORE_FILES" ]; then
    echo
    echo "Copying directories and files"
    echo -n "`$SETCOLOR_COMMENT`Copying`$SETCOLOR_NORMAL` "

    (cd $DIST_SRC && scan_dir .)
    echo
fi

#
# *****   Copy the test framework   *****
#

if [ -z "$SKIP_TEST_FRAMEWORK" ]; then
    if [ "$DEVELOPMENT" == "true" ]; then
	echo
	echo "Copying test framework"
	svn export "$CURRENT_URL/tests" "$DEST/tests" &>/dev/null
	echo
    fi
fi

#
# *****   Check for missing settings files   *****
#

if [ -z "$SKIP_CORE_FILES" ]; then
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
	echo
	echo "Some files are missing in the created distribution"
	echo "You should make sure the files have proper distribution properties set"
	echo
	echo "The files are:"
	for file in $MISSING_FILES; do
	    echo "`$SETCOLOR_FILE`$file`$SETCOLOR_NORMAL`"
	done
	exit 1
    fi
fi

#
# *****   Handle translations and locale   *****
#

if [ ! -f bin/linux/ezlupdate ]; then
    echo "You do not have the ezlupdate program compiled"
    echo "this is required to create a distribution"
    echo
    echo "cd support/lupdate-ezpublish3"
    echo "qmake ezlupdate.pro"
    echo "make"
    echo
    echo "NOTE: qmake may in some cases not be in PATH, provide the full path in those cases"
    exit 1
fi

echo
echo "Copying translations and locale"
rm -rf "$DEST/share/translations"
echo -n "`$POSITION_STORE`translations"
svn export "$TRANSLATION_URL" "$DEST/share/translations" &>/dev/null
if [ $? -ne 0 ]; then
    echo
    echo "svn export $TRANSLATION_URL $DEST/share/translations &>/dev/null"
    echo "Failed to check out translations from trunk"
    exit 1
fi
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`translations`$SETCOLOR_NORMAL`"

rm -rf "$DEST/share/locale"
echo -n " `$POSITION_STORE`locale"
svn export "$LOCALE_URL" "$DEST/share/locale" &>/dev/null
if [ $? -ne 0 ]; then
    echo
    echo "svn export $LOCALE_URL $DEST/share/locale &>/dev/null"
    echo "Failed to check out locale from trunk"
    exit 1
fi
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`locale`$SETCOLOR_NORMAL`"
echo

dir=`pwd`
# We do not validate the translations if SKIPTRANSLATION is set
if [ -z "$SKIPTRANSLATION" ]; then
    cp -R -f $DEST/share/translations $DEST/share/translations.org &>/dev/null
    if [ $? -ne 0 ]; then
	echo "Failed to make copy of translations"
	exit 1
    fi
    echo -n "Processing:"
    cd $DEST/share/translations
    for translation in *; do
	echo -n " `$POSITION_STORE``$SETCOLOR_EMPHASIZE`$translation`$SETCOLOR_NORMAL`"

	if [ -z $SKIPTRANSLATION ]; then
	    if [ "$translation" == "untranslated" ]; then
		(cd  $DEST && $dir/bin/linux/ezlupdate -u -d "$dir/design" &>/dev/null )
		if [ $? -ne 0 ]; then
		    echo
		    echo "Error updating translations"
		    exit 1
		fi
	    else
		(cd  $DEST && $dir/bin/linux/ezlupdate -d "$dir/design" "$translation" &>/dev/null )
		if [ $? -ne 0 ]; then
		    echo
		    echo "Error updating translations"
		    exit 1
		fi
	    fi
	fi
	echo -n "`$POSITION_RESTORE``$SETCOLOR_COMMENT`$translation`$SETCOLOR_NORMAL`"
    done
    cd $dir
    echo

    diff -U3 -r $DEST/share/translations.org $DEST/share/translations &>/dev/null
    if [ $? -ne 0 ]; then
	echo "The translations are not up to date"
	echo "You must update the translations in the repository using the ezlupdate program"
	exit 1
    fi

    rm -rf $DEST/share/translations.org
fi

echo "Removing obsolete translation strings"
cd $DEST/share/translations
for translation in *; do
    if [ -z $SKIPTRANSLATION ]; then
	if [ "$translation" == "untranslated" ]; then
	    (cd  $DEST && $dir/bin/linux/ezlupdate -no -d "$dir/design" -u &>/dev/null)
	    if [ $? -ne 0 ]; then
		echo "Error removing obsolete entries"
		exit 1
	    fi
	else
	    (cd  $DEST && $dir/bin/linux/ezlupdate -no -d "$dir/design" "$translation" &>/dev/null)
	    if [ $? -ne 0 ]; then
		echo "Error removing obsolete entries"
		exit 1
	    fi
	fi
    fi
done
cd $dir

#
# *****   Handle changelogs from earlier versions   *****
#

if [ -z "$SKIP_CHANGELOGS" ]; then
    echo
    echo "Copying changelogs from earlier versions"
    echo -n "Version:"
    for version in $STABLE_VERSIONS; do
	changelog_url="$REPOSITORY_BASE_URL/$REPOSITORY_STABLE_BRANCH_PATH/$version/doc/changelogs/$version"
	rm -rf "$DEST/doc/changelogs/$version"
	echo -n " `$POSITION_STORE`$version"
	svn export "$changelog_url" "$DEST/doc/changelogs/$version" &>/dev/null
	if [ $? -ne 0 ]; then
	    echo
	    echo "Failed to check out changelogs for version `$SETCOLOR_EMPHASIZE`$version`$SETCOLOR_NORMAL`"
	    exit 1
	fi
	echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`$version`$SETCOLOR_NORMAL`"
    done
    echo
fi

#
# *****   Generate SQL files   *****
#

if [ -z "$SKIP_SQL_GENERATION" ]; then
    echo
    echo -n "Updating MySQL SQL schema"
    ./bin/php/ezsqldumpschema.php --type=mysql --compatible-sql --output-sql --format=local "share/db_schema.dba" "$DEST/kernel/sql/mysql/kernel_schema.sql" 2>.dump.log
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
    echo
fi


#
# *****   Create some directories which should be present   *****
#

EXTRA_DIRS=""
if [ "$DIST_TYPE" == "sdk" ]; then
    EXTRA_DIRS=$SDK_EXTRA_DIRS
else
    EXTRA_DIRS=$FULL_EXTRA_DIRS
fi

for file in $EXTRA_DIRS; do
    mkdir -p $DEST/$file
done

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
#	echo -n " `$POSITION_STORE`$site"
#	./bin/shell/makesitepackages.sh -q --export-path="$DEST/kernel/setup/packages" --site=$site
#	if [ $? -ne 0 ]; then
#	    echo
#	    echo "The package creation of $site failed"
#	    echo "Run the following command to see what went wrong"
#	    echo "./bin/shell/makesitepackages.sh --site=$site"
#	    exit 1
#	else
#	    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`$site`$SETCOLOR_NORMAL`"
#	fi
#    done
#fi
#echo

if [ -z $SKIPADDONCREATION ]; then
   echo "Creating and exporting addons"
   rm -rf "$DEST/packages/addons"
   mkdir -p "$DEST/packages/addons" || exit 1
   ./bin/shell/makeaddonpackages.sh -q --export-path="$DEST/packages/addons"
   if [ $? -ne 0 ]; then
       echo
       echo "The creation of addon packages failed"
       echo "Run the following command to see what went wrong"
       echo "./bin/shell/makeaddonpackages.sh --export-path=\"$DEST/packages/addons\""
       exit 1
   fi
fi

if [ -z $SKIPSTYLECREATION ]; then
   echo "Creating and exporting styles"
   rm -rf "$DEST/packages/styles"
   mkdir -p "$DEST/packages/styles" || exit 1
   ./bin/shell/makestylepackages.sh -q --export-path="$DEST/packages/styles"
   if [ $? -ne 0 ]; then
       echo
       echo "The creation of the style packages failed"
       echo "Run the following command to see what went wrong"
       echo "./bin/shell/makestylepackages.sh --export-path=\"$DEST/packages/styles\""
       exit 1
   fi
fi
echo

#
# *****   Build package files for extensions *****
#

if [ -z "$SKIP_EXTENSIONS" ]; then
    echo "`ez_color_h1 'Building extension packages'`"

    DEST_EXT="$DEST_ROOT/extension"
    EXTENSION_FILES=""
    if [ -d "$DEST_EXT" ]; then
	rm -rf "$DEST_EXT"
    fi
    mkdir -p "$DEST_EXT"

    for extension in $EXTENSIONS; do
	echo "Working with extension `ez_color_url $extension`"
	echo -n "Fetching extension from SVN"
	svn co "$extension" "$DEST_EXT" &>.svn.log
	ez_result_output $? "Failed to export $extension, see `ez_color_file .svn.log` for more info"

	if [ ! -f "$DEST_EXT/dist.sh" ]; then
	    echo "Distribution info file `ez_color_file dist.sh` is missing from extension"
	    echo "This must be created before it can be packaged"
	    exit 1
	fi

	if [ ! -x "$DEST_EXT/dist.sh" ]; then
	    echo "Distribution info file `ez_color_file dist.sh` is not executable"
	    echo "This file must be executable for this script to read its contents"
	    exit 1
	fi

    # Load variables from distribution file
	EXTENSION_NAME=""
	EXTENSION_IDENTIFIER=""
	EXTENSION_SUMMARY=""
	EXTENSION_LICENSE=""
	EXTENSION_VERSION=""
	EXTENSION_PUBLISH_VERSION=""
	EXTENSION_ARCHIVE_NAME=""
	EXTENSION_PHP_VERSION=""
	. "$DEST_EXT/dist.sh"

	rm -f "$DEST_EXT/dist.sh"

	if [ -z "$EXTENSION_IDENTIFIER" ]; then
	    echo "Identifier was not set for extension"
	    exit 1
	fi

	OLD_DEST="$DEST"
	DEST="$DEST/extension/$EXTENSION_IDENTIFIER"
	mkdir -p "$DEST"
	echo "Copying distribution files"
	(cd "$DEST_EXT" && scan_dir .)

	echo
	echo "Looking for `$SETCOLOR_DIR`.svn`$SETCOLOR_NORMAL` directories"
	(cd $DEST
	    find . -name .svn -print)

	echo "Looking for `$SETCOLOR_COMMENT`temp`$SETCOLOR_NORMAL` files"
	(cd $DEST
	    TEMPFILES=`find . -name '*[~#]' -print`
	    echo $TEMPFILES | grep -e '[~#]' -q
	    if [ $? -eq 0 ]; then
		echo "Cannot create extension distribution, the following temporary files were found:"
		for tempfile in $TEMPFILES; do
		    echo "`$SETCOLOR_FAILURE`$tempfile`$SETCOLOR_NORMAL`"
		done
		echo "The files must be removed before the extension distribution can be made"
		exit 1
	    fi
	) || exit 1

	DEST="$OLD_DEST"
	if [ "$BUILD_SNAPSHOT" == "1" ]; then
	    EXTENSION_TGZFILE="$EXTENSION_ARCHIVE_NAME""-extension-$EXTENSION_VERSION-build$CURRENT_BUILD_NUMBER.tgz"
	else
	    EXTENSION_TGZFILE="$EXTENSION_ARCHIVE_NAME""-extension-$EXTENSION_VERSION.tgz"
	fi

	# Store paypal archive name
	if [ "$EXTENSION_IDENTIFIER" = "ezpaypal" ]; then
	    EXTENSION_PAYPAL_ARCHIVE="$DEST_EXTENSION_ARCHIVE/$EXTENSION_TGZFILE"
	fi

	echo -n "Creating extension archive `ez_color_file $EXTENSION_TGZFILE`"
	(cd "$DEST/extension" && \
	    tar cfz "$EXTENSION_TGZFILE" "$EXTENSION_IDENTIFIER" && \
	    rm -rf "$EXTENSION_IDENTIFIER" && \
	    mv "$EXTENSION_TGZFILE" "$DEST_EXTENSION_ARCHIVE/")
	ez_result_output $? "Failed to package extension"
	rm -rf "$DEST_EXT"
	EXTENSION_FILES="$EXTENSION_FILES $EXTENSION_TGZFILE"
	echo
    done

    echo
    echo "Extensions have been packaged to dir `ez_color_dir $DEST_EXTENSION_ARCHIVE`"
    echo
fi


#
# *****   Remove some comments in old SQL files *****
#

if [ -z "$SKIP_CORE_FILES" ]; then
    echo "`$SETCOLOR_COMMENT`Applying filters`$SETCOLOR_NORMAL`"
    for filter in $FILTER_FILES; do
	cat $DEST/$filter | sed 's,^#!\(.*\)$,\1,' | grep -v '^..*##!' > $DEST/$filter.tmp && mv -f $DEST/$filter.tmp $DEST/$filter
    done

    for filter in $FILTER_FILES2; do
	cat $DEST/$filter | sed 's,^##!\(.*\)$,\1,' | grep -v '^..*##!' > $DEST/$filter.tmp && mv -f $DEST/$filter.tmp $DEST/$filter
    done

    echo "`$SETCOLOR_COMMENT`Checking `$SETCOLOR_EMPHASIZE`SQL`$SETCOLOR_COMMENT` files for correctness`$SETCOLOR_NORMAL`"


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
	echo "`$SETCOLOR_EMPHASIZE`The following sql files has comments in them and should be fixed.`$SETCOLOR_NORMAL`"
	echo "`$SETCOLOR_DIR`$BAD_SQL_FILES`$SETCOLOR_NORMAL`"
	read -p "`$SETCOLOR_EMPHASIZE`Do want to fix this for the release?`$SETCOLOR_NORMAL` Yes/No [N]" FIX_SQL
	if [ "$FIX_SQL" == "" ]; then
	    FIX_SQL="N"
	fi
	case "$FIX_SQL" in
	    Y|y|Yes|yes|YES)
		for bad_sql_file in $BAD_SQL_FILES; do
		    echo -n "Fixing `$SETCOLOR_FILE`$DEST/$bad_sql_file`$SETCOLOR_NORMAL`"
		    cleanup_sql_file $DEST/$bad_sql_file
		    echo ", `$SETCOLOR_SUCCESS`done`$SETCOLOR_NORMAL`"
		done
		;;
	    *)
		echo "You will have to fix the sql files manually before creating the distribution."
		exit 1
		;;
	esac
    fi
fi


# cat index.php | sed 's/index.php/index_sdk.php/' > $DEST/index_sdk.php
# cp -f index.php $DEST/index.php

echo "Looking for `$SETCOLOR_DIR`.svn`$SETCOLOR_NORMAL` directories"
(cd $DEST
    find . -name .svn -print)

echo "Looking for `$SETCOLOR_COMMENT`temp`$SETCOLOR_NORMAL` files"
(cd $DEST
    TEMPFILES=`find . -name '*[~#]' -print`
    echo $TEMPFILES | grep -e '[~#]' -q
    if [ $? -eq 0 ]; then
	echo "Cannot create distribution, the following temporary files were found:"
	for tempfile in $TEMPFILES; do
	    echo "`$SETCOLOR_FAILURE`$tempfile`$SETCOLOR_NORMAL`"
	done
	echo "The files must be removed before the distribution can be made"
	exit 1
    fi
) || exit 1

if [ -z "$SKIP_CORE_FILES" ]; then
    if [ -f $DEST/bin/modfix.sh ]; then
	echo "Applying `$SETCOLOR_EXE`executable`$SETCOLOR_NORMAL` properties"
	(cd $DEST/bin
	    chmod a+x modfix.sh)
    fi
fi

if [ ! -d "$DEST/extension" ]; then
    (cd $DEST && mkdir extension)
fi

#
# *****   Remove some elements which should not be in dist   *****
#

if [ -d $DEST/kernel/sql/oracle ]; then
    rm -rf $DEST/kernel/sql/oracle
fi

if [ -f $DEST/kernel/sql/mysql/doc.sql ]; then
    rm -f $DEST/kernel/sql/mysql/doc.sql
fi

if [ -f $DEST/kernel/sql/postgresql/doc.sql ]; then
    rm -f $DEST/kernel/sql/postgresql/doc.sql
fi

if [ -f $DEST/support/lupdate-ezpublish3/Makefile ]; then
    (cd $DEST/support/lupdate-ezpublish3 && make clean &>/dev/null && rm -rf Makefile moc obj)
fi

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

# if [ -z "$SKIPDBSCHEMA" ]; then
# # Create SQL schema definition for later checks
# 
#     echo "Creating MySQL schema"
#     if [ "$DB_PASSWORD"x == x ]; then
# 	DBPWDOPTION=""
# 	DBPWDOPTION_LONG=""
#     else
# 	DBPWDOPTION="-p $DB_PASSWORD"
# 	DBPWDOPTION_LONG="--password=$DB_PASSWORD"
#     fi
#     mysqladmin -u "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION -f drop "$DB_NAME" &>/dev/null
#     mysqladmin -u "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION create "$DB_NAME"  &>/dev/null || exit 1
#     mysql -u "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" < kernel/sql/mysql/kernel_schema.sql  &>/dev/null || exit 1
# 
#     ./bin/php/ezsqldumpschema.php --type=ezmysql --user="$DB_USER" --host="$DB_SERVER" $DBPWDOPTION_LONG "$DB_NAME" $DEST/share/db_mysql_schema.dat  &>/dev/null || exit 1
# 
#     mysqladmin -u "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION -f drop "$DB_NAME" &>/dev/null
# 
# 
#     echo "Creating PostgreSQL schema"
#     if [ "$DB_PASSWORD"x == x ]; then
# 	DBPWDOPTION=""
# 	DBPWDOPTION_LONG=""
#     else
# 	DBPWDOPTION=""
# 	DBPWDOPTION_LONG="--password=$DB_PASSWORD"
#     fi
# 
#     dropdb -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" &>/dev/null
#     createdb -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME"  &>/dev/null || exit 1
#     psql -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" < kernel/sql/postgresql/kernel_schema.sql  &>/dev/null || exit 1
#     psql -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" < kernel/sql/postgresql/setval.sql  &>/dev/null || exit 1
# 
#     ./bin/php/ezsqldumpschema.php --type=ezpostgresql --user="$DB_USER" --host="$DB_SERVER" $DBPWDOPTION_LONG "$DB_NAME" $DEST/share/db_postgresql_schema.dat  &>/dev/null || exit 1
# 
#     dropdb -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" &>/dev/null
# fi

# Create MD5 check sums
if [ -z "$SKIP_CORE_FILES" ]; then
    echo "Creating MD5 check sums"
    (cd $DEST
	MD5_FILES=`find * -name "*.php" -or -name "*.ini" -or -name "*.sh" -or -name "*.sql"`
	
	for MD5_FILE in $MD5_FILES; do
	    md5sum $MD5_FILE >> share/filelist.md5
	done

	MD5_FILES=`find design/* -name "*.tpl"`

	for MD5_FILE in $MD5_FILES; do
	    md5sum $MD5_FILE >> share/filelist.md5

	    done)
fi



#
# *****   Unpack ezpaypal as part of standard dist   *****
#

if [ -f "$EXTENSION_PAYPAL_ARCHIVE" ]; then
    echo
    echo "Unpacking `ez_color_em Paypal` extension"
    (cd "$DEST/extension" && tar xfz "$EXTENSION_PAYPAL_ARCHIVE")
    echo
fi


#
# *****   Create archives   *****
#

TGZFILE="$BASE.tar.gz"
TBZFILE="$BASE.tar.bz2"
ZIPFILE="$BASE.zip"

echo -n "Creating `$SETCOLOR_FILE`tar.gz`$SETCOLOR_NORMAL` file"
(cd $DEST_ROOT
    tar cfz $TGZFILE $BASE
    echo ",  `$SETCOLOR_EMPHASIZE`$DEST_ROOT/$BASE.tar.gz`$SETCOLOR_NORMAL`")

echo -n "Creating `$SETCOLOR_FILE`tar.bz2`$SETCOLOR_NORMAL` file"
(cd $DEST_ROOT
    tar cf $BASE.tar $BASE
    if [ -f $TBZFILE ]; then
	rm -f $TBZFILE
    fi
    bzip2 $BASE.tar
    echo ", `$SETCOLOR_EMPHASIZE`$DEST_ROOT/$BASE.tar.bz2`$SETCOLOR_NORMAL`")

if [ "which zip &>/dev/null" ]; then
    echo -n "Creating `$SETCOLOR_FILE`zip`$SETCOLOR_NORMAL` file"
    (cd $DEST_ROOT
	zip -9 -r -q $ZIPFILE $BASE
	echo ",     `$SETCOLOR_EMPHASIZE`$DEST_ROOT/$BASE.zip`$SETCOLOR_NORMAL`")
else
    echo "`SETCOLOR_WARNING`Could not create `$SETCOLOR_FILE`zip`$SETCOLOR_WARNING` file, `$SETCOLOR_EXE`zip`$SETCOLOR_NORMAL` program not found.`SETCOLOR_NORMAL`"
fi

if [ -z "$SKIP_EXTENSIONS" ]; then
    echo
    echo "The following extensions have been packaged:"
    for extension_file in $EXTENSION_FILES; do
	echo "`ez_color_em $DEST_EXTENSION_ARCHIVE/$extension_file`"
    done
    echo
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

echo '' > $CACHE
echo "BUILD_NUMBER=\"$BUILD_NUMBER\"" >> $CACHE


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

FULL_EXTRA_DIRS="settings/override var/cache var/storage"
SDK_EXTRA_DIRS="settings/override var/carhe var/storage doc/generated/html"

FILTER_FILES="settings/site.ini settings/content.ini settings/setup.ini settings/i18n.ini settings/layout.ini settings/template.ini settings/texttoimage.ini settings/units.ini settings/siteaccess/admin/site.ini.append settings/siteaccess/admin/override.ini.append settings/webdav.ini settings/image.ini"
FILTER_FILES2="bin/modfix.sh"
PACKAGE_DIR="packages"

. ./bin/shell/common.sh
. ./bin/shell/packagescommon.sh

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
	    echo "         --build-root=DIR           Set build root, default is /tmp"
	    echo "         --with-svn-server[=SERVER] Checkout fresh repository"
	    echo "         --with-release=NAME        Checkout a previous release, default is trunk"
#	    echo "         --skip-site-creation       Do not build sites*"
	    echo "         --skip-version-check       Do not check version numbers*"
	    echo "         --skip-php-check           Do not check PHP for syntax correctnes*"
	    echo "         --skip-unit-tests          Do not run unit tests*"
	    echo "         --skip-db-schema           Do not create db schema (requires mysql and postgresql)*"
	    echo "         --skip-db-update           Do not run db update check*"
	    echo "         --skip-db-check            Do not run db schema check*"
	    echo "         --skip-translation         Do not run translation check*"
	    echo "         --skip-styles              Do not create style packages*"
	    echo "         --skip-addons              Do not create addon packages*"
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
DEST="$DEST_ROOT/$BASE"

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
    ./tests/testunits.php -q eztemplate
    if [ $? -ne 0 ]; then
	echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
	echo "Some unit tests failed"
	echo "Run the following command to find out which one failed"
	echo "./tests/testunits.php eztemplate"
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


echo "Making distribution in `$SETCOLOR_DIR`$DEST`$SETCOLOR_NORMAL`"

if [ -d $DEST ]; then
    echo "`$SETCOLOR_COMMENT`Removing old distribution`$SETCOLOR_NORMAL`"
    rm -rf $DEST
    mkdir -p $DEST
else
    echo "`$SETCOLOR_NEW`Creating distribution directory`$SETCOLOR_NEW`"
    mkdir -p $DEST
fi

echo
echo "Copying directories and files"
echo -n "`$SETCOLOR_COMMENT`Copying`$SETCOLOR_NORMAL` "

(cd $DIST_SRC && scan_dir .)
echo

if [ "$DEVELOPMENT" == "true" ]; then
    echo "Copying test framework"
    svn export "$CURRENT_URL/tests" "$DEST/tests" &>/dev/null
fi

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

if [ -z $SKIPTRANSLATION ]; then
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
cp -R -f $DEST/share/translations $DEST/share/translations.org &>/dev/null
if [ $? -ne 0 ]; then
    echo "Failed to make copy of translations"
    exit 1
fi
echo -n "Processing:"
cd $DEST/share/translations
for translation in *; do
    echo -n " `$POSITION_STORE`$translation"
    
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
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`$translation`$SETCOLOR_NORMAL`"
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

echo
echo "Copying `$SETCOLOR_FILE`kernel/sql/common/cleandata.sql`$SETCOLOR_NORMAL` to MySQL and PostgreSQL"
cat kernel/sql/common/cleandata.sql >$DEST/kernel/sql/mysql/cleandata.sql || exit 1
cat kernel/sql/common/cleandata.sql kernel/sql/postgresql/setval.sql >$DEST/kernel/sql/postgresql/cleandata.sql || exit 1

EXTRA_DIRS=""
if [ "$DIST_TYPE" == "sdk" ]; then
    EXTRA_DIRS=$SDK_EXTRA_DIRS
else
    EXTRA_DIRS=$FULL_EXTRA_DIRS
fi

for file in $EXTRA_DIRS; do
    mkdir -p $DEST/$file
done

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

if [ -f $DEST/bin/modfix.sh ]; then
    echo "Applying `$SETCOLOR_EXE`executable`$SETCOLOR_NORMAL` properties"
    (cd $DEST/bin
	chmod a+x modfix.sh)
fi

(cd $DEST && mkdir extension)

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

if [ -z "$SKIPDBSCHEMA" ]; then
# Create SQL schema definition for later checks

    echo "Creating MySQL schema"
    if [ "$DB_PASSWORD"x == x ]; then
	DBPWDOPTION=""
	DBPWDOPTION_LONG=""
    else
	DBPWDOPTION="-p $DB_PASSWORD"
	DBPWDOPTION_LONG="--password=$DB_PASSWORD"
    fi
    mysqladmin -u "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION -f drop "$DB_NAME" &>/dev/null
    mysqladmin -u "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION create "$DB_NAME"  &>/dev/null || exit 1
    mysql -u "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" < kernel/sql/mysql/kernel_schema.sql  &>/dev/null || exit 1

    ./bin/php/ezsqldumpschema.php --type=ezmysql --user="$DB_USER" --host="$DB_SERVER" $DBPWDOPTION_LONG "$DB_NAME" $DEST/share/db_mysql_schema.dat  &>/dev/null || exit 1

    mysqladmin -u "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION -f drop "$DB_NAME" &>/dev/null


    echo "Creating PostgreSQL schema"
    if [ "$DB_PASSWORD"x == x ]; then
	DBPWDOPTION=""
	DBPWDOPTION_LONG=""
    else
	DBPWDOPTION=""
	DBPWDOPTION_LONG="--password=$DB_PASSWORD"
    fi

    dropdb -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" &>/dev/null
    createdb -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME"  &>/dev/null || exit 1
    psql -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" < kernel/sql/postgresql/kernel_schema.sql  &>/dev/null || exit 1
    psql -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" < kernel/sql/postgresql/setval.sql  &>/dev/null || exit 1

    ./bin/php/ezsqldumpschema.php --type=ezpostgresql --user="$DB_USER" --host="$DB_SERVER" $DBPWDOPTION_LONG "$DB_NAME" $DEST/share/db_postgresql_schema.dat  &>/dev/null || exit 1

    dropdb -U "$DB_USER" -h "$DB_SERVER" $DBPWDOPTION "$DB_NAME" &>/dev/null
fi

# Create MD5 check sums
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


# Create archives
echo -n "Creating `$SETCOLOR_FILE`tar.gz`$SETCOLOR_NORMAL` file"
(cd $DEST_ROOT
    tar cfz $BASE.tar.gz $BASE
    echo ",  `$SETCOLOR_EMPHASIZE`$DEST_ROOT/$BASE.tar.gz`$SETCOLOR_NORMAL`")

echo -n "Creating `$SETCOLOR_FILE`tar.bz2`$SETCOLOR_NORMAL` file"
(cd $DEST_ROOT
    tar cf $BASE.tar $BASE
    if [ -f $BASE.tar.bz2 ]; then
	rm -f $BASE.tar.bz2
    fi
    bzip2 $BASE.tar
    echo ", `$SETCOLOR_EMPHASIZE`$DEST_ROOT/$BASE.tar.bz2`$SETCOLOR_NORMAL`")

if [ "which zip &>/dev/null" ]; then
    echo -n "Creating `$SETCOLOR_FILE`zip`$SETCOLOR_NORMAL` file"
    (cd $DEST_ROOT
	zip -9 -r -q $BASE.zip $BASE
	echo ",     `$SETCOLOR_EMPHASIZE`$DEST_ROOT/$BASE.zip`$SETCOLOR_NORMAL`")
else
    echo "`SETCOLOR_WARNING`Could not create `$SETCOLOR_FILE`zip`$SETCOLOR_WARNING` file, `$SETCOLOR_EXE`zip`$SETCOLOR_NORMAL` program not found.`SETCOLOR_NORMAL`"
fi

echo
echo "Now remember to tag the release with:"
# echo "`$SETCOLOR_EMPHASIZE`svn cp $DEFAULT_SVN_SERVER/trunk $DEFAULT_SVN_SERVER/$DEFAULT_SVN_RELEASE_PATH/$VERSION_NICK`$SETCOLOR_NORMAL`"
echo "`$SETCOLOR_EMPHASIZE`svn cp $DEFAULT_SVN_SERVER/trunk $DEFAULT_SVN_SERVER/$DEFAULT_SVN_VERSION_PATH/$VERSION`$SETCOLOR_NORMAL`"
# echo "And undeltify current version:"
# echo "`$SETCOLOR_WARNING`svnadmin undeltify `$SETCOLOR_SUCCESS`SVNREPOSITORY`$SETCOLOR_WARNING` `$SETCOLOR_SUCCESS`REVNUM`$SETCOLOR_WARNING` `$SETCOLOR_NORMAL`"
# echo "where `$SETCOLOR_SUCCESS`REVNUM`$SETCOLOR_NORMAL` is the revision number of the release."

#!/bin/bash

DIST_PROP="ez:distribution"
DIST_DIR_PROP="ez:distribution_include_all"
DIST_TYPE='full'
NAME="ezpublish"
DEST_ROOT="/tmp/$USER"
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

if ! which php &>/dev/null; then
    echo "No PHP executable found, please add it to the path"
    exit 1
fi

function make_dir
{
    local DIR
    DIR=`echo $1 | sed 's#^\./##'`
    if [ ! -d "$DEST/$DIR" ]; then
	mkdir $DEST/$DIR
    fi
}

function copy_file
{
    local SRC_FILE DST_FILE
    SRC_FILE=`echo $1 | sed 's#^\./##'`
    DST_FILE=$SRC_FILE
    cp -f $SRC_FILE $DEST/$DST_FILE

}

function scan_dir_normal
{
    local file
    local DIR

    DIR=$1
#    echo "Scanning dir $DIR normally"
    for file in $DIR/*; do
	if [ -e $file -a ! "$file" = "$DIR/.svn" -a ! "$file" = "$DIR/.." -a ! "$file" = "$DIR/." ]; then
# 	if ! echo $file | grep "/\*" &>/dev/null; then
	    if [ -d "$file" ]; then
	        # Do not include .svn dirs
		if [ "$file" != ".svn" ]; then
		    make_dir $file
		    scan_dir_normal $file
		fi
	    else
	        # Do not include temporary files
		if ! echo $file | grep '[~#]$' &>/dev/null; then
		    copy_file $file
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
			make_dir $file
			if [ -z $DIST_DIR_RECURSIVE ]; then
			    scan_dir "$file"
			else
			    echo -n "*"
			    scan_dir_normal "$file"
			fi
		    else
			echo -n " "`$SETCOLOR_FILE`"$file"`$SETCOLOR_NORMAL`
			copy_file $file
		    fi
		fi
	    fi
	fi
    done
}

SVN_SERVER=""
REPOS_RELEASE="trunk"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --build-root=DIR           Set build root, default is /tmp"
	    echo "         --release-sdk              Make SDK release"
	    echo "         --release-full             Make full release(default)"
	    echo "         --with-svn-server[=SERVER] Checkout fresh repository"
	    echo "         --with-release=NAME        Checkout a previous release, default is trunk"
            echo
            echo "Example:"
            echo "$0 --release-sdk --with-svn-server"
	    exit 1
	    ;;
	--release-sdk)
	    DIST_TYPE="sdk"
	    ;;
	--release-full)
	    DIST_TYPE="full"
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
	--skip-php-check)
	    SKIPCHECKPHP="1"
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
    echo "Checking db update version numbers"
    ./bin/php/checkdbfiles.php &>/dev/null
    if [ $? -ne 0 ]; then
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

    echo "Checking version numbers"
    ./bin/shell/checkversionnumbers.sh --exit-at-once &>/dev/null
    if [ $? -ne 0 ]; then
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
fi

if [ -z $SKIPCHECKPHP ]; then
    echo "Checking syntax of PHP files"
    ./bin/shell/phpcheck.sh --exit-on-error -q cronjobs kernel lib support update tests
    if [ $? -ne 0 ]; then
	echo "Some PHP files have syntax errors"
	echo "Run the following command to find the files"
	echo "./bin/shell/phpcheck.sh --errors-only cronjobs kernel lib support update tests"
	exit 1
    fi
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

echo "Copying directories and files"
echo -n "`$SETCOLOR_COMMENT`Copying`$SETCOLOR_NORMAL` "

(cd $DIST_SRC && scan_dir .)
echo

EXTRA_DIRS=""
if [ "$DIST_TYPE" == "sdk" ]; then
    EXTRA_DIRS=$SDK_EXTRA_DIRS
else
    EXTRA_DIRS=$FULL_EXTRA_DIRS
fi

for file in $EXTRA_DIRS; do
    mkdir -p $DEST/$file
done

if [ -d "$PACKAGE_DIR" ]; then
    echo "Fetching packages from `$SETCOLOR_EMPHASIZE`$PACKAGE_DIR`$SETCOLOR_NORMAL`"
    echo -n "Export packages:"
    mkdir -p $DEST/kernel/setup/packages
    for package in $PACKAGE_DIR/*; do
	if [ -d "$package" ]; then
	    PACKAGE_NAME=`basename $package`
	    echo -n " `$SETCOLOR_EMPHASIZE`$PACKAGE_NAME`$SETCOLOR_NORMAL`"
	    ./ezpm.php -q -r "$PACKAGE_DIR" export "$PACKAGE_NAME" -d "$DEST/kernel/setup/packages"
	fi
    done
    echo
else
    echo "No packages to export"
fi

# if [ "$DIST_TYPE" == "sdk" ]; then
if [ -d "doc/generated/html" ]; then
    echo "Copying generated documentation"
    mkdir -p $DEST/doc/generated/html
    cp -f "doc/generated/html"/* $DEST/doc/generated/html
fi

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

# Create archives
echo -n "Creating `$SETCOLOR_FILE`tar.gz`$SETCOLOR_NORMAL` file"
(cd $DEST_ROOT
    tar cfz $BASE.tar.gz $BASE
    echo ", `$SETCOLOR_SUCCESS`done`$SETCOLOR_NORMAL`")

echo -n "Creating `$SETCOLOR_FILE`tar.bz2`$SETCOLOR_NORMAL` file"
(cd $DEST_ROOT
    tar cf $BASE.tar $BASE
    if [ -f $BASE.tar.bz2 ]; then
	rm -f $BASE.tar.bz2
    fi
    bzip2 $BASE.tar
    echo ", `$SETCOLOR_SUCCESS`done`$SETCOLOR_NORMAL`")

if [ "which zip &>/dev/null" ]; then
    echo -n "Creating `$SETCOLOR_FILE`zip`$SETCOLOR_NORMAL` file"
    (cd $DEST_ROOT
	zip -9 -r -q $BASE.zip $BASE
	echo ", `$SETCOLOR_SUCCESS`done`$SETCOLOR_NORMAL`")
else
    echo "`SETCOLOR_WARNING`Could not create `$SETCOLOR_FILE`zip`$SETCOLOR_WARNING` file, `$SETCOLOR_EXE`zip`$SETCOLOR_NORMAL` program not found.`SETCOLOR_NORMAL`"
fi

echo "Created archives:"
echo "`$SETCOLOR_EMPHASIZE`$DEST_ROOT/$BASE.tar.gz`$SETCOLOR_NORMAL`"
echo "`$SETCOLOR_EMPHASIZE`$DEST_ROOT/$BASE.tar.bz2`$SETCOLOR_NORMAL`"
echo "`$SETCOLOR_EMPHASIZE`$DEST_ROOT/$BASE.zip`$SETCOLOR_NORMAL`"

echo
echo "Now remember to create releases with:"
# echo "`$SETCOLOR_EMPHASIZE`svn cp $DEFAULT_SVN_SERVER/trunk $DEFAULT_SVN_SERVER/$DEFAULT_SVN_RELEASE_PATH/$VERSION_NICK`$SETCOLOR_NORMAL`"
echo "`$SETCOLOR_EMPHASIZE`svn cp $DEFAULT_SVN_SERVER/stable/3.3 $DEFAULT_SVN_SERVER/$DEFAULT_SVN_VERSION_PATH/$VERSION`$SETCOLOR_NORMAL`"
# echo "And undeltify current version:"
# echo "`$SETCOLOR_WARNING`svnadmin undeltify `$SETCOLOR_SUCCESS`SVNREPOSITORY`$SETCOLOR_WARNING` `$SETCOLOR_SUCCESS`REVNUM`$SETCOLOR_WARNING` `$SETCOLOR_NORMAL`"
# echo "where `$SETCOLOR_SUCCESS`REVNUM`$SETCOLOR_NORMAL` is the revision number of the release."

#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

LAST_STABLE="3.3-3"

MAJOR=3
MINOR=4
RELEASE=0
REAL_RELEASE=1
STATE="alpha1"
VERSION=$MAJOR"."$MINOR"."$RELEASE""$STATE
VERSION_ONLY=$MAJOR"."$MINOR"."$RELEASE
MAJMIN=$MAJOR"."$MINOR
DEVELOPMENT="false"
[ -n $STATE ] && DEVELOPMENT="true"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --exit-at-once             Exit at the first version error"
            echo
	    exit 1
	    ;;
	--exit-at-once)
	    EXIT_AT_ONCE="1"
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
    esac;
done

# bin/shell/common.sh

if ! grep "VERSION=\"$VERSION\"" bin/shell/common.sh &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable VERSION"
    echo "Should be:"
    echo "VERSION=\"`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`\""
    echo
    ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

if ! grep "VERSION_ONLY=\"$VERSION_ONLY\"" bin/shell/common.sh &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable VERSION_ONLY"
    echo "Should be:"
    echo "VERSION_ONLY=\"`$SETCOLOR_EMPHASIZE`$VERSION_ONLY`$SETCOLOR_NORMAL`\""
    echo
    ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# lib/version.php

if ! grep "define( \"EZ_SDK_VERSION_MAJOR\", $MAJOR );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_MAJOR"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_MAJOR\", `$SETCOLOR_EMPHASIZE`$MAJOR`$SETCOLOR_NORMAL` );"
    echo
    ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

if ! grep "define( \"EZ_SDK_VERSION_MINOR\", $MINOR );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_MINOR"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_MINOR\", `$SETCOLOR_EMPHASIZE`$MINOR`$SETCOLOR_NORMAL` );"
    echo
    ERROR="1"
    [ -n $EXIT_AT_ONCE ] && exit 1
fi

if ! grep "define( \"EZ_SDK_VERSION_RELEASE\", $RELEASE );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_RELEASE"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_RELEASE\", `$SETCOLOR_EMPHASIZE`$RELEASE`$SETCOLOR_NORMAL` );"
    echo
    ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

if ! grep "define( \"EZ_SDK_VERSION_STATE\", '$STATE' );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_STATE"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_STATE\", '`$SETCOLOR_EMPHASIZE`$STATE`$SETCOLOR_NORMAL`' );"
    echo
    ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

if ! grep "define( \"EZ_SDK_VERSION_DEVELOPMENT\", $DEVELOPMENT );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_DEVELOPMENT"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_DEVELOPMENT\", `$SETCOLOR_EMPHASIZE`$DEVELOPMENT`$SETCOLOR_NORMAL` );"
    echo
    ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# doc/doxygen/Doxyfile

if ! grep -E "PROJECT_NUMBER[ ]+=[ ]+$VERSION" doc/doxygen/Doxyfile &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`doc/doxygen/Doxyfile`$SETCOLOR_NORMAL` for variable PROJECT_NUMBER"
    echo "Should be:"
    echo "PROJECT_NUMBER         = `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
    echo
    ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# kernel/sql/common/cleandata.sql

SQL_LIST="kernel/sql/common/cleandata.sql"
for package in PACKAGES; do
    SQL_LIST="$SQL_LIST var/sql/data/$package.sql"
done
SQL_ERROR_LIST=""

for sql in $SQL_LIST; do
    if ! grep -E "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-version','$VERSION_ONLY');" $sql &>/dev/null; then
	ERROR="1"
	SQL_ERROR="1"
    fi

    if ! grep -E "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-release','$REAL_RELEASE');" $sql &>/dev/null; then
	ERROR="1"
	SQL_ERROR="1"
    fi
    [ -n "$SQL_ERROR" ] && SQL_ERROR_LIST="$SQL_ERROR_LIST $sql"
done

if [ -n "$SQL_ERROR" ]; then

    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo -n "Wrong version number in "
    for sql in $SQL_ERROR_LIST; do
	echo -n "`$SETCOLOR_FILE`$sql`$SETCOLOR_NORMAL` "
    done
    echo
    echo "For the variable ezpublish-version"
    echo
    echo "Should be:"
    echo "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-version','`$SETCOLOR_EMPHASIZE`$VERSION_ONLY`$SETCOLOR_NORMAL`');"
    echo "and"
    echo "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-release','`$SETCOLOR_EMPHASIZE`$REAL_RELEASE`$SETCOLOR_NORMAL`');"
    echo
    echo "To fix this the following should be done:"
    echo
    echo "Create a file called `$SETCOLOR_FILE`data.sql`$SETCOLOR_NORMAL` and add the following"
    echo "`$SETCOLOR_EMPHASIZE`UPDATE ezsite_data set value='$VERSION_ONLY' WHERE name=-'ezpublish-version'`$SETCOLOR_NORMAL`"
    echo "`$SETCOLOR_EMPHASIZE`UPDATE ezsite_data set value='$REAL_RELEASE' WHERE name=-'ezpublish-release'`$SETCOLOR_NORMAL`"
    echo "then run `$SETCOLOR_EXE`./bin/shell/redumpall.sh`$SETCOLOR_NORMAL`,"
    echo "check the changes with `$SETCOLOR_EXE`svn diff`$SETCOLOR_NORMAL` and them commit them if everything is ok"
    echo
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# support/lupdate-ezpublish3/main.cpp

if ! grep -E "static QString version = \"$VERSION\";" support/lupdate-ezpublish3/main.cpp &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`support/lupdate-ezpublish3/main.cpp`$SETCOLOR_NORMAL` for variable version"
    echo "Should be:"
    echo "static QString version = \"`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`\""
    echo
    ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# update/database/*/$version/

for driver in $DRIVERS; do

    file="update/database/$driver/$MAJMIN/dbupdate-$LAST_STABLE-to-$VERSION.sql"
    if [ ! -f $file ]; then
	echo "`$SETCOLOR_FAILURE`Missing database update file`$SETCOLOR_NORMAL`"
	echo "The databse update file `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` is missing"
	echo "This file is required for a valid release"
	echo
	ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
    else
	if ! grep -E "UPDATE ezsite_data SET value='$VERSION' WHERE name='ezpublish-version';" $file &>/dev/null; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` for variable ezpublish-version"
	    echo "Should be:"
	    echo "UPDATE ezsite_data SET value='`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`' WHERE name='ezpublish-version';"
	    echo
	    ERROR="1"
	    [ -n "$EXIT_AT_ONCE" ] && exit 1
	fi
	if ! grep -E "UPDATE ezsite_data SET value='$REAL_RELEASE' WHERE name='ezpublish-release';" $file &>/dev/null; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` for variable ezpublish-version"
	    echo "Should be:"
	    echo "UPDATE ezsite_data SET value='`$SETCOLOR_EMPHASIZE`$REAL_RELEASE`$SETCOLOR_NORMAL`' WHERE name='ezpublish-release';"
	    echo
	    ERROR="1"
	    [ -n "$EXIT_AT_ONCE" ] && exit 1
	fi
    fi
done

[ -n "$ERROR" ] && exit 1

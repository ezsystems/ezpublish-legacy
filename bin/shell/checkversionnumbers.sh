#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

# The last version which changelogs and db updates are related to
# For the first development release this should be empty, in
# wich case $LAST_STABLE is used.
PREVIOUS_VERSION="3.4.0alpha3"
# The last version of the newest stable branch
LAST_STABLE="3.3-4"

MAJOR=3
MINOR=4
RELEASE=0
# Starts at 1 for the first release in a branch and increases with one
REAL_RELEASE=4
STATE="beta1"
VERSION=$MAJOR"."$MINOR"."$RELEASE""$STATE
VERSION_ONLY=$MAJOR"."$MINOR
BRANCH_VERSION=$MAJOR"."$MINOR
# Is automatically set to 'true' when $STATE contains some text
DEVELOPMENT="false"
# If non-empty the script will check for changelog and db update from $LAST_STABLE
FIRST_STABLE=""

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
    MAIN_ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

if ! grep "VERSION_ONLY=\"$VERSION_ONLY\"" bin/shell/common.sh &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable VERSION_ONLY"
    echo "Should be:"
    echo "VERSION_ONLY=\"`$SETCOLOR_EMPHASIZE`$VERSION_ONLY`$SETCOLOR_NORMAL`\""
    echo
    MAIN_ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# lib/version.php

if ! grep "define( \"EZ_SDK_VERSION_MAJOR\", $MAJOR );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_MAJOR"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_MAJOR\", `$SETCOLOR_EMPHASIZE`$MAJOR`$SETCOLOR_NORMAL` );"
    echo
    MAIN_ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

if ! grep "define( \"EZ_SDK_VERSION_MINOR\", $MINOR );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_MINOR"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_MINOR\", `$SETCOLOR_EMPHASIZE`$MINOR`$SETCOLOR_NORMAL` );"
    echo
    MAIN_ERROR="1"
    [ -n $EXIT_AT_ONCE ] && exit 1
fi

if ! grep "define( \"EZ_SDK_VERSION_RELEASE\", $RELEASE );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_RELEASE"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_RELEASE\", `$SETCOLOR_EMPHASIZE`$RELEASE`$SETCOLOR_NORMAL` );"
    echo
    MAIN_ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

if ! grep "define( \"EZ_SDK_VERSION_STATE\", '$STATE' );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_STATE"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_STATE\", '`$SETCOLOR_EMPHASIZE`$STATE`$SETCOLOR_NORMAL`' );"
    echo
    MAIN_ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

if ! grep "define( \"EZ_SDK_VERSION_DEVELOPMENT\", $DEVELOPMENT );" lib/version.php &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_DEVELOPMENT"
    echo "Should be:"
    echo "define( \"EZ_SDK_VERSION_DEVELOPMENT\", `$SETCOLOR_EMPHASIZE`$DEVELOPMENT`$SETCOLOR_NORMAL` );"
    echo
    MAIN_ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# doc/doxygen/Doxyfile

if ! grep -E "PROJECT_NUMBER[ ]+=[ ]+$VERSION" doc/doxygen/Doxyfile &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong version number in `$SETCOLOR_EXE`doc/doxygen/Doxyfile`$SETCOLOR_NORMAL` for variable PROJECT_NUMBER"
    echo "Should be:"
    echo "PROJECT_NUMBER         = `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
    echo
    MAIN_ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# kernel/sql/common/cleandata.sql

SQL_LIST="kernel/sql/common/cleandata.sql"
SQL_ERROR_LIST=""

for sql in $SQL_LIST; do
    SQL_FILE_ERROR=""
    if ! grep -e "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-version','$VERSION');" $sql &>/dev/null; then
	MAIN_ERROR="1"
	SQL_ERROR="1"
	SQL_FILE_ERROR="1"
    fi

    if ! grep -e "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-release','$REAL_RELEASE');" $sql &>/dev/null; then
	MAIN_ERROR="1"
	SQL_ERROR="1"
	SQL_FILE_ERROR="1"
    fi
    [ -n "$SQL_FILE_ERROR" ] && SQL_ERROR_LIST="$SQL_ERROR_LIST $sql"
done

if [ -n "$SQL_ERROR" ]; then

    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo -n "Wrong/missing version number in "
    for sql in $SQL_ERROR_LIST; do
	echo -n "`$SETCOLOR_FILE`$sql`$SETCOLOR_NORMAL` "
    done
    echo
    echo "For the variable ezpublish-version"
    echo
    echo "Should be:"
    echo "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-version','`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`');"
    echo "and"
    echo "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-release','`$SETCOLOR_EMPHASIZE`$REAL_RELEASE`$SETCOLOR_NORMAL`');"
    echo
    echo "To fix this the following should be done:"
    echo
    echo "Create a file called `$SETCOLOR_FILE`data.sql`$SETCOLOR_NORMAL` and add the following"
    echo "`$SETCOLOR_EMPHASIZE`UPDATE ezsite_data set value='$VERSION' WHERE name='ezpublish-version';`$SETCOLOR_NORMAL`"
    echo "`$SETCOLOR_EMPHASIZE`UPDATE ezsite_data set value='$REAL_RELEASE' WHERE name='ezpublish-release';`$SETCOLOR_NORMAL`"
    echo "then run `$SETCOLOR_EXE`./bin/shell/redumpall.sh --data tmp`$SETCOLOR_NORMAL`,"
    echo "check the changes with `$SETCOLOR_EXE`svn diff`$SETCOLOR_NORMAL` and them commit them if everything is ok"
    echo
    echo "You can copy and paste the following in your shell"
    echo "--------------------------------------8<----------------------------------------"
    echo
    echo "rm -f data.sql"
    echo "echo \"`$SETCOLOR_EMPHASIZE`UPDATE ezsite_data set value='$VERSION' WHERE name='ezpublish-version';`$SETCOLOR_NORMAL`\" > data.sql"
    echo "echo \"`$SETCOLOR_EMPHASIZE`UPDATE ezsite_data set value='$REAL_RELEASE' WHERE name='ezpublish-release';`$SETCOLOR_NORMAL`\" >> data.sql"
    echo
    echo "`$SETCOLOR_EXE`./bin/shell/redumpall.sh --data tmp`$SETCOLOR_NORMAL`"
    echo
    echo "--------------------------------------8<----------------------------------------"
    echo
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# support/lupdate-ezpublish3/main.cpp

if ! grep -E "static QString version = \"$VERSION\";" support/lupdate-ezpublish3/main.cpp &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo "Wrong/missing version number in `$SETCOLOR_EXE`support/lupdate-ezpublish3/main.cpp`$SETCOLOR_NORMAL` for variable version"
    echo "Should be:"
    echo "static QString version = \"`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`\""
    echo
    MAIN_ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
fi

# doc/changelogs/$version/

if [ -z "$PREVIOUS_VERSION" ]; then
    prev="$LAST_STABLE"
else
    prev="$PREVIOUS_VERSION"
fi

if [ "$DEVELOPMENT" == "true" ]; then
    file="doc/changelogs/$BRANCH_VERSION/unstable/CHANGELOG-$prev-to-$VERSION"
else
    file="doc/changelogs/$BRANCH_VERSION/CHANGELOG-$prev-to-$VERSION"
fi
if [ ! -f $file ]; then
    echo "`$SETCOLOR_FAILURE`Missing changelog file`$SETCOLOR_NORMAL`"
    echo "The changelog file `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` is missing"
    echo "This file is required for a valid release"
    if [ -n "$FIRST_STABLE" ]; then
	echo "It should contain all the changes from all the previous development releases"
    else
	echo "It should contain the changes from the previous version $prev"
    fi
    echo
    MAIN_ERROR="1"
    [ -n "$EXIT_AT_ONCE" ] && exit 1
else
    if ! grep -E "Changes from $prev to $VERSION" $file &>/dev/null; then
	echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	echo "Wrong/missing version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL`"
	echo "The changelog should contain this line at the top:"
	echo "Changes from `$SETCOLOR_EMPHASIZE`$prev`$SETCOLOR_NORMAL` to `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
	echo
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
    fi
fi

if [ -n "$FIRST_STABLE" ]; then
    prev="$PREVIOUS_VERSION"
    if [ "$DEVELOPMENT" == "true" ]; then
	file="doc/changelogs/$BRANCH_VERSION/unstable/CHANGELOG-$prev-to-$VERSION"
    else
	file="doc/changelogs/$BRANCH_VERSION/CHANGELOG-$prev-to-$VERSION"
    fi
    if [ ! -f $file ]; then
	echo "`$SETCOLOR_FAILURE`Missing changelog file`$SETCOLOR_NORMAL`"
	echo "The changelog file `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` is missing"
	echo "This file is required for a valid first stable release"
	echo
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
    else
	if ! grep -E "Changes from $prev to $VERSION" $file &>/dev/null; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong/missing version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL`"
	    echo "The changelog should contain this line at the top:"
	    echo "Changes from `$SETCOLOR_EMPHASIZE`$prev`$SETCOLOR_NORMAL` to `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
	    echo
	    MAIN_ERROR="1"
	    [ -n "$EXIT_AT_ONCE" ] && exit 1
	fi
    fi
fi


# update/database/*/$version/

for driver in $DRIVERS; do

    if [ -z "$PREVIOUS_VERSION" ]; then
	prev="$LAST_STABLE"
    else
	prev="$PREVIOUS_VERSION"
    fi

    if [ "$DEVELOPMENT" == "true" ]; then
	file="update/database/$driver/$BRANCH_VERSION/unstable/dbupdate-$prev-to-$VERSION.sql"
    else
	file="update/database/$driver/$BRANCH_VERSION/dbupdate-$prev-to-$VERSION.sql"
    fi
    if [ ! -f $file ]; then
	echo "`$SETCOLOR_FAILURE`Missing database update file`$SETCOLOR_NORMAL`"
	echo "The database update file `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` is missing"
	echo "This file is required for a valid release"
	echo
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
    else
	if ! grep -E "UPDATE ezsite_data SET value='$VERSION' WHERE name='ezpublish-version';" $file &>/dev/null; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong/missing version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` for variable ezpublish-version"
	    echo "Should be:"
	    echo "UPDATE ezsite_data SET value='`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`' WHERE name='ezpublish-version';"
	    echo
	    MAIN_ERROR="1"
	    [ -n "$EXIT_AT_ONCE" ] && exit 1
	fi
	if ! grep -E "UPDATE ezsite_data SET value='$REAL_RELEASE' WHERE name='ezpublish-release';" $file &>/dev/null; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong/missing version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` for variable ezpublish-version"
	    echo "Should be:"
	    echo "UPDATE ezsite_data SET value='`$SETCOLOR_EMPHASIZE`$REAL_RELEASE`$SETCOLOR_NORMAL`' WHERE name='ezpublish-release';"
	    echo
	    MAIN_ERROR="1"
	    [ -n "$EXIT_AT_ONCE" ] && exit 1
	fi
    fi

    if [ -n "$FIRST_STABLE" ]; then
	prev="$LAST_STABLE"
	if [ "$DEVELOPMENT" == "true" ]; then
	    file="update/database/$driver/$BRANCH_VERSION/unstable/dbupdate-$prev-to-$VERSION.sql"
	else
	    file="update/database/$driver/$BRANCH_VERSION/dbupdate-$prev-to-$VERSION.sql"
	fi
	if [ ! -f $file ]; then
	    echo "`$SETCOLOR_FAILURE`Missing database update file`$SETCOLOR_NORMAL`"
	    echo "The database update file `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` is missing"
	    echo "This file is required for a valid first stable release"
	    echo "It should contain all the updates from all the previous development versions."
	    echo
	    MAIN_ERROR="1"
	    [ -n "$EXIT_AT_ONCE" ] && exit 1
	else
	    if ! grep -E "UPDATE ezsite_data SET value='$VERSION' WHERE name='ezpublish-version';" $file &>/dev/null; then
		echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
		echo "Wrong/missing version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` for variable ezpublish-version"
		echo "Should be:"
		echo "UPDATE ezsite_data SET value='`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`' WHERE name='ezpublish-version';"
		echo
		MAIN_ERROR="1"
		[ -n "$EXIT_AT_ONCE" ] && exit 1
	    fi
	    if ! grep -E "UPDATE ezsite_data SET value='$REAL_RELEASE' WHERE name='ezpublish-release';" $file &>/dev/null; then
		echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
		echo "Wrong/missing version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` for variable ezpublish-version"
		echo "Should be:"
		echo "UPDATE ezsite_data SET value='`$SETCOLOR_EMPHASIZE`$REAL_RELEASE`$SETCOLOR_NORMAL`' WHERE name='ezpublish-release';"
		echo
		MAIN_ERROR="1"
		[ -n "$EXIT_AT_ONCE" ] && exit 1
	    fi
	fi
    fi

done

if [ -n "$MAIN_ERROR" ]; then
    exit 1
fi

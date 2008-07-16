#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

# The last version which changelogs and db updates are related to
# For the first development release this should be empty, in
# wich case $LAST_STABLE is used.
PREVIOUS_VERSION="3.10.1rc1"
# The last version of the newest stable branch
LAST_STABLE="3.10.0"
# Set this to true if the LAST_STABLE has been modified from the last release
# This will be set to true automatically if the release is a final release
LAST_STABLE_CHANGED="false"

MAJOR=3
MINOR=10
RELEASE=1
# Starts at 1 for the first release in a branch and increases with one
REAL_RELEASE=5
STATE="rc2"
VERSION=$MAJOR"."$MINOR"."$RELEASE""$STATE
VERSION_ONLY=$MAJOR"."$MINOR
BRANCH_VERSION=$MAJOR"."$MINOR
# This is the version of the package format, this must be updated only if the format has changes.
PACKAGE_VERSION="3.5.2"
PACKAGE_DEVELOPMENT="false"
# Is automatically set to 'true' when $STATE contains some text, do not modify
DEVELOPMENT="false"
# Whether the previous release is a development release or not.
DEVELOPMENT_PREVIOUS="false"
# Is only true when the release is a final release (ie. the first of the stable ones)
# Will be automatically set to true when $RELEASE is 0 and $DEVELOPMENT is false
FINAL="false"
# If non-empty the script will check for changelog and db update from $LAST_STABLE
# NOTE: Don't use this anymore
FIRST_STABLE=""

if [ "$STATE" != "" ]; then
    DEVELOPMENT="true"
fi

if [ "$RELEASE" == "0" -a "$DEVELOPMENT" == "false" ]; then
    FINAL="true"
fi

if [ "$FINAL" == "true" ]; then
    LAST_STABLE_CHANGED="true"
fi

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --exit-at-once             Exit at the first version error"
	    echo "         --fix                      Try to fix the errors found"
            echo
	    exit 1
	    ;;
	--exit-at-once)
	    EXIT_AT_ONCE="1"
	    ;;
	--fix)
	    FIX="1"
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
    esac;
done

# bin/shell/common.sh

function check_common_version
{
    if ! grep "VERSION=\"$VERSION\"" bin/shell/common.sh &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable VERSION"
	    echo "Should be:"
	    echo "VERSION=\"`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`\""
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function check_common_version_release
{
    if ! grep "VERSION_RELEASE=\"$RELEASE\"" bin/shell/common.sh &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable VERSION_RELEASE"
	    echo "Should be:"
	    echo "VERSION_RELEASE=\"`$SETCOLOR_EMPHASIZE`$RELEASE`$SETCOLOR_NORMAL`\""
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function check_common_version_only
{
    if ! grep "VERSION_ONLY=\"$VERSION_ONLY\"" bin/shell/common.sh &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable VERSION_ONLY"
	    echo "Should be:"
	    echo "VERSION_ONLY=\"`$SETCOLOR_EMPHASIZE`$VERSION_ONLY`$SETCOLOR_NORMAL`\""
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function check_common_version_stable
{
    if ! grep "VERSION_STABLE=\"$LAST_STABLE\"" bin/shell/common.sh &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Stable version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable VERSION_STABLE"
	    echo "Should be:"
	    echo "VERSION_STABLE=\"`$SETCOLOR_EMPHASIZE`$LAST_STABLE`$SETCOLOR_NORMAL`\""
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function check_common_version_state
{
    if ! grep "VERSION_STATE=\"$STATE\"" bin/shell/common.sh &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version state mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version state in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable VERSION_STATE"
	    echo "Should be:"
	    echo "VERSION_STATE=\"`$SETCOLOR_EMPHASIZE`$STATE`$SETCOLOR_NORMAL`\""
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function check_common_version_previous
{
    if ! grep "VERSION_PREVIOUS=\"$PREVIOUS_VERSION\"" bin/shell/common.sh &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Previous version mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong previous version in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable VERSION_PREVIOUS"
	    echo "Should be:"
	    echo "VERSION_PREVIOUS=\"`$SETCOLOR_EMPHASIZE`$PREVIOUS_VERSION`$SETCOLOR_NORMAL`\""
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function check_common_version_development
{
    if ! grep "DEVELOPMENT=\"$DEVELOPMENT\"" bin/shell/common.sh &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Development type mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong development type in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable DEVELOPMENT"
	    echo "Should be:"
	    echo "DEVELOPMENT=\"`$SETCOLOR_EMPHASIZE`$DEVELOPMENT`$SETCOLOR_NORMAL`\""
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function check_common_version_development_previous
{
    if ! grep "DEVELOPMENT_PREVIOUS=\"$DEVELOPMENT_PREVIOUS\"" bin/shell/common.sh &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Previous development type mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong development type in `$SETCOLOR_EXE`bin/shell/common.sh`$SETCOLOR_NORMAL` for variable DEVELOPMENT_PREVIOUS"
	    echo "Should be:"
	    echo "DEVELOPMENT_PREVIOUS=\"`$SETCOLOR_EMPHASIZE`$DEVELOPMENT_PREVIOUS`$SETCOLOR_NORMAL`\""
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

check_common_version "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^VERSION="[^"]*"/VERSION="'$VERSION'"/' bin/shell/common.sh
    check_common_version ""
fi

check_common_version_release "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^VERSION_RELEASE="[^"]*"/VERSION_RELEASE="'$RELEASE'"/' bin/shell/common.sh
    check_common_version_release ""
fi

check_common_version_only "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^VERSION_ONLY="[^"]*"/VERSION_ONLY="'$VERSION_ONLY'"/' bin/shell/common.sh
    check_common_version_only ""
fi

check_common_version_stable "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^VERSION_STABLE="[^"]*"/VERSION_STABLE="'$LAST_STABLE'"/' bin/shell/common.sh
    check_common_version_stable ""
fi

check_common_version_state "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^VERSION_STATE="[^"]*"/VERSION_STATE="'$STATE'"/' bin/shell/common.sh
    check_common_version_state ""
fi

check_common_version_previous "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^VERSION_PREVIOUS="[^"]*"/VERSION_PREVIOUS="'$PREVIOUS_VERSION'"/' bin/shell/common.sh
    check_common_version_previous ""
fi

check_common_version_development "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^DEVELOPMENT="[^"]*"/DEVELOPMENT="'$DEVELOPMENT'"/' bin/shell/common.sh
    check_common_version_development ""
fi

check_common_version_development_previous "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^DEVELOPMENT_PREVIOUS="[^"]*"/DEVELOPMENT_PREVIOUS="'$DEVELOPMENT_PREVIOUS'"/' bin/shell/common.sh
    check_common_version_development_previous ""
fi

# bin/php/checkdbfiles.php

function check_dbfiles_update
{
    if ! grep "array(  *'$LAST_STABLE',  *'$VERSION'  *)" bin/php/checkdbfiles.php &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`DB update missing`$SETCOLOR_NORMAL`"
	    echo "Missing database update entry in `$SETCOLOR_EXE`bin/php/checkdbfiles.php`$SETCOLOR_NORMAL`"
	    echo "The \$versions""$MAJOR""$MINOR"" should contain:"
	    echo ",array( '$LAST_STABLE', '$VERSION' )"
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

if [ "$FINAL" == "true" ]; then
    check_dbfiles_update "$FIX"
fi

function check_dbfiles_update_prev
{
    if ! grep "array(  *'$PREVIOUS_VERSION',  *'$VERSION'  *)" bin/php/checkdbfiles.php &>/dev/null; then
       if [ -z "$1" ]; then
           echo "`$SETCOLOR_FAILURE`DB update missing`$SETCOLOR_NORMAL`"
           echo "Missing database update entry in `$SETCOLOR_EXE`bin/php/checkdbfiles.sh`$SETCOLOR_NORMAL`"
           echo "The \$versions""$MAJOR""$MINOR"" should contain:"
           echo ",array( '$PREVIOUS_VERSION', '$VERSION' )"
           echo
       fi
       MAIN_ERROR="1"
       [ -n "$EXIT_AT_ONCE" ] && exit 1
       if [ -n "$1" ]; then
           return 1
       fi
    fi
}


check_dbfiles_update_prev "$FIX"

# kernel/classes/ezpackage.php

function package_check_version
{
    if ! grep "define( 'EZ_PACKAGE_VERSION', '$PACKAGE_VERSION' );" kernel/classes/ezpackage.php &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`kernel/classes/ezpackage.php`$SETCOLOR_NORMAL` for variable EZ_PACKAGE_VERSION"
	    echo "Should be:"
	    echo "define( 'EZ_PACKAGE_VERSION', '`$SETCOLOR_EMPHASIZE`$PACKAGE_VERSION`$SETCOLOR_NORMAL`' );"
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function package_check_version_development
{
    if ! grep "define( 'EZ_PACKAGE_DEVELOPMENT', $PACKAGE_DEVELOPMENT );" kernel/classes/ezpackage.php &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Setting mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong setting in `$SETCOLOR_EXE`kernel/classes/ezpackage.php`$SETCOLOR_NORMAL` for variable EZ_PACKAGE_DEVELOPMENT"
	    echo "Should be:"
	    echo "define( 'EZ_PACKAGE_DEVELOPMENT', `$SETCOLOR_EMPHASIZE`$PACKAGE_DEVELOPMENT`$SETCOLOR_NORMAL` );"
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

package_check_version "$FIX"
if [ $? -ne 0 ]; then
    sed -i "s/^define( 'EZ_PACKAGE_VERSION', '[^']*' *)/define( 'EZ_PACKAGE_VERSION', '$PACKAGE_VERSION' )/" kernel/classes/ezpackage.php
    package_check_version ""
fi

package_check_version_development "$FIX"
if [ $? -ne 0 ]; then
    sed -i "s/^define( 'EZ_PACKAGE_DEVELOPMENT',[^)]*)/define( 'EZ_PACKAGE_DEVELOPMENT', $PACKAGE_DEVELOPMENT )/" kernel/classes/ezpackage.php
    package_check_version_development ""
fi

# lib/version.php

function lib_check_version_major
{
    if ! grep "define( \"EZ_SDK_VERSION_MAJOR\", $MAJOR );" lib/version.php &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_MAJOR"
	    echo "Should be:"
	    echo "define( \"EZ_SDK_VERSION_MAJOR\", `$SETCOLOR_EMPHASIZE`$MAJOR`$SETCOLOR_NORMAL` );"
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function lib_check_version_minor
{
    if ! grep "define( \"EZ_SDK_VERSION_MINOR\", $MINOR );" lib/version.php &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_MINOR"
	    echo "Should be:"
	    echo "define( \"EZ_SDK_VERSION_MINOR\", `$SETCOLOR_EMPHASIZE`$MINOR`$SETCOLOR_NORMAL` );"
	    echo
	fi
	MAIN_ERROR="1"
	[ -n $EXIT_AT_ONCE ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function lib_check_version_release
{
    if ! grep "define( \"EZ_SDK_VERSION_RELEASE\", $RELEASE );" lib/version.php &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_RELEASE"
	    echo "Should be:"
	    echo "define( \"EZ_SDK_VERSION_RELEASE\", `$SETCOLOR_EMPHASIZE`$RELEASE`$SETCOLOR_NORMAL` );"
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function lib_check_version_state
{
    if ! grep "define( \"EZ_SDK_VERSION_STATE\", '$STATE' );" lib/version.php &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_STATE"
	    echo "Should be:"
	    echo "define( \"EZ_SDK_VERSION_STATE\", '`$SETCOLOR_EMPHASIZE`$STATE`$SETCOLOR_NORMAL`' );"
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

function lib_check_version_development
{
    if ! grep "define( \"EZ_SDK_VERSION_DEVELOPMENT\", $DEVELOPMENT );" lib/version.php &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`lib/version.php`$SETCOLOR_NORMAL` for variable EZ_SDK_VERSION_DEVELOPMENT"
	    echo "Should be:"
	    echo "define( \"EZ_SDK_VERSION_DEVELOPMENT\", `$SETCOLOR_EMPHASIZE`$DEVELOPMENT`$SETCOLOR_NORMAL` );"
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

lib_check_version_major "$FIX"
if [ $? -ne 0 ]; then
    sed -i "s/^define( \"EZ_SDK_VERSION_MAJOR\", *[0-9][0-9]* *)/define( \"EZ_SDK_VERSION_MAJOR\", '$MAJOR' )/" lib/version.php
    lib_check_version_major ""
fi

lib_check_version_minor "$FIX"
if [ $? -ne 0 ]; then
    sed -i "s/^define( \"EZ_SDK_VERSION_MINOR\", *[0-9][0-9]* *)/define( \"EZ_SDK_VERSION_MINOR\", '$MINOR' )/" lib/version.php
    lib_check_version_minor ""
fi

lib_check_version_release "$FIX"
if [ $? -ne 0 ]; then
    sed -i "s/^define( \"EZ_SDK_VERSION_RELEASE\", *[0-9][0-9]* *)/define( \"EZ_SDK_VERSION_RELEASE\", '$RELEASE' )/" lib/version.php
    lib_check_version_release ""
fi

lib_check_version_state "$FIX"
if [ $? -ne 0 ]; then
    sed -i "s/^define( \"EZ_SDK_VERSION_STATE\", *'[^']*' *)/define( \"EZ_SDK_VERSION_STATE\", '$STATE' )/" lib/version.php
    lib_check_version_state ""
fi

lib_check_version_development "$FIX"
if [ $? -ne 0 ]; then
    sed -i "s/^define( \"EZ_SDK_VERSION_DEVELOPMENT\", *[a-zA-Z][a-zA-Z]* *)/define( \"EZ_SDK_VERSION_DEVELOPMENT\", $DEVELOPMENT )/" lib/version.php
    lib_check_version_development ""
fi


# doc/doxygen/Doxyfile

function doxygen_check_version
{
    if ! grep -E "PROJECT_NUMBER[ ]+=[ ]+$VERSION$" doc/doxygen/Doxyfile &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong version number in `$SETCOLOR_EXE`doc/doxygen/Doxyfile`$SETCOLOR_NORMAL` for variable PROJECT_NUMBER"
	    echo "Should be:"
	    echo "PROJECT_NUMBER         = `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

doxygen_check_version "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^PROJECT_NUMBER[ ]*=[ ]*.*$/PROJECT_NUMBER         = '$VERSION'/' doc/doxygen/Doxyfile
    doxygen_check_version ""
fi


# kernel/sql/common/cleandata.sql

SQL_LIST="kernel/sql/common/cleandata.sql"
SQL_ERROR_LIST=""

./bin/php/ezsqldumpschema.php --type=mysql --compatible-sql --format=local --output-types=data --output-sql --schema-file=share/db_schema.dba share/db_data.dba ".tmp.sql"
SQL_FILE_ERROR=""
if ! grep -e "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-version','$VERSION');" ".tmp.sql" &>/dev/null; then
    rm -f ".tmp.sql"
    MAIN_ERROR="1"
    SQL_ERROR="1"
    SQL_FILE_ERROR="1"
fi

if ! grep -e "INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-release','$REAL_RELEASE');" ".tmp.sql" &>/dev/null; then
    rm -f ".tmp.sql"
    MAIN_ERROR="1"
    SQL_ERROR="1"
    SQL_FILE_ERROR="1"
fi
[ -n "$SQL_FILE_ERROR" ] && SQL_ERROR_LIST="$SQL_ERROR_LIST .tmp.sql"
if [ -f ".tmp.sql" ]; then
    rm -f ".tmp.sql"
fi

if [ -n "$SQL_ERROR" ]; then

    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
    echo -n "Wrong/missing version number in `ez_color_file share/db_data.dba`"
    echo
    echo "For the table `ez_color_comment ezsite_data` and name `ez_color_comment ezpublish-version`"
    echo
    echo "Should be:"
    echo "ezpublish-version='`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`'"
    echo "and"
    echo "ezpublish-release='`$SETCOLOR_EMPHASIZE`$REAL_RELEASE`$SETCOLOR_NORMAL`'"
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

function lupdate_check_version
{
    if ! grep -E "static QString version = \"$VERSION\";" support/lupdate-ezpublish3/main.cpp &>/dev/null; then
	if [ -z "$1" ]; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong/missing version number in `$SETCOLOR_EXE`support/lupdate-ezpublish3/main.cpp`$SETCOLOR_NORMAL` for variable version"
	    echo "Should be:"
	    echo "static QString version = \"`$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`\""
	    echo
	fi
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
	if [ -n "$1" ]; then
	    return 1
	fi
    fi
}

lupdate_check_version "$FIX"
if [ $? -ne 0 ]; then
    sed -i 's/^static QString version = "[^"]*";/static QString version = "'$VERSION'";/' support/lupdate-ezpublish3/main.cpp
    lupdate_check_version ""
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

if [ "$DEVELOPMENT" == "false" -a "$LAST_STABLE_CHANGED" == "true" ]; then
    file="$files doc/changelogs/$BRANCH_VERSION/CHANGELOG-$LAST_STABLE-to-$VERSION"

    if [ ! -f $file ]; then
	echo "`$SETCOLOR_FAILURE`Missing changelog file`$SETCOLOR_NORMAL`"
	echo "The changelog file `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` is missing"
	echo "This file is required for a valid release"
	if [ -n "$FIRST_STABLE" ]; then
	    echo "It should contain all the changes from all the previous development releases"
	else
	    echo "It should contain the changes from the last stable $LAST_STABLE"
	    echo "This is usually all the changes from all the development changelogs"
	fi
	echo
	MAIN_ERROR="1"
	[ -n "$EXIT_AT_ONCE" ] && exit 1
    else
	if ! grep -E "Changes from $LAST_STABLE to $VERSION" $file &>/dev/null; then
	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
	    echo "Wrong/missing version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL`"
	    echo "The changelog should contain this line at the top:"
	    echo "Changes from `$SETCOLOR_EMPHASIZE`$LAST_STABLE`$SETCOLOR_NORMAL` to `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
	    echo
	    MAIN_ERROR="1"
	    [ -n "$EXIT_AT_ONCE" ] && exit 1
	fi
    fi
fi

# if [ -n "$FIRST_STABLE" ]; then
#     prev="$PREVIOUS_VERSION"
#     if [ "$DEVELOPMENT" == "true" ]; then
# 	file="doc/changelogs/$BRANCH_VERSION/unstable/CHANGELOG-$prev-to-$VERSION"
#     else
# 	file="doc/changelogs/$BRANCH_VERSION/CHANGELOG-$prev-to-$VERSION"
#     fi
#     if [ ! -f $file ]; then
# 	echo "`$SETCOLOR_FAILURE`Missing changelog file`$SETCOLOR_NORMAL`"
# 	echo "The changelog file `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL` is missing"
# 	echo "This file is required for a valid first stable release"
# 	echo
# 	MAIN_ERROR="1"
# 	[ -n "$EXIT_AT_ONCE" ] && exit 1
#     else
# 	if ! grep -E "Changes from $prev to $VERSION" $file &>/dev/null; then
# 	    echo "`$SETCOLOR_FAILURE`Version number mismatch`$SETCOLOR_NORMAL`"
# 	    echo "Wrong/missing version number in `$SETCOLOR_EXE`$file`$SETCOLOR_NORMAL`"
# 	    echo "The changelog should contain this line at the top:"
# 	    echo "Changes from `$SETCOLOR_EMPHASIZE`$prev`$SETCOLOR_NORMAL` to `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
# 	    echo
# 	    MAIN_ERROR="1"
# 	    [ -n "$EXIT_AT_ONCE" ] && exit 1
# 	fi
#     fi
# fi


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

    if [ "$DEVELOPMENT" == "false" -a "$LAST_STABLE_CHANGED" == "true" ]; then
	prev="$LAST_STABLE"
	file="update/database/$driver/$BRANCH_VERSION/dbupdate-$prev-to-$VERSION.sql"
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

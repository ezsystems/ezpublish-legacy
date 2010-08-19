#!/bin/sh

# Script for finding INI files not conforming to PHP syntax

. ./bin/shell/common.sh

if ! which php &>/dev/null; then
    echo "No PHP executable found, please add it to the path"
    exit 1
fi

BAD_FILES_NAMES=
BAD_FILES_SYNTAX=
BAD_FILES_PHPTAG=
ERRORS_ONLY=0
EXIT_ON_ERROR=0
GLOBAL_ERROR_NAMES=0
GLOBAL_ERROR_SYNTAX=0
GLOBAL_ERROR_PHPTAG=0
PRINT=1
SKIP_DIR=

INI_TYPE=".ini"
INI_APPEND_TYPE=".ini.append"
INI_PHP_TYPE=".ini.php"
INI_APPEND_PHP_TYPE=".ini.append.php"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --skip-dir=DIR             Do not check this directory. This is a regexp,"
	    echo "                                    multiple directories separated by \\\\\|"
            echo "         --exit-on-error            Exit on the first error"
            echo "         --errors-only              Will only print bad files, other texts are ommited"
            echo "         -q                         Quiet, do not print anything"
            echo
            echo "Example:"
            echo "$0 --skip-dir=share\\\\\|ezpaypal"
	    exit 1
	    ;;
	--skip-dir*)
	    if echo $arg | grep -e "--skip-dir=" >/dev/null; then
		SKIP_DIR=`echo $arg | sed 's/--skip-dir=//'`
	    else
		echo "No dir specified, ignoring"
	    fi
	    ;;
        --exit-on-error)
            EXIT_ON_ERROR=1
            ;;
        --errors-only)
            ERRORS_ONLY=1
            ;;
        -q)
            PRINT=0
            ;;
	*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
    esac;
done



# Looking for .ini and .ini.append: They should not exist!
files=`find . -regex ".*\$INI_TYPE$\|.*\$INI_APPEND_TYPE$" -type f -printf '%p;';`
error=0
IFS=";"
for file in $files; do
    if [ ! -z "$SKIP_DIR" ]; then
	if [ `ls $file | grep "$SKIP_DIR"` ]; then
	    continue;
	fi
    fi
    if [ $PRINT -eq 1 ]; then
	if [ $ERRORS_ONLY -eq 0 ]; then
	    echo -n $file
	fi
    fi
    IFS=" "
    error=1
    BAD_FILES_NAMES="$BAD_FILES_NAMES $file"
    if [ $PRINT -eq 1 ]; then
	if [ $ERRORS_ONLY -eq 0 ]; then
	    echo "`$MOVE_TO_COL`[`$SETCOLOR_FAILURE`Bad`$SETCOLOR_NORMAL`]";
	else
	    echo "`$SETCOLOR_FAILURE`$file`$SETCOLOR_NORMAL`";
	fi
    fi
    if [ $EXIT_ON_ERROR -eq 1 ]; then
	exit 1
    fi
done
if [ $error -eq 1 ]; then
    GLOBAL_ERROR_NAMES=1
fi



# Looking for .ini.php and .ini.append.php: They should have valid PHP syntax!
files=`find . -regex ".*\$INI_PHP_TYPE$\|.*\$INI_APPEND_PHP_TYPE$" -type f -printf '%p;';`
error=0
IFS=";"
for file in $files; do
    if [ ! -z "$SKIP_DIR" ]; then
	if [ `ls $file | grep "$SKIP_DIR"` ]; then
	    continue;
	fi
    fi
    if [ $PRINT -eq 1 ]; then
	if [ $ERRORS_ONLY -eq 0 ]; then
	    echo -n $file
	fi
    fi
    IFS=" "
    php -l $file &>/dev/null
    if [ $? -eq 0 ]; then
	if [ $PRINT -eq 1 ]; then
	    if [ $ERRORS_ONLY -eq 0 ]; then
		echo "`$MOVE_TO_COL`[`$SETCOLOR_SUCCESS`OK`$SETCOLOR_NORMAL`]"
	    fi
	fi
    else
	error=1
	BAD_FILES_SYNTAX="$BAD_FILES_SYNTAX $file"
	if [ $PRINT -eq 1 ]; then
	    if [ $ERRORS_ONLY -eq 0 ]; then
		echo "`$MOVE_TO_COL`[`$SETCOLOR_FAILURE`Bad`$SETCOLOR_NORMAL`]";
	    else
		echo "`$SETCOLOR_FAILURE`$file`$SETCOLOR_NORMAL`";
	    fi
	fi
	if [ $EXIT_ON_ERROR -eq 1 ]; then
	    exit 1
	fi
    fi
done
if [ $error -eq 1 ]; then
    GLOBAL_ERROR_SYNTAX=1
fi



# Looking for .ini.php and .ini.append.php: They should contain "<?php"!
files=`find . -regex ".*\$INI_PHP_TYPE$\|.*\$INI_APPEND_PHP_TYPE$" -type f -printf '%p;';`
error=0
IFS=";"
for file in $files; do
    if [ ! -z "$SKIP_DIR" ]; then
	if [ `ls $file | grep "$SKIP_DIR"` ]; then
	    continue;
	fi
    fi
    if [ $PRINT -eq 1 ]; then
	if [ $ERRORS_ONLY -eq 0 ]; then
	    echo -n $file
	fi
    fi
    IFS=" "
    grep "<?php" $file &>/dev/null
    if [ $? -eq 0 ]; then
	if [ $PRINT -eq 1 ]; then
	    if [ $ERRORS_ONLY -eq 0 ]; then
		echo "`$MOVE_TO_COL`[`$SETCOLOR_SUCCESS`OK`$SETCOLOR_NORMAL`]"
	    fi
	fi
    else
	error=1
	BAD_FILES_PHPTAG="$BAD_FILES_PHPTAG $file"
	if [ $PRINT -eq 1 ]; then
	    if [ $ERRORS_ONLY -eq 0 ]; then
		echo "`$MOVE_TO_COL`[`$SETCOLOR_FAILURE`Bad`$SETCOLOR_NORMAL`]";
	    else
		echo "`$SETCOLOR_FAILURE`$file`$SETCOLOR_NORMAL`";
	    fi
	fi
	if [ $EXIT_ON_ERROR -eq 1 ]; then
	    exit 1
	fi
    fi
done
if [ $error -eq 1 ]; then
    GLOBAL_ERROR_PHPTAG=1
fi



if [ $GLOBAL_ERROR_NAMES -eq 1 ]; then
    if [ $ERRORS_ONLY -eq 0 ]; then
        if [ $PRINT -eq 1 ]; then echo; fi
        if [ $PRINT -eq 1 ]; then echo "The following ini files do not end with .php:"; fi
        for file in $BAD_FILES_NAMES; do
            if [ $PRINT -eq 1 ]; then echo "`$SETCOLOR_FAILURE`$file`$SETCOLOR_NORMAL`"; fi
        done
    fi
fi

if [ $GLOBAL_ERROR_SYNTAX -eq 1 ]; then
    if [ $ERRORS_ONLY -eq 0 ]; then
        if [ $PRINT -eq 1 ]; then echo; fi
        if [ $PRINT -eq 1 ]; then echo "The following ini files have parser errors in them:"; fi
        for file in $BAD_FILES_SYNTAX; do
            if [ $PRINT -eq 1 ]; then echo "`$SETCOLOR_FAILURE`$file`$SETCOLOR_NORMAL`"; fi
        done
    fi
fi

if [ $GLOBAL_ERROR_PHPTAG -eq 1 ]; then
    if [ $ERRORS_ONLY -eq 0 ]; then
        if [ $PRINT -eq 1 ]; then echo; fi
        if [ $PRINT -eq 1 ]; then echo "The following ini files end with php, but contain no php tag:"; fi
        for file in $BAD_FILES_PHPTAG; do
            if [ $PRINT -eq 1 ]; then echo "`$SETCOLOR_FAILURE`$file`$SETCOLOR_NORMAL`"; fi
        done
    fi
fi

if [ $GLOBAL_ERROR_NAMES -eq 1 ]; then
    exit 1
fi

if [ $GLOBAL_ERROR_SYNTAX -eq 1 ]; then
    exit 1
fi

if [ $GLOBAL_ERROR_PHPTAG -eq 1 ]; then
    exit 1
fi

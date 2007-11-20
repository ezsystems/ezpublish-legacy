#!/bin/bash

# Script to find bad PHP files and print them out


. ./bin/shell/common.sh

if ! which php &>/dev/null; then
    echo "No PHP executable found, please add it to the path"
    exit 1
fi

DIRS=
EXIT_ON_ERROR=0
ERRORS_ONLY=0
VERBOSE=1
PRINT=1

for arg in $*; do
    case $arg in
        --help|-h)
            echo "Usage: $0 [options] DIR [DIR]..."
            echo
            echo "Options: -h"
            echo "         --help                     This message"
            echo "         --exit-on-error            Exit on the first error"
            echo "         --errors-only              Will only print bad files, other texts are ommited"
            echo "         -q                         Quiet, do not print anything"
            echo
            echo "Example:"
            echo "$0 lib"
            exit 1
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
        -*)
            echo "$arg: unkown option specified"
            $0 -h
            exit 1
            ;;
        *)
            DIRS="$DIRS $arg"
    esac;
done

if [ -z "$DIRS" ]; then
    $0 -h
    exit 1
fi

BAD_FILES=

global_error=0

for dir in $DIRS; do
    if [ $PRINT -eq 1 ]; then
        if [ $ERRORS_ONLY -eq 0 ]; then
            echo "Checking $dir"
        fi
    fi
    files=`find $dir -name \*.php -type f -printf '%p;'`
    error=0
    IFS=";"
    for file in $files; do
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
            BAD_FILES="$BAD_FILES $file"
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
        global_error=1
    fi
done

if [ $global_error -eq 1 ]; then
    if [ $ERRORS_ONLY -eq 0 ]; then
        if [ $PRINT -eq 1 ]; then echo; fi
        if [ $PRINT -eq 1 ]; then echo "The following files have parser errors in them:"; fi
        for file in $BAD_FILES; do
            if [ $PRINT -eq 1 ]; then echo "`$SETCOLOR_FAILURE`$file`$SETCOLOR_NORMAL`"; fi
        done
    fi
fi

if [ $global_error -eq 1 ]; then
    exit 1
fi

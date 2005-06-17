#!/bin/bash

DIST_PROP="ez:distribution"
DIST_DIR_PROP="ez:distribution_include_all"
DIST_TYPE='full'
VERSION="2.9-2"
NAME="ezpublish"
DEST_ROOT="/tmp"
DEFAULT_SVN_SERVER="http://svn.ez.no/svn/ezpublish"
DEFAULT_SVN_RELEASE_PATH="releases"

SVN_SERVER=""
REPOS_RELEASE="trunk"

FILES=""
DIST_ALL=""
RESET_MARKERS=""

NO_OUTPUT=""

. ./bin/shell/common.sh

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options] FILE..."
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         -q                         Be quiet"
	    echo "         --reset                    Removes all markers"
	    echo "         --release-sdk              Set for SDK release"
	    echo "         --release-full             Set for full release(default)"
	    echo "         --release-all-children     Set directory to include all subchildren"
	    echo "         --with-svn-server[=SERVER] Do actions on svn server directly"
	    echo "         --with-release=NAME        Use a specific release, default is trunk"
            echo
            echo "Example:"
            echo "$0 --release-sdk --with-svn-server" index.php
	    exit 1
	    ;;
	--release-sdk)
	    DIST_TYPE="sdk"
	    ;;
	-q)
	    NO_OUTPUT="true"
	    ;;
	--release-full)
	    DIST_TYPE="full"
	    ;;
	--reset)
	    RESET_MARKERS="true"
	    ;;
	--release-all-children)
	    DIST_ALL="true"
	    ;;
	--with-svn-server*)
	    if echo $arg | grep -e "--with-svn-server=" >/dev/null; then
		SVN_SERVER=`echo $arg | sed 's/--with-svn-server=/\1/'`
	    else
		SVN_SERVER=$DEFAULT_SVN_SERVER
	    fi
	    ;;
	--with-release*)
	    if echo $arg | grep -e "--with-release=" >/dev/null; then
		REPOS_RELEASE=`echo $arg | sed 's/--with-release=/\1/'`
	    else
		REPOS_RELEASE="trunk"
	    fi
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
	*)
	    FILES="$FILES $arg"
	    ;;
    esac;
done

if [ -z "$FILES" ]; then
    $0 -h
    exit 1
fi

BASE="$NAME-$DIST_TYPE-$VERSION"

if [ "$DIST_TYPE" == "sdk" ]; then
    if [ -z $NO_OUTPUT ]; then
	echo "Setting for SDK release"
    fi
elif [ "$DIST_TYPE" == "full" ]; then
    if [ -z $NO_OUTPUT ]; then
	echo "Setting for full release"
    fi
    BASE="$NAME-$VERSION"
else
    echo "Unknown release"
    exit 1
fi
DEST="$DEST_ROOT/$BASE"

SVN_PATH="."
if [ "$SVN_SERVER" != "" ]; then
    if [ -z $NO_OUTPUT ]; then
	echo "Using server $SVN_SERVER"
    fi
    if [ "$REPOS_RELEASE" == "" ]; then
        SVN_PATH="$SVN_SERVER/DEFAULT_SVN_RELEASE_PATH/$REPOS_RELEASE"
	if [ -z $NO_OUTPUT ]; then
	    echo "Using release $REPOS_RELEASE"
	fi
    else
        SVN_PATH="$SVN_SERVER/trunk"
	if [ -z $NO_OUTPUT ]; then
	    echo "Using trunk"
	fi
    fi
    if [ -z $NO_OUTPUT ]; then
	echo "SVN_PATH=$SVN_PATH"
    fi
else
    if [ -z $NO_OUTPUT ]; then
	echo "Using local copy"
    fi
fi

if [ -z $RESET_MARKERS ]; then
    echo -n "Adding"

    for file in $FILES; do
	SVN_FILE=$SVN_PATH/$file

	OLD_PROP=`svn propget $DIST_PROP $SVN_FILE 2>/dev/null | grep -v $DIST_TYPE`
	OLD_PROP=`echo $OLD_PROP && echo $DIST_TYPE`
	svn propset $DIST_PROP "$OLD_PROP" $SVN_FILE &>/dev/null
	if [ -d "$file" ]; then
	    echo -n " "`$SETCOLOR_DIR`"$file"`$SETCOLOR_NORMAL`
	elif [ -f "$file" ]; then
	    echo -n " "`$SETCOLOR_FILE`"$file"`$SETCOLOR_NORMAL`
	else
	    echo -n " $file"
	fi
	if [ ! -z $DIST_ALL ]; then
	    OLD_PROP=`svn propget $DIST_DIR_PROP $SVN_FILE 2>/dev/null | grep -v $DIST_TYPE`
	    OLD_PROP=`echo $OLD_PROP && echo $DIST_TYPE`
	    svn propset $DIST_DIR_PROP "$OLD_PROP" $SVN_FILE &>/dev/null
	    echo -n "/*"
	fi
    done
    echo
else
    echo "Resetting '$DIST_PROP' for $FILES"
    svn propdel $DIST_PROP $FILES
    echo "Resetting '$DIST_DIR_PROP' for $FILES"
    svn propdel $DIST_DIR_PROP $FILES
fi

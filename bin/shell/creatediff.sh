#!/bin/bash

NAME="ezpublish"
DEST_ROOT="/tmp/ezpublish-dist"
DIST_SRC=`pwd`

. ./bin/shell/common.sh

function get_major_version
{
    VERSION=`echo $1 | sed 's#\([0-9]\.[0-9]\)-[0-9]#\1#'`
    echo $VERSION
}

for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options] ARCHIVE FROM TO"
	    echo "Creates a diff and update file between two eZ Publish versions"
	    echo
	    echo "ARCHIVE: The archive with all previously released versions"
	    echo "FROM:    The eZ Publish version to start from"
	    echo "TO:      The eZ Publish version which want the updates to become"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
            echo
            echo "Example:"
            echo "$0 ezpublish-dist/ 3.2-2 3.2-3"
	    exit 1
	    ;;
	--*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
	*)
	    if [ -z $DIST_ARCHIVE ]; then
		DIST_ARCHIVE=$arg
	    elif [ -z $FROM ]; then
		FROM=$arg
	    elif [ -z $TO ]; then
		TO=$arg;
	    fi
	    ;;
    esac;
done

if [ -z $DIST_ARCHIVE ]; then
    echo "ARCHIVE parameter missing"
    $0 -h
    exit 1
fi

if [ -z $FROM ]; then
    echo "FROM parameter missing"
    $0 -h
    exit 1
fi

if [ -z $TO ]; then
    echo "TO parameter missing"
    $0 -h
    exit 1
fi

FROM_MAJOR=`get_major_version $FROM`
TO_MAJOR=`get_major_version $TO`

FROM_DIRNAME="ezpublish-$FROM"
TO_DIRNAME="ezpublish-$TO"

FROM_DIR="$DIST_ARCHIVE/$FROM_MAJOR/$FROM"
TO_DIR="$DIST_ARCHIVE/$TO_MAJOR/$TO"

FROM_FILE="$FROM_DIR/ezpublish-$FROM.tar.gz"
TO_FILE="$TO_DIR/ezpublish-$TO.tar.gz"

TO_DIFF="$TO_DIR/ezpublish-$FROM-to-$TO.diff.gz"
TO_DIFF2="$TO_DIR/ezpublish-$FROM-to-$TO.diff.bz2"
TMP_DIFF="$DEST_ROOT/ezpublish-$TO.diff"

TO_UPDATE_TGZ="$TO_DIR/ezpublish-update-$FROM-to-$TO.tar.gz"
TO_UPDATE_TBZ2="$TO_DIR/ezpublish-update-$FROM-to-$TO.tar.bz2"
TO_UPDATE_ZIP="$TO_DIR/ezpublish-update-$FROM-to-$TO.zip"

CREATED_FILES=""

if [ ! -f "$FROM_FILE" ]; then
    echo "The FROM file $FROM_FILE does not exist, cannot continue"
    exit 1
fi

if [ ! -f "$TO_FILE" ]; then
    echo "The TO file $TO_FILE does not exist, cannot continue"
    exit 1
fi

DEST_ROOT_FROM="$DEST_ROOT/$FROM_DIRNAME"
DEST_ROOT_TO="$DEST_ROOT/$TO_DIRNAME"

if [ ! -d $DEST_ROOT ]; then
    mkdir -p $DEST_ROOT
fi

if [ -d $DEST_ROOT_FROM ]; then
    echo "`$SETCOLOR_COMMENT`Removing old distribution $DEST_ROOT_FROM`$SETCOLOR_NORMAL`"
    rm -rf $DEST_ROOT_FROM
else
    echo "`$SETCOLOR_NEW`Creating distribution directory $DEST_ROOT_FROM`$SETCOLOR_NORMAL`"
fi

if [ -d $DEST_ROOT_TO ]; then
    echo "`$SETCOLOR_COMMENT`Removing old distribution $DEST_ROOT_TO`$SETCOLOR_NORMAL`"
    rm -rf $DEST_ROOT_TO
else
    echo "`$SETCOLOR_NEW`Creating distribution directory $DEST_ROOT_TO`$SETCOLOR_NORMAL`"
fi

echo "`$SETCOLOR_NEW`Unpacking distribution $FROM`$SETCOLOR_NORMAL`"
(cd $DEST_ROOT; tar xfz $FROM_FILE || exit 1)

echo "`$SETCOLOR_NEW`Unpacking distribution $TO`$SETCOLOR_NORMAL`"
(cd $DEST_ROOT; tar xfz $TO_FILE || exit 1)

(cd $DEST_ROOT; diff --new-file -d -U 2 -r $FROM_DIRNAME $TO_DIRNAME > $TMP_DIFF || exit 1)
echo "`$SETCOLOR_NEW`Creating diff file $TO_DIFF`$SETCOLOR_NORMAL`"
(cat $TMP_DIFF | gzip -c > $TO_DIFF || exit 1)
CREATED_FILES="$CREATED_FILES $TO_DIFF"
echo "`$SETCOLOR_NEW`Creating diff file $TO_DIFF2`$SETCOLOR_NORMAL`"
(cat $TMP_DIFF | bzip2 -c > $TO_DIFF2 || exit 1)
CREATED_FILES="$CREATED_FILES $TO_DIFF2"

PATCHED_FILES=`patch -d $DEST_ROOT/$FROM_DIRNAME --dry-run -p1 < $TMP_DIFF  | sed 's#^patching file ##'`
rm $TMP_DIFF || exit 1

UPDATE_DIRNAME="ezpublish-update-$FROM-to-$TO"
UPDATE_DIR="$DEST_ROOT/$UPDATE_DIRNAME"

if [ -d $UPDATE_DIR ]; then
    echo "`$SETCOLOR_COMMENT`Removing old update dir $UPDATE_DIR`$SETCOLOR_NORMAL`"
    rm -rf $UPDATE_DIR
fi
mkdir -p $UPDATE_DIR

echo -n "Copying:"
for file in $PATCHED_FILES; do
    dir=`dirname "$file"`
    if [ ! -d $UPDATE_DIR/$dir ]; then
	echo -n " `$SETCOLOR_DIR`$dir`$SETCOLOR_NORMAL`"
	mkdir -p $UPDATE_DIR/$dir
    fi
    echo -n " `$SETCOLOR_FILE`$file`$SETCOLOR_NORMAL`"
    cp "$DEST_ROOT/$TO_DIRNAME/$file" "$UPDATE_DIR/$file"
done
echo ", done"

echo "Creating `$SETCOLOR_FILE`$TO_UPDATE_TGZ`$SETCOLOR_NORMAL`"
(cd $DEST_ROOT; tar cfz "$TO_UPDATE_TGZ" "$UPDATE_DIRNAME/" || exit 1)
CREATED_FILES="$CREATED_FILES $TO_UPDATE_TGZ"

echo "Creating `$SETCOLOR_FILE`$TO_UPDATE_TBZ2`$SETCOLOR_NORMAL`"
(cd $DEST_ROOT; tar cfj "$TO_UPDATE_TBZ2" "$UPDATE_DIRNAME/" || exit 1)
CREATED_FILES="$CREATED_FILES $TO_UPDATE_TBZ2"

echo "Creating `$SETCOLOR_FILE`$TO_UPDATE_ZIP`$SETCOLOR_NORMAL`"
(cd $DEST_ROOT; zip -9 -r -q "$TO_UPDATE_ZIP" "$UPDATE_DIRNAME/" || exit 1)
CREATED_FILES="$CREATED_FILES $TO_UPDATE_ZIP"

TEST_DIR="$DEST_ROOT/a"

echo "Verifying patch and update files"
if [ -d $TEST_DIR ]; then
    rm -rf $TEST_DIR
fi
(cd $DEST_ROOT && cp -r $FROM_DIRNAME $TEST_DIR && cd $TEST_DIR && gunzip -c $TO_DIFF | patch -p1 &>/dev/null || exit 1)
if ! diff -U 2 -r -d "$DEST_ROOT/$TO_DIRNAME" "$TEST_DIR" &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`There was a difference when patching from the diff file, inspect the difference by running:`$SETCOLOR_NORMAL`"
    echo "diff --new-file -U 2 -r -d $DEST_ROOT/$TO_DIRNAME $UPDATE_DIR"
    exit 1
else
    echo "`$SETCOLOR_SUCCESS`Diff files were verified`$SETCOLOR_NORMAL`"
fi


if [ -d $TEST_DIR ]; then
    rm -rf $TEST_DIR
fi
(cd $DEST_ROOT && cp -r $FROM_DIRNAME $TEST_DIR && cd $UPDATE_DIR && cp -r * $TEST_DIR/ || exit 1)
if ! diff -U 2 -r -d "$DEST_ROOT/$TO_DIRNAME" "$TEST_DIR" &>/dev/null; then
    echo "`$SETCOLOR_FAILURE`There was a difference when using update file, inspect the difference by running:`$SETCOLOR_NORMAL`"
    echo "diff --new-file -U 2 -r -d $DEST_ROOT/$TO_DIRNAME $UPDATE_DIR"
    exit 1
else
    echo "`$SETCOLOR_SUCCESS`Update files were verified`$SETCOLOR_NORMAL`"
fi

if [ -d $TEST_DIR ]; then
    rm -rf $TEST_DIR
fi

rm -rf $UPDATE_DIR || exit 1
rm -rf $DEST_ROOT_FROM || exit 1
rm -rf $DEST_ROOT_TO || exit 1

echo
echo "`$SETCOLOR_NEW`The following files were created`$SETCOLOR_NORMAL`"
for file in $CREATED_FILES; do
    echo "`$SETCOLOR_FILE`$file`$SETCOLOR_NORMAL`"
done

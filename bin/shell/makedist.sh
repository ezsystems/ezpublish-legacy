#!/bin/bash

DIST_PROP="ez:distribute"
DIST_DIR_PROP="ez:distribute_recursive"
DIST_TYPE='sdk'
VERSION="2.9-2"
NAME="ezpublish"
DEST_ROOT="/tmp"
BASE="$NAME-$DIST_TYPE-$VERSION"
DEST="$DEST_ROOT/$BASE"

EXTRA_DIRS="settings/override doc/generated/html"

function make_dir
{
    DIR=`echo $1 | sed 's#^\./##'`
    if [ ! -d $DEST/$DIR ]; then
#	echo making dir $DIR
	mkdir $DEST/$DIR
    fi
}

function copy_file
{
    SRC_FILE=`echo $1 | sed 's#^\./##'`
    DST_FILE=$SRC_FILE
    cp -f $SRC_FILE $DEST/$DST_FILE

}

function scan_dir_normal
{
    DIR=$1
    for file in $DIR/*; do
	if [ -d $file ]; then
	    # Do not include .svn dirs
	    if [ "$file" != ".svn" ]; then
		make_dir $file
		scan_dir_normal $file
	    fi
	else
	    # Do not include temporary files
	    if ! echo $file | grep '~$' &>/dev/null; then
		copy_file $file
	    fi
	fi
    done
}

function scan_dir
{
    DIR=$1
    for file in $DIR/*; do
	DIST_PROP_TYPE=`svn propget $DIST_PROP $file 2>/dev/null`
	if [ $? -eq 0 ] && [ ! -z "$DIST_PROP_TYPE" ]; then
	    if echo $DIST_PROP_TYPE | grep $DIST_TYPE &>/dev/null; then
		DIST_DIR=`svn propget $DIST_DIR_PROP $file 2>/dev/null`
		if [ -d $file ]; then
		    make_dir $file
		    if [ -z $DIST_DIR ]; then
			scan_dir $file
		    else
			scan_dir_normal $file
		    fi
		else
		    copy_file $file
		fi
	    fi
	fi
    done
}

if [ -d $DEST ]; then
    echo "Removing old distribution"
    rm -rf $DEST
    mkdir $DEST
else
    echo "Creating distribution directory"
    mkdir $DEST
fi

echo "Copying directories and files"

scan_dir .

for file in $EXTRA_DIRS; do
    mkdir $DEST/$file
done

cp -f "doc/generated/html"/* $DEST/doc/generated/html

# cat index.php | sed 's/index.php/index_sdk.php/' > $DEST/index_sdk.php
cp -f index.php $DEST/index.php

echo "Looking for .svn directories"
(cd $DEST
    find . -name .svn -print)

echo "Applying executable properties"
(cd $DEST/bin
    chmod a+x modfix.sh)

echo "Creating tar.bz2 file"
(cd $DEST_ROOT
    tar cf $BASE.tar $BASE
    if [ -f $BASE.tar.bz2 ]; then
	rm -f $BASE.tar.bz2
    fi
    bzip2 $BASE.tar)

echo "Archive path is $DEST_ROOT/$BASE.tar.bz2"

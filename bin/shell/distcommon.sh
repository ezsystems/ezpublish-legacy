#!/bin/sh

DIST_PROP="ez:distribution"
DIST_DIR_PROP="ez:distribution_include_all"

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

function scan_dir_svn
{
    local file
    local DIR
    local DIST_PROP_TYPE
    local DIST_DIR

    DIR=$1

    for file in $DIR/* $DIR/.*; do

        # Skip '.svn', '.', '..'.
	[ ! -e "$file" -o "$file" = "$DIR/.svn" -o "$file" = "$DIR/.." -o "$file" = "$DIR/." ] && continue

        DIST_PROP_TYPE=`svn propget $DIST_PROP $file`
        if [ $? -eq 0 -a -n "$DIST_PROP_TYPE" ]; then
            if echo $DIST_PROP_TYPE | grep $DIST_TYPE &>/dev/null; then
                DIST_DIR=`svn propget $DIST_DIR_PROP $file 2>/dev/null`
                DIST_DIR_RECURSIVE=""
                if [ $? -eq 0 -a -n "$DIST_DIR" ]; then
                    if echo $DIST_DIR | grep $DIST_TYPE &>/dev/null; then
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
    done
}

function scan_dir_nosvn
{
    local file
    local DIR
    local DIST_PROP_TYPE
    local DIST_DIR

    DIR=$1

    for file in $DIR/* $DIR/.*; do

        # Skip '.svn', '.', '..'.
	[ ! -e "$file" -o "$file" = "$DIR/.svn" -o "$file" = "$DIR/.." -o "$file" = "$DIR/." ] && continue

        if [ -d "$file" ]; then
            # Copy directory recursively.
            echo -n " "`$SETCOLOR_DIR`"$file"`$SETCOLOR_NORMAL`"/"
            make_dir "$file"
            scan_dir_normal "$file"
        else
            # Copy file.
            echo -n " "`$SETCOLOR_FILE`"$file"`$SETCOLOR_NORMAL`
            copy_file "$file"
        fi
    done
}


function scan_dir
{
    local DIR
    local NOSVN

    DIR=$1
    NOSVN=$2

    if [ -n "$NOSVN" ]; then
        scan_dir_nosvn $DIR
    else
        scan_dir_svn $DIR
    fi
}


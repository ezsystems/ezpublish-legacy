#!/bin/bash

. ./bin/shell/common.sh

function show_help
{
    echo "Usage: $0 [OPTIONS] LOCALE [TARGET]"
    echo
    echo "Options: -h"
    echo "         --help                     This message"
    echo "         -f                         Force overwrite of existing files"
    echo "         --locale=LOCALE            Add locale to package (default is to add same locale as translation)"
}

for arg in $*; do
    case $arg in
	--help|-h)
            show_help
	    exit 1
	    ;;
	-f)
	    OVERWRITE="true"
	    ;;
	--locale=*)
	    if echo $arg | grep -e "--locale=" >/dev/null; then
		LOCALE_LIST="$LOCALE_LIST `echo $arg | sed 's/--locale=//'`"
	    fi
	    ;;
	-*)
	    echo "$arg: unknown option specified"
            show_help
	    exit 1
	    ;;
	*)
	    if [ -z "$LOCALE" ]; then
		LOCALE=$arg
	    else
		if [ -z "$TARGET" ]; then
		    TARGET=$arg
		fi
	    fi
	    ;;
    esac;
done

if [ -z "$LOCALE" ]; then
    show_help
    exit 1
fi

if [ -z "$LOCALE_LIST" ]; then
    LOCALE_LIST=$LOCALE
fi

if [ -z "$TARGET" ]; then
    TARGET=`pwd`
fi

filebase=$TARGET/ezpublish-$VERSION_ONLY-$LOCALE
file=$filebase.tar.gz
filezip=$filebase.zip

if [ -f "$file" ]; then
    if [ -z "$OVERWRITE" ]; then
	echo "File exists, will not overwrite:"
	echo $file
	echo "Use -f to force overwrite"
	exit 1
    fi
fi

if [ -f "$filezip" ]; then
    if [ -z "$OVERWRITE" ]; then
	echo "File exists, will not overwrite:"
	echo $filezip
	echo "Use -f to force overwrite"
	exit 1
    fi
fi

FILES=share/translations/$LOCALE/translation.ts

for l in $LOCALE_LIST; do
    FILES="$FILES share/locale/$l*.ini"
done

echo Adding files:
echo Creating $file
tar -zcvf $file $FILES
echo Creating $filezip
zip -9 $filezip $FILES

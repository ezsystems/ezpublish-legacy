#!/bin/bash

. ./bin/shell/common.sh

function show_help
{
    echo "Usage: $0 [OPTIONS] LOCALE [TARGET]"
    echo
    echo "Options: -h"
    echo "         --help                     This message"
    echo "         -f                         Force overwrite of existing files"
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
	-*)
	    echo "$arg: unkown option specified"
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

echo Adding files:
echo Creating $file
tar -zcvf $file share/locale/$LOCALE*.ini share/translations/$LOCALE/translation.ts
echo Creating $filezip
zip -9 $filezip share/locale/$LOCALE*.ini share/translations/$LOCALE/translation.ts

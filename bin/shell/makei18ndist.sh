#!/bin/bash

. ./bin/shell/common.sh

if [ "$1" = "" ] || [ "$1" = "-h" ] || [ "$1" = "--help" ]
then
    echo "Usage: $0 [options] locale-code target-dir"
    echo "Options:"
    echo "         -h | --help               Display this message"
    exit 0
fi

locale=$1
targetdir=$2
if [ "$2" = "" ]
then
    targetdir=`pwd`
fi

filebase=$targetdir/ezpublish-$VERSION_NICK-$locale
file=$filebase.tar.gz
filezip=$filebase.zip

if [ -f "$file" ]
then
    echo File exists, will not overwrite:
    echo $file
    exit 1
fi

if [ -f "$filezip" ]
then
    echo File exists, will not overwrite:
    echo $filezip
    exit 1
fi

echo Creating $file
echo Adding files:
tar -zcvf $file share/locale/$locale*.ini share/translations/$locale/translation.ts
zip -9 $filezip share/locale/$locale*.ini share/translations/$locale/translation.ts

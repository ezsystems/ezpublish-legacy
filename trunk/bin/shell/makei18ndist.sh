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

file=$targetdir/ezpublish-$VERSION_NICK-$locale.tar.gz

if [ -f "$file" ]
then
    echo File exists, will not overwrite:
    echo $file
    exit 1
fi

echo Creating $file
echo Adding files:
tar -zcvf $file share/locale/$locale*.ini share/translations/$locale/translation.ts

#!/bin/bash

CWD=`pwd`
DIR=`echo $0 | awk -F'modfix.sh' '{print $1}'`

cd $DIR

chmod 777 ../var/cache/
if [ ! -d ../var/cache/ini ]; then
    mkdir ../var/cache/ini
fi

chmod 777 ../var/cache/ini
chmod -R 777 ../var/storage

cd $CWD

#!/bin/sh

CWD=`pwd`
DIR=`echo $0 | awk -F'modfix.sh' '{print $1}'`

DIR_MODE=777 ##!
##!DIR_MODE=775
FILE_MODE=666 ##!
##!FILE_MODE=666

cd $DIR

chmod $DIR_MODE ../var/cache/
if [ ! -d ../var/cache/ini ]; then
    mkdir ../var/cache/ini
fi

chmod $DIR_MODE ../var/cache/ini
chmod -R $DIR_MODE ../var/storage

chmod -R $DIR_MODE settings

if [ ! -d ../var/cache/template/tree ]; then
    mkdir -p ../var/cache/template/tree
fi
if [ ! -d ../var/cache/template/process ]; then
    mkdir -p ../var/cache/template/process
fi
chmod -R $DIR_MODE ../var/cache/template

if [ ! -d ../var/log ]; then
    mkdir ../var/log
fi
chmod $DIR_MODE ../var/log
LOGFILES="error.log warning.log notice.log debug.log"
for LOGFILE in $LOGFILES; do
    LOGPATH="../var/log/$LOGFILE"
    if [ -f $LOGPATH ]; then
	chmod $FILE_MODE $LOGPATH
    fi
done


cd $CWD

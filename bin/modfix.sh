#!/bin/sh

DIR_MODE=777
FILE_MODE=666

if [ ! -f "index.php" -a \
     ! -f "access.php" -a \
     ! -f "pre_check.php" -a \
     ! -d "bin" -a \
     ! -d "lib" -a \
     ! -d "kernel" ] ; then
     echo "You seem to be in the wrong directory"
     echo "Place yourself in the eZ Publish 4 root directory and run ./bin/modfix.sh"
     exit 1
fi


if [ ! -d var/cache/ini ]; then
    mkdir var/cache/ini
    echo "Created var/cache/ini"
fi

if [ ! -d var/cache/texttoimage ]; then
    mkdir var/cache/texttoimage
    echo "Created var/cache/texttoimage"
fi

if [ ! -d var/cache/codepages ]; then
    mkdir var/cache/codepages
    echo "Created var/cache/codepages"
fi

if [ ! -d var/cache/translation ]; then
    mkdir var/cache/translation
    echo "Created var/cache/translation"
fi

if [ ! -d var/storage/packages ]; then
    mkdir var/storage/packages
    echo "Created var/storage/packages"
fi

if [ ! -d var/cache/template/tree ]; then
    mkdir -p var/cache/template/tree
    echo "Created var/cache/template/tree"
fi

if [ ! -d var/cache/template/process ]; then
    mkdir -p var/cache/template/process
    echo "Created var/cache/template/process"
fi

if [ ! -d var/log ]; then
    mkdir var/log
    echo "Created var/log"
fi

LOGFILES="error.log warning.log notice.log debug.log"
for LOGFILE in $LOGFILES; do
    LOGPATH="var/log/$LOGFILE"
    if [ -f $LOGPATH ]; then
    chmod $FILE_MODE $LOGPATH
    fi
done

chmod $DIR_MODE var
chmod $DIR_MODE design
chmod $DIR_MODE var/log
chmod $DIR_MODE var/cache/
chmod $DIR_MODE var/cache/ini
chmod $DIR_MODE var/cache/codepages
chmod $DIR_MODE var/cache/translation
chmod $DIR_MODE var/cache/texttoimage
chmod $DIR_MODE var/storage/packages

chmod -R $DIR_MODE settings
chmod -R $DIR_MODE autoload
chmod -R $DIR_MODE extension
chmod -R $DIR_MODE var/webdav
chmod -R $DIR_MODE var/storage
chmod -R $DIR_MODE var/cache/template


echo "
*** WARNING WARNING WARNING WARNING ***
This script sets ${DIR_MODE} as permissions in var/, design/, settings/, extension/ and autoload/.

THIS IS NOT SECURE!

Find the user and group for your web server and make them owner of all files 
in all of the above directories. You should be able to find this information 
in the configuration file for your web server.

For example:
If your web server user is apache and the group is apache, then run the following commands:
# chown -R apache.apache var design settings autoload extension
# chmod -R 770 var design settings autoload extension
"
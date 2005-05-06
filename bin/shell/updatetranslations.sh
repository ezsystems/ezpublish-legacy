#!/bin/bash

. ./bin/shell/common.sh

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Updates all translations by running ezlupdate"
	    echo "It will automatically figure out all available translations in the system"
	    echo
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo
#            echo "Example:"
#            echo "$0 --release-sdk --with-svn-server"
	    exit 1
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
    esac;
done

# Check ezlupdate
ezdist_check_ezlupdate

# Make sure ezlupdate is up to date, in case of source changes
echo -n "Updating ezlupdate"
EZLUPDATE_LOG=`ezdist_update_ezlupdate 2>/dev/stdout`
if [ $? -ne 0 ]; then
    echo "`$MOVE_TO_COL``$SETCOLOR_FAILURE`[ Failure ]`$SETCOLOR_NORMAL`"
    echo
    echo "$EZLUPDATE_LOG"
    exit 1
fi
echo "`$MOVE_TO_COL``$SETCOLOR_SUCCESS`[ Success ]`$SETCOLOR_NORMAL`"

dir=`pwd`
echo -n "Processing:"
cd share/translations
for translation in *; do
    echo -n " `$POSITION_STORE``$SETCOLOR_EMPHASIZE`$translation`$SETCOLOR_NORMAL`"

    if [ "$translation" == "untranslated" ]; then
	(cd  $dir && $dir/bin/linux/ezlupdate -u &>/dev/null )
	if [ $? -ne 0 ]; then
	    echo
	    echo "Error updating translations"
	    exit 1
	fi
    else
	(cd  $dir && $dir/bin/linux/ezlupdate "$translation" &>/dev/null )
	if [ $? -ne 0 ]; then
	    echo
	    echo "Error updating translations"
	    exit 1
	fi
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_COMMENT`$translation`$SETCOLOR_NORMAL`"
done
cd $dir
echo

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
ez_result_output $? "$EZLUPDATE_LOG" || exit 1

dir=`pwd`
echo -n "Processing: "
cd share/translations

TR_COUNTER=0
TR_TOTAL=0
last_len=0
echo -n "`ez_store_pos`"
translations=""
for translation in *; do
    echo "$translation" | grep -E '^([a-zA-Z][a-zA-Z][a-zA-Z]-[a-zA-Z][a-zA-Z]|untranslated)$' &>/dev/null
    if [ $? -eq 0 ]; then
	translations="$translations $translation"
	TR_TOTAL=`expr $TR_TOTAL + 1`
    fi
done

for translation in $translations; do
    TR_COUNTER=`expr $TR_COUNTER + 1`
    current_len=${#translation}
    if [ $last_len -eq 0 ]; then
	last_len=$current_len
    fi
    text="$translation"
    iterator=$current_len
    while [ $iterator -lt $last_len ]; do
	text="$text "
	iterator=`expr $iterator + 1`
    done
    echo -n "`ez_restore_pos``ez_store_pos`[$TR_COUNTER/$TR_TOTAL] `$SETCOLOR_EMPHASIZE`$text`$SETCOLOR_NORMAL`"

    if [ "$translation" == "untranslated" ]; then
        (cd  $dir && $dir/bin/linux/ezlupdate -u &>/dev/null )
        if [ $? -ne 0 ]; then
	    ez_result_output 1 "Error updating translations for untranslated" || exit 1
        fi
    else
        (cd  $dir && $dir/bin/linux/ezlupdate "$translation" &>/dev/null )
        if [ $? -ne 0 ]; then
	    ez_result_output 1 "Error updating translations for $translation" || exit 1
        fi
    fi
    echo -n "`ez_restore_pos``ez_store_pos`[$TR_COUNTER/$TR_TOTAL] `$SETCOLOR_COMMENT`$text`$SETCOLOR_NORMAL`"
    last_len=$current_len
done
cd $dir
text=""
iterator=0
while [ $iterator -lt $last_len ]; do
    text="$text "
    iterator=`expr $iterator + 1`
done
echo -n "`ez_restore_pos``ez_store_pos`$text"
echo -n "`ez_restore_pos`[$TR_TOTAL/$TR_TOTAL] translations"
ez_result_output 0 "" || exit 1

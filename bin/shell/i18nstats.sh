#!/bin/bash

function show_help
{
    echo "Usage: $0 [LOCALE1] [LOCALE2] [LOCALE3] ..."
    echo
    echo "Options: -h"
    echo "         --help   This message"
}

for arg in $*; do
    case $arg in
    --help|-h)
        show_help
        exit 1
        ;;
    -*)
        echo "$arg: unknown option specified"
        show_help
        exit 1
        ;;
    *)
        LOCALE_LIST="$LOCALE_LIST `echo $arg`"
        ;;
    esac;
done

if [ -z "$LOCALE_LIST" ]; then
    echo "No locales given, will use all existing translations."
    LOCALE_LIST=`ls share/translations`
fi

echo "+---------------------------------------------------+"
echo "| Locale | Total | Obsolete | Finished | Unfinished |"
echo "+---------------------------------------------------+"
for LOCALE in $LOCALE_LIST; do
    if [ $LOCALE = "untranslated" ]; then
        continue
    fi
    FILE=share/translations/$LOCALE/translation.ts
    if [ -f "$FILE" ]; then
        printf "| %-6s " $LOCALE
        printf "| %5s " `grep "<translation" $FILE | wc -l`
        printf "| %8s " `grep "<translation" $FILE | grep obsolete | wc -l`
        printf "| %8s " `grep "<translation" $FILE | grep -v obsolete | grep -v unfinished | wc -l`
        printf "| %10s |\n" `grep "<translation" $FILE | grep unfinished | wc -l`
    else
        printf "| %-6s " $LOCALE
        printf "| %-31s" "No translation found"
        printf "%11s\n" "|"
    fi
done
echo "+---------------------------------------------------+"

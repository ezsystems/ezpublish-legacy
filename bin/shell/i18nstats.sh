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

echo "+--------+-------+----------+----------+------------+------------+"
echo "| Locale | Total | Obsolete | Finished | Unfinished | % Complete |"
echo "+--------+-------+----------+----------+------------+------------+"
for LOCALE in $LOCALE_LIST; do
    if [ $LOCALE = "untranslated" ]; then
        continue
    fi
    FILE=share/translations/$LOCALE/translation.ts
    if [ -f "$FILE" ]; then
        FINISHED=`grep "<translation" $FILE | grep -v obsolete | grep -v unfinished | wc -l`
        UNFINISHED=`grep "<translation" $FILE | grep unfinished | wc -l`
        printf "| %-6s " $LOCALE
        printf "| %5s " `grep "<translation" $FILE | wc -l`
        printf "| %8s " `grep "<translation" $FILE | grep obsolete | wc -l`
        printf "| %8s " $FINISHED
        printf "| %10s " $UNFINISHED
        printf "| %9i%% |\n" $(( ( $FINISHED * 100 ) / ( $FINISHED + $UNFINISHED ) ))
    else
        printf "| %-6s " $LOCALE
        printf "| No translation found %34s\n" "|"
    fi
done
echo "+--------+-------+----------+----------+------------+------------+"

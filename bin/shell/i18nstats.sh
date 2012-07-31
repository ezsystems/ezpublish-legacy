#!/bin/bash

function show_help
{
    echo "Usage: $0 [LOCALE1] [LOCALE2] [LOCALE3] ..."
    echo
    echo "Options: -h"
    echo "         --help   This message"
    echo "         --extension [extension] Stats for extension $extension"
}

for arg in $*; do
    case $arg in
    --help|-h)
        show_help
        exit 1
        ;;
    --extension=*)
        if echo $arg | grep -e "--extension=" >/dev/null; then
            EXTENSION=`echo $arg | sed 's/--extension=//'`
        fi
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
    if [ -n "$EXTENSION" ]; then
	LOCALE_LIST=`ls extension/$EXTENSION/translations`
    else
	LOCALE_LIST=`ls share/translations`
    fi
fi

echo "+--------+-------+----------+----------+------------+------------+"
echo "| Locale | Total | Obsolete | Finished | Unfinished | % Complete |"
echo "+--------+-------+----------+----------+------------+------------+"
for LOCALE in $LOCALE_LIST; do
    if [ $LOCALE = "untranslated" ]; then
        continue
    fi
    if [ -n "$EXTENSION" ]; then
        FILE=extension/$EXTENSION/translations/$LOCALE/translation.ts
    else
        FILE=share/translations/$LOCALE/translation.ts
    fi
    
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

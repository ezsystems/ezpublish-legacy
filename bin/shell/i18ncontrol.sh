#!/bin/bash

# find the files that we should process
echo "Searching for template files"
FILES=`find design -name "*.tpl"`
for file in $FILES; do
    echo "###### $file ######"
    ./bin/awk/i18ncontrol.awk $file
    echo "-------------------"
    echo
    echo
done


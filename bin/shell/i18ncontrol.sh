#!/bin/bash

# find the files that we should process
echo "Searching for template files"
FILES=`find design -name "*.tpl"`
for file in $FILES; do
    ./bin/awk/i18ncontrol.awk $file
done


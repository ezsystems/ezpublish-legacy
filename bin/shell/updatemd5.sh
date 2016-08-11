#!/usr/bin/env bash

set -e

echo -n "Creating MD5 checksums"
MD5_FILES=`find * -wholename "./var" -prune -o -wholename "*/.svn" -prune -o -type f -print | grep -F README.md -v | grep -F composer.json -v | grep -F share/filelist.md5 -v  | sort`

for MD5_FILE in $MD5_FILES; do
    MD5_LINE=$(md5sum "$MD5_FILE")
    if grep -qF "$MD5_FILE" share/filelist.md5; then
        MD5_RE=$(echo "$MD5_FILE" | sed 's/[/]/\\\//g')
        MD5_LINE_RE=$(echo "$MD5_LINE" | sed 's/[/]/\\\//g')
        sed -ri 's/[0-9a-f]+ +'"$MD5_RE"'$/'"$MD5_LINE_RE"'/' share/filelist.md5
    else
        echo "$MD5_LINE" >> share/filelist.md5
    fi
done
echo

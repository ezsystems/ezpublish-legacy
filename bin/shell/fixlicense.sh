#!/bin/bash

for i in `rgrep -R '*.php' -i -l 'http://ez.no/home/licences/professional' .`; do
    echo "Fixing $i"
    mv "$i" "$i.tmp"
    cat "$i.tmp" | sed 's#http://ez.no/home/licences/professional#http://ez.no/products/licences/professional#' > "$i"
done


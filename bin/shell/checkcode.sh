#!/bin/sh

. ./bin/shell/common.sh

echo "Searching for PHP files that doesn't conform to 'short_open_tag = Off'"

find . -name \*.php -exec grep -n -H '<?[ '"\t"']*$' {} \;

echo
echo "Check codingstandard"
echo "Searching for bad 'foreach' statements"
find . -name \*.php -exec ./bin/shell/checkforeach.sh {} \;


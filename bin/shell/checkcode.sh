#!/bin/sh

. ./bin/shell/common.sh

echo "Searching for PHP files that doesn't conform to 'short_open_tag = Off'"

find . -name \*.php -exec grep -n -H '<?[ '"\t"']*$' {} \;

echo
echo "Check codingstandard"
echo "Searching for bad 'foreach' statements"
find . -name \*.php -exec ./bin/shell/checkforeach.sh {} \;

echo "Check for elsif"

echo "Check for && and ||"

echo
echo "Check for global variable access"
find . -name \*.php -exec grep -n -H 'getenv' {} \;


echo "Checking doxygen code"
echo "Check for proper use of \return"


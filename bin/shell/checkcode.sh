#!/bin/sh

. ./bin/shell/common.sh

# echo "Searching for PHP files that doesn't conform to 'short_open_tag = Off'"

# find . -name \*.php -exec grep -n -H '<?[ '"\t"']*$' {} \;

echo
echo "Check that code conforms to 'allow_call_time_reference = Off'"
find . -name \*.php -exec ./bin/shell/checkcalltimeref.sh {} \;

# echo
# echo "Check for tabs"

# echo
# echo "Check for unneeded print statements"
# find . -name \*.php -exec ./bin/shell/checkprint.sh {} \;

# echo
# echo "Check codingstandard"
# echo "Searching for bad 'foreach' statements"
# find . -name \*.php -exec ./bin/shell/checkforeach.sh {} \;

# echo "Check for elsif"

# echo "Check for && and ||"

# echo
# echo "Check for global variable access"
# find . -name \*.php -exec grep -n -H 'getenv' {} \;

# echo "Check for _SERVER, _COOKIE and _ENV access"
# find . -name \*.php -exec grep -n -H -E '_SERVER|_COOKIE|_ENV' {} \;

# echo "Check for GLOBALS access"
# rgrep -R '*.php' -n 'GLOBALS' . | grep -v "GLOBALS\[[\"']eZ"

# echo "Checking doxygen code"
# echo "Check for proper use of \return"
# echo "Checking for old doc code"
# # //!! eZKernel
# # //! The class eZURLOperator does
# # /*!

# # */

# echo "Check for URIs which will not work with nVH setups"
# #  <a href=\"\">
# rgrep -R '*.tpl' -n -i '<a  *href="' . | grep -v -E 'href="(http:|mailto:)'
# rgrep -R '*.tpl' -n -i '<form .*action="' .
# rgrep -R '*.php' -n -i ' header *(' .

# # echo "Check for bad prints"
# # rgrep -R '*.php' -n -i 'print' .


echo "Checking copyright dates"
rgrep -R '*' -n -i '1999-200[012]' .

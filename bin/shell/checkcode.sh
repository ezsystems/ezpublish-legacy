#!/bin/sh

. ./bin/shell/common.sh

# echo "Searching for PHP files that doesn't conform to 'short_open_tag = Off'"

# find . -name \*.php -exec grep -n -H '<?[ '"\t"']*$' {} \;

echo
echo "Check for unneeded print statements"
find . -name \*.php -exec ./bin/shell/checkprint.sh {} \;

# echo
# echo "Check codingstandard"
# echo "Searching for bad 'foreach' statements"
# find . -name \*.php -exec ./bin/shell/checkforeach.sh {} \;

# echo "Check for elsif"

# echo "Check for && and ||"

# echo
# echo "Check for global variable access"
# find . -name \*.php -exec grep -n -H 'getenv' {} \;

# echo "Check for REQUEST_URI"
# rgrep -R '*.php' -n 'REQUEST_URI' .

# echo "Checking doxygen code"
# echo "Check for proper use of \return"
# echo "Checking for old doc code"
# # //!! eZKernel
# # //! The class eZURLOperator does
# # /*!

# # */

# echo "Check for URIs which will not work with nVH setups"
# #  <a href=\"\">
# rgrep -R '*.tpl' -n -i '<a  *href="' .
# rgrep -R '*.php' -n -i 'header' .

# echo "Check for bad prints"
# rgrep -R '*.php' -n -i 'print' .

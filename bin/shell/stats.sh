#!/bin/bash

. ./bin/shell/common.sh

echo "eZ Publish $VERSION statistics"
echo

# ***** Check PHP code *****

echo "PHP code:"
echo -n " - lines:"
PHP_LINES=`find . -name \*.php ! -path ./var\* ! -path ./tests/eztemplate/compilation/\* -exec cat {} \; | wc -l`
echo "         $PHP_LINES"

echo -n " - longest line:"
PHP_LONGEST_LINE=`find . -name \*.php ! -path ./var\* ! -path ./tests/eztemplate/compilation/\* -exec cat {} \; | wc -L`
echo "  $PHP_LONGEST_LINE"

echo -n " - characters:"
PHP_CHARS=`find . -name \*.php ! -path ./var\* ! -path ./tests/eztemplate/compilation/\* -exec cat {} \; | wc -m`
echo "    $PHP_CHARS"

echo -n " - words:"
PHP_WORDS=`find . -name \*.php ! -path ./var\* ! -path ./tests/eztemplate/compilation/\* -exec cat {} \; | wc -w`
echo "         $PHP_WORDS"

echo

# ***** Check C/C++ code *****

echo "C/C++ code:"
echo -n " - lines:"
CPP_LINES=`find . \( -name \*.c -o -name \*.cc -o -name \*.cpp -o -name \*.h -o -name \*.hh -o -name \*.hpp \) ! -path ./var\* ! -path ./var/\* -exec cat {} \; | wc -l`
echo "         $CPP_LINES"

echo -n " - longest line:"
CPP_LONGEST_LINE=`find . \( -name \*.c -o -name \*.cc -o -name \*.cpp -o -name \*.h -o -name \*.hh -o -name \*.hpp \) ! -path ./var\* ! -path ./var/\* -exec cat {} \; | wc -L`
echo "  $CPP_LONGEST_LINE"

echo -n " - characters:"
CPP_CHARS=`find . \( -name \*.c -o -name \*.cc -o -name \*.cpp -o -name \*.h -o -name \*.hh -o -name \*.hpp \) ! -path ./var\* ! -path ./var/\* -exec cat {} \; | wc -m`
echo "    $CPP_CHARS"

echo -n " - words:"
CPP_WORDS=`find . \( -name \*.c -o -name \*.cc -o -name \*.cpp -o -name \*.h -o -name \*.hh -o -name \*.hpp \) ! -path ./var\* ! -path ./var/\* -exec cat {} \; | wc -w`
echo "         $CPP_WORDS"

echo

# ***** Check Template code *****

echo "Template code:"
echo -n " - lines:"
TPL_LINES=`find . -name \*.tpl ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -l`
echo "         $TPL_LINES"

echo -n " - longest line:"
TPL_LONGEST_LINE=`find . -name \*.tpl ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -L`
echo "  $TPL_LONGEST_LINE"

echo -n " - characters:"
TPL_CHARS=`find . -name \*.tpl ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -m`
echo "    $TPL_CHARS"

echo -n " - words:"
TPL_WORDS=`find . -name \*.tpl ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -w`
echo "         $TPL_WORDS"

echo

# ***** Check CSS code *****

echo "CSS code:"
echo -n " - lines:"
CSS_LINES=`find . -name \*.css ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -l`
echo "         $CSS_LINES"

echo -n " - longest line:"
CSS_LONGEST_LINE=`find . -name \*.css ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -L`
echo "  $CSS_LONGEST_LINE"

echo -n " - characters:"
CSS_CHARS=`find . -name \*.css ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -m`
echo "    $CSS_CHARS"

echo -n " - words:"
CSS_WORDS=`find . -name \*.css ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -w`
echo "         $CSS_WORDS"

echo

# ***** Check INI settings *****

echo "INI settings:"
echo -n " - lines:"
INI_LINES=`find . \( -name \*.ini -o -name \*.ini.append -o -name \*.ini.append.php \) ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -l`
echo "         $INI_LINES"

echo -n " - longest line:"
INI_LONGEST_LINE=`find . \( -name \*.ini -o -name \*.ini.append -o -name \*.ini.append.php \) ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -L`
echo "  $INI_LONGEST_LINE"

echo -n " - characters:"
INI_CHARS=`find . \( -name \*.ini -o -name \*.ini.append -o -name \*.ini.append.php \) ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -m`
echo "    $INI_CHARS"

echo -n " - words:"
INI_WORDS=`find . \( -name \*.ini -o -name \*.ini.append -o -name \*.ini.append.php \) ! -path ./var\* ! -path ./packages/\* -exec cat {} \; | wc -w`
echo "         $INI_WORDS"

echo

#!/bin/bash

. ./bin/shell/common.sh

echo "eZ publish $VERSION statistics"
echo

echo -n "PHP code:      "
PHP_LINES=`find . -name \*.php -exec cat {} \; | wc -l`
echo "$PHP_LINES lines"

echo -n "Template code: "
TPL_LINES=`find . -name \*.tpl -exec cat {} \; | wc -l`
echo "$TPL_LINES lines"

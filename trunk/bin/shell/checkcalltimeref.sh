#!/bin/sh

grep -n -H -E '[a-zA-Z0-9_]+ *\( [^&]*&\$[a-zA-Z_]+' $1 | grep -v -E 'function &?[a-zA-Z0-9]+ *\('

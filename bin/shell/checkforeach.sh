#!/bin/sh

grep -n -H -E 'foreach' $1 | grep -v -E 'foreach \( ([a-zA-Z_]+\( \$[a-zA-Z_]+ \)|\$[a-zA-Z_]+(->[a-zA-Z_]+\([^)]*\))?) as \$[a-zA-Z_]+( => \$[a-zA-Z_]+)? \) *[^{]? *(//.*)?$'

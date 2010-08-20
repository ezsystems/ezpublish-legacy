#!/bin/sh

if ! ( echo $1 | grep -E '(sdk|scrap|bf)/' &>/dev/null ); then grep -n -H -i "[ \t]print *(" $1; fi

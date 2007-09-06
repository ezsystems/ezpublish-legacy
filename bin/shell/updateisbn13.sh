#!/bin/sh

# This script will download updated data for isb13 and
# redump kernel/classes/datatypes/ezisbn/share/db_data.dba

RANGES_URL="http://www.isbn-international.org/converter/ranges.js"
RANGES_FILE="ranges.js"
REDUMP_FILE="bin/php/ezsqldumpisbndata.php"

TEMP_FILES="$RANGES_FILE"

. ./bin/shell/common.sh

# show some info
echo "Updating ISBN13 data"

# download ranges
echo "Downloading ranges info from `$SETCOLOR_FILE`$RANGES_URL`$SETCOLOR_NORMAL`"
wget "$RANGES_URL" -O "$RANGES_FILE"

# creating data for redump
echo "Creating redump data"
php bin/php/updateisbn13.php --file="$RANGES_FILE"

# redumping
echo "Redumping..."
php bin/php/ezsqldumpisbndata.php

# remove tmp files
echo "Removing tmp files: `$SETCOLOR_FILE`$TEMP_FILES`$SETCOLOR_NORMAL`"
echo "$TEMP_FILES" | xargs rm -f

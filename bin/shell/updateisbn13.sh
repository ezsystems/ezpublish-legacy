#!/bin/sh

# This script will download updated data for isb13 and
# redump kernel/classes/datatypes/ezisbn/share/db_data.dba

RANGES_URL="http://www.isbn-international.org/converter/ranges.js"
RANGES_FILE="ranges.js"
REDUMP_FILE="bin/php/ezsqldumpisbndata.php"

. ./bin/shell/common.sh

for arg in $*; do
    case $arg in
        --help|-h)
            echo "Usage: $0 [options]"
            echo
            echo "Options: -h"
            echo "         --help                     This message"
            echo "         --file                     Javascript file with isbn data"
            echo "         --db-database              Database name"
            echo "         --db-user                  Database user"
            echo "         --db-password              Database password"
            echo "         --db-driver                Database driver"
            echo
            exit 1
            ;;
        --db-database=*)
            if echo $arg | grep -e "--db-database=" >/dev/null; then
                DB_DATABASE=`echo $arg | sed 's/--db-database=//'`
            fi
            ;;
        --db-user=*)
            if echo $arg | grep -e "--db-user=" >/dev/null; then
                DB_USER=`echo $arg | sed 's/--db-user=//'`
            fi
            ;;
        --db-password=*)
            if echo $arg | grep -e "--db-password=" >/dev/null; then
                DB_PASSWORD=`echo $arg | sed 's/--db-password=//'`
            fi
            ;;
        --db-driver=*)
            if echo $arg | grep -e "--db-driver=" >/dev/null; then
                DB_DRIVER=`echo $arg | sed 's/--db-driver=//'`
            fi
            ;;
        --file=*)
            if echo $arg | grep -e "--file=" >/dev/null; then
                CUSTOM_RANGES_FILE=`echo $arg | sed 's/--file=//'`
                RANGES_FILE=""
            fi
            ;;

        --*)
            echo "$arg: unkown long option specified"
            $0 -h
            exit 1
            ;;
        -*)
            echo "$arg: unkown option specified"
            $0 -h
            exit 1
            ;;
        *)
            echo "Type '$0 --help\` for a list of options to use."
            exit 1
            ;;
    esac;
done

# create option strings

if [ -n "$DB_DATABASE" ]; then
    OPTIONS="$OPTIONS --db-database=$DB_DATABASE"
fi

if [ -n "$DB_USER" ]; then
    OPTIONS="$OPTIONS --db-user=$DB_USER"
fi

if [ -n "$DB_PASSWORD" ]; then
    OPTIONS="$OPTIONS --db-password=$DB_PASSWORD"
fi

if [ -n "$DB_DRIVER" ]; then
    OPTIONS="$OPTIONS --db-driver=$DB_DRIVER"
fi

DUMP_OPTIONS="$OPTIONS"

if [ -n "$RANGES_FILE" ]; then
    OPTIONS="$OPTIONS --file=$RANGES_FILE"
fi

if [ -n "$CUSTOM_RANGES_FILE" ]; then
    OPTIONS="$OPTIONS --file=$CUSTOM_RANGES_FILE"
fi


# do the job
echo "Updating ISBN13 data"

# download ranges
if [ -n "$RANGES_FILE" ]; then
    echo "Downloading ranges info from `$SETCOLOR_FILE`$RANGES_URL`$SETCOLOR_NORMAL`"
    wget "$RANGES_URL" -O "$RANGES_FILE"
fi

# creating data for redump
echo "Creating redump data"
php bin/php/updateisbn13.php $OPTIONS

# redumping
echo "Redumping..."
php bin/php/ezsqldumpisbndata.php $DUMP_OPTIONS

# clean up
if [ -n "$RANGES_FILE" ]; then
    echo "Removing tmp files: `$SETCOLOR_FILE`$RANGES_FILE`$SETCOLOR_NORMAL`"
    echo "$RANGES_FILE" | xargs rm -f
fi

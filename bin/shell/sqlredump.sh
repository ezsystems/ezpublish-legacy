#!/bin/bash

USER="-uroot"

function help
{
	    echo "Usage: $0 [options] DBNAME SQLFILE"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
            echo
            echo "Example:"
            echo "$0 nextgen data.sql"
}

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    help
	    exit 1
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
	*)
	    if [ -z $DBNAME ]; then
		DBNAME=$arg
	    elif [ -z $SQLFILE ]; then
		SQLFILE=$arg
	    fi
	    ;;
    esac;
done

if [ -z $DBNAME ]; then
    echo "Missing database name"
    help
    exit 1;
fi
if [ -z $SQLFILE ]; then
    echo "Missing sql file"
    help
    exit 1;
fi
if [ ! -f "$SQLFILE" ]; then
    echo "SQL $SQLFILE file does not exist"
    help
    exit 1;
fi

mysqladmin "$USER" -f drop "$DBNAME"
mysqladmin "$USER" create "$DBNAME"
mysql "$USER" "$DBNAME" < "$SQLFILE"
mysqldump -c "$DBNAME" > "$SQLFILE".0
if [ $? -eq 0 ]; then
#     mv "$SQLFILE" "$SQLFILE"~
#     mv "$SQLFILE".0 "$SQLFILE"
    echo "Redumped $SQLFILE using $DBNAME database"
else
    rm "$SQLFILE".0
    echo "Failed dumping database $DBNAME to $SQLFILE"
fi


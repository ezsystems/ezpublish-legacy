#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

DEVELOPMENT="false"
[ -n $VERSION_STATE ] && DEVELOPMENT="true"

SUBPATH=""
[ "$DEVELOPMENT" == "true" ] && SUBPATH="unstable/"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options] DATABASE"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
            echo
	    exit 1
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
	*)
	    if [ -z "$DATABASE_NAME" ]; then
		DATABASE_NAME=$arg
	    fi
    esac;
done

if [ -z "$DATABASE_NAME" ]; then
    echo "No database name specified"
    exit 1
fi

DEST="/tmp/ez-$USER"
SCHEMA_URL="http://zev.ez.no/svn/nextgen/versions/$VERSION"
PREVIOUS_SCHEMA_URL="http://zev.ez.no/svn/nextgen/versions/$VERSION_PREVIOUS"

[ -d "$DEST" ] || mkdir "$DEST"

## MySQL

mysqladmin -uroot -f drop "$DATABASE_NAME" &>/dev/null
echo -n "MySQL:"
echo -n " `$POSITION_STORE`Creating"
mysqladmin -uroot create "$DATABASE_NAME" &>/dev/null
if [ $? -ne 0 ]; then
    echo
    echo "Failed to create MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL`"
    exit 1
fi
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Creating`$SETCOLOR_NORMAL`"

echo -n " `$POSITION_STORE`Initializing"
for sql_file in $KERNEL_MYSQL_SCHEMA_FILES; do
    mysql -uroot "$DATABASE_NAME" < "$sql_file"
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to initialize MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with $sql_file"
	exit 1
    fi
done
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Initializing`$SETCOLOR_NORMAL`"

echo

## PostgreSQL

dropdb "$DATABASE_NAME" &>/dev/null
echo -n "PostgreSQL:"
echo -n " `$POSITION_STORE`Creating"
createdb "$DATABASE_NAME" &>/dev/null
if [ $? -ne 0 ]; then
    echo
    echo "Failed to create PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL`"
    exit 1
fi
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Creating`$SETCOLOR_NORMAL`"

echo -n " `$POSITION_STORE`Initializing"
for sql_file in $KERNEL_POSTGRESQL_SCHEMA_FILES; do
    psql "$DATABASE_NAME" < "$sql_file" &>.psql.log
    if cat .psql.log | grep 'ERROR:' &>/dev/null; then
	echo
	echo "Failed to initialize PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with $sql_file"
	echo `$SETCOLOR_FAILURE`
	cat .psql.log
	echo `$SETCOLOR_NORMAL`
	rm .psql.log
	exit 1
    fi
    rm .psql.log
done
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Initializing`$SETCOLOR_NORMAL`"

echo

## Validation

echo -n "`$POSITION_STORE`Validating"
./bin/php/ezsqldiff.php --source-type=mysql --match-type=postgresql "$DATABASE_NAME" "$DATABASE_NAME" &>/dev/null
if [ $? -ne 0 ]; then
    echo
    echo "Database schemas did not match, this could mean that one of the database is missing an SQL update"
    echo "Check the database difference with"
    echo "./bin/php/ezsqldiff.php --source-type=mysql --match-type=postgresql \"$DATABASE_NAME\" \"$DATABASE_NAME\""
    exit 1
fi
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Validating`$SETCOLOR_NORMAL`"

echo -n "  `$SETCOLOR_SUCCESS`[  OK  ]`$SETCOLOR_NORMAL`"
echo

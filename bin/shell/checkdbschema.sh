#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

DEVELOPMENT="false"
[ -n $VERSION_STATE ] && DEVELOPMENT="true"

SUBPATH=""
[ "$DEVELOPMENT" == "true" ] && SUBPATH="unstable/"

MYSQL_USER=root
MYSQL_PASS=''
MYSQL_HOST=''
MYSQL_PORT=''

PGSQL_USER=''
PGSQL_PASS=''
PGSQL_HOST=''
PGSQL_PORT=''

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options] DATABASE"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
            echo "         --mysql-user               MySQL user"
            echo "         --mysql-password           MySQL password"
            echo "         --mysql-host               MySQL host"
            echo "         --mysql-port               MySQL port"
            echo "         --pgsql-user               PostgreSQL user"
            echo "         --pgsql-host               PostgreSQL host"
            echo "         --pgsql-port               PostgreSQL port"
            echo
	    exit 1
	    ;;
        --mysql-user=*)
            if echo $arg | grep -e "--mysql-user=" >/dev/null; then
                MYSQL_USER=`echo $arg | sed 's/--mysql-user=//'`
            fi
            ;;
        --mysql-password=*)
            if echo $arg | grep -e "--mysql-password=" >/dev/null; then
                MYSQL_PASS=`echo $arg | sed 's/--mysql-user=//'`
            fi
            ;;
        --mysql-host=*)
            if echo $arg | grep -e "--mysql-host=" >/dev/null; then
                MYSQL_HOST=`echo $arg | sed 's/--mysql-host=//'`
            fi
            ;;
        --mysql-port=*)
            if echo $arg | grep -e "--mysql-port=" >/dev/null; then
                MYSQL_PORT=`echo $arg | sed 's/--mysql-port=//'`
            fi
            ;;

        --pgsql-user=*)
            if echo $arg | grep -e "--pgsql-user=" >/dev/null; then
                PGSQL_USER=`echo $arg | sed 's/--pgsql-user=//'`
            fi
            ;;
        --pgsql-host=*)
            if echo $arg | grep -e "--pgsql-host=" >/dev/null; then
                PGSQL_HOST=`echo $arg | sed 's/--pgsql-host=//'`
            fi
            ;;
        --pgsql-port=*)
            if echo $arg | grep -e "--pgsql-port=" >/dev/null; then
                PGSQL _PORT=`echo $arg | sed 's/--pgsql-port=//'`
            fi
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

[ -n "$MYSQL_USER" ] && MYSQL_USER_OPT="-u $MYSQL_USER"
[ -n "$MYSQL_PASS" ] && MYSQL_PASS_OPT="-p$MYSQL_PASS"
[ -n "$MYSQL_HOST" ] && MYSQL_HOST_OPT="-h $MYSQL_HOST"
[ -n "$MYSQL_PORT" ] && MYSQL_PORT_OPT="-P $MYSQL_PORT"
MYSQL_CONNECT_OPT="$MYSQL_USER_OPT $MYSQL_PASS_OPT $MYSQL_HOST_OPT $MYSQL_PORT_OPT"

mysqladmin $MYSQL_CONNECT_OPT -f drop "$DATABASE_NAME" &>/dev/null
echo -n "MySQL:"
echo -n " `$POSITION_STORE`Creating"
mysqladmin $MYSQL_CONNECT_OPT create "$DATABASE_NAME" &>/dev/null
if [ $? -ne 0 ]; then
    echo
    echo "Failed to create MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL`"
    exit 1
fi
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Creating`$SETCOLOR_NORMAL`"

echo -n " `$POSITION_STORE`Initializing"
for sql_file in $KERNEL_MYSQL_SCHEMA_FILES; do
    mysql $MYSQL_CONNECT_OPT "$DATABASE_NAME" < "$sql_file"
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to initialize MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with $sql_file"
	exit 1
    fi
done
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Initializing`$SETCOLOR_NORMAL`"

echo

## PostgreSQL

[ -n "$PGSQL_USER" ] && PGSQL_USER_OPT="-U $PGSQL_USER"
[ -n "$PGSQL_HOST" ] && PGSQL_HOST_OPT="-h $PGSQL_HOST"
[ -n "$PGSQL_PORT" ] && PGSQL_PORT_OPT="-p $PGSQL_PORT"
PGSQL_CONNECT_OPT="$PGSQL_USER_OPT $PGSQL_HOST_OPT $PGSQL_PORT_OPT"

dropdb $PGSQL_CONNECT_OPT "$DATABASE_NAME" &>/dev/null
echo -n "PostgreSQL:"
echo -n " `$POSITION_STORE`Creating"
createdb $PGSQL_CONNECT_OPT "$DATABASE_NAME" &>/dev/null
if [ $? -ne 0 ]; then
    echo
    echo "Failed to create PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL`"
    exit 1
fi
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Creating`$SETCOLOR_NORMAL`"

echo -n " `$POSITION_STORE`Initializing"
for sql_file in $KERNEL_POSTGRESQL_SCHEMA_FILES; do
    psql $PGSQL_CONNECT_OPT "$DATABASE_NAME" < "$sql_file" &>.psql.log
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

# construct database connection options for ezsqldiff.php
EZSQLDIFF_DB_PARAMETERS=''
if [ -n "$MYSQL_HOST" ]; then
    EZSQLDIFF_DB_PARAMETERS="$EZSQLDIFF_DB_PARAMETERS --source-host=$MYSQL_HOST"
    [ -n "$MYSQL_PORT" ] && EZSQLDIFF_DB_PARAMETERS="$EZSQLDIFF_DB_PARAMETERS:$MYSQL_PORT"
fi
[ -n "$MYSQL_USER" ] && EZSQLDIFF_DB_PARAMETERS="$EZSQLDIFF_DB_PARAMETERS --source-user=$MYSQL_USER"
[ -n "$MYSQL_PASS" ] && EZSQLDIFF_DB_PARAMETERS="$EZSQLDIFF_DB_PARAMETERS --source-password=$MYSQL_PASS"
if [ -n "$PGSQL_HOST" ]; then
    EZSQLDIFF_DB_PARAMETERS="$EZSQLDIFF_DB_PARAMETERS --match-host=$PGSQL_HOST"
    [ -n "$PGSQL_PORT" ] && EZSQLDIFF_DB_PARAMETERS="$EZSQLDIFF_DB_PARAMETERS:$PGSQL_PORT"
fi
[ -n "$PGSQL_USER" ] && EZSQLDIFF_DB_PARAMETERS="$EZSQLDIFF_DB_PARAMETERS --match-user=$PGSQL_USER"

./bin/php/ezsqldiff.php $EZSQLDIFF_DB_PARAMETERS --source-type=mysql --match-type=postgresql "$DATABASE_NAME" "$DATABASE_NAME" &>/dev/null
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

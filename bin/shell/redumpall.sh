#!/bin/bash

. ./bin/shell/sqlcommon.sh

if ! which php &>/dev/null; then
    echo "No PHP executable found, please add it to the path"
    exit 1
fi

function help
{
    echo "Usage: $0 [options] DBNAME"
    echo "Imports and dumps all databases using database DBNAME"
    echo
    echo "Options: -h"
    echo "         --help                     This message"
    echo "         --mysql                    Redump MySQL schema files"
    echo "         --postgresql               Redump PostgreSQL schema files"
    echo "         --data                     Redump data files"
    echo "         --clean                    Cleanup various data entries before dumping (e.g. session, drafts)"
    echo "         --clean-search             Cleanup search index (implies --clean)"
    echo "         --postgresql-user=USER     Use USER as login on postgresql database"
    echo "         --socket=SOCK              Use socket SOCK to connect to database"
    echo
    echo "Example:"
    echo "$0 tmp"
}

USE_MYSQL=""
USE_POSTGRESQL=""
DUMP_DATA=""
PAUSE=""

POST_USER="root"
SOCKET=""

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    help
	    exit 1
	    ;;
	--mysql)
	    USE_MYSQL="yes"
	    ;;
	--postgresql)
	    USE_POSTGRESQL="yes"
	    ;;
	--data)
	    DUMP_DATA="yes"
	    ;;
	--socket=*)
	    if echo $arg | grep -e "--socket=" >/dev/null; then
		SOCKET=`echo $arg | sed 's/--socket=//'`
	    fi
	    ;;
	--postgresql-user=*)
	    if echo $arg | grep -e "--postgresql-user=" >/dev/null; then
		POST_USER=`echo $arg | sed 's/--postgresql-user=//'`
	    fi
	    ;;
	--pause)
	    USE_PAUSE="yes"
            PAUSE="--pause"
	    ;;
	--clean)
	    CLEAN="--clean"
	    ;;
	--clean-search)
	    CLEAN="--clean"
	    CLEAN_SEARCH="--clean-search"
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
	*)
	    if [ -z $DBNAME ]; then
		DBNAME=$arg
	    fi
	    ;;
    esac;
done

if [ "$SOCKET"x != "x" ]; then
    SOCKETARG="--socket=$SOCKET"
fi

if [ -z $DBNAME ]; then
    echo "Missing database name"
    echo
    help
    exit 1;
fi

function helpUpdateData
{
    echo "You must create the sql update files before you can use this command"
    echo "Copy the data sqls (insert, update etc.) to data.sql"
    echo
    echo "The data is normally taken from the database update files for the current release"
}

function helpUpdateMySQL
{
    echo "You must create the mysql sql update files before you can use this command"
    echo "Copy the definition sqls (create, alter etc.) to mysql_schema.sql"
    echo "and data sqls (insert, update etc.) to mysql_data.sql"
    echo
    echo "The definitions and data is normally taken from the database update files for the current release"
}

function helpUpdatePostgreSQL
{
    echo "You must create the postgresql sql update files before you can use this command"
    echo "Copy the definition sqls (create, alter etc.) to postgresql_schema.sql"
    echo "and data sqls (insert, update etc.) to postgresql_data.sql"
    echo
    echo "The definitions and data is normally taken from the database update files for the current release"
}

if [ "$USE_MYSQL" == "" -a "$USE_POSTGRESQL" == "" -a "$DUMP_DATA" == "" ]; then
    echo "No database type selected"
    help
    exit 1
fi


if [ "$USE_MYSQL" != "" ]; then

    if [ ! -f $MYSQL_SCHEMA_UPDATES ]; then
	echo "Missing $MYSQL_SCHEMA_UPDATES"
	helpUpdateMySQL
	exit 1
    fi

    ./bin/shell/sqlredump.sh --mysql $PAUSE $SOCKETARG --sql-schema-only $DBNAME $KERNEL_MYSQL_SCHEMA_FILE $MYSQL_SCHEMA_UPDATES
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file $KERNEL_MYSQL_SCHEMA_FILE"
	exit 1
    fi
    ./bin/php/ezsqldumpschema.php --type=ezmysql --user=root $DBNAME share/db_mysql_schema.dat
fi
if [ "$USE_POSTGRESQL" != "" ]; then

    if [ ! -e $POSTGRESQL_SCHEMA_UPDATES ]; then
	echo "Missing $POSTGRESQL_SCHEMA_UPDATES"
	helpUpdatePostgreSQL
	exit 1
    fi

    ./bin/shell/sqlredump.sh --postgresql $PAUSE $SOCKETARG --postgresql-user=$POST_USER --sql-schema-only --setval-file=$KERNEL_POSTGRESQL_SETVAL_FILE $DBNAME $KERNEL_POSTGRESQL_SCHEMA_FILE $POSTGRESQL_SCHEMA_UPDATES
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file $KERNEL_POSTGRESQL_SCHEMA_FILE"
	exit 1
    fi
    ./bin/php/ezsqldumpschema.php --type=ezpostgresql --user=root $DBNAME share/db_postgresql_schema.dat
fi

if [ "$DUMP_DATA" != "" ]; then
    if [ ! -f $DATA_UPDATES ]; then
	echo "Missing $DATA_UPDATES"
	helpUpdateData
	exit 1
    fi

    ./bin/shell/sqlredump.sh --mysql $CLEAN $CLEAN_SEARCH $PAUSE $SOCKETARG --sql-data-only $DBNAME --schema-sql=$KERNEL_MYSQL_SCHEMA_FILE $KERNEL_SQL_DATA_FILE $DATA_UPDATES
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file $KERNEL_SQL_DATA_FILE"
	exit 1
    fi

#    for sql in $PACKAGE_DATA_FILES; do
#	./bin/shell/sqlredump.sh --mysql $CLEAN $CLEAN_SEARCH $PAUSE --sql-data-only $DBNAME --schema-sql=$KERNEL_MYSQL_SCHEMA_FILE $sql $DATA_UPDATES
#	if [ $? -ne 0 ]; then
#	    "Failed re-dumping SQL file $sql"
#	    exit 1
#	fi
#    done

fi

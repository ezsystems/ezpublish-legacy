#!/bin/bash

. ./bin/shell/sqlcommon.sh
. ./bin/shell/common.sh

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
    echo "         --db-user                  Which database user to connect with"
    echo "         --db-host                  Which database host to connect to"
    echo "         --mysql                    Redump MySQL schema files"
    echo "         --postgresql               Redump PostgreSQL schema files"
    echo "         --data                     Redump data files"
    echo "         --clean                    Cleanup various data entries before dumping (e.g. session, drafts)"
    echo "         --clean-search             Cleanup search index (implies --clean)"
    echo
    echo "Example:"
    echo "$0 tmp"
}

USE_MYSQL=""
USE_POSTGRESQL=""
DUMP_DATA=""
PAUSE=""

POST_USER="postgres"
DB_USER=""

# DataBaseArray file
GENERIC_SCHEMA="share/db_schema.dba"

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
	--postgresql-user=*)
	    if echo $arg | grep -e "--postgresql-user=" >/dev/null; then
		POST_USER=`echo $arg | sed 's/--postgresql-user=//'`
	    fi
	    ;;
	--db-user=*)
	    if echo $arg | grep -e "--db-user=" >/dev/null; then
		DB_USER=`echo $arg | sed 's/--db-user=//'`
	    fi
	    ;;
	--db-host=*)
	    if echo $arg | grep -e "--db-host=" >/dev/null; then
		DB_HOST=`echo $arg | sed 's/--db-host=//'`
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

DUMP_TYPE=""

if [ "$USE_MYSQL" != "" ]; then
    [ -z "$DB_USER" ] && DB_USER="root"
	

    if [ ! -f $MYSQL_SCHEMA_UPDATES ]; then
	echo "Missing `ez_color_file $MYSQL_SCHEMA_UPDATES`"
	helpUpdateMySQL
	exit 1
    fi

    [ -n "$DB_USER" ] && DB_USER_OPT="--db-user=$DB_USER"
    ./bin/shell/sqlredump.sh --mysql $DB_USER_OPT $PAUSE --sql-schema-only $DBNAME $KERNEL_MYSQL_SCHEMA_FILE $MYSQL_SCHEMA_UPDATES
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file `ez_color_file $KERNEL_MYSQL_SCHEMA_FILE`"
	exit 1
    fi
    if [ -z "$DUMP_TYPE" ]; then
	DUMP_TYPE="ezmysql"
    fi
fi
if [ "$USE_POSTGRESQL" != "" ]; then
    [ -z "$DB_USER" ] && DB_USER="$POST_USER"

    if [ ! -e $POSTGRESQL_SCHEMA_UPDATES ]; then
	echo "Missing `ez_color_file $POSTGRESQL_SCHEMA_UPDATES`"
	helpUpdatePostgreSQL
	exit 1
    fi

    [ -n "$DB_USER" ] && DB_USER_OPT="--db-user=$DB_USER"
    ./bin/shell/sqlredump.sh --postgresql $PAUSE $DB_USER_OPT --sql-schema-only --setval-file=$KERNEL_POSTGRESQL_SETVAL_FILE $DBNAME $KERNEL_POSTGRESQL_SCHEMA_FILE $POSTGRESQL_SCHEMA_UPDATES
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file `ez_color_file $KERNEL_POSTGRESQL_SCHEMA_FILE`"
	exit 1
    fi
fi

if [ "$DUMP_DATA" != "" ]; then
    if [ ! -f $DATA_UPDATES ]; then
	echo "Missing `ez_color_file $DATA_UPDATES`"
	helpUpdateData
	exit 1
    fi

    [ -n "$DB_USER" ] && DB_USER_OPT="--db-user=$DB_USER"
    ./bin/shell/sqlredump.sh --mysql $CLEAN $CLEAN_SEARCH $PAUSE $DB_USER_OPT --sql-data-only $DBNAME --schema-sql=$KERNEL_MYSQL_SCHEMA_FILE $KERNEL_SQL_DATA_FILE $DATA_UPDATES
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file `ez_color_file $KERNEL_SQL_DATA_FILE`"
	exit 1
    fi
fi

if [ -n "$DUMP_TYPE" ]; then
    [ -n "$DB_USER" ] && DB_USER_OPT="--user=$DB_USER"
    echo -n "Dumping generic schema to `ez_color_file $GENERIC_SCHEMA`"
    ./bin/php/ezsqldumpschema.php --type="$DUMP_TYPE" $DB_USER_OPT --output-array $DBNAME $GENERIC_SCHEMA 2>.dump.log
    ez_result_file $? .dump.log || exit 1
fi

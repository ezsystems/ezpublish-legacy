#!/bin/bash

. ./bin/shell/sqlcommon.sh

if ! which php &>/dev/null; then
    echo "No PHP executable found, please add it to the path"
    exit 1
fi

function help
{
    echo "Usage: $0 [options] DBNAME"
    echo
    echo "Options: -h"
    echo "         --help                     This message"
    echo "         --mysql                    Redump MySQL files"
    echo "         --postgresql               Redump PostgreSQL files"
    echo
    echo "Example:"
    echo "$0 tmp"
}

USE_MYSQL=""
USE_POSTGRESQL=""

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
    help
    exit 1;
fi

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

if [ "$USE_MYSQL" != "" ]; then

    if [ ! -f $MYSQL_SCHEMA_UPDATES ]; then
	echo "Missing $MYSQL_SCHEMA_UPDATES"
	helpUpdateMySQL
	exit 1
    fi

    if [ ! -f $MYSQL_DATA_UPDATES ]; then
	echo "Missing $MYSQL_DATA_UPDATES"
	helpUpdateMySQL
	exit 1
    fi

    ./bin/shell/sqlredump.sh --mysql --sql-schema-only $DBNAME $KERNEL_MYSQL_SCHEMA_FILE $MYSQL_SCHEMA_UPDATES
    if [ $? -ne 0 ]; then
	"Failed re-dumping SQL file $KERNEL_MYSQL_SCHEMA_FILE"
	exit 1
    fi

    ./bin/shell/sqlredump.sh --mysql --sql-data-only $DBNAME --schema-sql=$KERNEL_MYSQL_SCHEMA_FILE $KERNEL_MYSQL_DATA_FILE $MYSQL_DATA_UPDATES
    if [ $? -ne 0 ]; then
	"Failed re-dumping SQL file $KERNEL_MYSQL_DATA_FILE"
	exit 1
    fi

    for sql in $PACKAGE_MYSQL_FILES; do
	./bin/shell/sqlredump.sh --mysql --sql-full $DBNAME $sql $MYSQL_SCHEMA_UPDATES $MYSQL_DATA_UPDATES
	if [ $? -ne 0 ]; then
	    "Failed re-dumping SQL file $sql"
	    exit 1
	fi
    done

    for package in $PACKAGES; do
	mysql_source="packages/"$package$PACKAGE_MYSQL_SUFFIX".sql"
	mysql_destination="packages/"$package"/sql/mysql/"$package$PACKAGE_MYSQL_SUFFIX".sql"
	if [ ! -f "$mysql_source" ]; then
	    echo "SQL file $mysql_source does not exist, cannot continue"
	    exit 1
	fi
	if [ ! -f "$mysql_destination" ]; then
	    echo "SQL file $mysql_destination does not exist, cannot continue"
	    exit 1
	fi
	cp -f $mysql_source $mysql_destination
    done

    cp -f kernel/sql/mysql/kernel_schema.sql packages/plain/sql/mysql/kernel_schema.sql
    cp -f kernel/sql/mysql/cleandata.sql packages/plain/sql/mysql/cleandata.sql
elif [ "$USE_POSTGRESQL" != "" ]; then

    if [ ! -e $POSTGRESQL_SCHEMA_UPDATES ]; then
	echo "Missing $POSTGRESQL_SCHEMA_UPDATES"
	helpUpdatePostgreSQL
	exit 1
    fi

    if [ ! -e $POSTGRESQL_DATA_UPDATES ]; then
	echo "Missing $POSTGRESQL_DATA_UPDATES"
	helpUpdatePostgreSQL
	exit 1
    fi

    ./bin/shell/sqlredump.sh --postgresql --sql-schema-only $DBNAME $KERNEL_POSTGRESQL_SCHEMA_FILE $POSTGRESQL_SCHEMA_UPDATES
    if [ $? -ne 0 ]; then
	"Failed re-dumping SQL file $KERNEL_POSTGRESQL_SCHEMA_FILE"
	exit 1
    fi

    ./bin/shell/sqlredump.sh --postgresql --sql-data-only $DBNAME --schema-sql=$KERNEL_POSTGRESQL_SCHEMA_FILE $KERNEL_POSTGRESQL_DATA_FILE $POSTGRESQL_DATA_UPDATES
    if [ $? -ne 0 ]; then
	"Failed re-dumping SQL file $KERNEL_POSTGRESQL_DATA_FILE"
	exit 1
    fi

    for sql in $PACKAGE_POSTGRESQL_FILES; do
	./bin/shell/sqlredump.sh --postgresql --sql-full $DBNAME $sql $POSTGRESQL_SCHEMA_UPDATES $POSTGRESQL_DATA_UPDATES
	if [ $? -ne 0 ]; then
	    "Failed re-dumping SQL file $sql"
	    exit 1
	fi
    done

    for package in $PACKAGES; do
	postgresql_source="packages/"$package$PACKAGE_POSTGRESQL_SUFFIX".sql"
	postgresql_destination="packages/"$package"/sql/postgresql/"$package$PACKAGE_POSTGRESQL_SUFFIX".sql"
	if [ ! -f "$postgresql_source" ]; then
	    echo "SQL file $postgresql_source does not exist, cannot continue"
	    exit 1
	fi
	if [ ! -f "$postgresql_destination" ]; then
	    echo "SQL file $postgresql_destination does not exist, cannot continue"
	    exit 1
	fi
	cp -f $postgresql_source $postgresql_destination
    done

    cp -f kernel/sql/postgresql/kernel_schema.sql packages/plain/sql/postgresql/kernel_schema.sql
    cp -f kernel/sql/postgresql/cleandata.sql packages/plain/sql/postgresql/cleandata.sql
else
    echo "No database type selected"
    help
    exit 1
fi
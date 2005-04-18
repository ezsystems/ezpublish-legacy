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
    echo "         --mysql                    Redump MySQL schema files"
    echo "         --postgresql               Redump PostgreSQL schema files"
    echo "         --data                     Redump data files"
    echo "         --clean                    Cleanup various data entries before dumping (e.g. session, drafts)"
    echo "         --clean-search             Cleanup search index (implies --clean)"
    echo

    # Show options for database
    ezdist_mysql_show_options
    ezdist_postgresql_show_options

    echo
    echo "Example:"
    echo "$0 --mysql tmp"
}

USE_MYSQL=""
USE_POSTGRESQL=""
DUMP_DATA=""
PAUSE=""

# Initialise several database related variables, see sqlcommon.sh
ezdist_db_init

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

	--*)
	    # Check for DB options
	    ezdist_mysql_check_options "$arg" && continue
	    ezdist_postgresql_check_options "$arg" && continue

	    if [ $? -ne 0 ]; then
		echo "$arg: unknown long option specified"
		echo
		echo "Type '$0 --help\` for a list of options to use."
		exit 1
	    fi
	    ;;
	-*)
	    # Check for DB options
	    ezdist_mysql_check_short_options "$arg" && continue
	    ezdist_postgresql_check_short_options "$arg" && continue

	    if [ $? -ne 0 ]; then
		echo "$arg: unknown option specified"
		echo
		echo "Type '$0 --help\` for a list of options to use."
		exit 1
	    fi
	    ;;
	*)
	    if [ -z $DBNAME ]; then
		DBNAME="$arg"
	    else
		echo "$arg: unknown argument specified"
		echo
		echo "Type '$0 --help\` for a list of options to use."
		exit 1
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


if [ "$USE_MYSQL" != "" ]; then

    if [ ! -f $MYSQL_SCHEMA_UPDATES ]; then
	echo "Missing $MYSQL_SCHEMA_UPDATES"
	helpUpdateMySQL
	exit 1
    fi

    # Init MySQL
    ezdist_db_init_mysql_from_defaults
    ezdist_mysql_prepare_params

    ./bin/shell/sqlredump.sh --mysql $PAUSE $PARAM_EZ_MYSQL_ALL --sql-schema-only $DBNAME $KERNEL_MYSQL_SCHEMA_FILE $MYSQL_SCHEMA_UPDATES
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file $KERNEL_MYSQL_SCHEMA_FILE"
	exit 1
    fi
    ezdist_db_prepare_params_from_mysql "1"
    ./bin/php/ezsqldumpschema.php --type=mysql $PARAM_EZ_DB_ALL $DBNAME share/db_mysql_schema.dat
fi
if [ "$USE_POSTGRESQL" != "" ]; then

    if [ ! -e $POSTGRESQL_SCHEMA_UPDATES ]; then
	echo "Missing $POSTGRESQL_SCHEMA_UPDATES"
	helpUpdatePostgreSQL
	exit 1
    fi

    # Init PostgreSQL
    ezdist_db_init_postgresql_from_defaults
    ezdist_postgresql_prepare_params

    ./bin/shell/sqlredump.sh --postgresql $PAUSE $PARAM_EZ_POSTGRESQL_ALL --sql-schema-only --setval-file=$KERNEL_POSTGRESQL_SETVAL_FILE $DBNAME $KERNEL_POSTGRESQL_SCHEMA_FILE $POSTGRESQL_SCHEMA_UPDATES
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file $KERNEL_POSTGRESQL_SCHEMA_FILE"
	exit 1
    fi
    ezdist_db_prepare_params_from_postgresql "1"
    ./bin/php/ezsqldumpschema.php --type=postgresql $PARAM_EZ_DB_ALL $DBNAME share/db_postgresql_schema.dat
fi

if [ "$DUMP_DATA" != "" ]; then
    if [ ! -f $DATA_UPDATES ]; then
	echo "Missing $DATA_UPDATES"
	helpUpdateData
	exit 1
    fi

    # Init MySQL
    ezdist_db_init_mysql_from_defaults
    ezdist_mysql_prepare_params

    ./bin/shell/sqlredump.sh --mysql $CLEAN $CLEAN_SEARCH $PAUSE $PARAM_EZ_MYSQL_ALL --sql-data-only $DBNAME --schema-sql=$KERNEL_MYSQL_SCHEMA_FILE $KERNEL_SQL_DATA_FILE $DATA_UPDATES
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file $KERNEL_SQL_DATA_FILE"
	exit 1
    fi

fi

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
    echo "         --schema                   Redump schema and validate for MySQL and PostgreSQL"
    echo "         --data                     Redump data files"
    echo "         --clean                    Cleanup various data entries before dumping (e.g. session, drafts)"
    echo "         --clean-search             Cleanup search index (implies --clean)"
    echo
    echo "Example:"
    echo "$0 tmp"
}

DUMP_DATA=""
PAUSE=""

POST_USER="postgres"
DB_USER=""

# DataBaseArray file
GENERIC_SCHEMA="share/db_schema.dba"

# Temporary schema files
TEMP_MYSQL_SCHEMA_FILE="mysql_schema.dba"
TEMP_POSTGRESQL_SCHEMA_FILE="postgresql_schema.dba"
TEMP_DATA_FILE="data_schema.dba"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    help
	    exit 1
	    ;;
	--schema)
	    DUMP_SCHEMA="1"
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

if [ "DUMP_SCHEMA" == "" -a "$DUMP_DATA" == "" ]; then
    echo "You must choose either to dump schema with --schema or data with --data"
    echo
    help
    exit 1
fi

DUMP_TYPE=""

if [ -n "$DUMP_SCHEMA" ]; then

    #
    # Check for schema files
    #

    if [ ! -f $MYSQL_SCHEMA_UPDATES ]; then
	echo "Missing `ez_color_file $MYSQL_SCHEMA_UPDATES`"
	helpUpdateMySQL
	exit 1
    fi

    if [ ! -e $POSTGRESQL_SCHEMA_UPDATES ]; then
	echo "Missing `ez_color_file $POSTGRESQL_SCHEMA_UPDATES`"
	helpUpdatePostgreSQL
	exit 1
    fi

    #
    # Handle MySQL schema
    #

    [ -z "$DB_USER" ] && DB_USER="root"

    [ -n "$DB_USER" ] && DB_USER_OPT="--db-user=$DB_USER"
    [ -n "$DB_HOST" ] && DB_HOST_OPT="--db-host=$DB_HOST"
    echo "Handling MySQL schema"
    ./bin/shell/sqlredump.sh --mysql $DB_USER_OPT $DB_HOST_OPT $PAUSE --sql-schema-file="$TEMP_MYSQL_SCHEMA_FILE" --sql-schema-only "$DBNAME" "$KERNEL_GENERIC_SCHEMA_FILE" "$MYSQL_SCHEMA_UPDATES"
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping schema file `ez_color_file $KERNEL_GENERIC_SCHEMA_FILE`"
	exit 1
    fi


    #
    # Handle PostgreSQL schema
    #

    [ -z "$DB_USER" ] && DB_USER="$POST_USER"

    [ -n "$DB_USER" ] && DB_USER_OPT="--db-user=$DB_USER"
    [ -n "$DB_HOST" ] && DB_HOST_OPT="--db-host=$DB_HOST"
    echo "Handling PostgreSQL schema"
    ./bin/shell/sqlredump.sh --postgresql $DB_USER_OPT $DB_HOST_OPT $PAUSE --sql-schema-file="$TEMP_POSTGRESQL_SCHEMA_FILE" --sql-schema-only --setval-file=$KERNEL_POSTGRESQL_SETVAL_FILE "$DBNAME" "$KERNEL_GENERIC_SCHEMA_FILE" "$POSTGRESQL_SCHEMA_UPDATES"
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file `ez_color_file $KERNEL_POSTGRESQL_SCHEMA_FILE`"
	exit 1
    fi

    #
    # Validate files with each other
    #

    echo -n "Validating schema files"
    diff -U3 "$TEMP_MYSQL_SCHEMA_FILE" "$TEMP_POSTGRESQL_SCHEMA_FILE" &>.dump.log
    ez_result_file $? .dump.log
    if [ $? -ne 0 ]; then
	rm -f "$TEMP_MYSQL_SCHEMA_FILE"
	rm -f "$TEMP_POSTGRESQL_SCHEMA_FILE"
	exit 1
    fi

    #
    # Validate schemas with each other
    #

    echo -n "Validating schemas"
    ./bin/php/ezsqldiff.php --source-type=mysql --match-type=postgresql "$TEMP_MYSQL_SCHEMA_FILE" "$TEMP_POSTGRESQL_SCHEMA_FILE" &>.dump.log
    ez_result_file $? .dump.log
    if [ $? -ne 0 ]; then
	rm -f "$TEMP_MYSQL_SCHEMA_FILE"
	rm -f "$TEMP_POSTGRESQL_SCHEMA_FILE"
	exit 1
    fi

    #
    # Validate schema with lint checker
    #

    echo -n "Checking schema syntax"
    ./bin/php/ezsqldiff.php --source-type=mysql --lint-check "$TEMP_MYSQL_SCHEMA_FILE" &>.dump.log
    ez_result_file $? .dump.log
    if [ $? -ne 0 ]; then
	rm -f "$TEMP_MYSQL_SCHEMA_FILE"
	rm -f "$TEMP_POSTGRESQL_SCHEMA_FILE"
	exit 1
    fi

    #
    # Copy temp schema to standard and cleanup
    #

    echo -n "Copying temp schema to standard"
    cp -f "$TEMP_MYSQL_SCHEMA_FILE" "$KERNEL_GENERIC_SCHEMA_FILE" 2>.dump.log
    ez_result_file $? .dump.log

    echo -n "Cleaning up temporary files"
    rm -f "$MYSQL_SCHEMA_UPDATES".done 2>.dump.log
    rm -f "$POSTGRESQL_SCHEMA_UPDATES".done 2>.dump.log
    mv "$MYSQL_SCHEMA_UPDATES" "$MYSQL_SCHEMA_UPDATES".done 2>.dump.log
    mv "$POSTGRESQL_SCHEMA_UPDATES" "$POSTGRESQL_SCHEMA_UPDATES".done 2>.dump.log
    rm -f "$TEMP_MYSQL_SCHEMA_FILE" 2>.dump.log
    rm -f "$TEMP_POSTGRESQL_SCHEMA_FILE" 2>.dump.log
    ez_result_file 0 .dump.log

    #
    # Update SQL files
    #

    # MySQL
    echo -n "Updating MySQL file `ez_color_file $KERNEL_MYSQL_SCHEMA_FILE`"
    ./bin/php/ezsqldumpschema.php --type=mysql --output-sql --compatible-sql --table-type=myisam "$KERNEL_GENERIC_SCHEMA_FILE" "$KERNEL_MYSQL_SCHEMA_FILE" 2>.dump.log
    ez_result_file $? .dump.log || exit 1

    # PostgreSQL
    echo -n "Updating PostgreSQL file `ez_color_file $KERNEL_POSTGRESQL_SCHEMA_FILE`"
    ./bin/php/ezsqldumpschema.php --type=postgresql --output-sql --compatible-sql "$KERNEL_GENERIC_SCHEMA_FILE" "$KERNEL_POSTGRESQL_SCHEMA_FILE" 2>.dump.log
    ez_result_file $? .dump.log || exit 1
fi

if [ "$DUMP_DATA" != "" ]; then
    if [ ! -f "$DATA_UPDATES" ]; then
	echo "Missing `ez_color_file $DATA_UPDATES`"
	helpUpdateData
	exit 1
    fi

    #
    # Handle database data
    #

    [ -n "$DB_USER" ] && DB_USER_OPT="--db-user=$DB_USER"
    echo "Handling database data"
    ./bin/shell/sqlredump.sh --mysql $CLEAN $CLEAN_SEARCH $PAUSE $DB_USER_OPT --sql-schema-file="$TEMP_DATA_FILE" --sql-data-only "$DBNAME" --schema-sql="$KERNEL_GENERIC_SCHEMA_FILE" "$KERNEL_GENERIC_DATA_FILE" "$DATA_UPDATES"
    if [ $? -ne 0 ]; then
	echo "Failed re-dumping SQL file `ez_color_file $KERNEL_GENERIC_DATA_FILE`"
	exit 1
    fi

    #
    # Copy temp schema to standard and cleanup
    #

    echo -n "Copying temp data to standard"
    cp -f "$TEMP_DATA_FILE" "$KERNEL_GENERIC_DATA_FILE" 2>.dump.log
    ez_result_file $? .dump.log

    echo -n "Cleaning up temporary files"
    rm -f "$DATA_UPDATES".done 2>.dump.log
    mv "$DATA_UPDATES" "$DATA_UPDATES".done 2>.dump.log
    rm -f "$TEMP_DATA_FILE" 2>.dump.log
    ez_result_file 0 .dump.log

    #
    # Update SQL files
    #

    echo -n "Updating data file `ez_color_file $KERNEL_SQL_DATA_FILE`"
    ./bin/php/ezsqldumpschema.php --type=mysql --output-sql --output-types=data --compatible-sql --diff-friendly --schema-file="$KERNEL_GENERIC_SCHEMA_FILE" "$KERNEL_GENERIC_DATA_FILE" "$KERNEL_SQL_DATA_FILE" 2>.dump.log
    ez_result_file $? .dump.log || exit 1
fi

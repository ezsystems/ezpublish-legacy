#!/bin/bash

. ./bin/shell/packagescommon.sh

KERNEL_MYSQL_SCHEMA_FILE="kernel/sql/mysql/kernel_schema.sql"
KERNEL_POSTGRESQL_SCHEMA_FILE="kernel/sql/postgresql/kernel_schema.sql"
KERNEL_SQL_DATA_FILE="kernel/sql/common/cleandata.sql"
KERNEL_MYSQL_DATA_FILE="kernel/sql/mysql/cleandata.sql"
KERNEL_POSTGRESQL_DATA_FILE="kernel/sql/postgresql/cleandata.sql"
KERNEL_POSTGRESQL_SETVAL_FILE="kernel/sql/postgresql/setval.sql"

KERNEL_MYSQL_SCHEMA_FILES="$KERNEL_MYSQL_SCHEMA_FILE"
KERNEL_POSTGRESQL_SCHEMA_FILES="$KERNEL_POSTGRESQL_SCHEMA_FILE $KERNEL_POSTGRESQL_SETVAL_FILE"

PACKAGE_DATA_FILES=""
PACKAGE_MYSQL_FILES=""
PACKAGE_RELEASE_MYSQL_FILES=""

PACKAGE_MYSQL_SUFFIX="_mysql"
PACKAGE_POSTGRESQL_SUFFIX="_postgresql"
for package in $PACKAGES; do
    PACKAGE_DATA_FILES="$PACKAGE_DATA_FILES packages/sql/data/"$package".sql"
    PACKAGE_MYSQL_FILES="$PACKAGE_MYSQL_FILES packages/sql/mysql/"$package".sql"
    PACKAGE_POSTGRESQL_FILES="$PACKAGE_POSTGRESQL_FILES packages/sql/postgresql/"$package".sql"
done

MYSQL_SCHEMA_UPDATES="mysql_schema.sql"
MYSQL_DATA_UPDATES="mysql_data.sql"
POSTGRESQL_SCHEMA_UPDATES="postgresql_schema.sql"
POSTGRESQL_DATA_UPDATES="postgresql_data.sql"
DATA_UPDATES="data.sql"

DRIVERS="mysql postgresql"


# Returns 1 if it finds an error in PostgreSQL error log
# Syntax:
# pg_error_code <logfile>
function pg_error_code
{
    if cat "$1" | grep 'ERROR:' &>/dev/null; then
	return 1
    fi
    return 0
}
#!/bin/bash

. ./bin/shell/packagescommon.sh

KERNEL_MYSQL_SCHEMA_FILE="kernel/sql/mysql/kernel_schema.sql"
KERNEL_POSTGRESQL_SCHEMA_FILE="kernel/sql/postgresql/kernel_schema.sql"
KERNEL_SQL_DATA_FILE="kernel/sql/common/cleandata.sql"
KERNEL_MYSQL_DATA_FILE="kernel/sql/mysql/cleandata.sql"
KERNEL_POSTGRESQL_DATA_FILE="kernel/sql/postgresql/cleandata.sql"
KERNEL_POSTGRESQL_SETVAL_FILE="kernel/sql/postgresql/setval.sql"

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

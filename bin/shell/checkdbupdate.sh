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
	    echo "         --mysql                    Check MySQL schema"
	    echo "         --postgresql               Check PostgreSQL schema"
            echo
	    exit 1
	    ;;
	--mysql)
	    DB_TYPE="mysql"
	    ;;
	--postgresql)
	    DB_TYPE="postgresql"
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

if [ -z "$DB_TYPE" ]; then
    echo "No database type specified"
    exit 1
fi

if [[ "$DB_TYPE" != "mysql" && "$DB_TYPE" != "postgresql" ]]; then
    echo "Unknown database type $DB_TYPE"
    exit 1
fi

if [ -z "$DATABASE_NAME" ]; then
    echo "No database name specified"
    exit 1
fi

DEST="/tmp/ez-$USER"
SCHEMA_URL="http://zev.ez.no/svn/nextgen/versions/$VERSION"
PREVIOUS_SCHEMA_URL="http://zev.ez.no/svn/nextgen/versions/$VERSION_PREVIOUS"

[ -d "$DEST" ] || mkdir "$DEST"

if [ "$DB_TYPE" == "mysql" ]; then
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

    mysql_schema_url="$PREVIOUS_SCHEMA_URL/kernel/sql/mysql/"
    rm -rf "$DEST/mysql"
    echo -n " `$POSITION_STORE`Exporting"
    svn export "$mysql_schema_url" "$DEST/mysql" &>/dev/null
    if [ $? -ne 0 ]; then
	echo
	echo "Failed checking out MySQL database schema `$SETCOLOR_EMPHASIZE`$mysql_schema_url`$SETCOLOR_NORMAL` from version `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Exporting`$SETCOLOR_NORMAL`"

    echo -n " `$POSITION_STORE`Initializing"
    mysql -uroot "$DATABASE_NAME" < "$DEST/mysql/kernel_schema.sql"
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to initialize MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with kernel_schema.sql"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Initializing`$SETCOLOR_NORMAL`"

    rm -rf "$DEST/mysql"

    dbupdatefile="update/database/mysql/""$VERSION_BRANCH""/""$SUBPATH""dbupdate-""$VERSION_PREVIOUS""-to-""$VERSION"".sql"
    echo -n " `$POSITION_STORE`Updating"
    mysql -uroot "$DATABASE_NAME" < "$dbupdatefile"
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to run database update `$SETCOLOR_EMPHASIZE`$dbupdatefile`$SETCOLOR_NORMAL` for MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL`"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Updating`$SETCOLOR_NORMAL`"

    echo -n " `$POSITION_STORE`Validating"
    ./bin/php/ezsqldiff.php --type=mysql "$DATABASE_NAME" share/db_mysql_schema.dat &>/dev/null
    if [ $? -ne 0 ]; then
	echo
	echo "MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` did not validate, this probably means the update file is incorrect"
	echo "Check the database difference with"
	echo "./bin/php/ezsqldiff.php --type=mysql $DATABASE_NAME share/db_mysql_schema.dat"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Validating`$SETCOLOR_NORMAL`"

    echo -n "  `$SETCOLOR_SUCCESS`[  OK  ]`$SETCOLOR_NORMAL`"
    echo
elif [ "$DB_TYPE" == "postgresql" ]; then
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

    postgresql_schema_url="$PREVIOUS_SCHEMA_URL/kernel/sql/postgresql/"
    rm -rf "$DEST/postgresql"
    echo -n " `$POSITION_STORE`Exporting"
    svn export "$postgresql_schema_url" "$DEST/postgresql" &>/dev/null
    if [ $? -ne 0 ]; then
	echo
	echo "Failed checking out PostgreSQL database schema `$SETCOLOR_EMPHASIZE`$postgresql_schema_url`$SETCOLOR_NORMAL` from version `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Exporting`$SETCOLOR_NORMAL`"

    echo -n " `$POSITION_STORE`Initializing"
    psql "$DATABASE_NAME" < "$DEST/postgresql/kernel_schema.sql" &>/dev/null
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to initialize PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with kernel_schema.sql"
	exit 1
    fi
    psql "$DATABASE_NAME" < "$DEST/postgresql/setval.sql" &>/dev/null
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to initialize PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with setval.sql"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Initializing`$SETCOLOR_NORMAL`"

    rm -rf "$DEST/postgresql"

    dbupdatefile="update/database/postgresql/""$VERSION_BRANCH""/""$SUBPATH""dbupdate-""$VERSION_PREVIOUS""-to-""$VERSION"".sql"
    echo -n " `$POSITION_STORE`Updating"
    psql "$DATABASE_NAME" < "$dbupdatefile" &>/dev/null
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to run database update `$SETCOLOR_EMPHASIZE`$dbupdatefile`$SETCOLOR_NORMAL` for PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL`"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Updating`$SETCOLOR_NORMAL`"

    echo -n " `$POSITION_STORE`Validating"
    ./bin/php/ezsqldiff.php --type=postgresql "$DATABASE_NAME" share/db_postgresql_schema.dat &>/dev/null
    if [ $? -ne 0 ]; then
	echo
	echo "MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` did not validate, this probably means the update file is incorrect"
	echo "Check the database difference with"
	echo "./bin/php/ezsqldiff.php --type=postgresql $DATABASE_NAME share/db_postgresql_schema.dat"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Validating`$SETCOLOR_NORMAL`"

    echo -n "  `$SETCOLOR_SUCCESS`[  OK  ]`$SETCOLOR_NORMAL`"
    echo
fi

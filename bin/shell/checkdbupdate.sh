#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

#DEVELOPMENT="false"
#if [ -n $VERSION_STATE ]; then
#    DEVELOPMENT="true"
#fi

SUBPATH=""
if [ "$DEVELOPMENT" == "true" ]; then
    SUBPATH="unstable/"
fi

# Initialise several database related variables, see sqlcommon.sh
ezdist_db_init

MYSQL_USER="root"
MYSQL_PASSWD="none"
MYSQL_SOCKET="none"
MYSQL_HOST="none"

POSTGRESQL_USER="$USER"
POSTGRESQL_PASSWD="none"
POSTGRESQL_HOST="none"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options] DATABASE"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
            echo

	    # Show options for database
	    ezdist_db_show_options

            echo
	    exit 1
	    ;;

	--*)
	    # Check for DB options
	    ezdist_db_check_options "$arg"

	    if [ $? -ne 0 ]; then
		echo "$arg: unknown long option specified"
		echo
		echo "Type '$0 --help\` for a list of options to use."
		exit 1
	    fi
	    ;;
	-*)
	    # Check for DB options
	    ezdist_db_check_short_options "$arg"

	    if [ $? -ne 0 ]; then
		echo "$arg: unknown option specified"
		echo
		echo "Type '$0 --help\` for a list of options to use."
		exit 1
	    fi
	    ;;
	*)
	    if [ -z "$DATABASE_NAME" ]; then
		DATABASE_NAME=$arg
	    else
		echo "$arg: unknown argument specified"
		echo
		echo "Type '$0 --help\` for a list of options to use."
		exit 1
	    fi
	    ;;
    esac;
done

if ezdist_is_empty "$DB_TYPE"; then
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

if [ "$DB_TYPE" == "mysql" ]; then
    ezdist_db_init_mysql_from_defaults
    echo "Connecting to MySQL using `ezdist_mysql_show_config`"
    ezdist_mysql_prepare_params
elif [ "$DB_TYPE" == "postgresql" ]; then
    ezdist_db_init_postgresql_from_defaults
    echo "Connecting to PostgreSQL using `ezdist_postgresql_show_config`"
    ezdist_postgresql_prepare_params
fi


DEST="/tmp/ez-$USER"

if [ "$VERSION" == "$VERSION_ONLY"".0" ]; then
    to="$VERSION"
    from="$VERSION_STABLE"
    has_setval="false"
else
    to="$VERSION"
    from="$VERSION_PREVIOUS"
    has_setval="true"
fi

SCHEMA_URL="http://zev.ez.no/svn/nextgen/versions/$to"
PREVIOUS_SCHEMA_URL="http://zev.ez.no/svn/nextgen/versions/$from"

[ -d "$DEST" ] || mkdir "$DEST"

if [ "$DB_TYPE" == "mysql" ]; then
    mysqladmin $PARAM_MYSQL_ALL -f drop "$DATABASE_NAME" &>/dev/null
    echo -n "MySQL:"
    echo -n " `$POSITION_STORE`Creating"
    mysqladmin $PARAM_MYSQL_ALL create "$DATABASE_NAME" &>/dev/null
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
    mysql $PARAM_MYSQL_ALL "$DATABASE_NAME" < "$DEST/mysql/kernel_schema.sql"
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to initialize MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with kernel_schema.sql"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Initializing`$SETCOLOR_NORMAL`"

    rm -rf "$DEST/mysql"

    dbupdatefile="update/database/mysql/""$VERSION_BRANCH""/""$SUBPATH""dbupdate-""$from""-to-""$VERSION"".sql"
    echo -n " `$POSITION_STORE`Updating"
    mysql $PARAM_MYSQL_ALL "$DATABASE_NAME" < "$dbupdatefile"
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to run database update `$SETCOLOR_EMPHASIZE`$dbupdatefile`$SETCOLOR_NORMAL` for MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL`"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Updating`$SETCOLOR_NORMAL`"

    # Create parameters
    ezdist_mysql_prepare_source_params
    echo -n " `$POSITION_STORE`Validating"
    ./bin/php/ezsqldiff.php --type=mysql $PARAM_SOURCE_ALL "$DATABASE_NAME" share/db_mysql_schema.dat &>/dev/null
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
    dropdb $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" &>/dev/null
    echo -n "PostgreSQL:"
    echo -n " `$POSITION_STORE`Creating"
    createdb $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" &>/dev/null
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
    psql $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" < "$DEST/postgresql/kernel_schema.sql" &>/dev/null
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to initialize PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with kernel_schema.sql"
	exit 1
    fi
    if [ "$has_setval" == "true" ]; then
	psql $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" < "$DEST/postgresql/setval.sql" &>/dev/null
	if [ $? -ne 0 ]; then
	    echo
	    echo "Failed to initialize PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with setval.sql"
	    exit 1
	fi
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Initializing`$SETCOLOR_NORMAL`"

    rm -rf "$DEST/postgresql"

    dbupdatefile="update/database/postgresql/""$VERSION_BRANCH""/""$SUBPATH""dbupdate-""$from""-to-""$VERSION"".sql"
    echo -n " `$POSITION_STORE`Updating"
    psql $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" < "$dbupdatefile" &>/dev/null
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to run database update `$SETCOLOR_EMPHASIZE`$dbupdatefile`$SETCOLOR_NORMAL` for PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL`"
	exit 1
    fi
    echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Updating`$SETCOLOR_NORMAL`"

    # Create parameters
    ezdist_postgresql_prepare_source_params
    echo -n " `$POSITION_STORE`Validating"
    ./bin/php/ezsqldiff.php --type=postgresql $PARAM_SOURCE_ALL "$DATABASE_NAME" share/db_postgresql_schema.dat &>/dev/null
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

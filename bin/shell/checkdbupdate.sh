#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

CHECK_TYPE="previous"
SUBPATH=""

# Initialise several database related variables, see sqlcommon.sh
ezdist_db_init

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options] DATABASE"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --check-stable             Check against the last stable"
	    echo "         --check-previous           Check against the last release (Default)"
            echo

	    # Show options for database
	    ezdist_db_show_options

            echo
	    exit 1
	    ;;
	--check-stable)
	    CHECK_TYPE="stable"
	    ;;
	--check-previous)
	    CHECK_TYPE="previous"
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

if [ "$CHECK_TYPE" = "previous" ]; then
    if [ "$DEVELOPMENT_PREVIOUS" == "true" ]; then
        SUBPATH="unstable/"
    elif [ "$DEVELOPMENT" == "true" ]; then
        SUBPATH="unstable/"
    fi
else
    if [ "$DEVELOPMENT" == "true" ]; then
        SUBPATH="unstable/"
    fi
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

if [ "$CHECK_TYPE" = "previous" ]; then
    if [ -z "$VERSION_PREVIOUS" ]; then
        to="$VERSION"
        from="$VERSION_STABLE"
        has_setval="false"
    else
        to="$VERSION"
        from="$VERSION_PREVIOUS"
        has_setval="true"
    fi
else
    to="$VERSION"
    from="$VERSION_STABLE"
    has_setval="false"
fi

SCHEMA_URL="http://svn.ez.no/svn/ezpublish/versions/$to"
PREVIOUS_SCHEMA_URL="http://svn.ez.no/svn/ezpublish/versions/$from"

[ -d "$DEST" ] || mkdir "$DEST"

# Helper function to deal with output

function db_type_to_name
{
    local type
    type=$1
    if [ "$type" == "mysql" ]; then
	echo "MySQL"
    elif [ "$type" == "postgresql" ]; then
	echo "PostgreSQL"
    else
	echo "Unknown"
    fi
}

function db_step_start
{
    local step_name
    local resource
    step_name=$1
    resource=$2
    echo -n "`$POSITION_STORE``$SETCOLOR_EMPHASIZE`$step_name`$SETCOLOR_NORMAL`"
    [ -n "$resource" ] && echo -n "($resource)"
}

function db_step_done
{
    local step_name
    local resource
    step_name=$1
    resource=$2
    echo -n "`$POSITION_RESTORE``$SETCOLOR_COMMENT`$step_name"
    [ -n "$resource" ] && echo -n "($resource)"
    echo -n "`$SETCOLOR_NORMAL`"
}

function db_create_failure
{
    local type_name
    local db_name
    type_name=`db_type_to_name $1`
    db_name=$2
    echo "`$POSITION_RESTORE``$SETCOLOR_FAILURE`Creating($db_name)`$SETCOLOR_NORMAL`"
    echo "Failed to create $type_name database `$SETCOLOR_EMPHASIZE`$db_name`$SETCOLOR_NORMAL`"
}

function db_export_failure
{
    local type_name
    local version
    local schema_url
    version=$2
    type_name=`db_type_to_name $1`
    schema_url=$3
    echo "`$POSITION_RESTORE``$SETCOLOR_FAILURE`Exporting($version)`$SETCOLOR_NORMAL`"
    echo "Failed checking out $type_name database schema `$SETCOLOR_EMPHASIZE`$schema_url`$SETCOLOR_NORMAL` from version `$SETCOLOR_EMPHASIZE`$from`$SETCOLOR_NORMAL`"
}

function db_initialize_failure
{
    local type_name
    local db_name
    type_name=`db_type_to_name $1`
    db_name=$2
    echo "`$POSITION_RESTORE``$SETCOLOR_FAILURE`Initializing`$SETCOLOR_NORMAL`"
    echo "Failed to initialize $type_name database `$SETCOLOR_EMPHASIZE`$db_name`$SETCOLOR_NORMAL` with kernel_schema.sql"
}

function db_initialize_failure_setval
{
    local type_name
    local db_name
    type_name=`db_type_to_name $1`
    db_name=$2
    echo "`$POSITION_RESTORE``$SETCOLOR_FAILURE`Initializing`$SETCOLOR_NORMAL`"
    echo "Failed to initialize $type_name database `$SETCOLOR_EMPHASIZE`$db_name`$SETCOLOR_NORMAL` with setval.sql"
}


function db_update_failure_dbfile
{
    local type_name
    local db_name
    local from_to_str
    local dbupdatefile
    type_name=`db_type_to_name $1`
    db_name=$2
    from_to_str=$3
    dbupdatefile=$4
    echo "`$POSITION_RESTORE``$SETCOLOR_FAILURE`Updating($from_to_str)`$SETCOLOR_NORMAL`"
    echo "Failed to run database update `$SETCOLOR_EMPHASIZE`$dbupdatefile`$SETCOLOR_NORMAL` for $type_name database `$SETCOLOR_EMPHASIZE`$db_name`$SETCOLOR_NORMAL`"
    echo "Update file `$SETCOLOR_EMPHASIZE`$dbupdatefile`$SETCOLOR_NORMAL` does not exist"
}

function db_update_failure_import
{
    local type_name
    local db_name
    local from_to_str
    local dbupdatefile
    type_name=`db_type_to_name $1`
    db_name=$2
    from_to_str=$3
    dbupdatefile=$4
    echo "`$POSITION_RESTORE``$SETCOLOR_FAILURE`Updating($from_to_str)`$SETCOLOR_NORMAL`"
    echo "Failed to run database update `$SETCOLOR_EMPHASIZE`$dbupdatefile`$SETCOLOR_NORMAL` for $type_name database `$SETCOLOR_EMPHASIZE`$db_name`$SETCOLOR_NORMAL`"
}

function db_validate_failure
{
    local type_name
    local db_name
    type=$1
    type_name=`db_type_to_name $1`
    db_name=$2
    echo "`$POSITION_RESTORE``$SETCOLOR_FAILURE`Validating`$SETCOLOR_NORMAL`"
    echo "$type_name database `$SETCOLOR_EMPHASIZE`$db_name`$SETCOLOR_NORMAL` did not validate, this probably means the update file is incorrect"
    echo "Check the database difference with"
    echo "./bin/php/ezsqldiff.php --type=$type $db_name share/db_schema.dba"
}

if [ "$DB_TYPE" == "mysql" ]; then
    mysqladmin $PARAM_MYSQL_ALL -f drop "$DATABASE_NAME" &>/dev/null
    echo -n "MySQL:"
    echo -n " "
    db_step_start "Creating" "$DATABASE_NAME"
    mysqladmin $PARAM_MYSQL_ALL create "$DATABASE_NAME" &>/dev/null
    if [ $? -ne 0 ]; then
	db_create_failure $DB_TYPE "$DATABASE_NAME"
	exit 1
    fi
    db_step_done "Creating" "$DATABASE_NAME"

    mysql_schema_url="$PREVIOUS_SCHEMA_URL/kernel/sql/mysql/"
    rm -rf "$DEST/mysql"
    echo -n " "
    db_step_start "Exporting" "$from"
    svn export "$mysql_schema_url" "$DEST/mysql" &>/dev/null
    if [ $? -ne 0 ]; then
	db_export_failure $DB_TYPE "$from" "$mysql_schema_url"
	echo "`$POSITION_RESTORE`Exporting(`$SETCOLOR_FAILURE`$from`$SETCOLOR_NORMAL`)"
	echo "Failed checking out MySQL database schema `$SETCOLOR_EMPHASIZE`$mysql_schema_url`$SETCOLOR_NORMAL` from version `$SETCOLOR_EMPHASIZE`$VERSION`$SETCOLOR_NORMAL`"
	exit 1
    fi
    db_step_done "Exporting" "$from"

    echo -n " "
    db_step_start "Initializing" ""
    mysql $PARAM_MYSQL_ALL "$DATABASE_NAME" < "$DEST/mysql/kernel_schema.sql" &>/dev/null
    if [ $? -ne 0 ]; then
	db_initialize_failure $DB_TYPE "$DATABASE_NAME"
	exit 1
    fi
    db_step_done "Initializing" ""

    rm -rf "$DEST/mysql"

    dbupdatefile="update/database/mysql/""$VERSION_BRANCH""/""$SUBPATH""dbupdate-""$from""-to-""$VERSION"".sql"
    echo -n " "
    from_to_str="$from""=>""$VERSION"
    db_step_start "Updating" "$from_to_str"
    if [ ! -f "$dbupdatefile" ]; then
	db_update_failure_dbfile $DB_TYPE "$DATABASE_NAME" "$from_to_str" "$dbupdatefile"
	exit 1
    fi
    mysql $PARAM_MYSQL_ALL "$DATABASE_NAME" < "$dbupdatefile" &>/dev/null
    if [ $? -ne 0 ]; then
	db_update_failure_import $DB_TYPE "$DATABASE_NAME" "$from_to_str" "$dbupdatefile"
	exit 1
    fi
    db_step_done "Updating" "$from_to_str"

    echo -n " "
    db_step_start "Validating" ""
    # Create parameters
    ezdist_mysql_prepare_source_params
    ./bin/php/ezsqldiff.php --type=mysql $PARAM_SOURCE_ALL "$DATABASE_NAME" share/db_schema.dba &>/dev/null
    if [ $? -ne 0 ]; then
	db_validate_failure $DB_TYPE "$DATABASE_NAME"
	exit 1
    fi
    db_step_done "Validating" ""

    echo -n "  `$SETCOLOR_SUCCESS`[  OK  ]`$SETCOLOR_NORMAL`"
    echo

elif [ "$DB_TYPE" == "postgresql" ]; then
    [ -n "$PGSQL_HOST" ] && PGSQL_HOST_OPT="-h $PGSQL_HOST"
    [ -n "$PGSQL_PORT" ] && PGSQL_PORT_OPT="-p $PGSQL_PORT"
    PGSQL_CONNECT_OPT="$PGSQL_HOST_OPT $PGSQL_PORT_OPT"

    dropdb $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" &>/dev/null
    echo -n "PostgreSQL:"
    echo -n " "
    db_step_start "Creating" "$DATABASE_NAME"
    createdb $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" &>/dev/null
    if [ $? -ne 0 ]; then
	db_create_failure $DB_TYPE "$DATABASE_NAME"
	exit 1
    fi
    db_step_done "Creating" "$DATABASE_NAME"

    postgresql_schema_url="$PREVIOUS_SCHEMA_URL/kernel/sql/postgresql/"
    rm -rf "$DEST/postgresql"
    echo -n " "
    db_step_start "Exporting" "$from"
    svn export "$postgresql_schema_url" "$DEST/postgresql" &>/dev/null
    if [ $? -ne 0 ]; then
	db_export_failure $DB_TYPE "$from" "$postgresql_schema_url"
	exit 1
    fi
    db_step_done "Exporting" "$from"

    echo -n " "
    db_step_start "Initializing" ""
    psql $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" < "$DEST/postgresql/kernel_schema.sql" &>.psql.log
    if cat .psql.log | grep 'ERROR:' &>/dev/null; then
	db_initialize_failure $DB_TYPE "$DATABASE_NAME"
	echo `$SETCOLOR_FAILURE`
	cat .psql.log
	echo `$SETCOLOR_NORMAL`
	rm .psql.log
	exit 1
    fi
    rm .psql.log
    if [ "$has_setval" == "true" ]; then
	psql $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" < "$DEST/postgresql/setval.sql" &>.psql.log
	if cat .psql.log | grep 'ERROR:' &>/dev/null; then
	    db_initialize_failure_setval $DB_TYPE "$DATABASE_NAME"
	    echo `$SETCOLOR_FAILURE`
	    cat .psql.log
	    echo `$SETCOLOR_NORMAL`
	    rm .psql.log
	    exit 1
	fi
	rm .psql.log
    fi
    db_step_done "Initializing" ""

    rm -rf "$DEST/postgresql"

    dbupdatefile="update/database/postgresql/""$VERSION_BRANCH""/""$SUBPATH""dbupdate-""$from""-to-""$VERSION"".sql"
    echo -n " "
    from_to_str="$from""=>""$VERSION"
    db_step_start "Updating" "$from_to_str"
    if [ ! -f "$dbupdatefile" ]; then
	db_update_failure_dbfile $DB_TYPE "$DATABASE_NAME" "$from_to_str" "$dbupdatefile"
	exit 1
    fi
    psql $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" < "$dbupdatefile" &>.psql.log
    if cat .psql.log | grep 'ERROR:' &>/dev/null; then
	db_update_failure_import $DB_TYPE "$DATABASE_NAME" "$from_to_str" "$dbupdatefile"
	echo `$SETCOLOR_FAILURE`
	cat .psql.log
	echo `$SETCOLOR_NORMAL`
	rm .psql.log
	exit 1
    fi
    rm .psql.log
    db_step_done "Updating" "$from_to_str"

    echo -n " "
    db_step_start "Validating" ""
    # Create parameters
    ezdist_postgresql_prepare_source_params
    ./bin/php/ezsqldiff.php --type=postgresql $PARAM_SOURCE_ALL "$DATABASE_NAME" share/db_schema.dba &>/dev/null
    if [ $? -ne 0 ]; then
	db_validate_failure $DB_TYPE "$DATABASE_NAME"
	exit 1
    fi
    db_step_done "Validating" ""

    echo -n "  `$SETCOLOR_SUCCESS`[  OK  ]`$SETCOLOR_NORMAL`"
    echo
fi

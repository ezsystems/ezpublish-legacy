#!/bin/bash

. ./bin/shell/packagescommon.sh

KERNEL_MYSQL_SCHEMA_FILE="kernel/sql/mysql/kernel_schema.sql"
KERNEL_POSTGRESQL_SCHEMA_FILE="kernel/sql/postgresql/kernel_schema.sql"
KERNEL_SQL_DATA_FILE="kernel/sql/common/cleandata.sql"
KERNEL_MYSQL_DATA_FILE="kernel/sql/mysql/cleandata.sql"
KERNEL_POSTGRESQL_DATA_FILE="kernel/sql/postgresql/cleandata.sql"
KERNEL_POSTGRESQL_SETVAL_FILE="kernel/sql/postgresql/setval.sql"
KERNEL_GENERIC_SCHEMA_FILE="share/db_schema.dba"
KERNEL_GENERIC_DATA_FILE="share/db_data.dba"

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

# Sets the general, mysql and postgresql options to "undef" if they
# they are not defined yet.
# This should be run at the top of the script, optionally after cached
# values are read.
function ezdist_db_init
{
    [ -z "$DB_USER" ] && DB_USER="undef"
    [ -z "$DB_PASSWD" ] && DB_PASSWD="undef"
    [ -z "$DB_SOCKET" ] && DB_SOCKET="undef"
    [ -z "$DB_HOST" ] && DB_HOST="undef"
    [ -z "$DB_PORT" ] && DB_PORT="undef"
    [ -z "$DB_NAME" ] && DB_NAME="undef"
    [ -z "$DB_TYPE" ] && DB_TYPE="undef"

    [ -z "$MYSQL_USER" ] && MYSQL_USER="undef"
    [ -z "$MYSQL_PASSWD" ] && MYSQL_PASSWD="undef"
    [ -z "$MYSQL_SOCKET" ] && MYSQL_SOCKET="undef"
    [ -z "$MYSQL_HOST" ] && MYSQL_HOST="undef"
    [ -z "$MYSQL_PORT" ] && MYSQL_PORT="undef"
    [ -z "$MYSQL_NAME" ] && MYSQL_NAME="undef"

    [ -z "$POSTGRESQL_USER" ] && POSTGRESQL_USER="undef"
    [ -z "$POSTGRESQL_PASSWD" ] && POSTGRESQL_PASSWD="undef"
    [ -z "$POSTGRESQL_HOST" ] && POSTGRESQL_HOST="undef"
    [ -z "$POSTGRESQL_PORT" ] && POSTGRESQL_PORT="undef"
    [ -z "$POSTGRESQL_NAME" ] && POSTGRESQL_NAME="undef"
}

# Outputs which options are available
function ezdist_db_show_options
{
    ezdist_db_common_show_options
    ezdist_mysql_show_options
    ezdist_postgresql_show_options
}

# Outputs which general DB options are available
function ezdist_db_common_show_options
{
    echo "Database options:"
    echo "         --db-user=USER         DB user"
    echo "         --db-password=PASSWORD DB password ( default: <empty> )"
    echo "         --db-socket=SOCKET     DB socket ( default: <empty> )"
    echo "         --db-host=HOST         DB host ( default: <locally> )"
    echo "         --db-name=NAME         DB name"
    echo "         --db-port=PORT         DB port"
    echo "         --db-type=TYPE         DB type (mysql or postgresql)"
    echo "         --mysql                Short for --db-type=mysql"
    echo "         --postgresql           Short for --db-type=postgresql"
}

# Outputs which MySQL options are available
function ezdist_mysql_show_options
{
    echo "MySQL options:"
    echo "         --mysql-user=USER          MySQL DB user ( default: root )"
    echo "         --mysql-password=PASSWORD  MySQL DB password ( default: <empty> )"
    echo "         --mysql-socket=SOCKET      MySQL DB socket ( default: <empty> )"
    echo "         --mysql-host=HOST          MySQL DB host ( default: <locally> )"
    echo "         --mysql-host=PORT          MySQL DB port"
}

# Outputs which PostgreSQL options are available
function ezdist_postgresql_show_options
{
    echo "PostgreSQL options:"
    echo "         --postgresql-user=USER          PostgreSQL DB user ( default: postgres )"
    echo "         --postgresql-password=PASSWORD  PostgreSQL DB password ( default: <empty> )"
    echo "         --postgresql-host=HOST          PostgreSQL DB host ( default: <locally> )"
    echo "         --postgresql-port=PORT          PostgreSQL DB port"
}

# Looks for common, MySQL and PostgreSQL short options (e.g -a)
# Parameters: ARG
# Returns: 0 if one is found, 1 otherwise
function ezdist_db_check_short_options
{
    ezdist_db_common_check_short_options "$1" && return 0
    ezdist_mysql_check_short_options "$1" && return 0
    ezdist_postgresql_check_short_options "$1" && return 0
    return 1
}

# Looks for short options (e.g -a)
# Parameters: ARG
# Returns: 0 if one is found, 1 otherwise
function ezdist_db_common_check_short_options
{
    return 1
}

# Looks for short options for MySQL (e.g -a)
# Parameters: ARG
# Returns: 0 if one is found, 1 otherwise
function ezdist_mysql_check_short_options
{
    return 1
}

# Looks for short options for PostgreSQL (e.g -a)
# Parameters: ARG
# Returns: 0 if one is found, 1 otherwise
function ezdist_postgresql_check_short_options
{
    return 1
}

# Looks for long options (e.g --all)
# Parameters: ARG
# Returns: 0 if one is found, 1 otherwise
function ezdist_db_check_options
{
    ezdist_db_common_check_options "$1" && return 0
    ezdist_mysql_check_options "$1" && return 0
    ezdist_postgresql_check_options "$1" && return 0
    return 1
}

# Looks for long options (e.g --all)
# Parameters: ARG
# Returns: 0 if one is found, 1 otherwise
function ezdist_db_common_check_options
{
    local arg
    arg="$1"
    case $arg in
	--db-user*)
	    if echo $arg | grep -e "--db-user=" >/dev/null; then
		DB_USER=`echo $arg | sed 's/--db-user=//'`
		DB_USER_INPUT="1"
	    fi
	    return 0
	    ;;
	--db-password*)
	    if echo $arg | grep -e "--db-password=" >/dev/null; then
		DB_PASSWD=`echo $arg | sed 's/--db-password=//'`
		DB_PASSWD_INPUT="1"
	    fi
	    return 0
	    ;;
	--db-socket*)
	    if echo $arg | grep -e "--db-socket=" >/dev/null; then
		DB_SOCKET=`echo $arg | sed 's/--db-socket=//'`
		DB_SOCKET_INPUT="1"
	    fi
	    return 0
	    ;;
	--db-host*)
	    if echo $arg | grep -e "--db-host=" >/dev/null; then
		DB_HOST=`echo $arg | sed 's/--db-host=//'`
		DB_HOST_INPUT="1"
	    fi
	    return 0
	    ;;
	--db-name*)
	    if echo $arg | grep -e "--db-name=" >/dev/null; then
		DB_NAME=`echo $arg | sed 's/--db-name=//'`
		DB_NAME_INPUT="1"
	    fi
	    return 0
	    ;;
	--db-type*)
	    if echo $arg | grep -e "--db-type=" >/dev/null; then
		DB_TYPE=`echo $arg | sed 's/--db-type=//'`
		DB_TYPE_INPUT="1"
	    fi
	    return 0
	    ;;
    esac
    return 1
}

# Looks for long options for MySQL (e.g --all)
# Parameters: ARG
# Returns: 0 if one is found, 1 otherwise
function ezdist_mysql_check_options
{
    local arg
    arg="$1"
    case $arg in
	--mysql)
	    DB_TYPE="mysql"
	    return 0
	    ;;
	--mysql-user*)
	    if echo $arg | grep -e "--mysql-user=" >/dev/null; then
		MYSQL_USER=`echo $arg | sed 's/--mysql-user=//'`
		MYSQL_USER_INPUT="1"
	    fi
	    return 0
	    ;;
	--mysql-password*)
	    if echo $arg | grep -e "--mysql-password=" >/dev/null; then
		MYSQL_PASSWD=`echo $arg | sed 's/--mysql-password=//'`
		MYSQL_PASSWD_INPUT="1"
	    fi
	    return 0
	    ;;
	--mysql-socket*)
	    if echo $arg | grep -e "--mysql-socket=" >/dev/null; then
		MYSQL_SOCKET=`echo $arg | sed 's/--mysql-socket=//'`
		MYSQL_SOCKET_INPUT="1"
	    fi
	    return 0
	    ;;
	--mysql-host*)
	    if echo $arg | grep -e "--mysql-host=" >/dev/null; then
		MYSQL_HOST=`echo $arg | sed 's/--mysql-host=//'`
		MYSQL_HOST_INPUT="1"
	    fi
	    return 0
	    ;;
	--mysql-name*)
	    if echo $arg | grep -e "--mysql-name=" >/dev/null; then
		MYSQL_NAME=`echo $arg | sed 's/--mysql-name=//'`
		MYSQL_NAME_INPUT="1"
	    fi
	    return 0
	    ;;
    esac
    return 1
}

# Looks for long options for PostgreSQL (e.g --all)
# Parameters: ARG
# Returns: 0 if one is found, 1 otherwise
function ezdist_postgresql_check_options
{
    local arg
    arg="$1"
    case $arg in
	--postgresql)
	    DB_TYPE="postgresql"
	    return 0
	    ;;
	--postgresql-user*)
	    if echo $arg | grep -e "--postgresql-user=" >/dev/null; then
		POSTGRESQL_USER=`echo $arg | sed 's/--postgresql-user=//'`
		POSTGRESQL_USER_INPUT="1"
	    fi
	    return 0
	    ;;
	--postgresql-password*)
	    if echo $arg | grep -e "--postgresql-password=" >/dev/null; then
		POSTGRESQL_PASSWD=`echo $arg | sed 's/--postgresql-password=//'`
		POSTGRESQL_PASSWD_INPUT="1"
	    fi
	    return 0
	    ;;
	--postgresql-socket*)
	    if echo $arg | grep -e "--postgresql-socket=" >/dev/null; then
		POSTGRESQL_SOCKET=`echo $arg | sed 's/--postgresql-socket=//'`
		POSTGRESQL_SOCKET_INPUT="1"
	    fi
	    return 0
	    ;;
	--postgresql-host*)
	    if echo $arg | grep -e "--postgresql-host=" >/dev/null; then
		POSTGRESQL_HOST=`echo $arg | sed 's/--postgresql-host=//'`
		POSTGRESQL_HOST_INPUT="1"
	    fi
	    return 0
	    ;;
	--postgresql-name*)
	    if echo $arg | grep -e "--postgresql-name=" >/dev/null; then
		POSTGRESQL_NAME=`echo $arg | sed 's/--postgresql-name=//'`
		POSTGRESQL_NAME_INPUT="1"
	    fi
	    return 0
	    ;;
    esac
    return 1
}

# Creates variable that can be used as parameters to both ez-style scripts
# and the normal MySQL command scripts
function ezdist_db_prepare_params
{
    unset PARAM_EZ_DB_USER
    unset PARAM_EZ_DB_PASSWD
    unset PARAM_EZ_DB_SOCKET
    unset PARAM_EZ_DB_HOST
    unset PARAM_EZ_DB_PORT
    unset PARAM_EZ_DB_NAME
    unset PARAM_EZ_DB_TYPE

    ezdist_is_nonempty "DB_USER" && PARAM_EZ_DB_USER="--db-user=$DB_USER"
    ezdist_is_nonempty "$DB_PASSWD" && PARAM_EZ_DB_PASSWD="--db-password=$DB_PASSWD"
    ezdist_is_nonempty "$DB_SOCKET" && PARAM_EZ_DB_SOCKET="--db-socket=$DB_SOCKET"
    ezdist_is_nonempty "$DB_HOST" && PARAM_EZ_DB_HOST="--db-host=$DB_HOST"
    ezdist_is_nonempty "$DB_PORT" && PARAM_EZ_DB_PORT="--db-port=$DB_HOST"
    ezdist_is_nonempty "$DB_NAME" && PARAM_EZ_DB_NAME="--db-name=$DB_NAME"
    ezdist_is_nonempty "$DB_TYPE" && PARAM_EZ_DB_TYPE="--db-type=$DB_TYPE"

    PARAM_EZ_DB_ALL="$PARAM_EZ_DB_USER $PARAM_EZ_DB_PASSWD $PARAM_EZ_DB_SOCKET $PARAM_EZ_DB_HOST $PARAM_EZ_DB_PORT $PARAM_EZ_DB_NAME $PARAM_EZ_DB_TYPE"
}

# Sets general db parameters (PARAM_EZ_DB_*) from the current PostgreSQL settings
# PARAMS: SHORT_FORM
# If SHORT_FORM is set to 1 the parameters will be --user, --host etc.
#  instead of --db-user, --db-host etc.
function ezdist_db_prepare_params_from_mysql
{
    unset PARAM_EZ_DB_USER
    unset PARAM_EZ_DB_PASSWD
    unset PARAM_EZ_DB_SOCKET
    unset PARAM_EZ_DB_HOST
    unset PARAM_EZ_DB_PORT
    unset PARAM_EZ_DB_NAME
    unset PARAM_EZ_DB_TYPE

    local prefix

    prefix=""
    if [ "x$1" != "x1" ]; then
	prefix="db-"
    fi

    ezdist_is_nonempty "$MYSQL_USER" && PARAM_EZ_DB_USER="--"$prefix"user=$MYSQL_USER"
    ezdist_is_nonempty "$MYSQL_PASSWD" && PARAM_EZ_DB_PASSWD="--"$prefix"password=$MYSQL_PASSWD"
    ezdist_is_nonempty "$MYSQL_SOCKET" && PARAM_EZ_DB_SOCKET="--"$prefix"socket=$MYSQL_SOCKET"
    ezdist_is_nonempty "$MYSQL_HOST" && PARAM_EZ_DB_HOST="--"$prefix"host=$MYSQL_HOST"
    ezdist_is_nonempty "$MYSQL_PORT" && PARAM_EZ_DB_PORT="--"$prefix"port=$MYSQL_PORT"
    ezdist_is_nonempty "$MYSQL_NAME" && PARAM_EZ_DB_NAME="--"$prefix"name=$MYSQL_NAME"
    PARAM_EZ_DB_TYPE="--"$prefix"type=mysql"

    PARAM_EZ_DB_ALL="$PARAM_EZ_DB_USER $PARAM_EZ_DB_PASSWD $PARAM_EZ_DB_SOCKET $PARAM_EZ_DB_HOST $PARAM_EZ_DB_PORT $PARAM_EZ_DB_NAME $PARAM_EZ_DB_TYPE"
}

# Fills in missing MySQL variables form the general DB variables
function ezdist_db_init_mysql_from_defaults
{
    [ -n "$DB_USER_INPUT" ] && MYSQL_USER="$DB_USER"
    [ -n "$DB_PASSWD_INPUT" ] && MYSQL_PASSWD="$DB_PASSWD"
    [ -n "$DB_HOST_INPUT" ] && MYSQL_HOST="$DB_HOST"
    [ -n "$DB_PORT_INPUT" ] && MYSQL_PORT="$DB_PORT"
    [ -n "$DB_SOCKET_INPUT" ] && MYSQL_SOCKET="$DB_SOCKET"
    [ -n "$DB_NAME_INPUT" ] && MYSQL_NAME="$DB_NAME"
}

# Fills in missing Postgresql variables form the general DB variables
function ezdist_db_init_postgresql_from_defaults
{
    [ -n "$DB_USER_INPUT" ] && POSTGRESQL_USER="$DB_USER"
    [ -n "$DB_PASSWD_INPUT" ] && POSTGRESQL_PASSWD="$DB_PASSWD"
    [ -n "$DB_HOST_INPUT" ] && POSTGRESQL_HOST="$DB_HOST"
    [ -n "$DB_PORT_INPUT" ] && POSTGRESQL_PORT="$DB_PORT"
    [ -n "$DB_SOCKET_INPUT" ] && POSTGRESQL_SOCKET="$DB_SOCKET"
    [ -n "$DB_NAME_INPUT" ] && POSTGRESQL_NAME="$DB_NAME"
}

# Prepares shell parameters from the current MySQL settings
# It prepares the various PARAM_EZ_MYSQL_* parameters for eZ publish scripts
# and PARAM_MYSQL_* for mysql commands as well
# as the handy PARAM_EZ_MYSQL_ALL and PARAM_MYSQL_ALL
function ezdist_mysql_prepare_params
{
    ezdist_is_nonempty "$MYSQL_USER" && PARAM_EZ_MYSQL_USER="--mysql-user=$MYSQL_USER"
    ezdist_is_nonempty "$MYSQL_PASSWD" && PARAM_EZ_MYSQL_PASSWD="--mysql-password=$MYSQL_PASSWD"
    ezdist_is_nonempty "$MYSQL_SOCKET" && PARAM_EZ_MYSQL_SOCKET="--mysql-socket=$MYSQL_SOCKET"
    ezdist_is_nonempty "$MYSQL_HOST" && PARAM_EZ_MYSQL_HOST="--mysql-host=$MYSQL_HOST"
    ezdist_is_nonempty "$MYSQL_PORT" && PARAM_EZ_MYSQL_PORT="--mysql-port=$MYSQL_PORT"
    ezdist_is_nonempty "$MYSQL_NAME" && PARAM_EZ_MYSQL_HOST="--mysql-name=$MYSQL_NAME"

    ezdist_is_nonempty "$MYSQL_USER" && PARAM_MYSQL_USER="-u $MYSQL_USER"
    ezdist_is_nonempty "$MYSQL_PASSWD" && PARAM_MYSQL_PASSWD="--password=$MYSQL_PASSWD"
    ezdist_is_nonempty "$MYSQL_SOCKET" && PARAM_MYSQL_SOCKET="--socket=$MYSQL_SOCKET"
    ezdist_is_nonempty "$MYSQL_HOST" && PARAM_MYSQL_HOST="-h $MYSQL_HOST"
    ezdist_is_nonempty "$MYSQL_PORT" && PARAM_MYSQL_PORT="-P $MYSQL_PORT"

    PARAM_EZ_MYSQL_ALL="$PARAM_EZ_MYSQL_USER $PARAM_EZ_MYSQL_PASSWD $PARAM_EZ_MYSQL_SOCKET $PARAM_EZ_MYSQL_HOST $PARAM_EZ_MYSQL_PORT $PARAM_EZ_MYSQL_NAME"
    PARAM_MYSQL_ALL="$PARAM_MYSQL_USER $PARAM_MYSQL_PASSWD $PARAM_MYSQL_SOCKET $PARAM_MYSQL_HOST $PARAM_MYSQL_PORT"
}

# Prepares the PARAM_MYSQL_* and PARAM_MYSQL_ALL parameters from the
# input parameters
# PARAMS: user password socket host port
function ezdist_mysql_prepare_mysql_params
{
    unset PARAM_MYSQL_USER
    unset PARAM_MYSQL_PASSWD
    unset PARAM_MYSQL_SOCKET
    unset PARAM_MYSQL_HOST
    unset PARAM_MYSQL_PORT
    unset PARAM_MYSQL_NAME

    ezdist_is_nonempty "$1" && PARAM_MYSQL_USER="-u $1"
    ezdist_is_nonempty "$2" && PARAM_MYSQL_PASSWD="--password=$2"
    ezdist_is_nonempty "$3" && PARAM_MYSQL_SOCKET="--socket=$3"
    ezdist_is_nonempty "$4" && PARAM_MYSQL_HOST="-h $4"
    ezdist_is_nonempty "$5" && PARAM_MYSQL_PORT="-h $5"

    PARAM_MYSQL_ALL="$PARAM_MYSQL_USER $PARAM_MYSQL_PASSWD $PARAM_MYSQL_SOCKET $PARAM_MYSQL_HOST $PARAM_MYSQL_PORT"
}

# Prepares source parameters from the current MySQL settings
function ezdist_mysql_prepare_source_params
{
    unset PARAM_SOURCE_USER
    unset PARAM_SOURCE_PASSWD
    unset PARAM_SOURCE_HOST
    unset PARAM_SOURCE_PORT

    ezdist_is_nonempty "$MYSQL_USER" && PARAM_SOURCE_USER="--source-user=$MYSQL_USER"
    ezdist_is_nonempty "$MYSQL_PASSWD" && PARAM_SOURCE_PASSWD="--source-password=$MYSQL_PASSWD"
#    ezdist_is_nonempty "$MYSQL_SOCKET" && PARAM_SOURCE_SOCKET="--source-socket=$MYSQL_SOCKET"
    ezdist_is_nonempty "$MYSQL_HOST" && PARAM_SOURCE_HOST="--source-host=$MYSQL_HOST"
    ezdist_is_nonempty "$MYSQL_PORT" && PARAM_SOURCE_PORT="--source-port=$MYSQL_PORT"

    PARAM_SOURCE_ALL="$PARAM_SOURCE_USER $PARAM_SOURCE_PASSWD $PARAM_SOURCE_SOCKET $PARAM_SOURCE_HOST $PARAM_SOURCE_PORT"
}

# Pepares source parameters from the current PostgreSQL settings
function ezdist_postgresql_prepare_source_params
{
    unset PARAM_SOURCE_USER
    unset PARAM_SOURCE_PASSWD
    unset PARAM_SOURCE_HOST
    unset PARAM_SOURCE_PORT

    ezdist_is_nonempty "$POSTGRESQL_USER" && PARAM_SOURCE_USER="--source-user=$POSTGRESQL_USER"
    ezdist_is_nonempty "$POSTGRESQL_PASSWD" && PARAM_SOURCE_PASSWD="--source-password=$POSTGRESQL_PASSWD"
    ezdist_is_nonempty "$POSTGRESQL_HOST" && PARAM_SOURCE_HOST="--source-host=$POSTGRESQL_HOST"
    ezdist_is_nonempty "$POSTGRESQL_PORT" && PARAM_SOURCE_PORT="--source-port=$POSTGRESQL_PORT"

    PARAM_SOURCE_ALL="$PARAM_SOURCE_USER $PARAM_SOURCE_PASSWD $PARAM_SOURCE_SOCKET $PARAM_SOURCE_HOST $PARAM_SOURCE_PORT"
}

# Prepares match parameters from the current MySQL settings
function ezdist_mysql_prepare_match_params
{
    unset PARAM_MATCH_USER
    unset PARAM_MATCH_PASSWD
    unset PARAM_MATCH_HOST
    unset PARAM_MATCH_PORT

    ezdist_is_nonempty "$MYSQL_USER" && PARAM_MATCH_USER="--match-user=$MYSQL_USER"
    ezdist_is_nonempty "$MYSQL_PASSWD" && PARAM_MATCH_PASSWD="--match-password=$MYSQL_PASSWD"
#    ezdist_is_nonempty "$MYSQL_SOCKET" && PARAM_MATCH_SOCKET="--match-socket=$MYSQL_SOCKET"
    ezdist_is_nonempty "$MYSQL_HOST" && PARAM_MATCH_HOST="--match-host=$MYSQL_HOST"
    ezdist_is_nonempty "$MYSQL_PORT" && PARAM_MATCH_PORT="--match-port=$MYSQL_PORT"

    PARAM_MATCH_ALL="$PARAM_MATCH_USER $PARAM_MATCH_PASSWD $PARAM_MATCH_SOCKET $PARAM_MATCH_HOST $PARAM_MATCH_PORT"
}

# Preparse match parameters from the current PostgreSQL settings
function ezdist_postgresql_prepare_match_params
{
    unset PARAM_MATCH_USER
    unset PARAM_MATCH_PASSWD
    unset PARAM_MATCH_HOST
    unset PARAM_MATCH_PORT

    ezdist_is_nonempty "$POSTGRESQL_USER" && PARAM_MATCH_USER="--match-user=$POSTGRESQL_USER"
    ezdist_is_nonempty "$POSTGRESQL_PASSWD" && PARAM_MATCH_PASSWD="--match-password=$POSTGRESQL_PASSWD"
    ezdist_is_nonempty "$POSTGRESQL_HOST" && PARAM_MATCH_HOST="--match-host=$POSTGRESQL_HOST"
    ezdist_is_nonempty "$POSTGRESQL_PORT" && PARAM_MATCH_PORT="--match-port=$POSTGRESQL_PORT"

    PARAM_MATCH_ALL="$PARAM_MATCH_USER $PARAM_MATCH_PASSWD $PARAM_MATCH_SOCKET $PARAM_MATCH_HOST $PARAM_MATCH_PORT"
}

# Creates variable that can be used as parameters to both ez-style scripts
# and the normal PostgreSQL command scripts
# PARAMS: SHORT_FORM
# If SHORT_FORM is set to 1 the parameters will be --user, --host etc.
#  instead of --db-user, --db-host etc.
function ezdist_db_prepare_params_from_postgresql
{
    unset PARAM_EZ_DB_USER
    unset PARAM_EZ_DB_PASSWD
    unset PARAM_EZ_DB_SOCKET
    unset PARAM_EZ_DB_HOST
    unset PARAM_EZ_DB_PORT
    unset PARAM_EZ_DB_NAME
    unset PARAM_EZ_DB_TYPE

    local prefix

    prefix=""
    if [ "x$1" != "x1" ]; then
	prefix="db-"
    fi

    ezdist_is_nonempty "$POSTGRESQL_USER" && PARAM_EZ_DB_USER="--"$prefix"user=$POSTGRESQL_USER"
    ezdist_is_nonempty "$POSTGRESQL_PASSWD" && PARAM_EZ_DB_PASSWD="--"$prefix"password=$POSTGRESQL_PASSWD"
    ezdist_is_nonempty "$POSTGRESQL_HOST" && PARAM_EZ_DB_HOST="--"$prefix"host=$POSTGRESQL_HOST"
    ezdist_is_nonempty "$POSTGRESQL_PORT" && PARAM_EZ_DB_PORT="--"$prefix"port=$POSTGRESQL_PORT"
    ezdist_is_nonempty "$POSTGRESQL_NAME" && PARAM_EZ_DB_NAME="--"$prefix"name=$POSTGRESQL_NAME"
    PARAM_EZ_DB_TYPE="--"$prefix"type=postgresql"

    PARAM_EZ_DB_ALL="$PARAM_EZ_DB_USER $PARAM_EZ_DB_PASSWD $PARAM_EZ_DB_SOCKET $PARAM_EZ_DB_HOST $PARAM_EZ_DB_PORT $PARAM_EZ_DB_NAME $PARAM_EZ_DB_TYPE"
}

# Prepares shell parameters from the current PostgreSQL settings
# It prepares the various PARAM_EZ_POSTGRESQL_* parameters for eZ publish scripts
# and PARAM_POSTGRESQL_* for psql commands as well
# as the handy PARAM_EZ_POSTGRESQL_ALL and PARAM_POSTGRESQL_ALL
function ezdist_postgresql_prepare_params
{
    unset PARAM_EZ_POSTGRESQL_USER
    unset PARAM_EZ_POSTGRESQL_PASSWD
    unset PARAM_EZ_POSTGRESQL_SOCKET
    unset PARAM_EZ_POSTGRESQL_HOST
    unset PARAM_EZ_POSTGRESQL_PORT
    unset PARAM_EZ_POSTGRESQL_NAME

    ezdist_is_nonempty "$POSTGRESQL_USER" && PARAM_EZ_POSTGRESQL_USER="--postgresql-user=$POSTGRESQL_USER"
    ezdist_is_nonempty "$POSTGRESQL_PASSWD" && PARAM_EZ_POSTGRESQL_PASSWD="--postgresql-password=$POSTGRESQL_PASSWD"
    ezdist_is_nonempty "$POSTGRESQL_HOST" && PARAM_EZ_POSTGRESQL_HOST="--postgresql-host=$POSTGRESQL_HOST"
    ezdist_is_nonempty "$POSTGRESQL_PORT" && PARAM_EZ_POSTGRESQL_PORT="--postgresql-port=$POSTGRESQL_PORT"
    ezdist_is_nonempty "$POSTGRESQL_NAME" && PARAM_EZ_POSTGRESQL_NAME="--postgresql-name=$POSTGRESQL_NAME"

    ezdist_is_nonempty "$POSTGRESQL_USER" && PARAM_POSTGRESQL_USER="-U $POSTGRESQL_USER"
    ezdist_is_nonempty "$POSTGRESQL_PASSWD" && PARAM_POSTGRESQL_PASSWD="--password=$POSTGRESQL_PASSWD"
    ezdist_is_nonempty "$POSTGRESQL_HOST" && PARAM_POSTGRESQL_HOST="-h $POSTGRESQL_HOST"
    ezdist_is_nonempty "$POSTGRESQL_PORT" && PARAM_POSTGRESQL_PORT="-p $POSTGRESQL_PORT"

    PARAM_EZ_POSTGRESQL_ALL="$PARAM_EZ_POSTGRESQL_USER $PARAM_EZ_POSTGRESQL_PASSWD $PARAM_EZ_POSTGRESQL_HOST $PARAM_EZ_POSTGRESQL_PORT $PARAM_EZ_POSTGRESQL_NAME"
    PARAM_POSTGRESQL_ALL="$PARAM_POSTGRESQL_USER $PARAM_POSTGRESQL_HOST $PARAM_POSTGRESQL_PORT"
}

# Prepares the PARAM_POSTGRESQL_* and PARAM_POSTGRESQL_ALL parameters from the
# input parameters
# PARAMS: user password socket host port
# Note: socket is currently not used
function ezdist_postgresql_prepare_postgresql_params
{
    unset PARAM_POSTGRESQL_USER
    unset PARAM_POSTGRESQL_PASSWD
    unset PARAM_POSTGRESQL_HOST
    unset PARAM_POSTGRESQL_PORT

    ezdist_is_nonempty "$1" && PARAM_POSTGRESQL_USER="-U $1"
    ezdist_is_nonempty "$2" && PARAM_POSTGRESQL_PASSWD="--password=$2"
    ezdist_is_nonempty "$3" && PARAM_POSTGRESQL_HOST="-h $3"
    ezdist_is_nonempty "$5" && PARAM_POSTGRESQL_PORT="-p $5"

    PARAM_POSTGRESQL_ALL="$PARAM_POSTGRESQL_USER $PARAM_POSTGRESQL_PASSWD $PARAM_POSTGRESQL_HOST $PARAM_POSTGRESQL_PORT"
}

# Reads in database settings from the user and stores them in variables
# Note: Will only ask if the variable is not yet defined.
function ezdist_db_read_info
{
    local user
    local passwd
    local host

    if ezdist_is_undef "$DB_USER"; then
	echo -n "DB: Please enter username for db access [$USER]: "
	read user
	if [ -z "$user" ]; then
	    # Use the current user, this must have db access for this to work
	    DB_USER="$USER"
	else
	    DB_USER="$user"
	fi
    fi

    if ezdist_is_undef "$DB_PASSWD"; then
	echo -n "DB: Please enter password for db access [<empty>]: "
	read passwd
	if [ -z "$passwd" ]; then
	    DB_PASSWD="none"
	else
	    DB_PASSWD="$passwd"
	fi
    fi

    if ezdist_is_undef "$DB_SOCKET"; then
	echo -n "DB: Please enter socket for db access [<no socket>]: "
	read socket
	if [ -z "$socket" ]; then
	    DB_SOCKET="none"
	else
	    DB_SOCKET="$socket"
	fi
    fi

    if ezdist_is_undef "$DB_HOST"; then
	echo -n "DB: Please enter host for db access [<locally>]: "
	read host
	if [ -z "$host" ]; then
	    DB_HOST="none"
	else
	    DB_HOST="$host"
	fi
    fi

    if ezdist_is_undef "$DB_PORT"; then
	echo -n "DB: Please enter port for db access [<default>]: "
	read port
	if [ -z "$port" ]; then
	    DB_PORT="none"
	else
	    DB_PORT="$port"
	fi
    fi
}

# Same as ezdist_db_read_info but will read MySQL settings
function ezdist_mysql_read_info
{
    local user
    local passwd
    local socket
    local host
    if ezdist_is_undef "$MYSQL_USER"; then
	echo -n "MySQL: Please enter username for db access [root]: "
	read user
	if [ -z "$user" ]; then
	    MYSQL_USER="root"
	else
	    MYSQL_USER="$user"
	fi
    fi

    if ezdist_is_undef "$MYSQL_PASSWD"; then
	echo -n "MySQL: Please enter password for db access [<empty>]: "
	read passwd
	if [ -z "$passwd" ]; then
	    MYSQL_PASSWD="none"
	else
	    MYSQL_PASSWD="$passwd"
	fi
    fi

    if ezdist_is_undef "$MYSQL_SOCKET"; then
	echo -n "MySQL: Please enter socket for db access [<no socket>]: "
	read socket
	if [ -z "$socket" ]; then
	    MYSQL_SOCKET="none"
	else
	    MYSQL_SOCKET="$socket"
	fi
    fi

    if ezdist_is_undef "$MYSQL_HOST"; then
	echo -n "MySQL: Please enter host for db access [<locally>]: "
	read host
	if [ -z "$host" ]; then
	    MYSQL_HOST="none"
	else
	    MYSQL_HOST="$host"
	fi
    fi

    if ezdist_is_undef "$MYSQL_PORT"; then
	echo -n "MySQL: Please enter port for db access [<default>]: "
	read port
	if [ -z "$port" ]; then
	    MYSQL_PORT="none"
	else
	    MYSQL_PORT="$port"
	fi
    fi
}

# Same as ezdist_db_read_info but will read PostgreSQL settings
function ezdist_postgresql_read_info
{
    local user
    local passwd
    local host

    if ezdist_is_undef "$POSTGRESQL_USER"; then
	echo -n "PostgreSQL: Please enter username for db access [$USER]: "
	read user
	if [ -z "$user" ]; then
	    # Use the current user, this must have db access for this to work
	    POSTGRESQL_USER="$USER"
	else
	    POSTGRESQL_USER="$user"
	fi
    fi

    if ezdist_is_undef "$POSTGRESQL_PASSWD"; then
	echo -n "PostgreSQL: Please enter password for db access [<empty>]: "
	read passwd
	if [ -z "$passwd" ]; then
	    POSTGRESQL_PASSWD="none"
	else
	    POSTGRESQL_PASSWD="$passwd"
	fi
    fi

    if ezdist_is_undef "$POSTGRESQL_HOST"; then
	echo -n "PostgreSQL: Please enter host for db access [<locally>]: "
	read host
	if [ -z "$host" ]; then
	    POSTGRESQL_HOST="none"
	else
	    POSTGRESQL_HOST="$host"
	fi
    fi

    if ezdist_is_undef "$POSTGRESQL_PORT"; then
	echo -n "PostgreSQL: Please enter port for db access [<default>]: "
	read port
	if [ -z "$port" ]; then
	    POSTGRESQL_PORT="none"
	else
	    POSTGRESQL_PORT="$port"
	fi
    fi
}

# Displays the current database config using a generic URL form
function ezdist_db_show_config
{
    ezdist_is_empty "$DB_TYPE" && echo -n "db://" || echo -n "$DB_TYPE://"
    if ezdist_is_nonempty "$DB_USER"; then
	echo -n "$DB_USER"
	ezdist_is_empty "$DB_PASSWD" || echo -n ":***"
	echo -n "@"
    fi
    ezdist_is_empty "$DB_HOST" && echo -n "localhost" || echo -n "$DB_HOST"
    ezdist_is_empty "$DB_PORT" || echo -n ":$DB_PORT"
    ezdist_is_empty "$DB_NAME" || echo -n "/$DB_NAME"
    ezdist_is_empty "$DB_SOCKET" || echo -n "?socket=$DB_SOCKET"
}

# Displays the current MySQL database config using a generic URL form
function ezdist_mysql_show_config
{
    echo -n "mysql://"
    if ezdist_is_nonempty "$MYSQL_USER"; then
	echo -n "$MYSQL_USER"
	ezdist_is_empty "$MYSQL_PASSWD" || echo -n ":***"
	echo -n "@"
    fi
    ezdist_is_empty "$MYSQL_HOST" && echo -n "localhost" || echo -n "$MYSQL_HOST"
    ezdist_is_empty "$MYSQL_PORT" || echo -n ":$MYSQL_PORT"
    ezdist_is_empty "$MYSQL_NAME" || echo -n "/$MYSQL_NAME"
    ezdist_is_empty "$MYSQL_SOCKET" || echo -n "?socket=$MYSQL_SOCKET"
}

# Displays the current PostgreSQL database config using a generic URL form
function ezdist_postgresql_show_config
{
    echo -n "postgresql://"
    if ezdist_is_nonempty "$POSTGRESQL_USER"; then
	echo -n "$POSTGRESQL_USER"
	ezdist_is_empty "$POSTGRESQL_PASSWD" || echo -n ":***"
	echo -n "@"
    fi
    ezdist_is_empty "$POSTGRESQL_HOST" && echo -n "localhost" || echo -n "$POSTGRESQL_HOST"
    ezdist_is_empty "$POSTGRESQL_PORT" || echo -n ":$POSTGRESQL_PORT"
    ezdist_is_empty "$POSTGRESQL_NAME" || echo -n "/$POSTGRESQL_NAME"
}

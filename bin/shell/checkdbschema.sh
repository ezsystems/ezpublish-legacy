#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

DEVELOPMENT="false"
[ -n $VERSION_STATE ] && DEVELOPMENT="true"

SUBPATH=""
[ "$DEVELOPMENT" == "true" ] && SUBPATH="unstable/"

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
            echo

	    # Show options for database
	    ezdist_mysql_show_options
	    ezdist_postgresql_show_options

            echo
	    exit 1
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

if [ -z "$DATABASE_NAME" ]; then
    echo "No database name specified"
    exit 1
fi

# Init MySQL
ezdist_db_init_mysql_from_defaults
echo "Connecting to MySQL using `ezdist_mysql_show_config`"
ezdist_mysql_prepare_params

# Init PostgreSQL
ezdist_db_init_postgresql_from_defaults
echo "Connecting to PostgreSQL using `ezdist_postgresql_show_config`"
ezdist_postgresql_prepare_params

DEST="/tmp/ez-$USER"
SCHEMA_URL="http://svn.ez.no/svn/ezpublish/versions/$VERSION"
PREVIOUS_SCHEMA_URL="http://svn.ez.no/svn/ezpublish/versions/$VERSION_PREVIOUS"

[ -d "$DEST" ] || mkdir "$DEST"

## MySQL

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

echo -n " `$POSITION_STORE`Initializing"
for sql_file in $KERNEL_MYSQL_SCHEMA_FILES; do
    mysql $PARAM_MYSQL_ALL "$DATABASE_NAME" < "$sql_file"
    if [ $? -ne 0 ]; then
	echo
	echo "Failed to initialize MySQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with $sql_file"
	exit 1
    fi
done
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Initializing`$SETCOLOR_NORMAL`"

echo

## PostgreSQL

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

echo -n " `$POSITION_STORE`Initializing"
for sql_file in $KERNEL_POSTGRESQL_SCHEMA_FILES; do
    psql $PARAM_POSTGRESQL_ALL "$DATABASE_NAME" < "$sql_file" &>.psql.log
    if cat .psql.log | grep 'ERROR:' &>/dev/null; then
	echo
	echo "Failed to initialize PostgreSQL database `$SETCOLOR_EMPHASIZE`$DATABASE_NAME`$SETCOLOR_NORMAL` with $sql_file"
	echo `$SETCOLOR_FAILURE`
	cat .psql.log
	echo `$SETCOLOR_NORMAL`
	rm .psql.log
	exit 1
    fi
    rm .psql.log
done
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Initializing`$SETCOLOR_NORMAL`"

echo

## Validation

ezdist_mysql_prepare_source_params
ezdist_postgresql_prepare_match_params

echo -n "`$POSITION_STORE`Validating"
./bin/php/ezsqldiff.php --source-type=mysql $PARAM_SOURCE_ALL --match-type=postgresql $PARAM_MATCH_ALL "$DATABASE_NAME" "$DATABASE_NAME" &>/dev/null
if [ $? -ne 0 ]; then
    echo
    echo "Database schemas did not match, this could mean that one of the database is missing an SQL update"
    echo "Check the database difference with"
    echo "./bin/php/ezsqldiff.php --source-type=mysql $PARAM_SOURCE_ALL --match-type=postgresql $PARAM_MATCH_ALL \"$DATABASE_NAME\" \"$DATABASE_NAME\""
    exit 1
fi
echo -n "`$POSITION_RESTORE``$SETCOLOR_EMPHASIZE`Validating`$SETCOLOR_NORMAL`"

echo -n "  `$SETCOLOR_SUCCESS`[  OK  ]`$SETCOLOR_NORMAL`"
echo

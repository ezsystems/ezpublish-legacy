#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

SQLFILES=""
SQLDUMP=""

SCHEMAFILES=""

USE_MYSQL=""
USE_POSTGRESQL=""

if ! which php &>/dev/null; then
    echo "No PHP executable found, please add it to the path"
    exit 1
fi

function help
{
      echo "Usage: $0 [options] DBNAME SQLFILE [SQLFILE]..."
      echo
      echo "Options: -h"
      echo "         --help                     This message"
      echo "         --db-user                  Which database user to connect with"
      echo "         --db-host                  Which database host to connect to"
      echo "         --sql-data-only            Only dump table data"
      echo "         --sql-schema-only          Only dump table definitions"
      echo "         --sql-full                 Dump table definition and data (default)"
      echo "         --clean                    Cleanup various data entries before dumping (e.g. session, drafts)"
      echo "         --clean-search             Cleanup search index (implies --clean)"
      echo "         --mysql                    Redump using MySQL"
      echo "         --postgresql               Redump using PostgreSQL"
      echo "         --schema-sql=FILE          Schema sql file to use before the SQLFILE,"
      echo "                                    useful for data only redumping"
      echo "         --setval-file=FILE         File to write setval statements to*"
      echo

      # Show options for database
      ezdist_mysql_show_options
      ezdist_postgresql_show_options

      echo
      echo "* Postgresql only"
      echo "Example:"
      echo "$0 --mysql tmp data.sql"
}

# Initialise several database related variables, see sqlcommon.sh
ezdist_db_init

# Check parameters
for arg in $*; do
    case $arg in

    --help|-h)
        help
        exit 1
        ;;
    --sql-data-only)
        SQLDUMP="data"
        NOCREATEINFOARG="-t"
        ;;
    --sql-schema-only)
        SQLDUMP="schema"
        NODATAARG="-n"
        NOCREATEINFOARG=""
        ;;
    --sql-full)
        SQLDUMP=""
        NODATAARG=""
        NOCREATEINFOARG=""
        ;;
	--sql-schema-file=*)
        if echo $arg | grep -e "--sql-schema-file=" >/dev/null; then
            SQL_SCHEMA_FILE=`echo $arg | sed 's/--sql-schema-file=//'`
        fi
        ;;
    --clean)
        CLEAN="1"
        ;;
	--clean-search)
        CLEAN="1"
        CLEAN_SEARCH="1"
        ;;
    --mysql)
        USE_MYSQL="yes"
        ;;
    --pause)
        USE_PAUSE="yes"
        ;;
    --setval-file=*)
        if echo $arg | grep -e "--setval-file=" >/dev/null; then
            SETVALFILE=`echo $arg | sed 's/--setval-file=//'`
        fi
        ;;
    --postgresql)
        USE_POSTGRESQL="yes"
        ;;
    --schema-sql=*)
        if echo $arg | grep -e "--schema-sql=" >/dev/null; then
            SCHEMAFILE=`echo $arg | sed 's/--schema-sql=//'`
            SCHEMAFILES="$SCHEMAFILES $SCHEMAFILE"
        fi
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
            DBNAME=$arg
        elif [ -z $SQLFILE ]; then
            SQLFILE=$arg
        else
            SQLFILES="$SQLFILES $arg"
            fi
        ;;
    esac;
done

if [ -z $DBNAME ]; then
    echo "Missing database name"
    help
    exit 1;
fi
if [ -z $SQLFILE ]; then
    echo "Missing sql file"
    help
    exit 1;
fi
if [ ! -f "$SQLFILE" ]; then
    echo "SQL `ez_color_file $SQLFILE` file does not exist"
    help
    exit 1;
fi

if [ "$USE_MYSQL" == "" -a "$USE_POSTGRESQL" == "" ]; then
    echo "No database type chosen"
    help
    exit 1
fi

#if [ -z "$DB_USER" ]; then
#    DB_USER="$POST_USER"
#fi

function ez_cleanup_db
{
    if [[ "$SQLDUMP" != "schema" && -n $CLEAN ]]; then
        #[ -n "$DB_USER" ] && DB_USER_OPT="--db-user=$DB_USER"
        #[ -n "$DB_HOST" ] && DB_HOST_OPT="--db-server=$DB_HOST"
        #[ -n "$DB_PWD" ] && DB_PWD_OPT="--db-password=$DB_PWD"
        echo -n "Flattening objects"
        ezdist_db_prepare_params_from_mysql ""
        ./bin/php/flatten.php --db-driver=mysql $PARAM_EZ_DB_ALL --db-database=$DBNAME all &>.mysql.log
        ez_result_file $? .mysql.log || exit 1
        if [ $CLEAN_SEARCH ]; then
            echo -n "Cleaning search engine"
            ./update/common/scripts/updatesearchindex.php --db-driver=mysql $PARAM_EZ_DB_ALL --db-database=$DBNAME --clean &>.mysql.log
            ez_result_file $? .mysql.log || exit 1
        fi
        echo -n "Updating nice urls"
        ./update/common/scripts/updateniceurls.php --db-driver=mysql $PARAM_EZ_DB_ALL --db-database=$DBNAME &>.mysql.log
        ez_result_file $? .mysql.log || exit 1
        echo -n "Cleaning up data"
        ./update/common/scripts/cleanup.php --db-driver=mysql $PARAM_EZ_DB_ALL --db-database=$DBNAME all &>.mysql.log
        ez_result_file $? .mysql.log || exit 1
    fi
}

if [ "$USE_MYSQL" != "" ]; then
    # Init MySQL
    ezdist_db_init_mysql_from_defaults
    echo "Connecting to MySQL using `ezdist_mysql_show_config`"
    ezdist_mysql_prepare_params

    [ -n "$SQLDUMP" ] && OUTPUT_TYPES_ARG="--output-types=$SQLDUMP"
    [ -n "$SQLDUMP" ] && [ -n "$SCHEMAFILE" ] && DUMP_SCHEMA_FILE="--schema-file=$SCHEMAFILE"
#     [ -n "$DB_USER" ] && USERARG="-u$DB_USER"
#     [ -n "$DB_HOST" ] && HOSTARG="-h$DB_HOST"
#     [ -n "$DB_PWD" ] && PWDARG="-p$DB_PWD"

    function ez_mysql_loadschema
    {
        local file
        local status
        file="$1"
        case $file in
            *.dba)
                # Create SQL from generic schema and dump them to mysql
                ./bin/php/ezsqldumpschema.php --type=mysql --output-sql "$file" | mysql $PARAM_MYSQL_ALL "$DBNAME"
                status=$?
                ;;
            *.sql)
                # Import SQL directly to mysql
                mysql $PARAM_MYSQL_ALL "$DBNAME" < "$file"
                status=$?
                ;;
            *)
                echo "Unknown file type '$file'" >/dev/stderr
                status=1
                ;;
        esac
        return $status
    }

    function ez_mysql_loadfile
    {
        local file
        local status
        file="$1"
        case $file in
            *.dba)
                # Create SQL from generic schema and dump them to mysql
                ./bin/php/ezsqldumpschema.php --type=mysql --output-sql $OUTPUT_TYPES_ARG $DUMP_SCHEMA_FILE "$file" | mysql $PARAM_MYSQL_ALL "$DBNAME"
                status=$?
            ;;
            *.sql)
                # Import SQL directly to mysql
                mysql $PARAM_MYSQL_ALL "$DBNAME" < "$file"
                status=$?
            ;;
            *)
                echo "Unknown file type '$file'" >/dev/stderr
                status=1
            ;;
        esac
        return $status
    }

    mysqladmin $PARAM_MYSQL_ALL -f drop "$DBNAME" &>/dev/null
    echo -n "Creating database `ez_color_comment $DBNAME`"
    mysqladmin $PARAM_MYSQL_ALL create "$DBNAME" 2>.mysql.log
    ez_result_file $? .mysql.log || exit 1
    for sql in $SCHEMAFILES; do
        echo -n "Importing schema SQL file `ez_color_file $sql`"
        ez_mysql_loadschema "$sql" 2>.mysql.log
        ez_result_file $? .mysql.log || exit 1
    done
    echo -n "Importing SQL file `ez_color_file $SQLFILE`"
    ez_mysql_loadfile "$SQLFILE" 2>.mysql.log
    ez_result_file $? .mysql.log || exit 1
    for sql in $SQLFILES; do
        echo -n "Importing SQL file `ez_color_file $sql`"
        ez_mysql_loadfile "$sql" 2>.mysql.log
        ez_result_file $? .mysql.log || exit 1
    done

    if [ ! -z $USE_PAUSE ]; then
        read -p "`$SETCOLOR_EMPHASIZE`SQL dump paused, press any key to continue.`$SETCOLOR_NORMAL`" TMP
    fi

    # Cleanup database data
    ez_cleanup_db

    if [ -n "$SQL_SCHEMA_FILE" ]; then
        [ -z "$SQLDUMP" ] && SQLDUMP="all"
        [ -n "$SQLDUMP" ] && DB_OUTPUT_TYPES="--output-types=$SQLDUMP"
        # [ -n "$DB_USER" ] && DB_USER_OPT="--user=$DB_USER"
        # [ -n "$DB_HOST" ] && DB_HOST_OPT="--host=$DB_HOST"
        # [ -n "$DB_PWD" ] && DB_PWD_OPT="--password=$DB_PWD"

        ezdist_db_prepare_params_from_mysql "1"
        echo -n "Dumping to SQL file `ez_color_file $SQL_SCHEMA_FILE`"
        ./bin/php/ezsqldumpschema.php --type=mysql --output-array $PARAM_EZ_DB_ALL $DB_OUTPUT_TYPES "$DBNAME" >"$SQL_SCHEMA_FILE".0 2>.mysql.log
        ez_result_file $? .mysql.log || exit 1
    fi
else
    # Init PostgreSQL
    ezdist_db_init_postgresql_from_defaults
    echo "Connecting to PostgreSQL using `ezdist_postgresql_show_config`"
    ezdist_postgresql_prepare_params

    [ -n "$SQLDUMP" ] && OUTPUT_TYPES_ARG="--output-types=$SQLDUMP"
    [ -n "$SQLDUMP" ] && [ -n "$SCHEMAFILE" ] && DUMP_SCHEMA_FILE="--schema-file=$SCHEMAFILE"
    # [ -n "$DB_USER" ] && USERARG="--username $DB_USER"
    # [ -n "$DB_HOST" ] && HOSTARG="--host $DB_HOST"
    # [ -n "$DB_PWD" ] && PWDARG="--password $DB_PWD"

    function ez_postgresql_loadschema
    {
        local file
        local status
        file="$1"
        case $file in
            *.dba)
                # Create SQL from generic schema and dump them to postgresql
                ./bin/php/ezsqldumpschema.php --type=postgresql --output-sql "$file" | psql $PARAM_POSTGRESQL_ALL "$DBNAME"
                status=$?
                ;;
            *.sql)
                # Import SQL directly to postgresql
                psql $PARAM_POSTGRESQL_ALL "$DBNAME" < "$file"
                status=$?
                ;;
            *)
                echo "Unknown file type '$file'" >/dev/stderr
                status=1
                ;;
        esac
        return $status
    }

    function ez_postgresql_loadfile
    {
        local file
        local status
        file="$1"
        case $file in
            *.dba)
                # Create SQL from generic schema and dump them to postgresql
                ./bin/php/ezsqldumpschema.php --type=postgresql --output-sql $OUTPUT_TYPES_ARG $DUMP_SCHEMA_FILE "$file" | psql $PARAM_POSTGRESQL_ALL "$DBNAME"
                status=$?
                ;;
            *.sql)
                # Import SQL directly to postgresql
                psql $PARAM_POSTGRESQL_ALL "$DBNAME" < "$file"
                status=$?
                ;;
            *)
                echo "Unknown file type '$file'" >/dev/stderr
                status=1
                ;;
        esac
        return $status
    }

    psql --version | grep 'psql (PostgreSQL) 7.3' &>/dev/null || \
    psql --version | grep 'psql (PostgreSQL) 7.4' &>/dev/null || \
    psql --version | grep 'psql (PostgreSQL) 8.' &>/dev/null
    if [ $? -ne 0 ]; then
        echo "You cannot run this command on your PostgreSQL version, requires 7.3"
        exit 1
    fi
    dropdb $PARAM_POSTGRESQL_ALL "$DBNAME" &>/dev/null
    echo -n "Creating database `ez_color_comment $DBNAME`"
    createdb $PARAM_POSTGRESQL_ALL "$DBNAME" &>.psql.log
    ez_result_file $? .psql.log || exit 1

    for sql in $SCHEMAFILES; do
        echo -n "Importing schema SQL file `ez_color_file $sql`"
        ez_postgresql_loadschema "$sql" &>.psql.log || exit 1
        pg_error_code ".psql.log"
        ez_result_file $? .psql.log || exit 1
        rm .psql.log
    done
    echo -n "Importing SQL file `ez_color_file $SQLFILE`"
    ez_postgresql_loadfile "$SQLFILE" &>.psql.log || exit 1
    pg_error_code ".psql.log"
    ez_result_file $? .psql.log || exit 1
    rm .psql.log
    for sql in $SQLFILES; do
        echo -n "Importing SQL file `ez_color_file $sql`"
        ez_postgresql_loadfile "$sql" &>.psql.log || exit 1
        pg_error_code ".psql.log"
        ez_result_file $? .psql.log || exit 1
        rm .psql.log
    done

    if [ ! -z $USE_PAUSE ]; then
        read -p "`$SETCOLOR_EMPHASIZE`SQL dump paused, press any key to continue.`$SETCOLOR_NORMAL`" TMP
    fi

    # Cleanup database data
    ez_cleanup_db
    if [ -n "$SQL_SCHEMA_FILE" ]; then
        [ -z "$SQLDUMP" ] && SQLDUMP="all"
        [ -n "$SQLDUMP" ] && DB_OUTPUT_TYPES="--output-types=$SQLDUMP"
        [ -n "$DB_USER" ] && DB_USER_OPT="--user=$DB_USER"
        [ -n "$DB_HOST" ] && DB_HOST_OPT="--host=$DB_HOST"
        [ -n "$DB_PWD" ] && DB_PWD_OPT="--password=$DB_PWD"

        ezdist_db_prepare_params_from_postgresql "1"
        echo -n "Dumping to SQL file `ez_color_file $SQL_SCHEMA_FILE`"
        ./bin/php/ezsqldumpschema.php --type=postgresql --output-array $PARAM_EZ_DB_ALL $DB_OUTPUT_TYPES "$DBNAME" >"$SQL_SCHEMA_FILE".0 2>.psql.log
        ez_result_file $? .psql.log || exit 1
    fi
fi

function ez_file_make_backup
{
    local file
    local suffix
    file="$1"
    suffix="$2"
    if [ -f "$file" ]; then
        mv "$file" "$file"~
    fi
    mv "$file"."$suffix" "$file"
}

if [ $? -eq 0 ]; then
    ez_file_make_backup "$SQL_SCHEMA_FILE" "0"
    # [ -z $SETVALFILE ] || mv "$SETVALFILE".0 "$SETVALFILE"
    echo "Redumped `ez_color_file $SQL_SCHEMA_FILE` using $DBNAME database"
else
    rm "$SQL_SCHEMA_FILE".0
    # [ -z $SETVALFILE ] || rm "$SETVALFILE".0
    echo "Failed dumping database $DBNAME to `ez_color_file $SQL_SCHEMA_FILE`"
    exit 1
fi


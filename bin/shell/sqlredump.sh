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
    echo "SQL $SQLFILE file does not exist"
    help
    exit 1;
fi

if [ "$USE_MYSQL" == "" -a "$USE_POSTGRESQL" == "" ]; then
    echo "No database type chosen"
    help
    exit 1
fi

if [ "$USE_MYSQL" != "" ]; then
    # Init MySQL
    ezdist_db_init_mysql_from_defaults
    echo "Connecting to MySQL using `ezdist_mysql_show_config`"
    ezdist_mysql_prepare_params

    mysqladmin $PARAM_MYSQL_ALL -f drop "$DBNAME"
    mysqladmin $PARAM_MYSQL_ALL create "$DBNAME" || exit 1
    for sql in $SCHEMAFILES; do
	echo "Importing schema SQL file $sql"
	mysql $PARAM_MYSQL_ALL "$DBNAME" < "$sql" || exit 1
    done
    echo "Importing SQL file $SQLFILE"
    mysql $PARAM_MYSQL_ALL "$DBNAME" < "$SQLFILE" || exit 1
    for sql in $SQLFILES; do
	echo "Importing SQL file $sql"
	mysql $PARAM_MYSQL_ALL "$DBNAME" < "$sql" || exit 1
    done

    if [ ! -z $USE_PAUSE ]; then
	read -p "`$SETCOLOR_EMPHASIZE`SQL dump paused, press any key to continue.`$SETCOLOR_NORMAL`" TMP
    fi

    if [[ "$SQLDUMP" != "schema" && -n $CLEAN ]]; then
	./update/common/scripts/flatten.php --db-driver=mysql "--db-database=$DBNAME" $PARAM_EZ_MYSQL_ALL all
	[ $CLEAN_SEARCH ] && ./update/common/scripts/updatesearchindex.php --db-driver=mysql "--db-database=$DBNAME" $PARAM_EZ_MYSQL_ALL --clean
	./update/common/scripts/updateniceurls.php --db-driver=mysql "--db-database=$DBNAME" $PARAM_EZ_MYSQL_ALL
	./update/common/scripts/cleanup.php --db-driver=mysql "--db-database=$DBNAME" $PARAM_EZ_MYSQL_ALL all
    fi

    echo "Dumping to SQL file $SQLFILE"
    if [ "$SQLDUMP" == "schema" ]; then
	mysqldump $PARAM_MYSQL_ALL -c --quick -d "$DBNAME" | perl -pi -e "s/(^--.*$)|(^#.*$)//g" > "$SQLFILE".0
    elif [ "$SQLDUMP" == "data" ]; then
	mysqldump $PARAM_MYSQL_ALL -c --quick -t "$DBNAME" | perl -pi -e "s/(^--.*$)|(^#.*$)//g" > "$SQLFILE".0
    else
	mysqldump $PARAM_MYSQL_ALL -c --quick "$DBNAME" | perl -pi -e "s/(^--.*$)|(^#.*$)//g" > "$SQLFILE".0
    fi
    perl -pi -e "s/(^--.*$)|(^#.*$)//g" "$SQLFILE".0
else
    # Init PostgreSQL
    ezdist_db_init_postgresql_from_defaults
    echo "Connecting to PostgreSQL using `ezdist_postgresql_show_config`"
    ezdist_postgresql_prepare_params

    psql --version | grep 'psql (PostgreSQL) 7.3' &>/dev/null
    if [ $? -ne 0 ]; then
	POSTGRESQL_VERSION=`psql --version | grep -E 'psql \(PostgreSQL\) [0-9][0-9.]*' | sed 's#^psql (PostgreSQL)  *##'`
	echo "You cannot run this command on your PostgreSQL version ($POSTGRESQL_VERSION), requires 7.3"
	exit 1
    fi

    dropdb $PARAM_POSTGRESQL_ALL "$DBNAME"
    createdb $PARAM_POSTGRESQL_ALL "$DBNAME" || exit 1
    for sql in $SCHEMAFILES; do
	echo "Importing schema SQL file $sql"
	psql $PARAM_POSTGRESQL_ALL "$DBNAME" < "$sql" &>.psql.log || exit 1
	if cat .psql.log | grep 'ERROR:' &>/dev/null; then
	    echo "`$SETCOLOR_FAILURE`Postgresql import from schema $sql failed`$SETCOLOR_NORMAL`"
	    echo `$SETCOLOR_FAILURE`
	    cat .psql.log
	    echo `$SETCOLOR_NORMAL`
	    rm .psql.log
	    exit 1
	fi
	rm .psql.log
    done
    echo "Importing SQL file $SQLFILE"
    psql $PARAM_POSTGRESQL_ALL "$DBNAME" < "$SQLFILE" &>.psql.log || exit 1
    if cat .psql.log | grep 'ERROR:' &>/dev/null; then
	echo "`$SETCOLOR_FAILURE`Postgresql import from $sql failed`$SETCOLOR_NORMAL`"
	echo `$SETCOLOR_FAILURE`
	cat .psql.log
	echo `$SETCOLOR_NORMAL`
	rm .psql.log
	exit 1
    fi
    rm .psql.log
    for sql in $SQLFILES; do
	echo "Importing SQL file $sql"
        psql $PARAM_POSTGRESQL_ALL "$DBNAME" < "$sql" &>.psql.log || exit 1
        if cat .psql.log | grep 'ERROR:' &>/dev/null; then
            echo "`$SETCOLOR_FAILURE`Postgresql import from $sql failed`$SETCOLOR_NORMAL`"
	    echo `$SETCOLOR_FAILURE`
            cat .psql.log
	    echo `$SETCOLOR_NORMAL`
            rm .psql.log
            exit 1
        fi
        rm .psql.log
    done

    if [ ! -z $USE_PAUSE ]; then
	read -p "`$SETCOLOR_EMPHASIZE`SQL dump paused, press any key to continue.`$SETCOLOR_NORMAL`" TMP
    fi

    if [[ "$SQLDUMP" != "schema" && -n $CLEAN ]]; then
	./update/common/scripts/flatten.php --db-driver=postgresql "--db-database=$DBNAME" $PARAM_EZ_POSTGRESQL_ALL all
	[ $CLEAN_SEARCH ] && ./update/common/scripts/updatesearchindex.php --db-driver=postgresql "--db-database=$DBNAME" $PARAM_EZ_POSTGRESQL_ALL --clean
	./update/common/scripts/updateniceurls.php --db-driver=postgresql "--db-database=$DBNAME" $PARAM_EZ_POSTGRESQL_ALL
	./update/common/scripts/cleanup.php --db-driver=postgresql "--db-database=$DBNAME" $PARAM_EZ_POSTGRESQL_ALL all
    fi

    echo "Dumping to SQL file $SQLFILE"
    if [ "$SQLDUMP" == "schema" ]; then
	pg_dump --no-owner --inserts --schema-only $PARAM_POSTGRESQL_ALL "$DBNAME" > "$SQLFILE".0
    elif [ "$SQLDUMP" == "data" ]; then
	pg_dump --no-owner --inserts --data-only $PARAM_POSTGRESQL_ALL "$DBNAME" > "$SQLFILE".0
    else
	pg_dump --no-owner --inserts $PARAM_POSTGRESQL_ALL "$DBNAME" > "$SQLFILE".0
    fi
    if [ -n $SETVALFILE ]; then
	(echo "select 'SELECT setval(\'' || relname || '_s\',max(id)+1) FROM ' || relname || ';' as query from pg_class where relname in (  select substring(relname FROM '^(.*)_s$') from pg_class where relname like 'ez%\_s' and  relname != 'ezcontentobject_tree_s'  and relkind='S' );" | psql $PARAM_POSTGRESQL_ALL "$DBNAME" -P format=unaligned -t > "$SETVALFILE".0 && echo "SELECT setval('ezcontentobject_tree_s', max(node_id)+1) FROM ezcontentobject_tree;" >> "$SETVALFILE".0) || exit 1
    fi
    perl -pi -e "s/SET search_path = public, pg_catalog;//g" "$SQLFILE".0
    perl -pi -e "s/(^--.*$)|(^#.*$)//g" "$SQLFILE".0
fi

if [ $? -eq 0 ]; then
    mv "$SQLFILE" "$SQLFILE"~
    mv "$SQLFILE".0 "$SQLFILE"
    [ -z $SETVALFILE ] || mv "$SETVALFILE".0 "$SETVALFILE"
    echo "Redumped $SQLFILE using $DBNAME database"
else
    rm "$SQLFILE".0
    [ -z $SETVALFILE ] || rm "$SETVALFILE".0
    echo "Failed dumping database $DBNAME to $SQLFILE"
    exit 1
fi


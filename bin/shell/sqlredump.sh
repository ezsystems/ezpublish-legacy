#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/sqlcommon.sh

USER="root"

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
	    echo "* Postgresql only"
            echo "Example:"
            echo "$0 tmp data.sql"
}

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
	--postgresql-user=*)
	    if echo $arg | grep -e "--postgresql-user=" >/dev/null; then
		POST_USER=`echo $arg | sed 's/--postgresql-user=//'`
	    fi
	    ;;
	--schema-sql=*)
	    if echo $arg | grep -e "--schema-sql=" >/dev/null; then
		SCHEMAFILE=`echo $arg | sed 's/--schema-sql=//'`
		SCHEMAFILES="$SCHEMAFILES $SCHEMAFILE"
	    fi
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
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

if [ -z $POST_USER ]; then
    POST_USER=$USER
fi

if [ -z "$DB_USER" ]; then
    DB_USER="$POST_USER"
fi


if [ "$USE_MYSQL" != "" ]; then
    [ -n "$DB_USER" ] && USERARG="-u$DB_USER"
    [ -n "$DB_HOST" ] && HOSTARG="-h$DB_HOST"
    mysqladmin $USERARG $HOSTARG -f drop "$DBNAME"
    mysqladmin $USERARG $HOSTARG create "$DBNAME" || exit 1
    for sql in $SCHEMAFILES; do
	echo -n "Importing schema SQL file `ez_color_file $sql`"
	mysql $USERARG $HOSTARG "$DBNAME" < "$sql" &>.mysql.log
	ez_result_file $? .mysql.log || exit 1
    done
    echo -n "Importing SQL file `ez_color_file $SQLFILE`"
    mysql $USERARG $HOSTARG "$DBNAME" < "$SQLFILE" &>.mysql.log
    ez_result_file $? .mysql.log || exit 1
    for sql in $SQLFILES; do
	echo -n "Importing SQL file `ez_color_file $sql`"
	mysql $USERARG $HOSTARG "$DBNAME" < "$sql" &>.mysql.log
	ez_result_file $? .mysql.log || exit 1
    done

    if [ ! -z $USE_PAUSE ]; then
	read -p "`$SETCOLOR_EMPHASIZE`SQL dump paused, press any key to continue.`$SETCOLOR_NORMAL`" TMP
    fi

    if [[ "$SQLDUMP" != "schema" && -n $CLEAN ]]; then
	[ -n "$DB_USER" ] && DB_USER_OPT="--db-user=$DB_USER"
	[ -n "$DB_HOST" ] && DB_HOST_OPT="--db-server=$DB_HOST"
	echo -n "Flattening objects"
	./update/common/scripts/flatten.php --db-driver=ezmysql $DB_HOST_OPT --db-database=$DBNAME $DB_USER_OPT all &>.mysql.log
	ez_result_file $? .mysql.log || exit 1
	if [ $CLEAN_SEARCH ]; then
	    echo -n "Cleaning search engine"
	    ./update/common/scripts/updatesearchindex.php --db-driver=ezmysql $DB_HOST_OPT --db-database=$DBNAME $DB_USER_OPT --clean &>.mysql.log
	    ez_result_file $? .mysql.log || exit 1
	fi
	echo -n "Updating nice urls"
	./update/common/scripts/updateniceurls.php --db-driver=ezmysql $DB_HOST_OPT --db-database=$DBNAME -$DB_USER_OPT &>.mysql.log
	ez_result_file $? .mysql.log || exit 1
	echo -n "Cleaning up data"
	./update/common/scripts/cleanup.php --db-driver=ezmysql $DB_HOST_OPT --db-database=$DBNAME $DB_USER_OPT all &>.mysql.log
	ez_result_file $? .mysql.log || exit 1
    fi

    echo -n "Dumping to SQL file `ez_color_file $SQLFILE`"
# mysqldump "$USERARG" -c --quick "$NODATAARG" "$NOCREATEINFOARG" -B"$DBNAME" > "$SQLFILE".0
    if [ "$SQLDUMP" == "schema" ]; then
	mysqldump $USERARG $HOSTARG -c --quick -d "$DBNAME" | perl -pi -e "s/(^--.*$)|(^#.*$)//g" > "$SQLFILE".0 2>.mysql.log
    elif [ "$SQLDUMP" == "data" ]; then
	mysqldump $USERARG $HOSTARG -c --quick -t "$DBNAME" | perl -pi -e "s/(^--.*$)|(^#.*$)//g" > "$SQLFILE".0 2>.mysql.log
    else
	mysqldump $USERARG $HOSTARG -c --quick "$DBNAME" | perl -pi -e "s/(^--.*$)|(^#.*$)//g" > "$SQLFILE".0 2>.mysql.log
    fi
    ez_result_file $? .mysql.log || exit 1
    perl -pi -e "s/(^--.*$)|(^#.*$)//g" "$SQLFILE".0
else
    [ -n "$DB_USER" ] && USERARG="--username $DB_USER"
    [ -n "$DB_HOST" ] && HOSTARG="--host $DB_HOST"
    psql --version | grep 'psql (PostgreSQL) 7.3' &>/dev/null
    if [ $? -ne 0 ]; then
	echo "You cannot run this command on your PostgreSQL version, requires 7.3"
	exit 1
    fi
    dropdb $USERARG $HOSTARG "$DBNAME"
    createdb $USERARG $HOSTARG "$DBNAME" || exit 1
    for sql in $SCHEMAFILES; do
	echo -n "Importing schema SQL file `ez_color_file $sql`"
	psql $USERARG $HOSTARG "$DBNAME" < "$sql" &>.psql.log || exit 1
	pg_error_code ".psql.log"
	ez_result_file $? .psql.log || exit 1
	rm .psql.log
    done
    echo -n "Importing SQL file `ez_color_file $SQLFILE`"
    psql $USERARG $HOSTARG "$DBNAME" < "$SQLFILE" &>.psql.log || exit 1
    pg_error_code ".psql.log"
    ez_result_file $? .psql.log || exit 1
    rm .psql.log
    for sql in $SQLFILES; do
	echo -n "Importing SQL file `ez_color_file $sql`"
	psql $USERARG $HOSTARG "$DBNAME" < "$sql" &>.psql.log || exit 1
	pg_error_code ".psql.log"
	ez_result_file $? .psql.log || exit 1
        rm .psql.log
    done

    if [ ! -z $USE_PAUSE ]; then
	read -p "`$SETCOLOR_EMPHASIZE`SQL dump paused, press any key to continue.`$SETCOLOR_NORMAL`" TMP
    fi

    if [[ "$SQLDUMP" != "schema" && -n $CLEAN ]]; then
	[ -n "$DB_USER" ] && DB_USER_OPT="--db-user=$DB_USER"
	echo -n "Flattening objects"
	./update/common/scripts/flatten.php --db-driver=ezpostgresql --db-server=localhost --db-database=$DBNAME $DB_USER_OPT all &>.psql.log
	ez_result_file $? .psql.log || exit 1
	if [ $CLEAN_SEARCH ]; then
	    echo -n "Cleaning search engine"
	    ./update/common/scripts/updatesearchindex.php --db-driver=ezpostgresql --db-server=localhost --db-database=$DBNAME $DB_USER_OPT --clean &>.psql.log
	    ez_result_file $? .psql.log || exit 1
	fi
	echo -n "Updating nice urls"
	./update/common/scripts/updateniceurls.php --db-driver=ezpostgresql --db-server=localhost --db-database=$DBNAME $DB_USER_OPT &>.psql.log
	ez_result_file $? .psql.log || exit 1
	echo -n "Cleaning up data"
	./update/common/scripts/cleanup.php --db-driver=ezpostgresql --db-server=localhost --db-database=$DBNAME $DB_USER_OPT all &>.psql.log
	ez_result_file $? .psql.log || exit 1
    fi

    echo -n "Dumping to SQL file `ez_color_file $SQLFILE`"
    if [ "$SQLDUMP" == "schema" ]; then
	pg_dump $USERARG $HOSTARG --no-owner --inserts --schema-only "$DBNAME" > "$SQLFILE".0 2>.psql.log
    elif [ "$SQLDUMP" == "data" ]; then
	pg_dump $USERARG $HOSTARG --no-owner --inserts --data-only "$DBNAME" > "$SQLFILE".0 2>.psql.log
    else
	pg_dump $USERARG $HOSTARG --no-owner --inserts "$DBNAME" > "$SQLFILE".0 2>.psql.log
    fi
    pg_error_code ".psql.log"
    ez_result_file $? .psql.log || exit 1
    if [ -n $SETVALFILE ]; then
	(echo "select 'SELECT setval(\'' || relname || '_s\',max(id)+1) FROM ' || relname || ';' as query from pg_class where relname in (  select substring(relname FROM '^(.*)_s$') from pg_class where relname like 'ez%\_s' and  relname != 'ezcontentobject_tree_s'  and relkind='S' );" | psql "$DBNAME" -P format=unaligned -t > "$SETVALFILE".0 && echo "SELECT setval('ezcontentobject_tree_s', max(node_id)+1) FROM ezcontentobject_tree;" >> "$SETVALFILE".0) || exit 1
    fi
    perl -pi -e "s/SET search_path = public, pg_catalog;//g" "$SQLFILE".0
    perl -pi -e "s/(^--.*$)|(^#.*$)//g" "$SQLFILE".0
fi

if [ $? -eq 0 ]; then
    mv "$SQLFILE" "$SQLFILE"~
    mv "$SQLFILE".0 "$SQLFILE"
    [ -z $SETVALFILE ] || mv "$SETVALFILE".0 "$SETVALFILE"
    echo "Redumped `ez_color_file $SQLFILE` using $DBNAME database"
else
    rm "$SQLFILE".0
    [ -z $SETVALFILE ] || rm "$SETVALFILE".0
    echo "Failed dumping database $DBNAME to $SQLFILE"
    exit 1
fi


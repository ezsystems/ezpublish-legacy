<?php
/**
 * File containing the eZPostgreSQLDB class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZPostgreSQLDB ezpostgresqldb.php
  \ingroup eZDB
  \brief  The eZPostgreSQLDB class provides PostgreSQL database functions.

  eZPostgreSQLDB implementes PostgreSQLDB specific database code.

  \sa eZDB
*/
class eZPostgreSQLDB extends eZDBInterface
{
    public $SlowSQLTimeout;
    /**
     * Creates a new eZPostgreSQLDB object and connects to the database.
     *
     * @param array $parameters
     * @throws eZDBNoConnectionException
     */
    public function __construct( $parameters )
    {
        parent::__construct( $parameters );

        if ( !extension_loaded( 'pgsql' ) )
        {
            if ( function_exists( 'eZAppendWarningItem' ) )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'ezdb',
                                                              'number' => eZDBInterface::ERROR_MISSING_EXTENSION ),
                                            'text' => 'PostgreSQL extension was not found, the DB handler will not be initialized.' ) );
                $this->IsConnected = false;
            }
            eZDebug::writeWarning( 'PostgreSQL extension was not found, the DB handler will not be initialized.', __METHOD__ );
            return;
        }

        eZDebug::createAccumulatorGroup( 'postgresql_total', 'Postgresql Total' );

        $ini = eZINI::instance();

        $server = $this->Server;
        $port = $this->Port;
        $db = $this->DB;
        $user = $this->User;
        $password = $this->Password;

        $connectString = self::connectString( $this->Server, $this->Port, $this->DB, $this->User, $this->Password );

        if ( $ini->variable( "DatabaseSettings", "UsePersistentConnection" ) == "enabled" &&  function_exists( "pg_pconnect" ))
        {
            eZDebugSetting::writeDebug( 'kernel-db-postgresql', $ini->variable( "DatabaseSettings", "UsePersistentConnection" ), "using persistent connection" );

            // avoid automatic SQL errors
            $oldHandling = eZDebug::setHandleType( eZDebug::HANDLE_EXCEPTION );
            eZDebug::accumulatorStart( 'postgresql_connection', 'postgresql_total', 'Database connection'  );
            try {
                $this->DBConnection = pg_pconnect( $connectString );
            } catch( ErrorException $e ) {}
            eZDebug::accumulatorStop( 'postgresql_connection' );
            eZDebug::setHandleType( $oldHandling );

            $maxAttempts = $this->connectRetryCount();
            $waitTime = $this->connectRetryWaitTime();
            $numAttempts = 1;
            while ( $this->DBConnection == false and $numAttempts <= $maxAttempts )
            {
                sleep( $waitTime );
                $oldHandling = eZDebug::setHandleType( eZDebug::HANDLE_EXCEPTION );
                eZDebug::accumulatorStart( 'postgresql_connection', 'postgresql_total', 'Database connection'  );
                try {
                    $this->DBConnection = pg_pconnect( $connectString );
                } catch( ErrorException $e ) {}
                eZDebug::accumulatorStop( 'postgresql_connection' );
                eZDebug::setHandleType( $oldHandling );
                $numAttempts++;
            }
            if ( $this->DBConnection )
            {
                $this->IsConnected = true;
            }
            else
            {
                throw new eZDBNoConnectionException( $server, $this->ErrorMessage, $this->ErrorNumber );
            }
        }
        else if ( function_exists( "pg_connect" ) )
        {
            eZDebugSetting::writeDebug( 'kernel-db-postgresql', "using real connection",  "using real connection" );

            $oldHandling = eZDebug::setHandleType( eZDebug::HANDLE_EXCEPTION );
            eZDebug::accumulatorStart( 'postgresql_connection', 'postgresql_total', 'Database connection'  );
            try {
                $this->DBConnection = pg_connect( $connectString );
            } catch( ErrorException $e ) {}
            eZDebug::accumulatorStop( 'postgresql_connection' );
            eZDebug::setHandleType( $oldHandling );

            $maxAttempts = $this->connectRetryCount();
            $waitTime = $this->connectRetryWaitTime();
            $numAttempts = 1;
            while ( $this->DBConnection == false and $numAttempts <= $maxAttempts )
            {
                sleep( $waitTime );
                $oldHandling = eZDebug::setHandleType( eZDebug::HANDLE_EXCEPTION );
                eZDebug::accumulatorStart( 'postgresql_connection', 'postgresql_total', 'Database connection'  );
                try {
                    $this->DBConnection = pg_connect( $connectString );
                } catch( ErrorException $e ) {}
                eZDebug::accumulatorStop( 'postgresql_connection' );
                eZDebug::setHandleType( $oldHandling );
                $numAttempts++;
            }
            if ( $this->DBConnection )
            {
                $this->IsConnected = true;
            }
            else
            {
                $this->setError();
                throw new eZDBNoConnectionException( $server, $this->ErrorMessage, $this->ErrorNumber );
            }
        }
        else
        {
            $this->IsConnected = false;
            eZDebug::writeError( "PostgreSQL support not compiled into PHP, contact your system administrator", __METHOD__);

        }
    }

    public static function connectString( $server = null, $port = null, $db = null, $user = null, $password = null )
    {
        $connectParams = array();
        if ( $server !== false and $server !== null )
            $connectParams[] = "host='$server'";
        if ( $db !== false and $db !== null )
            $connectParams[] = "dbname='$db'";
        if ( $user !== false and $user !== null )
            $connectParams[] = "user='$user'";
        if ( $password !== false and $password !== null )
            $connectParams[] = "password='$password'";
        if ( $port )
            $connectParams[] = "port='$port'";

        return implode( " ", $connectParams );
    }

    function availableDatabases()
    {
        $query = "SELECT datname FROM pg_database";
        $result = $this->query( $query );

        $databases = array();
        $counter = pg_num_rows( $result ) - 1;

        while ( $counter > 0 )
        {
            $row = pg_fetch_result( $result, $counter, "datname" );
            $databases[] = $row;
            $counter--;
        }

        pg_free_result( $result );

        return $databases;
    }

    function databaseName()
    {
        return 'postgresql';
    }

    function bindingType( )
    {
        return eZDBInterface::BINDING_NO;
    }

    function bindVariable( $value, $fieldDef = false )
    {
        return $value;
    }

    function query( $sql, $server = false )
    {
        if ( $this->isConnected() )
        {
            eZDebug::accumulatorStart( 'postgresql_query', 'postgresql_total', 'Postgresql queries' );
            if ( $this->OutputSQL )
            {
                $this->startTimer();
            }
            // postgres will by default cast an error if a query fails
            // exception handling mode needs to catch this exception and set the $result variable to false
            if ( $this->errorHandling == eZDB::ERROR_HANDLING_EXCEPTIONS )
            {
                $oldHandling = eZDebug::setHandleType( eZDebug::HANDLE_EXCEPTION );
                try {
                    $result = pg_query( $this->DBConnection, $sql );
                } catch( ErrorException $e ) {
                    $result = false;
                }
                eZDebug::setHandleType( $oldHandling );
            }
            else
            {
                $result = pg_query( $this->DBConnection, $sql );
            }

            if ( $this->OutputSQL )
            {
                $this->endTimer();
                if ($this->timeTaken() > $this->SlowSQLTimeout)
                {
                    $this->reportQuery( 'eZPostgreSQLDB', $sql, false, $this->timeTaken() );
                }
            }
            eZDebug::accumulatorStop( 'postgresql_query' );

            if ( !$result )
            {
                $this->setError();
                eZDebug::writeError( "Error: error executing query: $sql: {$this->ErrorMessage}", __METHOD__ );
                if ( $this->errorHandling == eZDB::ERROR_HANDLING_EXCEPTIONS )
                {
                    throw new eZDBException( $this->ErrorMessage, $this->ErrorNumber );
                }
                $this->reportError();
            }
        }
        else
            $result = false;
        return $result;
    }


    function arrayQuery( $sql, $params = array(), $server = false )
    {
        $retArray = array();
        if ( $this->isConnected() )
        {
            $limit = -1;
            $offset = 0;
            // check for array parameters
            if ( is_array( $params ) )
            {
//                $params = $min;


                $column = false;
                if ( isset( $params["limit"] ) and is_numeric( $params["limit"] ) )
                {
                    $limit = $params["limit"];
                }

                if ( isset( $params["offset"] ) and is_numeric( $params["offset"] ) )
                {
                    $offset = $params["offset"];
                }
                if ( isset( $params["column"] ) and ( is_numeric( $params["column"] ) or is_string( $params["column"] ) ) )
                    $column = $params["column"];
            }

            if ( $limit != -1 )
            {
                $sql .= "\nLIMIT $limit";
            }
            if ( $offset > 0 )
            {
                if ( $limit == -1 )
                    $sql .= "\n";
                else
                    $sql .= " ";
                $sql .= "OFFSET $offset";
            }

            eZDebug::accumulatorStart( 'postgresql_query', 'postgresql_total', 'Postgresql queries' );

            $result = $this->query( $sql );

            eZDebug::accumulatorStop( 'postgresql_query' );

            if ( $result == false )
            {
                return false;
            }

            if ( pg_numrows( $result ) > 0 )
            {
                eZDebug::accumulatorStart( 'postgresql_loop', 'postgresql_total', 'Looping result' );
                if ( !is_string( $column ) )
                {
                    for($i = 0; $i < pg_numrows($result); $i++)
                    {
                        $retArray[$i + $offset] = pg_fetch_array( $result, $i, PGSQL_ASSOC );
                    }
                }
                else
                {
                    for ($i = 0; $i < pg_numrows( $result ); $i++ )
                    {
                        $tmp_row = pg_fetch_array( $result, $i, PGSQL_ASSOC );
                        $retArray[$i + $offset] =& $tmp_row[$column];
                    }
                }
                eZDebug::accumulatorStart( 'postgresql_loop' );
            }
            pg_free_result( $result );
        }
        return $retArray;
    }

    /*!
     \private
    */
    function subString( $string, $from, $len = null )
    {
        if ( $len == null )
        {
            return " substring( $string from $from ) ";
        }else
        {
            return " substring( $string from $from for $len ) ";
        }

    }

    function concatString( $strings = array() )
    {
        $str = implode( " || " , $strings );
        return "  $str   ";
    }

    function md5( $str )
    {
        return " encode(digest( $str, 'md5' ), 'hex' ) ";
    }

    function supportedRelationTypeMask()
    {
        return ( eZDBInterface::RELATION_TABLE_BIT |
                 eZDBInterface::RELATION_SEQUENCE_BIT |
                 eZDBInterface::RELATION_TRIGGER_BIT |
                 eZDBInterface::RELATION_VIEW_BIT |
                 eZDBInterface::RELATION_INDEX_BIT );
    }

    function supportedRelationTypes()
    {
        return array( eZDBInterface::RELATION_TABLE,
                      eZDBInterface::RELATION_SEQUENCE,
                      eZDBInterface::RELATION_TRIGGER,
                      eZDBInterface::RELATION_VIEW,
                      eZDBInterface::RELATION_INDEX );
    }

    /*!
     \private
    */
    function relationKind( $relationType )
    {
        $kind = array( eZDBInterface::RELATION_TABLE => 'r',
                       eZDBInterface::RELATION_SEQUENCE => 'S',
                       eZDBInterface::RELATION_TRIGGER => 't',
                       eZDBInterface::RELATION_VIEW => 'v',
                       eZDBInterface::RELATION_INDEX => 'i' );
        if ( !isset( $kind[$relationType] ) )
            return false;
        return $kind[$relationType];
    }

    function relationCounts( $relationMask )
    {
        $relationTypes = $this->supportedRelationTypes();
        $relationKinds = array();
        foreach ( $relationTypes as $relationType )
        {
            $relationBit = ( 1 << $relationType );
            if ( $relationMask & $relationBit )
            {
                $relationKind = $this->relationKind( $relationType );
                if ( $relationKind )
                    $relationKinds[] = $relationKind;
            }
        }
        if ( count( $relationKinds ) == 0 )
            return 0;
        $count = false;
        $relkindText = '';
        $i = 0;
        foreach ( $relationKinds as $relationKind )
        {
            if ( $i > 0 )
                $relkindText .= ' OR ';
            $relkindText .= "relkind='$relationKind'";
            $i++;
        }
        if ( $this->isConnected() )
        {
            $sql = "SELECT COUNT( relname ) as count
                    FROM pg_catalog.pg_class c
                    LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
                    WHERE ( $relkindText )
                          AND c.relname !~ '^pg_'
                          AND pg_catalog.pg_table_is_visible(c.oid)";
            $array = $this->arrayQuery( $sql, array( 'column' => 'count' ) );
            $count = $array[0];
        }
        return $count;
    }

    function relationCount( $relationType = eZDBInterface::RELATION_TABLE )
    {
        $count = false;
        $relationKind = $this->relationKind( $relationType );
        if ( !$relationKind )
        {
            eZDebug::writeError( "Unsupported relation type '$relationType'", __METHOD__ );
            return false;
        }

        if ( $this->isConnected() )
        {
            $sql = "SELECT COUNT( relname ) as count
                    FROM pg_catalog.pg_class c
                    LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
                    WHERE c.relkind = '$relationKind'
                          AND c.relname !~ '^pg_'
                          AND pg_catalog.pg_table_is_visible(c.oid)";
            $array = $this->arrayQuery( $sql, array( 'column' => 'count' ) );
            $count = $array[0];
        }
        return $count;
    }

    function relationList( $relationType = eZDBInterface::RELATION_TABLE )
    {
        $count = false;
        $relationKind = $this->relationKind( $relationType );
        if ( !$relationKind )
        {
            eZDebug::writeError( "Unsupported relation type '$relationType'", __METHOD__ );
            return false;
        }

        $array = array();
        if ( $this->isConnected() )
        {
            $sql = "SELECT relname
                    FROM pg_catalog.pg_class c
                    LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
                    WHERE c.relkind = '$relationKind'
                          AND c.relname !~ '^pg_'
                          AND pg_catalog.pg_table_is_visible( c.oid )";
            $array = $this->arrayQuery( $sql, array( 'column' => 'relname' ) );
        }
        return $array;
    }

    function eZTableList( $server = eZDBInterface::SERVER_MASTER )
    {
        $array = array();
        if ( $this->isConnected() )
        {
            foreach ( array( eZDBInterface::RELATION_TABLE, eZDBInterface::RELATION_SEQUENCE ) as $relationType )
            {
                $sql = "SELECT relname FROM pg_class WHERE relkind='" . $this->relationKind( $relationType ) . "' AND relname like 'ez%'";
                foreach ( $this->arrayQuery( $sql, array( 'column' => 'relname' ) ) as $result )
                {
                    $array[$result] = $relationType;
                }
            }
        }
        return $array;
    }

    function relationMatchRegexp( $relationType )
    {
        return "#^(ez|tmp_notification_rule_s)#";
    }

    function removeRelation( $relationName, $relationType )
    {
        $relationTypeName = $this->relationName( $relationType );
        if ( !$relationTypeName )
        {
            eZDebug::writeError( "Unsupported relation type '$relationType'", __METHOD__ );
            return false;
        }

        if ( $this->isConnected() )
        {
            $sql = "DROP $relationTypeName $relationName";
            return $this->query( $sql );
        }
        return false;
    }

    function lock( $table )
    {
        $this->begin();
        if ( $this->isConnected() )
        {
            if ( is_array( $table ) )
            {
                $lockQuery = "LOCK TABLE";
                $first = true;
                foreach( array_keys( $table ) as $tableKey )
                {
                    if ( $first == true )
                        $first = false;
                    else
                        $lockQuery .= ",";
                    $lockQuery .= " " . $table[$tableKey]['table'];
                }
                $this->query( $lockQuery );
            }
            else
            {
                $this->query( "LOCK TABLE $table" );
            }
        }
    }

    function unlock()
    {
        $this->commit();
    }

    /*!
     The query to start the transaction.
    */
    function beginQuery()
    {
        return $this->query("BEGIN WORK");
    }

    /*!
     The query to commit the transaction.
    */
    function commitQuery()
    {
        return $this->query( "COMMIT WORK" );
    }

    /*!
     The query to cancel the transaction.
    */
    function rollbackQuery()
    {
        return pg_query( $this->DBConnection, "ROLLBACK WORK" );
    }

    /**
     * Returns the last serial ID generated with an auto increment field.
     *
     * In this case that means the current value of the sequence assigned
     * <var>$table</var>
     *
     * @param string|bool $table
     * @param string|bool $column
     * @return int|bool The most recent value for the sequence
     */
    function lastSerialID( $table = false, $column = 'id' )
    {
        $id = false;

        if ( $this->isConnected() )
        {
            $sql = "SELECT currval( '" . $table . "_s')";
            $result = pg_query( $this->DBConnection, $sql );
            if ( !$result )
            {
                eZDebug::writeError( "Error: error executing query: $sql " . pg_last_error( $this->DBConnection ), "eZPostgreSQLDB" );
            }

            if ( $result )
            {
                $array = pg_fetch_row( $result, 0 );
                $id = (int)$array[0];
            }
        }

        return $id;
    }

    function setError( )
    {
        if ( $this->DBConnection )
        {

            $this->ErrorMessage = pg_last_error( $this->DBConnection );
            if ( $this->ErrorMessage != '' )
            {
                $this->ErrorNumber = 1;
            }
            else
            {
                $this->ErrorNumber = 0;
            }

        }
    }

    function escapeString( $str )
    {
        $str = str_replace("\0", '', $str);
        $str = pg_escape_string( $str );
        return $str;
    }

    function close()
    {
        pg_close( $this->DBConnection );
    }

    function createDatabase( $dbName )
    {
        if ( $this->DBConnection != false )
        {
            $this->query( "CREATE DATABASE $dbName" );
            $this->setError();
        }
    }

    function removeDatabase( $dbName )
    {
        if ( $this->DBConnection != false )
        {
            $this->query( "DROP DATABASE $dbName" );
            $this->setError();
        }
    }

    function isCharsetSupported( $charset )
    {
        return true;
    }

    function databaseServerVersion()
    {
        if ( $this->isConnected() )
        {
            $sql = "SELECT version()";
            $result = pg_query( $this->DBConnection, $sql );
            if ( !$result )
            {
                eZDebug::writeError( "Error: error executing query: $sql " . pg_last_error( $this->DBConnection ), "eZPostgreSQLDB" );
            }

            if ( $result )
            {
                $array = pg_fetch_row( $result, 0 );
                $versionText = $array[0];
            }
            list( $dbType, $versionInfo ) = explode( ' ', $versionText );
            $versionArray = explode( '.', $versionInfo );
            return array( 'string' => $versionInfo,
                          'values' => $versionArray );
        }
        return false;
    }

    /*!
     Sets PostgreSQL sequence values to the maximum values used in the corresponding columns.
    */
    function correctSequenceValues()
    {
        if ( $this->isConnected() )
        {
            $rows = $this->arrayQuery( "SELECT pg_class.relname AS table, pg_attribute.attname AS column
                FROM pg_class,pg_attribute,pg_attrdef
                WHERE pg_attrdef.adsrc LIKE 'nextval(%'
                    AND pg_attrdef.adrelid=pg_attribute.attrelid
                    AND pg_attrdef.adnum=pg_attribute.attnum
                    AND pg_attribute.attrelid=pg_class.oid" );
            foreach ( $rows as $row )
            {
                $this->query( "SELECT setval('".$row['table']."_s', max(".$row['column'].")) from ".$row['table'] );
            }
            return true;
        }
        return false;
    }

    /// \privatesection

}

?>

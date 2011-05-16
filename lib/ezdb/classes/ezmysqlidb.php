<?php
/**
 * File containing the eZMySQLiDB class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZMySQLiDB eZMySQLiDB.php
  \ingroup eZDB
  \brief The eZMySQLiDB class provides MySQL implementation of the database interface.

  eZMySQLiDB is the MySQL implementation of eZDB.
  \sa eZDB
*/

class eZMySQLiDB extends eZDBInterface
{
    const RELATION_FOREIGN_KEY = 5;
    const RELATION_FOREIGN_KEY_BIT = 32;

    /*!
      Create a new eZMySQLiDB object and connects to the database backend.
    */
    function eZMySQLiDB( $parameters )
    {
        $this->eZDBInterface( $parameters );

        if ( !extension_loaded( 'mysqli' ) )
        {
            if ( function_exists( 'eZAppendWarningItem' ) )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'ezdb',
                                                              'number' => eZDBInterface::ERROR_MISSING_EXTENSION ),
                                            'text' => 'MySQLi extension was not found, the DB handler will not be initialized.' ) );
                $this->IsConnected = false;
            }
            eZDebug::writeWarning( 'MySQLi extension was not found, the DB handler will not be initialized.', 'eZMySQLiDB' );
            return;
        }

        /// Connect to master server
        if ( !$this->DBWriteConnection )
        {
            $connection = $this->connect( $this->Server, $this->DB, $this->User, $this->Password, $this->SocketPath, $this->Charset, $this->Port );
            if ( $this->IsConnected )
            {
                $this->DBWriteConnection = $connection;
            }
        }

        // Connect to slave
        if ( !$this->DBConnection )
        {
            if ( $this->UseSlaveServer === true )
            {
                $connection = $this->connect( $this->SlaveServer, $this->SlaveDB, $this->SlaveUser, $this->SlavePassword, $this->SocketPath, $this->Charset, $this->SlavePort );
            }
            else
            {
                $connection = $this->DBWriteConnection;
            }

            if ( $connection && $this->DBWriteConnection )
            {
                $this->DBConnection = $connection;
                $this->IsConnected = true;
            }
        }

        // Initialize TempTableList
        $this->TempTableList = array();

        eZDebug::createAccumulatorGroup( 'mysqli_total', 'Mysql Total' );
    }

    /*!
     \private
     Opens a new connection to a MySQL database and returns the connection
    */
    function connect( $server, $db, $user, $password, $socketPath, $charset = null, $port = false )
    {
        $connection = false;

        if ( $socketPath !== false )
        {
            ini_set( "mysqli.default_socket", $socketPath );
        }

        if ( $this->UsePersistentConnection == true )
        {
            // Only supported on PHP 5.3 (mysqlnd)
            if ( version_compare( PHP_VERSION, '5.3' ) > 0 )
                $this->Server = 'p:' . $this->Server;
            else
                eZDebug::writeWarning( 'mysqli only supports persistent connections when using php 5.3 and higher', __METHOD__ );
        }

        $oldHandling = eZDebug::setHandleType( eZDebug::HANDLE_EXCEPTION );
        try {
            $connection = mysqli_connect( $server, $user, $password, null, (int)$port, $socketPath );
        } catch( ErrorException $e ) {}
        eZDebug::setHandleType( $oldHandling );

        $maxAttempts = $this->connectRetryCount();
        $waitTime = $this->connectRetryWaitTime();
        $numAttempts = 1;
        while ( !$connection && $numAttempts <= $maxAttempts )
        {
            sleep( $waitTime );

            $oldHandling = eZDebug::setHandleType( eZDebug::HANDLE_EXCEPTION );
            try {
                $connection = mysqli_connect( $this->Server, $this->User, $this->Password, null, (int)$this->Port, $this->SocketPath );
            } catch( ErrorException $e ) {}
            eZDebug::setHandleType( $oldHandling );

            $numAttempts++;
        }
        $this->setError();

        $this->IsConnected = true;

        if ( !$connection )
        {
            eZDebug::writeError( "Connection error: Couldn't connect to database server. Please try again later or inform the system administrator.\n{$this->ErrorMessage}", __CLASS__ );
            $this->IsConnected = false;
            throw new eZDBNoConnectionException( $server, $this->ErrorMessage, $this->ErrorNumber );
        }

        if ( $this->IsConnected && $db != null )
        {
            $ret = mysqli_select_db( $connection, $db );
            if ( !$ret )
            {
                $this->setError( $connection );
                eZDebug::writeError( "Connection error: Couldn't select the database. Please try again later or inform the system administrator.\n{$this->ErrorMessage}", __CLASS__ );
                $this->IsConnected = false;
            }
        }

        if ( $charset !== null )
        {
            $originalCharset = $charset;
            $charset = eZCharsetInfo::realCharsetCode( $charset );
        }

        if ( $this->IsConnected and $charset !== null )
        {
            $status = mysqli_set_charset( $connection, eZMySQLCharset::mapTo( $charset ) );
            if ( !$status )
            {
                $this->setError();
                eZDebug::writeWarning( "Connection warning: " . mysqli_errno( $connection ) . ": " . mysqli_error( $connection ), "eZMySQLiDB" );
            }
        }

        return $connection;
    }

    function databaseName()
    {
        return 'mysql';
    }

    function bindingType( )
    {
        return eZDBInterface::BINDING_NO;
    }

    function bindVariable( $value, $fieldDef = false )
    {
        return $value;
    }

    /*!
      Checks if the requested character set matches the one used in the database.

      \return \c true if it matches or \c false if it differs.
      \param[out] $currentCharset The charset that the database uses.
                                  will only be set if the match fails.
                                  Note: This will be specific to the database.
    */
    function checkCharset( $charset, &$currentCharset )
    {
        // If we don't have a database yet we shouldn't check it
        if ( !$this->DB )
            return true;

        $versionInfo = $this->databaseServerVersion();

        if ( is_array( $charset ) )
        {
            foreach ( $charset as $charsetItem )
                $realCharset[] = eZCharsetInfo::realCharsetCode( $charsetItem );
        }
        else
            $realCharset = eZCharsetInfo::realCharsetCode( $charset );

        return $this->checkCharsetPriv( $realCharset, $currentCharset );
    }

    /*!
     \private
    */
    function checkCharsetPriv( $charset, &$currentCharset )
    {
        $query = "SHOW CREATE DATABASE `{$this->DB}`";
        $status = mysqli_query( $this->DBConnection, $query );
        $this->reportQuery( __CLASS__, $query, false, false );
        if ( !$status )
        {
            $this->setError();
            eZDebug::writeWarning( "Connection warning: " . mysqli_errno( $this->DBConnection ) . ": " . mysqli_error( $this->DBConnection ), "eZMySQLiDB" );
            return false;
        }

        $numRows = mysqli_num_rows( $status );
        if ( $numRows == 0 )
            return false;

        for ( $i = 0; $i < $numRows; ++$i )
        {
            $tmpRow = mysqli_fetch_array( $status, MYSQLI_ASSOC );
            if ( $tmpRow['Database'] == $this->DB )
            {
                $createText = $tmpRow['Create Database'];
                if ( preg_match( '#DEFAULT CHARACTER SET ([a-zA-Z0-9_-]+)#', $createText, $matches ) )
                {
                    $currentCharset = $matches[1];
                    $currentCharset = eZCharsetInfo::realCharsetCode( $currentCharset );
                    // Convert charset names into something MySQL will understand
                    $unmappedCurrentCharset = eZMySQLCharset::mapFrom( $currentCharset );

                    if ( is_array( $charset ) )
                    {
                        if ( in_array( $unmappedCurrentCharset, $charset ) )
                        {
                            return $unmappedCurrentCharset;
                        }
                    }
                    else if ( $unmappedCurrentCharset == $charset )
                    {
                        return true;
                    }
                    return false;
                }
                break;
            }
        }
        return true;
    }

    function query( $sql, $server = false )
    {
        if ( $this->IsConnected )
        {
            eZDebug::accumulatorStart( 'mysqli_query', 'mysqli_total', 'Mysqli_queries' );
            $orig_sql = $sql;
            // The converted sql should not be output
            if ( $this->InputTextCodec )
            {
                eZDebug::accumulatorStart( 'mysqli_conversion', 'mysqli_total', 'String conversion in mysqli' );
                $sql = $this->InputTextCodec->convertString( $sql );
                eZDebug::accumulatorStop( 'mysqli_conversion' );
            }

            if ( $this->OutputSQL )
            {
                $this->startTimer();
            }

            $sql = trim( $sql );

            // Check if we need to use the master or slave server by default
            if ( $server === false )
            {
                $server = strncasecmp( $sql, 'select', 6 ) === 0 && $this->TransactionCounter == 0 ?
                    eZDBInterface::SERVER_SLAVE : eZDBInterface::SERVER_MASTER;
            }

            $connection = ( $server == eZDBInterface::SERVER_SLAVE ) ? $this->DBConnection : $this->DBWriteConnection;

            $analysisText = false;
            // If query analysis is enable we need to run the query
            // with an EXPLAIN in front of it
            // Then we build a human-readable table out of the result
            if ( $this->QueryAnalysisOutput )
            {
                $analysisResult = mysqli_query( $connection, 'EXPLAIN ' . $sql );
                if ( $analysisResult )
                {
                    $numRows = mysqli_num_rows( $analysisResult );
                    $rows = array();
                    if ( $numRows > 0 )
                    {
                        for ( $i = 0; $i < $numRows; ++$i )
                        {
                            if ( $this->InputTextCodec )
                            {
                                $tmpRow = mysqli_fetch_array( $analysisResult, MYSQLI_ASSOC );
                                $convRow = array();
                                foreach( $tmpRow as $key => $row )
                                {
                                    $convRow[$key] = $this->OutputTextCodec->convertString( $row );
                                }
                                $rows[$i] = $convRow;
                            }
                            else
                                $rows[$i] = mysqli_fetch_array( $analysisResult, MYSQLI_ASSOC );
                        }
                    }

                    // Figure out all columns and their maximum display size
                    $columns = array();
                    foreach ( $rows as $row )
                    {
                        foreach ( $row as $col => $data )
                        {
                            if ( !isset( $columns[$col] ) )
                                $columns[$col] = array( 'name' => $col,
                                                        'size' => strlen( $col ) );
                            $columns[$col]['size'] = max( $columns[$col]['size'], strlen( $data ) );
                        }
                    }

                    $analysisText = '';
                    $delimiterLine = array();
                    // Generate the column line and the vertical delimiter
                    // The look of the table is taken from the MySQL CLI client
                    // It looks like this:
                    // +-------+-------+
                    // | col_a | col_b |
                    // +-------+-------+
                    // | txt   |    42 |
                    // +-------+-------+
                    foreach ( $columns as $col )
                    {
                        $delimiterLine[] = str_repeat( '-', $col['size'] + 2 );
                        $colLine[] = ' ' . str_pad( $col['name'], $col['size'], ' ', STR_PAD_RIGHT ) . ' ';
                    }
                    $delimiterLine = '+' . join( '+', $delimiterLine ) . "+\n";
                    $analysisText = $delimiterLine;
                    $analysisText .= '|' . join( '|', $colLine ) . "|\n";
                    $analysisText .= $delimiterLine;

                    // Go trough all data and pad them to create the table correctly
                    foreach ( $rows as $row )
                    {
                        $rowLine = array();
                        foreach ( $columns as $col )
                        {
                            $name = $col['name'];
                            $size = $col['size'];
                            $data = isset( $row[$name] ) ? $row[$name] : '';
                            // Align numerical values to the right (ie. pad left)
                            $rowLine[] = ' ' . str_pad( $row[$name], $size, ' ',
                                                        is_numeric( $row[$name] ) ? STR_PAD_LEFT : STR_PAD_RIGHT ) . ' ';
                        }
                        $analysisText .= '|' . join( '|', $rowLine ) . "|\n";
                        $analysisText .= $delimiterLine;
                    }

                    // Reduce memory usage
                    unset( $rows, $delimiterLine, $colLine, $columns );
                }
            }

            $result = mysqli_query( $connection, $sql );

            if ( $this->RecordError and !$result )
                $this->setError();

            if ( $this->OutputSQL )
            {
                $this->endTimer();

                if ($this->timeTaken() > $this->SlowSQLTimeout)
                {
                    $num_rows = mysqli_affected_rows( $connection );
                    $text = $sql;

                    // If we have some analysis text we append this to the SQL output
                    if ( $analysisText !== false )
                        $text = "EXPLAIN\n" . $text . "\n\nANALYSIS:\n" . $analysisText;

                    $this->reportQuery( __CLASS__ . '[' . $connection->host_info . ( $server == eZDBInterface::SERVER_MASTER ? ', on master' : '' ) . ']', $text, $num_rows, $this->timeTaken() );
                }
            }
            eZDebug::accumulatorStop( 'mysqli_query' );
            if ( $result )
            {
                return $result;
            }
            else
            {
                $errorMessage = 'Query error (' . mysqli_errno( $connection ) . '): ' . mysqli_error( $connection ) . '. Query: ' . $sql;
                eZDebug::writeError( $errorMessage, __CLASS__  );
                $oldRecordError = $this->RecordError;
                // Turn off error handling while we unlock
                $this->RecordError = false;
                mysqli_query( $connection, 'UNLOCK TABLES' );
                $this->RecordError = $oldRecordError;

                $this->reportError();

                // This is to behave the same way as other RDBMS PHP API as PostgreSQL
                // functions which throws an error with a failing request.
                if ( $this->errorHandling == eZDB::ERROR_HANDLING_STANDARD )
                {
                    trigger_error( "mysqli_query(): $errorMessage", E_USER_ERROR );
                }
                else
                {
                    throw new eZDBException( $this->ErrorMessage, $this->ErrorNumber );
                }

                return false;
            }
        }
        else
        {
            eZDebug::writeError( "Trying to do a query without being connected to a database!", __CLASS__ );
        }


    }

    function arrayQuery( $sql, $params = array(), $server = false )
    {
        $retArray = array();
        if ( $this->IsConnected )
        {
            $limit = false;
            $offset = 0;
            $column = false;
            // check for array parameters
            if ( is_array( $params ) )
            {
                if ( isset( $params["limit"] ) and is_numeric( $params["limit"] ) )
                    $limit = $params["limit"];

                if ( isset( $params["offset"] ) and is_numeric( $params["offset"] ) )
                    $offset = $params["offset"];

                if ( isset( $params["column"] ) and ( is_numeric( $params["column"] ) or is_string( $params["column"] ) ) )
                    $column = $params["column"];
            }

            if ( $limit !== false and is_numeric( $limit ) )
            {
                $sql .= "\nLIMIT $offset, $limit ";
            }
            else if ( $offset !== false and is_numeric( $offset ) and $offset > 0 )
            {
                $sql .= "\nLIMIT $offset, 18446744073709551615"; // 2^64-1
            }
            $result = $this->query( $sql, $server );

            if ( $result == false )
            {
                $this->reportQuery( __CLASS__, $sql, false, false );
                return false;
            }

            $numRows = mysqli_num_rows( $result );
            if ( $numRows > 0 )
            {
                if ( !is_string( $column ) )
                {
                    eZDebug::accumulatorStart( 'mysqli_loop', 'mysqli_total', 'Looping result' );
                    for ( $i=0; $i < $numRows; $i++ )
                    {
                        if ( $this->InputTextCodec )
                        {
                            $tmpRow = mysqli_fetch_array( $result, MYSQLI_ASSOC );
                            $convRow = array();
                            foreach( $tmpRow as $key => $row )
                            {
                                eZDebug::accumulatorStart( 'mysqli_conversion', 'mysqli_total', 'String conversion in mysqli' );
                                $convRow[$key] = $this->OutputTextCodec->convertString( $row );
                                eZDebug::accumulatorStop( 'mysqli_conversion' );
                            }
                            $retArray[$i + $offset] = $convRow;
                        }
                        else
                            $retArray[$i + $offset] = mysqli_fetch_array( $result, MYSQLI_ASSOC );
                    }
                    eZDebug::accumulatorStop( 'mysqli_loop' );

                }
                else
                {
                    eZDebug::accumulatorStart( 'mysqli_loop', 'mysqli_total', 'Looping result' );
                    for ( $i=0; $i < $numRows; $i++ )
                    {
                        $tmp_row = mysqli_fetch_array( $result, MYSQLI_ASSOC );
                        if ( $this->InputTextCodec )
                        {
                            eZDebug::accumulatorStart( 'mysqli_conversion', 'mysqli_total', 'String conversion in mysqli' );
                            $retArray[$i + $offset] = $this->OutputTextCodec->convertString( $tmp_row[$column] );
                            eZDebug::accumulatorStop( 'mysqli_conversion' );
                        }
                        else
                            $retArray[$i + $offset] =& $tmp_row[$column];
                    }
                    eZDebug::accumulatorStop( 'mysqli_loop' );
                }
            }
        }
        return $retArray;
    }

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
        $str = implode( "," , $strings );
        return " concat( $str  ) ";
    }

    function md5( $str )
    {
        return " MD5( $str ) ";
    }

    function bitAnd( $arg1, $arg2 )
    {
        return 'cast(' . $arg1 . ' & ' . $arg2 . ' AS SIGNED ) ';
    }

    function bitOr( $arg1, $arg2 )
    {
        return 'cast( ' . $arg1 . ' | ' . $arg2 . ' AS SIGNED ) ';
    }

    function supportedRelationTypeMask()
    {
        return eZDBInterface::RELATION_TABLE_BIT | self::RELATION_FOREIGN_KEY_BIT;
    }

    function supportedRelationTypes()
    {
        return array( self::RELATION_FOREIGN_KEY, eZDBInterface::RELATION_TABLE );
    }

    function relationCounts( $relationMask )
    {
        if ( $relationMask & eZDBInterface::RELATION_TABLE_BIT )
            return $this->relationCount();
        else
            return 0;
    }

    function relationCount( $relationType = eZDBInterface::RELATION_TABLE )
    {
        if ( !in_array( $relationType, $this->supportedRelationTypes() ) )
        {
            eZDebug::writeError( "Unsupported relation type '$relationType'", __METHOD__ );
            return false;
        }
        $count = false;
        if ( $this->IsConnected )
        {
            switch ( $relationType )
            {
                case eZDBInterface::RELATION_TABLE:
                {
                    $query = 'SHOW TABLES from `' . $this->DB .'`';
                    $result = mysqli_query( $this->DBConnection, $query );
                    $this->reportQuery( __CLASS__, $query, false, false );
                    $count = mysqli_num_rows( $result );
                    mysqli_free_result( $result );
                } break;

                case self::RELATION_FOREIGN_KEY:
                {
                    $count = count( $this->relationList( self::RELATION_FOREIGN_KEY ) );
                } break;
            }
        }
        return $count;
    }

    function relationList( $relationType = eZDBInterface::RELATION_TABLE )
    {
        if ( !in_array( $relationType, $this->supportedRelationTypes() ) )
        {
            eZDebug::writeError( "Unsupported relation type '$relationType'", __METHOD__ );
            return false;
        }
        if ( $this->IsConnected )
        {
            switch ( $relationType )
            {
                case eZDBInterface::RELATION_TABLE:
                {
                    $tables = array();
                    $query = 'SHOW TABLES from `' . $this->DB .'`';
                    $result = mysqli_query( $this->DBConnection, $query );
                    $this->reportQuery( __CLASS__, $query, false, false );
                    while( $row = mysqli_fetch_row( $result ) )
                    {
                        $tables[] = $row[0];
                    }
                    mysqli_free_result( $result );
                    return $tables;
                } break;

                case self::RELATION_FOREIGN_KEY:
                {
                    /**
                     * Ideally, we would have queried information_schema.KEY_COLUMN_USAGE
                     * However, a known bug causes queries on this table to potentially be VERY slow (http://bugs.mysql.com/bug.php?id=19588)
                     *
                     * The query would look like this:
                     * SELECT table_name AS from_table, column_name AS from_column, referenced_table_name AS to_table,
                     *        referenced_column_name AS to_column
                     * FROM information_schema.KEY_COLUMN_USAGE
                     * WHERE REFERENCED_TABLE_SCHEMA = '{$this->DB}'
                     *   AND REFERENCED_TABLE_NAME is not null;
                     *
                     * Result as of MySQL 5.1.48 / August 2010:
                     *
                     * +---------------+-------------+----------+-----------+
                     * | from_table    | from_column | to_table | to_column |
                     * +---------------+-------------+----------+-----------+
                     * | ezdbfile_data | name_hash   | ezdbfile | name_hash |
                     * +---------------+-------------+----------+-----------+
                     * 1 row in set (12.56 sec)
                     *
                     * The only way out right now is to parse SHOW CREATE TABLE for each table and extract CONSTRAINT lines
                     */

                    $foreignKeys = array();
                    foreach( $this->relationList( eZDBInterface::RELATION_TABLE ) as $table )
                    {
                        $query = "SHOW CREATE TABLE $table";
                        $result = mysqli_query( $this->DBConnection, $query );
                        $this->reportQuery( __CLASS__, $query, false, false );
                        if ( mysqli_num_rows( $result ) === 1 )
                        {
                            $row = mysqli_fetch_row( $result );
                            if ( strpos( $row[1], "CONSTRAINT" ) !== false )
                            {
                                if ( preg_match_all( '#CONSTRAINT [`"]([^`"]+)[`"] FOREIGN KEY \([`"].*[`"]\) REFERENCES [`"]([^`"]+)[`"] \([`"].*[`"]\)#', $row[1], $matches, PREG_PATTERN_ORDER ) )
                                {
                                    // $foreignKeys[] = array( 'table' => $table, 'keys' => $matches[1] );
                                    foreach( $matches[1] as $fkMatch )
                                    {
                                        $foreignKeys[] = array( 'table' => $table, 'fk' => $fkMatch );
                                    }
                                }
                            }
                        }
                    }
                    return $foreignKeys;
                }
            }
        }
    }

    function eZTableList( $server = eZDBInterface::SERVER_MASTER )
    {
        $tables = array();
        if ( $this->IsConnected )
        {
            if ( $this->UseSlaveServer && $server == eZDBInterface::SERVER_SLAVE )
            {
                $connection = $this->DBConnection;
                $db = $this->SlaveDB;
            }
            else
            {
                $connection = $this->DBWriteConnection;
                $db = $this->DB;
            }

            $query = 'SHOW TABLES from `' . $db .'`';
            $result = mysqli_query( $connection, $query );
            $this->reportQuery( __CLASS__, $query, false, false );
            while( $row = mysqli_fetch_row( $result ) )
            {
                $tableName = $row[0];
                if ( substr( $tableName, 0, 2 ) == 'ez' )
                {
                    $tables[$tableName] = eZDBInterface::RELATION_TABLE;
                }
            }
            mysqli_free_result( $result );
        }
        return $tables;
    }

    function relationMatchRegexp( $relationType )
    {
        return "#^ez#";
    }

    function removeRelation( $relationName, $relationType )
    {
        $relationTypeName = $this->relationName( $relationType );
        if ( !$relationTypeName )
        {
            eZDebug::writeError( "Unknown relation type '$relationType'", __METHOD__ );
            return false;
        }

        if ( $this->IsConnected )
        {
            switch ( $relationType )
            {
                case self::RELATION_FOREIGN_KEY:
                {
                    $sql = "ALTER TABLE {$relationName['table']} DROP FOREIGN KEY {$relationName['fk']}";
                    $this->query( $sql );
                    return true;
                } break;

                default:
                {
                    $sql = "DROP $relationTypeName $relationName";
                    return $this->query( $sql );
                }
            }
        }
        return false;
    }

    /**
     * Local eZDBInterface::relationName() override to support the foreign keys type relation
     * @param $relationType
     * @return string|false
     */
    public function relationName( $relationType )
    {
        if ( $relationType == self::RELATION_FOREIGN_KEY )
            return 'FOREIGN_KEY';
        else
            return parent::relationName( $relationType );
    }

    function lock( $table )
    {
        if ( $this->IsConnected )
        {
            if ( is_array( $table ) )
            {
                $lockQuery = "LOCK TABLES";
                $first = true;
                foreach( array_keys( $table ) as $tableKey )
                {
                    if ( $first == true )
                        $first = false;
                    else
                        $lockQuery .= ",";
                    $lockQuery .= " " . $table[$tableKey]['table'] . " WRITE";
                }
                $this->query( $lockQuery );
            }
            else
            {
                $this->query( "LOCK TABLES $table WRITE" );
            }
        }
    }

    function unlock()
    {
        if ( $this->IsConnected )
        {
            $this->query( "UNLOCK TABLES" );
        }
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
        return $this->query( "COMMIT" );
    }

    /*!
     The query to cancel the transaction.
    */
    function rollbackQuery()
    {
        return $this->query( "ROLLBACK" );
    }

    function lastSerialID( $table = false, $column = false )
    {
        if ( $this->IsConnected )
        {
            $id = mysqli_insert_id( $this->DBWriteConnection );
            return $id;
        }
        else
            return false;
    }

    function escapeString( $str )
    {
        if ( $this->IsConnected )
        {
            return mysqli_real_escape_string( $this->DBConnection, $str );
        }
        else
        {
            eZDebug::writeDebug( 'escapeString called before connection is made', __METHOD__ );
            return $str;
        }
    }

    function close()
    {
        if ( $this->IsConnected )
        {
            if ( $this->UseSlaveServer === true )
                mysqli_close( $this->DBConnection );
            mysqli_close( $this->DBWriteConnection );
        }
    }

    function createDatabase( $dbName )
    {
        if ( $this->IsConnected )
        {
            $this->query( "CREATE DATABASE $dbName" );
            $this->setError();
        }
    }

    function removeDatabase( $dbName )
    {
        if ( $this->IsConnected )
        {
            $this->query( "DROP DATABASE $dbName" );
            $this->setError();
        }
    }

    /**
     * Sets the internal error messages & number
     * @param MySQLi $connection database connection handle, overrides the current one if given
     */
    function setError( $connection = false )
    {
        if ( $this->IsConnected )
        {
            if ( $connection === false )
                $connection = $this->DBConnection;

            if ( $connection instanceof MySQLi )
            {
                $this->ErrorMessage = mysqli_error( $connection );
                $this->ErrorNumber = mysqli_errno( $connection );
            }
        }
        else
        {
            $this->ErrorMessage = mysqli_connect_error();
            $this->ErrorNumber = mysqli_connect_errno();
        }
    }

    function availableDatabases()
    {
        $databaseArray = mysqli_query( $this->DBConnection, 'SHOW DATABASES' );

        if ( $this->errorNumber() != 0 )
        {
            return null;
        }

        $databases = array();

        $numRows = mysqli_num_rows( $databaseArray );
        if ( count( $numRows ) == 0 )
        {
            return false;
        }

        while ( $row = mysqli_fetch_row( $databaseArray ) )
        {
            // we don't allow "mysql" or "information_schema" database to be shown anywhere
            $curDB = $row[0];
            if ( strcasecmp( $curDB, 'mysql' ) != 0 && strcasecmp( $curDB, 'information_schema' ) != 0 )
            {
                $databases[] = $curDB;
            }
        }
        return $databases;
    }

    function databaseServerVersion()
    {
        if ( $this->IsConnected )
        {
            $versionInfo = mysqli_get_server_info( $this->DBConnection );

            $versionArray = explode( '.', $versionInfo );

            return array( 'string' => $versionInfo,
                          'values' => $versionArray );
        }

        return false;
    }

    function databaseClientVersion()
    {
        $versionInfo = mysqli_get_client_info();

        $versionArray = explode( '.', $versionInfo );

        return array( 'string' => $versionInfo,
                      'values' => $versionArray );
    }

    function isCharsetSupported( $charset )
    {
        return true;
    }

    function supportsDefaultValuesInsertion()
    {
        return false;
    }

    function dropTempTable( $dropTableQuery = '', $server = self::SERVER_SLAVE )
    {
        $dropTableQuery = str_ireplace( 'DROP TABLE', 'DROP TEMPORARY TABLE', $dropTableQuery );
        parent::dropTempTable( $dropTableQuery, $server );
    }

    protected $TempTableList;

    /// \privatesection
}

?>

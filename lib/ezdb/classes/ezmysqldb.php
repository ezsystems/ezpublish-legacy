<?php
/**
 * File containing the eZMySQLDB class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/**
 * The eZMySQLDB class provides MySQL implementation of the database interface.
 *
 * eZMySQLDB is the MySQL implementation of eZDB.
 * @see eZDB
 * @deprecated Since 4.5 in favour of {@link eZMySQLiDB}
*/
class eZMySQLDB extends eZDBInterface
{
    const RELATION_FOREIGN_KEY = 5;
    const RELATION_FOREIGN_KEY_BIT = 32;

    /*!
      Create a new eZMySQLDB object and connects to the database backend.
    */
    function eZMySQLDB( $parameters )
    {
        $this->eZDBInterface( $parameters );

        if ( !extension_loaded( 'mysql' ) )
        {
            if ( function_exists( 'eZAppendWarningItem' ) )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'ezdb',
                                                              'number' => eZDBInterface::ERROR_MISSING_EXTENSION ),
                                            'text' => 'MySQL extension was not found, the DB handler will not be initialized.' ) );
                $this->IsConnected = false;
            }
            eZDebug::writeWarning( 'MySQL extension was not found, the DB handler will not be initialized.', 'eZMySQLDB' );
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

            if ( $connection and $this->DBWriteConnection )
            {
                $this->DBConnection = $connection;
                $this->IsConnected = true;
            }
        }

        // Initialize TempTableList
        $this->TempTableList = array();

        eZDebug::createAccumulatorGroup( 'mysql_total', 'Mysql Total' );
    }

    /*!
     \private
     Opens a new connection to a MySQL database and returns the connection
    */
    function connect( $server, $db, $user, $password, $socketPath, $charset = null, $port = false )
    {
        // if a port is specified, we add it to $server, this is how mysql_(p)connect accepts a port number
        if ( $port )
        {
            $server .= ':' . $port;
        }

        $connection = false;

        if ( $socketPath !== false )
        {
            ini_set( "mysql.default_socket", $socketPath );
        }

        $oldHandling = eZDebug::setHandleType( eZDebug::HANDLE_EXCEPTION );
        try {
            if ( $this->UsePersistentConnection == true )
            {
                $connection = mysql_pconnect( $server, $user, $password );
            }
            else
            {
                $connection = mysql_connect( $server, $user, $password, true );
            }
        } catch( ErrorException $e ) {}
        eZDebug::setHandleType( $oldHandling );

        $this->setError();
        $maxAttempts = $this->connectRetryCount();
        $waitTime = $this->connectRetryWaitTime();
        $numAttempts = 1;
        while ( !$connection && $numAttempts <= $maxAttempts )
        {
            sleep( $waitTime );
            $oldHandling = eZDebug::setHandleType( eZDebug::HANDLE_EXCEPTION );
            try {
                if ( $this->UsePersistentConnection == true )
                {
                    $connection = mysql_pconnect( $this->Server, $this->User, $this->Password );
                }
                else
                {
                    $connection = mysql_connect( $this->Server, $this->User, $this->Password );
                }
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
            $ret = mysql_select_db( $db, $connection );
            if ( !$ret )
            {
                $this->setError( $connection );
                eZDebug::writeError( "Connection error: Couldn't select the database. Please try again later or inform the system administrator.\n{$this->ErrorMessage}", __CLASS__ );
                $this->IsConnected = false;
            }
        }

        if ( $charset !== null )
        {
            $charset = eZCharsetInfo::realCharsetCode( $charset );
        }

        if ( $this->IsConnected and $charset !== null and $this->isCharsetSupported( $charset ) )
        {
            $query = "SET NAMES '" . eZMySQLCharset::mapTo( $charset ) . "'";
            $status = mysql_query( $query, $connection );
            $this->reportQuery( 'eZMySQLDB', $query, false, false, true );
            if ( !$status )
            {
                $this->setError();
                eZDebug::writeWarning( "Connection warning: " . mysql_errno( $connection ) . ": " . mysql_error( $connection ), "eZMySQLDB" );
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

      \note There will be no check for databases using MySQL 4.1.0 or lower since
            they do not have proper character set handling.
    */
    function checkCharset( $charset, &$currentCharset )
    {
        // If we don't have a database yet we shouldn't check it
        if ( !$this->DB )
            return true;

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
        $status = mysql_query( $query, $this->DBConnection );
        $this->reportQuery( __CLASS__, $query, false, false );
        if ( !$status )
        {
            $this->setError();
            eZDebug::writeWarning( "Connection warning: " . mysql_errno( $this->DBConnection ) . ": " . mysql_error( $this->DBConnection ), "eZMySQLDB" );
            return false;
        }

        $numRows = mysql_num_rows( $status );
        if ( $numRows == 0 )
            return false;

        for ( $i = 0; $i < $numRows; ++$i )
        {
            $tmpRow = mysql_fetch_array( $status, MYSQL_ASSOC );
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
            eZDebug::accumulatorStart( 'mysql_query', 'mysql_total', 'Mysql_queries' );
            // The converted sql should not be output
            if ( $this->InputTextCodec )
            {
                eZDebug::accumulatorStart( 'mysql_conversion', 'mysql_total', 'String conversion in mysql' );
                $sql = $this->InputTextCodec->convertString( $sql );
                eZDebug::accumulatorStop( 'mysql_conversion' );
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
                $analysisResult = mysql_query( 'EXPLAIN ' . $sql, $connection );
                if ( $analysisResult )
                {
                    $numRows = mysql_num_rows( $analysisResult );
                    $rows = array();
                    if ( $numRows > 0 )
                    {
                        for ( $i = 0; $i < $numRows; ++$i )
                        {
                            if ( $this->InputTextCodec )
                            {
                                $tmpRow = mysql_fetch_array( $analysisResult, MYSQL_ASSOC );
                                $convRow = array();
                                foreach( $tmpRow as $key => $row )
                                {
                                    $convRow[$key] = $this->OutputTextCodec->convertString( $row );
                                }
                                $rows[$i] = $convRow;
                            }
                            else
                                $rows[$i] = mysql_fetch_array( $analysisResult, MYSQL_ASSOC );
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

            $result = mysql_query( $sql, $connection );

            if ( $this->RecordError and !$result )
                $this->setError();

            if ( $this->OutputSQL )
            {
                $this->endTimer();

                if ($this->timeTaken() > $this->SlowSQLTimeout)
                {
                    $num_rows = mysql_affected_rows( $connection );
                    $text = $sql;

                    // If we have some analysis text we append this to the SQL output
                    if ( $analysisText !== false )
                        $text = "EXPLAIN\n" . $text . "\n\nANALYSIS:\n" . $analysisText;

                    $this->reportQuery( __CLASS__ . ( $server == eZDBInterface::SERVER_MASTER ? '[on master]' : '' ), $text, $num_rows, $this->timeTaken() );
                }
            }
            eZDebug::accumulatorStop( 'mysql_query' );
            if ( $result )
            {
                return $result;
            }
            else
            {
                $errorMessage = "Query error: " . mysql_error( $connection ) . ". Query: ". $sql;
                eZDebug::writeError( $errorMessage, __CLASS__  );
                $oldRecordError = $this->RecordError;
                // Turn off error handling while we unlock
                $this->RecordError = false;
                mysql_query( 'UNLOCK TABLES', $connection );
                $this->RecordError = $oldRecordError;

                $this->reportError();

                // This is to behave the same way as other RDBMS PHP API as PostgreSQL
                // functions which throws an error with a failing request.
                if ( $this->errorHandling == eZDB::ERROR_HANDLING_STANDARD )
                {
                    trigger_error( "mysql_query(): $errorMessage", E_USER_ERROR );
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

            $numRows = mysql_num_rows( $result );
            if ( $numRows > 0 )
            {
                if ( !is_string( $column ) )
                {
                    eZDebug::accumulatorStart( 'mysql_loop', 'mysql_total', 'Looping result' );
                    for ( $i=0; $i < $numRows; $i++ )
                    {
                        if ( $this->InputTextCodec )
                        {
                            $tmpRow = mysql_fetch_array( $result, MYSQL_ASSOC );
                            $convRow = array();
                            foreach( $tmpRow as $key => $row )
                            {
                                eZDebug::accumulatorStart( 'mysql_conversion', 'mysql_total', 'String conversion in mysql' );
                                $convRow[$key] = $this->OutputTextCodec->convertString( $row );
                                eZDebug::accumulatorStop( 'mysql_conversion' );
                            }
                            $retArray[$i + $offset] = $convRow;
                        }
                        else
                            $retArray[$i + $offset] = mysql_fetch_array( $result, MYSQL_ASSOC );
                    }
                    eZDebug::accumulatorStop( 'mysql_loop' );

                }
                else
                {
                    eZDebug::accumulatorStart( 'mysql_loop', 'mysql_total', 'Looping result' );
                    for ( $i=0; $i < $numRows; $i++ )
                    {
                        $tmp_row = mysql_fetch_array( $result, MYSQL_ASSOC );
                        if ( $this->InputTextCodec )
                        {
                            eZDebug::accumulatorStart( 'mysql_conversion', 'mysql_total', 'String conversion in mysql' );
                            $retArray[$i + $offset] = $this->OutputTextCodec->convertString( $tmp_row[$column] );
                            eZDebug::accumulatorStop( 'mysql_conversion' );
                        }
                        else
                            $retArray[$i + $offset] =& $tmp_row[$column];
                    }
                    eZDebug::accumulatorStop( 'mysql_loop' );
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
                    $query = "SHOW TABLES FROM `" . $this->DB . "`";
                    $result = @mysql_query( $query, $this->DBConnection );
                    $this->reportQuery( __CLASS__, $query, false, false );
                    $count = mysql_num_rows( $result );
                    mysql_free_result( $result );
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
        $tables = array();
        if ( $this->IsConnected )
        {
            switch ( $relationType )
            {
                case eZDBInterface::RELATION_TABLE:
                {
                    $query = "SHOW TABLES FROM `" . $this->DB . "`";
                    $result = @mysql_query( $query, $this->DBConnection );
                    $this->reportQuery( __CLASS__, $query, false, false );
                    $count = mysql_num_rows( $result );
                    for ( $i = 0; $i < $count; ++ $i )
                    {
                        $table = mysql_fetch_array( $result );
                        $tables[] = $table[0];
                    }
                    mysql_free_result( $result );
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
                        $result = mysql_query( $query, $this->DBConnection );
                        $this->reportQuery( __CLASS__, $query, false, false );
                        if ( mysql_num_rows( $result ) === 1 )
                        {
                            $row = mysql_fetch_row( $result );
                            if ( strpos( $row[1], "CONSTRAINT" ) !== false )
                            {
                                if ( preg_match_all( '#CONSTRAINT [`"]([^`"]+)[`"] FOREIGN KEY \([`"].*[`"]\) REFERENCES [`"]([^`"]+)[`"] \([`"].*[`"]\)#',
                                    $row[1], $matches, PREG_PATTERN_ORDER ) )
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

            $query = "SHOW TABLES FROM `" . $db . "`";
            $result = @mysql_query( $query, $connection );
            $this->reportQuery( __CLASS__, $query, false, false );
            $count = mysql_num_rows( $result );
            for ( $i = 0; $i < $count; ++ $i )
            {
                $table = mysql_fetch_array( $result );
                $tableName = $table[0];
                if ( substr( $tableName, 0, 2 ) == 'ez' )
                {
                    $tables[$tableName] = eZDBInterface::RELATION_TABLE;
                }
            }
            mysql_free_result( $result );
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
            $id = mysql_insert_id( $this->DBWriteConnection );
            return $id;
        }
        else
            return false;
    }

    function escapeString( $str )
    {
        if ( $this->IsConnected )
        {
            return mysql_real_escape_string( $str, $this->DBConnection );
        }
        else
        {
            return mysql_escape_string( $str );
        }
    }

    function close()
    {
        if ( $this->IsConnected )
        {
            if ( $this->UseSlaveServer === true )
                mysql_close( $this->DBConnection );
            mysql_close( $this->DBWriteConnection );
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
     * @param int $connection database connection handle, overrides the current one if given
     */
    function setError( $connection = false)
    {
        if ( $this->IsConnected )
        {
            if ( $connection === false )
                $connection = $this->DBConnection;

            if ( is_resource( $connection ) )
            {
                $this->ErrorMessage = mysql_error( $connection );
                $this->ErrorNumber = mysql_errno( $connection );
            }
        }
        else
        {
            $this->ErrorMessage = mysql_error();
            $this->ErrorNumber = mysql_errno();
        }
    }

    function availableDatabases()
    {
        $databaseArray = mysql_list_dbs( $this->DBConnection );

        if ( $this->errorNumber() != 0 )
        {
            return null;
        }

        $databases = array();
        $i = 0;
        $numRows = mysql_num_rows( $databaseArray );
        if ( count( $numRows ) == 0 )
        {
            return false;
        }

        $dbServerVersion = $this->databaseServerVersion();
        $dbServerMainVersion = $dbServerVersion['values'][0];

        while ( $i < $numRows )
        {
            // we don't allow "mysql" or "information_schema" database to be shown anywhere
            $curDB = mysql_db_name( $databaseArray, $i );
            if ( strcasecmp( $curDB, 'mysql' ) != 0 && strcasecmp( $curDB, 'information_schema' ) != 0 )
            {
                $databases[] = $curDB;
            }
            ++$i;
        }
        return $databases;
    }

    function databaseServerVersion()
    {
        if ( $this->IsConnected )
        {
            $versionInfo = mysql_get_server_info();

            $versionArray = explode( '.', $versionInfo );

            return array( 'string' => $versionInfo,
                          'values' => $versionArray );
        }
        return false;
    }

    function databaseClientVersion()
    {
        $versionInfo = mysql_get_client_info();

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

    public $TempTableList;

    /// \privatesection
}

?>

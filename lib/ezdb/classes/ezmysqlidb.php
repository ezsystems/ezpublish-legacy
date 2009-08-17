<?php
//
// $Id$
//
// Definition of eZMySQLiDB class
//
// Created on: <12-Feb-2002 15:54:17 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*!
  \class eZMySQLiDB eZMySQLiDB.php
  \ingroup eZDB
  \brief The eZMySQLiDB class provides MySQL implementation of the database interface.

  eZMySQLiDB is the MySQL implementation of eZDB.
  \sa eZDB
*/

class eZMySQLiDB extends eZDBInterface
{
    /*!
      Create a new eZMySQLiDB object and connects to the database backend.
    */
    function eZMySQLiDB( $parameters )
    {
        $this->eZDBInterface( $parameters );

        $this->CharsetMapping = array( 'iso-8859-1' => 'latin1',
                                       'iso-8859-2' => 'latin2',
                                       'iso-8859-8' => 'hebrew',
                                       'iso-8859-7' => 'greek',
                                       'iso-8859-9' => 'latin5',
                                       'iso-8859-13' => 'latin7',
                                       'windows-1250' => 'cp1250',
                                       'windows-1251' => 'cp1251',
                                       'windows-1256' => 'cp1256',
                                       'windows-1257' => 'cp1257',
                                       'utf-8' => 'utf8',
                                       'koi8-r' => 'koi8r',
                                       'koi8-u' => 'koi8u' );

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
        if ( !is_object( $this->DBWriteConnection ) )
        {
            $connection = $this->connect( $this->Server, $this->DB, $this->User, $this->Password, $this->SocketPath, $this->Charset, $this->Port );
            if ( $this->IsConnected )
            {
                $this->DBWriteConnection = $connection;
            }
        }

        // Connect to slave
        if ( !is_object( $this->DBConnection ) )
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
            eZDebug::writeWarning( 'mysqli does not support persistent connections', 'eZMySQLiDB::connect' );
        }

        $connection = mysqli_connect( $server, $user, $password, null, (int)$port, $socketPath );

        $dbErrorText = mysqli_connect_error();
        $maxAttempts = $this->connectRetryCount();
        $waitTime = $this->connectRetryWaitTime();
        $numAttempts = 1;
        while ( !is_object( $connection ) and $numAttempts <= $maxAttempts )
        {
            sleep( $waitTime );
            if ( $this->UsePersistentConnection == true )
            {
                eZDebug::writeWarning( 'mysqli does not support persistent connections', 'eZMySQLiDB::connect' );
            }

            $connection = mysqli_connect( $this->Server, $this->User, $this->Password, null, (int)$this->Port, $this->SocketPath );

            $numAttempts++;
        }
        $this->setError();

        $this->IsConnected = true;

        if ( !is_object( $connection ) )
        {
            eZDebug::writeError( "Connection error: Couldn't connect to database. Please try again later or inform the system administrator.\n$dbErrorText", "eZMySQLiDB" );
            $this->IsConnected = false;
            throw new eZDBNoConnectionException( $server );
        }

        if ( $this->IsConnected && $db != null )
        {
            $ret = mysqli_select_db( $connection, $db );
            if ( !$ret )
            {
                //$this->setError();
                eZDebug::writeError( "Connection error: " . mysqli_errno( $connection ) . ": " . mysqli_error( $connection ), "eZMySQLiDB" );
                $this->IsConnected = false;
            }
        }

        if ( $charset !== null )
        {
            $originalCharset = $charset;
            $charset = eZCharsetInfo::realCharsetCode( $charset );
            // Convert charset names into something MySQL will understand
            if ( isset( $this->CharsetMapping[ $charset ] ) )
                $charset = $this->CharsetMapping[ $charset ];
        }

        if ( $this->IsConnected and $charset !== null and $this->isCharsetSupported( $charset ) )
        {
            $status = mysqli_set_charset( $connection, $charset );
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

      \note There will be no check for databases using MySQL 4.1.0 or lower since
            they do not have proper character set handling.
    */
    function checkCharset( $charset, &$currentCharset )
    {
        // If we don't have a database yet we shouldn't check it
        if ( !$this->DB )
            return true;

        $versionInfo = $this->databaseServerVersion();

        // We require MySQL 4.1.1 to use the new character set functionality,
        // MySQL 4.1.0 does not have a full implementation of this, see:
        // http://dev.mysql.com/doc/mysql/en/Charset.html
        // Older version should not check character sets
        if ( version_compare( $versionInfo['string'], '4.1.1' ) < 0 )
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
        $status = mysqli_query( $this->DBConnection, $query );
        $this->reportQuery( 'eZMySQLiDB', $query, false, false );
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

                    $key = array_search( $currentCharset, $this->CharsetMapping );
                    $unmappedCurrentCharset = ( $key === false ) ? $currentCharset : $key;

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

                    $this->reportQuery( ( $server == eZDBInterface::SERVER_MASTER ? 'on master : ' : '' ) . 'eZMySQLiDB', $text, $num_rows, $this->timeTaken() );
                }
            }
            eZDebug::accumulatorStop( 'mysqli_query' );
            if ( $result )
            {
                return $result;
            }
            else
            {
                eZDebug::writeError( "Query error: " . mysqli_error( $connection ) . ". Query: ". $sql, "eZMySQLiDB"  );
                $oldRecordError = $this->RecordError;
                // Turn off error handling while we unlock
                $this->RecordError = false;
                $this->unlock();
                $this->RecordError = $oldRecordError;

                $this->reportError();

                return false;
            }
        }
        else
        {
            eZDebug::writeError( "Trying to do a query without being connected to a database!", "eZMySQLiDB"  );
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
                $this->reportQuery( 'eZMySQLiDB', $sql, false, false );
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
        return eZDBInterface::RELATION_TABLE_BIT;
    }

    function supportedRelationTypes()
    {
        return array( eZDBInterface::RELATION_TABLE );
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
        if ( $relationType != eZDBInterface::RELATION_TABLE )
        {
            eZDebug::writeError( "Unsupported relation type '$relationType'", 'eZMySQLiDB::relationCount' );
            return false;
        }
        $count = false;
        if ( $this->IsConnected )
        {
            $result = mysqli_query( $this->DBConnection, 'SHOW TABLES from `' . $this->DB .'`' );
            $count = mysqli_num_rows( $result );
            mysqli_free_result( $result );
        }
        return $count;
    }

    function relationList( $relationType = eZDBInterface::RELATION_TABLE )
    {
        if ( $relationType != eZDBInterface::RELATION_TABLE )
        {
            eZDebug::writeError( "Unsupported relation type '$relationType'", 'eZMySQLiDB::relationList' );
            return false;
        }
        $tables = array();
        if ( $this->IsConnected )
        {
            $result = mysqli_query( $this->DBConnection, 'SHOW TABLES from `' . $this->DB .'`' );
            while( $row = mysqli_fetch_row( $result ) )
            {
                $tables[] = $row[0];
            }
            mysqli_free_result( $result );
        }
        return $tables;
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

            $result = mysqli_query( $connection, 'SHOW TABLES from `' . $db .'`' );
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
            eZDebug::writeError( "Unknown relation type '$relationType'", 'eZMySQLiDB::removeRelation' );
            return false;
        }

        if ( $this->IsConnected )
        {
            $sql = "DROP $relationTypeName $relationName";
            return $this->query( $sql );
        }
        return false;
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
            eZDebug::writeDebug( 'escapeString called before connection is made', 'eZMySQLiDB::escapeString' );
            return $str;
        }
    }

    function close()
    {
        if ( $this->IsConnected )
        {
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

    function setError()
    {
        if ( $this->IsConnected )
        {
            $this->ErrorMessage = mysqli_error( $this->DBConnection );
            $this->ErrorNumber = mysqli_errno( $this->DBConnection );
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

    public $CharsetMapping;
    protected $TempTableList;

    /// \privatesection
}

?>
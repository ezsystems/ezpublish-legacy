<?php
//
// $Id$
//
// Definition of eZMySQLDB class
//
// Created on: <12-Feb-2002 15:54:17 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
  \class eZMySQLDB ezmysqldb.php
  \ingroup eZDB
  \brief The eZMySQLDB class provides MySQL implementation of the database interface.

  eZMySQLDB is the MySQL implementation of eZDB.
  \sa eZDB
*/

require_once( "lib/ezutils/classes/ezdebug.php" );
//include_once( "lib/ezutils/classes/ezini.php" );
//include_once( "lib/ezdb/classes/ezdbinterface.php" );

class eZMySQLDB extends eZDBInterface
{
    /*!
      Create a new eZMySQLDB object and connects to the database backend.
    */
    function eZMySQLDB( $parameters )
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
        if ( $this->DBWriteConnection == false )
        {
            $connection = $this->connect( $this->Server, $this->DB, $this->User, $this->Password, $this->SocketPath, $this->Charset, $this->Port );
            if ( $this->IsConnected )
            {
                $this->DBWriteConnection = $connection;
            }
        }

        // Connect to slave
        if ( $this->DBConnection == false )
        {
            if ( $this->UseSlaveServer === true )
            {
                $connection = $this->connect( $this->SlaveServer, $this->SlaveDB, $this->SlaveUser, $this->SlavePassword, $this->SocketPath, $this->Charset, $this->SlavePort );
            }
            else
            {
                $connection =& $this->DBWriteConnection;
            }

            if ( $connection and $this->DBWriteConnection )
            {
                $this->DBConnection = $connection;
                $this->IsConnected = true;
            }
        }

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

        if ( $this->UsePersistentConnection == true )
        {
            $connection = mysql_pconnect( $server, $user, $password );
        }
        else
        {
            $connection = mysql_connect( $server, $user, $password, true );
        }
        $dbErrorText = mysql_error();
        $maxAttempts = $this->connectRetryCount();
        $waitTime = $this->connectRetryWaitTime();
        $numAttempts = 1;
        while ( $connection == false and $numAttempts <= $maxAttempts )
        {
            sleep( $waitTime );
            if ( $this->UsePersistentConnection == true )
            {
                $connection = mysql_pconnect( $this->Server, $this->User, $this->Password );
            }
            else
            {
                $connection = mysql_connect( $this->Server, $this->User, $this->Password );
            }
            $numAttempts++;
        }
        $this->setError();

        $this->IsConnected = true;

        if ( $connection == false )
        {
            eZDebug::writeError( "Connection error: Couldn't connect to database. Please try again later or inform the system administrator.\n$dbErrorText", "eZMySQLDB" );
            $this->IsConnected = false;
        }

        if ( $this->IsConnected && $db != null )
        {
            $ret = mysql_select_db( $db, $connection );
            $this->setError();
            if ( !$ret )
            {
                eZDebug::writeError( "Connection error: " . mysql_errno( $connection ) . ": " . mysql_error( $connection ), "eZMySQLDB" );
                $this->IsConnected = false;
            }
        }

        if ( $charset !== null )
        {
            $originalCharset = $charset;
            //include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
            $charset = eZCharsetInfo::realCharsetCode( $charset );
            // Convert charset names into something MySQL will understand
            if ( isset( $this->CharsetMapping[ $charset ] ) )
                $charset = $this->CharsetMapping[ $charset ];
        }

        if ( $this->IsConnected and $charset !== null and $this->isCharsetSupported( $charset ) )
        {
            $versionInfo = $this->databaseServerVersion();

            // We require MySQL 4.1.1 to use the new character set functionality,
            // MySQL 4.1.0 does not have a full implementation of this, see:
            // http://dev.mysql.com/doc/mysql/en/Charset.html
            if ( version_compare( $versionInfo['string'], '4.1.1' ) >= 0 )
            {
                $query = "SET NAMES '" . $charset . "'";
                $status = mysql_query( $query, $connection );
                $this->reportQuery( 'eZMySQLDB', $query, false, false );
                if ( !$status )
                {
                    $this->setError();
                    eZDebug::writeWarning( "Connection warning: " . mysql_errno( $connection ) . ": " . mysql_error( $connection ), "eZMySQLDB" );
                }
            }
        }

        return $connection;
    }

    /*!
     \reimp
    */
    function databaseName()
    {
        return 'mysql';
    }

    /*!
      \reimp
    */
    function bindingType( )
    {
        return eZDBInterface::BINDING_NO;
    }

    /*!
      \reimp
    */
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

        //include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );

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
        $this->reportQuery( 'eZMySQLDB', $query, false, false );
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
                    //include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
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

    /*!
     \reimp
    */
    function query( $sql )
    {
        if ( $this->IsConnected )
        {
            eZDebug::accumulatorStart( 'mysql_query', 'mysql_total', 'Mysql_queries' );
            $orig_sql = $sql;
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
            // Check if it's a write or read sql query
            $sql = trim( $sql );

            $isWriteQuery = true;
            if ( strncasecmp( $sql, 'select', 6 ) === 0 )
            {
                $isWriteQuery = false;
            }

            // Send temporary create queries to slave server
            if ( preg_match( "/create\s+temporary/i", $sql ) )
            {
                $isWriteQuery = false;
            }

            if ( $isWriteQuery )
            {
                $connection = $this->DBWriteConnection;
            }
            else
            {
                $connection = $this->DBConnection;
            }

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

                    $this->reportQuery( 'eZMySQLDB', $text, $num_rows, $this->timeTaken() );
                }
            }
            eZDebug::accumulatorStop( 'mysql_query' );
            if ( $result )
            {
                return $result;
            }
            else
            {
                eZDebug::writeError( "Query error: " . mysql_error( $connection ) . ". Query: ". $sql, "eZMySQLDB"  );
                $oldRecordError = $this->RecordError;
                // Turn off error handling while we unlock
                $this->RecordError = false;
                mysql_query( 'UNLOCK TABLES', $connection );
                $this->RecordError = $oldRecordError;

                $this->reportError();

                return false;
            }
        }
        else
        {
            eZDebug::writeError( "Trying to do a query without being connected to a database!", "eZMySQLDB"  );
        }


    }

    /*!
     \reimp
    */
    function arrayQuery( $sql, $params = array() )
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
            $result = $this->query( $sql );

            if ( $result == false )
            {
                $this->reportQuery( 'eZMySQLDB', $sql, false, false );
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

    /*!
     \reimp
    */
    function supportedRelationTypeMask()
    {
        return eZDBInterface::RELATION_TABLE_BIT;
    }

    /*!
     \reimp
    */
    function supportedRelationTypes()
    {
        return array( eZDBInterface::RELATION_TABLE );
    }

    /*!
     \reimp
    */
    function relationCounts( $relationMask )
    {
        if ( $relationMask & eZDBInterface::RELATION_TABLE_BIT )
            return $this->relationCount();
        else
            return 0;
    }

    /*!
      \reimp
    */
    function relationCount( $relationType = eZDBInterface::RELATION_TABLE )
    {
        if ( $relationType != eZDBInterface::RELATION_TABLE )
        {
            eZDebug::writeError( "Unsupported relation type '$relationType'", 'eZMySQLDB::relationCount' );
            return false;
        }
        $count = false;
        if ( $this->IsConnected )
        {
            $query = "SHOW TABLES FROM `" . $this->DB . "`";
            $result = @mysql_query( $query, $this->DBConnection );
            $this->reportQuery( 'eZMySQLDB', $query, false, false );
            $count = mysql_num_rows( $result );
            mysql_free_result( $result );
        }
        return $count;
    }

    /*!
      \reimp
    */
    function relationList( $relationType = eZDBInterface::RELATION_TABLE )
    {
        if ( $relationType != eZDBInterface::RELATION_TABLE )
        {
            eZDebug::writeError( "Unsupported relation type '$relationType'", 'eZMySQLDB::relationList' );
            return false;
        }
        $tables = array();
        if ( $this->IsConnected )
        {
            $query = "SHOW TABLES FROM `" . $this->DB . "`";
            $result = @mysql_query( $query, $this->DBConnection );
            $this->reportQuery( 'eZMySQLDB', $query, false, false );
            $count = mysql_num_rows( $result );
            for ( $i = 0; $i < $count; ++ $i )
            {
                $table = mysql_fetch_array( $result );
                $tables[] = $table[0];
            }
            mysql_free_result( $result );
        }
        return $tables;
    }

    /*!
     \reimp
    */
    function eZTableList()
    {
        $tables = array();
        if ( $this->IsConnected )
        {
            $query = "SHOW TABLES FROM `" . $this->DB . "`";
            $result = @mysql_query( $query, $this->DBConnection );
            $this->reportQuery( 'eZMySQLDB', $query, false, false );
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

    /*!
     \reimp
    */
    function relationMatchRegexp( $relationType )
    {
        return "#^ez#";
    }

    /*!
      \reimp
    */
    function removeRelation( $relationName, $relationType )
    {
        $relationTypeName = $this->relationName( $relationType );
        if ( !$relationTypeName )
        {
            eZDebug::writeError( "Unknown relation type '$relationType'", 'eZMySQLDB::removeRelation' );
            return false;
        }

        if ( $this->IsConnected )
        {
            $sql = "DROP $relationTypeName $relationName";
            return $this->query( $sql );
        }
        return false;
    }

    /*!
     \reimp
    */
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

    /*!
     \reimp
    */
    function unlock()
    {
        if ( $this->IsConnected )
        {
            $this->query( "UNLOCK TABLES" );
        }
    }

    /*!
     \reimp
     The query to start the transaction.
    */
    function beginQuery()
    {
        return $this->query("BEGIN WORK");
    }

    /*!
     \reimp
     The query to commit the transaction.
    */
    function commitQuery()
    {
        return $this->query( "COMMIT" );
    }

    /*!
     \reimp
     The query to cancel the transaction.
    */
    function rollbackQuery()
    {
        return $this->query( "ROLLBACK" );
    }

    /*!
     \reimp
    */
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

    /*!
     \reimp
    */
    function escapeString( $str )
    {
        return mysql_real_escape_string( $str );
    }

    /*!
     \reimp
    */
    function close()
    {
        if ( $this->IsConnected )
        {
            mysql_close( $this->DBConnection );
            mysql_close( $this->DBWriteConnection );
        }
    }

    /*!
     \reimp
    */
    function createDatabase( $dbName )
    {
        if ( $this->DBConnection != false )
        {
            mysql_create_db( $dbName, $this->DBConnection );
            $this->setError();
        }
    }

    /*!
     \reimp
    */
    function setError()
    {
        if ( $this->DBConnection )
        {
            $this->ErrorMessage = mysql_error( $this->DBConnection );
            $this->ErrorNumber = mysql_errno( $this->DBConnection );
        }
        else
        {
            $this->ErrorMessage = mysql_error();
            $this->ErrorNumber = mysql_errno();
        }
    }

    /*!
     \reimp
    */
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
            // we don't allow "mysql" database to be shown anywhere
            $curDB = mysql_db_name( $databaseArray, $i );
            if ( strcasecmp( $curDB, 'mysql' ) != 0 &&
                 ( $dbServerMainVersion < '5' || strcasecmp( $curDB, 'information_schema' ) != 0 ) )
            {
                $databases[] = $curDB;
            }
            ++$i;
        }
        return $databases;
    }

    /*!
     \reimp
    */
    function databaseServerVersion()
    {
        $versionInfo = mysql_get_server_info();

        $versionArray = explode( '.', $versionInfo );

        return array( 'string' => $versionInfo,
                      'values' => $versionArray );
    }

    /*!
     \reimp
    */
    function databaseClientVersion()
    {
        $versionInfo = mysql_get_client_info();

        $versionArray = explode( '.', $versionInfo );

        return array( 'string' => $versionInfo,
                      'values' => $versionArray );
    }

    /*!
     \reimp
    */
    function isCharsetSupported( $charset )
    {
        if ( $charset == 'utf-8' )
        {
            $versionInfo = $this->databaseServerVersion();

            // We require MySQL 4.1.1 to use the new character set functionality,
            // MySQL 4.1.0 does not have a full implementation of this, see:
            // http://dev.mysql.com/doc/mysql/en/Charset.html
            return ( version_compare( $versionInfo['string'], '4.1.1' ) >= 0 );
        }
        else
        {
            return true;
        }
    }

    public $CharsetMapping;

    /// \privatesection
}

?>

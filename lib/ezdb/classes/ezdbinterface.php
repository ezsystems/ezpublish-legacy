<?php
//
// $Id$
//
// Definition of eZDBInterface class
//
// Created on: <12-Feb-2002 15:54:17 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2004 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

/*!
  \class eZDBInterface ezdbinterface.php
  \ingroup eZDB
  \brief The eZDBInterface defines the interface for all database implementations

  \sa eZDB
*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );

define( 'EZ_DB_RELATION_TABLE', 0 );
define( 'EZ_DB_RELATION_SEQUENCE', 1 );
define( 'EZ_DB_RELATION_TRIGGER', 2 );
define( 'EZ_DB_RELATION_VIEW', 3 );
define( 'EZ_DB_RELATION_INDEX', 4 );

define( 'EZ_DB_RELATION_TABLE_BIT', (1 << EZ_DB_RELATION_TABLE) );
define( 'EZ_DB_RELATION_SEQUENCE_BIT', (1 << EZ_DB_RELATION_SEQUENCE) );
define( 'EZ_DB_RELATION_TRIGGER_BIT', (1 << EZ_DB_RELATION_TRIGGER) );
define( 'EZ_DB_RELATION_VIEW_BIT', (1 << EZ_DB_RELATION_VIEW) );
define( 'EZ_DB_RELATION_INDEX_BIT', (1 << EZ_DB_RELATION_INDEX) );

define( 'EZ_DB_RELATION_NONE', 0 );
define( 'EZ_DB_RELATION_MASK', ( EZ_DB_RELATION_TABLE_BIT |
                                 EZ_DB_RELATION_SEQUENCE_BIT |
                                 EZ_DB_RELATION_TRIGGER_BIT |
                                 EZ_DB_RELATION_VIEW_BIT |
                                 EZ_DB_RELATION_INDEX_BIT ) );

define( 'EZ_DB_ERROR_MISSING_EXTENSION', 1 );

class eZDBInterface
{
    /*!
      Create a new eZDBInterface object and connects to the database backend.
    */
    function eZDBInterface( $parameters )
    {
        $server = $parameters['server'];
        $user = $parameters['user'];
        $password = $parameters['password'];
        $db = $parameters['database'];
        $useSlaveServer = $parameters['use_slave_server'];
        $slaveServer = $parameters['slave_server'];
        $slaveUser = $parameters['slave_user'];
        $slavePassword = $parameters['slave_password'];
        $slaveDB =  $parameters['slave_database'];
        $socketPath = $parameters['socket'];
        $charset = $parameters['charset'];
		$isInternalCharset = $parameters['is_internal_charset'];
        $builtinEncoding = $parameters['builtin_encoding'];
        $connectRetries = $parameters['connect_retries'];

        if ( $parameters['use_persistent_connection'] == 'enabled' )
        {
            $this->UsePersistentConnection = true;
        }

        $this->DB = $db;
        $this->Server = $server;
        $this->SocketPath = $socketPath;
        $this->User = $user;
        $this->Password = $password;
        $this->UseSlaveServer = $useSlaveServer;
        $this->SlaveDB = $slaveDB;
        $this->SlaveServer = $slaveServer;
        $this->SlaveUser = $slaveUser;
        $this->SlavePassword = $slavePassword;
        $this->Charset = $charset;
		$this->IsInternalCharset = $isInternalCharset;
        $this->UseBuiltinEncoding = $builtinEncoding;
        $this->ConnectRetries = $connectRetries;
        $this->DBConnection = false;
        $this->DBWriteConnection = false;
        $this->TransactionCounter = 0;

        $this->OutputTextCodec = null;
        $this->InputTextCodec = null;
        if ( $this->UseBuiltinEncoding )
        {
            include_once( "lib/ezi18n/classes/eztextcodec.php" );
            $this->OutputTextCodec =& eZTextCodec::instance( $charset, false, false );
            $this->InputTextCodec =& eZTextCodec::instance( false, $charset, false );
        }

        $ini =& eZINI::instance();
        if ( ( $ini->variable( "DatabaseSettings", "SQLOutput" ) == "enabled" ) and
             ( $ini->variable( "DebugSettings", "DebugOutput" ) == "enabled" ) )
        {
            $this->OutputSQL = true;
        }
        else
        {
            $this->OutputSQL = false;
        }

        $this->IsConnected = false;
        $this->NumQueries = 0;
        $this->StartTime = false;
        $this->EndTime = false;
        $this->TimeTaken = false;

		$this->AttributeVariableMap =
		array(
		    'database_name' => 'DB',
            'database_server' => 'Server',
            'database_socket_path' => 'SocketPath',
            'database_user' => 'User',
            'use_slave_server' => 'UseSlaveServer',
            'slave_database_name' => 'SlaveDB',
            'slave_database_server' => 'SlaveServer',
            'slave_database_user' => 'SlaveUser',
            'charset' => 'Charset',
			'is_internal_charset' => 'IsInternalCharset',
	        'use_builting_encoding' => 'UseBuiltinEncoding',
        	'retry_count' => 'ConnectRetries' );
    }

    /*!
	 \return the available attributes for this database handler.
	*/
	function attributes()
	{
		return array_keys( $this->AttributeVariableMap );
	}

    /*!
	 \return \c true if the attribute \a $name exists for this database handler.
	*/
	function hasAttribute( $name )
	{
		if ( isset( $this->AttributeVariableMap[$name] ) )
		{
		    return true;
		}
		return false;
	}

    /*!
	 \return the value of the attribute \a $name if it exists, otherwise \c null.
	*/
	function &attribute( $name )
	{
		if ( isset( $this->AttributeVariableMap[$name] ) )
		{
		    $memberVariable = $this->AttributeVariableMap[$name];
			return $this->$memberVariable;
		}
		return null;
	}

    /*!
     \private
     Prepare the sql file so we can create the database.
     \param $fd    The file descriptor
     \param $buffer Reference to string buffer for SQL queries.
    */
    function prepareSqlQuery( &$fd, &$buffer )
    {

        $sqlQueryArray = array();
        while( count( $sqlQueryArray ) == 0 && !feof( $fd ) )
        {
            $buffer  .= fread( $fd, 4096 );
            if ( $buffer )
            {
                // Fix SQL file by deleting all comments and newlines
//            eZDebug::writeDebug( $buffer, "read data" );
                $sqlQuery = preg_replace( array( "/^#.*\n" . "/m",
                                                 "#^/\*.*\*/\n" . "#m",
                                                 "/^--.*\n" . "/m",
                                                 "/\n|\r\n|\r/m" ),
                                          array( "",
                                                 "",
                                                 "",
                                                 "\n" ),
                                          $buffer );
//            eZDebug::writeDebug( $sqlQuery, "read data" );

                // Split the query into an array
                $sqlQueryArray = preg_split( "/;\n/m", $sqlQuery );

                if ( preg_match( '/;\n/m', $sqlQueryArray[ count( $sqlQueryArray ) -1 ] ) )
                {
                    $buffer = '';
                }
                else
                {
                    $buffer = $sqlQueryArray[ count( $sqlQueryArray ) -1 ];
                    array_splice( $sqlQueryArray, count( $sqlQueryArray ) -1 , 1 );
                }
            }
            else
            {
                return $sqlQueryArray;

            }
        }
        return $sqlQueryArray;
    }

    /*!
     Inserts the SQL file \a $sqlFile found in the path \a $path into
     the currently connected database.
     \return \c true if succesful.
    */
    function insertFile( $path, $sqlFile, $usePathType = true )
    {
        $type = $this->databaseName();

        include_once( 'lib/ezfile/classes/ezdir.php' );
        if ( $usePathType )
            $sqlFileName = eZDir::path( array( $path, $type, $sqlFile ) );
        else
            $sqlFileName = eZDir::path( array( $path, $sqlFile ) );
        $sqlFileHandler = fopen( $sqlFileName, 'rb' );
        $buffer = '';
        $done = false;
        while(  count( ( $sqlArray = $this->prepareSqlQuery( $sqlFileHandler, $buffer ) ) ) > 0 )
        {
            // Turn unneccessary SQL debug output off
            $oldOutputSQL = $this->OutputSQL;
            $this->OutputSQL = false;
            if ( $sqlArray && is_array( $sqlArray ) )
            {
                $done = true;
                foreach( $sqlArray as $singleQuery )
                {
                    $singleQuery = preg_replace( "/\n|\r\n|\r/", " ", $singleQuery );
                    if ( trim( $singleQuery ) != "" )
                    {
//                    eZDebug::writeDebug( $singleQuery );
                        $this->query( $singleQuery );
                        if ( $this->errorNumber() != 0 )
                            return false;
                    }
                }

            }
            $this->OutputSQL = $oldOutputSQL;
        }
        return $done;

    }

    /*!
     \private
     Writes a debug notice with query information.
    */
    function reportQuery( $class, $sql, $numRows, $timeTaken )
    {
        $rowText = '';
        if ( $numRows !== false )
            $rowText = "$numRows rows, ";
        eZDebug::writeNotice( "$sql", "$class::query($rowText" . number_format( $timeTaken, 3 ) . " ms) query number per page:" . $this->NumQueries++ );
    }

    /*!
     Enabled or disables sql output.
    */
    function setIsSQLOutputEnabled( $enabled )
    {
        $this->OutputSQL = $enabled;
    }

    /*!
     \private
     Records the current micro time. End the timer with endTimer() and
     fetch the result with timeTaken();
    */
    function startTimer()
    {
        $this->StartTime = microtime();
    }

    /*!
     \private
     Stops the current timer and calculates the time taken.
     \sa startTimer, timeTaken
    */
    function endTimer()
    {
        $this->EndTime = microtime();
        // Calculate time taken in ms
        list($usec, $sec) = explode( " ", $this->StartTime );
        $start_val = ((float)$usec + (float)$sec);
        list($usec, $sec) = explode( " ", $this->EndTime );
        $end_val = ((float)$usec + (float)$sec);
        $this->TimeTaken = $end_val - $start_val;
        $this->TimeTaken *= 1000.0;
    }

    /*!
     \private
     \return the micro time when the timer was start or false if no timer.
    */
    function startTime()
    {
        return $this->StartTime;
    }

    /*!
     \private
     \return the micro time when the timer was ended or false if no timer.
    */
    function endTime()
    {
        return $this->EndTime;
    }

    /*!
     \private
     \return the number of milliseconds the last operation took or false if no value.
    */
    function timeTaken()
    {
        return $this->TimeTaken;
    }

    /*!
     \pure
     Returns the name of driver, this is used to determine the name of the database type.
     For instance multiple implementations of the MySQL database will all return \c 'mysql'.
    */
    function databaseName()
    {
        return '';
    }

    /*!
     \return the socket path for the database or \c false if no socket path was defined.
    */
    function socketPath()
    {
        return $this->SocketPath;
    }

    /*!
     \return the number of times the db handler should try to reconnect if it fails.
    */
    function connectRetryCount()
    {
        return $this->ConnectRetries;
    }

    /*!
     \return the number of seconds the db handler should wait before rereconnecting.
     \note Currently returns 3 seconds.
    */
    function connectRetryWaitTime()
    {
        return 3;
    }

    /*!
     \pure
     \return a mask of the relation type it supports.
    */
    function supportedRelationTypeMask()
    {
        return EZ_DB_RELATION_NONE;
    }

    /*!
     \pure
     \return if the short column names should be used insted of default ones
    */
    function useShortNames()
    {
        return false;
    }

    /*!
     \pure
     \return an array of the relation types.
    */
    function supportedRelationTypes()
    {
        return array();
    }

    /*!
     \virtual
     \return the version of the database server or \c false if no version could be retrieved/
    */
    function databaseServerVersion()
    {
    }

    /*!
     \pure
     \return the version of the database client or \c false if no version could be retrieved/
    */
    function databaseClientVersion()
    {
    }

    /*!
     \return \c true if the charset \a $charset is supported by the connected database.
    */
    function isCharsetSupported( $charset )
    {
        return false;
    }

    /*!
     Returns the charset which the database is encoded in.
     \sa usesBuiltinEncoding
    */
    function charset()
    {
        return $this->Charset;
    }

    /*!
     Returns true if the database handles encoding itself, if not
     all queries and returned data must be decoded yourselves.
     \note This functionality might be removed in the future
    */
    function usesBuiltinEncoding()
    {
        return $this->UseBuiltinEncoding;
    }

    /*!
      \pure
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.
    */
    function &query( $sql )
    {
    }

    /*!
      \pure
      Executes an SQL query and returns the result as an array of accociative arrays.
    */
    function &arrayQuery( $sql, $params = array() )
    {
    }

    /*!
      \pure
      Locks a table
    */
    function lock( $table )
    {
    }

    /*!
      \pure
      Releases table locks.
    */
    function unlock()
    {
    }

    /*!
      \pure
      Starts a new transaction.
    */
    function begin()
    {
    }

    /*!
      \pure
      Commits the transaction.
    */
    function commit()
    {
    }

    /*!
      \pure
      Cancels the transaction.
    */
    function rollback()
    {
    }

    /*!
      \pure
      \return the relation count for all relation types in the mask \a $relationMask.
    */
    function relationCounts( $relationMask )
    {
    }

    /*!
      \pure
      \return the number of relation objects in the database for the relation type \a $relationType.
    */
    function relationCount( $relationType = EZ_DB_RELATION_TABLE )
    {
    }

    /*!
     \pure
     \return existing ez publish tables in database
    */
    function eZTableList()
    {
    }

    /*!
      \pure
      \return the relation names in the database as an array for the relation type \a $relationType.
    */
    function relationList( $relationType = EZ_DB_RELATION_TABLE )
    {
    }

    /*!
      \pure
      Tries to remove the relation type \a $relationType named \a $relationName
      \return \c true if successful
    */
    function removeRelation( $relationName, $relationType )
    {
        return false;
    }

    /*!
     \protected
     \return the name of the relation type which is usable in SQL or false if unknown type.
     \note This function can be used by som database handlers which can operate on relation types using SQL.
    */
    function relationName( $relationType )
    {
        $names = array( EZ_DB_RELATION_TABLE => 'TABLE',
                        EZ_DB_RELATION_SEQUENCE => 'SEQUENCE',
                        EZ_DB_RELATION_TRIGGER => 'TRIGGER',
                        EZ_DB_RELATION_VIEW => 'VIEW',
                        EZ_DB_RELATION_INDEX => 'INDEX' );
        if ( !isset( $names[$relationType] ) )
            return false;
        return $names[$relationType];
    }

    /*!
      \pure
      Returns the last serial ID generated with an auto increment field.
    */
    function lastSerialID( $table, $column )
    {
    }

    /*!
      \pure
      Will escape a string so it's ready to be inserted in the database.
    */
    function &escapeString( $str )
    {
    }

    /*!
      \pure
      Will close the database connection.
    */
    function close()
    {
    }

    /*!
      \protected
      Returns true if we're connected to the database backend.
    */
    function isConnected()
    {
        return $this->IsConnected;
    }

    /*!
      \pure
      Create a new database
    */
    function createDatabase()
    {
    }

    /*!
      Create a new temporary table
    */
    function createTempTable( $createTableQuery = '' )
    {
        $this->query( $createTableQuery );
    }

    /*!
      Drop temporary table
    */
    function dropTempTable( $dropTableQuery = '' )
    {
        $this->query( $dropTableQuery );
    }

    /*!
      \pure
      Sets the error message and error message number
    */
    function setError()
    {
    }

    /*!
      Returns the error message
    */
    function errorMessage()
    {
        return $this->ErrorMessage;
    }

    /*!
      Returns the error number
    */
    function errorNumber()
    {
        return $this->ErrorNumber;
    }

    /*!
      Return alvailable databases in database.

      \return array of available databases,
              null of none available
              false if listing databases not supported by database
    */
    function availableDatabases()
    {
        return false;
    }

    /*!
      Get database version number

      \return version number
              false if not supported
    */
    function version()
    {
        return false;
    }

    /// \protectedsection
    /// Contains the current server
    var $Server;
    /// The socket path, used by MySQL
    var $SocketPath;
    /// The current database name
    var $DB;
    /// The current connection, \c false if not connection has been made
    var $DBConnection;
    /// Contains the write database connection if used
    var $DBWriteConnection;
    /// Stores the database connection user
    var $User;
    /// Stores the database connection password
    var $Password;
    /// The charset used for the current database
    var $Charset;
    /// The number of times to retry a connection if it fails
    var $ConnectRetries;
    /// Instance of a textcodec which handles text conversion, may not be set if no builtin encoding is used
    var $OutputTextCodec;
    /// True if a builtin encoder is to be used, this means that all input/output text is converted
    var $UseBuiltinEncoding;
    /// Setting if SQL queries should be sent to debug output
    var $OutputSQL;
    /// Contains true if we're connected to the database backend
    var $IsConnected = false;
    /// Contains number of queries sended to DB
    var $NumQueries = 0;
    /// The start time of the timer
    var $StartTime;
    /// The end time of the tiemr
    var $EndTime;
    /// The total number of milliseconds the timer took
    var $TimeTaken;
    /// The database error message of the last executed function
    var $ErrorMessage;
    /// The database error message number of the last executed function
    var $ErrorNumber = 0;
    /// If true then ErrorMessage and ErrorNumber get filled
    var $RecordError = true;
    /// If true then the database connection should be persistent
    var $UsePersistentConnection = false;
    /// Contains true if slave servers are enabled
    var $UserSlaveServer;
    /// The slave database name
    var $SlaveDB;
    /// The slave server name
    var $SlaveServer;
    /// The slave database user
    var $SlaveUser;
    /// The slave database user password
    var $SlavePassword;
}

?>

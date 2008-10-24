<?php
//
// $Id$
//
// Definition of eZDBInterface class
//
// Created on: <12-Feb-2002 15:54:17 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZDBInterface ezdbinterface.php
  \ingroup eZDB
  \brief The eZDBInterface defines the interface for all database implementations

  \sa eZDB
*/

require_once( "lib/ezutils/classes/ezdebug.php" );
//include_once( "lib/ezutils/classes/ezini.php" );

class eZDBInterface
{
    const BINDING_NO = 0;
    const BINDING_NAME = 1;
    const BINDING_ORDERED = 2;

    const RELATION_TABLE = 0;
    const RELATION_SEQUENCE = 1;
    const RELATION_TRIGGER = 2;
    const RELATION_VIEW = 3;
    const RELATION_INDEX = 4;

    const RELATION_TABLE_BIT = 1;
    const RELATION_SEQUENCE_BIT = 2;
    const RELATION_TRIGGER_BIT = 4;
    const RELATION_VIEW_BIT = 8;
    const RELATION_INDEX_BIT = 16;

    const RELATION_NONE = 0;
    const RELATION_MASK = 31;

    const ERROR_MISSING_EXTENSION = 1;

    const SERVER_MASTER = 1;
    const SERVER_SLAVE = 2;

    /*!
      Create a new eZDBInterface object and connects to the database backend.
    */
    function eZDBInterface( $parameters )
    {
        $server = $parameters['server'];
        $port = $parameters['port'];
        $user = $parameters['user'];
        $password = $parameters['password'];
        $db = $parameters['database'];
        $useSlaveServer = $parameters['use_slave_server'];
        $slaveServer = $parameters['slave_server'];
        $slavePort = $parameters['slave_port'];
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
        $this->Port = $port;
        $this->SocketPath = $socketPath;
        $this->User = $user;
        $this->Password = $password;
        $this->UseSlaveServer = $useSlaveServer;
        $this->SlaveDB = $slaveDB;
        $this->SlaveServer = $slaveServer;
        $this->SlavePort = $slavePort;
        $this->SlaveUser = $slaveUser;
        $this->SlavePassword = $slavePassword;
        $this->Charset = $charset;
        $this->IsInternalCharset = $isInternalCharset;
        $this->UseBuiltinEncoding = $builtinEncoding;
        $this->ConnectRetries = $connectRetries;
        $this->DBConnection = false;
        $this->DBWriteConnection = false;
        $this->TransactionCounter = 0;
        $this->TransactionIsValid = false;
        $this->TransactionStackTree = false;

        $this->OutputTextCodec = null;
        $this->InputTextCodec = null;
/*
        This is pseudocode, there is no such function as
        mysql_supports_charset() of course
        if ( $this->UseBuiltinEncoding and mysql_supports_charset( $charset ) )
        {
            mysql_session_set_charset( $charset );
        }
        else
*/
        {
            //include_once( "lib/ezi18n/classes/eztextcodec.php" );
            $tmpOutputTextCodec = eZTextCodec::instance( $charset, false, false );
            $tmpInputTextCodec = eZTextCodec::instance( false, $charset, false );
            unset( $this->OutputTextCodec );
            unset( $this->InputTextCodec );
            $this->OutputTextCodec = null;
            $this->InputTextCodec = null;

            if ( $tmpOutputTextCodec && $tmpInputTextCodec )
            {
                if ( $tmpOutputTextCodec->conversionRequired() && $tmpInputTextCodec->conversionRequired() )
                {
                    $this->OutputTextCodec =& $tmpOutputTextCodec;
                    $this->InputTextCodec =& $tmpInputTextCodec;
                }
            }
        }

        $this->OutputSQL = false;
        $this->SlowSQLTimeout = 0;
        $ini = eZINI::instance();
        if ( ( $ini->variable( "DatabaseSettings", "SQLOutput" ) == "enabled" ) and
             ( $ini->variable( "DebugSettings", "DebugOutput" ) == "enabled" ) )
        {
            $this->OutputSQL = true;

            $this->SlowSQLTimeout = (int) $ini->variable( "DatabaseSettings", "SlowQueriesOutput" );
        }
        if ( $ini->variable( "DatabaseSettings", "DebugTransactions" ) == "enabled" )
        {
            // Setting it to an array turns on the debugging
            $this->TransactionStackTree = array();
        }

        $this->QueryAnalysisOutput = false;
        if ( $ini->variable( "DatabaseSettings", "QueryAnalysisOutput" ) == "enabled" )
        {
            $this->QueryAnalysisOutput = true;
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
            'database_port' => 'Port',
            'database_socket_path' => 'SocketPath',
            'database_user' => 'User',
            'use_slave_server' => 'UseSlaveServer',
            'slave_database_name' => 'SlaveDB',
            'slave_database_server' => 'SlaveServer',
            'slave_database_port' => 'SlavePort',
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
    function attribute( $name )
    {
        if ( isset( $this->AttributeVariableMap[$name] ) )
        {
            $memberVariable = $this->AttributeVariableMap[$name];
            return $this->$memberVariable;
        }
        else
        {
            eZDebug::writeError( "Attribute '$name' does not exist", 'eZDBInterface::attribute' );
            return null;
        }
    }

    /*!
      Checks if the requested character set matches the one used in the database.

      \return \c true if it matches or \c false if it differs.
      \param[out] $currentCharset The charset that the database uses,
                                  will only be set if the match fails.
                                  Note: This will be specific to the database.

      \note The default is to always return \c true, see the specific database handler
            for more information.
    */
    function checkCharset( $charset, &$currentCharset )
    {
        return true;
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

        //include_once( 'lib/ezfile/classes/ezdir.php' );
        if ( $usePathType )
            $sqlFileName = eZDir::path( array( $path, $type, $sqlFile ) );
        else
            $sqlFileName = eZDir::path( array( $path, $sqlFile ) );
        $sqlFileHandler = fopen( $sqlFileName, 'rb' );
        $buffer = '';
        $done = false;
        while ( count( ( $sqlArray = $this->prepareSqlQuery( $sqlFileHandler, $buffer ) ) ) > 0 )
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
                    if ( preg_match( "#^ */(.+)$#", $singleQuery, $matches ) )
                    {
                        $singleQuery = $matches[1];
                    }
                    if ( trim( $singleQuery ) != "" )
                    {
//                    eZDebug::writeDebug( $singleQuery );
                        $this->query( trim( $singleQuery ) );
                        if ( $this->errorNumber() )
                        {
                            return false;
                        }
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
    function reportQuery( $class, $sql, $numRows, $timeTaken, $asDebug = false )
    {
        $rowText = '';
        if ( $numRows !== false ) $rowText = "$numRows rows, ";

        $backgroundClass = ($this->TransactionCounter > 0  ? "debugtransaction transactionlevel-$this->TransactionCounter" : '');
        if ( $asDebug )
            eZDebug::writeDebug( "$sql", "$class::query($rowText" . number_format( $timeTaken, 3 ) . ' ms) query number per page:' . $this->NumQueries++, $backgroundClass );
        else
            eZDebug::writeNotice( "$sql", "$class::query($rowText" . number_format( $timeTaken, 3 ) . ' ms) query number per page:' . $this->NumQueries++, $backgroundClass );
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
        $this->StartTime = microtime( true );
    }

    /*!
     \private
     Stops the current timer and calculates the time taken.
     \sa startTimer, timeTaken
    */
    function endTimer()
    {
        $this->EndTime = microtime( true );
        // Calculate time taken in ms
        $this->TimeTaken = $this->EndTime - $this->StartTime;
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
        return eZDBInterface::RELATION_NONE;
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
     \pure
     \return a sql-expression(string) to get substring.
    */
    function subString( $string, $from, $len = null )
    {
        return '';
    }

    /*!
     \pure
     \return a sql-expression(string) to concatenate strings.
    */
    function concatString( $strings = array() )
    {
        return '';
    }

    /*!
     \pure
     \return a sql-expression(string) to generate a md5 sum of the string.
    */
    function md5( $str )
    {
        return '';
    }

    /*!
     \pure
     \return a sql-expression(string) to generate a bit and  of the argument.
    */
    function bitAnd( $arg1, $arg2 )
    {
        return '(' . $arg1 . ' & ' . $arg2 . ' ) ';
    }

    /*!
     \pure
     \return a sql-expression(string) to generate a bit and  of the argument.
    */
    function bitOr( $arg1, $arg2 )
    {
        return '( ' . $arg1 . ' | ' . $arg2 . ' ) ';
    }

    /*!
     Checks if the version number of the server is equal or larger than \a $minVersion.
     Will also check if the database type is correct if \a $name is set.

     \param $minVersion A string denoting the min. required version.
     \param $name The name of the database type it requires or \c false if it does not matter.
     \return \c true if the server fulfills the requirements.
    */
    function hasRequiredServerVersion( $minVersion, $name = false )
    {
        if ( $name !== false and
             $name != $this->databaseName() )
            return false;

        $version = $this->databaseServerVersion();
        $version = $version['string'];
        return version_compare( $version, $minVersion ) >= 0;
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
       Returns type of binding used in database plugin.
    */
    function bindingType( )
    {
    }

    /*!
      \pure
       Binds variable.
    */
    function bindVariable( $value, $fieldDef = false )
    {
    }

    /*!
      \pure
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.

      \param $sql SQL query to execute.
    */
    function query( $sql, $server = false )
    {
    }

    /*!
      \pure
      Executes an SQL query and returns the result as an array of accociative arrays.

      \param $sql SQL query to execute.
      \param $params Associative array containing extra parameters, can contain:
             - offset - The offset of the query.
             - limit - The limit of the query.
             - column - Limit returned row arrays to only contain this column.
      \param $server Which server to execute the query on, either eZDBInterface::SERVER_MASTER or eZDBInterface::SERVER_SLAVE

      An example would be:
      \code
      $db->arrayQuery( 'SELECT * FROM eztable', array( 'limit' => 10, 'offset' => 5 ) );
      \endcode
    */
    function arrayQuery( $sql, $params = array(), $server = false )
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
      Begin a new transaction. If we are already in transaction then we omit
      this new transaction and its matching commit or rollback.
    */
    function begin()
    {
        $ini = eZINI::instance();
        if ($ini->variable( "DatabaseSettings", "Transactions" ) == "enabled")
        {
            if ( $this->TransactionCounter > 0 )
            {
                if ( is_array( $this->TransactionStackTree ) )
                {
                    // Make a new sub-level for debugging
                    $bt = debug_backtrace();
                    $subLevels =& $this->TransactionStackTree['sub_levels'];
                    for ( $i = 1; $i < $this->TransactionCounter; ++$i )
                    {
                        $subLevels =& $subLevels[count( $subLevels ) - 1]['sub_levels'];
                    }
                    // Next entry will be at the end
                    $subLevels[count( $subLevels )] = array( 'level' => $this->TransactionCounter,
                                                             'trace' => $bt,
                                                             'sub_levels' => array() );
                }
                ++$this->TransactionCounter;
                return false;
            }
            else
            {
                if ( is_array( $this->TransactionStackTree ) )
                {
                    // Start new stack tree for debugging
                    $bt = debug_backtrace();
                    $this->TransactionStackTree = array( 'level' => $this->TransactionCounter,
                                                         'trace' => $bt,
                                                         'sub_levels' => array() );
                }
            }
            $this->TransactionIsValid = true;

            if ( $this->isConnected() )
            {
                $oldRecordError = $this->RecordError;
                // Turn off error handling while we begin
                $this->RecordError = false;
                $this->beginQuery();
                $this->RecordError = $oldRecordError;

                // We update the transaction counter after the query, otherwise we
                // mess up the debug background highlighting.
                ++$this->TransactionCounter;
            }
        }
        return true;
    }

    /*!
      \virtual
      The query to start a transaction.
      This function must be reimplemented in the subclasses.
    */
     function beginQuery()
    {
        return false;
    }

    /*!
      Commits the current transaction. If this is not the outermost it will not commit
      to the database immediately but instead decrease the transaction counter.

      If the current transaction had any errors in it the transaction will be rollbacked
      instead of commited. This ensures that the database is in a valid state.
      Also the PHP execution will be stopped.

      \return \c true if the transaction was successful, \c false otherwise.
    */
    function commit()
    {
        $ini = eZINI::instance();
        if ($ini->variable( "DatabaseSettings", "Transactions" ) == "enabled")
        {
            if ( $this->TransactionCounter <= 0 )
            {
                eZDebug::writeError( 'No transaction in progress, cannot commit', 'eZDBInterface::commit' );
                return false;
            }

            --$this->TransactionCounter;
            if ( $this->TransactionCounter == 0 )
            {
                if ( is_array( $this->TransactionStackTree ) )
                {
                    // Reset the stack debug tree since the top commit was done
                    $this->TransactionStackTree = array();
                }
                if ( $this->isConnected() )
                {
                    // Check if we have encountered any problems, if so we have to rollback
                    if ( !$this->TransactionIsValid )
                    {
                        $oldRecordError = $this->RecordError;
                        // Turn off error handling while we rollback
                        $this->RecordError = false;
                        $this->rollbackQuery();
                        $this->RecordError = $oldRecordError;

                        return false;
                    }
                    else
                    {
                        $oldRecordError = $this->RecordError;
                        // Turn off error handling while we commit
                        $this->RecordError = false;
                        $this->commitQuery();
                        $this->RecordError = $oldRecordError;
                    }
                }
            }
            else
            {
                if ( is_array( $this->TransactionStackTree ) )
                {
                    // Close the last open nested transaction
                    $bt = debug_backtrace();
                    // Store commit trace
                    $subLevels =& $this->TransactionStackTree['sub_levels'];
                    for ( $i = 1; $i < $this->TransactionCounter; ++$i )
                    {
                        $subLevels =& $subLevels[count( $subLevels ) - 1]['sub_levels'];
                    }
                    // Find last entry and add the commit trace
                    $subLevels[count( $subLevels ) - 1]['commit_trace'] = $bt;
                }
            }
        }
        return true;
    }

    /*!
      \virtual
      The query to commit the transaction.
      This function must be reimplemented in the subclasses.
    */
    function commitQuery()
    {
        return false;
    }

    /*!
      Cancels the transaction.
    */
    function rollback()
    {
        if ( is_array( $this->TransactionStackTree ) )
        {
            // All transactions were rollbacked, reset the tree.
            $this->TransactionStackTree = array();
        }
        $ini = eZINI::instance();
        if ($ini->variable( "DatabaseSettings", "Transactions" ) == "enabled")
        {
            if ( $this->TransactionCounter <= 0 )
            {
                eZDebug::writeError( 'No transaction in progress, cannot rollback', 'eZDBInterface::rollback' );
                return false;
            }
            // Reset the transaction counter
            $this->TransactionCounter = 0;
            if ( $this->isConnected() )
            {
                $oldRecordError = $this->RecordError;
                // Turn off error handling while we rollback
                $this->RecordError = false;
                $this->rollbackQuery();
                $this->RecordError = $oldRecordError;
            }
        }
        return true;
    }

    /*!
      Goes through the transaction stack tree $this->TransactionStackTree and
      generates the text output for it and returns it.
      \returns The generated string or false if it is disabled.
    */
    function generateFailedTransactionStack()
    {
        if ( !$this->TransactionStackTree )
        {
            return false;
        }
        return $this->generateFailedTransactionStackEntry( $this->TransactionStackTree, 0 );
    }

    /*!
     \private
     Recursive helper function for generating stack tree output.
     \returns The generated string
     */
    function generateFailedTransactionStackEntry( $stack, $indentCount )
    {
        $stackText = '';
        $indent = '';
        if ( $indentCount > 0 )
        {
            $indent = str_repeat( " ", $indentCount*4 );
        }
        $stackText .= $indent . "Level " . $stack['level'] . "\n" . $indent . "{" . $indent . "\n";
        $stackText .= $indent . "  Began at:\n";
        for ( $i = 0; $i < 2 && $i < count( $stack['trace'] ); ++$i )
        {
            $indent2 = str_repeat( "  ", $i + 1 );
            if ( $i > 0 )
            {
                $indent2 .= "->";
            }
            $stackText .= $indent . $indent2 . $this->generateTraceEntry( $stack['trace'][$i] );
            $stackText .= "\n";
        }
        foreach ( $stack['sub_levels'] as $subStack )
        {
            $stackText .= $this->generateFailedTransactionStackEntry( $subStack, $indentCount + 1 );
        }
        if ( isset( $stack['commit_trace'] ) )
        {
            $stackText .= $indent . "  And commited at:\n";
            for ( $i = 0; $i < 2 && $i < count( $stack['commit_trace'] ); ++$i )
            {
                $indent2 = str_repeat( "  ", $i + 1 );
                if ( $i > 0 )
                {
                    $indent2 .= "->";
                }
                $stackText .= $indent . $indent2 . $this->generateTraceEntry( $stack['commit_trace'][$i] );
                $stackText .= "\n";
            }
        }
        $stackText .= $indent . "}" . "\n";
        return $stackText;
    }

    /*!
     \private
     Helper function for generating output for one stack-trace entry.
     \returns The generated string
     */
    function generateTraceEntry( $entry )
    {
        if ( isset( $entry['file'] ) )
        {
            $stackText = $entry['file'];
        }
        else
        {
            $stackText = "???";
        }
        $stackText .= ":";
        if ( isset( $entry['line'] ) )
        {
            $stackText .= $entry['line'];
        }
        else
        {
            $stackText .= "???";
        }
        $stackText .= " ";
        if ( isset( $entry['class'] ) )
        {
            $stackText .= $entry['class'];
        }
        else
        {
            $stackText .= "???";
        }
        $stackText .= "::";
        if ( isset( $entry['function'] ) )
        {
            $stackText .= $entry['function'];
        }
        else
        {
            $stackText .= "???";
        }
        return $stackText;
    }

    /*!
      \virtual
      The query to cancel the transaction.
      This function must be reimplemented in the subclasses.
    */
    function rollbackQuery()
    {
        return false;
    }

    /*!
      Invalidates the current transaction, see commit() for more details on this.
      \return \c true if it was invalidated or \c false if there is no transaction to invalidate.

      \sa isTransactionValid()
    */
    function invalidateTransaction()
    {
        if ( $this->TransactionCounter <= 0 )
            return false;
        $this->TransactionIsValid = false;
        return true;
    }

    /*!
     \protected
     This is called whenever an error occurs in one of the database handlers.
     If a transaction is active it will be invalidated as well.
    */
    function reportError()
    {
        // If we have a running transaction we must mark as invalid
        // in which case a call to commit() will perform a rollback
        if ( $this->TransactionCounter > 0 )
        {
            $this->invalidateTransaction();

            // This is the unique ID for this incidence which will also be placed in the error logs.
            $transID = 'TRANSID-' . md5( time() . mt_rand() );

            eZDebug::writeError( 'Transaction in progress failed due to DB error, transaction was rollbacked. Transaction ID is ' . $transID . '.', 'eZDBInterface::commit ' . $transID );

            $oldRecordError = $this->RecordError;
            // Turn off error handling while we rollback
            $this->RecordError = false;
            $this->rollbackQuery();
            $this->RecordError = $oldRecordError;

            // Stop execution immediately while allowing other systems (session etc.) to cleanup
            require_once( 'lib/ezutils/classes/ezexecution.php' );
            eZExecution::cleanup();
            eZExecution::setCleanExit();

            // Give some feedback, and also possibly show the debug output
            eZDebug::setHandleType( eZDebug::HANDLE_NONE );

            $ini = eZINI::instance();
            $adminEmail = $ini->variable( 'MailSettings', 'AdminEmail' );
            //include_once( 'lib/ezutils/classes/ezsys.php' );

            if ( !eZSys::isShellExecution() )
            {
                $site = eZSys::serverVariable( 'HTTP_HOST' );
                $uri = eZSys::serverVariable( 'REQUEST_URI' );

                print( "<div class=\"fatal-error\" style=\"" );
                print( 'margin: 0.5em 0 1em 0; ' .
                       'padding: 0.25em 1em 0.75em 1em;' .
                       'border: 4px solid #000000;' .
                       'background-color: #f8f8f4;' .
                       'border-color: #f95038;" >' );
                print( "<b>Fatal error</b>: A database transaction in eZ Publish failed.<br/>" );
                print( "<p>" );
                print( "The current execution was stopped to prevent further problems.<br/>\n" .
                       "You should contact the <a href=\"mailto:$adminEmail?subject=Transaction failed on $site and URI $uri with ID $transID\">System Administrator</a> of this site with the information on this page.<br/>\n" .
                       "The current transaction ID is <b>$transID</b> and has been logged.<br/>\n" .
                       "Please include the transaction ID and the current URL when contacting the system administrator.<br/>\n" );
                print( "</p>" );
                print( "</div>" );

                $templateResult = null;
                if ( function_exists( 'eZDisplayResult' ) )
                {
                    eZDisplayResult( $templateResult );
                }
            }
            else
            {
                fputs( STDERR,"Fatal error: A database transaction in eZ Publish failed.\n" );
                fputs( STDERR, "\n" );
                fputs( STDERR, "The current execution was stopped to prevent further problems.\n" .
                       "You should contact the System Administrator ($adminEmail) of this site.\n" .
                       "The current transaction ID is $transID and has been logged.\n" .
                       "Please include the transaction ID and the name of the current script when contacting the system administrator.\n" );
                fputs( STDERR, "\n" );

                fputs( STDERR, eZDebug::printReport( false, false, true ) );
            }

            // PHP execution stops here
            exit( 1 );
        }
    }

    /*!
      \return \c true if the current or last running transaction was valid,
              \c false otherwise.
      \sa invalidateTransaction()
    */
    function isTransactionValid()
    {
        return $this->TransactionIsValid;
    }

    /*!
     \return The current transaction counter.

     0 means no transactions, 1 or higher means 1 or more transactions
     are running and a negative value means something is wrong.
    */
    function transactionCounter()
    {
        return $this->TransactionCounter;
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
    function relationCount( $relationType = eZDBInterface::RELATION_TABLE )
    {
    }

    /*!
     \pure
     \return existing ez publish tables in database
    */
    function eZTableList( $server = self::SERVER_MASTER )
    {
    }

    /*!
      \pure
      \return the relation names in the database as an array for the relation type \a $relationType.
    */
    function relationList( $relationType = eZDBInterface::RELATION_TABLE )
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
        $names = array( eZDBInterface::RELATION_TABLE => 'TABLE',
                        eZDBInterface::RELATION_SEQUENCE => 'SEQUENCE',
                        eZDBInterface::RELATION_TRIGGER => 'TRIGGER',
                        eZDBInterface::RELATION_VIEW => 'VIEW',
                        eZDBInterface::RELATION_INDEX => 'INDEX' );
        if ( !isset( $names[$relationType] ) )
            return false;
        return $names[$relationType];
    }

    /*!
     \pure
     \return A regexp (PCRE) that can be used to filter out certain relation elements.
             If no special regexp is provided it will return \c false.
     \param $relationType The type which needs to be filtered, this allows one regexp per type.

     An example, will only match tables that start with 'ez'.
     \code
     return "#^ez#";
     \endcode

     \note This function is currently used by the eZDBTool class to remove relation elements
           of a specific kind (Most likely eZ Publish related elements).
    */
    function relationMatchRegexp( $relationType )
    {
        return false;
    }

    /*!
      Casts elements of \a $pieces to type \a $type and returns them as string separated by \a $glue.

      \param $glue The separator.
             $pieces The array containing the elements.
             $type The type to cast to.

      Example:
      \code
      implodeWithTypeCast( ',', $myArray, 'int' )
      \endcode

    */
    function implodeWithTypeCast( $glue, &$pieces, $type )
    {
        $str = '';
        if ( !is_array( $pieces ) )
            return $str;

        foreach( $pieces as $piece )
        {
            settype( $piece, $type );
            $str .= $piece.$glue;
        }
        $str = rtrim( $str, $glue );
        return $str;
    }

    /*!
      \pure
      Returns the last serial ID generated with an auto increment field.
    */
    function lastSerialID( $table = false, $column = false )
    {
    }

    /*!
      \pure
      Will escape a string so it's ready to be inserted in the database.
    */
    function escapeString( $str )
    {
        return $str;
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
    function createDatabase( $dbName )
    {
    }

    /*!
      \pure
      Removes a database
    */
    function removeDatabase( $dbName )
    {
    }

    /*!
      Create a new temporary table
    */
    function createTempTable( $createTableQuery = '', $server = self::SERVER_SLAVE )
    {
        $this->query( $createTableQuery, $server );
    }

    /*!
      Drop temporary table
    */
    function dropTempTable( $dropTableQuery = '', $server = self::SERVER_SLAVE )
    {
        $this->query( $dropTableQuery, $server );
    }

    /*!
      Drop temporary table list
    */
    function dropTempTableList( $tableList, $server = self::SERVER_SLAVE )
    {
        foreach( $tableList as $tableName )
            $this->dropTempTable( "DROP TABLE $tableName", $server );
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
     Generate unique table name basing on the given pattern.
     If the pattern contains a (%) character then the character
     is replaced with a part providing uniqueness (e.g. random number).
    */
    function generateUniqueTempTableName( $pattern, $randomizeIndex = false, $server = self::SERVER_SLAVE )
    {
        $tableList = array_keys( $this->eZTableList( $server ) );
        if ( $randomizeIndex === false )
        {
            $randomizeIndex = rand( 10, 1000 );
        }
        do
        {
            $uniqueTempTableName = str_replace( '%', $randomizeIndex, $pattern );
            $randomizeIndex++;
        } while ( in_array( $uniqueTempTableName, $tableList ) );

        return $uniqueTempTableName;
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

    /*!
     \static

     This function can be used to create a SQL IN statement to be used in a WHERE clause:

     WHERE columnName IN ( element1, element2, ... )

     By default, the elements that can be submitted as an anonymous array (or an integer value
     in case of a single element) will just be imploded. Drivers for databases with a limitation
     of the elements within an IN statement have to reimplement this function. It is also possible
     to negate the "IN" to a "NOT IN" by using the last parameter of this function.

     Usage:

     $db =& eZDb::instance();
     $db->generateSQLINStatement( array( 2, 5, 43, ) );

     \param $elements   Elements that should (not) be matched by the IN statment as an integer or anonymous array
     \param $columnName Column name of the database table the IN statement should be created for
     \param $not        Will generate a "NOT IN" ( if set to \c true ) statement instead of an "IN" ( if set to
                        \c false , default )
     \param $unique
     \param $type      The type to cast the array elements to

     \return A string with the correct IN statement like for example
             "columnName IN ( element1, element2 )"
     */
    function generateSQLINStatement( $elements, $columnName = '', $not = false, $unique = true, $type = false )
    {
        $result    = '';
        $statement = $columnName . ' IN';
        if ( $not === true )
        {
            $statement = $columnName . ' NOT IN';
        }

        if ( !is_array( $elements ) )
        {
            $elements = array( $elements );
        }

        $impString = $type ? $this->implodeWithTypeCast( ', ', $elements, $type ) : implode( ', ', $elements );
        $result = $statement . ' ( ' . $impString . ' )';

        return $result;
    }

    /// \protectedsection
    /// Contains the current server
    public $Server;
    /// Contains the current port
    public $Port;
    /// The socket path, used by MySQL
    public $SocketPath;
    /// The current database name
    public $DB;
    /// The current connection, \c false if not connection has been made
    public $DBConnection;
    /// Contains the write database connection if used
    public $DBWriteConnection;
    /// Stores the database connection user
    public $User;
    /// Stores the database connection password
    public $Password;
    /// The charset used for the current database
    public $Charset;
    /// The number of times to retry a connection if it fails
    public $ConnectRetries;
    /// Instance of a textcodec which handles text conversion, may not be set if no builtin encoding is used
    public $OutputTextCodec;
    public $InputTextCodec;

    /// True if a builtin encoder is to be used, this means that all input/output text is converted
    public $UseBuiltinEncoding;
    /// Setting if SQL queries should be sent to debug output
    public $OutputSQL;
    /// Contains true if we're connected to the database backend
    public $IsConnected = false;
    /// Contains number of queries sended to DB
    public $NumQueries = 0;
    /// The start time of the timer
    public $StartTime;
    /// The end time of the tiemr
    public $EndTime;
    /// The total number of milliseconds the timer took
    public $TimeTaken;
    /// The database error message of the last executed function
    public $ErrorMessage;
    /// The database error message number of the last executed function
    public $ErrorNumber = 0;
    /// If true then ErrorMessage and ErrorNumber get filled
    public $RecordError = true;
    /// If true then the database connection should be persistent
    public $UsePersistentConnection = false;
    /// Contains true if slave servers are enabled
    public $UserSlaveServer;
    /// The slave database name
    public $SlaveDB;
    /// The slave server name
    public $SlaveServer;
    /// The slave server port
    public $SlavePort;
    /// The slave database user
    public $SlaveUser;
    /// The slave database user password
    public $SlavePassword;
    /// The transaction counter, 0 means no transaction
    public $TransactionCounter;
    /// Flag which tells if a transaction is considered valid or not
    /// A transaction will be made invalid if SQL errors occur
    public $TransactionIsValid;
}

?>

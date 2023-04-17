<?php
/**
 * File containing the eZDBInterface class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/**
 * The eZDBInterface defines the interface for all database implementations
 *
 * @todo Convert methods and variables marked as protected/private to protected/private methods and variables
 *
 * @package lib
 * @subpackage eZDB
 */
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

    /**
     * Creates a new eZDBInterface object and connects to the database backend.
     *
     * @param array $parameters
     */
    public function __construct( $parameters )
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

    /**
     * Returns the available attributes for this database handler.
     *
     * @return array
     */
    function attributes()
    {
        return array_keys( $this->AttributeVariableMap );
    }

    /**
     * Returns true if the attribute $name exists for this database handler.
     *
     * @param string $name
     * @return bool
     */
    function hasAttribute( $name )
    {
        if ( isset( $this->AttributeVariableMap[$name] ) )
        {
            return true;
        }
        return false;
    }

    /**
     * Returns the value of the attribute $name if it exists, otherwise null.
     *
     * @param string $name
     * @return null
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
            eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
            return null;
        }
    }

    /**
     * Checks if the requested character set matches the one used in the database.
     *
     * The default is to always return true, see the specific database handler for more information.
     *
     * @param string|array $charset
     * @param string $currentCharset The charset that the database uses, will only be set if the match fails.
     * @return bool true if it matches or false if it differs.
     */
    function checkCharset( $charset, &$currentCharset )
    {
        return true;
    }

    /**
     * Prepare the sql file so we can create the database.
     *
     * @access private
     * @param resource $fd The file descriptor
     * @param string $buffer Reference to string buffer for SQL queries.
     * @return array
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
                // eZDebug::writeDebug( $buffer, "read data" );
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

    /**
     * Inserts the SQL file $sqlFile found in the path $path into the currently connected database.
     *
     * @param string $path
     * @param string $sqlFile
     * @param bool $usePathType
     * @return bool true if succesful.
     */
    function insertFile( $path, $sqlFile, $usePathType = true )
    {
        $type = $this->databaseName();

        if ( $usePathType )
            $sqlFileName = eZDir::path( array( $path, $type, $sqlFile ) );
        else
            $sqlFileName = eZDir::path( array( $path, $sqlFile ) );
        if ( !file_exists( $sqlFileName ) )
        {
            eZDebug::writeError( "File not found: $sqlFileName", __METHOD__ );
            return false;
        }
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

    /**
     * Writes a debug notice with query information.
     *
     * @access private
     * @param string $class
     * @param string $sql
     * @param int|string $numRows
     * @param int|string $timeTaken
     * @param bool $asDebug
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

    /**
     * Enabled or disables sql output.
     *
     * @param bool $enabled
     */
    function setIsSQLOutputEnabled( $enabled )
    {
        $this->OutputSQL = $enabled;
    }

    /**
     * Records the current micro time. End the timer with endTimer() and fetch the result with timeTaken();
     *
     * @access private
     */
    function startTimer()
    {
        $this->StartTime = microtime( true );
    }

    /**
     * Stops the current timer and calculates the time taken.
     *
     * @access private
     */
    function endTimer()
    {
        $this->EndTime = microtime( true );
        // Calculate time taken in ms
        $this->TimeTaken = $this->EndTime - $this->StartTime;
        $this->TimeTaken *= 1000.0;
    }

    /**
     * Returns the micro time when the timer was start or false if no timer.
     *
     * @access private
     * @return bool|float
     */
    function startTime()
    {
        return $this->StartTime;
    }

    /**
     * Returns the micro time when the timer was ended or false if no timer.
     *
     * @access private
     * @return bool|float
     */
    function endTime()
    {
        return $this->EndTime;
    }

    /**
     * Returns the number of milliseconds the last operation took or false if no value.
     *
     * @access private
     * @return bool|float
     */
    function timeTaken()
    {
        return $this->TimeTaken;
    }

    /**
     * Returns the name of driver, this is used to determine the name of the database type.
     *
     * For instance multiple implementations of the MySQL database will all return 'mysql'.
     *
     * @return string
     */
    function databaseName()
    {
        return '';
    }

    /**
     * Returns the socket path for the database or false if no socket path was defined.
     *
     * @return string|bool
     */
    function socketPath()
    {
        return $this->SocketPath;
    }

    /**
     * Returns the number of times the db handler should try to reconnect if it fails.
     *
     * @return int
     */
    function connectRetryCount()
    {
        return $this->ConnectRetries;
    }

    /**
     * Returns the number of seconds the db handler should wait before rereconnecting.
     *
     * @return int
     */
    function connectRetryWaitTime()
    {
        return 3;
    }

    /**
     * Returns a mask of the relation type it supports.
     *
     * @return int
     */
    function supportedRelationTypeMask()
    {
        return eZDBInterface::RELATION_NONE;
    }

    /**
     * Returns if the short column names should be used insted of default ones
     *
     * @return bool
     */
    function useShortNames()
    {
        return false;
    }

    /**
     * Returns an array of the relation types.
     * @return array
     */
    function supportedRelationTypes()
    {
        return array();
    }

    /**
     * Returns a sql-expression(string) to get substring.
     *
     * @param string $string
     * @param int $from
     * @param int $len
     * @return string
     */
    function subString( $string, $from, $len = null )
    {
        return '';
    }

    /**
     * Returns a sql-expression(string) to concatenate strings.
     *
     * @param array $strings
     * @return string
     */
    function concatString( $strings = array() )
    {
        return '';
    }

    /**
     * Returns a sql-expression(string) to generate a md5 sum of the string.
     *
     * @param string $str
     * @return string
     */
    function md5( $str )
    {
        return '';
    }

    /**
     * Returns a sql-expression(string) to generate a bit and  of the argument.
     *
     * @param string $arg1
     * @param string $arg2
     * @return string
     */
    function bitAnd( $arg1, $arg2 )
    {
        return '(' . $arg1 . ' & ' . $arg2 . ' ) ';
    }

    /**
     * Returns a sql-expression(string) to generate a bit and  of the argument.
     *
     * @param string $arg1
     * @param string $arg2
     * @return string
     */
    function bitOr( $arg1, $arg2 )
    {
        return '( ' . $arg1 . ' | ' . $arg2 . ' ) ';
    }

    /**
     * Checks if the version number of the server is equal or larger than $minVersion.
     *
     * Will also check if the database type is correct if $name is set.
     *
     * @param string $minVersion A string denoting the min. required version.
     * @param string|bool $name The name of the database type it requires or false if it does not matter.
     * @return bool true if the server fulfills the requirements.
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

    /**
     * Returns the version of the database server or false if no version could be retrieved/
     *
     * @return string|bool
     */
    function databaseServerVersion()
    {
    }

    /**
     * Returns the version of the database client or false if no version could be retrieved/
     *
     * @return string|bool
     */
    function databaseClientVersion()
    {
    }

    /**
     * Returns true if the charset $charset is supported by the connected database.
     *
     * @param string $charset
     * @return bool
     */
    function isCharsetSupported( $charset )
    {
        return false;
    }

    /**
     * Returns the charset which the database is encoded in.
     *
     * @see usesBuiltinEncoding()
     * @return string
     */
    function charset()
    {
        return $this->Charset;
    }

    /**
     * Returns true if the database handles encoding itself. If not, all queries and returned data must be decoded yourselves.
     *
     * This functionality might be removed in the future
     *
     * @return bool
     */
    function usesBuiltinEncoding()
    {
        return $this->UseBuiltinEncoding;
    }

    /**
     * Returns type of binding used in database plugin.
     *
     * @return int
     */
    function bindingType()
    {
    }

    /**
     * Binds variable.
     *
     * @param mixed $value
     * @param mixed $fieldDef
     * @return mixed
     */
    function bindVariable( $value, $fieldDef = false )
    {
    }

    /**
     * Execute a query on the global MySQL database link.  If it returns an error, the script is halted and the
     * attempted SQL query and MySQL error message are printed.
     *
     * @param string $sql SQL query to execute.
     * @param int|bool $server
     * @return mixed
     */
    function query( $sql, $server = false )
    {
    }

    /**
     * Executes an SQL query and returns the result as an array of accociative arrays.
     *
     * Example:
     * <code>
     * $db->arrayQuery( 'SELECT * FROM eztable', array( 'limit' => 10, 'offset' => 5 ) );
     * </code>
     *
     * @param string $sql SQL query to execute.
     * @param array $params Associative array containing extra parameters, can contain:
     *                      - offset -> The offset of the query.
     *                      - limit -> The limit of the query.
     *                      - column - Limit returned row arrays to only contain this column.
     * @param int|bool $server Which server to execute the query on, either eZDBInterface::SERVER_MASTER or eZDBInterface::SERVER_SLAVE
     * @return array
     */
    function arrayQuery( $sql, $params = array(), $server = false )
    {
    }

    /**
     * Locks one or several tables
     *
     * @param string|array $table
     */
    function lock( $table )
    {
    }

    /**
     * Releases table locks.
     *
     * @return void
     */
    function unlock()
    {
    }

    /**
     * Begin a new transaction.
     *
     * If we are already in transaction then we omit this new transaction and its matching commit or rollback.
     *
     * @return bool
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

    /**
     * The query to start a transaction.
     *
     * This function must be reimplemented in the subclasses.
     *
     * @return bool
     */
     function beginQuery()
    {
        return false;
    }

    /**
     * Commits the current transaction. If this is not the outermost it will not commit
     * to the database immediately but instead decrease the transaction counter.
     *
     * If the current transaction had any errors in it the transaction will be rollbacked
     * instead of commited. This ensures that the database is in a valid state.
     * Also the PHP execution will be stopped.
     *
     * @return bool true if the transaction was successful, false otherwise.
     */
    function commit()
    {
        $ini = eZINI::instance();
        if ($ini->variable( "DatabaseSettings", "Transactions" ) == "enabled")
        {
            if ( $this->TransactionCounter <= 0 )
            {
                eZDebug::writeError( 'No transaction in progress, cannot commit', __METHOD__ );
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

    /**
     * The query to commit the transaction.
     *
     * This function must be reimplemented in the subclasses.
     *
     * @return bool
     */
    function commitQuery()
    {
        return false;
    }

    /**
     * Cancels the transaction.
     *
     * @return bool
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
                eZDebug::writeError( 'No transaction in progress, cannot rollback', __METHOD__ );
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

    */
    /**
     * Goes through the transaction stack tree {@see eZDBInterface::$TransactionStackTree},
     * generates the text output for it and returns it.
     *
     * @return bool|string The generated string or false if it is disabled.
     */
    function generateFailedTransactionStack()
    {
        if ( !$this->TransactionStackTree )
        {
            return false;
        }
        return $this->generateFailedTransactionStackEntry( $this->TransactionStackTree, 0 );
    }

    /**
     * Recursive helper function for generating stack tree output.
     *
     * @access private
     * @param array $stack
     * @param int $indentCount
     * @return string The generated string
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

    /**
     * Helper function for generating output for one stack-trace entry.
     *
     * @access private
     * @param array $entry
     * @return string The generated string
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

    /**
     * The query to cancel the transaction.
     *
     * This function must be reimplemented in the subclasses.
     *
     * @return bool
     */
    function rollbackQuery()
    {
        return false;
    }

    /**
     * Invalidates the current transaction, see {@see commit()} for more details on this.
     *
     * @see isTransactionValid()
     * @return bool true if it was invalidated or false if there is no transaction to invalidate.
     */
    function invalidateTransaction()
    {
        if ( $this->TransactionCounter <= 0 )
            return false;
        $this->TransactionIsValid = false;
        return true;
    }

    /**
     * This is called whenever an error occurs in one of the database handlers.
     *
     * If a transaction is active it will be invalidated as well.
     *
     * @access protected
     * @throws eZDBException
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

            $this->rollback();

            if ( $this->errorHandling == eZDB::ERROR_HANDLING_EXCEPTIONS )
            {
                throw new eZDBException( $this->ErrorMessage, $this->ErrorNumber );
            }
            else
            {
                // Stop execution immediately while allowing other systems (session etc.) to cleanup
                eZExecution::cleanup();
                eZExecution::setCleanExit();

                // Give some feedback, and also possibly show the debug output
                eZDebug::setHandleType( eZDebug::HANDLE_NONE );

                $ini = eZINI::instance();
                $adminEmail = $ini->variable( 'MailSettings', 'AdminEmail' );
                if ( !eZSys::isShellExecution() )
                {
                    if ( !headers_sent() )
                    {
                        header("HTTP/1.1 500 Internal Server Error");
                    }
                    $site = htmlentities(eZSys::serverVariable( 'HTTP_HOST' ), ENT_QUOTES);
                    $uri = htmlentities(eZSys::serverVariable( 'REQUEST_URI' ), ENT_QUOTES);

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
    }

    /**
     * Returns if the current or last running transaction is valid
     *
     * @see invalidateTransaction()
     * @return bool true if the current or last running transaction was valid, false otherwise
     */
    function isTransactionValid()
    {
        return $this->TransactionIsValid;
    }

    /**
     * Returns the current transaction counter.
     *
     * 0 means no transactions, 1 or higher means 1 or more transactions are running and
     * a negative value means something is wrong.
     *
     * @return int
     */
    function transactionCounter()
    {
        return $this->TransactionCounter;
    }

    /**
     * Returns the relation count for all relation types in the mask $relationMask.
     *
     * @param int $relationMask
     * @return int
     */
    function relationCounts( $relationMask )
    {
    }

    /**
     * Returns the number of relation objects in the database for the relation type $relationType.
     *
     * @param int $relationType
     * @return int
     */
    function relationCount( $relationType = eZDBInterface::RELATION_TABLE )
    {
    }

    /**
     * Returns the existing ez publish tables in database
     *
     * @param int $server
     * @return array
     */
    function eZTableList( $server = self::SERVER_MASTER )
    {
    }

    /**
     * Returns the relation names in the database as an array for the relation type $relationType.
     *
     * @param int $relationType
     * @return array
     */
    function relationList( $relationType = eZDBInterface::RELATION_TABLE )
    {
    }

    /**
     * Tries to remove the relation type $relationType named $relationName
     *
     * @param string $relationName
     * @param int $relationType
     * @return bool true if successful
     */
    function removeRelation( $relationName, $relationType )
    {
        return false;
    }

    /**
     * Returns the name of the relation type which is usable in SQL or false if unknown type.
     *
     * This function can be used by some database handlers which can operate on relation types using SQL.
     *
     * @access protected
     * @param int $relationType
     * @return bool
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

    /**
     * Return A regexp (PCRE) that can be used to filter out certain relation elements.
     * If no special regexp is provided it will return false.
     *
     * An example, will only match tables that start with 'ez'.
     * <code>
     * return "#^ez#";
     * </code>
     *
     * This function is currently used by the eZDBTool class to remove relation elements of a specific kind
     * (Most likely eZ Publish related elements).
     *
     * @param int $relationType The type which needs to be filtered, this allows one regexp per type.
     * @return bool
     */
    function relationMatchRegexp( $relationType )
    {
        return false;
    }

    /**
     * Casts elements of $pieces to type $type and returns them as string separated by $glue.
     *
     * Use {@see generateSQLINStatement()} if you intend to use this for IN statments!
     * Example:
     * <code>
     * $this->implodeWithTypeCast( ',', $myArray, 'int' );
     * </code>
     *
     * @param string $glue The separator.
     * @param array $pieces The array containing the elements.
     * @param string $type The type to cast to.
     * @return string
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

    /**
     * Returns the last serial ID generated with an auto increment field.
     *
     * @param string|bool $table
     * @param string|bool $column
     * @return int|bool The most recent value for the sequence
     */
    function lastSerialID( $table = false, $column = false )
    {
    }

    /**
     * Will escape a string so it's ready to be inserted in the database.
     *
     * @param string $str
     * @return string mixed
     */
    function escapeString( $str )
    {
        return $str;
    }

    /**
     * Will close the database connection.
     *
     * @return void
     */
    function close()
    {
    }

    /**
     * Returns true if we're connected to the database backend.
     *
     * @access protected
     * @return bool
     */
    function isConnected()
    {
        return $this->IsConnected;
    }

    /**
     * Create a new database
     *
     * @param string $dbName
     * @return void
     */
    function createDatabase( $dbName )
    {
    }

    /**
     * Removes a database
     *
     * @param string $dbName
     * @return void
     */
    function removeDatabase( $dbName )
    {
    }

    /**
     * Create a new temporary table
     *
     * @param string $createTableQuery
     * @param int $server
     */
    function createTempTable( $createTableQuery = '', $server = self::SERVER_SLAVE )
    {
        $this->query( $createTableQuery, $server );
    }

    /**
     * Drop temporary table
     *
     * @param string $dropTableQuery
     * @param int $server
     * @return void
     */
    function dropTempTable( $dropTableQuery = '', $server = self::SERVER_SLAVE )
    {
        $this->query( $dropTableQuery, $server );
    }

    /**
     * Drop temporary table list
     *
     * @param array $tableList
     * @param int $server
     */
    function dropTempTableList( $tableList, $server = self::SERVER_SLAVE )
    {
        foreach( $tableList as $tableName )
            $this->dropTempTable( "DROP TABLE $tableName", $server );
    }

    /**
     * Sets the error message and error message number
     *
     * @return void
     */
    function setError()
    {
    }

    /**
     * Returns the error message
     *
     * @return string
     */
    function errorMessage()
    {
        return $this->ErrorMessage;
    }

    /**
     * Returns the error number
     *
     * @return int
     */
    function errorNumber()
    {
        return $this->ErrorNumber;
    }

    /**
     * Returns an array of available databases in the database, null of none available,
     * false if listing databases not supported by database
     *
     * @return array|null|bool
     */
    function availableDatabases()
    {
        return false;
    }

    /*!



    */
    /**
     * Generate unique table name basing on the given pattern.
     *
     * If the pattern contains a (%) character then the character
     * is replaced with a part providing uniqueness (e.g. random number).
     *
     * @param string $pattern
     * @param bool $randomizeIndex
     * @param int $server
     * @return string
     */
    function generateUniqueTempTableName( $pattern, $randomizeIndex = false, $server = self::SERVER_SLAVE )
    {
        $tableList = array_keys( $this->eZTableList( $server ) );
        if ( $randomizeIndex === false )
        {
            $randomizeIndex = mt_rand( 10, 1000 );
        }
        do
        {
            $uniqueTempTableName = str_replace( '%', $randomizeIndex, $pattern );
            $randomizeIndex++;
        } while ( in_array( $uniqueTempTableName, $tableList ) );

        return $uniqueTempTableName;
    }

    /**
     * Get database version number
     *
     * @return string|bool Version number, false if not supported
     */
    function version()
    {
        return false;
    }

    /**
     * This function can be used to create a SQL IN statement to be used in a WHERE clause:
     *
     * WHERE columnName IN ( element1, element2, ... )
     * By default, the elements that can be submitted as an anonymous array (or an integer value
     * in case of a single element) will just be imploded. Drivers for databases with a limitation
     * of the elements within an IN statement have to reimplement this function. It is also possible
     * to negate the "IN" to a "NOT IN" by using the last parameter of this function.
     *
     * Usage:
     *
     * $db =& eZDB::instance();
     * $db->generateSQLINStatement( array( 2, 5, 43, ) );
     *
     * @param int|array $elements
     *        Elements that should (not) be matched by the IN statment as an
     *        integer or anonymous array
     * @param string $columnName
     *        Column name of the database table the IN statement should be
     *        created for
     * @param bool $not
     *        Will generate a "NOT IN" ( if set to true ) statement instead
     *        of an "IN" ( if set to false , default )
     * @param $unique
     *        Wether or not to make the array unique. Not implemented in this
     *        class, but can be used by extending classes (oracle does use it)
     * @param $type The type to cast the array elements to
     *
     * @return string A string with the correct IN statement like for example
     *         "columnName IN ( element1, element2 )"
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

    /**
     * Returns true if the database handler support the insertion of default values, false if not
     *
     * @return bool
     */
    function supportsDefaultValuesInsertion()
    {
        return true;
    }

    /**
     * Sets the eZDB error handling mode
     * @param int $errorHandling
     *        Possible values are:pm
     *        - eZDB::ERROR_HANDLING_STANDARD: backward compatible error handling, using reportError
     *        - eZDB::ERROR_HANDLING_EXCEPTION: using exceptions
     * @since 4.5
     */
    public function setErrorHandling( $errorHandling )
    {
        if ( $errorHandling != eZDB::ERROR_HANDLING_EXCEPTIONS &&
             $errorHandling != eZDB::ERROR_HANDLING_STANDARD )
            throw new RuntimeException( "Unknown eZDB error handling mode $errorHandling" );

        $this->errorHandling = $errorHandling;
    }

    /**
     * Truncates a $string to a $maxLength
     *
     * This method is meant to be overridden if needed by different DB implementation:
     * for example oracle handles bytes and not char
     *
     * @param $string
     * @param $maxLength
     * @param $fieldName
     * @param $truncationSuffix string to be added at the end, room will be left so it can be added later.
     *
     * @return string
     */
    public function truncateString( $string, $maxLength, $fieldName, $truncationSuffix = '' )
    {
        if ( mb_strlen( $string, "utf-8" ) <= $maxLength )
        {
            return $string;
        }

        eZDebug::writeDebug( $string, "truncation of $fieldName to max_length=". $maxLength );

        return mb_substr(  $string , 0, $maxLength - mb_strlen( $truncationSuffix, "utf-8" ), "utf-8" );
    }

    /**
     * Returns the size of the $string
     *
     * This method is meant to be overridden if needed by different DB implementation:
     * for example oracle handles bytes and not char
     *
     * @param $string
     *
     * @return int
     */
    public function countStringSize( $string )
    {
        if ( !is_string( $string ) )
            return 0;
        else
            return mb_strlen( $string, "utf-8" );
    }

    /**
     * Contains the current server
     *
     * @access protected
     * @var string
     */
    public $Server;

    /**
     * Contains the current port
     *
     * @access protected
     * @var int
     */
    public $Port;

    /**
     * The socket path, used by MySQL
     *
     * @access protected
     * @var string
     */
    public $SocketPath;

    /**
     * The current database name
     *
     * @access protected
     * @var string
     */
    public $DB;

    /**
     * The current connection, false if not connection has been made
     *
     * @access protected
     * @var resource|bool
     */
    public $DBConnection;

    /**
     * Contains the write database connection if used
     *
     * @access protected
     * @var resource|bool
     */
    public $DBWriteConnection;

    /**
     * Stores the database connection user
     *
     * @access protected
     * @var string
     */
    public $User;

    /**
     * Stores the database connection password
     *
     * @access protected
     * @var string
     */
    public $Password;

    /**
     * The charset used for the current database
     *
     * @access protected
     * @var string
     */
    public $Charset;

    /**
     * The number of times to retry a connection if it fails
     *
     * @access protected
     * @var int
     */
    public $ConnectRetries;

    /**
     * Instance of a textcodec which handles text conversion, may not be set if no builtin encoding is used
     *
     * @access protected
     * @var eZTextCodec|null|bool
     */
    public $OutputTextCodec;

    /**
     * Instance of a textcodec which handles text conversion, may not be set if no builtin encoding is used
     *
     * @access protected
     * @var eZTextCodec|null|bool
     */
    public $InputTextCodec;

    /**
     * True if a builtin encoder is to be used, this means that all input/output text is converted
     *
     * @access protected
     * @var bool
     */
    public $UseBuiltinEncoding;

    /**
     * Setting if SQL queries should be sent to debug output
     *
     * @access protected
     * @var bool
     */
    public $OutputSQL;

    /**
     * Contains true if we're connected to the database backend
     *
     * @access protected
     * @var bool
     */
    public $IsConnected = false;

    /**
     * Contains number of queries sended to DB
     *
     * @access protected
     * @var int
     */
    public $NumQueries = 0;

    /**
     * The start time of the timer
     *
     * @access protected
     * @var bool|float
     */
    public $StartTime;

    /**
     * The end time of the timer
     *
     * @access protected
     * @var bool|float
     */
    public $EndTime;

    /**
     * The total number of milliseconds the timer took
     *
     * @access protected
     * @var bool|float
     */
    public $TimeTaken;

    /**
     * The database error message of the last executed function
     *
     * @access protected
     * @var string
     */
    public $ErrorMessage;

    /**
     * The database error message number of the last executed function
     *
     * @access protected
     * @var int
     */
    public $ErrorNumber = 0;

    /**
     * If true then ErrorMessage and ErrorNumber get filled
     *
     * @access protected
     * @var bool
     */
    public $RecordError = true;

    /**
     * If true then the database connection should be persistent
     *
     * @access protected
     * @var bool
     */
    public $UsePersistentConnection = false;

    /**
     * True if slave servers are enabled
     *
     * @access protected
     * @var bool
     */
    public $UseSlaveServer;

    /**
     * The slave database name
     *
     * @access protected
     * @var string
     */
    public $SlaveDB;

    /**
     * The slave server name
     *
     * @access protected
     * @var string
     */
    public $SlaveServer;

    /**
     * The slave server port
     *
     * @access protected
     * @var int
     */
    public $SlavePort;

    /**
     * The slave database user
     *
     * @access protected
     * @var string
     */
    public $SlaveUser;

    /**
     * The slave database user password
     *
     * @access protected
     * @var string
     */
    public $SlavePassword;

    /**
     * The transaction counter, 0 means no transaction
     *
     * @access protected
     * @var int
     */
    public $TransactionCounter;

    /**
     * Flag which tells if a transaction is considered valid or not. A transaction will be made invalid if SQL errors occur
     *
     * @access protected
     * @var bool
     */
    public $TransactionIsValid;

    /**
     * Holds the transactions
     *
     * @access protected
     * @var array|bool
     */
    public $TransactionStackTree;

    /**
     * Error handling mechanism
     *
     * @var int One of the eZDB::ERROR_HANDLING_* constants
     */
    protected $errorHandling = eZDB::ERROR_HANDLING_STANDARD;

    /**
     * Maximal value for mysql int columns
     *
     * @var int
     */
    public $MAX_INT = 2147483647;

    /**
     * Minimal value for mysql int columns
     *
     * @var int
     */
    public $MIN_INT = -2147483648;
}

?>

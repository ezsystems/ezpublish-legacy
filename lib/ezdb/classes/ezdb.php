<?php
//
// $Id$
//
// Definition of eZDB class
//
// Created on: <12-Feb-2002 15:41:03 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*! \file ezdb.php
 Database abstraction layer.
*/

/*! \defgroup eZDB Database abstraction layer */

/*!
  \class eZDB ezdb.php
  \ingroup eZDB
  \brief The eZDB class provides database wrapper functions
  The eZ db library procides a database independent framework for
  SQL databases. The current supported databases are: PostgreSQL and
  MySQL.

  eZ db is designed to be used with the following type subset of SQL:
  int, float, varchar and text.

  To store date and time values int's are used. eZ locale is used to
  present the date and times on a localized format. That way we don't have
  to worry about the different date and time formats used in the different
  databases.

  Auto incrementing numbers, sequences, are used to generate unique id's
  for a table row. This functionality is abstracted as it works different
  in the different databases.

  Limit and offset functionality is also abstracted by the eZ db library.

  eZ db is designed to use lowercase in all table/column names. This is
  done to prevent errors as the different databases handles this differently.
  Especially when returning the data as an associative array.

  \code
  // include the library
  //include_once( 'lib/ezdb/classes/ezdb.php' );

  // Get the current database instance
  // will create a new database object and connect to the database backend
  // if there is already created an instance for this session the existing
  // object will be returned.
  // the settings for the database connections are set in site.ini
  $db = eZDB::instance();

  // Run a simple query
  $db->query( 'DELETE FROM sql_test' );

  // insert some data
  $str = $db->escapeString( "Testing escaping'\"" );
  $db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', '$str' )" );

  // Get the last serial value for the sql_test table
  $rowID = $db->lastSerialID( 'sql_test', 'id' );

  // fetch some data into an array of associative arrays
  $rows = $db->arrayQuery( 'SELECT * FROM sql_test' );

  foreach ( $rows as $row )
  {
     print( $row['name'] );
  }

  // fetch some data with a limit
  // will return the 10 first rows in the result
  $ret = $db->arrayQuery( 'SELECT id, name, description, rownum FROM sql_test',
                           array( 'offset' => 0, 'limit' => 10 ) );

  // check which implementation we're running
  print( $db->databaseName() );

  \endcode

  \sa eZLocale eZINI
*/

require_once( 'lib/ezutils/classes/ezdebug.php' );

class eZDB
{
    /*!
      Constructor.
      NOTE: Should not be used.
    */
    private function __construct()
    {
        eZDebug::writeError( 'This class should not be instantiated', 'eZDB::eZDB' );
    }

    /*!
      \static
      Returns an instance of the database object.
    */
    static function hasInstance()
    {
        return isset( $GLOBALS['eZDBGlobalInstance'] ) && $GLOBALS['eZDBGlobalInstance'] instanceof eZDBInterface;
    }

    /*!
     \static
     Sets the global database instance to \a $instance.
    */
    static function setInstance( $instance )
    {
        $GLOBALS['eZDBGlobalInstance'] = $instance;
    }

    /*!
      \static
      Returns an instance of the database object.
      If you want to change the current database values you should set \a $forceNewInstance to \c true to force a new instance.
    */
    static function instance( $databaseImplementation = false, $databaseParameters = false, $forceNewInstance = false )
    {
        $impl =& $GLOBALS['eZDBGlobalInstance'];

        $fetchInstance = false;
        if ( !( $impl instanceof eZDBInterface ) )
            $fetchInstance = true;

        if ( $forceNewInstance  )
        {
            unset($impl);
            $impl = false;
            $fetchInstance = true;
        }

        $useDefaults = true;
        if ( is_array( $databaseParameters ) and isset( $databaseParameters['use_defaults'] ) )
            $useDefaults = $databaseParameters['use_defaults'];

        if ( $fetchInstance )
        {
            //include_once( 'lib/ezutils/classes/ezini.php' );
            $ini = eZINI::instance();
            if ( $databaseImplementation === false and $useDefaults )
                $databaseImplementation = $ini->variable( 'DatabaseSettings', 'DatabaseImplementation' );

            $server = $user = $pwd = $db = $usePersistentConnection = $port = false;
            if ( $useDefaults )
                list( $server, $port, $user, $pwd, $db, $usePersistentConnection ) =
                    $ini->variableMulti( 'DatabaseSettings', array( 'Server', 'Port', 'User', 'Password', 'Database', 'UsePersistentConnection', ) );

            $socketPath = false;
            if ( $useDefaults )
            {
                $socket = $ini->variable( 'DatabaseSettings', 'Socket' );
                if ( trim( $socket != "" ) and $socket != "disabled" )
                {
                    $socketPath = $socket;
                }
            }

            // Check slave servers
            $slaveServer = null;
            $slaveServerPort = null;
            $slaveServerUser = null;
            $slaveServerPassword = null;
            $slaveServerDatabase = null;
            $useSlave = $ini->variable( 'DatabaseSettings', 'UseSlaveServer' );
            if ( $useSlave == "enabled" )
            {
                $slaveServers = $ini->variable( 'DatabaseSettings', 'SlaveServerArray' );
                $slaveServerPorts = $ini->variable( 'DatabaseSettings', 'SlaveServerPort' );
                $slaveServerUsers = $ini->variable( 'DatabaseSettings', 'SlaverServerUser' );
                $slaveServerPasswords = $ini->variable( 'DatabaseSettings', 'SlaverServerPassword' );
                $slaveServerDatabases = $ini->variable( 'DatabaseSettings', 'SlaverServerDatabase' );
                $numberServers = count( $slaveServers );
                if ( $numberServers > 1 )
                {
                    $index = rand( 1, $numberServers ) - 1;
                }
                else
                    $index = 0;
                $slaveServer = $slaveServers[$index];
                $slaveServerPort = $slaveServerPorts[$index];
                $slaveServerUser = $slaveServerUsers[$index];
                $slaveServerPassword = $slaveServerPasswords[$index];
                $slaveServerDatabase = $slaveServerDatabases[$index];
            }

            list( $charset, $retries ) =
                $ini->variableMulti( 'DatabaseSettings', array( 'Charset', 'ConnectRetries' ) );

            $isInternalCharset = false;
            if ( trim( $charset ) == '' )
            {
                $charset = eZTextCodec::internalCharset();
                $isInternalCharset = true;
            }
            $builtinEncoding = ( $ini->variable( 'DatabaseSettings', 'UseBuiltinEncoding' ) == 'true' );

            $extraPluginPathArray = $ini->variableArray( 'DatabaseSettings', 'DatabasePluginPath' );
            $pluginPathArray = array_merge( array( 'lib/ezdb/classes/' ),
                                            $extraPluginPathArray );
            $impl = null;

            $useSlaveServer = false;
            if ( $useSlave == "enabled" )
                $useSlaveServer = true;
            $defaultDatabaseParameters = array( 'server' => $server,
                                                'port' => $port,
                                                'user' => $user,
                                                'password' => $pwd,
                                                'database' => $db,
                                                'use_slave_server' => $useSlaveServer,
                                                'slave_server' => $slaveServer,
                                                'slave_port' => $slaveServerPort,
                                                'slave_user' => $slaveServerUser,
                                                'slave_password' => $slaveServerPassword,
                                                'slave_database' => $slaveServerDatabase,
                                                'charset' => $charset,
                                                'is_internal_charset' => $isInternalCharset,
                                                'socket' => $socketPath,
                                                'builtin_encoding' => $builtinEncoding,
                                                'connect_retries' => $retries,
                                                'use_persistent_connection' => $usePersistentConnection,
                                                'show_errors' => true );
            /* This looks funny, but is needed to fix a crash in PHP */
            $b = $databaseParameters;
            $databaseParameters = $defaultDatabaseParameters;
            if ( isset( $b['server'] ) )
                $databaseParameters['server'] = $b['server'];
            if ( isset( $b['port'] ) )
                $databaseParameters['port'] = $b['port'];
            if ( isset( $b['user'] ) )
                $databaseParameters['user'] = $b['user'];
            if ( isset( $b['password'] ) )
                $databaseParameters['password'] = $b['password'];
            if ( isset( $b['database'] ) )
                $databaseParameters['database'] = $b['database'];
            if ( isset( $b['use_slave_server'] ) )
                $databaseParameters['use_slave_server'] = $b['use_slave_server'];
            if ( isset( $b['slave_server'] ) )
                $databaseParameters['slave_server'] = $b['slave_server'];
            if ( isset( $b['slave_port'] ) )
                $databaseParameters['slave_port'] = $b['slave_port'];
            if ( isset( $b['slave_user'] ) )
                $databaseParameters['slave_user'] = $b['slave_user'];
            if ( isset( $b['slave_password'] ) )
                $databaseParameters['slave_password'] = $b['slave_password'];
            if ( isset( $b['slave_database'] ) )
                $databaseParameters['slave_database'] = $b['slave_database'];
            if ( isset( $b['charset'] ) )
            {
                $databaseParameters['charset'] = $b['charset'];
                $databaseParameters['is_internal_charset'] = false;
            }
            if ( isset( $b['socket'] ) )
                $databaseParameters['socket'] = $b['socket'];
            if ( isset( $b['builtin_encoding'] ) )
                $databaseParameters['builtin_encoding'] = $b['builtin_encoding'];
            if ( isset( $b['connect_retries'] ) )
                $databaseParameters['connect_retries'] = $b['connect_retries'];
            if ( isset( $b['use_persistent_connection'] ) )
                $databaseParameters['use_persistent_connection'] = $b['use_persistent_connection'];
            if ( isset( $b['show_errors'] ) )
                $databaseParameters['show_errors'] = $b['show_errors'];

            // Search for the db interface implementations in active extensions directories.
            //include_once( 'lib/ezutils/classes/ezextension.php' );
            $baseDirectory         = eZExtension::baseDirectory();
            $extensionDirectories  = eZExtension::activeExtensions();
            $extensionDirectories  = array_unique( $extensionDirectories );
            $repositoryDirectories = array();
            foreach ( $extensionDirectories as $extDir )
            {
                $newRepositoryDir = "$baseDirectory/$extDir/ezdb/dbms-drivers/";
                if ( file_exists( $newRepositoryDir ) )
                    $repositoryDirectories[] = $newRepositoryDir;
            }
            $repositoryDirectories = array_merge( $repositoryDirectories, $pluginPathArray );

            foreach( $repositoryDirectories as $repositoryDir )
            {
                // If we have an alias get the real name
                $aliasList = $ini->variable( 'DatabaseSettings', 'ImplementationAlias' );
                if ( isset( $aliasList[$databaseImplementation] ) )
                {
                    $databaseImplementation = $aliasList[$databaseImplementation];
                }

                $dbFile = $repositoryDir . $databaseImplementation . 'db.php';
                if ( file_exists( $dbFile ) )
                {
                    include_once( $dbFile );
                    $className = $databaseImplementation . 'db';
                    $impl = new $className( $databaseParameters );
                    break;
                }
            }
            if ( $impl === null )
            {
                //include_once( 'lib/ezdb/classes/eznulldb.php' );
                $impl = new eZNullDB( $databaseParameters );
                $impl->ErrorMessage = "No database handler was found for '$databaseImplementation'";
                $impl->ErrorNumber = -1;
                if ( $databaseParameters['show_errors'] )
                {
                    eZDebug::writeError( 'Database implementation not supported: ' . $databaseImplementation, 'eZDB::instance' );
                }
            }

        }
        return $impl;
    }

    /*!
      Checks transaction counter
      If the current transaction counter is 1 or higher
      means 1 or more transactions are running and a negative value
      means something is wrong.
      Prints the error.
    */
    static function checkTransactionCounter()
    {
        $result = true;
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $checkValidity = ( $ini->variable( "SiteAccessSettings", "CheckValidity" ) == "true" );
        if ( $checkValidity )
            return $result;

        $db = eZDB::instance();

        if ( $db->transactionCounter() > 0 )
        {
            $result = array();
            $result['error'] = "Internal transaction counter mismatch : " . $db->transactionCounter() . ". Should be zero.";
            eZDebug::writeError( $result['error'] );
            $stack = $db->generateFailedTransactionStack();
            if ( $stack !== false )
            {
                eZDebug::writeError( $stack, 'Transaction stack' );
            }
            //include_once( 'lib/ezutils/classes/ezini.php' );
            $ini = eZINI::instance();
            // In debug mode the transaction will be invalidated causing the top-level commit
            // to issue an error.
            if ( $ini->variable( "DatabaseSettings", "DebugTransactions" ) == "enabled" )
            {
                $db->invalidateTransaction();
                $db->reportError();
            }
            else
            {
                while ( $db->transactionCounter() > 0 )
                {
                    $db->commit();
                }
            }
        }

        return $result;
    }

}

?>

<?php
//
// $Id$
//
// Definition of eZDB class
//
// Created on: <12-Feb-2002 15:41:03 bf>
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
  include_once( 'lib/ezdb/classes/ezdb.php' );

  // Get the current database instance
  // will create a new database object and connect to the database backend
  // if there is already created an instance for this session the existing
  // object will be returned.
  // the settings for the database connections are set in site.ini
  $db =& eZDB::instance();

  // Run a simple query
  $db->query( 'DELETE FROM sql_test' );

  // insert some data
  $str = $db->escapeString( "Testing escaping'\"" );
  $db->query( "INSERT INTO sql_test ( name, description ) VALUES ( 'New test', '$str' )" );

  // Get the last serial value for the sql_test table
  $rowID = $db->lastSerialID( 'sql_test', 'id' );

  // fetch some data into an array of associative arrays
  $rows =& $db->arrayQuery( 'SELECT * FROM sql_test' );

  foreach ( $rows as $row )
  {
     print( $row['name'] );
  }

  // fetch some data with a limit
  // will return the 10 first rows in the result
  $ret =& $db->arrayQuery( 'SELECT id, name, description, rownum FROM sql_test',
                           array( 'offset' => 0, 'limit' => 10 ) );

  // check which implementation we're running
  print( $db->databaseName() );

  \endcode

  \sa eZLocale eZINI
*/

include_once( 'lib/ezutils/classes/ezdebug.php' );

class eZDB
{
    /*!
      Constructor.
      NOTE: Should not be used.
    */
    function eZDB()
    {
        eZDebug::writeError( 'This class should not be instantiated', 'eZDB::eZDB' );
    }

    /*!
      \static
      Returns an instance of the database object.
    */
    function hasInstance()
    {
        $impl =& $GLOBALS['eZDBGlobalInstance'];
        $class =& get_class( $impl );
        $hasDB = false;
        if ( preg_match( '/.*?db/', $class ) )
        {
            $hasDB = true;
        }
        return $hasDB;
    }

    /*!
     \static
     Sets the global database instance to \a $instance.
    */
    function setInstance( &$instance )
    {
        $GLOBALS['eZDBGlobalInstance'] =& $instance;
    }

    /*!
      \static
      Returns an instance of the database object.
      If you want to change the current database values you should set \a $forceNewInstance to \c true to force a new instance.
    */
    function &instance( $databaseImplementation = false, $databaseParameters = false, $forceNewInstance = false )
    {
        $impl =& $GLOBALS['eZDBGlobalInstance'];
        $class = get_class( $impl );

        $fetchInstance = false;
        if ( !preg_match( '/.*?db/', $class ) )
            $fetchInstance = true;

        if ( $forceNewInstance  )
        {
            unset($impl);
            $impl = false;
            $fetchInstance = true;
        }

        if ( $fetchInstance )
        {
            include_once( 'lib/ezutils/classes/ezini.php' );
            $ini =& eZINI::instance();
            if ( $databaseImplementation === false )
                $databaseImplementation = $ini->variable( 'DatabaseSettings', 'DatabaseImplementation' );

            $server = $ini->variable( 'DatabaseSettings', 'Server' );
            $user = $ini->variable( 'DatabaseSettings', 'User' );
            $pwd = $ini->variable( 'DatabaseSettings', 'Password' );
            $db = $ini->variable( 'DatabaseSettings', 'Database' );
            $usePersistentConnection = $ini->variable( 'DatabaseSettings', 'UsePersistentConnection' );
            $socketPath = false;
            $socket = $ini->variable( 'DatabaseSettings', 'Socket' );
            if ( trim( $socket != "" ) and $socket != "disabled" )
            {
                $socketPath = $socket;
            }

            // Check slave servers
            $slaveServer = null;
            $slaveServerUser = null;
            $slaveServerPassword = null;
            $slaveServerDatabase = null;
            $useSlave = $ini->variable( 'DatabaseSettings', 'UseSlaveServer' );
            if ( $useSlave == "enabled" )
            {
                $slaveServers = $ini->variable( 'DatabaseSettings', 'SlaveServerArray' );
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
                $slaveServerUser = $slaveServerUsers[$index];
                $slaveServerPassword = $slaveServerPasswords[$index];
                $slaveServerDatabase = $slaveServerDatabases[$index];
            }


            $charset = $ini->variable( 'DatabaseSettings', 'Charset' );
            $retries = $ini->variable( 'DatabaseSettings', 'ConnectRetries' );
            $builtinEncoding = ( $ini->variable( 'DatabaseSettings', 'UseBuiltinEncoding' ) == 'true' );

            $extraPluginPathArray = $ini->variableArray( 'DatabaseSettings', 'DatabasePluginPath' );
            $pluginPathArray = array_merge( array( 'lib/ezdb/classes/' ),
                                            $extraPluginPathArray );
//             eZDebug::writeDebug( $pluginPathArray, 'pluginPath' );
            $impl = null;

            $useSlaveServer = false;
            if ( $useSlave == "enabled" )
                $useSlaveServer = true;
            $defaultDatabaseParameters = array( 'server' => $server,
                                                'user' => $user,
                                                'password' => $pwd,
                                                'database' => $db,
                                                'use_slave_server' => $useSlaveServer,
                                                'slave_server' => $slaveServer,
                                                'slave_user' => $slaveServerUser,
                                                'slave_password' => $slaveServerPassword,
                                                'slave_database' => $slaveServerDatabase,
                                                'charset' => $charset,
                                                'socket' => $socketPath,
                                                'builtin_encoding' => $builtinEncoding,
                                                'connect_retries' => $retries,
                                                'use_persistent_connection' => $usePersistentConnection );
            $b = $databaseParameters;
            $databaseParameters = $defaultDatabaseParameters;
            if ( isset( $b['server'] ) )
                $databaseParameters['server'] = $b['server'];
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
            if ( isset( $b['slave_user'] ) )
                $databaseParameters['slave_user'] = $b['slave_user'];
            if ( isset( $b['slave_password'] ) )
                $databaseParameters['slave_password'] = $b['slave_password'];
            if ( isset( $b['slave_database'] ) )
                $databaseParameters['slave_database'] = $b['slave_database'];
            if ( isset( $b['charset'] ) )
                $databaseParameters['charset'] = $b['charset'];
            if ( isset( $b['socket'] ) )
                $databaseParameters['socket'] = $b['socket'];
            if ( isset( $b['builtin_encoding'] ) )
                $databaseParameters['builtin_encoding'] = $b['builtin_encoding'];
            if ( isset( $b['connect_retries'] ) )
                $databaseParameters['connect_retries'] = $b['connect_retries'];
            if ( isset( $b['use_persistent_connection'] ) )
                $databaseParameters['use_persistent_connection'] = $b['use_persistent_connection'];
/*            if ( $databaseParameters === false )
            {
                $databaseParameters = $defaultDatabaseParameters;
            }
            else
            {
                $b = $databaseParameters;
                $c = $defaultDatabaseParameters;

                $databaseParameters =& array_merge( $c, $b );

            }*/
            foreach( $pluginPathArray as $pluginPath )
            {
                $dbFile = $pluginPath . $databaseImplementation . 'db.php';
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
                include_once( 'lib/ezdb/classes/eznulldb.php' );
                $impl = new eZNullDB( $databaseParameters );
                eZDebug::writeError( 'Database implementation not supported: ' . $databaseImplementation, 'eZDB::instance' );
            }

        }
        return $impl;
    }
}

?>

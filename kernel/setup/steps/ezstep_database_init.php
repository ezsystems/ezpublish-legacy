<?php
//
// Definition of eZStepDatabaseInit class
//
// Created on: <12-Aug-2003 11:45:59 kk>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezstep_database_init.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( 'lib/ezdb/classes/ezdbtool.php' );
include_once( 'kernel/common/i18n.php' );
include_once( "kernel/setup/ezsetuptests.php" );

define( 'EZ_SETUP_DB_ERROR_EMPTY_PASSWORD', 1 );
define( 'EZ_SETUP_DB_ERROR_NONMATCH_PASSWORD', 2 );
define( 'EZ_SETUP_DB_ERROR_CONNECTION_FAILED', 3 );
define( 'EZ_SETUP_DB_ERROR_NOT_EMPTY', 4 );
define( 'EZ_SETUP_DB_ERROR_NO_DATABASES', 5 );

/*!
  \class eZStepDatabaseInit ezstep_database_init.php
  \brief The class eZStepDatabaseInit does

*/

class eZStepDatabaseInit extends eZStepInstaller
{
    /*!
     Constructor
     \reimp
    */
    function eZStepDatabaseInit( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        $databaseMap = eZSetupDatabaseMap();

        // Get database parameters from input form.
        if ( $this->Http->hasPostVariable( 'eZSetupDatabaseServer' ) )
            $this->PersistenceList['database_info']['server'] = $this->Http->postVariable( 'eZSetupDatabaseServer' );
        if ( $this->Http->hasPostVariable( 'eZSetupDatabaseName' ) )
            $this->PersistenceList['database_info']['dbname'] = $this->Http->postVariable( 'eZSetupDatabaseName' );
        if ( $this->Http->hasPostVariable( 'eZSetupDatabaseUser' ) )
            $this->PersistenceList['database_info']['user'] = $this->Http->postVariable( 'eZSetupDatabaseUser' );
        if ( $this->Http->hasPostVariable( 'eZSetupDatabaseSocket' ) )
            $this->PersistenceList['database_info']['socket'] = $this->Http->postVariable( 'eZSetupDatabaseSocket' );
        if ( !isset( $this->PersistenceList['database_info']['socket'] ) )
            $this->PersistenceList['database_info']['socket'] = false;


        $this->Error = 0;
        $dbStatus = false;

        // Get password
        if ( isset( $this->PersistenceList['database_info']['password'] ) )
        {
            $password = $this->PersistenceList['database_info']['password'];
        }

        if ( $this->Http->hasPostVariable( 'eZSetupDatabasePassword' ) )
        {
            $password = $this->Http->postVariable( 'eZSetupDatabasePassword' );
            $this->PersistenceList['database_info']['password'] = $password;
        }

        $databaseChoice = false;

        // Check database connection
        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $regionalInfo = $this->PersistenceList['regional_info'];

        $dbStatus = array();
        $dbDriver = $databaseInfo['info']['driver'];
        $dbServer = $databaseInfo['server'];
        $dbName = $databaseInfo['dbname'];
        $dbUser = $databaseInfo['user'];
        $dbSocket = $databaseInfo['socket'];
        if ( trim( $dbSocket ) == '' )
            $dbSocket = false;
        $dbPwd = $password;
        $dbCharset = 'iso-8859-1';
        $dbParameters = array( 'server' => $dbServer,
                               'user' => $dbUser,
                               'password' => $dbPwd,
                               'socket' => $dbSocket,
                               'database' => false,
                               'charset' => $dbCharset );

        // PostgreSQL requires us to specify database name.
        // We use template1 here since it exists on all PostgeSQL installations.
        if( $this->PersistenceList['database_info']['type'] == 'pgsql' )
            $dbParameters['database'] = 'template1';

        $db =& eZDB::instance( $dbDriver, $dbParameters, true );
        $availDatabases = $db->availableDatabases();
        $this->PersistenceList['database_info']['use_unicode'] = false;
        if ( $db->isCharsetSupported( 'utf-8' ) )
        {
            $this->PersistenceList['database_info']['use_unicode'] = true;
        }

        if ( $availDatabases === false ) // not possible to determine if username and password is correct here
        {
            return true;
        }
        else if ( count( $availDatabases ) > 0 ) // login succeded, and at least one database available
        {
            $this->PersistenceList['database_info_available'] = $availDatabases;
            return true;
        }
        else if ( $availDatabases == null && $db->isConnected() === true )
        {
            $this->Error = EZ_SETUP_DB_ERROR_NO_DATABASES;
            return false;
        }

        $this->Error = EZ_SETUP_DB_ERROR_CONNECTION_FAILED;

        return false;
    }

    /*!
     \reimp
     */
    function init()
    {
        // If using windows installer, set standard values, and continue
/*        if ( eZSetupTestInstaller() == 'windows' )
        {
            $this->PersistenceList['database_info']['server'] = 'localhost';
            $this->PersistenceList['database_info']['user'] = 'root';
            $this->PersistenceList['database_info']['password'] = '';
            return true;
        }*/

        $config =& eZINI::instance( 'setup.ini' );
        if ( !isset( $this->PersistenceList['database_info']['server'] ) or
             !$this->PersistenceList['database_info']['server'] )
            $this->PersistenceList['database_info']['server'] = $config->variable( 'DatabaseSettings', 'DefaultServer' );
        if ( !isset( $this->PersistenceList['database_info']['dbname'] ) or
             !$this->PersistenceList['database_info']['dbname'] )
            $this->PersistenceList['database_info']['dbname'] = $config->variable( 'DatabaseSettings', 'DefaultName' );
        if ( !isset( $this->PersistenceList['database_info']['user'] ) or
             !$this->PersistenceList['database_info']['user'] )
            $this->PersistenceList['database_info']['user'] = $config->variable( 'DatabaseSettings', 'DefaultUser' );
        if ( !isset( $this->PersistenceList['database_info']['password'] ) or
             !$this->PersistenceList['database_info']['password'] )
            $this->PersistenceList['database_info']['password'] = $config->variable( 'DatabaseSettings', 'DefaultPassword' );

        if ( $this->Http->postVariable( 'eZSetup_current_step' ) == 'SiteDetails' ) // Failed to connect to tables in database
        {
            $this->Error = EZ_SETUP_DB_ERROR_CONNECTION_FAILED;
        }

        return false; // Always show database initialization
    }

    /*!
     \reimp
     */
    function &display()
    {
        $databaseMap = eZSetupDatabaseMap();

        $dbError = 0;
        switch ( $this->Error )
        {
            case EZ_SETUP_DB_ERROR_CONNECTION_FAILED:
            {
                if ( $this->PersistenceList['database_info']['type'] == 'pgsql' )
                {
                    $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                        'Please make sure that the username and the password is correct. Verify that your PostgreSQL database is configured correctly.'
                                                        .'<br>See the %documentation for more information about this.'
                                                        .'<br>Remember to start postmaster with the -i option.'
                                                        .'<br>Note that PostgreSQL 7.2 is not supported.', null,
                                                        array( '%documentation' => '<a href="http://www.php.net/manual/en/ref.pgsql.php">PHP documentation</a>' ) ),
                                      'number' => EZ_SETUP_DB_ERROR_CONNECTION_FAILED );
                }
                else
                {
                    $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                        'The database would not accept the connection, please review your settings and try again.' ),
                                      'number' => EZ_SETUP_DB_ERROR_CONNECTION_FAILED );
                }

                break;
            }
            case EZ_SETUP_DB_ERROR_NONMATCH_PASSWORD:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    'Password entries did not match.' ),
                                  'number' => EZ_SETUP_DB_ERROR_NONMATCH_PASSWORD );
                break;
            }
            case EZ_SETUP_DB_ERROR_NOT_EMPTY:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    'The selected database was not empty, please choose from the alternatives below.' ),
                                  'number' => EZ_SETUP_DB_ERROR_NOT_EMPTY );
                $this->Tpl->setVariable( 'db_not_empty', 1 );
                break;
            }
            case EZ_SETUP_DB_ERROR_NO_DATABASES:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    'The selected selected user has not got access to any databases. Change user or create a database for the user.' ),
                                  'number' => EZ_SETUP_DB_ERROR_NO_DATABASES );
                break;
            }
        }

        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $databaseInfo['table']['is_empty'] = $this->DBEmpty;
        $regionalInfo = $this->PersistenceList['regional_info'];

        $this->Tpl->setVariable( 'db_error', $dbError );
        $this->Tpl->setVariable( 'database_info', $databaseInfo );
        $this->Tpl->setVariable( 'regional_info', $regionalInfo );

        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/database_init.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Database initalization' ),
                                        'url' => false ) );
        return $result;
    }

    var $Error = 0;
    var $DBEmpty = true;
}

?>

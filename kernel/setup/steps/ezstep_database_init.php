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
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
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
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'database_init', 'Database init' );
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
        if ( isset( $this->PersistenceList['regional_info'] ) )
        {
            $regionalInfo = $this->PersistenceList['regional_info'];
        }
        else
        {
            $regionalInfo = '';
        }

        $this->PersistenceList['database_info']['password'] = $password;

        $result = $this->checkDatabaseRequirements( false );

        $this->PersistenceList['database_info']['version'] = $result['db_version'];
        $this->PersistenceList['database_info']['required_version'] = $result['db_required_version'];
        if ( !$result['status'] )
        {
            $this->Error = $result['error_code'];
            return false;
        }

        $db =& $result['db_instance'];
        $this->PersistenceList['database_info']['use_unicode'] = $result['use_unicode'];
        $availDatabases = $db->availableDatabases();

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
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            // Fill in database info in persistence list
            // This is needed for db requirement check
            $this->PersistenceList['database_info']['server'] = $data['Server'];
            $this->PersistenceList['database_info']['dbname'] = $data['Database'];
            $this->PersistenceList['database_info']['user'] = $data['User'];
            $this->PersistenceList['database_info']['password'] = $data['Password'];
            $this->PersistenceList['database_info']['socket'] = $data['Socket'];
            $this->PersistenceList['database_info']['database'] = $data['Database'];

            $result = $this->checkDatabaseRequirements( false );

            $this->PersistenceList['database_info']['version'] = $result['db_version'];
            $this->PersistenceList['database_info']['required_version'] = $result['db_required_version'];
            if ( !$result['status'] )
            {
                $this->Error = $result['error_code'];
                return false;
            }

            $this->PersistenceList['database_info']['use_unicode'] = $result['use_unicode'];

            return $this->kickstartContinueNextStep();
        }

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
        if ( !isset( $this->PersistenceList['database_info']['socket'] ) )
            $this->PersistenceList['database_info']['socket'] = '';

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
        $dbNotEmpty = 0;
        if ( $this->Error )
        {
            $dbError = $this->databaseErrorInfo( array( 'error_code' => $this->Error,
                                                        'database_info' => $this->PersistenceList['database_info'] ) );
        }

        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $databaseInfo['table']['is_empty'] = $this->DBEmpty;
        if ( isset( $this->PersistenceList['regional_info'] ) )
        {
            $regionalInfo = $this->PersistenceList['regional_info'];
        }
        else
        {
            $regionalInfo = '';
        }

        $this->Tpl->setVariable( 'db_error', $dbError );
        $this->Tpl->setVariable( 'database_info', $databaseInfo );
        $this->Tpl->setVariable( 'regional_info', $regionalInfo );
        $this->Tpl->setVariable( 'db_not_empty', $dbNotEmpty );

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

<?php
//
// Definition of eZStepSiteDetails class
//
// Created on: <12-Aug-2003 18:30:57 kk>
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

/*! \file ezstep_site_details.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );

if ( !defined( 'EZ_SETUP_DB_ERROR_NOT_EMPTY' ) )
    define( 'EZ_SETUP_DB_ERROR_NOT_EMPTY', 4 );
if ( !defined( 'EZ_SETUP_DB_ERROR_ALREADY_CHOSEN' ) )
    define( 'EZ_SETUP_DB_ERROR_ALREADY_CHOSEN', 10 );
if ( !defined( 'EZ_SETUP_SITE_ACCESS_ILLEGAL' ) )
    define( 'EZ_SETUP_SITE_ACCESS_ILLEGAL', 11 );
if ( !defined( 'EZ_SETUP_SITE_ACCESS_ILLEGAL_NAME' ) )
    define( 'EZ_SETUP_SITE_ACCESS_ILLEGAL_NAME', 12 );

/*!
  \class eZStepSiteDetails ezstep_site_details.php
  \brief The class eZStepSiteDetails does

*/

class eZStepSiteDetails extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteDetails( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_details', 'Site details' );
    }

    /*!
     \reimp
    */
    function processPostData()
    {

        include_once( 'lib/ezdb/classes/ezdbtool.php' );
        $databaseMap = eZSetupDatabaseMap();

        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $regionalInfo = $this->PersistenceList['regional_info'];

        $dbStatus = array();
        $dbDriver = $databaseInfo['info']['driver'];
        $dbServer = $databaseInfo['server'];
        $dbUser = $databaseInfo['user'];
        $dbSocket = $databaseInfo['socket'];
        $dbPwd = $databaseInfo['password'];

        $chosenDatabases = array();
        $siteAccessValues = array();
        $siteAccessValues['admin'] = 1; // Add user and admin as illegal site access values
        $siteAccessValues['user'] = 1;

        $siteTypes = $this->chosenSiteTypes();

        $counter = 0;
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];
            $siteType['title'] = $this->Http->postVariable( 'eZSetup_site_templates_' . $counter.'_title' );
            $siteType['url'] = $this->Http->postVariable( 'eZSetup_site_templates_' . $counter.'_url' );

            if ( isset( $siteAccessValues[$this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_value' )] ) ) // check for equal site access values
            {
                $this->Error[$counter] = EZ_SETUP_SITE_ACCESS_ILLEGAL;
            }

            $siteType['access_type_value'] = $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_value' );
            $siteAccessValues[$siteType['access_type_value']] = 1;

            if ( isset( $siteAccessValues[$this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_admin_value' )] ) ) // check for equal site access values
            {
                $this->Error[$counter] = EZ_SETUP_SITE_ACCESS_ILLEGAL;
            }

            $siteType['admin_access_type_value'] = $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_admin_value' );
            $siteAccessValues[$siteType['admin_access_type_value']] = 1;

            $siteType['database'] = $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_database' );

            if ( isset( $chosenDatabases[$siteType['database']] ) )
            {
                $this->Error[$counter] = EZ_SETUP_DB_ERROR_ALREADY_CHOSEN;
            }

            $chosenDatabases[$siteType['database']] = 1;

            // Check database connection
            $dbName = $siteType['database'];
            $dbCharset = 'iso-8859-1';
            $dbParameters = array( 'server' => $dbServer,
                                   'user' => $dbUser,
                                   'password' => $dbPwd,
                                   'socket' => $dbSocket,
                                   'database' => $dbName,
                                   'charset' => $dbCharset );
            $db =& eZDB::instance( $dbDriver, $dbParameters, true );

            $dbVersion = $db->databaseServerVersion();

            if ( $dbVersion != null )
            {
                $dbStatus['connected'] = $db->isConnected();
                if ( $db->databaseName() == 'postgresql' )
                {
                    if ( $dbVersion['values'][0] < 7 )
                    {
                        $dbStatus['connected'] = false;
                    }
                    else if ( $dbVersion['values'][1] < 3 )
                    {
                        $dbStatus['connected'] = false;
                    }
                }
            }
            else
            {
                $dbStatus['connected'] = $db->isConnected();
            }

            $dbError = false;
            $demoDataResult = true;
            if ( $dbStatus['connected'] )
            {

                if ( count( $db->eZTableList() ) != 0 )
                {
                    if ( $this->Http->hasPostVariable( 'eZSetup_site_templates_'.$counter.'_existing_database' ) &&
                         $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_existing_database' ) != '4' )
                    {
                        $siteType['existing_database'] = $this->Http->postVariable( 'eZSetup_site_templates_' . $counter . '_existing_database' );
                    }
                    else
                    {
                        $this->Error[$counter] = EZ_SETUP_DB_ERROR_NOT_EMPTY ;
                    }
                }
            }
            else
            {
                return 'DatabaseInit';
            }

            /* Check for valid host names */
            if ( $siteType['access_type'] == 'hostname' )
            {
                if ( strpos( $siteType['access_type_value'], '_' ) !== false )
                {
                    $siteType['access_type_value'] = strtr ( $siteType['access_type_value'], '_', '-' ) ;
                    $this->Error[$counter] = EZ_SETUP_SITE_ACCESS_ILLEGAL_NAME;
                }
                if ( strpos( $siteType['admin_access_type_value'], '_' ) !== false )
                {
                    $siteType['admin_access_type_value'] = strtr ( $siteType['admin_access_type_value'], '_', '-' ) ;
                    $this->Error[$counter] = EZ_SETUP_SITE_ACCESS_ILLEGAL_NAME;
                }
            }
            ++$counter;
        }
        $this->storeSiteTypes( $siteTypes );

        return ( count( $this->Error ) == 0 );
    }

    /*!
     \reimp
    */
    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            $siteTypes = $this->chosenSiteTypes();

            $counter = 0;
            foreach ( array_keys( $siteTypes ) as $siteTypeKey )
            {
                $siteType =& $siteTypes[$siteTypeKey];
                $identifier = $siteType['identifier'];
                $siteType['title'] = isset( $data['Title'][$identifier] ) ? $data['Title'][$identifier] : false;
                if ( !$siteType['title'] )
                    $siteType['title'] = $siteType['name'];
                $siteType['url'] = isset( $data['URL'][$identifier] ) ? $data['URL'][$identifier] : false;
                if ( strlen( $siteType['url'] ) == 0 )
                    $siteType['url'] = 'http://' . eZSys::hostName() . eZSys::indexDir( false );

                // Change access name for user side, if not use default which is the identifier
                if ( isset( $data['Access'][$identifier] ) )
                    $siteType['access_type_value'] = $data['Access'][$identifier];

                // Change access name for admin side, if not use default which is the identifier + _admin
                if ( isset( $data['AdminAccess'][$identifier] ) )
                    $siteType['admin_access_type_value'] = $data['AdminAccess'][$identifier];

                $siteType['database'] = $data['Database'][$identifier];
                $action = 1;
                $map = array( 'ignore' => 1,
                              'remove' => 2,
                              'skip' => 3 );
                // Figure out what to do with database, do we need cleanup etc?
                if ( isset( $map[$data['DatabaseAction'][$identifier]] ) )
                    $action = $map[$data['DatabaseAction'][$identifier]];
                $siteType['existing_database'] = $action;

                $chosenDatabases[$siteType['database']] = 1;

                ++$counter;
            }
            $this->storeSiteTypes( $siteTypes );

            return $this->kickstartContinueNextStep();
        }

        include_once( 'lib/ezdb/classes/ezdbtool.php' );

        // Get available databases
        $databaseMap = eZSetupDatabaseMap();
        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $regionalInfo = $this->PersistenceList['regional_info'];

        $demoDataResult = false;

        $dbStatus = array();
        $dbDriver = $databaseInfo['info']['driver'];
        $dbServer = $databaseInfo['server'];
        $dbName = $databaseInfo['dbname'];
        $dbUser = $databaseInfo['user'];
        $dbSocket = $databaseInfo['socket'];
        if ( trim( $dbSocket ) == '' )
            $dbSocket = false;
        $dbPwd = $databaseInfo['password'];
        $dbCharset = 'iso-8859-1';
        $dbParameters = array( 'server' => $dbServer,
                               'user' => $dbUser,
                               'password' => $dbPwd,
                               'socket' => $dbSocket,
                               'database' => false,
                               'charset' => $dbCharset );
        $db =& eZDB::instance( $dbDriver, $dbParameters, true );
        $availDatabases = $db->availableDatabases();

        if ( count( $availDatabases ) > 0 ) // login succeded, and at least one database available
        {
            $this->PersistenceList['database_info_available'] = $availDatabases;
        }

        return false; // Always show site details
    }

    /*!
     \reimp
    */
    function &display()
    {
        $config =& eZINI::instance( 'setup.ini' );

        $siteTypes = $this->chosenSiteTypes();

        $availableDatabaseList = $this->PersistenceList['database_info_available'];
        $databaseList = $availableDatabaseList;
        $databaseCounter = 0;
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];
            if ( !isset( $siteType['title'] ) )
                $siteType['title'] = $siteType['name'];
            if ( !isset( $siteType['url'] ) )
                $siteType['url'] = 'http://' . eZSys::hostName() . eZSys::indexDir( false );
            if ( !isset( $siteType['site_access_illegal'] ) )
                $siteType['site_access_illegal'] = false;
            if ( !isset( $siteType['db_already_chosen'] ) )
                $siteType['db_already_chosen'] = false;
            if ( !isset( $siteType['database'] ) )
            {
                $matchedDBName = false;
                // First try database name match
                foreach ( $databaseList as $databaseName )
                {
                    $dbName = trim( strtolower( $databaseName ) );
                    $identifier = trim( strtolower( $siteType['identifier'] ) );
                    if ( $dbName == $identifier )
                    {
                        $matchedDBName = $databaseName;
                        break;
                    }
                }
                if ( !$matchedDBName )
                    $matchedDBName = $databaseList[$databaseCounter++];
                $databaseList = array_values( array_diff( $databaseList, array( $matchedDBName ) ) );
                $siteType['database'] = $matchedDBName;
            }
        }

        $this->Tpl->setVariable( 'db_not_empty', 0 );
        $this->Tpl->setVariable( 'db_already_chosen', 0 );
        $this->Tpl->setVariable( 'site_access_illegal', 0 );
        $this->Tpl->setVariable( 'site_access_illegal_name', 0 );
        foreach ( $this->Error as $key => $error )
        {
            switch ( $error )
            {
                case EZ_SETUP_DB_ERROR_NOT_EMPTY:
                {
                    $this->Tpl->setVariable( 'db_not_empty', 1 );
                    $siteTypes[$key]['db_not_empty'] = 1;
                } break;

                case EZ_SETUP_DB_ERROR_ALREADY_CHOSEN:
                {
                    $this->Tpl->setVariable( 'db_already_chosen', 1 );
                    $siteTypes[$key]['db_already_chosen'] = 1;
                } break;

                case EZ_SETUP_SITE_ACCESS_ILLEGAL:
                {
                    $this->Tpl->setVariable( 'site_access_illegal', 1 );
                    $siteTypes[$key]['site_access_illegal'] = 1;
                } break;

                case EZ_SETUP_SITE_ACCESS_ILLEGAL_NAME:
                {
                    $this->Tpl->setVariable( 'site_access_illegal_name', 1 );
                    $siteTypes[$key]['site_access_illegal_name'] = 1;
                } break;
            }
        }
        $this->storeSiteTypes( $siteTypes );

        $this->Tpl->setVariable( 'database_default', $config->variable( 'DatabaseSettings', 'DefaultName' ) );
        $this->Tpl->setVariable( 'database_available', $availableDatabaseList );
        $this->Tpl->setVariable( 'site_types', $siteTypes );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_details.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Site details' ),
                                        'url' => false ) );
        return $result;
    }

    var $Error = array();
}

?>

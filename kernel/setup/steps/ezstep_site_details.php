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

/*! \file ezstep_site_details.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );

define( 'EZ_SETUP_DB_ERROR_NOT_EMPTY', 4 );
define( 'EZ_SETUP_DB_ERROR_ALREADY_CHOSEN', 10 );
define( 'EZ_SETUP_SITE_ACCESS_ILLEGAL', 11 );
define( 'EZ_SETUP_SITE_DETAILS_PASSWORD_MISSMATCH', 12 );

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
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
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

//         include_once( 'kernel/setup/ezsetuptypes.php' );
//         $siteTypes = eZSetupTypes();
//         $chosenType = $this->PersistenceList['site_type']['identifier'];
//         $siteType = $siteTypes[$chosenType];
//         $siteList = array( $siteType );

//         for ( $counter = 0; $counter < $this->PersistenceList['site_templates']['count']; $counter++ )
//         for ( $counter = 0; $counter < count( $siteList ); ++$counter )
        $siteTypes = $this->chosenSiteTypes();

        $counter = 0;
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];
//             $this->PersistenceList['site_templates_' . $counter]['title'] =
//                  $this->Http->postVariable( 'eZSetup_site_templates_' . $counter.'_title' );
//             $this->PersistenceList['site_templates_' . $counter]['url'] =
//                  $this->Http->postVariable( 'eZSetup_site_templates_' . $counter.'_url' );
            $siteType['title'] = $this->Http->postVariable( 'eZSetup_site_templates_' . $counter.'_title' );
            $siteType['url'] = $this->Http->postVariable( 'eZSetup_site_templates_' . $counter.'_url' );

            if ( isset( $siteAccessValues[$this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_value' )] ) ) // check for equal site access values
            {
                $this->Error[$counter] = EZ_SETUP_SITE_ACCESS_ILLEGAL;
            }

//             $this->PersistenceList['site_templates_'.$counter]['access_type_value'] =
//                  $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_value' );
            $siteType['access_type_value'] = $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_value' );
            $siteAccessValues[$siteType['access_type_value']] = 1;

            if ( isset( $siteAccessValues[$this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_admin_value' )] ) ) // check for equal site access values
            {
                $this->Error[$counter] = EZ_SETUP_SITE_ACCESS_ILLEGAL;
            }

//             $this->PersistenceList['site_templates_'.$counter]['admin_access_type_value'] =
//                  $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_admin_value' );
            $siteType['admin_access_type_value'] = $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_admin_value' );
            $siteAccessValues[$siteType['admin_access_type_value']] = 1;
//             $this->PersistenceList['site_templates_'.$counter]['database'] =
//                  $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_database' );
            $siteType['database'] = $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_database' );

//             if ( isset( $chosenDatabases[$this->PersistenceList['site_templates_'.$counter]['database']] ) )
            if ( isset( $chosenDatabases[$siteType['database']] ) )
            {
                $this->Error[$counter] = EZ_SETUP_DB_ERROR_ALREADY_CHOSEN;
            }

            $chosenDatabases[$siteType['database']] = 1;

            // Check database connection
//             $dbName = $this->PersistenceList['site_templates_'.$counter]['database'];
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
                if ( $dbVersion['values'][0] == 7 and $dbVersion['values'][1] == 2 )
                {
                    $dbStatus['connected'] = false;
                }
                else
                {
                    $dbStatus['connected'] = $db->isConnected();
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
//                         $this->PersistenceList['site_templates_'.$counter]['existing_database'] =
//                              $this->Http->postVariable( 'eZSetup_site_templates_'.$counter.'_existing_database' );
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
            ++$counter;
        }
        $this->storeSiteTypes( $siteTypes );

        $user = array();

        $user['first_name'] = $this->Http->postVariable( 'eZSetup_site_templates_first_name' );
        $user['last_name'] = $this->Http->postVariable( 'eZSetup_site_templates_last_name' );
        $user['email'] = $this->Http->postVariable( 'eZSetup_site_templates_email' );
        if ( $this->Http->postVariable( 'eZSetup_site_templates_password1' ) != $this->Http->postVariable( 'eZSetup_site_templates_password2' ) )
        {
            $this->Error[$counter] = EZ_SETUP_SITE_DETAILS_PASSWORD_MISSMATCH;
        }
        else
        {
            $user['password'] = $this->Http->postVariable( 'eZSetup_site_templates_password1' );
        }
        $this->PersistenceList['admin'] = $user;

        return ( count( $this->Error ) == 0 );
    }

    /*!
     \reimp
    */
    function init()
    {
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
//         $dbPwd = $password;
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

//         include_once( 'kernel/setup/ezsetuptypes.php' );
//         $siteTypes = eZSetupTypes();
//         $chosenType = $this->PersistenceList['site_type']['identifier'];
//         $siteType = $siteTypes[$chosenType];
        $siteTypes = $this->chosenSiteTypes();

        $availableDatabaseList = $this->PersistenceList['database_info_available'];
        $databaseList = $availableDatabaseList;
        $databaseCounter = 0;
//         $templates = array();
//         $siteList = array( $siteType );
//         for ( $counter = 0; $counter < $this->PersistenceList['site_templates']['count']; $counter++ )
//         for ( $counter = 0; $counter < count( $siteList ); ++$counter )
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];
//             $templates[$counter] = $this->PersistenceList['site_templates_' . $counter];
//             $templates[$counter]['thumbnail'] = $siteList[$counter]['thumbnail'];
//             $templates[$counter] = $siteList[$counter]
//             if ( !isset( $templates[$counter]['identifier'] ) )
//                 $templates[$counter]['identifier'] = $siteList[$counter]['identifier'];
            if ( !isset( $siteType['title'] ) )
                $siteType['title'] = $siteType['name'];
//             if ( !isset( $templates[$counter]['title'] ) )
//                 $templates[$counter]['title'] = $siteList[$counter]['name'];
            if ( !isset( $siteType['url'] ) )
                $siteType['url'] = 'http://' . eZSys::hostName() . eZSys::indexDir( false );
//             if ( !isset( $templates[$counter]['url'] ) )
//                 $templates[$counter]['url'] = 'http://' . eZSys::hostName() . eZSys::indexDir( false );
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

        foreach ( $this->Error as $key => $error )
        {
            switch ( $error )
            {
                case EZ_SETUP_DB_ERROR_NOT_EMPTY:
                {
                    $this->Tpl->setVariable( 'db_not_empty', 1 );
                    $siteTypes[$key]['db_not_empty'] = 1;
//                     $templates[$key]['db_not_empty'] = 1;
                } break;

                case EZ_SETUP_DB_ERROR_ALREADY_CHOSEN:
                {
                    $this->Tpl->setVariable( 'db_already_chosen', 1 );
                    $siteTypes[$key]['db_already_chosen'] = 1;
//                     $templates[$key]['db_already_chosen'] = 1;
                } break;

                case EZ_SETUP_SITE_ACCESS_ILLEGAL:
                {
                    $this->Tpl->setVariable( 'site_access_illegal', 1 );
                    $siteTypes[$key]['site_access_illegal'] = 1;
//                     $templates[$key]['site_access_illegal'] = 1;
                } break;

                case EZ_SETUP_SITE_DETAILS_PASSWORD_MISSMATCH:
                {
                    $this->Tpl->setVariable( 'password_missmatch', 1 );
                } break;
            }
        }
        $this->storeSiteTypes( $siteTypes );

        $adminUser = array( 'first_name' => false,
                            'last_name' => false,
                            'email' => false,
                            'password' => false );
        if ( isset( $this->PersistenceList['admin'] ) )
            $adminUser = $this->PersistenceList['admin'];

        $this->Tpl->setVariable( 'database_default', $config->variable( 'DatabaseSettings', 'DefaultName' ) );
        $this->Tpl->setVariable( 'database_available', $availableDatabaseList );
        $this->Tpl->setVariable( 'site_types', $siteTypes );
        $this->Tpl->setVariable( 'admin', $adminUser );

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

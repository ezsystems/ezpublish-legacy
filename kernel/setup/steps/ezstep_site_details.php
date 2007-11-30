<?php
//
// Definition of eZStepSiteDetails class
//
// Created on: <12-Aug-2003 18:30:57 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
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

/*! \file ezstep_site_details.php
*/
//include_once( 'kernel/setup/steps/ezstep_installer.php');
require_once( "kernel/common/i18n.php" );

/*!
  \class eZStepSiteDetails ezstep_site_details.php
  \brief The class eZStepSiteDetails does

*/

class eZStepSiteDetails extends eZStepInstaller
{
    const SITE_ACCESS_ILLEGAL = 11;

    const SITE_ACCESS_DEFAULT_REGEXP = '/^([a-zA-Z0-9_]*)$/';
    const SITE_ACCESS_HOSTNAME_REGEXP = '/^([a-zA-Z0-9.\-:]*)$/';
    const SITE_ACCESS_PORT_REGEXP = '/^([0-9]*)$/';

    /*!
     Constructor
    */
    function eZStepSiteDetails( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_details', 'Site details' );
    }

    /*!
     \reimp
    */
    function processPostData()
    {
        //include_once( 'lib/ezdb/classes/ezdbtool.php' );
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

        $siteType = $this->chosenSiteType();
        // This should not be run, it will remove the utf-8 choice from previous steps.
//        unset( $this->PersistenceList['regional_info']['site_charset'] );


        $siteType['title'] = $this->Http->postVariable( 'eZSetup_site_templates_title' );
        $siteType['url'] = $this->Http->postVariable( 'eZSetup_site_templates_url' );

        $error = false;
        $userPath = $this->Http->postVariable( 'eZSetup_site_templates_value' );

        $regexp = self::SITE_ACCESS_DEFAULT_REGEXP;
        if ( $siteType['access_type'] == 'port' )
        {
            $regexp = self::SITE_ACCESS_PORT_REGEXP;
        }
        elseif ( $siteType['access_type'] == 'hostname' )
        {
            $regexp = self::SITE_ACCESS_HOSTNAME_REGEXP;
        }
        $validateUserPath = preg_match( $regexp, $userPath );

        if ( isset( $siteAccessValues[$userPath] ) or !$validateUserPath ) // check for equal site access values
        {
            $this->Error[0] = self::SITE_ACCESS_ILLEGAL;
            /* Check for valid host name */
            $userPath = ( ( $siteType['access_type'] == 'hostname' ) and ( strpos( $userPath, '_' ) !== false ) ) ? strtr( $userPath, '_', '-' ) : $userPath;
            $error = true;
        }

        // User siteaccess
        $siteType['access_type_value'] = $userPath;
        if ( $siteType['access_type_value'] == '' )
        {
            $this->Error[0] = self::SITE_ACCESS_ILLEGAL;
            return false;
        }

        $siteAccessValues[$siteType['access_type_value']] = 1;
        $adminPath = $this->Http->postVariable( 'eZSetup_site_templates_admin_value' );
        $validateAdminPath = preg_match( $regexp, $adminPath );

        if ( isset( $siteAccessValues[$adminPath] ) or !$validateAdminPath ) // check for equal site access values
        {
            $this->Error[0] = self::SITE_ACCESS_ILLEGAL;
            /* Check for valid host name */
            $adminPath = ( ( $siteType['access_type'] == 'hostname' ) and ( strpos( $adminPath, '_' ) !== false ) ) ? strtr( $adminPath, '_', '-' ) : $adminPath;
            $error = true;
        }

        // Admin siteaccess
        $siteType['admin_access_type_value'] = $adminPath;
        if ( $siteType['admin_access_type_value'] == '' )
        {
            $this->Error[0] = self::SITE_ACCESS_ILLEGAL;
            return false;
        }

        $siteAccessValues[$siteType['admin_access_type_value']] = 1;

        $siteType['database'] = $this->Http->postVariable( 'eZSetup_site_templates_database' );

        if ( isset( $chosenDatabases[$siteType['database']] ) )
        {
            $this->Error[0] = eZStepInstaller::DB_ERROR_ALREADY_CHOSEN;
            $error = true;
        }

        $chosenDatabases[$siteType['database']] = 1;

        if ( $error )
        {
            $this->storeSiteType( $siteType );
            return false;
        }

        // Check database connection
        $result = $this->checkDatabaseRequirements( false,
                                                    array( 'database' => $siteType['database'] ) );

        if ( !$result['status'] )
        {
            $this->storeSiteType( $siteType );
            $this->Error[0] = array( 'type' => 'db',
                                            'error_code' => $result['error_code'] );
            return false;
        }
        // Store charset if found
        if ( $result['site_charset'] )
        {
            $this->PersistenceList['regional_info']['site_charset'] = $result['site_charset'];
        }

        $db = $result['db_instance'];

        $dbStatus['connected'] = $result['connected'];

        $dbError = false;
        $demoDataResult = true;
        if ( $dbStatus['connected'] )
        {

            if ( count( $db->eZTableList() ) != 0 )
            {
                if ( $this->Http->hasPostVariable( 'eZSetup_site_templates_existing_database' ) &&
                     $this->Http->postVariable( 'eZSetup_site_templates_existing_database' ) != eZStepInstaller::DB_DATA_CHOOSE )
                {
                    $siteType['existing_database'] = $this->Http->postVariable( 'eZSetup_site_templates_existing_database' );
                }
                else
                {
                    $this->Error[0] = eZStepInstaller::DB_ERROR_NOT_EMPTY ;
                }
            }
        }
        else
        {
            return 'DatabaseInit';
        }

        $this->storeSiteType( $siteType );

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

            $siteType = $this->chosenSiteType();

            $portCounter = 8080;

            if ( isset( $data['Title'] ) )
                $siteType['title'] = $data['Title'];

            if ( !$siteType['title'] )
                $siteType['title'] = $siteType['name'];

            $siteType['url'] = isset( $data['URL'] ) ? $data['URL'] : false;
            if ( strlen( $siteType['url'] ) == 0 )
                $siteType['url'] = 'http://' . eZSys::hostName() . eZSys::indexDir( false );

            switch ( $siteType['access_type'] )
            {
                case 'port':
                {
                    // Change access port for user site, if not use default which is the current value of $portCoutner
                    if ( isset( $data['AccessPort'] ) )
                        $siteType['access_type_value'] = $data['AccessPort'];
                    else
                        $siteType['access_type_value'] = $portCounter++;

                    // Change access port for admin site, if not use default which is the current value of $portCoutner
                    if ( isset( $data['AdminAccessPort'] ) )
                        $siteType['admin_access_type_value'] = $data['AdminAccessPort'];
                    else
                        $siteType['admin_access_type_value'] = $portCounter++;
                }
                break;

                case 'hostname':
                {
                    if ( isset( $data['AccessHostname'] ) )
                        $siteType['access_type_value'] = $data['AccessHostname'];
                    else
                        $siteType['access_type_value'] = $siteType['identifier'] . '.' . eZSys::hostName();

                    if ( isset( $data['AdminAccessHostname'] ) )
                        $siteType['admin_access_type_value'] = $data['AdminAccessHostname'];
                    else
                        $siteType['admin_access_type_value'] = $siteType['identifier'] . '-admin.' . eZSys::hostName();
                }
                break;

                default:
                {
                    // Change access name for user site, if not use default which is the identifier
                    if ( isset( $data['Access'] ) )
                        $siteType['access_type_value'] = $data['Access'];
                    else
                        $siteType['access_type_value'] = $siteType['identifier'];

                    // Change access name for admin site, if not use default which is the identifier + _admin
                    if ( isset( $data['AdminAccess'] ) )
                        $siteType['admin_access_type_value'] = $data['AdminAccess'];
                    else
                        $siteType['admin_access_type_value'] = $siteType['identifier'] . '_admin';
                }
                break;
            };

            $siteType['database'] = $data['Database'];
            $action = eZStepInstaller::DB_DATA_APPEND;
            $map = array( 'ignore' => 1,
                          'remove' => 2,
                          'skip' => 3 );
            // Figure out what to do with database, do we need cleanup etc?
            if ( isset( $map[$data['DatabaseAction']] ) )
                $action = $map[$data['DatabaseAction']];
            $siteType['existing_database'] = $action;

            $chosenDatabases[$siteType['database']] = 1;

            $result = $this->checkDatabaseRequirements( false,
                                                        array( 'database' => $siteType['database'] ) );

            if ( !$result['status'] )
            {
                $this->Error[0] = array( 'type' => 'db',
                                                'error_code' => $result['error_code'] );
                return false;
            }

            // Store charset if found
            if ( $result['site_charset'] )
            {
                $this->PersistenceList['regional_info']['site_charset'] = $result['site_charset'];
            }

            $this->storeSiteType( $siteType );

            return $this->kickstartContinueNextStep();
        }

        //include_once( 'lib/ezdb/classes/ezdbtool.php' );

        // Get available databases
        $databaseMap = eZSetupDatabaseMap();
        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $regionalInfo = $this->PersistenceList['regional_info'];

        $demoDataResult = false;

        $dbStatus = array();
        $dbDriver = $databaseInfo['info']['driver'];
        $dbServer = $databaseInfo['server'];
        $dbName = isset( $databaseInfo['dbname'] ) ? $databaseInfo['dbname'] : '';
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

        // PostgreSQL requires us to specify database name.
        // We use template1 here since it exists on all PostgreSQL installations.
        if( $databaseInfo['info']['type'] == 'pgsql' )
            $dbParameters['database'] = 'template1';

        $db = eZDB::instance( $dbDriver, $dbParameters, true );
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
    function display()
    {
        $config = eZINI::instance( 'setup.ini' );

        $siteType = $this->chosenSiteType();

        $availableDatabaseList = false;
        if ( isset( $this->PersistenceList['database_info_available'] ) )
        {
            $availableDatabaseList = $this->PersistenceList['database_info_available'];
        }
        $databaseList = $availableDatabaseList;
        $databaseCounter = 0;

        if ( !isset( $siteType['title'] ) )
            $siteType['title'] = $siteType['name'];
        $siteType['errors'] = array();
        if ( !isset( $siteType['url'] ) )
            $siteType['url'] = 'http://' . eZSys::hostName() . eZSys::indexDir( false );
        if ( !isset( $siteType['site_access_illegal'] ) )
            $siteType['site_access_illegal'] = false;
        if ( !isset( $siteType['db_already_chosen'] ) )
            $siteType['db_already_chosen'] = false;
        if ( !isset( $siteType['db_not_empty'] ) )
            $siteType['db_not_empty'] = 0;
        if ( !isset( $siteType['database'] ) )
        {
            if ( is_array( $databaseList ) )
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
            else
            {
                $siteType['database'] = '';
            }
        }
        if ( !isset( $siteType['existing_database'] ) )
        {
            $siteType['existing_database'] = eZStepInstaller::DB_DATA_APPEND;
        }

        $this->Tpl->setVariable( 'db_not_empty', 0 );
        $this->Tpl->setVariable( 'db_already_chosen', 0 );
        $this->Tpl->setVariable( 'db_charset_differs', 0 );
        $this->Tpl->setVariable( 'site_access_illegal', 0 );
        $this->Tpl->setVariable( 'site_access_illegal_name', 0 );

        // TODO: remove sites error array

        if ( isset( $this->Error[0] ) )
        {
            $error = $this->Error[0];

            $type = 'site';
            if ( is_array( $error ) )
            {
                $type = $error['type'];
                $error = $error['error_code'];
            }
            if ( $type == 'site' )
            {
                switch ( $error )
                {
                    case eZStepInstaller::DB_ERROR_NOT_EMPTY:
                    {
                        $this->Tpl->setVariable( 'db_not_empty', 1 );
                        $siteType['db_not_empty'] = 1;
                    } break;

                    case eZStepInstaller::DB_ERROR_ALREADY_CHOSEN:
                    {
                        $this->Tpl->setVariable( 'db_already_chosen', 1 );
                        $siteType['db_already_chosen'] = 1;
                    } break;

                    case self::SITE_ACCESS_ILLEGAL:
                    {
                        $this->Tpl->setVariable( 'site_access_illegal', 1 );
                        $siteType['site_access_illegal'] = 1;
                    } break;
                }
            }
            else if ( $type == 'db' )
            {
                if ( $error == eZStepInstaller::DB_ERROR_CHARSET_DIFFERS )
                    $this->Tpl->setVariable( 'db_charset_differs', 1 );
                $siteType['errors'][] = $this->databaseErrorInfo( array( 'error_code' => $error,
                                                                         'database_info' => $this->PersistenceList['database_info'],
                                                                         'site_type' => $siteType ) );
            }
        }
        $this->storeSiteType( $siteType );

        $this->Tpl->setVariable( 'database_default', $config->variable( 'DatabaseSettings', 'DefaultName' ) );
        $this->Tpl->setVariable( 'database_available', $availableDatabaseList );
        $this->Tpl->setVariable( 'site_type', $siteType );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_details.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Site details' ),
                                        'url' => false ) );
        return $result;
    }

    public $Error = array();
}

?>

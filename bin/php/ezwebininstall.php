#!/usr/bin/env php
<?php
//
// Created on: <26-Jun-2007 15:00:00 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

// eZWebin install Script
// file  bin/php/ezwebininstall.php


/*!
 define constans
*/

/*!
 define global vars
*/


/*!
 includes
*/
include_once( 'bin/php/ezwebincommon.php' );
require 'autoload.php';

// script initializing
$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                        "Install eZWebin package\n" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => false,
                                     'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[repository:][package:][package-dir:][url:][admin-siteaccess:][user-siteaccess:][auto-mode:]",
                                      "",
                                      array( 'repository' => "Path to repository where unpacked(unarchived) packages are \n" .
                                                         "placed. it's relative to 'var/[site.ini].[FileSettings].[StorageDir]/[package.ini].[RepositorySettings].[RepositoryDirectory]' \n".
                                                         "(default is 'var/storage/packages/ez_systems')",
                                             'package-dir' => "Path to directory with packed(ezpkg) packages(default is '/tmp/ezwebin') ",
                                             'url' => "URL to download packages, f.e. 'http://packages.ez.no/ezpublish/3.9'.\n" .
                                                      "'package-dir' can be specified to store uploaded packages on local computer.\n" .
                                                      "if 'package-dir' is not specified then default dir('/tmp/ezwebin') will be used.",
                                             'admin-siteaccess' => 'Will be used as base for eZWebin admin siteaccess',
                                             'user-siteaccess' => 'Will be used as base for eZWebin user siteaccesses',
                                             'auto-mode' => "[on/off]. Do not ask what to do in case of confilicts. By default is 'on'"
                                           ),
                                      false,
                                      array( 'user' => true )
                                     );


if( !$scriptOptions['admin-siteaccess'] )
{
    showError( "No admin siteaccess provided" );
}

if( !$scriptOptions['user-siteaccess'] )
{
    showError( "No user siteaccess provided" );
}

$adminSiteaccess = $scriptOptions['admin-siteaccess'];
$userSiteaccess = $scriptOptions['user-siteaccess'];

checkSiteaccess( $adminSiteaccess, true );
checkSiteaccess( $userSiteaccess, true );

showNotice( "Using siteaccess '" . $adminSiteaccess . "'" );
$script->setUseSiteAccess( $adminSiteaccess );

$script->initialize();

/**************************************************************
* process options                                             *
***************************************************************/

//
// 'repository' option
//
$packageRepository = $scriptOptions['repository'];
if ( !$packageRepository )
{
    $packageRepository = repositoryByVendor( defaultVendor() );
}


//
// 'package' option
//
$packageList = array(   'ezwebin_classes'
                      , 'ezwebin_extension'
                      , 'ezwebin_banners'
                      , 'ezwebin_democontent'
                      , 'ezwebin_design'
                      , 'ezwebin_site'
                    );

//
// 'package-dir' option
//
$packageDir = $scriptOptions['package-dir'] ? $scriptOptions['package-dir'] : "/tmp/ezwebin";

//
// 'url' option
//
$packageURL = $scriptOptions['url'];
if ( !$packageURL )
{
    $packageINI = eZINI::instance( 'package.ini' );
    $packageURL = $packageINI->variable( 'RepositorySettings', 'RemotePackagesIndexURL' );
}

//
// 'auto-mode' option
//
global $autoMode;
$autoMode = $scriptOptions['auto-mode'];
if( $autoMode != 'off' )
{
    $autoMode = 'on';
    $importDir = eZPackage::repositoryPath() . "/$packageRepository";
    showWarning( "Processing in auto-mode: \n".
                 "- packages will be downloaded to '$packageDir';\n" .
                 "- packages will be imported to '$importDir';\n" .
                 "- installing of existing classes will be skipped;\n" .
                 "- all files(extesion, design, downloaded and imported packages) will be overwritten;" );
    $action = getUserInput( "Continue? [y/n]: ");
    if( strpos( $action, 'y' ) !== 0 )
        $script->shutdown( 0, 'Done' );
}

/**************************************************************
* do the work                                                 *
***************************************************************/

if( downloadPackages( $packageList, $packageURL, $packageDir, $packageRepository ) )
{
    if( file_exists( installScriptDir( $packageRepository ) ) )
    {
        //
        // Prepare siteaccesses access info.
        //
        $locales = eZContentLanguage::fetchLocaleList();
        $primaryLanguage = eZContentLanguage::topPriorityLanguage();

        $siteINI = eZINI::instance();
        $matchOrder = $siteINI->variableArray( 'SiteAccessSettings', 'MatchOrder' );
        $accessType = $matchOrder[0];
        $accessTypeValue = 'ezwebin_site';
        $adminAccessTypeValue = 'ezwebin_site_admin';

        $package = eZPackage::fetch( 'ezwebin_site' );

        if( $accessType == 'port' )
        {
            $portAccessList = $siteINI->group( 'PortAccessSettings' );
            $usedPorts = array_keys( $portAccessList );
            foreach( $portAccessList as $port => $siteaccess )
            {
                if( $siteaccess == $userSiteaccess )
                {
                    while( in_array( $port, $usedPorts ) )
                    {
                        // $port is used => get next port
                        ++$port;
                    }

                    $accessTypeValue = $port;
                    $usedPorts[] = $port;
                }
                else if( $siteaccess == $adminSiteaccess )
                {
                    while( in_array( $port, $usedPorts ) )
                    {
                        // $port is used => get next port
                        ++$port;
                    }

                    $adminAccessTypeValue = $port;
                    $usedPorts[] = $port;
                }
            }
        }

        //
        // Prepare install params.
        //
        $params = array( 'object_remote_map' => array( '1bb4fe25487f05527efa8bfd394cecc7' => 14,
                                                       '5f7f0bdb3381d6a461d8c29ff53d908f' => 11,
                                                       '15b256dbea2ae72418ff5facc999e8f9' => 42 ),
                         'package_object' => $package,
                         'design_list' => array( 'ezwebin_site',
                                                 'admin' ),
                         'user_siteaccess' => 'ezwebin_site',
                         'admin_siteaccess' => 'ezwebin_site_admin',
                         'site_type' => array( 'access_type' => $accessType,
                                               'access_type_value' => $accessTypeValue,
                                               'admin_access_type_value' => $adminAccessTypeValue ),
                         'all_language_codes' => $locales,
                         'primary_language' => $primaryLanguage->attribute( 'locale' ),
                         'host' => $siteINI->variable( 'SiteSettings', 'SiteURL' ) );

        $user = eZUser::currentUser();

        $installParameters = array( 'site_access_map' => array( '*' => $userSiteaccess ),
                                    'top_nodes_map' => array( '*' => 2 ),
                                    'design_map' => array( '*' => 'ezwebin_site' ),
                                    'language_map' => array( 'eng-GB' => $primaryLanguage->attribute( 'locale' ) ),
                                    'restore_dates' => true,
                                    'user_id' => $user->attribute( 'contentobject_id' ),
                                    'non-interactive' => true );

        //
        // Do the job
        //
        include_once( installScriptDir( $packageRepository ) . "/settings/ezwebininstaller.php" );
        $webinInstaller = new eZWebinInstaller( $params );

        if( defined( 'EZWEBIN_INSTALLER_MAJOR_VERSION' ) && EZWEBIN_INSTALLER_MAJOR_VERSION >= "1.3" )
        {

            $webinInstaller->createSiteAccess( array( 'src' => array( 'siteaccess' => $adminSiteaccess ),
                                                      'dst' => array( 'siteaccess' => 'ezwebin_site_admin' ) ) );
            $webinInstaller->createSiteAccess( array( 'src' => array( 'siteaccess' => $userSiteaccess ),
                                                      'dst' => array( 'siteaccess' => 'ezwebin_site' ) ) );

            $webinInstaller->preInstall();

            installPackages( $packageList, $installParameters );

            $webinInstaller->install();

            $siteaccessUrls = $webinInstaller->setting( 'siteaccess_urls' );
        }
        else
        {
            //
            // BC for eZWebin < 1.3
            //

            $siteInstaller = new eZSiteInstaller();

            $params['locales'] = $params['all_language_codes'];

            // extra siteaccess based on languages info, like 'eng', 'rus', ...
            $params['language_based_siteaccess_list'] = $siteInstaller->languageNameListFromLocaleList( $params['locales'] );

            $params['user_siteaccess_list'] = array_merge( array( $params['user_siteaccess'] ),
                                                           $params['language_based_siteaccess_list'] );
            $params['all_siteaccess_list'] = array_merge( $params['user_siteaccess_list'],
                                                          $params['admin_siteaccess'] );
            $params['main_site_design'] = 'ezwebin';


            // Create siteaccesses URLs
            $siteaccessUrls = array( 'admin'       => $siteInstaller->createSiteaccessUrls( array( 'siteaccess_list' => array( $params['admin_siteaccess'] ),
                                                                                                   'access_type' => $accessType,
                                                                                                   'port' => $adminAccessTypeValue,
                                                                                                   'host' => $params['host'] ) ),
                                     'user'        => $siteInstaller->createSiteaccessUrls( array( 'siteaccess_list' => array( $params['user_siteaccess'] ),
                                                                                                   'access_type' => $accessType,
                                                                                                   'port' => $accessTypeValue,
                                                                                                   'host' => $params['host'] ) ),
                                     'translation' => $siteInstaller->createSiteaccessUrls( array( 'siteaccess_list' => $params['language_based_siteaccess_list'],
                                                                                                   'access_type' => $accessType,
                                                                                                   'port' => $accessTypeValue + 1, // $accessTypeValue is for 'ezwein_site_user', so take next port number.
                                                                                                   'host' => $params['host'],
                                                                                                   'exclude_port_list' => array( $adminAccessTypeValue,
                                                                                                                                 $accessTypeValue ) ) ) );
            $params['siteaccess_urls'] = $siteaccessUrls;

            // prepare 'admin_url' for 'eZSiteINISettings'. Will unset it later.
            $params['siteaccess_urls']['admin_url'] = $siteaccessUrls['admin']['ezwebin_site_admin']['url'];

            // Include setting files
            $settingsFiles = $package->attribute( 'settings-files' );
            foreach( $settingsFiles as $settingsFileName )
                include_once( installScriptDir( $packageRepository ) . '/settings/' . $settingsFileName );

            $siteInstaller->createSiteAccess( array( 'src' => array( 'siteaccess' => $adminSiteaccess ),
                                                     'dst' => array( 'siteaccess' => 'ezwebin_site_admin' ) ) );
            $siteInstaller->createSiteAccess( array( 'src' => array( 'siteaccess' => $userSiteaccess ),
                                                     'dst' => array( 'siteaccess' => 'ezwebin_site' ) ) );

            // Call user function for additional setup tasks.
            if ( function_exists( 'eZSitePreInstall' ) )
                eZSitePreInstall();

            installPackages( $packageList, $installParameters );

            $settings = array();
            $settings[] = array( 'settings_dir' => 'settings/siteaccess/' . $params['user_siteaccess'],
                                 'groups' => eZSiteINISettings( $params ) );
            $settings[] = array( 'settings_dir' => 'settings/siteaccess/' . $params['admin_siteaccess'],
                                 'groups' => eZSiteAdminINISettings( $params ) );
            $settings[] = array( 'settings_dir' => 'settings/override',
                                 'groups' => eZSiteCommonINISettings( $params ) );


            foreach( $settings as $settingsGroup )
            {
                resetINI( $settingsGroup, 'override.ini' );
                $siteInstaller->updateINIFiles( $settingsGroup );
            }

            // 'admin_url' is not needed anymore.
            unset( $params['siteaccess_urls']['admin_url'] );

            updateINIAccessType( $accessType, $params );

            $siteInstaller->updateRoles( array( 'roles' => eZSiteRoles( $params ) ) );
            $siteInstaller->updatePreferences( array( 'prefs' => eZSitePreferences( $params ) ) );

            setVersion( 'ezwebin', '1.2.0' );

            postInstallAdminSiteaccessINIUpdate( $params );
            postInstallUserSiteaccessINIUpdate( $params );
            createTranslationSiteAccesses( $params );

            // updateTemplateLookClassAttributes() and updateTemplateLookObjectAttributes();
            $classIdentifier = 'template_look';
            $newAttributeIdArr = expandClass( $classIdentifier );
            foreach( $newAttributeIdArr as $id )
            {
                updateObject( $classIdentifier, $id );
            }
            $templateLookData = templateLookObjectData( $params );
            $siteInstaller->updateContentObjectAttributes( array( 'object_id' => $webinInstaller->setting( 'template_look_object_id' ),
                                                                  'attributes_data' => $templateLookData ) );


            $siteInstaller->swapNodes( array( 'src_node' => array( 'name' => "eZ Publish" ),
                                              'dst_node' => array( 'name' => "Home" ) ) );
            $siteInstaller->removeContentObject( array( 'name' => 'eZ Publish' ) );

            $webinInstaller->postInstall();
        }

        //
        // Output installation status.
        //
        showMessage2( 'Installation complete.' );

        showMessage( 'URLs to access eZWebin sites:' );

        foreach( $siteaccessUrls as $siteaccessType => $siteaccessInfo )
        {
            showMessage( "  $siteaccessType:" );
            foreach( $siteaccessInfo as $siteaccessName => $urlInfo )
            {
                showMessage( "    $siteaccessName: " . $urlInfo['url'] );
            }
        }
    }
    else
    {
        showWarning( "Unable to find installtion script dir." );
    }
}


$script->shutdown( 0, 'Done' );

?>

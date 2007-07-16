#!/usr/bin/env php
<?php
//
// Created on: <26-Jun-2007 15:00:00 dl>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
include_once( 'kernel/classes/ezcontentlanguage.php' );
include_once( '../ezbacktrace.php' );



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
    $packageRepository = "ez_systems";
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
                         'host' => $siteINI->variable( 'SiteSettings', 'SiteURL' ) );

        $user =& eZUser::currentUser();

        $installParameters = array( 'site_access_map' => array( '*' => $userSiteaccess ),
                                    'top_nodes_map' => array( '*' => 2 ),
                                    'design_map' => array( '*' => 'ezwebin_site' ),
                                    'language_map' => array( 'eng-GB' => $primaryLanguage->attribute( 'locale' ) ),
                                    'restore_dates' => true,
                                    'user_id' => $user->attribute( 'contentobject_id' ),
                                    'non-interactive' => true );

        include_once( installScriptDir( $packageRepository ) . "/settings/ezwebininstaller.php" );

        $installer = new eZWebinInstaller( $params );

        $installer->createSiteAccess( array( 'src' => array( 'siteaccess' => $adminSiteaccess ),
                                             'dst' => array( 'siteaccess' => 'ezwebin_site_admin' ) ) );
        $installer->createSiteAccess( array( 'src' => array( 'siteaccess' => $userSiteaccess ),
                                             'dst' => array( 'siteaccess' => 'ezwebin_site' ) ) );

        $installer->preInstall();

        installPackages( $packageList, $installParameters );

        $installer->install();

        showMessage2( 'Installation complete.' );

        $siteaccassUrls = $installer->setting( 'siteaccess_urls' );

        showMessage( 'URLs to access eZWebin sites:' );

        foreach( $siteaccassUrls as $siteaccessType => $siteaccessInfo )
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

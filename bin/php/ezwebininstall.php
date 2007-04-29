#!/usr/bin/env php
<?php
//
// Created on: <18-Apr-2007 15:00:00 dl>
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

// eZWebin upgrade Script
// file  bin/php/ezwebinupgrade.php


/*!
 define constans
*/
define( "EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT", 'q' );
define( "EZ_INSTALL_PACKAGE_EXTRA_ACTION_SKIP_PACKAGE", 's' );

/*!
 define global vars
*/
global $cli;
global $script;


/*!
 includes
*/
include_once( 'kernel/classes/ezscript.php' );
include_once( 'kernel/common/i18n.php' );
include_once( 'kernel/classes/ezpackage.php' );


/*!
 'cli->output' wrappers
*/
function showError( $message, $addEOL = true, $bailOut = true )
{
    global $cli;
    global $script;

    $cli->output( $cli->stylize( 'error', "Error: " ) .  $message, $addEOL );

    if( $bailOut )
    {
        exit();
        $script->shutdown( 1 );
    }
}

function showWarning( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'warning', "Warning: " ) . $message, $addEOL );
}

function showNotice( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'notice', "Notice: " ) .  $message, $addEOL );
}

function showMessage( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'blue', $message ), $addEOL );
}

function showMessage2( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'red', $message ), $addEOL );
}

function showPackageActions( $actionList )
{
    foreach( $actionList as $action => $actionDescription )
    {
        showMessage( "    [ $action ]: " . $actionDescription );
    }
}


/*!
 add extra actions to default package item's actions
*/
function getExtraActions( &$actionList )
{
    $actionList[EZ_INSTALL_PACKAGE_EXTRA_ACTION_SKIP_PACKAGE] = "Skipt rest of the package";
    $actionList[EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT] = "Quit";
}

/*!
 prompt user to choose what to do next
*/
function getUserInput( $prompt )
{
    $stdin = fopen( "php://stdin", "r+" );

    fwrite( $stdin, $prompt );

    $userInput = fgets( $stdin );

    fclose( $stdin );

    return $userInput;
}

/*!
 handle installation error:
     - show error message;
     - ask user what to do next;
*/
function handlePackageError( $error )
{
    showWarning( $error['description'] );

    $actionList = $error['actions'];
    getExtraActions( $actionList );

    showPackageActions( $actionList );

    $actions = array_keys( $actionList );
    $actions = '[' . implode( '], [', $actions ) . ']';

    $userInput = getUserInput( "    Plese choose one of the following actions( $actions ): " );

    return $action;
}

function checkDir( $dirName )
{
    if ( !file_exists( $dirName ) )
    {
        $action = getUserInput( "Directory '$dirName' doesn't exist. Create? [y/n]: ");
        if( strpos( $action, 'n' ) === 0 )
            showError( "Unable to continue. Aborting..." );

        if( !eZDir::mkdir( $dirName, eZDir::directoryPermission(), true ) )
            showError( "Unable to create dir '$dirName'. Aborting..." );
    }

    return true;
}

/*!
 download packages if neede.
    1. check if packages specified in $packageList exist in $packageRepository(means already downloaded and imported).
       if yes - ask user to do download or not. If not - go out
    2. check $packgesList exists in $packageDir(means packages downloaded but not imported)
       if yes - ask user to import or not. If not - go out
    3. download and import.
*/
function downloadPackages( $packageList, $packageURL, $packageDir, $packageRepository )
{
    global $cli;

    showMessage2( "Configuring..." );

    if ( !is_array( $packageList ) || count( $packageList ) == 0 )
        showError( "Package list is empty. Aborting..." );

    // 1. check if packages specified in $packageList exist in $packageRepository(means already downloaded and imported).
    //    if yes - ask user to do download or not. If not - go out
    foreach( array_keys( $packageList ) as $k )
    {
        $packageName = $packageList[$k];
        $package = eZPackage::fetch( $packageName );

        if( is_object( $package ) )
        {
            $action = getUserInput( "Package '$packageName' already imported. Import it anyway? [y/n]: " );
            if ( strpos( $action, 'n' ) === 0 )
                unset( $packageList[$k] );
            else
            {
                eZDir::recursiveDelete( eZPackage::repositoryPath() . "/$packageRepository/$packageName" );
            }
        }
    }

    if( count( $packageList ) == 0 )
    {
        // all packages are imported.
        return true;
    }

    // 2. check $packgesList exists in $packageDir(means packages downloaded but not imported)
    //    if yes - ask user to import or not. If not - go out
    if( !checkDir( $packageDir ) )
        return false;

    $downloadPackageList = array();
    foreach( $packageList as $packageName )
    {
        if( file_exists( "$packageDir/$packageName.ezpkg" ) )
        {
            $action = getUserInput( "Package '$packageName' already downloaded. Download it anyway? [y/n]: " );
            if ( strpos( $action, 'n' ) === 0 )
                continue;
        }

        $downloadPackageList[] = $packageName;
    }

    //
    // download
    //
    showMessage2( "Downloading..." );
    if( count( $downloadPackageList ) > 0 )
    {
        // TODO: using 'eZStepSiteTypes' is hack.
        //       need to exclude 'downloadFile' from that class.
        include_once( 'kernel/setup/steps/ezstep_site_types.php' );

        $tpl = false;
        $http = false;
        $ini = false;
        $persistenceList = false;

        $downloader = new eZStepSiteTypes( $tpl, $http, $ini, $persistenceList );

        foreach( $downloadPackageList as $packageName )
        {
            showMessage( "$packageName" );
            $archiveName = $downloader->downloadFile( "$packageURL/$packageName.ezpkg", $packageDir );
            if ( $archiveName === false )
                showError( "Error while downloading: " . $downloader->ErrorMsg );
        }
    }

    //
    // import
    //
    showMessage2( "Importing..." );
    foreach( $packageList as $packageName )
    {
        showMessage( "$packageName" );
        $package = eZPackage::import( "$packageDir/$packageName.ezpkg", $packageName, false, $packageRepository );

        if( !is_object( $package ) )
            showError( "Faild to import '$packageName' package: err = $package" );
    }

    return true;
}

/*!
 install packages
*/
function installPackages( $packageList )
{
    global $cli;

    showMessage2( "Installing..." );

    $action = false;
    while( ( list( , $packageName ) = each( $packageList ) ) && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT )
    {
        $action = false;

        $cli->output( $cli->stylize( 'emphasize', "Installing package '$packageName'" ), true );

        $params = array( 'install_type' => 'install' );

        $package = eZPackage::fetch( $packageName );
        if ( !is_object( $package ) )
        {
            showError( "can't fetch package '$packageName'. Aborting..." );
        }

        $packageItems = $package->installItemsList();
        while( ( list( , $item ) = each( $packageItems ) ) && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT
                                                           && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_SKIP_PACKAGE )
        {
            $itemInstalled = false;
            do
            {
                $action = false;
                $package->installItem( $item, $params );

                if ( isset( $params['error'] ) && is_array( $params['error'] ) && count( $params['error'] ) > 0 )
                {
                    $action = handlePackageError( $params['error'] );
                    $params['error']['choosen_action'] = $action;
                }
                else
                {
                    $itemInstalled = true;
                }
            }
            while( !$itemInstalled && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT
                                   && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_SKIP_PACKAGE );
        }
    }
}

// script initializing
$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "\n" .
                                                         "This script will upgrade ezwebin 1.1-1 to 1.2-0\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => false,
                                      'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[repository:][package:][package-dir:][url:]",
                                      "",
                                          array( 'repository' => "Path to repository where unpacked(unarchived) packages are \n" .
                                                             "placed. it's relative to 'var/[site.ini].[FileSettings].[StorageDir]/[package.ini].[RepositorySettings].[RepositoryDirectory]' \n".
                                                             "(default is 'var/storage/packages/ez_systems')",
                                             'package' => "Package(s) to install, f.e. 'ezwebin-classes'",
                                             'package-dir' => "Path to directory with packed(ezpkg) packages(default is '/tmp/ezwebin') ",
                                             'url' => "URL to download packages, f.e. 'http://packages.ez.no/ezpublish/3.9'.\n" .
                                                      "'package-dir' can be specified to store uploaded packages on local computer.\n" .
                                                      "if 'package-dir' is not specified then default dir('/tmp/ezwebin') will be used."
                                             ),
                                      false,
                                      array( 'user' => true )
                                     );

$script->initialize();

//
// process options
//


//
// 'repository'
//
$packageRepository = $scriptOptions['repository'];
if ( !$packageRepository )
{
    $packageRepository = "ez_systems";
}


//
// 'package'
//
$packageList = $scriptOptions['package'];
if ( !$packageList )
{
    $packageList = array(   'ezwebin_classes'
                          , 'ezwebin_extension'
                          // skipping content for now
                          //, 'ezwebin_banners'
                          //, 'ezwebin_democontent'
                          , 'ezwebin_design'
                        );
}
else
{
    $packageList = split( ' ', $packageList );
}

//
// package-dir
//
$packageDir = $scriptOptions['package-dir'] ? $scriptOptions['package-dir'] : "/tmp/ezwebin";

//
// url
//
$packageURL = $scriptOptions['url'];
if ( !$packageURL )
{
    $packageINI =& eZINI::instance( 'package.ini' );
    $packageURL = $packageINI->variable( 'RepositorySettings', 'RemotePackagesIndexURL' );
}

if( downloadPackages( $packageList, $packageURL, $packageDir, $packageRepository ) )
{
    installPackages( $packageList );
}


$script->shutdown( 0, 'Done' );


// diff -U3 -r /projects/extensions/ezwebin/trunk/packages/ezwebin_extension.export/ezextension/ezwebin /projects/trunk/extension/ezwebin/

// while coping files(extension, design) it doesn't remove files which exists in current ezwebin but are removed in new ezwebin.
// while coping ask user to do if the same file exists(skip, override). Currently it's possible to skip or override whole package.
//
// add analyzing: before upgrading collect info about what was changed on user installation comparing to
//                current version(means version which is going to be upgraded)
// add merging of class attributes.
// problems:
//   'ezwebin_classes':
//      - can't replace 'frontpage' class cause it's used by top-level node
//  'ezwebin_banners':
//      - problem with replacing 'banners' object.
//  'ezwebin_democontent'
//      - problem with replacing 'partners' object. looks liek because of that aobjects are not published.
//  'ezwebin_design':
//      - removes old design but didn't copy new desing;


?>
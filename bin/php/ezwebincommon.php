<?php
//
// Created on: <26-Jun-2007 15:00:00 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

// eZWebin install/updagrade helper routines.
// file  bin/php/ezwebincommon.php


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
require_once 'autoload.php';

/**************************************************************
* 'cli->output' wrappers                                      *
***************************************************************/
function showError( $message, $addEOL = true, $bailOut = true )
{
    global $cli;
    global $script;

    $cli->output( $cli->stylize( 'error', "Error: " .  $message ), $addEOL );

    if( $bailOut )
    {
        $script->shutdown( 1 );
    }
}

function showWarning( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'warning', "Warning: " . $message ), $addEOL );
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

/*!
 Show available actions to user
*/
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
function getExtraActions( $actionList )
{
    $actionList[EZ_INSTALL_PACKAGE_EXTRA_ACTION_SKIP_PACKAGE] = "Skipt rest of the package";
    $actionList[EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT] = "Quit";
    return $actionList;
}

/*!
 prompt user to choose what to do next
*/
function getUserInput( $prompt )
{
    $stdin = fopen( "php://stdin", "r+" );

    fwrite( $stdin, $prompt );

    $userInput = fgets( $stdin );
    $userInput = trim( $userInput, "\n" );

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
    $actionList = getExtraActions( $actionList );

    showPackageActions( $actionList );

    $actions = array_keys( $actionList );
    $actions = '[' . implode( '], [', $actions ) . ']';

    $userInput = getUserInput( "    Plese choose one of the following actions( $actions ): " );

    return $userInput;
}

/*!
 Check if dir $dirName exists. If not, ask user to create it.
*/
function checkDir( $dirName )
{
    if ( !file_exists( $dirName ) )
    {
        global $autoMode;
        if( $autoMode == 'on' )
        {
            $action = 'y';
        }
        else
        {
            $action = getUserInput( "Directory '$dirName' doesn't exist. Create? [y/n]: ");
        }

        if( strpos( $action, 'n' ) === 0 )
            showError( "Unable to continue. Aborting..." );

        if( !eZDir::mkdir( $dirName, false, true ) )
            showError( "Unable to create dir '$dirName'. Aborting..." );
    }

    return true;
}

function installScriptDir( $packageRepository, $packageName )
{
    return ( eZPackage::repositoryPath() . "/$packageRepository/$packageName" );
}

function defaultVendor()
{
    // $vendor is taken from 'ezwebin_site' package.
    $vendor = 'eZ systems';
    return $vendor;
}

function repositoryByVendor( $vendor )
{
    // it's copy/paste from eZPackage
    $trans = eZCharTransform::instance();
    $repository = $trans->transformByGroup( $vendor, 'urlalias' );
    return $repository;
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
            global $autoMode;
            if( $autoMode == 'on' )
            {
                $action = 'y';
            }
            else
            {
                $action = getUserInput( "Package '$packageName' already imported. Import it anyway? [y/n]: " );
            }

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
            global $autoMode;
            if( $autoMode == 'on' )
            {
                $action = 'y';
            }
            else
            {
                $action = getUserInput( "Package '$packageName' already downloaded. Download it anyway? [y/n]: " );
            }

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
                showError( "download error - " . $downloader->ErrorMsg );
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
function installPackages( $packageList, $params = array() )
{
    global $cli;

    showMessage2( "Installing..." );

    // copy/paste from eZPackage
    if ( !isset( $params['path'] ) )
        $params['path'] = false;

    // process packages
    $action = false;
    while( ( list( , $packageName ) = each( $packageList ) ) && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT )
    {
        $action = false;

        $cli->output( $cli->stylize( 'emphasize', "Installing package '$packageName'" ), true );

        $package = eZPackage::fetch( $packageName );
        if ( !is_object( $package ) )
        {
            showError( "can't fetch package '$packageName'. Aborting..." );
        }

        // skip package which can not be installed(e.g. which can be imported only, like 'design' and 'site' types)
        if ( $package->attribute( 'install_type' ) != 'install' )
        {
            continue;
        }

        $packageType = $package->attribute( 'type' );
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
                    global $autoMode;
                    if( $autoMode == 'on' )
                    {
                        switch( $packageType )
                        {
                            case 'contentclass':
                                $action = 2;
                                break;
                            case 'extension':
                                $action = 1;
                                break;
                            default:
                                $action = handlePackageError( $params['error'] );
                                break;
                        }
                    }
                    else
                    {
                        $action = handlePackageError( $params['error'] );
                    }

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

function siteAccessMap( $siteAccessNameArray )
{
    if( is_array( $siteAccessNameArray ) )
    {
        //Build array map of checked siteaccesses.
        //Siteaccess name as key, points to root dir, to be used in eZINI methods.
        $siteAccessMap = array();
        foreach( $siteAccessNameArray as $siteAccessName )
        {
            $mapEntry = checkSiteaccess( $siteAccessName );
            $siteAccessMap[] = $mapEntry;
        }
        return $siteAccessMap;
    }
    else
    {
        return false;
    }
}

function checkSiteaccess( $siteAccess, $bailOutOnError = false )
{
    $extensionBaseDir = eZExtension::baseDirectory();
    $extensionNameArray = eZExtension::activeExtensions();
    $siteAccessSettingsDir = '/settings/siteaccess/';
    $siteAccessExists = false;

    if( file_exists( 'settings/siteaccess/' . $siteAccess ) )
    {
        $siteAccessExists = true;
    }
    else
    {
        // Not found, check if it exists in extensions
        foreach( $extensionNameArray as $extensionName )
        {
            $extensionSiteaccessPath = $extensionBaseDir . '/' . $extensionName . $siteAccessSettingsDir . $siteAccess;
            if( file_exists( $extensionSiteaccessPath ) )
            {
                $siteAccessExists = true;
                break;
            }
        }
    }

    if( !$siteAccessExists && $bailOutOnError )
    {
        showError( "Siteaccess '" . $siteAccess . "' does not exist. Exiting..." );
    }

    return $siteAccessExists;
}

//
// For BC with eZWebin 1.2
//
function postInstallAdminSiteaccessINIUpdate( $params )
{
    $siteINI = eZINI::instance( "site.ini.append.php", "settings/siteaccess/" . $params['admin_siteaccess'], null, false, null, true );
    $siteINI->setVariable( "DesignSettings", "SiteDesign", $params['admin_siteaccess'] );
    $siteINI->setVariable( "DesignSettings", "AdditionalSiteDesignList", array( "admin" ) );
    $siteINI->setVariable( "SiteAccessSettings", "RelatedSiteAccessList", $params['all_siteaccess_list'] );
    $siteINI->setVariable( "FileSettings", "VarDir", "var/ezwebin_site" );
    $siteINI->save();
}

function postInstallUserSiteaccessINIUpdate( $params )
{
    $siteINI = eZINI::instance( "site.ini.append.php", "settings/siteaccess/" . $params['user_siteaccess'], null, false, null, true );
    $siteINI->setVariable( "DesignSettings", "SiteDesign", $params['main_site_design'] );
    $siteINI->setVariable( "SiteAccessSettings", "RelatedSiteAccessList", $params['all_siteaccess_list'] );
    $siteINI->setVariable( "FileSettings", "VarDir", "var/ezwebin_site" );
    $siteINI->save( false, false, false, false, true, true );
}

function createTranslationSiteAccesses( $params )
{
    foreach( $params['locales'] as $locale )
    {
        // Prepare 'SiteLanguageList':
        // make $locale as 'top priority language'
        // and append 'primary language' as fallback language.
        $primaryLanguage = $params['primary_language'];
        $languageList = array( $locale );
        if ( $locale != $primaryLanguage )
        {
            $languageList[] = $primaryLanguage;
        }

        eZSiteInstaller::createSiteAccess( array( 'src' => array( 'siteaccess' => $params['user_siteaccess'] ),
                                                  'dst' => array( 'siteaccess' => eZSiteInstaller::languageNameFromLocale( $locale ),
                                                                  'settings' => array( 'site.ini' => array( 'RegionalSettings' => array( 'Locale' => $locale,
                                                                                                                                         'ContentObjectLocale' => $locale,
                                                                                                                                         'TextTranslation' => $locale != 'eng-GB' ? 'enabled' : 'disabled',
                                                                                                                                         'SiteLanguageList' => $languageList ) ) ) ) ) );
    }
}

function resetINI( $settingsGroups, $iniToReset )
{
    foreach( $settingsGroups['groups'] as $settingsGroup )
    {
        if( $settingsGroup['name'] === $iniToReset )
        {
            $iniFilename = $settingsGroup['name'] . '.append.php';
            $ini = eZINI::instance( $iniFilename, $settingsGroups['settings_dir'] );
            $ini->reset();
        }
    }
}

function languageMatrixDefinition()
{
    $matrixDefinition = new eZMatrixDefinition();
    $matrixDefinition->addColumn( "Site URL", "site_url" );
    $matrixDefinition->addColumn( "Siteaccess", "siteaccess" );
    $matrixDefinition->addColumn( "Language name", "language_name" );
    $matrixDefinition->decodeClassAttribute( $matrixDefinition->xmlString() );

    return $matrixDefinition;
}

function templateLookObjectData( $params )
{
    $languageSettingsMatrixDefinition = languageMatrixDefinition();

    $siteaccessUrls = $params['siteaccess_urls'];

    // set 'language settings' matrix data
    $siteaccessAliasTable = array();
    foreach( $siteaccessUrls['translation'] as $name => $urlInfo )
    {
        $siteaccessAliasTable[] = $urlInfo['url'];
        $siteaccessAliasTable[] = $name;
        $siteaccessAliasTable[] = ucfirst( $name );
    }

    //create data array
    $templateLookData = array( "site_map_url" => array( "DataText" => "Site map",
                                                        "Content" => "/content/view/sitemap/2" ),
                                "tag_cloud_url" => array( "DataText" => "Tag cloud",
                                                          "Content" => "/content/view/tagcloud/2" ),
                                "login_label" => array( "DataText" => "Login" ),
                                "logout_label" => array( "DataText" => "Logout" ),
                                "my_profile_label" => array( "DataText" => "My profile" ),
                                "register_user_label" => array( "DataText" => "Register" ),
                                "rss_feed" => array( "DataText" => "/rss/feed/my_feed" ),
                                "shopping_basket_label" => array( "DataText" => "Shopping basket" ),
                                "site_settings_label" => array( "DataText" => "Site settings" ),
                                "language_settings" => array( "MatrixTitle" => "Language settings",
                                                              "MatrixDefinition" => $languageSettingsMatrixDefinition,
                                                              "MatrixCells" => $siteaccessAliasTable ),
                                "footer_text" => array( "DataText" => "Copyright &#169; 2007 eZ Systems AS. All rights reserved." ),
                                "hide_powered_by" => array( "DataInt" => 0 ),
                                "footer_script" => array( "DataText" => "" ) );

    return $templateLookData;
}

function updateINIAccessType( $accessType, $params )
{
    if( $accessType === 'url' || $accessType === 'url' )
        return;

    // avoid double check of 'hostname' and 'host'
    if( $accessType === 'hostname' || $accessType === 'host' )
        $accessType = 'hostname';

    $portMatch = array();
    $hostMatch = array();

    $siteINI = eZINI::instance( "site.ini.append.php", "settings/override", null, false, null, true );

    $siteaccessTypes = $params['siteaccess_urls'];

    // append webin's hosts to existing info.
    if( $accessType === 'hostname' || $accessType === 'host' )
        $hostMatch = $siteINI->variable( 'SiteAccessSettings', 'HostMatchMapItems' );

    foreach( $siteaccessTypes as $siteaccessList )
    {
        foreach( $siteaccessList as $siteaccessName => $urlInfo )
        {
            switch( $accessType )
            {
                case 'port':
                    {
                        $port = $urlInfo['port'];
                        $siteINI->setVariable( 'PortAccessSettings', $port, $siteaccessName );
                    } break;

                case 'hostname':
                    {
                        $host = $urlInfo['host'];
                        $hostMatch[] = $host . ';' . $siteaccessName;
                    }
            }
        }
    }

    if( $accessType === 'hostname' )
        $siteINI->setVariable( 'SiteAccessSettings', 'HostMatchMapItems', $hostMatch );

    $siteINI->save( false, false, false, false, true, true );
}

?>

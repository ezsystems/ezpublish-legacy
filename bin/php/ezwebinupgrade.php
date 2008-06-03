#!/usr/bin/env php
<?php
//
// Created on: <18-Apr-2007 15:00:00 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

// eZWebin upgrade Script
// file  bin/php/ezwebinupgrade.php


/*!
 define constans
*/

/*!
 define global vars
*/

/*!
 includes
*/
require 'autoload.php';
include_once( 'bin/php/ezwebincommon.php' );


function execUpdateFunction( $funcName, $toVersion )
{
    $funcName = "$funcName" . "_" . preg_replace( "/[.-]/", "_", $toVersion );

    if ( function_exists( $funcName ) )
    {
        $funcName();
    }
}

function upgradePackageListByWebinVersion( $version )
{
    $packageList = false;

    switch ( $version )
    {
        case '1.2-0':
            {
                $packageList = array(   'ezwebin_classes'
                                      , 'ezwebin_extension'
                                      // skipping content for now
                                      //, 'ezwebin_banners'
                                      //, 'ezwebin_democontent'
                                      , 'ezwebin_design'
                                      , 'ezwebin_site' );
            } break;
        case '1.3-0':
            {
                $packageList = array(   'ezwebin_classes'
                                      , 'ezwebin_extension'
                                      // skipping content for now
                                      //, 'ezwebin_banners'
                                      //, 'ezwebin_democontent'
                                      , 'ezwebin_design_blue'
                                      , 'ezwebin_design_gray'
                                      , 'ezwebin_site' );
            } break;
        default:
            break;
    }

    return $packageList;

}

/*!
 update content classes
*/
function updateClasses_1_2_0()
{
    $installer = new eZWebinInstaller();

    $installer->addClassAttributes( array( 'class' => array( 'identifier' => 'folder' ),
                                           'attributes' => array( array( 'identifier' => 'tags',
                                                                         'name' => 'Tags',
                                                                         'data_type_string' => 'ezkeyword' ),
                                                                  array( 'identifier' => 'publish_date',
                                                                         'name' => 'Publish date',
                                                                         'data_type_string' => 'ezdatetime',
                                                                         'default_value' => 0 ) ) ) );

    $installer->addClassAttributes( array( 'class' => array( 'identifier' => 'template_look' ),
                                           'attributes' => array( array( 'data_type_string' => 'ezurl',
                                                                         'name' => 'Tag Cloud URL',
                                                                         'identifier' => 'tag_cloud_url' ) ) ) );

    $installer->updateClassAttributes( array( 'class' => array( 'identifier' => 'folder' ),
                                              'attributes' => array( array( 'identifier' => 'show_children',
                                                                            'new_name' => 'Display sub items' ) ) ) );

    $installer->setRSSExport( array( 'creator' => '14',
                                     'access_url' => 'my_feed',
                                     'main_node_only' => '1',
                                     'number_of_objects' => '10',
                                     'rss_version' => '2.0',
                                     'status' => '1',
                                     'title' => 'My RSS Feed',
                                     'rss_export_itmes' => array( 0 => array( 'class_id' => '16',
                                                                              'description' => 'intro',
                                                                              'source_node_id' => '153',
                                                                              'status' => '1',
                                                                              'title' => 'title' ) ) ) );
}

/*!
 update content objects
*/
function updateObjects_1_2_0()
{
    $installer = new eZWebinInstaller();

    $templateLookData = array( "tag_cloud_url" => array( "DataText" => "Tag cloud",
                                                         "Content" => "/content/view/tagcloud/2" ),
                               "footer_text" => array( "DataText" => "Copyright &#169; 2007 eZ Systems AS. All rights reserved." ) );

    $installer->updateContentObjectAttributes( array( 'object_id' => $installer->setting( 'template_look_object_id' ),
                                                      'attributes_data' => $templateLookData ) );
}


/*!
*/
function updateINI_1_2_0()
{
    showMessage2( "Updating INI-files..." );

    $siteaccessList = getUserInput( "Please specify the eZ webin siteaccesses on your site (separated with space, for example eng nor): ");
    $siteaccessList = explode( ' ', $siteaccessList );

    $ezWebinSiteacceses = siteAccessMap( $siteaccessList );

    $parameters = array();

    $extraSettings = array();
    $extraSettings[] = eZSiteOverrideINISettings();
    $extraSettings[] = eZSiteImageINISettings();
    $extraSettings[] = eZSiteContentINISettings( $parameters );
    $extraSettings[] = eZSiteDesignINISettings( $parameters );
    $extraSettings[] = eZSiteBrowseINISettings( $parameters );
    $extraSettings[] = eZSiteTemplateINISettings( $parameters );

    $extraCommonSettings = array();
    $extraCommonSettings[] = eZCommonContentINISettings( $parameters );

    //The following INI-files should be modified instead of being replaced
    $modifiableINIFiles = array();
    $modifiableINIFiles[] = 'design.ini';


    foreach ( $ezWebinSiteacceses as $sa )
    {
        if( $sa and is_array( $sa ) )
        {
            $saName = key($sa);
            $saPath = current($sa);

            // NOTE: it's copy/paste from ezstep_create_sites.php
            foreach ( $extraSettings as $extraSetting )
            {
                if ( $extraSetting === false )
                    continue;

                $iniName = $extraSetting['name'];
                $settings = $extraSetting['settings'];
                $resetArray = false;
                if ( isset( $extraSetting['reset_arrays'] ) )
                    $resetArray = $extraSetting['reset_arrays'];

                if ( in_array( $iniName, $modifiableINIFiles ) )
                {
                    //Certain INI files we don't want to replace fully, for instance design.ini can have other values for sitestyles.
                    $iniToModify = $iniName . '.append.php';
                    $tmpINI = eZINI::instance( $iniToModify, $saPath );
                    // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
                    $tmpINI->setReadOnlySettingsCheck( false );
                    $tmpINI->setVariables( $settings );
                    $tmpINI->save( false, false, false, false, $saPath, $resetArray );
                }
                else
                {
                    //Replace new INI files eZ webin 1.2 accordingly.
                    $tmpINI = eZINI::create( $iniName );
                    // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
                    $tmpINI->setReadOnlySettingsCheck( false );
                    $tmpINI->setVariables( $settings );
                    $tmpINI->save( false, '.append.php', false, true, $saPath, $resetArray );
                }
            }
        }
    }

    foreach ( $extraCommonSettings as $extraSetting )
    {
        if ( $extraSetting === false )
            continue;

        $iniName = $extraSetting['name'];
        $settings = $extraSetting['settings'];
        $resetArray = false;
        if ( isset( $extraSetting['reset_arrays'] ) )
            $resetArray = $extraSetting['reset_arrays'];

        if ( file_exists( 'settings/override/' . $iniName . '.append' ) ||
             file_exists( 'settings/override/' . $iniName . '.append.php' ) )
        {
            $tmpINI = eZINI::instance( $iniName, 'settings/override', null, null, false, true );
        }
        else
        {
            $tmpINI = eZINI::create( $iniName );
        }
        // Set ReadOnlySettingsCheck to false: towards
        // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
        $tmpINI->setReadOnlySettingsCheck( false );
        $tmpINI->setVariables( $settings );
        $tmpINI->save( false, '.append.php', false, true, "settings/override", $resetArray );
    }
}


function isValidWebinUpgradeVersion( $version )
{
    $isValid = false;

    switch( $version )
    {
        case '1.2-0':
        case '1.3-0':
            {
                $isValid = true;
            } break;
        default:
            break;
    }

    return $isValid;
}

// script initializing
$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                        "This script will upgrade ezwebin." ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => false,
                                     'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[to-version:][repository:][package:][package-dir:][url:][auto-mode:]",
                                      "",
                                      array( 'to-version' => "Specify what upgrade path to use. \n" .
                                                             " available options: '1.2-0' - upgrade 1.1-1 to 1.2-0\n" .
                                                             "                    '1.3-0' - upgrade 1.2-0 to 1.3-0",
                                             'repository' => "Path to repository where unpacked(unarchived) packages are \n" .
                                                         "placed. it's relative to 'var/[site.ini].[FileSettings].[StorageDir]/[package.ini].[RepositorySettings].[RepositoryDirectory]' \n".
                                                         "(default is 'var/storage/packages/ez_systems')",
                                             'package' => "Package(s) to install, f.e. 'ezwebin_classes'",
                                             'package-dir' => "Path to directory with packed(ezpkg) packages(default is '/tmp/ezwebin') ",
                                             'url' => "URL to download packages, f.e. 'http://packages.ez.no/ezpublish/3.9'.\n" .
                                                      "'package-dir' can be specified to store uploaded packages on local computer.\n" .
                                                      "if 'package-dir' is not specified then default dir('/tmp/ezwebin') will be used.",
                                             'auto-mode' => "[on/off]. Do not ask what to do in case of confilicts. By default is 'on'"
                                           ),
                                      false,
                                      array( 'user' => true )
                                     );


if ( !$scriptOptions['siteaccess'] )
{
    showNotice( "No siteaccess provided, will use default siteaccess" );
}
else
{
    $siteAccessExists = checkSiteaccess( $scriptOptions['siteaccess'] );

    if ( $siteAccessExists )
    {
        showNotice( "Using siteaccess " . $scriptOptions['siteaccess'] );
        $script->setUseSiteAccess( $scriptOptions['siteaccess'] );
    }
    else
    {
        showError( "Siteaccess '" . $scriptOptions['siteaccess'] . "' does not exist. Exiting..." );
    }
}
$script->initialize();


/**************************************************************
* process options                                             *
***************************************************************/

$toVersion = '1.2-0';
if ( $scriptOptions['to-version'] )
{
    $version = $scriptOptions['to-version'];
    if ( isValidWebinUpgradeVersion( $version ) )
    {
        $toVersion = $version;
    }
    else
    {
        showError( "invalid '--to-version' option" );
    }
}

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
$packageList = $scriptOptions['package'];
if ( !$packageList )
{
    $packageList = upgradePackageListByWebinVersion( $toVersion );
}
else
{
    $packageList = split( ' ', $packageList );
}

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
    // install
    installPackages( $packageList );
}

if( file_exists( installScriptDir( $packageRepository ) ) )
{
    include_once( installScriptDir( $packageRepository ) . "/settings/ezwebininstaller.php" );
    include_once( installScriptDir( $packageRepository ) . "/settings/ini-site.php" );
    include_once( installScriptDir( $packageRepository ) . "/settings/ini-common.php" );

    showMessage2( "Updating content classes..." );
    execUpdateFunction( "updateClasses", $toVersion );

    showMessage2( "Updating content objects..." );
    execUpdateFunction( "updateObjects", $toVersion );

    showMessage2( "Updating INI-files..." );
    execUpdateFunction( "updateINI", $toVersion );
}
else
{
    showWarning( "no data for updating content classes, objects, roles, ini" );
}

showMessage2( "Upgrade complete" );
$script->shutdown( 0 );

?>

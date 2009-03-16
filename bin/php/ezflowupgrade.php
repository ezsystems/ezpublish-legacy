#!/usr/bin/env php
<?php
//
// Created on: <16-Mar-2009 15:00:00 ls>
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

// eZ Flow upgrade Script
// file  bin/php/ezflowupgrade.php


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

function upgradePackageListByFlowVersion( $version )
{
    $packageList = false;

    switch ( $version )
    {
        case '1.1-0':
            {
                $packageList = array(   'ezflow_extension'
                                      , 'ezflow_classes'
                                      , 'ezwebin_extension'
                                      , 'ezflow_design'
                                      , 'ezflow_site' );
            } break;
        default:
            break;
    }

    return $packageList;

}

function isValidFlowUpgradeVersion( $version )
{
    $isValid = false;

    switch( $version )
    {
        case '1.1-0':
            {
                $isValid = true;
            } break;
        default:
            break;
    }

    return $isValid;
}

function updateINI_1_1_0()
{
    showMessage2( "Updating INI-files..." );

    $siteaccessList = getUserInput( "Please specify the eZ Flow siteaccesses on your site (separated with space, for example eng nor): ");
    $siteaccessList = explode( ' ', $siteaccessList );

    $translationSA = array();
    foreach( $siteaccessList as $siteaccess )
    {
        if( !file_exists( 'settings/siteaccess/' . $siteaccess ) )
            continue;

        /* Update override.ini.append.php part */
        $settings = array( 'full_silverlight' => array( 'Source' => 'node/view/full.tpl',
                                                        'MatchFile' => 'full/silverlight.tpl',
                                                        'Subdir' => 'templates',
                                                        'Match' => array( 'class_identifier' => 'silverlight' )  ),
                           'line_silverlight' => array( 'Source' => 'node/view/line.tpl',
                                                        'MatchFile' => 'line/silverlight.tpl',
                                                        'Subdir' => 'templates',
                                                        'Match' => array( 'class_identifier' => 'silverlight' )  ),
                           'edit_ezsubtreesubscription_forum_topic' => array( 'Source' => 'content/datatype/edit/ezsubtreesubscription.tpl',
                                                                              'MatchFile' => 'datatype/edit/ezsubtreesubscription/forum_topic.tpl',
                                                                              'Subdir' => 'templates',
                                                                              'Match' => array( 'class_identifier' => 'forum_topic' )  ),
                           'block_item_image' => array( 'Source' => 'node/view/block_item.tpl',
                                                        'MatchFile' => 'block_item/image.tpl',
                                                        'Subdir' => 'templates',
                                                        'Match' => array( 'class_identifier' => 'image' ) ),
                           'dynamic_3_items1' => array( 'Source' => 'block/view/view.tpl',
                                                        'MatchFile' => 'block/dynamic_3_items1.tpl',
                                                        'Subdir' => 'templates',
                                                        'Match' => array( 'type' => 'Dynamic3Items',
                                                                          'view' => '3_items1' ) ) );
        $ini = eZINI::instance( 'override.ini', 'settings/siteaccess/' . $siteaccess, null, null, false, true );
        $ini->setReadOnlySettingsCheck( false );
        $ini->setVariables( $settings );
        $ini->save( false, '.append.php', false, false, 'settings/siteaccess/' . $siteaccess, false );

        /* Update menu.ini.append.php part */
        $settings = array( 'SelectedMenu' => array( 'CurrentMenu' => 'DoubleTop',
                                                    'TopMenu' => 'double_top',
                                                    'LeftMenu' => '' ) );

        $ini = eZINI::instance( 'menu.ini', 'settings/siteaccess/' . $siteaccess, null, null, false, true );
        $ini->setReadOnlySettingsCheck( false );
        $ini->setVariables( $settings );
        $ini->save( false, '.append.php', false, false, 'settings/siteaccess/' . $siteaccess, false );

        /* Get site.ini for ContentObjectLocale code */
        $ini = eZINI::instance( 'site.ini', 'settings/siteaccess/' . $siteaccess, null, null, false, true );
        $contentObjectLocale = explode( '-', $ini->variable( 'RegionalSettings', 'ContentObjectLocale' ) );
        
        $translationSA[$siteaccess] = ucfirst( $contentObjectLocale[0] );
    }
    
    $settings = array( array( 'name' => 'site.ini', 
                              'settings' => array( 'RegionalSettings' => array( 'TranslationSA' => $translationSA ) ) ),
                       array( 'name' => 'content.ini',
                              'settings' => array( 'table' => array( 'CustomAttributes' => array( '0' => 'summary',
                                                                                                  '1' => 'caption' ) ),
                                                   'td' => array( 'CustomAttributes' => array( '0' => 'valign' ) ),
                                                   'th' => array( 'CustomAttributes' => array( '0' => 'scope',
                                                                                               '1' => 'abbr',
                                                                                               '2' => 'valign' ) ),
                                                   'CustomTagSettings' => array( 'AvailableCustomTags' => array( '0' => 'underline' ),
                                                                                                                 'IsInline' => array( 'underline' => 'true') ),
                                                   'embed-type_images' => array( 'AvailableClasses' => array() ) ) ),
                       array( 'name' => 'ezoe_attributes.ini',
                              'settings' => array( 'CustomAttribute_table_summary' => array( 'Name' => 'Summary (WAI)',
                                                                                             'Required' => 'true' ),
                                                   'CustomAttribute_scope' => array( 'Name' => 'Scope',
                                                                                     'Title' => 'The scope attribute defines a way to associate header cells and data cells in a table.',
                                                                                     'Type' => 'select',
                                                                                     'Selection' => array( '0' => '',
                                                                                                           'col' => 'Column',
                                                                                                           'row' => 'Row' ) ),
                                                   'CustomAttribute_valign' => array( 'Title' => 'Lets you define the vertical alignment of the table cell/ header.',
                                                                                      'Type' => 'select',
                                                                                      'Selection' => array( '0' => '',
                                                                                                            'top' => 'Top',
                                                                                                            'middle' => 'Middle',
                                                                                                            'bottom' => 'Bottom',
                                                                                                            'baseline' => 'Baseline' ) ),
                                                   'Attribute_table_border' => array( 'Type' => 'htmlsize',
                                                                                      'AllowEmpty' => 'true' ),
                                                   'CustomAttribute_embed_offset' => array( 'Type' => 'int',
                                                                                            'AllowEmpty' => 'true' ),
                                                   'CustomAttribute_embed_limit' => array( 'Type' => 'int',
                                                                                           'AllowEmpty' => 'true' ) ) ),
                       array( 'name' => 'ezxml.ini',
                              'settings' => array( 'TagSettings' => array( 'TagPresets' => array( '0' => '',
                                                                                                  'mini' => 'Simple formatting' ) ) ) ) );
    foreach ( $settings as $setting )
    {
        $iniName = $setting['name'];
        $onlyModified = false;
        if ( file_exists( 'settings/override/' . $iniName . '.append' ) ||
             file_exists( 'settings/override/' . $iniName . '.append.php' ) )
        {
        
            $ini = eZINI::instance( $iniName, 'settings/override', null, null, false, true );
        }
        else
        {
            $ini = eZINI::create( $iniName, 'settings/override' );
            $onlyModified = true;
        }
        $ini->setReadOnlySettingsCheck( false );
        $ini->setVariables( $setting['settings'] );
        $ini->save( false, '.append.php', false, $onlyModified, 'settings/override', false );
    }
}

// script initializing
$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                        "This script will upgrade eZ Flow." ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true,
                                     'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[to-version:][repository:][package:][package-dir:][url:][auto-mode:]",
                                      "",
                                      array( 'to-version' => "Specify what upgrade path to use. \n" .
                                                             " available options: '1.1-0' - upgrade 1.0-0 to 1.1-0",
                                             'repository' => "Path to repository where unpacked(unarchived) packages are \n" .
                                                         "placed. it's relative to 'var/[site.ini].[FileSettings].[StorageDir]/[package.ini].[RepositorySettings].[RepositoryDirectory]' \n".
                                                         "(default is 'var/storage/packages/eZ-systems')",
                                             'package' => "Package(s) to install, f.e. 'ezflow_classes'",
                                             'package-dir' => "Path to directory with packed(ezpkg) packages(default is '/tmp/ezflow') ",
                                             'url' => "URL to download packages, f.e. 'http://packages.ez.no/ezpublish/3.9'.\n" .
                                                      "'package-dir' can be specified to store uploaded packages on local computer.\n" .
                                                      "if 'package-dir' is not specified then default dir('/tmp/ezflow') will be used.",
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

$toVersion = '1.1-0';
if ( $scriptOptions['to-version'] )
{
    $version = $scriptOptions['to-version'];
    if ( isValidFlowUpgradeVersion( $version ) )
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
    $packageList = upgradePackageListByFlowVersion( $toVersion );
}
else
{
    $packageList = split( ' ', $packageList );
}

//
// 'package-dir' option
//
$packageDir = $scriptOptions['package-dir'] ? $scriptOptions['package-dir'] : "/tmp/ezflow";

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

if( file_exists( installScriptDir( $packageRepository, 'ezflow_site' ) ) )
{
    include_once( installScriptDir( $packageRepository, 'ezflow_site' ) . "/settings/ezflowinstaller.php" );
    include_once( installScriptDir( $packageRepository, 'ezflow_site' ) . "/settings/ini-site.php" );
    include_once( installScriptDir( $packageRepository, 'ezflow_site' ) . "/settings/ini-common.php" );

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

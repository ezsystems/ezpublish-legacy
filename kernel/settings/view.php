<?php
//
// Created on: <17-Jan-2004 12:41:17 oh>
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
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/common/template.php" );

$tpl =& templateInit();
$http =& eZHTTPTool::instance();
$ini =& eZINI::instance();
$siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

if ( $Params['INIFile'] )
    $settingFile = $Params['INIFile'];

if ( $http->hasPostVariable( 'selectedINIFile' )  )
    $settingFile = $http->variable( "selectedINIFile" );

if ( $Params['SiteAccess'] )
    $currentSiteAccess = $Params['SiteAccess'];

if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    $currentSiteAccess = $http->postVariable( 'CurrentSiteAccess' );

if ( !isset( $currentSiteAccess ) )
    $currentSiteAccess = $siteAccessList[0];

unset( $ini );
$ini = eZINI::instance( $settingFile, 'settings', null, null, false );
$ini->prependOverrideDir( "siteaccess/$currentSiteAccess", false, 'siteaccess' );
$ini->loadCache();

if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    $placements = $ini->groupPlacements();
    if ( $http->hasPostVariable( 'RemoveSettingsArray' ) )
    {
        $deletedSettingArray =& $http->postVariable( 'RemoveSettingsArray' );
        foreach ( $deletedSettingArray as $deletedSetting )
        {
            list( $block, $setting ) = split( ':', $deletedSetting );
            $placement = $ini->findSettingPlacement( $placements[$block][$setting] );
            if ( $placement == "undefined" )
            {
                foreach ( $placements[$block][$setting] as $settingElementKey=>$key )
                {
                    $elementPlacement = $ini->findSettingPlacement( $placements[$block][$setting][$settingElementKey] );
                    if ( $elementPlacement == "override" )
                    {
                        $placement = "override";
                        break;
                    }
                    if ( $elementPlacement == "siteaccess" )
                    {
                        $placement = "siteaccess";
                    }
                }
            }
            if ( $placement == 'siteaccess' )
                $path = "settings/siteaccess/$currentSiteAccess";
            else
                $path = 'settings/override';

            $iniTemp = eZINI::instance( $settingFile . '.append.php', $path, null, null, null, true );
            $iniTemp->removeSetting( $block, $setting );
            $iniTemp->save();
            unset( $iniTemp );
        }
    }
}


if ( $http->hasPostVariable( 'ChangeINIFile' ) or
     ( $Params['SiteAccess'] and $Params['INIFile'] ) )
{
    $ini = eZINI::instance( $settingFile, 'settings', null, null, false );
    $ini->prependOverrideDir( "siteaccess/$currentSiteAccess", false, 'siteaccess' );
    $ini->loadCache();

    $blocks = $ini->groups();
    $placements = $ini->groupPlacements();
    $settings = array();
    $blockCount = 0;
    $totalSettingCount = 0;

    foreach( $blocks as $block=>$key )
    {
        $settingsCount = 0;
        $blockRemoveable = false;
        foreach( $key as $setting=>$settingKey )
        {
            $hasSetPlacement = false;
            $type = $ini->settingType( $settingKey );
            $removeable = false;

            switch ( $type )
            {
                case 'array':
                    foreach( $settingKey as $settingElementKey=>$settingElementValue )
                    {
                        $settingPlacement = $ini->findSettingPlacement( $placements[$block][$setting][$settingElementKey] );
                        if ( $settingElementValue != null )
                            $settings[$block]['content'][$setting]['content'][$settingElementKey]['content'] = $settingElementValue;
                        else
                            $settings[$block]['content'][$setting]['content'][$settingElementKey]['content'] = "";
                        $settings[$block]['content'][$setting]['content'][$settingElementKey]['placement'] = $settingPlacement;
                        $hasSetPlacement = true;
                        if ( $settingPlacement != 'default' )
                        {
                            $removeable = true;
                            $blockRemoveable = true;
                        }
                    }
                    break;
                case 'string':
                    if( strpos( $settingKey, ';' ) )
                    {
                        // Make a space after the ';' to make it possible for
                        // the browser to break long lines
                        $settingArray = str_replace( ';', "; ", $settingKey );
                        $settings[$block]['content'][$setting]['content'] = $settingArray;
                    }
                    else
                    {
                        $settings[$block]['content'][$setting]['content'] = $settingKey;
                    }
                    break;
                default:
                    $settings[$block]['content'][$setting]['content'] = $settingKey;
            }
            $settings[$block]['content'][$setting]['type'] = $type;
            $settings[$block]['content'][$setting]['placement'] = "";

            if ( !$hasSetPlacement )
            {
                $placement = $ini->findSettingPlacement( $placements[$block][$setting] );
                $settings[$block]['content'][$setting]['placement'] = $placement;
                if ( $placement != 'default' )
                {
                    $removeable = true;
                    $blockRemoveable = true;
                }
            }
            $settings[$block]['content'][$setting]['removeable'] = $removeable;
            ++$settingsCount;
        }
        $settings[$block]['count'] = $settingsCount;
        $settings[$block]['removeable'] = $blockRemoveable;
        $totalSettingCount += $settingsCount;
        ++$blockCount;
    }
    ksort( $settings );
    $tpl->setVariable( 'settings', $settings );
    $tpl->setVariable( 'block_count', $blockCount );
    $tpl->setVariable( 'setting_count', $totalSettingCount );
    $tpl->setVariable( 'ini_file', $settingFile );
}
else
{
    $tpl->setVariable( 'settings', false );
    $tpl->setVariable( 'block_count', false );
    $tpl->setVariable( 'setting_count', false );
    $tpl->setVariable( 'ini_file', false );
}

$rootDir = 'settings';

$iniFiles = eZDir::recursiveFindRelative( $rootDir, '', '.ini' );
$iniFiles = str_replace('/', '', $iniFiles );
sort( $iniFiles );

$tpl->setVariable( 'ini_files', $iniFiles );
$tpl->setVariable( 'siteaccess_list', $siteAccessList );
$tpl->setVariable( 'current_siteaccess', $currentSiteAccess );



$Result = array();
$Result['content'] =& $tpl->fetch( 'design:settings/view.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'settings/view', 'Settings' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'settings/view', 'View' ),
                                'url' => false ) );

?>

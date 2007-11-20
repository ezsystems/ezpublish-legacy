<?php
//
// Created on: <17-Jan-2004 12:41:17 oh>
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
//include_once( "lib/ezutils/classes/ezhttptool.php" );
//include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
require_once( "kernel/common/template.php" );

$tpl = templateInit();
$http = eZHTTPTool::instance();
$ini = eZINI::instance();
$siteAccessList = $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );

if ( $Params['INIFile'] )
    $settingFile = $Params['INIFile'];

if ( $http->hasPostVariable( 'selectedINIFile' )  )
    $settingFile = $http->variable( "selectedINIFile" );

if ( $Params['SiteAccess'] )
    $currentSiteAccess = $Params['SiteAccess'];

if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    $currentSiteAccess = $http->postVariable( 'CurrentSiteAccess' );

if ( !isset( $currentSiteAccess ) or
     !in_array( $currentSiteAccess, $siteAccessList ) )
    $currentSiteAccess = $siteAccessList[0];

unset( $ini );

if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    if ( isset( $settingFile ) )
    {
        $ini = eZINI::create( $settingFile, 'settings', null, null, false );
        $ini->prependOverrideDir( "siteaccess/$currentSiteAccess", false, 'siteaccess' );
        $ini->loadCache();
    }

    $placements = $ini->groupPlacements();
    if ( $http->hasPostVariable( 'RemoveSettingsArray' ) )
    {
        $deletedSettingArray = $http->postVariable( 'RemoveSettingsArray' );
        foreach ( $deletedSettingArray as $deletedSetting )
        {
            list( $block, $setting ) = split( ':', $deletedSetting );

            if ( is_array( $placements[$block][$setting] ) )
            {
                foreach ( $placements[$block][$setting] as $settingElementKey=>$key )
                {
                    $placement = $ini->findSettingPlacement( $placements[$block][$setting][$settingElementKey] );
                    break;
                }
            }
            else
            {
                $placement = $ini->findSettingPlacement( $placements[$block][$setting] );
            }
            // Get extension name if exists, $placement might be "extension:ezdhtml"
            $exploded = explode( ':', $placement );
            $extension = $exploded[0] == 'extension'
                        ? $exploded[1]
                        : false;

            $path = 'settings/override';
            if ( $placement == 'siteaccess' )
                $path = "settings/siteaccess/$currentSiteAccess";
            elseif ( $placement != 'override' and $extension !== false )
                $path = "extension/$extension/settings";

            // We should use "reference" if multiply removing of ini setting.
            // if eZINI::instance() is called twice instance will be fetched from GLOBAL variable.
            // Without reference there will be a inconsistency with GLOBAL instance and stored ini file.
            $iniTemp = eZINI::create( $settingFile . '.append.php', $path, null, null, null, true );
            $iniTemp->removeSetting( $block, $setting );
            $iniTemp->save();
        }
    }
}


if ( $http->hasPostVariable( 'ChangeINIFile' ) or
     ( $Params['SiteAccess'] and $Params['INIFile'] ) )
{
    $ini = eZINI::create( $settingFile, 'settings', null, null, false );
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
        $blockEditable = true;
        foreach( $key as $setting=>$settingKey )
        {
            $hasSetPlacement = false;
            $type = $ini->settingType( $settingKey );
            $removeable = false;

            switch ( $type )
            {
                case 'array':
                    if ( count( $settingKey ) == 0 )
                        $settings[$block]['content'][$setting]['content'] = array();

                    foreach( $settingKey as $settingElementKey=>$settingElementValue )
                    {
                        $settingPlacement = $ini->findSettingPlacement( $placements[$block][$setting][$settingElementKey] );
                        if ( $settingElementValue != null )
                        {
                            // Make a space after the ';' to make it possible for
                            // the browser to break long lines
                            $settings[$block]['content'][$setting]['content'][$settingElementKey]['content'] = str_replace( ';', "; ", $settingElementValue );
                        }
                        else
                        {
                            $settings[$block]['content'][$setting]['content'][$settingElementKey]['content'] = "";
                        }
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
            $editable = $ini->isSettingReadOnly( $settingFile, $block, $setting );
            $removeable = $editable === false ? false : $removeable;
            $settings[$block]['content'][$setting]['editable'] = $editable;
            $settings[$block]['content'][$setting]['removeable'] = $removeable;
            ++$settingsCount;
        }
        $blockEditable = $ini->isSettingReadOnly( $settingFile, $block );
        $settings[$block]['count'] = $settingsCount;
        $settings[$block]['removeable'] = $blockRemoveable;
        $settings[$block]['editable'] = $blockEditable;
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
$Result['content'] = $tpl->fetch( 'design:settings/view.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'settings/view', 'Settings' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'settings/view', 'View' ),
                                'url' => false ) );

?>

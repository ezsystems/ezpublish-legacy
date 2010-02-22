<?php
//
// Definition of Menuconfig class
//
// Created on: <05-Mar-2004 14:34:34 bf>
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];


$ini = eZINI::instance();
$tpl = eZTemplate::factory();

if ( $module->isCurrentAction( 'SelectCurrentSiteAccess' ) )
{
    if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    {
        $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $http->postVariable( 'CurrentSiteAccess' ) );
    }
}

$siteAccessList = $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );
$siteAccess = false;

// Fetch siteaccess settings for the selected override
// Default to first defined siteacces if none are selected
if ( $http->hasSessionVariable( 'eZTemplateAdminCurrentSiteAccess' ) )
    $siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

if ( !in_array( $siteAccess, $siteAccessList ) )
    $siteAccess = $siteAccessList[0];

if ( $http->hasPostVariable( 'SelectCurrentSiteAccessButton' ) )
{
    $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $siteAccess );
}

// Get path to specified site access.
$pathToSiteAccess = eZSiteAccess::findPathToSiteAccess( $siteAccess );
$menuINI = eZINI::instance( "menu.ini", "", null, null, true );
$menuINI->prependOverrideDir( $pathToSiteAccess, true, 'siteaccess' );
$menuINI->loadCache();

/*$iniPath = "settings/siteaccess/$siteAccess";
$menuINI = eZINI::instance( 'menu.ini.append.php', $iniPath, null, false, null, true );*/

if ( $module->isCurrentAction( 'Store' ) )
{
    $menuType = $http->postVariable( 'MenuType' );

    $menuINI->setVariable( 'SelectedMenu', 'CurrentMenu', $menuType );
    $menuINI->setVariable( 'SelectedMenu', 'TopMenu', $menuINI->variable( $menuType, "TopMenu" ) );
    $menuINI->setVariable( 'SelectedMenu', 'LeftMenu', $menuINI->variable( $menuType, "LeftMenu" ) );

    $menuINI->save( "menu.ini.append.php", false, false, false, $pathToSiteAccess, true );

    // Delete compiled template
    $iniPath = $pathToSiteAccess;
    $siteINI = eZINI::instance( 'site.ini.append', $iniPath );
    if ( $siteINI->hasVariable( 'FileSettings', 'CacheDir' ) )
    {
        $cacheDir = $siteINI->variable( 'FileSettings', 'CacheDir' );
        if ( $cacheDir[0] == "/" )
        {
            $cacheDir = eZDir::path( array( $cacheDir ) );
        }
        else
        {
            if ( $siteINI->hasVariable( 'FileSettings', 'VarDir' ) )
            {
                $varDir = $siteINI->variable( 'FileSettings', 'VarDir' );
                $cacheDir = eZDir::path( array( $varDir, $cacheDir ) );
            }
        }
    }
    else if ( $siteINI->hasVariable( 'FileSettings', 'VarDir' ) )
    {
         $varDir = $siteINI->variable( 'FileSettings', 'VarDir' );
         $cacheDir = $ini->variable( 'FileSettings', 'CacheDir' );
         $cacheDir = eZDir::path( array( $varDir, $cacheDir ) );
    }
    else
    {
        $cacheDir =  eZSys::cacheDirectory();
    }
    $compiledTemplateDir = $cacheDir ."/template/compiled";
    eZDir::unlinkWildcard( $compiledTemplateDir . "/", "*pagelayout*.*" );

    // Expire template block cache
    eZContentCacheManager::clearTemplateBlockCacheIfNeeded( false );
}

$availableMenuArray = $menuINI->variable( 'MenuSettings', 'AvailableMenuArray' );

$menuArray = array();
foreach ( $availableMenuArray as $menuType )
{
    $menuArray[] = array( 'type' => $menuType, 'settings' => $menuINI->group( $menuType ) );
}

$tpl->setVariable( 'available_menu_array', $menuArray );
$tpl->setVariable( 'current_menu', $menuINI->variable( 'SelectedMenu', 'CurrentMenu' ) );
$tpl->setVariable( 'siteaccess_list', $siteAccessList );

$tpl->setVariable( 'current_siteaccess', $siteAccess );

$Result = array();
$Result['content'] = $tpl->fetch( "design:visual/menuconfig.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::translate( 'design/standard/menuconfig', 'Menu management' ) ) );

?>

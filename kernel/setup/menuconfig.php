<?php
//
// Definition of Menuconfig class
//
// Created on: <05-Mar-2004 14:34:34 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezfile/classes/ezdir.php' );
include_once( "kernel/common/template.php" );

$ini =& eZINI::instance();
$tpl =& templateInit();

if ( $module->isCurrentAction( 'SelectCurrentSiteAccess' ) )
{
    if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    {
        $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $http->postVariable( 'CurrentSiteAccess' ) );
    }
}

$siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
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

$menuINI =& eZINI::instance( "menu.ini" );
$menuINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
$menuINI->loadCache();

/*$iniPath = "settings/siteaccess/$siteAccess";
$menuINI = eZINI::instance( 'menu.ini.append.php', $iniPath, null, false, null, true );*/

if ( $module->isCurrentAction( 'Store' ) )
{
    $menuType = $http->postVariable( 'MenuType' );

    $menuINI->setVariable( 'SelectedMenu', 'CurrentMenu', $menuType );
    $menuINI->setVariable( 'SelectedMenu', 'TopMenu', $menuINI->variable( $menuType, "TopMenu" ) );
    $menuINI->setVariable( 'SelectedMenu', 'LeftMenu', $menuINI->variable( $menuType, "LeftMenu" ) );

    //$menuINI->save( false, false, false, false, true, true );

    $menuINI->save( "menu.ini.append.php", false, false, false, "settings/siteaccess/$siteAccess", true );

    // Delete compiled template
    $iniPath = "settings/siteaccess/$siteAccess";
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
    $templateBlockCacheEnabled = ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled' );

    if ( $templateBlockCacheEnabled )
    {
        eZContentObject::expireTemplateBlockCache();
    }
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
$Result['content'] =& $tpl->fetch( "design:setup/menuconfig.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'design/standard/menuconfig', 'Menu management' ) ) );

?>

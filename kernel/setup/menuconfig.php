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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezfile/classes/ezdir.php' );
include_once( "kernel/common/template.php" );


$ini =& eZINI::instance();
$tpl =& templateInit();

$siteAccess = "base";

$menuINI = eZINI::instance( 'menu.ini', 'settings', null, null, true );
$menuINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
$menuINI->loadCache();

if ( $module->isCurrentAction( 'SelectCurrentSiteAccess' ) )
{
    if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    {
        $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $http->postVariable( 'CurrentSiteAccess' ) );
    }
}

// Fetch siteaccess settings for the selected override
// Default to first defined siteacces if none are selected
if ( !$http->hasSessionVariable( 'eZTemplateAdminCurrentSiteAccess' ) )
{
    $siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
    $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $siteAccessList[0] );
}

$siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

if ( $module->isCurrentAction( 'Store' ) )
{
    $menuType = $http->postVariable( 'MenuType' );

    $menuINI->setVariable( 'SelectedMenu', 'CurrentMenu', $menuType );
    $menuINI->setVariable( 'SelectedMenu', 'TopMenu', $menuINI->variable( $menuType, "TopMenu" ) );
    $menuINI->setVariable( 'SelectedMenu', 'LeftMenu', $menuINI->variable( $menuType, "LeftMenu" ) );

    $menuINI->save( "siteaccess/$siteAccess/menu.ini.append" );

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
    eZDir::unlinkWildcard( $compiledTemplateDir . "/","pagelayout*.*" );
}

$availableMenuArray = $menuINI->variable( 'MenuSettings', 'AvailableMenuArray' );

$menuArray = array();
foreach ( $availableMenuArray as $menuType )
{
    $menuArray[] = array( 'type' => $menuType, 'settings' => $menuINI->group( $menuType ) );
}

$tpl->setVariable( 'available_menu_array', $menuArray );
$tpl->setVariable( 'current_menu', $menuINI->variable( 'SelectedMenu', 'CurrentMenu' ) );

$tpl->setVariable( 'current_siteaccess', $siteAccess );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/menuconfig.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Menu config' ) ) );

?>

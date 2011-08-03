<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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
                                'text' => ezpI18n::tr( 'design/standard/menuconfig', 'Menu management' ) ) );

?>

<?php
//
// Created on: <08-May-2003 11:16:19 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
$module =& $Params["Module"];
$parameters =& $Params["Parameters"];

include_once( "kernel/common/template.php" );
include_once( "kernel/common/eztemplatedesignresource.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$ini = eZINI::instance();
$tpl =& templateInit();

$template = "";

foreach ( $parameters as $param )
{
    $template .= "/$param";
}

if ( $module->isCurrentAction( 'SelectCurrentSiteAccess' ) )
{
    if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    {
        $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $http->postVariable( 'CurrentSiteAccess' ) );
    }
}

$siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

// Fetch siteaccess settings for the selected override
// Default to first defined siteacces if none are selected
if ( !$http->hasSessionVariable( 'eZTemplateAdminCurrentSiteAccess' ) )
{
    $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $siteAccessList[0] );
}

$siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

$overrideArray = eZTemplateDesignResource::overrideArray( $siteAccess );

$templateSettings = false;
if ( isset( $overrideArray[$template] ) )
{
    $templateSettings = $overrideArray[$template];
}

if ( $http->hasPostVariable( 'EditTemplateButton' ) )
{
    // Should be used in templateedit when clicking on "Back" button
    $http->setSessionVariable( 'visualRedirectTo', '/visual/templateview' . $template );
    $module->redirectTo( '/visual/templateedit/' . $templateSettings['base_dir'] . $template );
}

if ( $module->isCurrentAction( 'NewOverride' ) )
{
    if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    {
        $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $http->postVariable( 'CurrentSiteAccess' ) );
    }

    $appendParameters = '';
    if ( $http->hasPostVariable( 'NewTemplateLocation' ) )
    {
        $appendParameters .= '/(location)/' . $http->postVariable( 'NewTemplateLocation' );
    }
    if ( $http->hasPostVariable( 'NewTemplateType' ) )
    {
        $appendParameters .= '/(type)/' . $http->postVariable( 'NewTemplateType' );
    }
    $module->redirectTo( '/visual/templatecreate' . $template . $appendParameters );
    return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
}

if ( $module->isCurrentAction( 'UpdateOverride' ) )
{
    if ( $http->hasPostVariable( 'PriorityArray' ) )
    {
        $priorityArray = $http->postVariable( 'PriorityArray' );

        // Load override.ini for the current siteaccess
        $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
        $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
        $overrideINI->loadCache();

        asort( $priorityArray );
        $currentINIGroups =& $overrideINI->groups();

        $newGroupArray = array();
        foreach ( array_keys( $priorityArray ) as $key )
        {
            $newGroupArray[$key] = $currentINIGroups[$key];
            unset( $currentINIGroups[$key] );
        }
        $overrideINI->setGroups( array_merge( $currentINIGroups, $newGroupArray ) );

        $filePermission = $ini->variable( 'FileSettings', 'StorageFilePermissions' );

        $oldumask = umask( 0 );
        $overrideINI->save( "siteaccess/$siteAccess/override.ini.append" );
        chmod( "settings/siteaccess/$siteAccess/override.ini.append", octdec( $filePermission ) );
        umask( $oldumask );
    }
}

$overrideINISaveFailed = false;
$notRemoved = array();

if ( !isset( $templateSettings['custom_match'] ) )
{
    $templateSettings['custom_match'] = 0;
}

if ( $module->isCurrentAction( 'RemoveOverride' ) )
{
    if ( $http->hasPostVariable( 'RemoveOverrideArray' ) )
    {
        $removeOverrideArray = $http->postVariable( 'RemoveOverrideArray' );
        // TODO: read from correct site.ini
        $siteBase = $siteAccess;

        // Load override.ini for the current siteaccess
        $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
        $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
        $overrideINI->loadCache();

        $siteINI = eZINI::instance( 'site.ini', 'settings', null, null, true );
        $siteINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
        $siteINI->loadCache();
        $siteBase = $siteINI->variable( 'DesignSettings', 'SiteDesign' );

        // Remove settings and file
        foreach ( $removeOverrideArray as $removeOverride )
        {
            $group = $overrideINI->group( $removeOverride );
            $removedKey = -1;

            $fileName = "design/$siteBase/override/templates/" . $group['MatchFile'];
            if ( $templateSettings['custom_match'] != 0 )
            {
                foreach ( $templateSettings['custom_match'] as $matchkey => $match )
                {
                    if ( $match['override_name'] == $removeOverride )
                    {
                        $fileName = $match['match_file'];
                        $removedKey = $matchkey;
                        break;
                    }
                }
            }

            if ( unlink( $fileName ) )
            {
                $overrideINI->removeGroup( $removeOverride );
                if ( $removedKey > -1 )
                {
                    unset( $templateSettings['custom_match'][$removedKey] );
                }
            }
            else
            {
                $notRemoved[] = array( 'filename' => $fileName );
            }
            unset( $removedKey );
        }
        if ( $overrideINI->save( "siteaccess/$siteAccess/override.ini.append" ) == false )
        {
            $overrideINISaveFailed = true;
        }

        include_once( 'kernel/classes/ezcache.php' );
        eZCache::clearGlobalINICache();

        // Expire content view cache
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();

        // Clear override cache
        $cachedDir = eZSys::cacheDirectory();
        $cachedDir .= "/override/";
        eZDir::recursiveDelete( $cachedDir );
    }
}

$tpl->setVariable( 'template_settings',  $templateSettings );
$tpl->setVariable( 'current_siteaccess', $siteAccess );
$tpl->setVariable( 'not_removed',        $notRemoved );
$tpl->setVariable( 'ini_not_saved',      $overrideINISaveFailed );
$tpl->setVariable( 'siteaccess_list',    $siteAccessList );
$tpl->setVariable( 'action_path',        '/visual/templateview' . $templateSettings['template'] );

$siteINI = eZINI::instance( 'site.ini' );
if ( $siteINI->variable( 'BackwardCompatibilitySettings', 'UsingDesignAdmin34' ) == 'enabled' )
{
    $tpl->setVariable( 'custom_match', $templateSettings['custom_match'] );
}

$Result = array();
$Result['content'] =& $tpl->fetch( "design:visual/templateview.tpl" );
$Result['path'] = array( array( 'url' => "/visual/templatelist/",
                                'text' => ezi18n( 'kernel/design', 'Template list' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/design', 'Template view' ) ) );
?>

<?php
//
// Created on: <08-May-2003 11:16:19 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
$parameters =& $Params["Parameters"];

include_once( "kernel/common/template.php" );
include_once( "kernel/common/eztemplatedesignresource.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$ini =& eZINI::instance();
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

// Fetch siteaccess settings for the selected override
// Default to first defined siteacces if none are selected
if ( !$http->hasSessionVariable( 'eZTemplateAdminCurrentSiteAccess' ) )
{
    $siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
    $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $siteAccessList[0] );
}

$siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

if ( $module->isCurrentAction( 'NewOverride' ) )
{
    if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    {
        $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $http->postVariable( 'CurrentSiteAccess' ) );
    }

    $module->redirectTo( '/visual/templatecreate'. $template );
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

            $fileName = "design/$siteBase/override/templates/" . $group['MatchFile'];

            if ( unlink( $fileName ) )
            {
                $overrideINI->removeGroup( $removeOverride );
            }
            else
            {
                $notRemoved[] = array( 'filename' => $fileName );
                // eZDebug::writeError( "Could not remove override template, check permissions on $fileName", "Template override" );
            }
        }
        if ( $overrideINI->save( "siteaccess/$siteAccess/override.ini.append" ) == false )
        {
            $overrideINISaveFailed = true;
        }

        // Expire content view cache
        $viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );
        if ( $viewCacheEnabled )
        {
            eZContentObject::expireAllCache();
        }

        // Clear override cache
        $cachedDir = eZSys::cacheDirectory();
        $cachedDir .= "/override/";
        eZDir::recursiveDelete( $cachedDir );
    }
}

$overrideArray =& eZTemplatedesignresource::overrideArray( $siteAccess );

$templateSettings = false;
if ( isset( $overrideArray[$template] ) )
{
    $templateSettings = $overrideArray[$template];
}

if ( !isset( $templateSettings['custom_match'] ) )
    $templateSettings['custom_match'] = 0;

$tpl->setVariable( 'template_settings', $templateSettings );
$tpl->setVariable( 'current_siteaccess', $siteAccess );
$tpl->setVariable( 'not_removed', $notRemoved );
$tpl->setVariable( 'ini_not_saved', $overrideINISaveFailed );

$siteINI =& eZINI::instance( 'site.ini' );
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

<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$http = eZHTTPTool::instance();
$module = $Params['Module'];
$parameters = $Params["Parameters"];


$ini = eZINI::instance();
$tpl = eZTemplate::factory();

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
    $siteAccessList = $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );
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
    return eZModule::HOOK_STATUS_CANCEL_RUN;
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

        $oldumask = (defined('EZP_USE_FILE_PERMISSIONS') ? EZP_USE_FILE_PERMISSIONS : true ) ? umask( 0 ) : umask();
        $overrideINI->save( "siteaccess/$siteAccess/override.ini.append" );
        if ( ( defined('EZP_USE_FILE_PERMISSIONS') ? EZP_USE_FILE_PERMISSIONS : true ) &&
             eZINI::instance()->variable( 'FileSettings', 'ControlFilePermissions' ) !== 'false' ) {
            @chmod( "settings/siteaccess/$siteAccess/override.ini.append", octdec( $filePermission ) );
        }
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
            }
        }
        if ( $overrideINI->save( "siteaccess/$siteAccess/override.ini.append" ) == false )
        {
            $overrideINISaveFailed = true;
        }

        // Expire content view cache
        eZContentCacheManager::clearAllContentCache();

        // Clear override cache
        $cachedDir = eZSys::cacheDirectory();
        $cachedDir .= "/override/";
        eZDir::recursiveDelete( $cachedDir );
    }
}

$overrideArray = eZTemplateDesignResource::overrideArray( $siteAccess );

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

$siteINI = eZINI::instance( 'site.ini' );
if ( $siteINI->variable( 'BackwardCompatibilitySettings', 'UsingDesignAdmin34' ) == 'enabled' )
{
    $tpl->setVariable( 'custom_match', $templateSettings['custom_match'] );
}

$Result = array();
$Result['content'] = $tpl->fetch( "design:visual/templateview.tpl" );
$Result['path'] = array( array( 'url' => "/visual/templatelist/",
                                'text' => ezpI18n::tr( 'kernel/design', 'Template list' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/design', 'Template view' ) ) );
?>

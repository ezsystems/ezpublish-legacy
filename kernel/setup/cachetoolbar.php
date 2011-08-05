<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$http = eZHTTPTool::instance();
$module = $Params['Module'];

$cacheType = $module->actionParameter( 'CacheType' );

eZPreferences::setValue( 'admin_clearcache_type', $cacheType );

if ( $module->hasActionParameter ( 'NodeID' ) )
    $nodeID = $module->actionParameter( 'NodeID' );

if ( $module->hasActionParameter ( 'ObjectID' ) )
    $objectID = $module->actionParameter( 'ObjectID' );

if ( $cacheType == 'All' )
{
    eZCache::clearAll();
}
elseif ( $cacheType == 'Template' )
{
    eZCache::clearByTag( 'template' );
}
elseif ( $cacheType == 'Content' )
{
    eZCache::clearByTag( 'content' );
}
elseif ( $cacheType == 'TemplateContent' )
{
    eZCache::clearByTag( 'template' );
    eZCache::clearByTag( 'content' );
}
elseif ( $cacheType == 'Ini' )
{
    eZCache::clearByTag( 'ini' );
}
elseif ( $cacheType == 'Static' )
{
    $staticCache = new eZStaticCache();
    $staticCache->generateCache( true, true );
    $cacheCleared['static'] = true;
}
elseif ( $cacheType == 'ContentNode' )
{
    $contentModule = eZModule::exists( 'content' );
    if ( $contentModule instanceof eZModule )
    {
        $contentModule->setCurrentAction( 'ClearViewCache', 'action' );

        $contentModule->setActionParameter( 'NodeID', $nodeID, 'action' );
        $contentModule->setActionParameter( 'ObjectID', $objectID, 'action' );

        $contentModule->run( 'action', array( $nodeID, $objectID) );
    }
}
elseif ( $cacheType == 'ContentSubtree' )
{
    $contentModule = eZModule::exists( 'content' );
    if ( $contentModule instanceof eZModule )
    {
        $contentModule->setCurrentAction( 'ClearViewCacheSubtree', 'action' );

        $contentModule->setActionParameter( 'NodeID', $nodeID, 'action' );
        $contentModule->setActionParameter( 'ObjectID', $objectID, 'action' );

        $contentModule->run( 'action', array( $nodeID, $objectID) );
    }
}

$uri = $http->postVariable( 'RedirectURI', $http->sessionVariable( 'LastAccessedModifyingURI', '/' ) );
$module->redirectTo( $uri );

?>

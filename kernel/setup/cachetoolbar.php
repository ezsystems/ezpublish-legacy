<?php
//
// Created on: <21-Feb-2005 16:53:31 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

$uri = $http->sessionVariable( "LastAccessedModifyingURI" );
$module->redirectTo( $uri );

?>

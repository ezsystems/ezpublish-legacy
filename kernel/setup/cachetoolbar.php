<?php
//
// Created on: <21-Feb-2005 16:53:31 ks>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

include_once( 'kernel/classes/ezcache.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'kernel/classes/ezpreferences.php' );

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
    include_once( 'kernel/classes/ezstaticcache.php' );
    
    $staticCache = new eZStaticCache();
    $staticCache->generateCache( true, true );
    $cacheCleared['static'] = true;
}
elseif ( $cacheType == 'ContentNode' )
{
    $contentModule = eZModule::exists( 'content' );
    if ( get_class( $contentModule ) == "ezmodule" )
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
    if ( get_class( $contentModule ) == "ezmodule" )
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

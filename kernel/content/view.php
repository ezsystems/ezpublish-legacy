<?php
//
// Created on: <24-Apr-2002 11:18:59 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/eztrigger.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

$http =& eZHTTPTool::instance();

$tpl =& templateInit();

$ViewMode = $Params['ViewMode'];
$NodeID = $Params['NodeID'];
$Module =& $Params['Module'];
$LanguageCode = $Params['LanguageCode'];
$Offset = $Params['Offset'];

if ( !is_numeric( $Offset ) )
    $Offset = 0;

$ini =& eZINI::instance();
$viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );

if ( $viewCacheEnabled )
{
    include_once( 'kernel/classes/ezcontentcache.php' );
    $cacheInfo = eZContentObject::cacheInfo( $Params );
    $language = $cacheInfo['language'];
    $roleList = $cacheInfo['role_list'];
    $discountList = $cacheInfo['discount_list'];
    $designSetting = eZTemplateDesignResource::designSetting( 'site' );
    if ( eZContentCache::exists( $designSetting, $NodeID, $ViewMode, $language, $Offset, $roleList, $discountList ) )
    {
//         eZDebug::writeDebug( 'found cache', 'content/view' );
        $Result = eZContentCache::restore( $designSetting, $NodeID, $ViewMode, $language, $Offset, $roleList, $discountList );
        return $Result;
    }
}

$limitationList = array();

if ( array_key_exists( 'Limitation', $Params ) )
{
    $Limitation =& $Params['Limitation'];
    foreach ( $Limitation as $policy )
    {
        $limitationList[] =& $policy->attribute( 'limitations' );
    }
}

include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
// eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $NodeID,
//                                                           'version' => 1 ),
//                              array( 'name' => 'pre_publish',
//                                     'parameters' => array( 'object_id' => 5,
//                                                            'version' => 1,
//                                                            'parent_node_id' => 12 ),
//                                     'loop_run' => array( "loop-nodes" => 1,
//                                                          "set-version-pending" => 1,
//                                                          "pre_publish" => 2,
//                                                          "set-version-archived" => 1,
//                                                          "publish-node" => 2,
//                                                          "set-version-published" => 1,
//                                                          "post_publish" => 2 ),
//                                     'loop_data' => array( 'name' => 'loop-nodes',
//                                                           'count' => 3,
//                                                           'index' => 1 ) ) );
// if ( !eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $NodeID,
//                                                                'version' => 1 ) ) )
//     return;
$user =& eZUser::currentUser();

eZDebug::addTimingPoint( 'Operation start' );
$operationResult = eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
                                                                          'user_id' => $user->id(),
                                                                          'language_code' => $LanguageCode ) );
eZDebug::addTimingPoint( 'Operation end' );


switch( $operationResult['status'] )
{
    case EZ_MODULE_OPERATION_CONTINUE:
    {
        if ( $operationResult != null &&
             !isset( $operationResult['result'] ) &&
             ( !isset( $operationResult['redirect_url'] ) || $operationResult['redirect_url'] == null ) )
        {
            $viewParameters = array( 'offset' => $Offset );
            $object = $operationResult[ 'object' ];

            if ( !get_class( $object ) == 'ezcontentobject' )
                return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );


                $node = $operationResult[ 'node' ];
            if ( ! is_object( $object ) )
            {
                eZDebug::printReport();
            }

            $res =& eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ), // Object ID
                                  array( 'node', $node->attribute( 'node_id' ) ), // Node ID
                                  array( 'parent_node', $node->attribute( 'parent_node_id' ) ), // Node ID
                                  array( 'class', $object->attribute( 'contentclass_id' ) ), // Class ID
                                  array( 'view_offset', $Offset ),
                                  array( 'viewmode', $ViewMode ),
                                  ) );

            include_once( 'kernel/classes/ezsection.php' );
            eZSection::setGlobalID( $object->attribute( 'section_id' ) );

            $tpl->setVariable( 'node', $node );
            $tpl->setVariable( 'view_parameters', $viewParameters );

// create path
            $parents =& $node->attribute( 'path' );

            $path = array();
            foreach ( $parents as $parent )
            {
                $path[] = array( 'text' => $parent->attribute( 'name' ),
                                 'url' => '/content/view/full/' . $parent->attribute( 'node_id' )
                                 );
            }
            $path[] = array( 'text' => $object->attribute( 'name' ),
                             'url' => false );

            $Result = array();
            $Result['content'] =& $tpl->fetch( 'design:node/view/' . $ViewMode . '.tpl' );
            $Result['view_parameters'] =& $viewParameters;
            $Result['path'] =& $path;
            $Result['section_id'] =& $object->attribute( 'section_id' );

            if ( $viewCacheEnabled )
            {
                include_once( 'kernel/classes/ezcontentcache.php' );
                $cacheInfo = eZContentObject::cacheInfo( $Params );
                $language = $cacheInfo['language'];
                $roleList = $cacheInfo['role_list'];
                $discountList = $cacheInfo['discount_list'];
                if ( eZContentCache::store( $designSetting, $NodeID, $ViewMode, $language, $Offset, $roleList, $discountList, $Result ) )
                {
//                     eZDebug::writeDebug( 'cache written', 'content/view' );
                }
            }

        }
    }break;
    case EZ_MODULE_OPERATION_HALTED:
    {
        if (  isset( $operationResult['redirect_url'] ) )
        {
            $Module->redirectTo( $operationResult['redirect_url'] );
            return;
        }
        else if ( isset( $operationResult['result'] ) )
        {
            $Result['content'] =& $operationResult['result'];
        }
    }break;
    case EZ_MODULE_OPERATION_CANCELED:
    {
        $Result = array();
        $Result['content'] = "- I think you are not able to view that object :) <br/>
                              - Why?<br/>
                              - Because I think so :)";
    }
}

?>

<?php
//
// Created on: <24-Apr-2002 11:18:59 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
$LanguageCode = $Params['Language'];
$Offset = $Params['Offset'];
$Year = $Params['Year'];
$Month = $Params['Month'];
$Day = $Params['Day'];

if ( $Offset )
    $Offset = (int) $Offset;
if ( $Year )
    $Year = (int) $Year;
if ( $Month )
    $Month = (int) $Month;
if ( $Day )
    $Day = (int) $Day;

if ( trim( $LanguageCode ) != '' )
{
    eZContentObject::setDefaultLanguage( $LanguageCode );
}

if ( $NodeID < 2 )
    $NodeID = 2;

if ( !is_numeric( $Offset ) )
    $Offset = 0;

$ini =& eZINI::instance();
$viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );
if ( isset( $Params['ViewCache'] ) )
    $viewCacheEnabled = $Params['ViewCache'];

$collectionAttributes = false;
if ( isset( $Params['CollectionAttributes'] ) )
    $collectionAttributes = $Params['CollectionAttributes'];

$validation = array( 'processed' => false,
                     'attributes' => array() );
if ( isset( $Params['AttributeValidation'] ) )
    $validation = $Params['AttributeValidation'];

// Check if read operations should be used
$workflowINI =& eZINI::instance( 'workflow.ini' );
if ( in_array( 'content_read', $workflowINI->variableArray( 'OperationSettings', 'AvailableOperations') ) )
{
    $useTriggers = true;
}
else
{
    $useTriggers = false;
}

$res =& eZTemplateDesignResource::instance();
$keys =& $res->keys();
if ( isset( $keys['layout'] ) )
    $layout = $keys['layout'];
else
    $layout = false;

$viewParameters = array( 'offset' => $Offset,
                         'year' => $Year,
                         'month' => $Month,
                         'day' => $Day );

/*$templateResult =& $tpl->fetch( 'design:content/view/full.tpl' );*/

// Should we load the cache now, or check operation
if ( $viewCacheEnabled and ( $useTriggers == false ) )
{
    // Note: this code is duplicate, see about 100 lines down
    include_once( 'kernel/classes/ezcontentcache.php' );
    $cacheInfo = eZContentObject::cacheInfo( $Params );
    $language = $cacheInfo['language'];
    $roleList = $cacheInfo['role_list'];
    $discountList = $cacheInfo['discount_list'];
    $designSetting = eZTemplateDesignResource::designSetting( 'site' );
    if ( eZContentCache::exists( $designSetting, $NodeID, $ViewMode, $language, $Offset, $roleList, $discountList, $layout,
                                 array( 'view_parameters' => $viewParameters ) ) )
    {
        $Result = eZContentCache::restore( $designSetting, $NodeID, $ViewMode, $language, $Offset, $roleList, $discountList, $layout,
                                           array( 'view_parameters' => $viewParameters ) );
        if ( $Result )
        {
            $res =& eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'object', $Result['content_info']['object_id'] ),
                                  array( 'node', $Result['content_info']['node_id'] ),
                                  array( 'parent_node', $Result['content_info']['parent_node_id'] ),
                                  array( 'class', $Result['content_info']['class_id'] ),
                                  array( 'view_offset', $Result['content_info']['offset'] ),
                                  array( 'viewmode', $Result['content_info']['viewmode'] ),
                                  array( 'navigation_part_identifier', $Result['content_info']['navigation_part_identifier'] ),
                                  array( 'depth', $Result['content_info']['node_depth'] ),
                                  array( 'url_alias', $Result['content_info']['url_alias'] )
                                  ) );
            return $Result;
        }
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
$user =& eZUser::currentUser();

eZDebugSetting::addTimingPoint( 'kernel-content-view', 'Operation start' );

$operationResult =& eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
                                                                          'user_id' => $user->id(),
                                                                          'language_code' => $LanguageCode ), null, $useTriggers );
eZDebugSetting::writeDebug( 'kernel-content-view', $operationResult, 'operationResult' );
eZDebugSetting::addTimingPoint( 'kernel-content-view', 'Operation end' );

eZDebugSetting::writeDebug( 'kernel-content-view', $NodeID, "Fetching node" );

switch( $operationResult['status'] )
{
    case EZ_MODULE_OPERATION_CONTINUE:
    {
        if ( $operationResult != null &&
             !isset( $operationResult['result'] ) &&
             ( !isset( $operationResult['redirect_url'] ) || $operationResult['redirect_url'] == null ) )
        {
            if ( $viewCacheEnabled )
            {
                // Note: this code is duplicate, see about 100 lines up
                include_once( 'kernel/classes/ezcontentcache.php' );
                $cacheInfo = eZContentObject::cacheInfo( $Params );
                $language = $cacheInfo['language'];
                $roleList = $cacheInfo['role_list'];
                $discountList = $cacheInfo['discount_list'];
                $designSetting = eZTemplateDesignResource::designSetting( 'site' );
                if ( eZContentCache::exists( $designSetting, $NodeID, $ViewMode, $language, $Offset, $roleList, $discountList, $layout,
                                             array( 'view_parameters' => $viewParameters ) ) )
                {
                    eZDebugSetting::writeDebug( 'kernel-content-view-cache', 'found cache', 'content/view' );
                    $Result = eZContentCache::restore( $designSetting, $NodeID, $ViewMode, $language, $Offset, $roleList, $discountList, $layout,
                                                       array( 'view_parameters' => $viewParameters ) );
                    if ( $Result )
                    {
                        $res =& eZTemplateDesignResource::instance();
                        $res->setKeys( array( array( 'object', $Result['content_info']['object_id'] ),
                                              array( 'node', $Result['content_info']['node_id'] ),
                                              array( 'parent_node', $Result['content_info']['parent_node_id'] ),
                                              array( 'class', $Result['content_info']['class_id'] ),
                                              array( 'view_offset', $Result['content_info']['offset'] ),
                                              array( 'navigation_part_identifier', $Result['content_info']['navigation_part_identifier'] ),
                                              array( 'viewmode', $Result['content_info']['viewmode'] ),
                                              array( 'depth', $Result['content_info']['node_depth'] ),
                                              array( 'url_alias', $Result['content_info']['url_alias'] )
                                              ) );
                        return $Result;
                    }
                }
            }

            $object = $operationResult[ 'object' ];

            if ( !get_class( $object ) == 'ezcontentobject' )
                return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

            $node =& $operationResult[ 'node' ];

            if ( $node === null )
                return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

            if ( $object === null )
                return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

            if ( !$object->attribute( 'can_read' ) )
                return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

            if ( ! is_object( $object ) )
            {
//                 eZDebug::printReport();
            }

            include_once( 'kernel/classes/ezsection.php' );
            eZSection::setGlobalID( $object->attribute( 'section_id' ) );

            $section =& eZSection::fetch( $object->attribute( 'section_id' ) );
            if ( $section )
                $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

            $res =& eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                                  array( 'node', $node->attribute( 'node_id' ) ),
                                  array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                                  array( 'class', $object->attribute( 'contentclass_id' ) ),
                                  array( 'view_offset', $Offset ),
                                  array( 'viewmode', $ViewMode ),
                                  array( 'navigation_part_identifier', $navigationPartIdentifier ),
                                  array( 'depth', $node->attribute( 'depth' ) ),
                                  array( 'url_alias', $node->attribute( 'url_alias' ) )
                                  ) );

            $tpl->setVariable( 'node', $node );
            $tpl->setVariable( 'language_code', $LanguageCode );
            $tpl->setVariable( 'view_parameters', $viewParameters );
            $tpl->setVariable( 'collection_attributes', $collectionAttributes );
            $tpl->setVariable( 'validation', $validation );

            $parents =& $node->attribute( 'path' );

            $path = array();
            $titlePath = array();
            foreach ( $parents as $parent )
            {
                $path[] = array( 'text' => $parent->attribute( 'name' ),
                                 'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                                 'url_alias' => $parent->attribute( 'url_alias' ),
                                 'node_id' => $parent->attribute( 'node_id' )
                                 );
            }
            $path[] = array( 'text' => $object->attribute( 'name' ),
                             'url' => false,
                             'url_alias' => false,
                             'node_id' => $node->attribute( 'node_id' ) );

            array_shift( $parents );
            foreach ( $parents as $parent )
            {
                $titlePath[] = array( 'text' => $parent->attribute( 'name' ),
                                      'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                                      'url_alias' => $parent->attribute( 'url_alias' ),
                                      'node_id' => $parent->attribute( 'node_id' )
                                      );
            }
            $titlePath[] = array( 'text' => $object->attribute( 'name' ),
                                  'url' => false,
                                  'url_alias' => false );

            $tpl->setVariable( 'node_path', $path );

            $Result = array();
            $Result['content'] =& $tpl->fetch( 'design:node/view/' . $ViewMode . '.tpl' );
            $Result['view_parameters'] =& $viewParameters;
            $Result['path'] =& $path;
            $Result['title_path'] =& $titlePath;
            $Result['section_id'] =& $object->attribute( 'section_id' );
            $Result['node_id'] =& $NodeID;
            $Result['navigation_part'] = $navigationPartIdentifier;
            $Result['content_info'] = array( 'object_id' => $object->attribute( 'id' ),
                                             'node_id' => $node->attribute( 'node_id' ),
                                             'parent_node_id' => $node->attribute( 'parent_node_id' ),
                                             'class_id' => $object->attribute( 'contentclass_id' ),
                                             'offset' => $Offset,
                                             'viewmode' => $ViewMode,
                                             'navigation_part_identifier' => $navigationPartIdentifier,
                                             'node_depth' => $node->attribute( 'depth' ),
                                             'url_alias' => $node->attribute( 'url_alias' ) );


            // Check if time to live is set in template
            if ( $tpl->hasVariable( 'cache_ttl' ) )
            {
                $cacheTTL =& $tpl->variable( 'cache_ttl' );
            }

            if ( !isset( $cacheTTL ) )
            {
                $cacheTTL = -1;
            }

            // Check if cache time = 0 (disabled)
            if ( $cacheTTL == 0 )
            {
                $viewCacheEnabled = false;
            }

            if ( $viewCacheEnabled  )
            {
                include_once( 'kernel/classes/ezcontentcache.php' );
                $cacheInfo = eZContentObject::cacheInfo( $Params );
                $language = $cacheInfo['language'];
                $roleList = $cacheInfo['role_list'];
                $discountList = $cacheInfo['discount_list'];
                $sectionID = $object->attribute( 'section_id' );
                $objectID = $object->attribute( 'id' );
                $parentNodeID = $node->attribute( 'parent_node_id' );
                $classID = $object->attribute( 'contentclass_id' );
                $nodeDepth = $node->attribute( 'depth' );
                $urlAlias = $node->attribute( 'url_alias' );

                if ( eZContentCache::store( $designSetting, $objectID, $classID,
                                            $NodeID, $parentNodeID, $nodeDepth, $urlAlias, $ViewMode, $sectionID, $language,
                                            $Offset, $roleList, $discountList, $layout, $navigationPartIdentifier, $Result, $cacheTTL,
                                            array( 'view_parameters' => $viewParameters ) ) )
                {
                    eZDebugSetting::writeDebug( 'kernel-content-view-cache', 'cache written', 'content/view' );
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
            $result =& $operationResult['result'];
            $resultContent = false;
            if ( is_array( $result ) )
            {
                if ( isset( $result['content'] ) )
                    $resultContent = $result['content'];
                if ( isset( $result['path'] ) )
                    $Result['path'] = $result['path'];
            }
            else
                $resultContent =& $result;
            $Result['content'] =& $resultContent;
        }
    }break;
    case EZ_MODULE_OPERATION_CANCELED:
    {
        $Result = array();
        $Result['content'] = "Content view cancelled<br/>";
    }
}


?>

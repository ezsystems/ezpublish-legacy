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
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/eznodeviewfunctions.php' );

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

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}

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

$contentINI =& eZINI::instance( 'content.ini' );
$classGroupOverrideEnabled = ( $contentINI->variable( 'ContentOverrideSettings', 'EnableClassGroupOverride' ) == 'true' );

if ( isset( $Params['ViewCache'] ) )
{
    $viewCacheEnabled = $Params['ViewCache'];
}
elseif ( $viewCacheEnabled && !in_array( $ViewMode, $ini->variableArray( 'ContentSettings', 'CachedViewModes' ) ) )
{
    $viewCacheEnabled = false;
}

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

$viewParameters = array_merge( $viewParameters, $UserParameters );

$user =& eZUser::currentUser();

eZDebugSetting::addTimingPoint( 'kernel-content-view', 'Operation start' );


include_once( 'lib/ezutils/classes/ezmoduleoperationdefinition.php' );

if ( $useTriggers == true )
{
    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    include_once( 'kernel/classes/eztrigger.php' );

    $operationResult =& eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
                                                                               'user_id' => $user->id(),
                                                                               'language_code' => $LanguageCode ), null, $useTriggers );
}
else
{
    if ( $viewCacheEnabled )
    {
        $cacheExpired = false;
        $user =& eZUser::currentUser();

        $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $user, $NodeID, $Offset, $layout, $Params['Language'], $ViewMode, $viewParameters );

        // Read Cache file
        $fp = @fopen( $cacheFileArray['cache_path'], 'r' );
        if ( $fp )
        {
            $stat = fstat( $fp );
        }
        if ( $fp and !eZContentObject::isCacheExpired( $stat['mtime'] ) )
        {
            $contents = fread( $fp, filesize( $cacheFileArray['cache_path'] ) );

            $Result = unserialize( $contents );
            fclose( $fp );

            // Check if cache has expired when cache_ttl is set
            $cacheTTL =$Result['cache_ttl'];;
            if ( $cacheTTL > 0 )
            {
                $expiryTime = $stat['mtime'] + $cacheTTL;
                if ( time() > $expiryTime )
                {
                    $cacheExpired = true;
                }
            }

            if ( !$cacheExpired )
            {
                $res =& eZTemplateDesignResource::instance();

                $keyArray = array();
                $keyArray[] = array( 'object', $Result['content_info']['object_id'] );
                $keyArray[] = array( 'node', $Result['content_info']['node_id'] );
                $keyArray[] = array( 'parent_node', $Result['content_info']['parent_node_id'] );
                $keyArray[] = array( 'class', $Result['content_info']['class_id'] );
                $keyArray[] = array( 'view_offset', $Result['content_info']['offset'] );
                $keyArray[] = array( 'navigation_part_identifier', $Result['content_info']['navigation_part_identifier'] );
                $keyArray[] = array( 'viewmode', $Result['content_info']['viewmode'] );
                $keyArray[] = array( 'depth', $Result['content_info']['node_depth'] );
                $keyArray[] = array( 'url_alias', $Result['content_info']['url_alias'] );

                if ( $classGroupOverrideEnabled )
                {
                    $keyArray[] = array( 'class_group', $Result['content_info']['class_group'] );
                }

                $res->setKeys( $keyArray  );
                if ( isset( $Result['content_info']['class_identifier'] ) )
                    $res->setKeys( array( array( 'class_identifier', $Result['content_info']['class_identifier'] ) ) );

                // set section id
                include_once( 'kernel/classes/ezsection.php' );
                eZSection::setGlobalID( $Result['section_id'] );

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
                    if ( isset( $Result['content_info']['class_identifier'] ) )
                        $res->setKeys( array( array( 'class_identifier', $Result['content_info']['class_identifier'] ) ) );

                    return $Result;
                }

                return $Result;
            }
        }
    }
    else
    {
        $cacheFileArray = array( 'cache_dir' => false, 'cache_path' => false );
    }

    if ( $Params['Language'] != '' )
    {
        $node =& eZContentObjectTreeNode::fetch( $NodeID, $Params['Language'] );
    }
    else
    {
        $node =& eZContentObjectTreeNode::fetch( $NodeID );
    }

    if ( !is_object( $node ) )
        return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

    $object = $node->attribute( 'object' );

    if ( !is_object( $object ) )
        return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

    if ( $Params['Language'] != '' )
    {
        $object->setCurrentLanguage( $Params['Language'] );
    }

    if ( !get_class( $object ) == 'ezcontentobject' )
        return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

    if ( $node === null )
        return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

    if ( $object === null )
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

//    if ( !$object->attribute( 'can_read' ) )
    if ( !$object->canRead() )
    {
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel', array( 'AccessList' => $object->accessList( 'read' ) ) );
    }

    $Result = eZNodeviewfunctions::generateNodeView( $tpl, $node, $object, $Params['Language'], $ViewMode, $Offset,
                                                     $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], $viewCacheEnabled, $viewParameters,
                                                     $collectionAttributes, $validation );
    return $Result;
}

switch( $operationResult['status'] )
{
    case EZ_MODULE_OPERATION_CONTINUE:
    {
        if ( $operationResult != null &&
             !isset( $operationResult['result'] ) &&
             ( !isset( $operationResult['redirect_url'] ) || $operationResult['redirect_url'] == null ) )
        {
            // TODO make it work with workflows
        }
    }break;
    case EZ_MODULE_OPERATION_HALTED:
    {
        if ( isset( $operationResult['redirect_url'] ) )
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

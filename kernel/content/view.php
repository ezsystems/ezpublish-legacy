<?php
//
// Created on: <24-Apr-2002 11:18:59 bf>
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

// Check if we should switch access mode (http/https) for this node.
include_once( 'kernel/classes/ezsslzone.php' );
eZSSLZone::checkNodeID( 'content', 'view', $NodeID );

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

if ( $NodeID < 2 )
    $NodeID = 2;

if ( !is_numeric( $Offset ) )
    $Offset = 0;

$ini =& eZINI::instance();
$viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );

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
$operationList = $workflowINI->variableArray( 'OperationSettings', 'AvailableOperations' );
$operationList = array_unique( array_merge( $operationList, $workflowINI->variable( 'OperationSettings', 'AvailableOperationList' ) ) );
if ( in_array( 'content_read', $operationList ) )
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
                         'day' => $Day,
                         'namefilter' => false );
$viewParameters = array_merge( $viewParameters, $UserParameters );

$user =& eZUser::currentUser();

eZDebugSetting::addTimingPoint( 'kernel-content-view', 'Operation start' );


include_once( 'lib/ezutils/classes/ezmoduleoperationdefinition.php' );

$operationResult = array();

if ( $useTriggers == true )
{
    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    include_once( 'kernel/classes/eztrigger.php' );

    $operationResult = eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
                                                                              'user_id' => $user->id(),
                                                                              'language_code' => $LanguageCode ), null, $useTriggers );
}

if ( ( array_key_exists(  'status', $operationResult ) && $operationResult['status'] != EZ_MODULE_OPERATION_CONTINUE ) )
{
    switch( $operationResult['status'] )
    {
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
    return $Result;
}

$cacheGenFile = false;
if ( $viewCacheEnabled )
{
    $cacheExpired = false;
    $user =& eZUser::currentUser();

    $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $user, $NodeID, $Offset, $layout, $LanguageCode, $ViewMode, $viewParameters );

    // VS-DBFILE

    $cacheFilePath = $cacheFileArray['cache_path'];
    require_once( 'kernel/classes/ezclusterfilehandler.php' );
    $cacheFile = eZClusterFileHandler::instance( $cacheFilePath );
    $stat = $cacheFile->stat();

    if ( !isset( $stat['mtime'] ) )
    {
        // Check if cache gen file exists, and wait
        // for eZTemplateFunction_FileGenerateTimeout seconds if it does.
        $cacheFileGenPath = $cacheFilePath . '.gen';
        $cacheGenFile = eZClusterFileHandler::instance( $cacheFileGenPath );
        while( $cacheGenFile->fileExists( $cacheGenFile->name() ) &&
               $cacheGenFile->mtime() + eZNodeViewFunctions_FileGenerateTimeout > mktime() )
        {
            sleep( 1 );
            $cacheGenFile->loadMetaData();
        }

        $cacheFile->loadMetaData();
        $stat = $cacheFile->stat();
    }

    // Read Cache file
    if ( isset( $stat['mtime'] ) && !eZContentObject::isCacheExpired( $stat['mtime'] ) )
    {
        $contents = $cacheFile->fetchContents();
        $Result = unserialize( $contents );

        // Check if cache has expired when cache_ttl is set
        $cacheTTL = isset( $Result['cache_ttl'] ) ? $Result['cache_ttl'] : -1;
        if ( $cacheTTL > 0 )
        {
            $expiryTime = $stat['mtime'] + $cacheTTL;
            if ( time() > $expiryTime )
            {
                $cacheExpired = true;
            }
        }

        if ( $cacheExpired )
        {
            // Check if cache gen file exists, and wait
            // for eZTemplateFunction_FileGenerateTimeout seconds if it does.
            $cacheFileGenPath = $cacheFilePath . '.gen';
            $cacheGenFile = eZClusterFileHandler::instance( $cacheFileGenPath );
            while( $cacheGenFile->fileExists( $cacheGenFile->name() ) &&
                   $cacheGenFile->mtime() + eZNodeViewFunctions_FileGenerateTimeout > mktime() )
            {
                sleep( 1 );
                $cacheGenFile->loadMetaData();
            }

            if  ( $cacheFile->fileExists( $cacheFile->name() ) )
            {
                // Cache file might have been generated by now.
                $contents = $cacheFile->fetchContents();
                $Result = unserialize( $contents );
                $cacheExpired = false;

                // Check if cache has expired when cache_ttl is set
                $cacheTTL = isset( $Result['cache_ttl'] ) ? $Result['cache_ttl'] : -1;
                if ( $cacheTTL > 0 )
                {
                    $expiryTime = $stat['mtime'] + $cacheTTL;
                    if ( time() > $expiryTime )
                    {
                        $cacheExpired = true;
                    }
                }
            }
        }

        // Check if template source files are newer, but only if the cache is not expired
        if ( !$cacheExpired )
        {
            $developmentModeEnabled = $ini->variable( 'TemplateSettings', 'DevelopmentMode' ) == 'enabled';
            // Only do filemtime checking when development mode is enabled.
            if ( $developmentModeEnabled &&
                 isset( $Result['template_list'] ) ) // And only if there is a list stored in the cache
            {
                foreach ( $Result['template_list'] as $templateFile )
                {
                    if ( @filemtime( $templateFile ) > $stat['mtime'] )
                    {
                        $cacheExpired = true;
                        break;
                    }
                }
            }
        }

        if ( !$cacheExpired )
        {
            if ( $cacheGenFile )
            {
                $cacheGenFile->delete();
            }

            $keyArray = array( array( 'object', $Result['content_info']['object_id'] ),
                               array( 'node', $Result['content_info']['node_id'] ),
                               array( 'parent_node', $Result['content_info']['parent_node_id'] ),
                               array( 'class', $Result['content_info']['class_id'] ),
                               array( 'view_offset', $Result['content_info']['offset'] ),
                               array( 'navigation_part_identifier', $Result['content_info']['navigation_part_identifier'] ),
                               array( 'viewmode', $Result['content_info']['viewmode'] ),
                               array( 'depth', $Result['content_info']['node_depth'] ),
                               array( 'url_alias', $Result['content_info']['url_alias'] ),
                               array( 'persistent_variable', $Result['content_info']['persistent_variable'] ),
                               array( 'class_group', $Result['content_info']['class_group'] ),
                               array( 'parent_class_id', $Result['content_info']['parent_class_id'] ),
                               array( 'parent_class_identifier', $Result['content_info']['parent_class_identifier'] ) );

            if ( isset( $Result['content_info']['class_identifier'] ) )
                $keyArray[] = array( 'class_identifier', $Result['content_info']['class_identifier'] );

            $res =& eZTemplateDesignResource::instance();
            $res->setKeys( $keyArray );

            // set section id
            include_once( 'kernel/classes/ezsection.php' );
            eZSection::setGlobalID( $Result['section_id'] );

            return $Result;
        }
    }
}
else
{
    $cacheFileArray = array( 'cache_dir' => false, 'cache_path' => false );
}

$node = eZContentObjectTreeNode::fetch( $NodeID );

if ( !is_object( $node ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$object =& $node->attribute( 'object' );

if ( !is_object( $object ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( !get_class( $object ) == 'ezcontentobject' )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $node === null )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $object === null )
{
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}

if ( $node->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes() )
{
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}

//    if ( !$object->attribute( 'can_read' ) )
if ( !$object->canRead() )
{
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel', array( 'AccessList' => $object->accessList( 'read' ) ) );
}
if ( $cacheGenFile )
{
    $cacheGenFile->storeContents( '1' );
}

$Result =& eZNodeviewfunctions::generateNodeView( $tpl, $node, $object, $LanguageCode, $ViewMode, $Offset,
                                                  $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], $viewCacheEnabled, $viewParameters,
                                                  $collectionAttributes, $validation );

// If cache gen file was created, delete it now.
if ( $cacheGenFile )
{
    $cacheGenFile->delete();
}

return $Result;


?>

<?php
//
// Created on: <19-Jan-2004 20:18:59 kk>
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
include_once( 'kernel/classes/eztrigger.php' );
include_once( 'kernel/common/eztemplatedesignresource.php' );
include_once( 'kernel/classes/ezcontentcache.php' );
include_once( 'kernel/common/template.php' );
include_once( 'lib/eztemplate/classes/eztemplateincludefunction.php' );

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

$viewParameters = array_merge( $viewParameters, $UserParameters );

// Should we load the cache now, or check operation
if ( $viewCacheEnabled && ( $useTriggers == false ) )
{
    // Note: this code is duplicate, see about 100 lines down
    include_once( 'kernel/classes/ezcontentcache.php' );
    $cacheInfo = eZContentObject::cacheInfo( $Params );
    $language = $cacheInfo['language'];
    $roleList = $cacheInfo['role_list'];
    $discountList = $cacheInfo['discount_list'];
    $designSetting = eZTemplateDesignResource::designSetting( 'site' );
    if ( eZContentCache::exists( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout,
                                 array( 'view_parameters' => $viewParameters ) ) )
    {
        $cachePathInfo =& eZContentCache::cachePathInfo( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout, false,
                                                         array( 'view_parameters' => $viewParameters ) );

        contentPDFPassthrough( $cachePathInfo['path'] );
    }
}

include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
$user =& eZUser::currentUser();

eZDebugSetting::addTimingPoint( 'kernel-content-pdf', 'Operation start' );

$operationResult =& eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
                                                                           'user_id' => $user->id(),
                                                                           'language_code' => $LanguageCode ), null, $useTriggers );
eZDebugSetting::writeDebug( 'kernel-content-pdf', $operationResult, 'operationResult' );
eZDebugSetting::addTimingPoint( 'kernel-content-pdf', 'Operation end' );

eZDebugSetting::writeDebug( 'kernel-content-pdf', $NodeID, 'Fetching node' );

switch( $operationResult['status'] )
{
    case EZ_MODULE_OPERATION_CONTINUE:
    {
        if ( ( $operationResult != null ) &&
             ( !isset( $operationResult['result'] ) ) &&
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
                if ( eZContentCache::exists( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout,
                                             array( 'view_parameters' => $viewParameters ) ) )
                {
                    $cachePathInfo =& eZContentCache::cachePathInfo( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout, false,
                                                                     array( 'view_parameters' => $viewParameters ) );
                    contentPDFPassthrough( $cachePathInfo['path'] );
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

            $cachePathInfo =& eZContentCache::cachePathInfo( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout, false,
                                                             array( 'view_parameters' => $viewParameters ) );
            $node =& eZContentObjectTreeNode::fetch( $NodeID );

            contentPDFGenerate( $cachePathInfo['path'] , $node, $object, $viewCacheEnabled );

            if ( $viewCacheEnabled  )
            {
                eZDebugSetting::writeDebug( 'kernel-content-pdf-cache', 'cache written', 'content/pdf' );
            }

            contentPDFPassthrough( $cachePathInfo['path'] );
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
        $Result['content'] = 'Content PDF view cancelled<br/>';
    }
}


/*!
  Passthrough PDF cache file, and exit cleanly
*/
function contentPDFPassthrough( $cacheFile )
{
    ob_clean();

    header( 'X-Powered-By: eZ publish' );

	header( 'Content-Length: '. filesize( $cacheFile ) );
    header( 'Content-Type: application/pdf' );
    header( 'Content-Transfer-Encoding: binary' );
    header( 'Accept-Ranges: bytes' );

    ob_end_clean();

    $fp = @fopen( $cacheFile, 'r' );
    @fpassthru( $fp );

    include_once( 'lib/ezutils/classes/ezexecution.php' );
    eZExecution::cleanExit();
}

/*!
  generate PDF, and output stream.
*/
function contentPDFGenerate( $cacheFile, &$node, $object = false, $viewCacheEnabled = true )
{
    if( $object === false )
    {
        $object =& $node->attribute( 'object' );
    }

    $res =& eZTemplateDesignResource::instance();
    $res->setKeys( array( array( 'object', $node->attribute( 'contentobject_id' ) ),
                          array( 'node', $node->attribute( 'node_id' ) ),
                          array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                          array( 'class', $object->attribute( 'contentclass_id' ) ),
                          array( 'depth', $node->attribute( 'depth' ) ),
                          array( 'url_alias', $node->attribute( 'url_alias' ) ),
                          array( 'class_identifier', $object->attribute( 'class_identifier' ) )
                          ) );

    $tpl =& templateInit();

    $tpl->setVariable( 'node', $node );
    $tpl->setVariable( 'generate_toc', 0 );

    $tpl->setVariable( 'tree_traverse', 0 );
    $tpl->setVariable( 'class_array', 0 );
    $tpl->setVariable( 'show_frontpage', 0 );

    if ( $viewCacheEnabled )
    {
        $tpl->setVariable( 'generate_file', 1 );
        $tpl->setVariable( 'filename', $cacheFile );
    }
    else
    {
        $tpl->setVariable( 'generate_file', 0 );
        $tpl->setVariable( 'generate_stream', 1 );
    }

    $textElements = array();
    $uri = 'design:node/view/pdf.tpl';
    $tpl->setVariable( 'pdf_root_template', 1 );
    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, '', '' );
    $pdf_definition = implode( '', $textElements );

    $pdf_definition = str_replace( array( ' ',
                                          "\r\n",
                                          "\t",
                                          "\n" ),
                                   '',
                                   $pdf_definition );
    $tpl->setVariable( 'pdf_definition', $pdf_definition );

    $uri = 'design:node/view/execute_pdf.tpl';
    $textElements = '';
    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, '', '' );
}
?>

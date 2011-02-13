<?php
//
// Created on: <19-Jan-2004 20:18:59 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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


$NodeID = $Params['NodeID'];
$Module = $Params['Module'];
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

if ( $NodeID < 2 )
    $NodeID = 2;

if ( !is_numeric( $Offset ) )
    $Offset = 0;

$ini = eZINI::instance();
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
$workflowINI = eZINI::instance( 'workflow.ini' );
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

$res = eZTemplateDesignResource::instance();
$keys = $res->keys();
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
    $cacheInfo = eZContentObject::cacheInfo( $Params );
    $language = $cacheInfo['language'];
    $roleList = $cacheInfo['role_list'];
    $discountList = $cacheInfo['discount_list'];
    $designSetting = eZTemplateDesignResource::designSetting( 'site' );
    if ( eZContentCache::exists( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout,
                                 array( 'view_parameters' => $viewParameters ) ) )
    {
        $cachePathInfo = eZContentCache::cachePathInfo( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout, false,
                                                        array( 'view_parameters' => $viewParameters ) );

        contentPDFPassthrough( $cachePathInfo['path'] );
    }
}

$user = eZUser::currentUser();

eZDebugSetting::addTimingPoint( 'kernel-content-pdf', 'Operation start' );

$operationResult = eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
                                                                          'user_id' => $user->id(),
                                                                          'language_code' => $LanguageCode ), null, $useTriggers );
eZDebugSetting::writeDebug( 'kernel-content-pdf', $operationResult, 'operationResult' );
eZDebugSetting::addTimingPoint( 'kernel-content-pdf', 'Operation end' );

eZDebugSetting::writeDebug( 'kernel-content-pdf', $NodeID, 'Fetching node' );

switch( $operationResult['status'] )
{
    case eZModuleOperationInfo::STATUS_CONTINUE:
    {
        if ( ( $operationResult != null ) &&
             ( !isset( $operationResult['result'] ) ) &&
             ( !isset( $operationResult['redirect_url'] ) || $operationResult['redirect_url'] == null ) )
        {
            if ( $viewCacheEnabled )
            {
                // Note: this code is duplicate, see about 100 lines up
                $cacheInfo = eZContentObject::cacheInfo( $Params );
                $language = $cacheInfo['language'];
                $roleList = $cacheInfo['role_list'];
                $discountList = $cacheInfo['discount_list'];
                $designSetting = eZTemplateDesignResource::designSetting( 'site' );
                if ( eZContentCache::exists( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout,
                                             array( 'view_parameters' => $viewParameters ) ) )
                {
                    $cachePathInfo = eZContentCache::cachePathInfo( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout, false,
                                                                    array( 'view_parameters' => $viewParameters ) );
                    contentPDFPassthrough( $cachePathInfo['path'] );
                }
            }

            if ( isset( $operationResult['object'] ) )
            {
                $object = $operationResult[ 'object' ];
            }
            else
            {
                return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
            }

            if ( !( $object instanceof eZContentObject ) )
                return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

            $node = $operationResult[ 'node' ];

            if ( $node === null )
                return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

            if ( $object === null )
                return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

            if ( !$object->attribute( 'can_read' ) )
                return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

            if ( !$node->attribute( 'can_pdf' ) )
                return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

            if ( $node->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes() )
                return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );


            $cachePathInfo = eZContentCache::cachePathInfo( $designSetting, $NodeID, 'pdf', $language, $Offset, $roleList, $discountList, $layout, false,
                                                            array( 'view_parameters' => $viewParameters ) );
            $node = eZContentObjectTreeNode::fetch( $NodeID );

            contentPDFGenerate( $cachePathInfo['path'] , $node, false, $viewCacheEnabled, $LanguageCode, $viewParameters );

            if ( $viewCacheEnabled  )
            {
                eZDebugSetting::writeDebug( 'kernel-content-pdf-cache', 'cache written', 'content/pdf' );
            }

            contentPDFPassthrough( $cachePathInfo['path'] );
        }
    }break;
    case eZModuleOperationInfo::STATUS_HALTED:
    {
        if (  isset( $operationResult['redirect_url'] ) )
        {
            $Module->redirectTo( $operationResult['redirect_url'] );
            return;
        }
        else if ( isset( $operationResult['result'] ) )
        {
            $result = $operationResult['result'];
            $resultContent = false;
            if ( is_array( $result ) )
            {
                if ( isset( $result['content'] ) )
                    $resultContent = $result['content'];
                if ( isset( $result['path'] ) )
                    $Result['path'] = $result['path'];
            }
            else
                $resultContent = $result;
            $Result['content'] = $resultContent;
        }
    }break;
    case eZModuleOperationInfo::STATUS_CANCELLED:
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
    $file = eZClusterFileHandler::instance( $cacheFile );

    if( !$file->exists() )
    {
        eZDebug::writeEror( "Cache-file for pdf doesn't exist", 'content::pdf::contentPDFPassthrough' );
        return;
    }

    $file->fetch( true );

    ob_clean();

    header( 'Pragma: ' );
    header( 'Cache-Control: ' );
    /* Set cache time out to 10 seconds, this should be good enough to work around an IE bug */
    header( "Expires: ". gmdate( 'D, d M Y H:i:s', time() + 10 ) . ' GMT' );
    header( 'X-Powered-By: eZ Publish' );

    header( 'Content-Length: '. $file->size() );
    header( 'Content-Type: application/pdf' );
    header( 'Content-Transfer-Encoding: binary' );
    header( 'Accept-Ranges: bytes' );

    ob_end_clean();

    $fp = @fopen( $cacheFile, 'r' );
    @fpassthru( $fp );
    fclose( $fp );

    eZExecution::cleanExit();
}

/*!
  generate PDF, and output stream.
*/
function contentPDFGenerate( $cacheFile,
                             $node,
                             $object = false,
                             $viewCacheEnabled = true,
                             $languageCode = false,
                             $viewParameters = array() )
{
    if ( $languageCode )
    {
        $node->setCurrentLanguage( $languageCode );
    }

    if( $object == false )
    {
        $object = $node->attribute( 'object' );
    }

    $res = eZTemplateDesignResource::instance();
    $res->setKeys( array( array( 'object', $node->attribute( 'contentobject_id' ) ),
                          array( 'remote_id', $object->attribute( 'remote_id' ) ),
                          array( 'node_remote_id', $node->attribute( 'remote_id' ) ),
                          array( 'section', $object->attribute( 'section_id' ) ),
                          array( 'node', $node->attribute( 'node_id' ) ),
                          array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                          array( 'class', $object->attribute( 'contentclass_id' ) ),
                          array( 'depth', $node->attribute( 'depth' ) ),
                          array( 'url_alias', $node->attribute( 'url_alias' ) ),
                          array( 'class_group', $object->attribute( 'match_ingroup_id_list' ) ),
                          array( 'class_identifier', $object->attribute( 'class_identifier' ) ) ) );

    $tpl = eZTemplate::factory();

    $tpl->setVariable( 'view_parameters', $viewParameters );

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

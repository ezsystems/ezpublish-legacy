<?php
//
// Created on: <18-Jul-2002 10:55:01 bf>
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
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'kernel/classes/ezcontentupload.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

$tpl =& templateInit();
$http =& eZHTTPTool::instance();
$module =& $Params['Module'];

$upload = new eZContentUpload();

$errors = array();

if ( $module->isCurrentAction( 'CancelUpload' ) )
{
    $url = false;
    if ( $upload->attribute( 'cancel_uri' ) )
    {
        $url = $module->redirectTo( $upload->attribute( 'cancel_uri' ) );
    }
    else if ( $upload->attribute( 'result_uri' ) )
    {
        $url = $module->redirectTo( $upload->attribute( 'result_uri' ) );
    }
    else if ( $upload->attribute( 'result_module' ) )
    {
        $info = $upload->attribute( 'result_module' );
        $moduleName = isset( $info[0] ) ? $info[0] : false;
        $viewName = isset( $info[1] ) ? $info[1] : false;
        $parameters = isset( $info[2] ) ? $info[2] : false;
        $unorderedParameters = isset( $info[3] ) ? $info[3] : false;
        $userParameters = isset( $info[4] ) ? $info[4] : false;
        $anchor = isset( $info[5] ) ? $info[5] : false;
        $url = $module->redirectionURI( $moduleName, $viewName, $parameters,
                                        $unorderedParameters, $userParameters, $anchor );
    }
    else
    {
        $url = '/';
    }
    return $module->redirectTo( $url );
}

if ( $module->isCurrentAction( 'UploadFile' ) )
{
    $location = $module->actionParameter( 'UploadLocation' );

    if ( $upload->handleUpload( $result, 'UploadFile', $location, false ) )
    {
        $object =& $result['contentobject'];
        $mainNode =& $result['contentobject_main_node'];
        if ( $result['redirect_url'] )
        {
            return $module->redirectTo( $operationResult['redirect_url'] );
        }
        if ( $result['result'] )
        {
            $resultData = $result['result'];
            $resultContent = false;
            if ( is_array( $resultData ) )
            {
                if ( isset( $resultData['content'] ) )
                    $resultContent = $resultData['content'];
                if ( isset( $resultData['path'] ) )
                    $Result['path'] = $resultData['path'];
            }
            else
            {
                $resultContent = $resultData;
            }
            $Result['content'] = $resultContent;
        }

        // Redirect to request URI if it is set, if not view the new object in main node
        if ( $upload->attribute( 'result_uri' ) )
        {
            $uri = $upload->attribute( 'result_uri' );
            return $module->redirectTo( $uri );
        }
        else if ( $upload->attribute( 'result_module' ) )
        {
            $data = $upload->attribute( 'result_module' );
            $moduleName = $data[0];
            $view = $data[1];
            $parameters = isset( $data[2] ) ? $data[2] : array();
            $userParameters = isset( $data[3] ) ? $data[3] : array();
            $resultModule =& eZModule::findModule( $moduleName, $module );
            $resultModule->setCurrentAction( $upload->attribute( 'result_action_name' ), $view );
            $actionParameters = $upload->attribute( 'result_action_parameters' );
            if ( $actionParameters )
            {
                foreach ( $actionParameters as $actionParameterName => $actionParameter )
                {
                    $resultModule->setActionParameter( $actionParameterName, $actionParameter, $view );
                }
            }
            return $resultModule->run( $view, $parameters, false, $userParameters );
        }
        else
        {
            $mainNode = $object->mainNode();
            $upload->cleanupAll();
            return $module->redirectTo( '/' . $mainNode->attribute( 'url' ) );
        }
    }
    else
    {
        $errors = $result['errors'];
    }
}


$res =& eZTemplateDesignResource::instance();
$keyArray = array();
$attributeKeys = $upload->attribute( 'keys' );

if ( is_array( $attributeKeys ) )
{
    foreach ( $attributeKeys as $attributeKey => $attributeValue )
    {
        $keyArray[] = array( $attributeKey, $attributeValue );
    }
}
$res->setKeys( $keyArray );

$tpl->setVariable( 'upload', $upload );
$tpl->setVariable( 'errors', $errors );

$Result = array();

$navigationPart = $upload->attribute( 'navigation_part_identifier' );
if ( $navigationPart )
    $Result['navigation_part'] = $navigationPart;

// setting keys for override
$res =& eZTemplateDesignResource::instance();

$Result['content'] =& $tpl->fetch( 'design:content/upload.tpl' );

$Result['path'] = array( array( 'text' => 'Upload',
                                'url' => false ) );

?>

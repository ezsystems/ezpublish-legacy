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


function storeUploadedFile( &$object, $version, &$attribute, &$file, $mimeData )
{
    $attributeID = $attribute->attribute( 'id' );
    switch ( $attribute->attribute( 'data_type_string' ) )
    {
        case 'ezbinaryfile':
        {
            $binary =& eZBinaryFile::fetch( $attributeID, $version );
            if ( $binary === null )
                $binary =& eZBinaryFile::create( $attributeID, $version );

            eZDebug::writeDebug( "storing" );
//             print( "storing<br/>\n" );
            if ( !$file->store( "original", false, false ) )
            {
//                 print( "Failed to store http-file: " . $file->attribute( "original_filename" ) . "<br/>\n" .
//                        "content/upload<br/>\n" );
                return false;
            }
            eZDebug::writeDebug( "storing done" );
//             print( "storing done<br/>\n" );

            $dir = $file->storageDir( "original" );
//             print( "dir: $dir<br/>" );

            $binary->setAttribute( "contentobject_attribute_id", $attributeID );
            $binary->setAttribute( "version", $version );
            $binary->setAttribute( "filename", basename( $file->attribute( "filename" ) ) );
            $binary->setAttribute( "original_filename", $file->attribute( "original_filename" ) );
            $binary->setAttribute( "mime_type", $mimeData['name'] );

            $binary->store();

            $attribute->setContent( $binary );
            return true;
        } break;

        case 'ezimage':
        {
            $handler =& $attribute->content();
            if ( !$handler )
                return false;

            return $handler->initializeFromHTTPFile( $file );
        } break;

        case 'ezmedia':
        {
            return true;
        } break;
    }

    return false;
}

function storeString( &$object, $version, &$attribute, $nameString )
{
    $attributeID = $attribute->attribute( 'id' );
    switch ( $attribute->attribute( 'data_type_string' ) )
    {
        case 'ezstring':
        case 'eztext':
        {
            $attribute->setAttribute( 'data_text', $nameString );
            return true;
        } break;
    }
    return false;
}


if ( $module->isCurrentAction( 'UploadFile' ) )
{
    include_once( 'lib/ezutils/classes/ezhttpfile.php' );
    if ( !eZHTTPFile::canFetch( 'UploadFile' ) )
    {
        $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                    'A file is required for upload.' ) );
    }
    else
    {
        $file =& eZHTTPFile::fetch( 'UploadFile' );
        if ( get_class( $file ) == "ezhttpfile" )
        {
            $mimeData =& eZMimeType::findByFileContents( $file->attribute( "original_filename" ) );
            $mime =& $mimeData['name'];

            if ( $mime == '' )
            {
                $mime = $file->attribute( "mime_type" );
            }

            $uploadINI =& eZINI::instance( 'upload.ini' );

            $mimeClassMap = $uploadINI->variable( 'CreateSettings', 'MimeClassMap' );
            $defaultClass = $uploadINI->variable( 'CreateSettings', 'DefaultClass' );

            list( $group, $type ) = explode( '/', $mime );
            if ( isset( $mimeClassMap[$mime] ) )
            {
                $classIdentifier = $mimeClassMap[$mime];
            }
            else if ( isset( $mimeClassMap[$group] ) )
            {
                $classIdentifier = $mimeClassMap[$group];
            }
            else
            {
                $classIdentifier = $defaultClass;
            }
            unset( $group, $type );

//             $node =& eZContentObjectTreeNode::fetch( $http->postVariable( 'NodeID' ) );
//         }
//         $parentContentObject =& $node->attribute( 'object' );

//         if ( $parentContentObject->checkAccess( 'create', $contentClassID,  $parentContentObject->attribute( 'contentclass_id' ) ) == '1' )
            if ( !$classIdentifier )
            {
//                 print( "No matching class identifier found<br/>\n" );
                return false;
            }

            $parentNodes = false;
            $parentMainNode = false;
            if ( $upload->attribute( 'parent_nodes' ) )
            {
                $parentNodes = $upload->attribute( 'parent_nodes' );
            }
            else
            {
                $contentINI =& eZINI::instance( 'content.ini' );

                $classPlacementMap = $contentINI->variable( 'RelationAssignmentSettings', 'ClassSpecificAssignment' );
                $defaultPlacement = $contentINI->variable( 'RelationAssignmentSettings', 'DefaultAssignment' );
                foreach ( $classPlacementMap as $classData )
                {
                    $classElements = explode( ';', $classData );
                    $classList = explode( ',', $classElements[0] );
                    $nodeList = explode( ',', $classElements[1] );
                    $mainNode = false;
                    if ( isset( $classElements[2] ) )
                        $mainNode = $classElements[2];
                    if ( in_array( $classIdentifier, $classList ) )
                    {
                        $parentNodes = $nodeList;
                        $parentMainNode = $mainNode;
                        break;
                    }
                }
            }
            if ( !$parentNodes )
            {
                $parentNodes = array( $defaultPlacement );
            }
            if ( !$parentNodes or
                 count( $parentNodes ) == 0 )
            {
//                 print( "Was not able to figure out placement of object<br/>\n" );
                return false;
            }

            foreach ( $parentNodes as $key => $parentNode )
            {
                $parentNode = eZContentUpload::nodeAliasID( $parentNode );
                $parentNodes[$key] = $parentNode;
//                 print( "placing in $parentNode<br/>" );
            }
            if ( !$parentMainNode )
            {
                $parentMainNode = $parentNodes[0];
            }
            $parentMainNode = eZContentUpload::nodeAliasID( $parentMainNode );
//             print( "main node: $parentMainNode<br/>\n" );

//             print( "identifier: $classIdentifier<br/>" );
            $class =& eZContentClass::fetchByIdentifier( $classIdentifier );
            if ( !$class )
            {
//                 print( "The class $classIdentifier does not exist<br/>\n" );
                return false;
            }

            $iniGroup = $classIdentifier . '_ClassSettings';
            if ( !$uploadINI->hasGroup( $iniGroup ) )
            {
//                 print( "No configuration group in upload.ini for class identifier '$classIdentifier'<br/>\n" );
                return false;
            }
            $fileAttribute = $uploadINI->variable( $iniGroup, 'FileAttribute' );
            $nameAttribute = $uploadINI->variable( $iniGroup, 'NameAttribute' );
            $namePattern = $uploadINI->variable( $iniGroup, 'NamePattern' );

            $fileDatatypes = array( 'ezbinaryfile', 'ezimage', 'ezmedia' );
            $nameDatatypes = array( 'ezstring', 'eztext' );

            $dataMap = $class->dataMap();
            if ( !isset( $dataMap[$fileAttribute] ) or
                 !in_array( $dataMap[$fileAttribute]->attribute( 'data_type_string' ), $fileDatatypes ) )
            {
                $fileAttribute = false;
//                 print( "No file attribute found, scanning<br/>\n" );
                foreach ( $dataMap as $identifer => $attribute )
                {
                    $type = $attribute->attribute( 'data_type_string' );
                    if ( in_array( $type, $fileDatatypes ) )
                    {
                        $fileAttribute = $identifier;
                        break;
                    }
                }
            }
            if ( !isset( $dataMap[$nameAttribute] ) or
                 !in_array( $dataMap[$nameAttribute]->attribute( 'data_type_string' ), $nameDatatypes ) )
            {
                $nameAttribute = false;
//                 print( "No name attribute found, scanning<br/>\n" );
                foreach ( $dataMap as $identifer => $attribute )
                {
                    $type = $attribute->attribute( 'data_type_string' );
                    if ( in_array( $type, $nameDatatypes ) )
                    {
                        $nameAttribute = $identifier;
                        break;
                    }
                }
            }

//             if ( $fileAttribute )
//                 print( "File attribute: $fileAttribute<br/>\n" );
//             else
//                 print( "No file attribute<br/>\n" );

//             if ( $nameAttribute )
//                 print( "Name attribute: $nameAttribute<br/>\n" );
//             else
//                 print( "No name attribute<br/>\n" );

            $variables = array( 'original_filename' => $file->attribute( 'original_filename' ),
                                'mime_type' => $mime );
            $variables['original_filename_base'] = $mimeData['basename'];
            $variables['original_filename_suffix'] = $mimeData['suffix'];

            $tags = array();
            $pos = 0;
            $lastPos = false;
            $len = strlen( $namePattern );
            while ( $pos < $len )
            {
                $markerPos = strpos( $namePattern, '<', $pos );
                if ( $markerPos !== false )
                {
                    $markerEndPos = strpos( $namePattern, '>', $markerPos + 1 );
                    if ( $markerEndPos === false )
                    {
                        $markerEndPos = $len;
                        $pos = $len;
                    }
                    else
                    {
                        $pos = $markerEndPos + 1;
                    }
                    $tag = substr( $namePattern, $markerPos + 1, $markerEndPos - $markerPos - 1 );
                    $tags[] = array( 'name' => $tag );
                }
                if ( $lastPos !== false and
                     $lastPos < $pos )
                {
                    $tags[] = substr( $namePattern, $lastPos, $pos - $lastPos );
                }
                $lastPos = $pos;
            }
            $nameString = '';
            foreach ( $tags as $tag )
            {
                if ( is_string( $tag ) )
                {
                    $nameString .= $tag;
                }
                else
                {
                    if ( isset( $variables[$tag['name']] ) )
                        $nameString .= $variables[$tag['name']];
                }
            }
//             print( "Name: '" . $nameString . "'<br/>\n" );

            $object =& $class->instantiate();
            foreach ( $parentNodes as $key => $parentNode )
            {
                $object->createNodeAssignment( $parentNode, $parentNode == $parentMainNode );
            }

            unset( $dataMap );
            $dataMap =& $object->dataMap();

            storeUploadedFile( $object, $object->attribute( 'current_version' ),
                               $dataMap[$fileAttribute], $file, $mimeData );
            $dataMap[$fileAttribute]->store();
            storeString( $object, $object->attribute( 'current_version' ),
                         $dataMap[$nameAttribute], $nameString );
            $dataMap[$nameAttribute]->store();

            $object->setName( $class->contentObjectName( $object ) );
            $object->store();

            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
//            $oldObjectName = $object->name();
//             print( "version: " . $object->attribute( 'current_version' ) . "<br/>\n" );
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                         'version' => $object->attribute( 'current_version' ) ) );
            $objectID = $object->attribute( 'id' );
            unset( $object );
            $object =& eZContentObject::fetch( $objectID );

            $mainNode = $object->mainNode();
            $upload->setResult( array( 'node_id' => $mainNode->attribute( 'node_id' ),
                                       'object_id' => $object->attribute( 'id' ),
                                       'object_version' => $object->attribute( 'current_version' ) ) );

//            $newObjectName = $object->name();

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
                $parameters = $data[2];
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
                return $resultModule->run( $view, $parameters );
            }
            else
            {
                $upload->cleanupAll();
                return $module->redirectTo( '/' . $mainNode->attribute( 'url' ) );
            }
        }
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

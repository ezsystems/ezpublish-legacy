<?php
//
// Definition of eZContentUpload class
//
// Created on: <28-Apr-2003 11:04:47 sp>
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

/*! \file ezcontentupload.php
*/

/*!
  \class eZContentUpload ezcontentupload.php
  \brief Handles simple creation of content objects by uploading files

  This class makes it easy to use the start a new file upload
  and let it be created as a content object.

  Using it is simply to call the \link upload \endlink function with some parameters.

\code
eZContentUpload::upload( array( 'action_name' => 'MyActionName' ), $module );
\endcode

  It requires the module objects as the second parameter to redirect and the first
  define how the upload page should behave. Normally you just want to set \c action_name
  and define the behaviour of that action in settings/upload.ini.

  Fetching the result afterwards is done by calling the result() method, it will return
  the resulting node ID or object ID depending on the configuration of the upload action.

\code
eZContentUpload::result( 'MyActionName' );
\endcode

  It is also possible to use this class to upload a given file (HTTP or regular) as an object.
  The correct class and location can be determined automatically.

  Simple create a new instance of this class and then call handleUpload() or handleLocalFile().

\code
$upload = new eZContentUpload( array() );
$upload->handleUpload( $result, 'UploadFile', 'auto', false );

$upload->handleLocalFile( $result, 'a_yellow_flower.jpg', 'auto' );
*/

class eZContentUpload
{
    /*!
     Initializes the object with the session data if they are found.
     If \a $params is supplied it used instead.
    */
    function eZContentUpload( $params = false )
    {
        $http =& eZHTTPTool::instance();
        if ( !$params && $http->hasSessionVariable( 'ContentUploadParameters' ) )
        {
            $this->Parameters =& $http->sessionVariable( 'ContentUploadParameters' );
        }
        else
        {
            $this->Parameters = $params;
        }
    }

    /*!
     \return an array with attribute names.
    */
    function attributes()
    {
        return array_keys( $this->Parameters );
    }

    /*!
     \return true if the attribute name \a $attributeName is among the upload parameters.
    */
    function hasAttribute( $attributeName )
    {
        return array_key_exists( $attributeName, $this->Parameters );
    }

    /*!
     \return the attribute value of the attribute named \a $attributeName or \c null if no such attribute.
    */
    function &attribute( $attributeName )
    {
        if ( isset( $this->Parameters[$attributeName] ) )
            return $this->Parameters[$attributeName];
        return null;
    }

    /*!
     \static
     Sets some session data taken from \a $parameters and start the upload module by redirecting to it using \a $module.
     Most data will be automatically derived from the \c action_name value taken from settings/upload.ini, other
     values will override default values.
    */
    function upload( $parameters = array(), &$module )
    {
        $ini =& eZINI::instance( 'upload.ini' );

        if ( !isset( $parameters['action_name'] ) )
            $parameters['action_name'] = $ini->variable( 'UploadSettings', 'DefaultActionName' );

        if ( !isset( $parameters['result_action_name'] ) )
            $parameters['result_action_name'] = $parameters['action_name'];

        if ( !isset( $parameters['navigation_part_identifier'] ) )
            $parameters['navigation_part_identifier'] = false;

        if ( !isset( $parameters['type'] ) )
            $parameters['type'] = $parameters['action_name'];

        if ( !isset( $parameters['return_type'] ) )
        {
            if ( $ini->hasVariable( $parameters['type'], 'ReturnType' ) )
                $parameters['return_type'] = $ini->variable( $parameters['type'], 'ReturnType' );
            else
                $parameters['return_type'] = $ini->variable( 'UploadSettings', 'DefaultReturnType' );
        }

        if ( !isset( $parameters['upload_custom_action'] ) )
            $parameters['upload_custom_action'] = false;

        if ( !isset( $parameters['custom_action_data'] ) )
            $parameters['custom_action_data'] = false;

        if ( !isset( $parameters['description_template'] ) )
            $parameters['description_template'] = false;

        if ( !isset( $parameters['parent_nodes'] ) )
        {
            $parameters['parent_nodes'] = false;
            if ( $ini->hasVariable( $parameters['type'], 'ParentNodes' ) )
            {
                $parameters['parent_nodes'] = $ini->variable( $parameters['type'], 'ParentNodes' );
            }
        }

        if ( isset( $parameters['keys'] ) )
        {
            $overrideStartNode = false;
            foreach ( $parameters['keys'] as $key => $keyValue )
            {
                $variableName = 'StartNode_' . $key;
                if ( !$ini->hasVariable( $parameters['type'], $variableName ) )
                    continue;
                $keyData = $ini->variable( $parameters['type'], $variableName );
                if ( is_array( $keyValue ) )
                {
                    foreach ( $keyValue as $keySubValue )
                    {
                        if ( isset( $keyData[$keySubValue] ) )
                            $overrideStartNode = $keyData[$keySubValue];
                    }
                }
                else if ( isset( $keyData[$keyValue] ) )
                {
                    $overrideStartNode = $keyData[$keyValue];
                }
                if ( $overrideStartNode )
                    break;
            }
//             if ( $overrideStartNode )
//                 $parameters['start_node'] = $overrideStartNode;
        }

        if ( !isset( $parameters['persistent_data'] ) )
            $parameters['persistent_data'] = false;

        if ( isset( $parameters['parent_nodes'] ) and
             is_array( $parameters['parent_nodes'] ) )
        {
            foreach ( $parameters['parent_nodes'] as $key => $parentNode )
            {
                if ( !is_numeric( $parentNode ) )
                {
                    $parameters['parent_nodes'][$key] = eZContentUpload::nodeAliasID( $parentNode );
                }
            }
        }

        if ( !isset( $parameters['result_uri'] ) )
            $parameters['result_uri'] = false;

        if ( !isset( $parameters['result_module'] ) )
            $parameters['result_module'] = false;

        $parameters['result'] = false;

        $http =& eZHTTPTool::instance();
        $http->setSessionVariable( 'ContentUploadParameters', $parameters );

        if ( is_null( $module ) )
        {
            return "/content/upload/";
        }
        else
        {
            $module->redirectTo( "/content/upload/" );
            return "/content/upload/";
        }
    }

    /*!
     Fetches the local file, figures out its MIME-type and creates the proper content object out of it.

     \param $filePath Path to file which should be stored.
     \param $result Result data, will be filled with information which the client can examine, contains:
                    - errors - An array with errors, each element is an array with \c 'description' containing the text
     \param $location The node ID which the new object will be placed or the string \c 'auto' for automatic placement of type.
     \param $existingNode Pass a contentobjecttreenode object to let the uploading be done to an existing object,
                          if not it will create one from scratch.

     \return \c false if something failed or \c true if succesful.
    */
    function handleLocalFile( &$result, $filePath, $location, $existingNode )
    {
        $result = array( 'errors' => array(),
                         'notices' => array(),
                         'result' => false,
                         'redirect_url' => false );
        $errors =& $result['errors'];
        $notices =& $result['notices'];

        if ( !file_exists( $filePath ) )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'The file %filename does not exist, cannot insert file.', null,
                                                        array( '%filename' => $filePath ) ) );
            return false;
        }
        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $mimeData = eZMimeType::findByFileContents( $filePath );
        $mime = $mimeData['name'];

        $object = false;
        $class = false;
        // Figure out class identifier from an existing node
        // if not we will have to detect it from the mimetype
        if ( is_object( $existingNode ) )
        {
            $object =& $existingNode->object();
            $class =& $object->contentClass();
            $classIdentifier = $class->attribute( 'identifier' );
        }
        else
        {
            $classIdentifier = $this->detectClassIdentifier( $mime );
        }

        if ( !$classIdentifier )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'No matching class identifier found.' ) );
            return false;
        }

        if ( !is_object( $class ) )
            $class =& eZContentClass::fetchByIdentifier( $classIdentifier );

        if ( !$class )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'The class %class_identifier does not exist.', null,
                                                        array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }


        $parentNodes = false;
        $parentMainNode = false;
        // If do not have an existing node we need to figure
        // out the locations from $location and $classIdentifier
        if ( !is_object( $existingNode ) )
        {
            $locationOK = $this->detectLocations( $classIdentifier, $location, $parentNodes, $parentMainNode );
            if ( !$locationOK )
            {
                $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                            'Was not able to figure out placement of object.' ) );
                return false;
            }
        }

        $uploadINI =& eZINI::instance( 'upload.ini' );
        $iniGroup = $classIdentifier . '_ClassSettings';
        if ( !$uploadINI->hasGroup( $iniGroup ) )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'No configuration group in upload.ini for class identifier %class_identifier.', null,
                                                        array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }

        $fileAttribute = $uploadINI->variable( $iniGroup, 'FileAttribute' );
        $nameAttribute = $uploadINI->variable( $iniGroup, 'NameAttribute' );
        $dataMap = $class->dataMap();

        $fileAttribute = $this->findRegularFileAttribute( $dataMap, $fileAttribute );
        if ( !$fileAttribute )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'No matching file attribute found, cannot create content object without this.' ) );
            return false;
        }

        $nameAttribute = $this->findStringAttribute( $dataMap, $nameAttribute );
        if ( !$nameAttribute )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'No matching name attribute found, cannot create content object without this.' ) );
            return false;
        }

        $variables = array( 'original_filename' => $filePath,
                            'mime_type' => $mime );
        $variables['original_filename_base'] = $mimeData['basename'];
        $variables['original_filename_suffix'] = $mimeData['suffix'];


        $namePattern = $uploadINI->variable( $iniGroup, 'NamePattern' );
        $nameString = $this->processNamePattern( $variables, $namePattern );

        // When we have an existing node we will already have the
        // $object as well (look above).
        // If not we need to create a new object from the class
        if ( !is_object( $existingNode ) )
        {
            $object =& $class->instantiate();
        }

        unset( $dataMap );
        $dataMap =& $object->dataMap();

        $status = $dataMap[$fileAttribute]->insertRegularFile( $object, $object->attribute( 'current_version' ), eZContentObject::defaultLanguage(),
                                                               $filePath,
                                                               $storeResult );
        if ( $status === null )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'The attribute %class_identifier does not support regular file storage.', null,
                                                        array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }
        else if ( !$status )
        {
            $errors = array_merge( $errors, $storeResult['errors'] );
            return false;
        }
        if ( $storeResult['require_storage'] )
            $dataMap[$fileAttribute]->store();

        $status = $dataMap[$nameAttribute]->insertSimpleString( $object, $object->attribute( 'current_version' ), eZContentObject::defaultLanguage(),
                                                                $nameString,
                                                                $storeResult );
        if ( $status === null )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'The attribute %class_identifier does not support simple string storage.', null,
                                                        array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }
        else if ( !$status )
        {
            $errors = array_merge( $errors, $storeResult['errors'] );
            return false;
        }
        if ( $storeResult['require_storage'] )
            $dataMap[$nameAttribute]->store();

        return $this->publishObject( $result, $errors, $notices,
                                     $object, $class, $parentNodes, $parentMainNode );
    }

    /*!
     Fetches the uploaded file, figures out its MIME-type and creates the proper content object out of it.

     \param $httpFileIdentifier The HTTP identifier of the uploaded file, this must match the \em name of your \em input tag.
     \param $result Result data, will be filled with information which the client can examine, contains:
                    - errors - An array with errors, each element is an array with \c 'description' containing the text
     \param $location The node ID which the new object will be placed or the string \c 'auto' for automatic placement of type.
     \param $existingNode Pass a contentobjecttreenode object to let the uploading be done to an existing object,
                          if not it will create one from scratch.

     \return \c false if something failed or \c true if succesful.
    */
    function handleUpload( &$result, $httpFileIdentifier, $location, $existingNode )
    {
        $result = array( 'errors' => array(),
                         'notices' => array(),
                         'result' => false,
                         'redirect_url' => false );
        $errors =& $result['errors'];
        $notices =& $result['notices'];

        $this->fetchHTTPFile( $httpFileIdentifier, $errors, $file, $mimeData );
        if ( !$file )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'No HTTP file found, cannot fetch uploaded file.' ) );
            return false;
        }
        $mime = $mimeData['name'];
        if ( $mime == '' )
            $mime = $file->attribute( "mime_type" );

        $object = false;
        $class = false;
        // Figure out class identifier from an existing node
        // if not we will have to detect it from the mimetype
        if ( is_object( $existingNode ) )
        {
            $object =& $existingNode->object();
            $class =& $object->contentClass();
            $classIdentifier = $class->attribute( 'identifier' );
        }
        else
        {
            $classIdentifier = $this->detectClassIdentifier( $mime );
        }

        if ( !$classIdentifier )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'No matching class identifier found.' ) );
            return false;
        }

        if ( !is_object( $class ) )
            $class =& eZContentClass::fetchByIdentifier( $classIdentifier );

        if ( !$class )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'The class %class_identifier does not exist.', null,
                                                        array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }


        $parentNodes = false;
        $parentMainNode = false;
        // If do not have an existing node we need to figure
        // out the locations from $location and $classIdentifier
        if ( !is_object( $existingNode ) )
        {
            $locationOK = $this->detectLocations( $classIdentifier, $location, $parentNodes, $parentMainNode );
            if ( !$locationOK )
            {
                $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                            'Was not able to figure out placement of object.' ) );
                return false;
            }
        }

        $uploadINI =& eZINI::instance( 'upload.ini' );
        $iniGroup = $classIdentifier . '_ClassSettings';
        if ( !$uploadINI->hasGroup( $iniGroup ) )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'No configuration group in upload.ini for class identifier %class_identifier.', null,
                                                        array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }

        $fileAttribute = $uploadINI->variable( $iniGroup, 'FileAttribute' );
        $nameAttribute = $uploadINI->variable( $iniGroup, 'NameAttribute' );
        $dataMap = $class->dataMap();

        $fileAttribute = $this->findHTTPFileAttribute( $dataMap, $fileAttribute );
        if ( !$fileAttribute )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'No matching file attribute found, cannot create content object without this.' ) );
            return false;
        }

        $nameAttribute = $this->findStringAttribute( $dataMap, $nameAttribute );
        if ( !$nameAttribute )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'No matching name attribute found, cannot create content object without this.' ) );
            return false;
        }

        $variables = array( 'original_filename' => $file->attribute( 'original_filename' ),
                            'mime_type' => $mime );
        $variables['original_filename_base'] = $mimeData['basename'];
        $variables['original_filename_suffix'] = $mimeData['suffix'];


        $namePattern = $uploadINI->variable( $iniGroup, 'NamePattern' );
        $nameString = $this->processNamePattern( $variables, $namePattern );

        // When we have an existing node we will already have the
        // $object as well (look above).
        // If not we need to create a new object from the class
        if ( !is_object( $existingNode ) )
        {
            $object =& $class->instantiate();
        }

        unset( $dataMap );
        $dataMap =& $object->dataMap();

        $status = $dataMap[$fileAttribute]->insertHTTPFile( $object, $object->attribute( 'current_version' ), eZContentObject::defaultLanguage(),
                                                            $file, $mimeData,
                                                            $storeResult );
        if ( $status === null )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'The attribute %class_identifier does not support HTTP file storage.', null,
                                                        array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }
        else if ( !$status )
        {
            $errors = array_merge( $errors, $storeResult['errors'] );
            return false;
        }
        if ( $storeResult['require_storage'] )
            $dataMap[$fileAttribute]->store();

        $status = $dataMap[$nameAttribute]->insertSimpleString( $object, $object->attribute( 'current_version' ), eZContentObject::defaultLanguage(),
                                                                $nameString,
                                                                $storeResult );
        if ( $status === null )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'The attribute %class_identifier does not support simple string storage.', null,
                                                        array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }
        else if ( !$status )
        {
            $errors = array_merge( $errors, $storeResult['errors'] );
            return false;
        }
        if ( $storeResult['require_storage'] )
            $dataMap[$nameAttribute]->store();

        return $this->publishObject( $result, $errors, $notices,
                                     $object, $class, $parentNodes, $parentMainNode );
    }

    /*!
     \private
     Publishes the object to the selected locations.

     \return \c true if everything was OK, \c false if something failed.
    */
    function publishObject( &$result, &$errors, &$notices,
                            &$object, &$class, $parentNodes, $parentMainNode )
    {
        if ( is_array( $parentNodes ) )
        {
            foreach ( $parentNodes as $key => $parentNode )
            {
                $object->createNodeAssignment( $parentNode, $parentNode == $parentMainNode );
            }
        }

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
        $result['contentobject'] =& $object;
        $result['contentobject_id'] = $object->attribute( 'id' );
        $result['contentobject_version'] = $object->attribute( 'current_version' );
        $result['contentobject_main_node'] = false;
        $result['contentobject_main_node_id'] = false;

        $this->setResult( array( 'node_id' => false,
                                 'object_id' => $object->attribute( 'id' ),
                                 'object_version' => $object->attribute( 'current_version' ) ) );

        switch ( $operationResult['status'] )
        {
            case EZ_MODULE_OPERATION_HALTED:
            {
                if ( isset( $operationResult['redirect_url'] ) )
                {
                    $result['redirect_url'] = $operationResult['redirect_url'];
                    $notices[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                                 'Publishing of content object was halted.' ) );
                    return true;
                }
                else if ( isset( $operationResult['result'] ) )
                {
                    $result['result'] = $operationResult['result'];
                    return true;
                }
            } break;

            case EZ_MODULE_OPERATION_CANCELED:
            {
                $result['result'] = ezi18n( 'kernel/content/upload',
                                            'Publish process was cancelled.' );
                return true;
            } break;

            case EZ_MODULE_OPERATION_CONTINUE:
            {
            }
        }

        $mainNode = $object->mainNode();
        $result['contentobject_main_node'] = $mainNode;
        $result['contentobject_main_node_id'] = $mainNode->attribute( 'node_id' );
        $this->setResult( array( 'node_id' => $mainNode->attribute( 'node_id' ),
                                 'object_id' => $object->attribute( 'id' ),
                                 'object_version' => $object->attribute( 'current_version' ) ) );
//         $newObjectName = $object->name();
        return true;
    }

    /*!
      Finds the file attribute for object \a $contentObject and tries to extract
      file information using eZDataType::storedFileInformation().
      \return The information structure or \c false if it fails somehow.
    */
    function objectFileInfo( &$contentObject )
    {
        $uploadINI =& eZINI::instance( 'upload.ini' );

        $class =& $contentObject->contentClass();
        $classIdentifier = $class->attribute( 'identifier' );
        $classDataMap =& $class->dataMap();
        $attributeIdentifier = false;
        if ( $uploadINI->hasGroup( $classIdentifier . '_ClassSettings' ) )
        {
            $attributeIdentifier = $uploadINI->variable( $classIdentifier . '_ClassSettings', 'FileAttribute' );
        }

        $attributeIdentifier = $this->findRegularFileAttribute( $classDataMap, $attributeIdentifier );
        if ( !$attributeIdentifier )
        {
            // No file information for this object
            return false;
        }

        $dataMap =& $contentObject->dataMap();
        $fileAttribute =& $dataMap[$attributeIdentifier];

        if ( $fileAttribute->hasStoredFileInformation( $contentObject, false, false ) )
        {
            $info = $fileAttribute->storedFileInformation( $contentObject, false, false );
            return $info;
        }
        return false;
    }

    /*!
     \private
     Fetches the HTTP-File into \a $file and fills in MIME-Type information into \a $mimeData.
     \return \c false if something went wrong.
    */
    function fetchHTTPFile( $httpFileIdentifier, &$errors, &$file, &$mimeData )
    {
        include_once( 'lib/ezutils/classes/ezhttpfile.php' );
        if ( !eZHTTPFile::canFetch( $httpFileIdentifier ) )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'A file is required for upload, no file were found.' ) );
            return false;
        }

        $file = eZHTTPFile::fetch( $httpFileIdentifier );
        if ( get_class( $file ) != "ezhttpfile" )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'Expected a eZHTTPFile object but got nothing.' ) );
            return false;
        }

        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $mimeData = eZMimeType::findByFileContents( $file->attribute( "original_filename" ) );

        return false;
    }

    /*!
     \private
     \static
     Checks if the attribute with the identifier \a $fileAttribute in \a $dataMap
     supports HTTP file uploading. If not it will go trough all attributes and
     find the first that has this support.

     \return The identifier of the matched attribute or \c false if none were found.
     \param $dataMap Associative array with class attributes, the key is attribute identifier
     \param $fileAttribute The identifier of the attribute that is expected to have the file datatype.
    */
    function findHTTPFileAttribute( &$dataMap, $fileAttribute )
    {
        $fileDatatype = false;
        if ( isset( $dataMap[$fileAttribute] ) )
            $fileDatatype =& $dataMap[$fileAttribute]->dataType();
        if ( !$fileDatatype or
             !$fileDatatype->isHTTPFileInsertionSupported() )
        {
            $fileAttribute = false;
            foreach ( $dataMap as $identifier => $attribute )
            {
                $attribute =& $dataMap[$identifier];
                $datatype = $attribute->dataType();
                if ( $datatype->isHTTPFileInsertionSupported() )
                {
                    $fileAttribute = $identifier;
                    break;
                }
            }
        }
        return $fileAttribute;
    }

    /*!
     \private
     \static
     Checks if the attribute with the identifier \a $fileAttribute in \a $dataMap
     supports file uploading. If not it will go trough all attributes and
     find the first that has this support.

     \return The identifier of the matched attribute or \c false if none were found.
     \param $dataMap Associative array with class attributes, the key is attribute identifier
     \param $fileAttribute The identifier of the attribute that is expected to have the file datatype.
    */
    function findRegularFileAttribute( &$dataMap, $fileAttribute )
    {
        $fileDatatype = false;
        if ( isset( $dataMap[$fileAttribute] ) )
            $fileDatatype =& $dataMap[$fileAttribute]->dataType();
        if ( !$fileDatatype or
             !$fileDatatype->isRegularFileInsertionSupported() )
        {
            $fileAttribute = false;
            foreach ( $dataMap as $identifier => $attribute )
            {
                $attribute =& $dataMap[$identifier];
                $datatype = $attribute->dataType();
                if ( $datatype->isRegularFileInsertionSupported() )
                {
                    $fileAttribute = $identifier;
                    break;
                }
            }
        }
        return $fileAttribute;
    }

    /*!
     \private
     \static
     Checks if the attribute with the identifier \a $nameAttribute in \a $dataMap
     supports string insertion. If not it will go trough all attributes and
     find the first that has this support.

     \return The identifier of the matched attribute or \c false if none were found.
     \param $dataMap Associative array with class attributes, the key is attribute identifier
     \param $nameAttribute The identifier of the attribute that is expected to have the string datatype.
    */
    function findStringAttribute( &$dataMap, $nameAttribute )
    {
        $nameDatatype = false;
        if ( isset( $dataMap[$nameAttribute] ) )
            $nameDatatype =& $dataMap[$nameAttribute]->dataType();
        if ( !$nameDatatype or
             !$nameDatatype->isSimpleStringInsertionSupported() )
        {
            $nameAttribute = false;
            foreach ( $dataMap as $identifier => $attribute )
            {
                $attribute =& $dataMap[$identifier];
                $datatype =& $attribute->dataType();
                if ( $datatype->isSimpleStringInsertionSupported() )
                {
                    $nameAttribute = $identifier;
                    break;
                }
            }
        }
        return $nameAttribute;
    }

    /*!
     \private
     \static
     Figures out the class which should be used for file with
     MIME-Type \a $mime and returns the class identifier.
     \param $mime A string defining the MIME-Type, will be used to determine class identifier.
    */
    function detectClassIdentifier( $mime )
    {
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
        return $classIdentifier;
    }

    /*!
     \private
     Figures out the location(s) in which the class with
     the identifier \a $classIdentifier should be placed.
     The returned locations will either be a node ID or an identifier
     for a node (e.g. content).

     \return \c true if a location was found or \c false if no location could be determined
     \param $classIdentifier Identifier of class, is used to determine location
     \param $location The wanted location, either use \c 'auto' for automatic placement
                      or number to determine to parent node ID.
     \param[out] $parentNodes Will contain an array with node IDs or identifiers if a location could be detected.
     \param[out] $parentMainNode Will contain the ID of the main node if a location could be detected.
    */
    function detectLocations( $classIdentifier, $location,
                              &$parentNodes, &$parentMainNode )
    {
        if ( $this->attribute( 'parent_nodes' ) )
        {
            $parentNodes = $this->attribute( 'parent_nodes' );
        }
        else
        {
            if ( $location == 'auto' or !is_numeric( $location ) )
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
            else
            {
                $parentNodes = array( $location );
            }
        }
        if ( !$parentNodes )
        {
            $parentNodes = array( $defaultPlacement );
        }
        if ( !$parentNodes or
             count( $parentNodes ) == 0 )
        {
            return false;
        }

        foreach ( $parentNodes as $key => $parentNode )
        {
            $parentNode = eZContentUpload::nodeAliasID( $parentNode );
            $parentNodes[$key] = $parentNode;
        }
        if ( !$parentMainNode )
        {
            $parentMainNode = $parentNodes[0];
        }
        $parentMainNode = eZContentUpload::nodeAliasID( $parentMainNode );

        return true;
    }

    /*!
     \private
     \static
     Parses the name pattern \a $namePattern and replaces any
     variables found in \a $variables with the variable value.

     \return The resulting string with all \e tags replaced.
     \param $variables An associative array where the key is variable name and element the variable value.
     \param $namePattern A string containing of plain text or \e tags, each tag is enclosed in < and > and
                         defines name of the variable to lookup.

     \code
     $vars = array( 'name' => 'A name',
                    'filename' => 'myfile.txt' );
     $name = $this->parseNamePattern( $vars, '<name> - <filename>' );
     print( $name ); // Will output 'A name - myfile.txt'
     \endcode
    */
    function processNamePattern( $variables, $namePattern )
    {
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
        return $nameString;
    }

    /*!
     \static
     \return the node ID for the node alias \a $nodeName or \c false if no ID could be found.
    */
    function nodeAliasID( $nodeName )
    {
        if ( is_numeric( $nodeName ) )
            return $nodeName;
        $uploadINI =& eZINI::instance( 'upload.ini' );
        $aliasList = $uploadINI->variable( 'UploadSettings', 'AliasList' );
        if ( isset( $aliasList[$nodeName] ) )
            return $aliasList[$nodeName];
        $contentINI =& eZINI::instance( 'content.ini' );
        if ( $nodeName == 'content' )
            return $contentINI->variable( 'NodeSettings', 'RootNode' );
        else if ( $nodeName == 'users' )
            return $contentINI->variable( 'NodeSettings', 'UserRootNode' );
        else if ( $nodeName == 'media' )
            return $contentINI->variable( 'NodeSettings', 'MediaRootNode' );
        else if ( $nodeName == 'setup' )
            return $contentINI->variable( 'NodeSettings', 'SetupRootNode' );
        else
            return false;
    }

    /*!
     Sets the result array to \a $result and stores the session variable.
    */
    function setResult( $result )
    {
        $this->Parameters['result'] = $result;
        $http =& eZHTTPTool::instance();
        $http->setSessionVariable( 'ContentUploadParameters', $this->Parameters );
    }

    /*!
     \static
     \return the result of the previous upload operation or \c false if no result was found.
             It uses the action name \a $actionName to determine which result to look for.
     \param $cleanup If \c true it the persisten data is cleaned up by calling cleanup().
    */
    function result( $actionName, $cleanup = true )
    {
        if ( isset( $this ) and
             get_class( $this) == 'ezcontentupload' )
            $upload =& $this;
        else
            $upload = new eZContentUpload();

        $isNodeSelection = $upload->attribute( 'return_type' ) == 'NodeID';
        $resultData = $upload->attribute( 'result' );
        $result = false;
        if ( $isNodeSelection )
        {
            $result = $resultData['node_id'];
        }
        else
        {
            $result = $resultData['object_id'];
        }
        if ( $cleanup )
            eZContentUpload::cleanup( $actionName );
        return $result;
    }

    /*!
     \static
     Cleans up the persistent data and result for action named \a $actionName
    */
    function cleanup( $actionName )
    {
        $http =& eZHTTPTool::instance();
        $http->removeSessionVariable( 'ContentUploadParameters' );
    }

    /*!
     \static
     Similar to cleanup() but removes persistent data from all actions.
    */
    function cleanupAll()
    {
        $http =& eZHTTPTool::instance();
        $http->removeSessionVariable( 'ContentUploadParameters' );
    }

    /// \privatesection
    /// The upload parameters.
    var $Parameters = false;
}

?>

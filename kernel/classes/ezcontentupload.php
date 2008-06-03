<?php
//
// Definition of eZContentUpload class
//
// Created on: <28-Apr-2003 11:04:47 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

  Simply create an instance and then call handleUpload() or handleLocalFile().

\code
$upload = new eZContentUpload();
$upload->handleUpload( $result, 'UploadFile', 'auto', false );

$upload->handleLocalFile( $result, 'a_yellow_flower.jpg', 'auto' );
\endcode
*/

//include_once( 'lib/ezutils/classes/ezhttptool.php' );
//include_once( 'lib/ezutils/classes/ezini.php' );
//include_once( 'kernel/classes/ezcontentobject.php' );

class eZContentUpload
{

    const STATUS_PERMISSION_DENIED = 1;

    /*!
     Initializes the object with the session data if they are found.
     If \a $params is supplied it used instead.
    */
    function eZContentUpload( $params = false )
    {
        $http = eZHTTPTool::instance();
        if ( !$params && $http->hasSessionVariable( 'ContentUploadParameters' ) )
        {
            $this->Parameters =& $http->sessionVariable( 'ContentUploadParameters' );
        }
        else
        {
            if ( $params === false )
                $params = array();
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
    function attribute( $attributeName )
    {
        if ( isset( $this->Parameters[$attributeName] ) )
        {
            return $this->Parameters[$attributeName];
        }

        eZDebug::writeError( "Attribute '$attributeName' does not exist", 'eZContentUpload::attribute' );
        return null;
    }

    /*!
     \static
     Sets some session data taken from \a $parameters and start the upload module by redirecting to it using \a $module.
     Most data will be automatically derived from the \c action_name value taken from settings/upload.ini, other
     values will override default values.
    */
    static function upload( $parameters = array(), $module )
    {
        $ini = eZINI::instance( 'upload.ini' );

        if ( !isset( $parameters['action_name'] ) )
            $parameters['action_name'] = $ini->variable( 'UploadSettings', 'DefaultActionName' );

        if ( !isset( $parameters['result_action_name'] ) )
            $parameters['result_action_name'] = $parameters['action_name'];

        if ( !isset( $parameters['navigation_part_identifier'] ) )
            $parameters['navigation_part_identifier'] = false;
        if ( !$parameters['navigation_part_identifier'] and
             $ini->hasVariable( 'UploadSettings', 'NavigationPartIdentifier' ) )
            $parameters['navigation_part_identifier'] = $ini->variable( 'UploadSettings', 'NavigationPartIdentifier' );

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

        if ( !isset( $parameters['cancel_uri'] ) )
            $parameters['cancel_uri'] = false;

        if ( !isset( $parameters['result_module'] ) )
            $parameters['result_module'] = false;

        $parameters['result'] = false;

        $http = eZHTTPTool::instance();
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
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function handleLocalFile( &$result, $filePath, $location, $existingNode, $nameString = '' )
    {
        $result = array( 'errors' => array(),
                         'notices' => array(),
                         'result' => false,
                         'redirect_url' => false,
                         'status' => false );

        if ( !file_exists( $filePath ) )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'The file %filename does not exist, cannot insert file.', null,
                                                array( '%filename' => $filePath ) ) );
            return false;
        }
        //include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $mimeData = eZMimeType::findByFileContents( $filePath );
        $mime = $mimeData['name'];

        $handler = $this->findHandler( $result, $mimeData );
        if ( $handler === false )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'There was an error trying to instantiate content upload handler.' ) );
            return false;
        }

        // If this is an object we have a special handler for the file.
        // The handler will be responsible for the rest of the execution.
        if ( is_object( $handler ) )
        {
            $originalFilename = $filePath;
            return $handler->handleFile( $this, $result,
                                         $filePath, $originalFilename, $mimeData,
                                         $location, $existingNode );
        }

        $object = false;
        $class = false;
        // Figure out class identifier from an existing node
        // if not we will have to detect it from the mimetype
        if ( is_object( $existingNode ) )
        {
            $object = $existingNode->object();
            $class = $object->contentClass();
            $classIdentifier = $class->attribute( 'identifier' );
        }
        else
        {
            $classIdentifier = $this->detectClassIdentifier( $mime );
        }

        if ( !$classIdentifier )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'No matching class identifier found.' ) );
            return false;
        }

        if ( !is_object( $class ) )
            $class = eZContentClass::fetchByIdentifier( $classIdentifier );

        if ( !$class )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
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
            $locationOK = $this->detectLocations( $classIdentifier, $class, $location, $parentNodes, $parentMainNode );
            if ( $locationOK === false )
            {
                $result['errors'][] =
                    array( 'description' => ezi18n( 'kernel/content/upload',
                                                    'Was not able to figure out placement of object.' ) );
                return false;
            }
            elseif ( $locationOK === null )
            {
                $result['status'] = eZContentUpload::STATUS_PERMISSION_DENIED;
                $result['errors'][] =
                    array( 'description' => ezi18n( 'kernel/content/upload',
                                                    'Permission denied' ) );
                return false;
            }
        }

        $uploadINI = eZINI::instance( 'upload.ini' );
        $iniGroup = $classIdentifier . '_ClassSettings';
        if ( !$uploadINI->hasGroup( $iniGroup ) )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'No configuration group in upload.ini for class identifier %class_identifier.', null,
                                                array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }

        $fileAttribute = $uploadINI->variable( $iniGroup, 'FileAttribute' );
        $dataMap = $class->dataMap();

        $fileAttribute = $this->findRegularFileAttribute( $dataMap, $fileAttribute );
        if ( !$fileAttribute )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'No matching file attribute found, cannot create content object without this.' ) );
            return false;
        }

        $nameAttribute = $uploadINI->variable( $iniGroup, 'NameAttribute' );
        if ( !$nameAttribute )
        {
            $nameAttribute = $this->findStringAttribute( $dataMap, $nameAttribute );
        }
        if ( !$nameAttribute )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'No matching name attribute found, cannot create content object without this.' ) );
            return false;
        }

        $variables = array( 'original_filename' => $mimeData['filename'],
                            'mime_type' => $mime );
        $variables['original_filename_base'] = $mimeData['basename'];
        $variables['original_filename_suffix'] = $mimeData['suffix'];

        if ( !$nameString )
        {
            $namePattern = $uploadINI->variable( $iniGroup, 'NamePattern' );
            $nameString = $this->processNamePattern( $variables, $namePattern );
        }
        // If we have an existing node we need to create
        // a new version in it.
        // If we don't we have to make a new object
        if ( is_object( $existingNode ) )
        {
            if ( $existingNode->canEdit( ) != '1' )
            {
                $result['status'] = eZContentUpload::STATUS_PERMISSION_DENIED;
                $result['errors'][] =
                    array( 'description' => ezi18n( 'kernel/content/upload',
                                                    'Permission denied' ) );
                return false;
            }
            $version = $object->createNewVersion( false, true );
            unset( $dataMap );
            $dataMap = $version->dataMap();
            $publishVersion = $version->attribute( 'version' );
        }
        else
        {
            $object = $class->instantiate();
            unset( $dataMap );
            $dataMap = $object->dataMap();
            $publishVersion = $object->attribute( 'current_version' );
        }

        $status = $dataMap[$fileAttribute]->insertRegularFile( $object, $publishVersion, eZContentObject::defaultLanguage(),
                                                               $filePath,
                                                               $storeResult );
        if ( $status === null )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'The attribute %class_identifier does not support regular file storage.', null,
                                                array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }
        else if ( !$status )
        {
            $result['errors'] = array_merge( $result['errors'], $storeResult['errors'] );
            return false;
        }
        if ( $storeResult['require_storage'] )
            $dataMap[$fileAttribute]->store();

        $status = $dataMap[$nameAttribute]->insertSimpleString( $object, $publishVersion, eZContentObject::defaultLanguage(),
                                                                $nameString,
                                                                $storeResult );
        if ( $status === null )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'The attribute %class_identifier does not support simple string storage.', null,
                                                array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }
        else if ( !$status )
        {
            $result['errors'] = array_merge( $result['errors'], $storeResult['errors'] );
            return false;
        }
        if ( $storeResult['require_storage'] )
            $dataMap[$nameAttribute]->store();

        return $this->publishObject( $result, $result['errors'], $result['notices'],
                                     $object, $publishVersion, $class, $parentNodes, $parentMainNode );
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
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function handleUpload( &$result, $httpFileIdentifier, $location, $existingNode, $nameString = '' )
    {
        $result = array( 'errors' => array(),
                         'notices' => array(),
                         'result' => false,
                         'redirect_url' => false );

        $this->fetchHTTPFile( $httpFileIdentifier, $result['errors'], $file, $mimeData );
        if ( !$file )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'No HTTP file found, cannot fetch uploaded file.' ) );
            return false;
        }
        $mime = $mimeData['name'];
        if ( $mime == '' )
            $mime = $file->attribute( "mime_type" );

        $handler = $this->findHandler( $result, $mimeData );
        if ( $handler === false )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'There was an error trying to instantiate content upload handler.' ) );
            return false;
        }

        // If this is an object we have a special handler for the file
        // The handler will be responsible for the rest of the execution.
        if ( is_object( $handler ) )
        {
            $filePath = $file->attribute( "filename" );
            $originalFilename = $file->attribute( "original_filename" );
            $handlerResult = $handler->handleFile( $this, $result,
                                                   $filePath, $originalFilename, $mimeData,
                                                   $location, $existingNode );
            if ( is_object( $result['contentobject'] ) )
                return $handlerResult;
        }

        $object = false;
        $class = false;
        // Figure out class identifier from an existing node
        // if not we will have to detect it from the mimetype
        if ( is_object( $existingNode ) )
        {
            $object = $existingNode->object();
            $class = $object->contentClass();
            $classIdentifier = $class->attribute( 'identifier' );
        }
        else
        {
            $classIdentifier = $this->detectClassIdentifier( $mime );
        }

        if ( !$classIdentifier )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'No matching class identifier found.' ) );
            return false;
        }

        if ( !is_object( $class ) )
            $class = eZContentClass::fetchByIdentifier( $classIdentifier );

        if ( !$class )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
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
            $locationOK = $this->detectLocations( $classIdentifier, $class, $location, $parentNodes, $parentMainNode );
            if ( $locationOK === false )
            {
                $result['errors'][] =
                    array( 'description' => ezi18n( 'kernel/content/upload',
                                                    'Was not able to figure out placement of object.' ) );
                return false;
            }
            elseif ( $locationOK === null )
            {
                $result['status'] = eZContentUpload::STATUS_PERMISSION_DENIED;
                $result['errors'][] =
                    array( 'description' => ezi18n( 'kernel/content/upload',
                                                    'Permission denied' ) );
                return false;
            }
        }

        $uploadINI = eZINI::instance( 'upload.ini' );
        $iniGroup = $classIdentifier . '_ClassSettings';
        if ( !$uploadINI->hasGroup( $iniGroup ) )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'No configuration group in upload.ini for class identifier %class_identifier.', null,
                                                array( '%class_identifier' => $classIdentifier ) ) );
            return false;
        }

        $fileAttribute = $uploadINI->variable( $iniGroup, 'FileAttribute' );
        $dataMap = $class->dataMap();

        if ( $classIdentifier == 'image' )
        {
            $classAttribute = $dataMap['image'];
            $maxSize = 1024 * 1024 * $classAttribute->attribute( 'data_int1' );
            if ( $maxSize != 0 && $file->attribute( 'filesize' ) > $maxSize )
            {
                $result['errors'][] =
                    array( 'description' => ezi18n( 'kernel/content/upload',
                                                    'The size of the uploaded file exceeds the limit set for this site: %1 bytes.', null, array( $maxSize ) ) );
                return false;
            }
        }


        $fileAttribute = $this->findHTTPFileAttribute( $dataMap, $fileAttribute );
        if ( !$fileAttribute )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'No matching file attribute found, cannot create content object without this.' ) );
            return false;
        }

        $nameAttribute = $uploadINI->variable( $iniGroup, 'NameAttribute' );
        if ( !$nameAttribute )
        {
            $nameAttribute = $this->findStringAttribute( $dataMap, $nameAttribute );
        }
        if ( !$nameAttribute )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'No matching name attribute found, cannot create content object without this.' ) );
            return false;
        }

        $variables = array( 'original_filename' => $file->attribute( 'original_filename' ),
                            'mime_type' => $mime );
        $variables['original_filename_base'] = $mimeData['basename'];
        $variables['original_filename_suffix'] = $mimeData['suffix'];

        if ( !$nameString )
        {
            $namePattern = $uploadINI->variable( $iniGroup, 'NamePattern' );
            $nameString = $this->processNamePattern( $variables, $namePattern );
        }

        $db = eZDB::instance();
        $db->begin();

        // If we have an existing node we need to create
        // a new version in it.
        // If we don't we have to make a new object
        if ( is_object( $existingNode ) )
        {
            if ( $existingNode->canEdit( ) != '1' )
            {
                $result['status'] = eZContentUpload::STATUS_PERMISSION_DENIED;
                $result['errors'][] =
                    array( 'description' => ezi18n( 'kernel/content/upload',
                                                    'Permission denied' ) );

                $db->commit();
                return false;
            }
            $version = $object->createNewVersion( false, true );
            unset( $dataMap );
            $dataMap = $version->dataMap();
            $publishVersion = $version->attribute( 'version' );
        }
        else
        {
            $object = $class->instantiate();
            unset( $dataMap );
            $dataMap = $object->dataMap();
            $publishVersion = $object->attribute( 'current_version' );
        }

        unset( $dataMap );
        $dataMap = $object->dataMap();

        $status = $dataMap[$fileAttribute]->insertHTTPFile( $object, $publishVersion, eZContentObject::defaultLanguage(),
                                                            $file, $mimeData,
                                                            $storeResult );
        if ( $status === null )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'The attribute %class_identifier does not support HTTP file storage.', null,
                                                        array( '%class_identifier' => $classIdentifier ) ) );
            $db->commit();
            return false;
        }
        else if ( !$status )
        {
            $result['errors'] = array_merge( $result['errors'], $storeResult['errors'] );
            $db->commit();
            return false;
        }
        if ( $storeResult['require_storage'] )
            $dataMap[$fileAttribute]->store();

        $status = $dataMap[$nameAttribute]->insertSimpleString( $object, $publishVersion, eZContentObject::defaultLanguage(),
                                                                $nameString,
                                                                $storeResult );
        if ( $status === null )
        {
            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                'The attribute %class_identifier does not support simple string storage.', null,
                                                array( '%class_identifier' => $classIdentifier ) ) );
            $db->commit();
            return false;
        }
        else if ( !$status )
        {
            $result['errors'] = array_merge( $result['errors'], $storeResult['errors'] );
            $db->commit();
            return false;
        }
        if ( $storeResult['require_storage'] )
            $dataMap[$nameAttribute]->store();

        $tmpresult = $this->publishObject( $result, $result['errors'], $result['notices'],
                                           $object, $publishVersion, $class, $parentNodes, $parentMainNode );

        $db->commit();
        return $tmpresult;
    }

    /*!
     \private
     Publishes the object to the selected locations.

     \return \c true if everything was OK, \c false if something failed.
    */
    function publishObject( &$result, &$errors, &$notices,
                            $object, $publishVersion, $class, $parentNodes, $parentMainNode )
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

        //include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                     'version' => $publishVersion ) );

        $objectID = $object->attribute( 'id' );
        unset( $object );
        $object = eZContentObject::fetch( $objectID );
        $result['contentobject'] = $object;
        $result['contentobject_id'] = $object->attribute( 'id' );
        $result['contentobject_version'] = $publishVersion;
        $result['contentobject_main_node'] = false;
        $result['contentobject_main_node_id'] = false;

        $this->setResult( array( 'node_id' => false,
                                 'object_id' => $object->attribute( 'id' ),
                                 'object_version' => $publishVersion ) );

        switch ( $operationResult['status'] )
        {
            case eZModuleOperationInfo::STATUS_HALTED:
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

            case eZModuleOperationInfo::STATUS_CANCELLED:
            {
                $result['result'] = ezi18n( 'kernel/content/upload',
                                            'Publish process was cancelled.' );
                return true;
            } break;

            case eZModuleOperationInfo::STATUS_CONTINUE:
            {
            }
        }

        $mainNode = $object->mainNode();
        $result['contentobject_main_node'] = $mainNode;
        $result['contentobject_main_node_id'] = $mainNode->attribute( 'node_id' );
        $this->setResult( array( 'node_id' => $mainNode->attribute( 'node_id' ),
                                 'object_id' => $object->attribute( 'id' ),
                                 'object_version' => $publishVersion ) );

        return true;
    }

    /*!
      Finds the file attribute for object \a $contentObject and tries to extract
      file information using eZDataType::storedFileInformation().
      \return The information structure or \c false if it fails somehow.
    */
    function objectFileInfo( $contentObject )
    {
        $uploadINI = eZINI::instance( 'upload.ini' );

        $class = $contentObject->contentClass();
        $classIdentifier = $class->attribute( 'identifier' );
        $classDataMap = $class->dataMap();
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

        $dataMap = $contentObject->dataMap();
        $fileAttribute = $dataMap[$attributeIdentifier];

        if ( $fileAttribute->hasStoredFileInformation( $contentObject, false, false ) )
        {
            return $fileAttribute->storedFileInformation( $contentObject, false, false );
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
        //include_once( 'lib/ezutils/classes/ezhttpfile.php' );
        if ( !eZHTTPFile::canFetch( $httpFileIdentifier ) )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'A file is required for upload, no file were found.' ) );
            return false;
        }

        $file = eZHTTPFile::fetch( $httpFileIdentifier );
        if ( !( $file instanceof eZHTTPFile ) )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                        'Expected a eZHTTPFile object but got nothing.' ) );
            return false;
        }

        //include_once( 'lib/ezutils/classes/ezmimetype.php' );
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
    function findHTTPFileAttribute( $dataMap, $fileAttribute )
    {
        $fileDatatype = false;
        if ( isset( $dataMap[$fileAttribute] ) )
            $fileDatatype = $dataMap[$fileAttribute]->dataType();
        if ( !$fileDatatype or
             !$fileDatatype->isHTTPFileInsertionSupported() )
        {
            $fileAttribute = false;
            foreach ( $dataMap as $identifier => $attribute )
            {
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
    function findRegularFileAttribute( $dataMap, $fileAttribute )
    {
        $fileDatatype = false;
        if ( isset( $dataMap[$fileAttribute] ) )
            $fileDatatype = $dataMap[$fileAttribute]->dataType();
        if ( !$fileDatatype or
             !$fileDatatype->isRegularFileInsertionSupported() )
        {
            $fileAttribute = false;
            foreach ( $dataMap as $identifier => $attribute )
            {
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
    function findStringAttribute( $dataMap, $nameAttribute )
    {
        $nameDatatype = false;
        if ( isset( $dataMap[$nameAttribute] ) )
            $nameDatatype = $dataMap[$nameAttribute]->dataType();
        if ( !$nameDatatype or
             !$nameDatatype->isSimpleStringInsertionSupported() )
        {
            $nameAttribute = false;
            foreach ( $dataMap as $identifier => $attribute )
            {
                $datatype = $attribute->dataType();
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
        $uploadINI = eZINI::instance( 'upload.ini' );

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
    function detectLocations( $classIdentifier, $class, $location,
                              &$parentNodes, &$parentMainNode )
    {
        $isAccessChecked = false;
        $parentMainNode = false;
        if ( $this->hasAttribute( 'parent_nodes' ) &&
             $this->attribute( 'parent_nodes' ) )
        {
            $parentNodes = $this->attribute( 'parent_nodes' );
            foreach( $parentNodes as $key => $parentNode )
            {
                $parentNodes[$key] = eZContentUpload::nodeAliasID( $parentNode );
                if ( !eZContentUpload::checkAccess( $parentNodes[$key], $class ) )
                {
                    $parentNodes = false;
                    return null;
                }
            }
        }
        else
        {
            if ( $location == 'auto' or !is_numeric( $location ) )
            {
                $contentINI = eZINI::instance( 'content.ini' );

                $classPlacementMap = $contentINI->variable( 'RelationAssignmentSettings', 'ClassSpecificAssignment' );
                $defaultPlacement = $contentINI->variable( 'RelationAssignmentSettings', 'DefaultAssignment' );

                // Find location that matches the class and where user is allowed to create objects
                foreach ( $classPlacementMap as $classData )
                {
                    $classElements = explode( ';', $classData );
                    $classList = explode( ',', $classElements[0] );

                    if ( in_array( $classIdentifier, $classList ) )
                    {
                        $parentNodes = explode( ',', $classElements[1] );
                        if ( count( $parentNodes ) == 0 )
                            continue;

                        if ( isset( $classElements[2] ) )
                            $parentMainNode = eZContentUpload::nodeAliasID( $classElements[2] );

                        // check access rights and convert to IDs
                        foreach ( $parentNodes as $key => $parentNode )
                        {
                            $parentNodeID = eZContentUpload::nodeAliasID( $parentNode );
                            if ( !$parentNodeID )
                            {
                                $parentNodes = false;
                                break;
                            }

                            $parentNodes[$key] = $parentNodeID;

                            if ( !eZContentUpload::checkAccess( $parentNodeID, $class ) )
                            {
                                eZDebug::writeNotice( "Upload assignment setting '$classData' skipped - no permissions", 'eZContentUpload::detectLocations' );
                                $parentNodes = false;
                                break;
                            }
                        }

                        if ( $parentNodes )
                        {
                            eZDebug::writeNotice( "Matched assignment for upload :'$classData'", 'eZContentUpload::detectLocations' );
                            break;
                        }
                    }
                }

                if ( !$parentNodes && isset( $defaultPlacement ) && $defaultPlacement )
                {
                    $defaultNodeID = eZContentUpload::nodeAliasID( $defaultPlacement );
                    if ( $defaultNodeID )
                    {
                        if ( eZContentUpload::checkAccess( $defaultNodeID, $class ) )
                        {
                            $parentNodes = array( $defaultNodeID );
                        }
                        else
                        {
                            eZDebug::writeNotice( "No create permission for default upload location: node #$defaultNodeID", 'eZContentUpload::detectLocations' );
                            return null;
                        }

                    }
                }
            }
            else
            {
                $locationID = eZContentUpload::nodeAliasID( $location );
                if ( $locationID )
                {
                    if ( eZContentUpload::checkAccess( $locationID, $class ) )
                    {
                        $parentNodes = array( $locationID );
                    }
                    else
                    {
                        eZDebug::writeNotice( "No create permission for upload location: node #$locationID", 'eZContentUpload::detectLocations' );
                        return null;
                    }
                }
            }
        }

        if ( !$parentNodes || count( $parentNodes ) == 0 )
        {
            return false;
        }

        if ( !$parentMainNode )
        {
            $parentMainNode = $parentNodes[0];
        }

        return true;
    }

    /*!
     \private
     \static
    */

    function checkAccess( $nodeID, $class )
    {
        $parentNodeObj = eZContentObjectTreeNode::fetch( $nodeID );
        $parentObject =  $parentNodeObj->attribute( 'object' );

        if ( $parentNodeObj->checkAccess( 'create',
                                          $class->attribute( 'id' ),
                                          $parentObject->attribute( 'contentclass_id' ) ) != '1' )
        {
            return false;
        }
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
        {
            $node = eZContentObjectTreeNode::fetch( $nodeName, false, false );
            if ( is_array( $node ) )
            {
                $result['status'] = eZContentUpload::STATUS_PERMISSION_DENIED;
                $errors[] = array( 'description' => ezi18n( 'kernel/content/upload',
                                                            'Permission denied' ) );

                return $nodeName;
            }
        }

        $uploadINI = eZINI::instance( 'upload.ini' );
        $aliasList = $uploadINI->variable( 'UploadSettings', 'AliasList' );
        if ( isset( $aliasList[$nodeName] ) )
            return $aliasList[$nodeName];
        $contentINI = eZINI::instance( 'content.ini' );
        if ( $nodeName == 'content' or $nodeName == 'root' )
            return $contentINI->variable( 'NodeSettings', 'RootNode' );
        else if ( $nodeName == 'users' )
            return $contentINI->variable( 'NodeSettings', 'UserRootNode' );
        else if ( $nodeName == 'media' )
            return $contentINI->variable( 'NodeSettings', 'MediaRootNode' );
        else if ( $nodeName == 'setup' )
            return $contentINI->variable( 'NodeSettings', 'SetupRootNode' );

        // Check for node path element
        $pathPos = strpos( $nodeName, '/' );
        if ( $pathPos !== false )
        {
            $node = eZContentObjectTreeNode::fetchByURLPath( $nodeName, false );
            if ( is_array( $node ) )
            {
                return $node['node_id'];
            }
        }
        return false;
    }

    /*!
     Sets the result array to \a $result and stores the session variable.
    */
    function setResult( $result )
    {
        $this->Parameters['result'] = $result;
        $http = eZHTTPTool::instance();
        $http->setSessionVariable( 'ContentUploadParameters', $this->Parameters );
    }

    /*!
     \static
     \return the result of the previous upload operation or \c false if no result was found.
             It uses the action name \a $actionName to determine which result to look for.
     \param $cleanup If \c true it the persisten data is cleaned up by calling cleanup().
    */
    static function result( $actionName, $cleanup = true )
    {
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
    static function cleanup( $actionName )
    {
        $http = eZHTTPTool::instance();
        $http->removeSessionVariable( 'ContentUploadParameters' );
    }

    /*!
     \static
     Similar to cleanup() but removes persistent data from all actions.
    */
    function cleanupAll()
    {
        $http = eZHTTPTool::instance();
        $http->removeSessionVariable( 'ContentUploadParameters' );
    }

    /*!
     Finds the correct upload handler for the file specified in \a $mimeInfo.
     If no handler is found it will return the default attribute based handler eZContentUpload,
     this means that the file is passed to one suitable attribute and handled from there.
     \return An object with the interface eZContentUploadHandler or \c false if an error occured.
             Will return \c true if there is no handler configured for this type.
    */
    function findHandler( &$result, $mimeInfo )
    {
        // Check for specific mime handler plugin
        $uploadINI = eZINI::instance( 'upload.ini' );
        $uploadSettings = $uploadINI->variable( 'CreateSettings', 'MimeUploadHandlerMap' );

        $mime = $mimeInfo['name'];
        $elements = explode( '/', $mime );
        $mimeGroup = $elements[0];

        $handlerName = false;
        // Check first for MIME-Type group, this allows a handler to work
        // with an entire group, e.g. image
        if ( isset( $uploadSettings[$mimeGroup] ) )
        {
            $handlerName = $uploadSettings[$mimeGroup];
        }
        else if ( isset( $uploadSettings[$mime] ) )
        {
            $handlerName = $uploadSettings[$mime];
        }

        if ( $handlerName !== false )
        {
            //include_once( 'lib/ezutils/classes/ezextension.php' );
            $baseDirectory = eZExtension::baseDirectory();
            $extensionDirectories = eZExtension::activeExtensions();

            // Check all extension directories for an upload handler for this mimetype
            foreach ( $extensionDirectories as $extensionDirectory )
            {
                $handlerPath = $baseDirectory . '/' . $extensionDirectory . '/uploadhandlers/' . $handlerName . ".php";
                if ( !file_exists( $handlerPath ) )
                    continue;

                include_once( $handlerPath );
                $handlerClass = $handlerName;
                $handler = new $handlerClass();
                if ( !$handler instanceof eZContentUploadHandler )
                {
                    eZDebug::writeError( "Content upload handler '$handlerName' is not inherited from eZContentUploadHandler. All upload handlers must do this.", 'eZContentUpload::findHandler' );
                    return false;
                }
                return $handler;
            }

            $result['errors'][] =
                array( 'description' => ezi18n( 'kernel/content/upload',
                                                "Could not find content upload handler '%handler_name'",
                                                null, array( '%handler_name' => $handlerName ) ) );

            return false;
        }
        return true;
    }

    /// \privatesection
    /// The upload parameters.
    public $Parameters = false;
}

?>

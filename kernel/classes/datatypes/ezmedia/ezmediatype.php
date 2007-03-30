<?php
//
// Definition of eZMediaType class
//
// Created on: <30-Apr-2002 13:06:21 bf>
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

/*!
  \class eZMediaType ezmediatype.php
  \ingroup eZDatatype
  \brief The class eZMediaType handles storage and playback of media files.

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "kernel/classes/datatypes/ezmedia/ezmedia.php" );
include_once( "lib/ezfile/classes/ezfile.php" );
include_once( "lib/ezutils/classes/ezmimetype.php" );
include_once( "lib/ezutils/classes/ezhttpfile.php" );
include_once( "lib/ezfile/classes/ezdir.php" );

define( "EZ_DATATYPESTRING_MEDIA", "ezmedia" );
define( 'EZ_DATATYPESTRING_MAX_MEDIA_FILESIZE_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_MAX_MEDIA_FILESIZE_VARIABLE', '_ezmedia_max_filesize_' );
define( "EZ_DATATYPESTRING_TYPE_FIELD", "data_text1" );
define( "EZ_DATATYPESTRING_TYPE_VARIABLE", "_ezmedia_type_" );

class eZMediaType extends eZDataType
{
    function eZMediaType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_MEDIA, ezi18n( 'kernel/classes/datatypes', "Media", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Sets value according to current version
    */
    function postInitializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $contentObjectAttributeID = $originalContentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );
            $oldfile = eZMedia::fetch( $contentObjectAttributeID, $currentVersion );
            if( $oldfile != null )
            {
                $oldfile->setAttribute( 'contentobject_attribute_id', $contentObjectAttribute->attribute( 'id' ) );
                $oldfile->setAttribute( "version",  $version );
                $oldfile->store();
            }
        }
        else
        {
            $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
            $version = $contentObjectAttribute->attribute( 'version' );

            $media = eZMedia::create( $contentObjectAttributeID, $version );

            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $pluginPage = eZMediaType::pluginPage( $contentClassAttribute->attribute( 'data_text1' ) );

            $media->setAttribute( 'quality', 'high' );
            $media->setAttribute( 'pluginspage', $pluginPage );
            $media->store();
        }
    }

    /*!
     Delete stored attribute
    */
    function deleteStoredObjectAttribute( &$contentObjectAttribute, $version = null )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $mediaFiles = eZMedia::fetch( $contentObjectAttributeID, null );
        $sys = eZSys::instance();
        $storage_dir = $sys->storageDirectory();
        if ( $version == null )
        {
            foreach ( $mediaFiles as $mediaFile )
            {
                $mimeType =  $mediaFile->attribute( "mime_type" );
                list( $prefix, $suffix ) = split ('[/]', $mimeType );
//                $orig_dir = "var/storage/original/" . $prefix;
                $orig_dir = $storage_dir . '/original/' . $prefix;
                $fileName = $mediaFile->attribute( "filename" );

                if ( $fileName == '' )
                    continue;

                // VS-DBFILE

                require_once( 'kernel/classes/ezclusterfilehandler.php' );
                $file = eZClusterFileHandler::instance( $orig_dir . "/" . $fileName );
                if ( $file->exists() )
                    $file->delete();
            }
        }
        else
        {
            $count = 0;
            $currentBinaryFile = eZMedia::fetch( $contentObjectAttributeID, $version );
            if ( $currentBinaryFile != null )
            {
                $mimeType =  $currentBinaryFile->attribute( "mime_type" );
                $currentFileName = $currentBinaryFile->attribute( "filename" );
                list( $prefix, $suffix ) = is_string( $mimeType ) && $mimeType ? split ( '[/]', $mimeType ) : array( null, null );
//              $orig_dir = "var/storage/original/" . $prefix;
                $orig_dir = $storage_dir . '/original/' . $prefix;
                foreach ( $mediaFiles as $mediaFile )
                {
                    $fileName = $mediaFile->attribute( "filename" );
                    if( $currentFileName == $fileName )
                        $count += 1;
                }
                if ( $count == 1 && $currentFileName != '' )
                {
                    // VS-DBFILE

                    require_once( 'kernel/classes/ezclusterfilehandler.php' );
                    $file = eZClusterFileHandler::instance( $orig_dir . "/" . $currentFileName );
                    if ( $file->exists() )
                        $file->delete();
                }
            }
        }
        eZMedia::remove( $contentObjectAttributeID, $version );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $httpFileName = $base . "_data_mediafilename_" . $contentObjectAttribute->attribute( "id" );
        $maxSize = 1024 * 1024 * $classAttribute->attribute( EZ_DATATYPESTRING_MAX_MEDIA_FILESIZE_FIELD );
        $mustUpload = false;

        if ( $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );
            $media = eZMedia::fetch( $contentObjectAttributeID, $version );
            if ( $media === null || !$media->attribute( 'filename' ) )
            {
                $mustUpload = true;
            }
        }

        $canFetchResult = eZHTTPFile::canFetch( $httpFileName, $maxSize );
        if ( $mustUpload && $canFetchResult == EZ_UPLOADEDFILE_DOES_NOT_EXIST )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                'A valid media file is required.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        if ( $canFetchResult == EZ_UPLOADEDFILE_EXCEEDS_PHP_LIMIT )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                'The size of the uploaded file exceeds the limit set by upload_max_filesize directive in php.ini. Please contact the site administrator.') );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        if ( $canFetchResult == EZ_UPLOADEDFILE_EXCEEDS_MAX_SIZE )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                'The size of the uploaded file exceeds site maximum: %1 bytes.' ), $maxSize );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Checks if file uploads are enabled, if not it gives a warning.
    */
    function checkFileUploads()
    {
        $isFileUploadsEnabled = ini_get( 'file_uploads' ) != 0;
        if ( !$isFileUploadsEnabled )
        {
            $isFileWarningAdded =& $GLOBALS['eZMediaTypeWarningAdded'];
            if ( !isset( $isFileWarningAdded ) or
                 !$isFileWarningAdded )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'kernel',
                                                              'number' => EZ_ERROR_KERNEL_NOT_AVAILABLE ),
                                            'text' => ezi18n( 'kernel/classes/datatypes',
                                                              'File uploading is not enabled. Please contact the site administrator to enable it.' ) ) );
                $isFileWarningAdded = true;
            }
        }
    }

    /*!
     \static
     Returns plugin page by media type

    */
    function pluginPage( $mediaType )
    {
        $pluginPage = '';
        switch( $mediaType )
        {
            case 'flash':
                $pluginPage = "http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash";
            break;
            case 'quick_time':
                $pluginPage = "http://quicktime.apple.com";
            break;
            case 'real_player' :
                $pluginPage = "http://www.real.com/";
            break;
            case 'windows_media_player' :
                $pluginPage = "http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112" ;
            break;
            default:
                $pluginPage = "";
            break;
        }

        return $pluginPage;
    }

    /*!
     Fetches input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $debug = eZDebug::instance();

        eZMediaType::checkFileUploads();

        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $player = $classAttribute->attribute( "data_text1" );
        $pluginPage = eZMediaType::pluginPage( $player );

        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $version = $contentObjectAttribute->attribute( "version" );
        $width = $http->postVariable( $base . "_data_media_width_" . $contentObjectAttribute->attribute( "id" ) );
        $height = $http->postVariable( $base . "_data_media_height_" . $contentObjectAttribute->attribute( "id" ) );
        $quality = $http->hasPostVariable( $base . "_data_media_quality_" . $contentObjectAttribute->attribute( "id" ) ) ? $http->postVariable( $base . "_data_media_quality_" . $contentObjectAttribute->attribute( "id" ) ) : null;
        if ( $http->hasPostVariable( $base . "_data_media_controls_" . $contentObjectAttribute->attribute( "id" ) ) )
            $controls = $http->postVariable( $base . "_data_media_controls_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $controls = null;

        $media = eZMedia::fetch( $contentObjectAttributeID, $version );
        if ( $media == null )
        {
           $media = eZMedia::create( $contentObjectAttributeID, $version );
        }

        $media->setAttribute( "contentobject_attribute_id", $contentObjectAttributeID );
        $media->setAttribute( "version", $version );
        $media->setAttribute( "width", $width );
        $media->setAttribute( "height", $height );
        $media->setAttribute( "quality", $quality );
        $media->setAttribute( "controls", $controls );
        $media->setAttribute( "pluginspage", $pluginPage );
        if ( $http->hasPostVariable( $base . "_data_media_is_autoplay_" . $contentObjectAttribute->attribute( "id" ) ) )
            $media->setAttribute( "is_autoplay", true );
        else
            $media->setAttribute( "is_autoplay", false );
        if ( $http->hasPostVariable( $base . "_data_media_has_controller_" . $contentObjectAttribute->attribute( "id" ) ) )
            $media->setAttribute( "has_controller", true );
        else
            $media->setAttribute( "has_controller", false );
        if ( $http->hasPostVariable( $base . "_data_media_is_loop_" . $contentObjectAttribute->attribute( "id" ) ) )
            $media->setAttribute( "is_loop", true );
        else
            $media->setAttribute( "is_loop", false );

        $mediaFilePostVarName = $base . "_data_mediafilename_" . $contentObjectAttribute->attribute( "id" );
        if ( eZHTTPFile::canFetch( $mediaFilePostVarName ) )
            $mediaFile = eZHTTPFile::fetch( $mediaFilePostVarName );
        else
            $mediaFile = null;
        if ( strtolower( get_class( $mediaFile ) ) == "ezhttpfile" )
        {
            $mimeData = eZMimeType::findByFileContents( $mediaFile->attribute( "original_filename" ) );
            $mime = $mimeData['name'];

            if ( $mime == '' )
            {
                $mime = $mediaFile->attribute( "mime_type" );
            }
            $extension = preg_replace('/.*\.(.+?)$/', '\\1', $mediaFile->attribute( "original_filename" ) );
            $mediaFile->setMimeType( $mime );
            if ( !$mediaFile->store( "original", $extension ) )
            {
                $debug->writeError( "Failed to store http-file: " . $mediaFile->attribute( "original_filename" ),
                                     "eZMediaType" );
                return false;
            }

            $orig_dir = $mediaFile->storageDir( "original" );
            $debug->writeNotice( "dir=$orig_dir" );
            $media->setAttribute( "filename", basename( $mediaFile->attribute( "filename" ) ) );
            $media->setAttribute( "original_filename", $mediaFile->attribute( "original_filename" ) );
            $media->setAttribute( "mime_type", $mime );

            // VS-DBFILE

            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            //$filePath = $media->attribute( 'original_filename' );
            $filePath = $mediaFile->attribute( 'filename' );
            $fileHandler = eZClusterFileHandler::instance();
            $fileHandler->fileStore( $filePath, 'media', true, $mime );
        }

        $media->store();
        $contentObjectAttribute->setContent( $media );
        return true;
    }

    function storeObjectAttribute( &$contentObjectAttribute )
    {
    }

    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        if ( $action == "delete_media" )
        {
            $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );
            $this->deleteStoredObjectAttribute( $contentObjectAttribute, $version );
            $media = eZMedia::create( $contentObjectAttributeID, $version );
            $contentObjectAttribute->setContent( $media );
        }
    }

    /*!
     \reimp
     HTTP file insertion is supported.
    */
    function isHTTPFileInsertionSupported()
    {
        return true;
    }

    /*!
     \reimp
     Regular file insertion is supported.
    */
    function isRegularFileInsertionSupported()
    {
        return true;
    }

    /*!
     \reimp
     Inserts the file using the eZMedia class.
    */
    function insertHTTPFile( &$object, $objectVersion, $objectLanguage,
                             &$objectAttribute, &$httpFile, $mimeData,
                             &$result )
    {
        $result = array( 'errors' => array(),
                         'require_storage' => false );
        $errors =& $result['errors'];
        $attributeID = $objectAttribute->attribute( 'id' );

        $media = eZMedia::fetch( $attributeID, $objectVersion );
        if ( $media === null )
            $media = eZMedia::create( $attributeID, $objectVersion );

        $httpFile->setMimeType( $mimeData['name'] );
        if ( !$httpFile->store( "original", false, false ) )
        {
            $errors[] = array( 'description' => ezi18n( 'kernel/classe/datatypes/ezmedia',
                                                        'Failed to store media file %filename. Please contact the site administrator.', null,
                                                        array( '%filename' => $httpFile->attribute( "original_filename" ) ) ) );
            return false;
        }

        $classAttribute =& $objectAttribute->contentClassAttribute();
        $player = $classAttribute->attribute( "data_text1" );
        $pluginPage = eZMediaType::pluginPage( $player );

        $media->setAttribute( "contentobject_attribute_id", $attributeID );
        $media->setAttribute( "version", $objectVersion );
        $media->setAttribute( "filename", basename( $httpFile->attribute( "filename" ) ) );
        $media->setAttribute( "original_filename", $httpFile->attribute( "original_filename" ) );
        $media->setAttribute( "mime_type", $mimeData['name'] );

        // Setting width and height to zero means that the browser/player must find the size itself.
        // In the future we will probably analyze the media file and find this information
        $width = $height = 0;
        // Quality is not known, so we don't set any
        $quality = false;
        // Not sure what this is for, set to false
        $controls = false;
        // We want to show controllers by default
        $hasController = true;
        // Don't play automatically
        $isAutoplay = false;
        // Don't loop movie
        $isLoop = false;

        $media->setAttribute( "width", $width );
        $media->setAttribute( "height", $height );
        $media->setAttribute( "quality", $quality );
        $media->setAttribute( "controls", $controls );
        $media->setAttribute( "pluginspage", $pluginPage );
        $media->setAttribute( "is_autoplay", $isAutoplay );
        $media->setAttribute( "has_controller", $hasController );
        $media->setAttribute( "is_loop", $isLoop );

        // SP-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $filePath = $httpFile->attribute( 'filename' );
        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileStore( $filePath, 'mediafile', true, $mimeData['name'] );


        $media->store();

        $objectAttribute->setContent( $media );
        return true;
    }

    /*!
     \reimp
     Inserts the file using the eZMedia class.
    */
    function insertRegularFile( &$object, $objectVersion, $objectLanguage,
                                &$objectAttribute, $filePath,
                                &$result )
    {
        $result = array( 'errors' => array(),
                         'require_storage' => false );
        $errors =& $result['errors'];
        $attributeID = $objectAttribute->attribute( 'id' );

        $media = eZMedia::fetch( $attributeID, $objectVersion );
        if ( $media === null )
            $media = eZMedia::create( $attributeID, $objectVersion );

        $fileName = basename( $filePath );
        $mimeData = eZMimeType::findByFileContents( $filePath );
        $storageDir = eZSys::storageDirectory();
        list( $group, $type ) = explode( '/', $mimeData['name'] );
        $destination = $storageDir . '/original/' . $group;
        $oldumask = umask( 0 );
        if ( !eZDir::mkdir( $destination, false, true ) )
        {
            umask( $oldumask );
            return false;
        }
        umask( $oldumask );
        $destination = $destination . '/' . $fileName;
        copy( $filePath, $destination );
        // SP-DBFILE
        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileStore( $destination, 'mediafile', true, $mimeData['name'] );

        $classAttribute =& $objectAttribute->contentClassAttribute();
        $player = $classAttribute->attribute( "data_text1" );
        $pluginPage = eZMediaType::pluginPage( $player );

        $media->setAttribute( "contentobject_attribute_id", $attributeID );
        $media->setAttribute( "version", $objectVersion );
        $media->setAttribute( "filename", $fileName );
        $media->setAttribute( "original_filename", $fileName );
        $media->setAttribute( "mime_type", $mimeData['name'] );

        // Setting width and height to zero means that the browser/player must find the size itself.
        // In the future we will probably analyze the media file and find this information
        $width = $height = 0;
        // Quality is not known, so we don't set any
        $quality = false;
        // Not sure what this is for, set to false
        $controls = false;
        // We want to show controllers by default
        $hasController = true;
        // Don't play automatically
        $isAutoplay = false;
        // Don't loop movie
        $isLoop = false;

        $media->setAttribute( "width", $width );
        $media->setAttribute( "height", $height );
        $media->setAttribute( "quality", $quality );
        $media->setAttribute( "controls", $controls );
        $media->setAttribute( "pluginspage", $pluginPage );
        $media->setAttribute( "is_autoplay", $isAutoplay );
        $media->setAttribute( "has_controller", $hasController );
        $media->setAttribute( "is_loop", $isLoop );

        $media->store();

        $objectAttribute->setContent( $media );
        return true;
    }

    /*!
      \reimp
      We support file information
    */
    function hasStoredFileInformation( &$object, $objectVersion, $objectLanguage,
                                       &$objectAttribute )
    {
        return true;
    }

    /*!
      \reimp
      Extracts file information for the media entry.
    */
    function storedFileInformation( &$object, $objectVersion, $objectLanguage,
                                    &$objectAttribute )
    {
        $mediaFile = eZMedia::fetch( $objectAttribute->attribute( "id" ),
                                      $objectAttribute->attribute( "version" ) );
        if ( $mediaFile )
        {
            return $mediaFile->storedFileInfo();
        }
        return false;
    }

    function storeClassAttribute( &$attribute, $version )
    {
    }

    function storeDefinedClassAttribute( &$attribute )
    {
    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     \reimp
    */
    function fixupClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $filesizeName = $base . EZ_DATATYPESTRING_MAX_MEDIA_FILESIZE_VARIABLE . $classAttribute->attribute( 'id' );
        $typeName = $base . EZ_DATATYPESTRING_TYPE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $filesizeName ) )
        {
            $filesizeValue = $http->postVariable( $filesizeName );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_MEDIA_FILESIZE_FIELD, $filesizeValue );
        }
        if ( $http->hasPostVariable( $typeName ) )
        {
            $typeValue = $http->postVariable( $typeName );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_TYPE_FIELD, $typeValue );
        }
    }

    /*!
     Returns the object title.
    */
    function title( $contentObjectAttribute,  $name = "original_filename" )
    {
        $mediaFile = eZMedia::fetch( $contentObjectAttribute->attribute( "id" ),
                                      $contentObjectAttribute->attribute( "version" ) );

        if ( $mediaFile != null )
            $value = $mediaFile->attribute( $name );
        else
            $value = "";
        return $value;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $mediaFile = eZMedia::fetch( $contentObjectAttribute->attribute( "id" ),
                                      $contentObjectAttribute->attribute( "version" ) );
        if ( !$mediaFile )
            return false;
       return true;
    }

    function objectAttributeContent( $contentObjectAttribute )
    {
        $mediaFile = eZMedia::fetch( $contentObjectAttribute->attribute( "id" ),
                                      $contentObjectAttribute->attribute( "version" ) );
        if ( !$mediaFile )
        {
            $retValue = false;
            return $retValue;
        }
        return $mediaFile;
    }

    function metaData()
    {
        return "";
    }
    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $objectAttribute )
    {
        $mediaFile = $objectAttribute->content();

        if ( is_object( $mediaFile ) )
        {
            return implode( '|', array( $mediaFile->attribute( 'filepath' ), $mediaFile->attribute( 'original_filename' ) ) );
        }
        else
            return '';
    }



    function fromString( &$objectAttribute, $string )
    {
        if( !$string )
            return true;

        $result = array();
        return $this->insertRegularFile( $objectAttribute->attribute( 'object' ),
                                         $objectAttribute->attribute( 'version' ),
                                         $objectAttribute->attribute( 'language_code' ),
                                         $objectAttribute,
                                         $string,
                                         $result );

    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $maxSize = $classAttribute->attribute( EZ_DATATYPESTRING_MAX_MEDIA_FILESIZE_FIELD );
        $type = $classAttribute->attribute( EZ_DATATYPESTRING_TYPE_FIELD );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'max-size', $maxSize,
                                                                                     array( 'unit-size' => 'mega' ) ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'type', $type ) );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $maxSize = $attributeParametersNode->elementTextContentByName( 'max-size' );
        $sizeNode = $attributeParametersNode->elementByName( 'max-size' );
        $unitSize = $sizeNode->attributeValue( 'unit-size' );
        $type = $attributeParametersNode->elementTextContentByName( 'type' );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_MEDIA_FILESIZE_FIELD, $maxSize );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_TYPE_FIELD, $type );
    }

    /*!
     \param package
     \param content attribute

     \return a DOM representation of the content object attribute
    */
    function serializeContentObjectAttribute( &$package, &$objectAttribute )
    {

        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $mediaFile = $objectAttribute->attribute( 'content' );
        if ( !$mediaFile )
        {
            // Media type content could not be found.
            return $node;
        }

        $fileKey = md5( mt_rand() );

        $fileInfo = $mediaFile->storedFileInfo();
        $package->appendSimpleFile( $fileKey, $fileInfo['filepath'] );

        $mediaNode = eZDOMDocument::createElementNode( 'media-file' );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'filesize', $mediaFile->attribute( 'filesize' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'filename', $mediaFile->attribute( 'filename' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'original-filename', $mediaFile->attribute( 'original_filename' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'mime-type', $mediaFile->attribute( 'mime_type' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'filekey', $fileKey ) );

        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'width', $mediaFile->attribute( 'width' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'height', $mediaFile->attribute( 'height' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'has-controller', $mediaFile->attribute( 'has_controller' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'controls', $mediaFile->attribute( 'controls' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'is-autoplay', $mediaFile->attribute( 'is_autoplay' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'plugins-page', $mediaFile->attribute( 'plugingspage' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'quality', $mediaFile->attribute( 'quality' ) ) );
        $mediaNode->appendAttribute( eZDOMDocument::createAttributeNode( 'is-loop', $mediaFile->attribute( 'is_loop' ) ) );
        $node->appendChild( $mediaNode );

        return $node;
    }

    /*!
     \reimp
     \param package
     \param contentobject attribute object
     \param ezdomnode object
    */
    function unserializeContentObjectAttribute( &$package, &$objectAttribute, $attributeNode )
    {
        $mediaNode = $attributeNode->elementByName( 'media-file' );
        if ( !$mediaNode )
        {
            // No media type data found.
            return;
        }

        $mediaFile = eZMedia::create( $objectAttribute->attribute( 'id' ), $objectAttribute->attribute( 'version' ) );

        $sourcePath = $package->simpleFilePath( $mediaNode->attributeValue( 'filekey' ) );

        include_once( 'lib/ezfile/classes/ezdir.php' );
        $ini = eZINI::instance();
        $mimeType = $mediaNode->attributeValue( 'mime-type' );
        list( $mimeTypeCategory, $mimeTypeName ) = explode( '/', $mimeType );
        $destinationPath = eZSys::storageDirectory() . '/original/' . $mimeTypeCategory . '/';
        if ( !file_exists( $destinationPath ) )
        {
            $oldumask = umask( 0 );
            if ( !eZDir::mkdir( $destinationPath, eZDir::directoryPermission(), true ) )
            {
                umask( $oldumask );
                return false;
            }
            umask( $oldumask );
        }

        $basename = basename( $mediaNode->attributeValue( 'filename' ) );
        while ( file_exists( $destinationPath . $basename ) )
        {
            $basename = substr( md5( mt_rand() ), 0, 8 ) . '.' . eZFile::suffix( $mediaNode->attributeValue( 'filename' ) );
        }

        include_once( 'lib/ezfile/classes/ezfilehandler.php' );
        eZFileHandler::copy( $sourcePath, $destinationPath . $basename );
        $debug = eZDebug::instance();
        $debug->writeNotice( 'Copied: ' . $sourcePath . ' to: ' . $destinationPath . $basename,
                              'eZMediaType::unserializeContentObjectAttribute()' );

        $mediaFile->setAttribute( 'contentobject_attribute_id', $objectAttribute->attribute( 'id' ) );
        $mediaFile->setAttribute( 'filename', $basename );
        $mediaFile->setAttribute( 'original_filename', $mediaNode->attributeValue( 'original-filename' ) );
        $mediaFile->setAttribute( 'mime_type', $mediaNode->attributeValue( 'mime-type' ) );

        $mediaFile->setAttribute( 'width', $mediaNode->attributeValue( 'width' ) );
        $mediaFile->setAttribute( 'height', $mediaNode->attributeValue( 'height' ) );
        $mediaFile->setAttribute( 'has_controller', $mediaNode->attributeValue( 'has-controller' ) );
        $mediaFile->setAttribute( 'controls', $mediaNode->attributeValue( 'controls' ) );
        $mediaFile->setAttribute( 'is_autoplay', $mediaNode->attributeValue( 'is-autoplay' ) );
        $mediaFile->setAttribute( 'pluginspage', $mediaNode->attributeValue( 'plugins-page' ) );
        $mediaFile->setAttribute( 'quality', $mediaNode->attributeValue( 'quality' ) );
        $mediaFile->setAttribute( 'is_loop', $mediaNode->attributeValue( 'is-loop' ) );

        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileStore( $destinationPath . $basename, 'mediafile', true );

        $mediaFile->store();
    }
}

eZDataType::register( EZ_DATATYPESTRING_MEDIA, "ezmediatype" );

?>

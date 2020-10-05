<?php
/**
 * File containing the eZMediaType class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZMediaType ezmediatype.php
  \ingroup eZDatatype
  \brief The class eZMediaType handles storage and playback of media files.

*/

class eZMediaType extends eZDataType
{
    const DATA_TYPE_STRING = "ezmedia";
    const MAX_FILESIZE_FIELD = 'data_int1';
    const MAX_FILESIZE_VARIABLE = '_ezmedia_max_filesize_';
    const TYPE_FIELD = "data_text1";
    const TYPE_VARIABLE = "_ezmedia_type_";

    public function __construct()
    {
        parent::__construct( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "Media", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
        $this->FileExtensionBlackListValidator = new eZFileExtensionBlackListValidator();
    }

    /*!
     Sets value according to current version
    */
    function postInitializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
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

            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            $pluginPage = eZMediaType::pluginPage( $contentClassAttribute->attribute( 'data_text1' ) );

            $media->setAttribute( 'quality', 'high' );
            $media->setAttribute( 'pluginspage', $pluginPage );
            $media->store();
        }
    }

    /*!
     The object is being moved to trash, do any necessary changes to the attribute.
     Rename file and update db row with new name, so that access to the file using old links no longer works.
    */
    function trashStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $sys = eZSys::instance();
        $storage_dir = $sys->storageDirectory();

        if ( $version == null )
            $mediaFiles = eZMedia::fetch( $contentObjectAttributeID, null );
        else
            $mediaFiles = array( eZMedia::fetch( $contentObjectAttributeID, $version ) );

        foreach ( $mediaFiles as $mediaFile )
        {
            if ( $mediaFile == null )
                continue;
            $mimeType =  $mediaFile->attribute( "mime_type" );
            list( $prefix, $suffix ) = explode( '/', $mimeType );
            $orig_dir = $storage_dir . '/original/' . $prefix;
            $fileName = $mediaFile->attribute( "filename" );

            // Check if there are any other records in ezmedia that point to that fileName.
            $mediaObjectsWithSameFileName = eZMedia::fetchByFileName( $fileName );

            $filePath = $orig_dir . "/" . $fileName;
            $file = eZClusterFileHandler::instance( $filePath );

            if ( $file->exists() and count( $mediaObjectsWithSameFileName ) <= 1 )
            {
                // create dest filename in the same manner as eZHTTPFile::store()
                // grab file's suffix
                $fileSuffix = eZFile::suffix( $fileName );
                // prepend dot
                if ( $fileSuffix )
                    $fileSuffix = '.' . $fileSuffix;
                // grab filename without suffix
                $fileBaseName = basename( $fileName, $fileSuffix );
                // create dest filename
                $newFileName = md5( $fileBaseName . microtime() . mt_rand() ) . $fileSuffix;
                $newFilePath = $orig_dir . "/" . $newFileName;

                // rename the file, and update the database data
                $file->move( $newFilePath );
                $mediaFile->setAttribute( 'filename', $newFileName );
                $mediaFile->store();
            }
        }
    }

    /*!
     Delete stored attribute
    */
    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $sys = eZSys::instance();
        $storage_dir = $sys->storageDirectory();
        if ( $version == null )
        {
            $mediaFiles = eZMedia::fetch( $contentObjectAttributeID, null );
            foreach ( $mediaFiles as $mediaFile )
            {
                $mimeType =  $mediaFile->attribute( "mime_type" );
                list( $prefix, $suffix ) = explode('/', $mimeType );
//                $orig_dir = "var/storage/original/" . $prefix;
                $orig_dir = $storage_dir . '/original/' . $prefix;
                $fileName = $mediaFile->attribute( "filename" );

                if ( $fileName == '' )
                    continue;

                $file = eZClusterFileHandler::instance( $orig_dir . "/" . $fileName );
                if ( $file->exists() )
                    $file->delete();
            }
        }
        else
        {
            $mediaFiles = eZMedia::fetchByContentObjectID( $contentObjectAttribute->attribute( 'contentobject_id' ) );
            $count = 0;
            $currentBinaryFile = eZMedia::fetch( $contentObjectAttributeID, $version );
            if ( $currentBinaryFile != null )
            {
                $mimeType =  $currentBinaryFile->attribute( "mime_type" );
                $currentFileName = $currentBinaryFile->attribute( "filename" );
                list( $prefix, $suffix ) = is_string( $mimeType ) && $mimeType ? explode( '/', $mimeType ) : array( null, null );
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
                    $file = eZClusterFileHandler::instance( $orig_dir . "/" . $currentFileName );
                    if ( $file->exists() )
                        $file->delete();
                }
            }
        }
        eZMedia::removeByID( $contentObjectAttributeID, $version );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        $httpFileName = $base . "_data_mediafilename_" . $contentObjectAttribute->attribute( "id" );
        $maxSize = 1024 * 1024 * $classAttribute->attribute( self::MAX_FILESIZE_FIELD );
        $mustUpload = false;

        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObjectAttribute->attribute( 'version' );
        $media = eZMedia::fetch( $contentObjectAttributeID, $version );
        $extensionsBlackList = implode(', ', $this->FileExtensionBlackListValidator->extensionsBlackList() );
        if ( $media === null || !$media->attribute( 'filename' ) )
        {
            if ( $contentObjectAttribute->validateIsRequired() )
            {
                $mustUpload = true;
            }
        }
        else
        {
            $state = $this->FileExtensionBlackListValidator->validate( $media->attribute( 'filename' ) );
            if ( $state === eZInputValidator::STATE_INVALID || $state === eZInputValidator::STATE_INTERMEDIATE )
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                    "A valid file is required. The following file extensions are blacklisted: $extensionsBlackList" ) );
                return eZInputValidator::STATE_INVALID;
            }
        }

        if ( isset( $_FILES[$httpFileName] ) && $_FILES[$httpFileName]['tmp_name'] !== '')
        {
            $state = $this->FileExtensionBlackListValidator->validate( $_FILES[$httpFileName]['name'] );
            if ( $state === eZInputValidator::STATE_INVALID || $state === eZInputValidator::STATE_INTERMEDIATE )
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                    "A valid file is required. The following file extensions are blacklisted: $extensionsBlackList" ) );
                return eZInputValidator::STATE_INVALID;
            }
        }

        $canFetchResult = eZHTTPFile::canFetch( $httpFileName, $maxSize );
        if ( $mustUpload && $canFetchResult == eZHTTPFile::UPLOADEDFILE_DOES_NOT_EXIST )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                'A valid media file is required.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        if ( $canFetchResult == eZHTTPFile::UPLOADEDFILE_EXCEEDS_PHP_LIMIT )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                'The size of the uploaded file exceeds the limit set by upload_max_filesize directive in php.ini. Please contact the site administrator.') );
            return eZInputValidator::STATE_INVALID;
        }
        if ( $canFetchResult == eZHTTPFile::UPLOADEDFILE_EXCEEDS_MAX_SIZE )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                'The size of the uploaded file exceeds site maximum: %1 bytes.' ), $maxSize );
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Checks if file uploads are enabled, if not it gives a warning.
    */
    function checkFileUploads()
    {
        $isFileUploadsEnabled = ini_get( 'file_uploads' ) != 0;
        if ( !$isFileUploadsEnabled )
        {
            if ( empty( $GLOBALS['eZMediaTypeWarningAdded'] ) )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'kernel',
                                                              'number' => eZError::KERNEL_NOT_AVAILABLE ),
                                            'text' => ezpI18n::tr( 'kernel/classes/datatypes',
                                                              'File uploading is not enabled. Please contact the site administrator to enable it.' ) ) );
                $GLOBALS['eZMediaTypeWarningAdded'] = true;
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
            case 'silverlight':
                $pluginPage = "http://go.microsoft.com/fwlink/?LinkID=108182";
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
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {

        eZMediaType::checkFileUploads();

        if ( $this->validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute ) !== eZInputValidator::STATE_ACCEPTED )
        {
            return false;
        }

        $classAttribute = $contentObjectAttribute->contentClassAttribute();
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
        if ( $mediaFile instanceof eZHTTPFile )
        {
            $mimeData = eZMimeType::findByFileContents( $mediaFile->attribute( "original_filename" ) );
            $mime = $mimeData['name'];

            if ( $mime == '' )
            {
                $mime = $mediaFile->attribute( "mime_type" );
            }
            $extension = eZFile::suffix( $mediaFile->attribute( "original_filename" ) );
            $mediaFile->setMimeType( $mime );
            if ( !$mediaFile->store( "original", $extension ) )
            {
                eZDebug::writeError( "Failed to store http-file: " . $mediaFile->attribute( "original_filename" ),
                                     "eZMediaType" );
                return false;
            }

            $orig_dir = $mediaFile->storageDir( "original" );
            eZDebug::writeNotice( "dir=$orig_dir", __METHOD__ );
            $media->setAttribute( "filename", basename( $mediaFile->attribute( "filename" ) ) );
            $media->setAttribute( "original_filename", $mediaFile->attribute( "original_filename" ) );
            $media->setAttribute( "mime_type", $mime );

            $filePath = $mediaFile->attribute( 'filename' );
            $fileHandler = eZClusterFileHandler::instance();
            $fileHandler->fileStore( $filePath, 'media', true, $mime );
        }
        else if ( $media->attribute( 'filename' ) == '' )
        {
            $media->remove();
            return false;
        }

        $media->store();
        $contentObjectAttribute->setContent( $media );
        return true;
    }

    function storeObjectAttribute( $contentObjectAttribute )
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
     HTTP file insertion is supported.
    */
    function isHTTPFileInsertionSupported()
    {
        return true;
    }

    /*!
     Regular file insertion is supported.
    */
    function isRegularFileInsertionSupported()
    {
        return true;
    }

    /*!
     Inserts the file using the eZMedia class.
    */
    function insertHTTPFile( $object, $objectVersion, $objectLanguage,
                             $objectAttribute, $httpFile, $mimeData,
                             &$result )
    {
        $result = array( 'errors' => array(),
                         'require_storage' => false );
        $attributeID = $objectAttribute->attribute( 'id' );

        $media = eZMedia::fetch( $attributeID, $objectVersion );
        if ( $media === null )
            $media = eZMedia::create( $attributeID, $objectVersion );

        $httpFile->setMimeType( $mimeData['name'] );
        if ( !$httpFile->store( "original", false, false ) )
        {
            $result['errors'][] = array( 'description' => ezpI18n::tr( 'kernel/classes/datatypes/ezmedia',
                                                        'Failed to store media file %filename. Please contact the site administrator.', null,
                                                        array( '%filename' => $httpFile->attribute( "original_filename" ) ) ) );
            return false;
        }

        $classAttribute = $objectAttribute->contentClassAttribute();
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

        $filePath = $httpFile->attribute( 'filename' );
        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileStore( $filePath, 'mediafile', true, $mimeData['name'] );


        $media->store();

        $objectAttribute->setContent( $media );
        return true;
    }

    /*!
     Inserts the file using the eZMedia class.
    */
    function insertRegularFile( $object, $objectVersion, $objectLanguage,
                                $objectAttribute, $filePath,
                                &$result )
    {
        $result = array( 'errors' => array(),
                         'require_storage' => false );
        $attributeID = $objectAttribute->attribute( 'id' );

        $media = eZMedia::fetch( $attributeID, $objectVersion );
        if ( $media === null )
            $media = eZMedia::create( $attributeID, $objectVersion );

        $fileName = basename( $filePath );
        $mimeData = eZMimeType::findByFileContents( $filePath );
        $storageDir = eZSys::storageDirectory();
        list( $group, $type ) = explode( '/', $mimeData['name'] );
        $destination = $storageDir . '/original/' . $group;

        if ( !file_exists( $destination ) )
        {
            if ( !eZDir::mkdir( $destination, false, true ) )
            {
                return false;
            }
        }

        // create dest filename in the same manner as eZHTTPFile::store()
        // grab file's suffix
        $fileSuffix = eZFile::suffix( $fileName );
        // prepend dot
        if( $fileSuffix )
            $fileSuffix = '.' . $fileSuffix;
        // grab filename without suffix
        $fileBaseName = basename( $fileName, $fileSuffix );
        // create dest filename
        $destFileName = md5( $fileBaseName . microtime() . mt_rand() ) . $fileSuffix;
        $destination = $destination . '/' . $destFileName;

        copy( $filePath, $destination );

        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileStore( $destination, 'mediafile', true, $mimeData['name'] );

        $classAttribute = $objectAttribute->contentClassAttribute();
        $player = $classAttribute->attribute( "data_text1" );
        $pluginPage = eZMediaType::pluginPage( $player );

        $media->setAttribute( "contentobject_attribute_id", $attributeID );
        $media->setAttribute( "version", $objectVersion );
        $media->setAttribute( "filename", $destFileName );
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
      We support file information
    */
    function hasStoredFileInformation( $object, $objectVersion, $objectLanguage,
                                       $objectAttribute )
    {
        return true;
    }

    /*!
      Extracts file information for the media entry.
    */
    function storedFileInformation( $object, $objectVersion, $objectLanguage,
                                    $objectAttribute )
    {
        $mediaFile = eZMedia::fetch( $objectAttribute->attribute( "id" ),
                                      $objectAttribute->attribute( "version" ) );
        if ( $mediaFile )
        {
            return $mediaFile->storedFileInfo();
        }
        return false;
    }

    function storeClassAttribute( $attribute, $version )
    {
    }

    function storeDefinedClassAttribute( $attribute )
    {
    }

    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $filesizeName = $base . self::MAX_FILESIZE_VARIABLE . $classAttribute->attribute( 'id' );
        $typeName = $base . self::TYPE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $filesizeName ) )
        {
            $filesizeValue = $http->postVariable( $filesizeName );
            $classAttribute->setAttribute( self::MAX_FILESIZE_FIELD, $filesizeValue );
        }
        if ( $http->hasPostVariable( $typeName ) )
        {
            $typeValue = $http->postVariable( $typeName );
            $classAttribute->setAttribute( self::TYPE_FIELD, $typeValue );
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

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $mediaFile = eZMedia::fetch( $contentObjectAttribute->attribute( "id" ),
                                      $contentObjectAttribute->attribute( "version" ) );
        if ( !$mediaFile )
            return false;
        if( $mediaFile->attribute( "filename" ) == "" )
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

    function metaData( $contentObjectAttribute )
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



    function fromString( $objectAttribute, $string )
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

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $maxSize = $classAttribute->attribute( self::MAX_FILESIZE_FIELD );
        $type = $classAttribute->attribute( self::TYPE_FIELD );

        $dom = $attributeParametersNode->ownerDocument;

        $maxSizeNode = $dom->createElement( 'max-size' );
        $maxSizeNode->appendChild( $dom->createTextNode( $maxSize ) );
        $maxSizeNode->setAttribute( 'unit-size', 'mega' );
        $attributeParametersNode->appendChild( $maxSizeNode );

        $typeNode = $dom->createElement( 'type' );
        $typeNode->appendChild( $dom->createTextNode( $type ) );
        $attributeParametersNode->appendChild( $typeNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $sizeNode = $attributeParametersNode->getElementsByTagName( 'max-size' )->item( 0 );
        $maxSize = $sizeNode->textContent;
        $unitSize = $sizeNode->getAttribute( 'unit-size' );
        $type = $attributeParametersNode->getElementsByTagName( 'type' )->item( 0 )->textContent;
        $classAttribute->setAttribute( self::MAX_FILESIZE_FIELD, $maxSize );
        $classAttribute->setAttribute( self::TYPE_FIELD, $type );
    }

    function serializeContentObjectAttribute( $package, $objectAttribute )
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

        $dom = $node->ownerDocument;

        $mediaNode = $dom->createElement( 'media-file' );
        $mediaNode->setAttribute( 'filesize', $mediaFile->attribute( 'filesize' ) );
        $mediaNode->setAttribute( 'filename', $mediaFile->attribute( 'filename' ) );
        $mediaNode->setAttribute( 'original-filename', $mediaFile->attribute( 'original_filename' ) );
        $mediaNode->setAttribute( 'mime-type', $mediaFile->attribute( 'mime_type' ) );
        $mediaNode->setAttribute( 'filekey', $fileKey );

        $mediaNode->setAttribute( 'width', $mediaFile->attribute( 'width' ) );
        $mediaNode->setAttribute( 'height', $mediaFile->attribute( 'height' ) );
        $mediaNode->setAttribute( 'has-controller', $mediaFile->attribute( 'has_controller' ) );
        $mediaNode->setAttribute( 'controls', $mediaFile->attribute( 'controls' ) );
        $mediaNode->setAttribute( 'is-autoplay', $mediaFile->attribute( 'is_autoplay' ) );
        $mediaNode->setAttribute( 'plugins-page', $mediaFile->attribute( 'pluginspage' ) );
        $mediaNode->setAttribute( 'quality', $mediaFile->attribute( 'quality' ) );
        $mediaNode->setAttribute( 'is-loop', $mediaFile->attribute( 'is_loop' ) );
        $node->appendChild( $mediaNode );

        return $node;
    }

    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $mediaNode = $attributeNode->getElementsByTagName( 'media-file' )->item( 0 );
        if ( !$mediaNode )
        {
            // No media type data found.
            return;
        }

        $mediaFile = eZMedia::create( $objectAttribute->attribute( 'id' ), $objectAttribute->attribute( 'version' ) );

        $sourcePath = $package->simpleFilePath( $mediaNode->getAttribute( 'filekey' ) );

        $ini = eZINI::instance();
        $mimeType = $mediaNode->getAttribute( 'mime-type' );
        list( $mimeTypeCategory, $mimeTypeName ) = explode( '/', $mimeType );
        $destinationPath = eZSys::storageDirectory() . '/original/' . $mimeTypeCategory . '/';
        if ( !file_exists( $destinationPath ) )
        {
            if ( !eZDir::mkdir( $destinationPath, false, true ) )
            {
                return false;
            }
        }

        $basename = basename( $mediaNode->getAttribute( 'filename' ) );
        while ( file_exists( $destinationPath . $basename ) )
        {
            $basename = substr( md5( mt_rand() ), 0, 8 ) . '.' . eZFile::suffix( $mediaNode->getAttribute( 'filename' ) );
        }

        eZFileHandler::copy( $sourcePath, $destinationPath . $basename );
        eZDebug::writeNotice( 'Copied: ' . $sourcePath . ' to: ' . $destinationPath . $basename, __METHOD__ );

        $mediaFile->setAttribute( 'contentobject_attribute_id', $objectAttribute->attribute( 'id' ) );
        $mediaFile->setAttribute( 'filename', $basename );
        $mediaFile->setAttribute( 'original_filename', $mediaNode->getAttribute( 'original-filename' ) );
        $mediaFile->setAttribute( 'mime_type', $mediaNode->getAttribute( 'mime-type' ) );

        $mediaFile->setAttribute( 'width', $mediaNode->getAttribute( 'width' ) );
        $mediaFile->setAttribute( 'height', $mediaNode->getAttribute( 'height' ) );
        $mediaFile->setAttribute( 'has_controller', $mediaNode->getAttribute( 'has-controller' ) );
        $mediaFile->setAttribute( 'controls', $mediaNode->getAttribute( 'controls' ) );
        $mediaFile->setAttribute( 'is_autoplay', $mediaNode->getAttribute( 'is-autoplay' ) );
        $mediaFile->setAttribute( 'pluginspage', $mediaNode->getAttribute( 'plugins-page' ) );
        $mediaFile->setAttribute( 'quality', $mediaNode->getAttribute( 'quality' ) );
        $mediaFile->setAttribute( 'is_loop', $mediaNode->getAttribute( 'is-loop' ) );

        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileStore( $destinationPath . $basename, 'mediafile', true );

        $mediaFile->store();
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    /// \privatesection
    /// The file extension blacklist validator
    private $FileExtensionBlackListValidator;
}

eZDataType::register( eZMediaType::DATA_TYPE_STRING, "eZMediaType" );

?>

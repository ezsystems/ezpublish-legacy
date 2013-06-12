<?php
/**
 * File containing the eZImageType class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZImageType ezimagetype.php
  \ingroup eZDatatype
  \brief The class eZImageType handles image accounts and association with content objects

  \note The method initializeObjectAttribute was removed in 3.8, the new
        storage technique removes the need to have it.
*/

class eZImageType extends eZDataType
{
    const FILESIZE_FIELD = 'data_int1';
    const FILESIZE_VARIABLE = '_ezimage_max_filesize_';
    const DATA_TYPE_STRING = "ezimage";

    function eZImageType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "Image", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    /*!
     The object is being moved to trash, do any necessary changes to the attribute.
     Rename file and update db row with new name, so that access to the file using old links no longer works.
    */
    function trashStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        $imageHandler = $contentObjectAttribute->attribute( "content" );
        $originalAlias = $imageHandler->imageAlias( "original" );
        $basenameHashed = md5( $originalAlias["basename"] );
        $trashedFolder = "{$originalAlias["dirpath"]}/trashed";
        $imageHandler->updateAliasPath( $trashedFolder, $basenameHashed );
        if ( $imageHandler->isStorageRequired() )
        {
            $imageHandler->store( $contentObjectAttribute );
            $contentObjectAttribute->store();
        }

        // Now clean all other aliases, not cleanly registered within the attribute content
        // First get all remaining aliases full path to then safely move them to the trashed folder
        $aliasNames = array_keys( $imageHandler->aliasList() );
        $aliasesPath = array();
        foreach ( $aliasNames as $aliasName )
        {
            if ( $aliasName === "original" )
            {
                continue;
            }

            $aliasesPath[] = "{$originalAlias["dirpath"]}/{$originalAlias["basename"]}_{$aliasName}.{$originalAlias["suffix"]}";
        }

        if( empty( $aliasesPath ) )
        {
            return;
        }
        $conds = array(
            "contentobject_attribute_id" => $contentObjectAttribute->attribute( "id" ),
            "filepath"                   => array( $aliasesPath )
        );
        $remainingAliases = eZPersistentObject::fetchObjectList(
            eZImageFile::definition(), null,
            $conds
        );
        unset( $conds, $remainingAliasesPath );

        if ( !empty( $remainingAliases ) )
        {
            foreach ( $remainingAliases as $remainingAlias )
            {
                $filename = basename( $remainingAlias->attribute( "filepath" ) );
                $newFilePath = $trashedFolder . "/" . $basenameHashed . substr( $filename, strrpos( $filename, '_' ) );
                eZClusterFileHandler::instance( $remainingAlias->attribute( "filepath" ) )->move( $newFilePath );

                // $newFilePath might have already been processed in eZImageFile
                // If so, $remainingAlias is a duplicate. We can then remove it safely
                $imageFile = eZImageFile::fetchByFilepath( false, $newFilePath, false );
                if ( empty( $imageFile ) )
                {
                    $remainingAlias->setAttribute( "filepath", $newFilePath );
                    $remainingAlias->store();
                }
                else
                {
                    $remainingAlias->remove();
                }
            }
        }
    }

    public function restoreTrashedObjectAttribute( $contentObjectAttribute )
    {
        $imageHandler = $contentObjectAttribute->attribute( "content" );
        $originalAlias = $imageHandler->imageAlias( "original" );
        $originalPath = str_replace( "/trashed", "", $originalAlias["dirpath"]);
        $originalName = $imageHandler->imageName( $contentObjectAttribute, $contentObjectAttribute->objectVersion() );
        $imageHandler->updateAliasPath( $originalPath, $originalName );

        if ( $imageHandler->isStorageRequired() )
        {
            $imageHandler->store( $contentObjectAttribute );
            $contentObjectAttribute->store();
        }

        // Now clean all other aliases, not cleanly registered within the attribute content
        // First get all remaining aliases full path to then safely remove them
        $aliasNames = array_keys( $imageHandler->aliasList() );
        $aliasesPath = array();
        foreach ( $aliasNames as $aliasName )
        {
            if ( $aliasName === "original" )
            {
                continue;
            }

            $aliasesPath[] = "{$originalAlias["dirpath"]}/{$originalAlias["basename"]}_{$aliasName}.{$originalAlias["suffix"]}";
        }

        if( empty( $aliasesPath ) )
        {
            return;
        }
        $conds = array(
        	"contentobject_attribute_id" => $contentObjectAttribute->attribute( "id" ),
            "filepath"                   => array( $aliasesPath )
        );
        $remainingAliases = eZPersistentObject::fetchObjectList(
            eZImageFile::definition(), null,
            $conds
        );
        unset( $conds, $remainingAliasesPath );

        if ( !empty( $remainingAliases ) )
        {
            foreach ( $remainingAliases as $remainingAlias )
            {
                $filename = basename( $remainingAlias->attribute( "filepath" ) );
                $newFilePath = $originalPath . "/" . $originalName . substr( $filename, strrpos( $filename, '_' ) );
                eZClusterFileHandler::instance( $remainingAlias->attribute( "filepath" ) )->move( $newFilePath );

                // $newFilePath might have already been processed in eZImageFile
                // If so, $remainingAlias is a duplicate. We can then remove it safely
                $imageFile = eZImageFile::fetchByFilepath( false, $newFilePath, false );
                if ( empty( $imageFile ) )
                {
                    $remainingAlias->setAttribute( "filepath", $newFilePath );
                    $remainingAlias->store();
                }
                else
                {
                    $remainingAlias->remove();
                }
            }
        }
    }

    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        if ( $version === null )
        {
            eZImageAliasHandler::removeAllAliases( $contentObjectAttribute );
        }
        else
        {
            $imageHandler = $contentObjectAttribute->attribute( 'content' );
            if ( $imageHandler )
                $imageHandler->removeAliases( $contentObjectAttribute );
        }
    }

    /**
     * Validate the object attribute input in http. If there is validation failure, there failure message will be put into $contentObjectAttribute->ValidationError
     * @param $http: http object
     * @param $base:
     * @param $contentObjectAttribute: content object attribute being validated
     * @return validation result- eZInputValidator::STATE_INVALID or eZInputValidator::STATE_ACCEPTED
     *
     * @see kernel/classes/eZDataType#validateObjectAttributeHTTPInput($http, $base, $objectAttribute)
     */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        $httpFileName = $base . "_data_imagename_" . $contentObjectAttribute->attribute( "id" );
        $maxSize = 1024 * 1024 * $classAttribute->attribute( self::FILESIZE_FIELD );
        $mustUpload = false;

        if( $contentObjectAttribute->validateIsRequired() )
        {
            $tmpImgObj = $contentObjectAttribute->attribute( 'content' );
            $original = $tmpImgObj->attribute( 'original' );
            if ( !$original['is_valid'] )
            {
                $mustUpload = true;
            }
        }

        $canFetchResult = eZHTTPFile::canFetch( $httpFileName, $maxSize );
        if ( isset( $_FILES[$httpFileName] ) and  $_FILES[$httpFileName]["tmp_name"] != "" )
        {
             $imagefile = $_FILES[$httpFileName]['tmp_name'];
             if ( !$_FILES[$httpFileName]["size"] )
             {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                     'The image file must have non-zero size.' ) );
                return eZInputValidator::STATE_INVALID;
             }
             if ( function_exists( 'getimagesize' ) )
             {
                $info = getimagesize( $imagefile );
                if ( !$info )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                         'A valid image file is required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
             }
             else
             {
                 $mimeType = eZMimeType::findByURL( $_FILES[$httpFileName]['name'] );
                 $nameMimeType = $mimeType['name'];
                 $nameMimeTypes = explode("/", $nameMimeType);
                 if ( $nameMimeTypes[0] != 'image' )
                 {
                     $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                          'A valid image file is required.' ) );
                     return eZInputValidator::STATE_INVALID;
                 }
             }
        }
        if ( $mustUpload && $canFetchResult == eZHTTPFile::UPLOADEDFILE_DOES_NOT_EXIST )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                'A valid image file is required.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        if ( $canFetchResult == eZHTTPFile::UPLOADEDFILE_EXCEEDS_PHP_LIMIT )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                'The size of the uploaded image exceeds limit set by upload_max_filesize directive in php.ini. Please contact the site administrator.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        if ( $canFetchResult == eZHTTPFile::UPLOADEDFILE_EXCEEDS_MAX_SIZE )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                'The size of the uploaded file exceeds the limit set for this site: %1 bytes.' ), $maxSize );
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /**
     * Fetch object attribute http input, override the ezDataType method
     * This method is triggered when submiting a http form which includes Image class
     * Image is stored into file system every time there is a file input and validation result is valid.
     * @param $http http object
     * @param $base
     * @param $contentObjectAttribute : the content object attribute being handled
     * @return true if content object is not null, false if content object is null
     */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $result = false;
        $imageAltText = false;
        $hasImageAltText = false;
        if ( $http->hasPostVariable( $base . "_data_imagealttext_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $imageAltText = $http->postVariable( $base . "_data_imagealttext_" . $contentObjectAttribute->attribute( "id" ) );
            $hasImageAltText = true;
        }

        $content = $contentObjectAttribute->attribute( 'content' );
        $httpFileName = $base . "_data_imagename_" . $contentObjectAttribute->attribute( "id" );

        if ( eZHTTPFile::canFetch( $httpFileName ) )
        {
            $httpFile = eZHTTPFile::fetch( $httpFileName );
            if ( $httpFile )
            {
                if ( $content )
                {
                    $content->setHTTPFile( $httpFile );
                    $result = true;
                }
            }

        }

        if ( $content )
        {
            if ( $hasImageAltText )
                $content->setAttribute( 'alternative_text', $imageAltText );
            $result = true;
        }

        return $result;
    }

    function storeObjectAttribute( $contentObjectAttribute )
    {
        $imageHandler = $contentObjectAttribute->attribute( 'content' );
        if ( $imageHandler )
        {
            $httpFile = $imageHandler->httpFile( true );
            if ( $httpFile )
            {
                $imageAltText = $imageHandler->attribute( 'alternative_text' );

                $imageHandler->initializeFromHTTPFile( $httpFile, $imageAltText );
            }
            if ( $imageHandler->isStorageRequired() )
            {
                $imageHandler->store( $contentObjectAttribute );
            }
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
     Inserts the file using the Image Handler eZImageAliasHandler.
    */
    function insertHTTPFile( $object, $objectVersion, $objectLanguage,
                             $objectAttribute, $httpFile, $mimeData,
                             &$result )
    {
        $result = array( 'errors' => array(),
                         'require_storage' => false );

        $handler = $objectAttribute->content();
        if ( !$handler )
        {
            $result['errors'][] = array( 'description' => ezpI18n::tr( 'kernel/classes/datatypes/ezimage',
                                                                  'Failed to fetch Image Handler. Please contact the site administrator.' ) );
            return false;
        }

        $status = $handler->initializeFromHTTPFile( $httpFile );
        $result['require_storage'] = $handler->isStorageRequired();
        return $status;
    }

    /*!
     Inserts the file using the Image Handler eZImageAliasHandler.
    */
    function insertRegularFile( $object, $objectVersion, $objectLanguage,
                                $objectAttribute, $filePath,
                                &$result )
    {
        $result = array( 'errors' => array(),
                         'require_storage' => false );

        $handler = $objectAttribute->content();
        if ( !$handler )
        {
            $result['errors'][] = array( 'description' => ezpI18n::tr( 'kernel/classes/datatypes/ezimage',
                                                                  'Failed to fetch Image Handler. Please contact the site administrator.' ) );
            return false;
        }

        $status = $handler->initializeFromFile( $filePath, false, basename( $filePath ) );
        $result['require_storage'] = $handler->isStorageRequired();
        return $status;
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
      Extracts file information for the image entry.
    */
    function storedFileInformation( $object, $objectVersion, $objectLanguage,
                                    $objectAttribute )
    {
        $content = $objectAttribute->content();
        if ( $content )
        {
            $original = $content->attribute( 'original' );
            $fileName = $original['filename'];
            $filePath = $original['full_path'];
            $mimeType = $original['mime_type'];
            $originalFileName = $original['original_filename'];

            return array( 'filename' => $fileName,
                          'original_filename' => $originalFileName,
                          'filepath' => $filePath,
                          'mime_type' => $mimeType );
        }
        return false;
    }

    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
        $hasContent = $contentObjectAttribute->hasContent();
        if ( $hasContent )
        {
            $imageHandler = $contentObjectAttribute->attribute( 'content' );
            $mainNode = false;
            foreach ( array_keys( $publishedNodes ) as $publishedNodeKey )
            {
                $publishedNode = $publishedNodes[$publishedNodeKey];
                if ( $publishedNode->attribute( 'is_main' ) )
                {
                    $mainNode = $publishedNode;
                    break;
                }
            }
            if ( $mainNode )
            {
                $dirpath = $imageHandler->imagePathByNode( $contentObjectAttribute, $mainNode );
                $oldDirpath = $imageHandler->directoryPath();
                if ( $oldDirpath != $dirpath )
                {
                    $name = $imageHandler->imageNameByNode( $contentObjectAttribute, $mainNode );
                    $imageHandler->updateAliasPath( $dirpath, $name );
                }
            }
            if ( $imageHandler->isStorageRequired() )
            {
                $imageHandler->store( $contentObjectAttribute );
                $contentObjectAttribute->store();
            }
        }
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $filesizeName = $base . self::FILESIZE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $filesizeName ) )
        {
            $filesizeValue = $http->postVariable( $filesizeName );
            $classAttribute->setAttribute( self::FILESIZE_FIELD, $filesizeValue );
            return true;
        }
        return false;
    }

    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        if( $action == "delete_image" )
        {
            $content = $contentObjectAttribute->attribute( 'content' );
            if ( $content )
            {
                $content->removeAliases( $contentObjectAttribute );
            }
        }
    }

    /*!
     Will return one of the following items from the original alias.
     - alternative_text - If it's not empty
     - Default paramater in \a $name if it exists
     - original_filename, this is the default fallback.
    */
    function title( $contentObjectAttribute, $name = 'original_filename' )
    {
        $content = $contentObjectAttribute->content();
        $original = $content->attribute( 'original' );
        $value = $original['alternative_text'];
        if ( trim( $value ) == '' )
        {
            if ( array_key_exists( $name, $original ) )
                $value = $original[$name];
            else
                $value = $original['original_filename'];
        }

        return $value;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $handler = $contentObjectAttribute->content();
        if ( !$handler )
            return false;
        return $handler->attribute( 'is_valid' );
    }

    function objectAttributeContent( $contentObjectAttribute )
    {
        $imageHandler = new eZImageAliasHandler( $contentObjectAttribute );

        return $imageHandler;
    }

    function metaData( $contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();
        $original = $content->attribute( 'original' );
        $value = $original['alternative_text'];
        return $value;
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $maxSize = $classAttribute->attribute( self::FILESIZE_FIELD );
        $dom = $attributeParametersNode->ownerDocument;

        $maxSizeNode = $dom->createElement( 'max-size' );
        $maxSizeNode->appendChild( $dom->createTextNode( $maxSize ) );
        $maxSizeNode->setAttribute( 'unit-size', 'mega' );
        $attributeParametersNode->appendChild( $maxSizeNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $sizeNode = $attributeParametersNode->getElementsByTagName( 'max-size' )->item( 0 );
        $maxSize = $sizeNode->textContent;
        $unitSize = $sizeNode->getAttribute( 'unit-size' );
        $classAttribute->setAttribute( self::FILESIZE_FIELD, $maxSize );
    }


    /*!
     \return a DOM representation of the content object attribute
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $content = $objectAttribute->content();
        $original = $content->attribute( 'original' );

        if ( $original['url'] )
        {
            $imageKey = md5( mt_rand() );

            $package->appendSimpleFile( $imageKey, $original['url'] );
            $node->setAttribute( 'image-file-key', $imageKey );
        }

        $node->setAttribute( 'alternative-text', $original['alternative_text'] );

        return $node;
    }

    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        // Remove all existing image data for the case this is a translated attribute,
        // so initial language's image alias will not be removed in 'initializeFromFile'
        $objectAttribute->setAttribute( 'data_text', '' );

        $alternativeText = $attributeNode->getAttribute( 'alternative-text' );
        // Backwards compatibility with older node name
        if ( $alternativeText === false )
            $alternativeText = $attributeNode->getAttribute( 'alternativ-text' );
        $content = $objectAttribute->attribute( 'content' );
        $imageFileKey = $attributeNode->getAttribute( 'image-file-key' );
        if ( $imageFileKey )
        {
            $content->initializeFromFile( $package->simpleFilePath( $imageFileKey ), $alternativeText );
        }
        else
        {
            $content->setAttribute( 'alternative_text', $alternativeText );
        }
        $content->store( $objectAttribute );
    }

    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $objectAttribute )
    {
        $content = $objectAttribute->content();
        $original = $content->attribute( 'original' );
        $alternativeText = $content->attribute( 'alternative_text' );
        return $original['url'] . '|' . $alternativeText;
    }

    function fromString( $objectAttribute, $string )
    {
        $delimiterPos = strpos( $string, '|' );

        $content = $objectAttribute->attribute( 'content' );
        if ( $delimiterPos === false )
        {
               $content->initializeFromFile( $string, '' );
        }
        else
        {
            $content->initializeFromFile( substr( $string, 0, $delimiterPos ), '' );
            $content->setAttribute( 'alternative_text', substr( $string, $delimiterPos + 1 ) );
        }
        $content->store( $objectAttribute );
        return true;
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }
}

?>

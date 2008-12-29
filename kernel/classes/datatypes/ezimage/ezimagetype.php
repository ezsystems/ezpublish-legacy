<?php
//
// Definition of eZImageType class
//
// Created on: <30-Apr-2002 13:06:21 bf>
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
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Image", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     \reimp
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    /*!
     \reimp
    */
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

    /*!
     \reimp
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
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'The image file must have non-zero size.' ) );
                return eZInputValidator::STATE_INVALID;
             }
             if ( function_exists( 'getimagesize' ) )
             {
                $info = getimagesize( $imagefile );
                if ( !$info )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
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
                     $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                          'A valid image file is required.' ) );
                     return eZInputValidator::STATE_INVALID;
                 }
             }
        }
        if ( $mustUpload && $canFetchResult == eZHTTPFile::UPLOADEDFILE_DOES_NOT_EXIST )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                'A valid image file is required.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        if ( $canFetchResult == eZHTTPFile::UPLOADEDFILE_EXCEEDS_PHP_LIMIT )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                'The size of the uploaded image exceeds limit set by upload_max_filesize directive in php.ini. Please contact the site administrator.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        if ( $canFetchResult == eZHTTPFile::UPLOADEDFILE_EXCEEDS_MAX_SIZE )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                'The size of the uploaded file exceeds the limit set for this site: %1 bytes.' ), $maxSize );
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     \reimp
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

    /*!
     \reimp
    */
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
            $result['errors'][] = array( 'description' => ezi18n( 'kernel/classes/datatypes/ezimage',
                                                                  'Failed to fetch Image Handler. Please contact the site administrator.' ) );
            return false;
        }

        $status = $handler->initializeFromHTTPFile( $httpFile );
        $result['require_storage'] = $handler->isStorageRequired();
        return $status;
    }

    /*!
     \reimp
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
            $result['errors'][] = array( 'description' => ezi18n( 'kernel/classes/datatypes/ezimage',
                                                                  'Failed to fetch Image Handler. Please contact the site administrator.' ) );
            return false;
        }

        $status = $handler->initializeFromFile( $filePath, false, basename( $filePath ) );
        $result['require_storage'] = $handler->isStorageRequired();
        return $status;
    }

    /*!
      \reimp
      We support file information
    */
    function hasStoredFileInformation( $object, $objectVersion, $objectLanguage,
                                       $objectAttribute )
    {
        return true;
    }

    /*!
      \reimp
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

    /*!
     \reimp
    */
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
                if ( $publishedNode->attribute( 'main_node_id' ) )
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

    /*!
     \reimp
    */
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

    /*!
     \reimp
    */
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
     \reimp
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

    /*!
     \reimp
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $imageHandler = new eZImageAliasHandler( $contentObjectAttribute );

        return $imageHandler;
    }

    /*!
     \reimp
    */
    function metaData( $contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();
        $original = $content->attribute( 'original' );
        $value = $original['alternative_text'];
        return $value;
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $maxSize = $classAttribute->attribute( self::FILESIZE_FIELD );
        $maxSizeNode = $attributeParametersNode->ownerDocument->createElement( 'max-size', $maxSize );
        $maxSizeNode->setAttribute( 'unit-size', 'mega' );
        $attributeParametersNode->appendChild( $maxSizeNode );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $sizeNode = $attributeParametersNode->getElementsByTagName( 'max-size' )->item( 0 );
        $maxSize = $sizeNode->textContent;
        $unitSize = $sizeNode->getAttribute( 'unit-size' );
        $classAttribute->setAttribute( self::FILESIZE_FIELD, $maxSize );
    }


    /*!
     \reimp
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
        return $original['url'];
    }

    function fromString( $objectAttribute, $string )
    {
        $content = $objectAttribute->attribute( 'content' );
        $content->initializeFromFile( $string, "" );
        $content->store( $objectAttribute );
        return true;
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }
}

eZDataType::register( eZImageType::DATA_TYPE_STRING, "eZImageType" );

?>

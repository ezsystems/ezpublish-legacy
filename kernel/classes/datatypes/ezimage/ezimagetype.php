<?php
//
// Definition of eZImageType class
//
// Created on: <30-Apr-2002 13:06:21 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZImageType ezimagetype.php
  \ingroup eZKernel
  \brief The class eZImageType handles image accounts and association with content objects

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezutils/classes/ezdir.php" );
include_once( "lib/ezutils/classes/ezhttpfile.php" );
include_once( "lib/ezutils/classes/ezdir.php" );

define( 'EZ_DATATYPESTRING_MAX_IMAGE_FILESIZE_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_MAX_IMAGE_FILESIZE_VARIABLE', '_ezimage_max_filesize_' );
define( "EZ_DATATYPESTRING_IMAGE", "ezimage" );

class eZImageType extends eZDataType
{
    function eZImageType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_IMAGE, ezi18n( 'kernel/classes/datatypes', "Image", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    function repairContentObjectAttribute( &$contentObjectAttribute )
    {
        $image =& eZImage::fetch( $contentObjectAttribute->attribute( 'id' ),
                                  $contentObjectAttribute->attribute( 'version' ) );
        if ( !is_object( $image ) )
        {
            $list =& eZContentObjectAttribute::fetchSameClassAttributeIDList( $contentObjectAttribute->attribute( 'contentclassattribute_id' ),
                                                                              true,
                                                                              $contentObjectAttribute->attribute( 'version' ) );
            $language = eZContentObject::defaultLanguage();
            $attribute = false;
            foreach ( array_keys( $list ) as $listKey )
            {
                $listItem =& $list[$listKey];
                if ( $listItem->attribute( 'language_code' ) == $language )
                {
                    $attribute =& $listItem;
                    break;
                }
            }
            if ( $attribute === false )
            {
                $attribute =& $list[0];
            }
            if ( $attribute )
            {
                $originalImage =& eZImage::fetch( $attribute->attribute( 'id' ),
                                                  $attribute->attribute( 'version' ) );
                if ( is_object( $originalImage ) )
                {
                    $originalImage->setAttribute( 'contentobject_attribute_id', $contentObjectAttribute->attribute( 'id' ) );
                    $originalImage->store();
                    return true;
                }
            }
        }
        return false;
    }

    /*!
     \reimp
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        $contentObjectAttributeID = $originalContentObjectAttribute->attribute( "id" );
        $version = $contentObjectAttribute->attribute( "version" );
        if ( $currentVersion != false )
        {
            $imageHandler =& $contentObjectAttribute->attribute( 'content' );
            if ( $imageHandler )
            {
                $imageHandler->setOriginalAttributeDataFromAttribute( $originalContentObjectAttribute );
                $imageHandler->store();
            }
        }
    }

    /*!
     \reimp
    */
    function deleteStoredObjectAttribute( &$contentObjectAttribute, $version = null )
    {
        if ( $version === null )
        {
            include_once( "kernel/classes/datatypes/ezimage/ezimagealiashandler.php" );
            eZImageAliasHandler::removeAllAliases( $contentObjectAttribute );
        }
        else
        {
            $imageHandler =& $contentObjectAttribute->attribute( 'content' );
            if ( $imageHandler )
                $imageHandler->removeAliases();
        }
    }

    /*!
     \reimp
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $httpFileName = $base . "_data_imagename_" . $contentObjectAttribute->attribute( "id" );
        $maxSize = 1024 * 1024 * $classAttribute->attribute( EZ_DATATYPESTRING_MAX_IMAGE_FILESIZE_FIELD );
        $mustUpload = false;

        if( $classAttribute->attribute( "is_required" ) == true )
        {
            $tmpImgObj =& $contentObjectAttribute->attribute( 'content' );
            $original =& $tmpImgObj->attribute( 'original' );
            if ( !$original['is_valid'] )
            {
                $mustUpload = true;
            }
        }

        $canFetchResult = eZHTTPFile::canFetch( $httpFileName, $maxSize );
        if ( $mustUpload && $canFetchResult == EZ_UPLOADEDFILE_DOES_NOT_EXIST )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                'A valid file is required.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        if ( $canFetchResult == EZ_UPLOADEDFILE_EXCEEDS_PHP_LIMIT ) 
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                'Size of uploaded file exceeds limit set by upload_max_filesize directive in php.ini.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        if ( $canFetchResult == EZ_UPLOADEDFILE_EXCEEDS_MAX_SIZE )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                'Size of uploaded file exceeds %1 bytes.' ), $maxSize );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     \reimp
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $result = false;
        $imageAltText = false;
        $hasImageAltText = false;
        if ( $http->hasPostVariable( $base . "_data_imagealttext_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $imageAltText =& eZHTTPTool::postVariable( $base . "_data_imagealttext_" . $contentObjectAttribute->attribute( "id" ) );
            $hasImageAltText = true;
        }

        $content =& $contentObjectAttribute->attribute( 'content' );
        $httpFileName = $base . "_data_imagename_" . $contentObjectAttribute->attribute( "id" );

        if ( eZHTTPFile::canFetch( $httpFileName ) )
        {
            $httpFile =& eZHTTPFile::fetch( $httpFileName );
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
    function storeObjectAttribute( &$contentObjectAttribute )
    {
        $imageHandler =& $contentObjectAttribute->attribute( 'content' );
        if ( $imageHandler )
        {
            $httpFile =& $imageHandler->httpFile( true );
            if ( $httpFile )
            {
                $imageAltText = $imageHandler->attribute( 'alternative_text' );

                $imageHandler->initializeFromHTTPFile( $httpFile, $imageAltText );
            }
            if ( $imageHandler->isStorageRequired() )
            {
                $imageHandler->store();
            }
        }
    }

    /*!
     \reimp
    */
    function onPublish( &$contentObjectAttribute, &$contentObject, &$publishedNodes )
    {
        $imageHandler =& $contentObjectAttribute->attribute( 'content' );
        if ( $imageHandler )
        {
            $mainNode = false;
            foreach ( array_keys( $publishedNodes ) as $publishedNodeKey )
            {
                $publishedNode =& $publishedNodes[$publishedNodeKey];
                if ( $publishedNode->attribute( 'main_node_id' ) )
                {
                    $mainNode =& $publishedNode;
                    break;
                }
            }
            if ( $mainNode )
            {
                $dirpath = $imageHandler->imagePathByNode( $contentObjectAttribute, $mainNode );
                $oldDirpath =& $imageHandler->directoryPath();
                if ( $oldDirpath != $dirpath )
                {
                    $name = $imageHandler->imageNameByNode( $contentObjectAttribute, $mainNode );
                    $imageHandler->updateAliasPath( $dirpath, $name );
                }
            }
            if ( $imageHandler->isStorageRequired() )
            {
                $imageHandler->store();
                $contentObjectAttribute->store();
            }
        }
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $filesizeName = $base . EZ_DATATYPESTRING_MAX_IMAGE_FILESIZE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $filesizeName ) )
        {
            $filesizeValue = $http->postVariable( $filesizeName );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_IMAGE_FILESIZE_FIELD, $filesizeValue );
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute )
    {
        if( $action == "delete_image" )
        {
            $content =& $contentObjectAttribute->attribute( 'content' );
            if ( $content )
            {
                $content->removeAliases();
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
    function title( &$contentObjectAttribute, $name = 'original_filename' )
    {
        $content =& $this->content();
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

    /*!
     \reimp
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        include_once( "kernel/classes/datatypes/ezimage/ezimagealiashandler.php" );
        $imageHandler = new eZImageAliasHandler( $contentObjectAttribute );

        return $imageHandler;
    }

    /*!
     \reimp
    */
    function metaData( $contentObjectAttribute )
    {
        $content =& $contentObjectAttribute->content();
        $original = $content->attribute( 'original' );
        $value = $original['alternative_text'];
        return $value;
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $maxSize = $classAttribute->attribute( EZ_DATATYPESTRING_MAX_IMAGE_FILESIZE_FIELD );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'max-size', $maxSize,
                                                                                     array( 'unit-size' => 'mega' ) ) );
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $maxSize = $attributeParametersNode->elementTextContentByName( 'max-size' );
        $sizeNode = $attributeParametersNode->elementByName( 'max-size' );
        $unitSize = $sizeNode->attributeValue( 'unit-size' );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_IMAGE_FILESIZE_FIELD, $maxSize );
    }
}

eZDataType::register( EZ_DATATYPESTRING_IMAGE, "ezimagetype" );

?>

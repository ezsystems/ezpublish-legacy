<?php
//
// Definition of eZImageType class
//
// Created on: <30-Apr-2002 13:06:21 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
        if( $classAttribute->attribute( "is_required" ) == true )
        {
            $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );
            $httpFileName = $base . "_data_imagename_" . $contentObjectAttribute->attribute( "id" );
            if ( !eZHTTPFile::canFetch( $httpFileName ) )
            {
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
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
        if ( $http->hasPostVariable( $base . "_data_imagealttext_" . $contentObjectAttribute->attribute( "id" ) ) )
            $imageAltText =& eZHTTPTool::postVariable( $base . "_data_imagealttext_" . $contentObjectAttribute->attribute( "id" ) );

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
                $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
                $version = $contentObjectAttribute->attribute( "version" );

                $imageHandler->increaseImageSerialNumber();

                $mimeData = eZMimeType::findByFileContents( $httpFile->attribute( 'filename' ) );
                if ( !$mimeData['is_valid'] )
                {
                    $mimeData = eZMimeType::findByName( $httpFile->attribute( 'mime_type' ) );
                    if ( !$mimeData['is_valid'] )
                    {
                        $mimeData = eZMimeType::findByURL( $httpFile->attribute( 'original_filename' ) );
                    }
                }
                $contentVersion =& eZContentObjectVersion::fetchVersion( $contentObjectAttribute->attribute( 'version' ),
                                                                         $contentObjectAttribute->attribute( 'contentobject_id' ) );
                $objectName = $imageHandler->imageName( $contentObjectAttribute, $contentVersion );
                $objectPathString = $imageHandler->imagePath( $contentObjectAttribute, $contentVersion, true );

                eZMimeType::changeBaseName( $mimeData, $objectName );
                eZMimeType::changeDirectoryPath( $mimeData, $objectPathString );

                $imageAltText = $imageHandler->attribute( 'alternative_text' );
                $imageHandler->removeAliases();

                $httpFile->store( false, false, $mimeData );

                $imageHandler->initialize( $mimeData, $httpFile, $imageAltText );
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
    */
    function title( &$contentObjectAttribute, $name = "filename" )
    {
        $content =& $this->content();
        $original = $content->attribute( 'original' );
        $value = $original['alternative_text'];

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
    function metaData()
    {
        $content =& $this->content();
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

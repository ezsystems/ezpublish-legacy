<?php
//
// Definition of eZBinaryFileHandler class
//
// Created on: <30-Apr-2002 16:47:08 bf>
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

/*!
 \group eZBinaryHandlers Binary file handlers
*/

/*!
  \class eZBinaryFileHandler ezbinaryfilehandler.php
  \ingroup eZKernel
  \brief Interface for all binary file handlers

*/

define( "EZ_BINARY_FILE_HANDLE_UPLOAD", 0x1 );
define( "EZ_BINARY_FILE_HANDLE_DOWNLOAD", 0x2 );

define( "EZ_BINARY_FILE_HANDLE_ALL", EZ_BINARY_FILE_HANDLE_UPLOAD |
                                     EZ_BINARY_FILE_HANDLE_DOWNLOAD );

define( "EZ_BINARY_FILE_TYPE_FILE", 'file' );
define( "EZ_BINARY_FILE_TYPE_MEDIA", 'media' );

define( "EZ_BINARY_FILE_RESULT_OK", 1 );
define( "EZ_BINARY_FILE_RESULT_UNAVAILABLE", 2 );

class eZBinaryFileHandler
{
    function eZBinaryFileHandler( $identifier, $name, $handleType )
    {
        $this->Info = array();
        $this->Info['identifier'] = $identifier;
        $this->Info['name'] = $name;
        $this->Info['handle-type'] = $handleType;
    }

    function attributes()
    {
        return array_keys( $this->Info );
    }

    function hasAttribute( $attribute )
    {
        return isset( $this->Info[$attribute] );
    }

    function &attribute( $attribute )
    {
        if ( isset( $this->Info[$attribute] ) )
            return $this->Info[$attribute];
        return null;
    }

    /*!
     \return the suffix for the template name which will be used for attribute viewing.
     \note Default returns false which means no special template.
    */
    function &viewTemplate( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     \return the suffix for the template name which will be used for attribute viewing.
     \note Default returns false which means no special template.
    */
    function &editTemplate( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     \return the suffix for the template name which will be used for attribute viewing.
     \note Default returns false which means no special template.
    */
    function &informationTemplate( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     Figures out the filename from the binary object \a $binary.
     Currently supports eZBinaryFile, eZMedia and eZImageAliasHandler.
     \return \c false if no file was found.
     \param $returnMimeData If this is set to \c true then it will return a mime structure, otherwise it returns the filename.
     \deprecated
    */
    function storedFilename( &$binary, $returnMimeData = false )
    {

        $origDir = eZSys::storageDirectory() . '/original';

        $class = get_class( $binary );
        $fileName = false;
        $originalFilename = false;
        if ( in_array( $class, array( 'ezbinaryfile', 'ezmedia' ) ) )
        {
            $fileName = $origDir . "/" . $binary->attribute( 'mime_type_category' ) . '/'.  $binary->attribute( "filename" );
            $originalFilename = $binary->attribute( 'original_filename' );
        }
        else if ( $class == 'ezimagealiashandler' )
        {
            $alias = $binary->attribute( 'original' );
            if ( $alias )
                $fileName = $alias['url'];
            $originalFilename = $binary->attribute( 'original_filename' );
        }
        if ( $fileName )
        {
            $mimeData = eZMimeType::findByFileContents( $fileName );
            $mimeData['original_filename'] = $originalFilename;

            if ( !isSet( $mimeData['name'] ) )
                $mimeData['name'] = 'application/octet-stream';

            if ( $returnMimeData )
                return $mimeData;
            else
                return $mimeData['url'];
        }
        return false;
    }

    function handleUpload()
    {
        return false;
    }

    /*!
     \return the file object which corresponds to \a $contentObject and \a $contentObjectAttribute.
    */
    function downloadFileObject( &$contentObject, &$contentObjectAttribute )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObject->attribute( 'current_version' );
        $fileObject =& eZBinaryFile::fetch( $contentObjectAttributeID, $version );
        if ( $fileObject )
            return $fileObject;
        $fileObject =& eZMedia::fetch( $contentObjectAttributeID, $version );
        return $fileObject;
    }

    /*!
     \return the file object type which corresponds to \a $contentObject and \a $contentObjectAttribute.
     \deprecated
    */
    function downloadType( &$contentObject, &$contentObjectAttribute )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObject->attribute( 'current_version' );
        $fileObject =& eZBinaryFile::fetch( $contentObjectAttributeID, $version );
        if ( $fileObject )
            return EZ_BINARY_FILE_TYPE_FILE;
        $fileObject =& eZMedia::fetch( $contentObjectAttributeID, $version );
        if ( $fileObject )
            return EZ_BINARY_FILE_TYPE_MEDIA;
        return false;
    }

    /*!
     \return the download url for the file object which corresponds to \a $contentObject and \a $contentObjectAttribute.
     \deprecated
    */
    function downloadURL( &$contentObject, &$contentObjectAttribute )
    {
        $contentObjectID = $contentObject->attribute( 'id' );
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $downloadType = eZBinaryFileHandler::downloadType( $contentObject, $contentObjectAttribute );
        $downloadObject = eZBinaryFileHandler::downloadFileObject( $contentObject, $contentObjectAttribute );
        $name = '';
        switch ( $downloadType )
        {
            case EZ_BINARY_FILE_TYPE_FILE:
            {
                $name = $downloadObject->attribute( 'original_filename' );
            } break;
            case EZ_BINARY_FILE_TYPE_MEDIA:
            {
                $name = $downloadObject->attribute( 'original_filename' );
            } break;
            default:
            {
                eZDebug::writeWarning( "Unknown binary file type '$downloadType'", 'eZBinaryFileHandler::downloadURL' );
            } break;
        }
        $url = "/content/download/$contentObjectID/$contentObjectAttributeID/$downloadType/$name";
        return $url;
    }

    function handleDownload( &$contentObject, &$contentObjectAttribute, $type )
    {
        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        include_once( 'kernel/classes/datatypes/ezimage/ezimagealiashandler.php' );
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObject->attribute( 'current_version' );



        if ( !$contentObjectAttribute->hasStoredFileInformation( $contentObject, $version,
                                                                 $contentObjectAttribute->attribute( 'language_code' ) ) )
            return EZ_BINARY_FILE_RESULT_UNAVAILABLE;

        $fileInfo = $contentObjectAttribute->storedFileInformation( $contentObject, $version,
                                                                    $contentObjectAttribute->attribute( 'language_code' ) );
        if ( !$fileInfo )
            return EZ_BINARY_FILE_RESULT_UNAVAILABLE;
        if ( !$fileInfo['mime_type'] )
            return EZ_BINARY_FILE_RESULT_UNAVAILABLE;

        $contentObjectAttribute->handleDownload( $contentObject, $version,
                                                 $contentObjectAttribute->attribute( 'language_code' ) );

        return $this->handleFileDownload( $contentObject, $contentObjectAttribute, $type, $fileInfo );
    }

    function handleFileDownload( &$contentObject, &$contentObjectAttribute, $type, $mimeData )
    {
        return false;
    }

    function repositories()
    {
        return array( 'kernel/classes/binaryhandlers' );
    }

    function &instance( $identifier = false )
    {
        if ( $identifier === false )
        {
            $fileINI =& eZINI::instance( 'file.ini' );
            $identifier = $fileINI->variable( 'BinaryFileSettings', 'Handler' );
        }
        $instance =& $GLOBALS['eZBinaryFileHandlerInstance-' . $identifier];
        if ( !isset( $instance ) )
        {
            $handlerDirectory = $identifier;
            $handlerFilename = $identifier . "handler.php";
            $repositories = eZBinaryFileHandler::repositories();
            foreach ( $repositories as $repository )
            {
                $file = eZDir::path( array( $repository, $handlerDirectory, $handlerFilename ) );
                if ( file_exists( $file ) )
                {
                    include_once( $file );
                    $classname = $identifier . "handler";
                    $instance = new $classname();
                    break;
                }
                else
                    eZDebug::writeError( "Could not find binary file handler '$identifier'", 'eZBinaryFileHandler::instance' );
            }
        }
        return $instance;
    }

    /// \privatesection
    var $Info;
}

?>

<?php
//
// Definition of eZBinaryFileType class
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
  \class eZBinaryFileType ezbinaryfiletype.php
  \ingroup eZKernel
  \brief The class eZBinaryFileType handles image accounts and association with content objects

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php" );
include_once( "kernel/classes/ezbinaryfilehandler.php" );
include_once( "lib/ezfile/classes/ezfile.php" );
include_once( "lib/ezutils/classes/ezsys.php" );
include_once( "lib/ezutils/classes/ezmimetype.php" );
include_once( "lib/ezutils/classes/ezhttpfile.php" );

define( 'EZ_DATATYPESTRING_MAX_BINARY_FILESIZE_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_MAX_BINARY_FILESIZE_VARIABLE', '_ezbinaryfile_max_filesize_' );
define( "EZ_DATATYPESTRING_BINARYFILE", "ezbinaryfile" );

class eZBinaryFileType extends eZDataType
{
    function eZBinaryFileType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_BINARYFILE, ezi18n( 'kernel/classes/datatypes', "BinaryFile", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    function hasAttribute( $name )
    {
        return eZDataType::hasAttribute( $name );
    }

    function &attribute( $name )
    {
        return eZDataType::attribute( $name );
    }

    /*!
     \return the binary file handler.
    */
    function &fileHandler()
    {
        return eZBinaryFileHandler::instance();
    }

    /*!
     \reimp
     \return the template name which the handler decides upon.
    */
    function &viewTemplate( &$contentobjectAttribute )
    {
        $handler =& $this->fileHandler();
        $handlerTemplate = $handler->viewTemplate( $contentobjectAttribute );
        $template = $this->DataTypeString;
        if ( $handlerTemplate !== false )
            $template .= '_' . $handlerTemplate;
        return $template;
    }

    /*!
     \return the template name to use for editing the attribute.
     \note Default is to return the datatype string which is OK
           for most datatypes, if you want dynamic templates
           reimplement this function and return a template name.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, informationTemplate
    */
    function &editTemplate( &$contentobjectAttribute )
    {
        $handler =& $this->fileHandler();
        $handlerTemplate = $handler->editTemplate( $contentobjectAttribute );
        $template = $this->DataTypeString;
        if ( $handlerTemplate !== false )
            $template .= '_' . $handlerTemplate;
        return $template;
    }

    /*!
     \return the template name to use for information collection for the attribute.
     \note Default is to return the datatype string which is OK
           for most datatypes, if you want dynamic templates
           reimplement this function and return a template name.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, editTemplate
    */
    function &informationTemplate( &$contentobjectAttribute )
    {
        $handler =& $this->fileHandler();
        $handlerTemplate = $handler->informationTemplate( $contentobjectAttribute );
        $template = $this->DataTypeString;
        if ( $handlerTemplate !== false )
            $template .= '_' . $handlerTemplate;
        return $template;
    }

    /*!
     Sets value according to current version
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $contentObjectAttributeID = $originalContentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );
            $oldfile =& eZBinaryFile::fetch( $contentObjectAttributeID, $currentVersion );
            if ( $oldfile != null )
            {
                $oldfile->setAttribute( 'contentobject_attribute_id', $contentObjectAttribute->attribute( 'id' ) );
                $oldfile->setAttribute( "version",  $version );
                $oldfile->store();
            }
        }
    }

    /*!
     Delete stored attribute
    */
    function deleteStoredObjectAttribute( &$contentObjectAttribute, $version = null )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
//        $binaryFiles =& eZBinaryFile::fetch( $contentObjectAttributeID, $version );
        $binaryFiles =& eZBinaryFile::fetch( $contentObjectAttributeID );
        $sys =& eZSys::instance();
        $storage_dir = $sys->storageDirectory();

        if ( $version == null )
        {
            foreach ( array_keys( $binaryFiles ) as $key )
            {
                $binaryFile =& $binaryFiles[$key];
                $mimeType =  $binaryFile->attribute( "mime_type" );
                list( $prefix, $suffix ) = split ('[/]', $mimeType );
                $orig_dir = $storage_dir . '/original/' . $prefix;
//              $orig_dir = "var/storage/original/" . $prefix;
                $fileName = $binaryFile->attribute( "filename" );
                if ( file_exists( $orig_dir . "/" .$fileName ) )
                    unlink( $orig_dir . "/" . $fileName );
            }
        }
        else
        {
            $count = 0;
            $currentBinaryFile =& eZBinaryFile::fetch( $contentObjectAttributeID, $version );
            if ( $currentBinaryFile != null )
            {
                $mimeType =  $currentBinaryFile->attribute( "mime_type" );
                $currentFileName = $currentBinaryFile->attribute( "filename" );
                list( $prefix, $suffix ) = split ('[/]', $mimeType );
//              $orig_dir = "var/storage/original/" . $prefix;
                $orig_dir = $storage_dir . "/original/" . $prefix;

                foreach ( array_keys ( $binaryFiles ) as $key )
                {
                    $binaryFile =& $binaryFiles[$key];
                    $fileName = $binaryFile->attribute( "filename" );
                    if ( $currentFileName == $fileName )
                        $count += 1;
                }
                if ( $count == 1 )
                {
                    if ( file_exists( $orig_dir . "/" . $currentFileName ) )
                        unlink( $orig_dir . "/" .  $currentFileName );
                }
            }
        }
        eZBinaryFile::remove( $contentObjectAttributeID, $version );
    }

    /*!
     Checks if file uploads are enabled, if not it gives a warning.
    */
    function checkFileUploads()
    {
        $isFileUploadsEnabled = ini_get( 'file_uploads' );
        if ( !$isFileUploadsEnabled )
        {
            $isFileWarningAdded =& $GLOBALS['eZBinaryFileTypeWarningAdded'];
            if ( !isset( $isFileWarningAdded ) or
                 !$isFileWarningAdded )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'kernel',
                                                              'number' => EZ_ERROR_KERNEL_NOT_AVAILABLE ),
                                            'text' => ezi18n( 'kernel/classes/datatypes',
                                                              'File uploading is not enabled, no file handling can be performed.' ) ) );
                $isFileWarningAdded = true;
            }
        }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        eZBinaryFileType::checkFileUploads();
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        if ( $classAttribute->attribute( "is_required" ) == true )
        {
            $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );
            $binary =& eZBinaryFile::fetch( $contentObjectAttributeID, $version );
            if ( $binary === null )
            {
                $file =& eZHTTPFile::fetch( $base . "_data_binaryfilename_" . $contentObjectAttribute->attribute( "id" ) );
                if ( $file === null )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'A valid file is required.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        eZBinaryFileType::checkFileUploads();
        if ( !eZHTTPFile::canFetch( $base . "_data_binaryfilename_" . $contentObjectAttribute->attribute( "id" ) ) )
            return false;

        $binaryFile =& eZHTTPFile::fetch( $base . "_data_binaryfilename_" . $contentObjectAttribute->attribute( "id" ) );

        $contentObjectAttribute->setContent( $binaryFile );

        //$binaryFile =& $contentObjectAttribute->content();

        if ( get_class( $binaryFile ) == "ezhttpfile" )
        {
            $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );

            /*
            $mimeObj = new  eZMimeType();
            $mimeData = $mimeObj->findByURL( $binaryFile->attribute( "original_filename" ), true );
            $mime = $mimeData['name'];
            */

            $mimeData =& eZMimeType::findByFileContents( $binaryFile->attribute( "original_filename" ) );
            $mime = $mimeData['name'];

            if ( $mime == '' )
            {
                $mime = $binaryFile->attribute( "mime_type" );
            }
            $extension = preg_replace('/.*\.(.+?)$/', '\\1', $binaryFile->attribute( "original_filename" ) );
            if ( !$binaryFile->store( "original", $extension ) )
            {
                eZDebug::writeError( "Failed to store http-file: " . $binaryFile->attribute( "original_filename" ),
                                     "eZBinaryFileType" );
                return false;
            }

            $binary =& eZBinaryFile::fetch( $contentObjectAttributeID, $version );
            if ( $binary === null )
                $binary =& eZBinaryFile::create( $contentObjectAttributeID, $version );

            $orig_dir = $binaryFile->storageDir( "original" );

            $binary->setAttribute( "contentobject_attribute_id", $contentObjectAttributeID );
            $binary->setAttribute( "version", $version );
            $binary->setAttribute( "filename", basename( $binaryFile->attribute( "filename" ) ) );
            $binary->setAttribute( "original_filename", $binaryFile->attribute( "original_filename" ) );
            $binary->setAttribute( "mime_type", $mime );

            $binary->store();


            $contentObjectAttribute->setContent( $binary );
        }
        return true;
    }

    /*!
     Does nothing, since the file has been stored. See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( &$contentObjectAttribute )
    {
    }

    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute )
    {
        eZBinaryFileType::checkFileUploads();
        if( $action == "delete_binary" )
        {
            $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );
            $this->deleteStoredObjectAttribute( $contentObjectAttribute, $version );
        }
    }

    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $filesizeName = $base . EZ_DATATYPESTRING_MAX_BINARY_FILESIZE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $filesizeName ) )
        {
            $filesizeValue = $http->postVariable( $filesizeName );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_BINARY_FILESIZE_FIELD, $filesizeValue );
        }
    }
    /*!
     Returns the object title.
    */
    function title( &$contentObjectAttribute,  $name = "original_filename" )
    {
        $binaryFile =& eZBinaryFile::fetch( $contentObjectAttribute->attribute( "id" ),
                                            $contentObjectAttribute->attribute( "version" ) );
        $value = $binaryFile->attribute( $name );

        return $value;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $binaryFile =& eZBinaryFile::fetch( $contentObjectAttribute->attribute( "id" ),
                                            $contentObjectAttribute->attribute( "version" ) );
        if ( !$binaryFile )
            return false;
        return true;
    }

    function &objectAttributeContent( $contentObjectAttribute )
    {
        $binaryFile =& eZBinaryFile::fetch( $contentObjectAttribute->attribute( "id" ),
                                            $contentObjectAttribute->attribute( "version" ) );
        if ( !$binaryFile )
            return false;
        return $binaryFile;
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    function &metaData( &$contentObjectAttribute )
    {
        $binaryFile =& $contentObjectAttribute->content();

        $metaData = "";
        if ( get_class( $binaryFile ) == "ezbinaryfile" )
        {
            $metaData = $binaryFile->metaData();
        }
        return $metaData;
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $maxSize = $classAttribute->attribute( EZ_DATATYPESTRING_MAX_BINARY_FILESIZE_FIELD );
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
        $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_BINARY_FILESIZE_FIELD, $maxSize );
    }

    /*!
     \param package
     \param content attribute

     \return a DOM representation of the content object attribute
    */
    function &serializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        $node = new eZDOMNode();

        $node->setPrefix( 'ezobject' );
        $node->setName( 'attribute' );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'id', $objectAttribute->attribute( 'id' ), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'identifier', $objectAttribute->contentClassAttributeIdentifier(), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', $this->isA() ) );

        $binaryFile =& $objectAttribute->attribute( 'content' );
        $fileKey = md5( mt_rand() );
        $package->appendSimpleFile( $fileKey, $binaryFile->attribute( 'filepath' ) );

        $fileNode =& eZDOMDocument::createElementNode( 'binary-file' );
        $fileNode->appendAttribute( eZDOMDocument::createAttributeNode( 'filesize', $binaryFile->attribute( 'filesize' ) ) );
        $fileNode->appendAttribute( eZDOMDocument::createAttributeNode( 'filename', $binaryFile->attribute( 'filename' ) ) );
        $fileNode->appendAttribute( eZDOMDocument::createAttributeNode( 'original-filename', $binaryFile->attribute( 'original_filename' ) ) );
        $fileNode->appendAttribute( eZDOMDocument::createAttributeNode( 'mime-type', $binaryFile->attribute( 'mime_type' ) ) );
        $fileNode->appendAttribute( eZDOMDocument::createAttributeNode( 'filekey', $fileKey ) );
        $node->appendChild( $fileNode );

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
//        $objectAttribute->setAttribute( 'data_int', (int)$attributeNode->elementTextContentByName( 'data-int' ) );

        $fileNode = $attributeNode->elementByName( 'binary-file' );
        $binaryFile =& eZBinaryFile::create( $objectAttribute->attribute( 'id' ), $objectAttribute->attribute( 'version' ) );

        $sourcePath = $package->simpleFilePath( $fileNode->attributeValue( 'filekey' ) );

        include_once( 'lib/ezfile/classes/ezdir.php' );
        $ini =& eZINI::instance();
        $mimeType = $fileNode->attributeValue( 'mime-type' );
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

        $basename = basename( $fileNode->attributeValue( 'filename' ) );
        while ( file_exists( $destinationPath . $basename ) )
        {
            $basename = substr( md5( mt_rand() ), 0, 8 ) . '.' . eZFile::suffix( $fileNode->attributeValue( 'filename' ) );
        }

        include_once( 'lib/ezfile/classes/ezfilehandler.php' );
        eZFileHandler::copy( $sourcePath, $destinationPath . $basename );
        eZDebug::writeNotice( 'Copied: ' . $sourcePath . ' to: ' . $destinationPath . $basename,
                              'eZBinaryFileType::unserializeContentObjectAttribute()' );

        $binaryFile->setAttribute( 'contentobject_attribute_id', $objectAttribute->attribute( 'id' ) );
        $binaryFile->setAttribute( 'filename', $basename );
        $binaryFile->setAttribute( 'original_filename', $fileNode->attributeValue( 'original-filename' ) );
        $binaryFile->setAttribute( 'mime_type', $fileNode->attributeValue( 'mime-type' ) );

        $binaryFile->store();
    }

}

eZDataType::register( EZ_DATATYPESTRING_BINARYFILE, "ezbinaryfiletype" );

?>

<?php
//
// Definition of eZBinaryFileHandler class
//
// Created on: <30-Apr-2002 16:47:08 bf>
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
 \group eZBinaryHandlers Binary file handlers
*/

/*!
  \class eZBinaryFileHandler ezbinaryfilehandler.php
  \ingroup eZKernel
  \brief Interface for all binary file handlers

*/

class eZBinaryFileHandler
{
    const HANDLE_UPLOAD = 0x1;
    const HANDLE_DOWNLOAD = 0x2;

    const HANDLE_ALL = 0x3; // HANDLE_UPLOAD | HANDLE_DOWNLOAD

    const TYPE_FILE = 'file';
    const TYPE_MEDIA = 'media';

    const RESULT_OK = 1;
    const RESULT_UNAVAILABLE = 2;

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

    function attribute( $attribute )
    {
        if ( isset( $this->Info[$attribute] ) )
        {
            return $this->Info[$attribute];
        }

        eZDebug::writeError( "Attribute '$attribute' does not exist", 'eZBinaryFileHandler::attribute' );
        return null;
    }

    /*!
     \return the suffix for the template name which will be used for attribute viewing.
     \note Default returns false which means no special template.
    */
    function viewTemplate( $contentobjectAttribute )
    {
        $retVal = false;
        return $retVal;
    }

    /*!
     \return the suffix for the template name which will be used for attribute viewing.
     \note Default returns false which means no special template.
    */
    function editTemplate( $contentobjectAttribute )
    {
        $retVal = false;
        return $retVal;
    }

    /*!
     \return the suffix for the template name which will be used for attribute viewing.
     \note Default returns false which means no special template.
    */
    function informationTemplate( $contentobjectAttribute )
    {
        $retVal = false;
        return $retVal;
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
        if ( in_array( $class, array( 'eZBinaryFile', 'eZMedia' ) ) )
        {
            $fileName = $origDir . "/" . $binary->attribute( 'mime_type_category' ) . '/'.  $binary->attribute( "filename" );
            $originalFilename = $binary->attribute( 'original_filename' );
        }
        else if ( $class == 'eZImageAliasHandler' )
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
    function downloadFileObject( $contentObject, $contentObjectAttribute )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObject->attribute( 'current_version' );
        $fileObject = eZBinaryFile::fetch( $contentObjectAttributeID, $version );
        if ( $fileObject )
            return $fileObject;
        $fileObject = eZMedia::fetch( $contentObjectAttributeID, $version );
        return $fileObject;
    }

    /*!
     \return the file object type which corresponds to \a $contentObject and \a $contentObjectAttribute.
     \deprecated
    */
    function downloadType( $contentObject, $contentObjectAttribute )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObject->attribute( 'current_version' );
        $fileObject = eZBinaryFile::fetch( $contentObjectAttributeID, $version );
        if ( $fileObject )
            return self::TYPE_FILE;
        $fileObject = eZMedia::fetch( $contentObjectAttributeID, $version );
        if ( $fileObject )
            return self::TYPE_MEDIA;
        return false;
    }

    /*!
     \return the download url for the file object which corresponds to \a $contentObject and \a $contentObjectAttribute.
     \deprecated
    */
    function downloadURL( $contentObject, $contentObjectAttribute )
    {
        $contentObjectID = $contentObject->attribute( 'id' );
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $downloadType = eZBinaryFileHandler::downloadType( $contentObject, $contentObjectAttribute );
        $downloadObject = eZBinaryFileHandler::downloadFileObject( $contentObject, $contentObjectAttribute );
        $name = '';
        switch ( $downloadType )
        {
            case self::TYPE_FILE:
            {
                $name = $downloadObject->attribute( 'original_filename' );
            } break;
            case self::TYPE_MEDIA:
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

    function handleDownload( $contentObject, $contentObjectAttribute, $type )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObject->attribute( 'current_version' );



        if ( !$contentObjectAttribute->hasStoredFileInformation( $contentObject, $version,
                                                                 $contentObjectAttribute->attribute( 'language_code' ) ) )
            return self::RESULT_UNAVAILABLE;

        $fileInfo = $contentObjectAttribute->storedFileInformation( $contentObject, $version,
                                                                    $contentObjectAttribute->attribute( 'language_code' ) );
        if ( !$fileInfo )
            return self::RESULT_UNAVAILABLE;
        if ( !$fileInfo['mime_type'] )
            return self::RESULT_UNAVAILABLE;

        $contentObjectAttribute->handleDownload( $contentObject, $version,
                                                 $contentObjectAttribute->attribute( 'language_code' ) );

        return $this->handleFileDownload( $contentObject, $contentObjectAttribute, $type, $fileInfo );
    }

    function handleFileDownload( $contentObject, $contentObjectAttribute, $type, $mimeData )
    {
        return false;
    }

    function repositories()
    {
        return array( 'kernel/classes/binaryhandlers' );
    }

    static function instance( $identifier = false )
    {
        if ( $identifier === false )
        {
            $fileINI = eZINI::instance( 'file.ini' );
            $identifier = $fileINI->variable( 'BinaryFileSettings', 'Handler' );
        }
        $instance =& $GLOBALS['eZBinaryFileHandlerInstance-' . $identifier];
        if ( !isset( $instance ) )
        {
            $handlerDirectory = $identifier;
            $handlerFilename = $identifier . "handler.php";
            if ( eZExtension::findExtensionType( array( 'ini-name' => 'file.ini',
                                                    'repository-group' => 'BinaryFileSettings',
                                                    'repository-variable' => 'Repositories',
                                                    'extension-group' => 'BinaryFileSettings',
                                                    'extension-variable' => 'ExtensionRepositories',
                                                    'type-directory' => true,
                                                    'type' => $identifier,
                                                    'subdir' => 'binaryhandlers',
                                                    'extension-subdir' => 'binaryhandlers',
                                                    'suffix-name' => 'handler.php' ),
                                             $out ) )
            {
                include_once( $out['found-file-path'] );
                $classname = $identifier . "handler";
                $instance = new $classname();
            }
            else
            {
                eZDebug::writeError( "Could not find binary file handler '$identifier'", 'eZBinaryFileHandler::instance' );
            }
        }
        return $instance;
    }

    /// \privatesection
    public $Info;
}

?>

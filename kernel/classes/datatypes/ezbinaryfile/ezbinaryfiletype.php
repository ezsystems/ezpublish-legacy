<?php
//
// Definition of eZBinaryFileType class
//
// Created on: <30-Apr-2002 13:06:21 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
include_once( "lib/ezutils/classes/ezfile.php" );
include_once( "lib/ezutils/classes/ezmimetype.php" );
include_once( "lib/ezutils/classes/ezhttpfile.php" );

define( 'EZ_DATATYPESTRING_MAX_BINARY_FILESIZE_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_MAX_BINARY_FILESIZE_VARIABLE', '_ezbinaryfile_max_filesize_' );
define( "EZ_DATATYPESTRING_BINARYFILE", "ezbinaryfile" );

class eZBinaryFileType extends eZDataType
{
    function eZBinaryFileType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_BINARYFILE, "BinaryFile" );
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
     Sets value according to current version
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $version = $contentObjectAttribute->attribute( "version" );
        $oldfile =& eZBinaryFile::fetch( $contentObjectAttributeID, $currentVersion );
        if( $oldfile != null )
        {
            $oldfile->setAttribute( "version",  $version );
            $oldfile->store();
        }
    }

    /*!
     Delete stored attribute
    */
    function deleteStoredObjectAttribute( &$contentObjectAttribute, $version = null )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $binaryFiles =& eZBinaryFile::fetch( $contentObjectAttributeID );
        if( $version == null )
        {
            foreach ( $binaryFiles as $binaryFile )
            {
                $mimeType =  $binaryFile->attribute( "mime_type" );
                list( $prefix, $suffix ) = split ('[/]', $mimeType );
                $orig_dir = "var/storage/original/" . $prefix;
                $fileName = $binaryFile->attribute( "filename" );
                if( file_exists( $orig_dir . "/" .$fileName ) )
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
                $orig_dir = "var/storage/original/" . $prefix;
                foreach ( $binaryFiles as $binaryFile )
                {
                    $fileName = $binaryFile->attribute( "filename" );
                    if( $currentFileName == $fileName )
                        $count += 1;
                }
                if( $count == 1 )
                {
                    if( file_exists( $orig_dir . "/" . $currentFileName ) )
                        unlink( $orig_dir . "/" .  $currentFileName );
                }
            }
        }
        eZBinaryFile::remove( $contentObjectAttributeID, $version );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
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
                    $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                         'A valid file is required.',
                                                                         'eZBinaryFileType' ) );
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
        if ( !eZHTTPFile::canFetch( $base . "_data_binaryfilename_" . $contentObjectAttribute->attribute( "id" ) ) )
            return false;

        $binaryFile =& eZHTTPFile::fetch( $base . "_data_binaryfilename_" . $contentObjectAttribute->attribute( "id" ) );

        $contentObjectAttribute->setContent( $binaryFile );

        //$binaryFile =& $contentObjectAttribute->content();

        if ( get_class( $binaryFile ) == "ezhttpfile" )
        {
            $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );

            $mimeObj = new  eZMimeType();
            $mime = $mimeObj->mimeTypeFor( $binaryFile->attribute( "original_filename" ), true );
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
            eZDebug::writeNotice( "dir=$orig_dir" );

            $binary->setAttribute( "contentobject_attribute_id", $contentObjectAttributeID );
            $binary->setAttribute( "version", $version );
            $binary->setAttribute( "filename", basename( $binaryFile->attribute( "filename" ) ) );
            $binary->setAttribute( "original_filename", $binaryFile->attribute( "original_filename" ) );
            $binary->setAttribute( "mime_type", $mime );

            $binary->store();


            $contentObjectAttribute->setContent( $binary );
        }
    }

    /*!
     Does nothing, since the file has been stored. See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( &$contentObjectAttribute )
    {
    }

    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute )
    {
        if( $action == "delete_binary" )
        {
            $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
            $version = $contentObjectAttribute->attribute( "version" );
            $this->deleteStoredObjectAttribute( &$contentObjectAttribute, $version );
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
    function title( &$contentObjectAttribute,  $name = "filename" )
    {
        $binaryFile =& eZBinaryFile::fetch( $contentObjectAttribute->attribute( "id" ),
                                            $contentObjectAttribute->attribute( "version" ) );
        $value = $binaryFile->attribute( $name );

        return $value;
    }

    function &objectAttributeContent( $contentObjectAttribute )
    {
        $binaryFile =& eZBinaryFile::fetch( $contentObjectAttribute->attribute( "id" ),
                                            $contentObjectAttribute->attribute( "version" ) );
        return $binaryFile;
    }

    function metaData()
    {
        return "";
    }
}

eZDataType::register( EZ_DATATYPESTRING_BINARYFILE, "ezbinaryfiletype" );

?>

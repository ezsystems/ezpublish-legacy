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
  \class eZFilePasstroughHandler ezfilepasstroughhandler.php
  \ingroup eZBinaryHandlers
  \brief Handles file downloading by passing the file trough PHP

*/
include_once( "kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php" );
include_once( "kernel/classes/ezbinaryfilehandler.php" );
define( "EZ_FILE_PASSTROUGH_ID", 'ezfilepasstrough' );

class eZFilePasstroughHandler extends eZBinaryFileHandler
{
    function eZFilePasstroughHandler()
    {
        $this->eZBinaryFileHandler( EZ_FILE_PASSTROUGH_ID, "PHP passtrough", EZ_BINARY_FILE_HANDLE_DOWNLOAD );
    }

    function handleDownload( &$contentObject, &$contentObjectAttribute, $type )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObject->attribute( 'current_version' );
        $fileObject =& eZBinaryFile::fetch( $contentObjectAttributeID, $version );
        if ( $fileObject === null )
            $fileObject =& eZMedia::fetch( $contentObjectAttributeID, $version );
//         switch ( $type )
//         {
//             case EZ_BINARY_FILE_TYPE_FILE:
//             {
//                 $fileObject =& eZBinaryFile::fetch( $contentObjectAttributeID, $version );
//             } break;
//             case EZ_BINARY_FILE_TYPE_MEDIA:
//             {
//                 $fileObject =& eZMedia::fetch( $contentObjectAttributeID, $version );
//             } break;
//         }
        if ( $fileObject === null )
            return EZ_BINARY_FILE_RESULT_UNAVAILABLE;
        $fileName = $this->storedFilename( $fileObject );
        if ( $fileObject->attribute( "filename" ) != "" and file_exists( $fileName ) )
        {
            $fileSize = filesize( $fileName );
            $mimeType =  $fileObject->attribute( 'mime_type' );
            $originalFileName = $fileObject->attribute( 'original_filename' );

//             print( "<pre>" );
//             var_dump( $_SERVER );
//             print( "</pre>" );
//             exit;
//             eZDebug::writeDebug( $_SERVER, "\$_SERVER" );

            $contentLength = $fileSize;
            $fileOffset = false;
            $fileLength = false;
            if ( isset( $_SERVER['HTTP_RANGE'] ) )
            {
                $httpRange = trim( $_SERVER['HTTP_RANGE'] );
//                 eZDebug::writeDebug( $httpRange, "httpRange" );
                if ( preg_match( "/^bytes=([0-9]+)-$/", $httpRange, $matches ) )
                {
                    $fileOffset = $matches[1];
//                     eZDebug::writeDebug( $fileOffset, "fileoffset" );
//                     eZDebug::writeDebug( "Content-Range: bytes $fileOffset" . "-" . ($fileSize - 1) . "/$fileSize" );
                    header( "Content-Range: bytes $fileOffset-" . $fileSize - 1 . "/$fileSize" );
                    header( "HTTP/1.1 206 Partial content" );
                    $contentLength -= $fileOffset;
                }
            }

            header( "Pragma: " );
            header( "Cache-Control: " );
            header( "Content-Length: $contentLength" );
            header( "Content-Type: $mimeType" );
            header( "X-Powered-By: eZ publish" );
            header( "Content-disposition: attachment; filename=$originalFileName" );
            header( "Content-Transfer-Encoding: binary" );
            header( "Accept-Ranges: bytes" );

            $fh = fopen( "$fileName", "rb" );
            if ( $fileOffset )
            {
                eZDebug::writeDebug( $fileOffset, "seeking to fileoffset" );
                fseek( $fh, $fileOffset );
            }

            ob_end_clean();
            fpassthru( $fh );
//             while ( !feof( $fh ) )
//             {
//                 $buffer = fread( $fh, 4096 );
//                 print( $buffer );
//             }
            fclose( $fh );
            fflush();
            eZExecution::cleanExit();
        }
        return EZ_BINARY_FILE_RESULT_UNAVAILABLE;
    }
}

?>

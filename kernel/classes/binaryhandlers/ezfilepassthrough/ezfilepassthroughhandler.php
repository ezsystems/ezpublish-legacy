<?php
//
// Definition of eZFilePassthroughHandler class
//
// Created on: <30-Apr-2002 16:47:08 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
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
  \class eZFilePassthroughHandler ezfilepassthroughhandler.php
  \ingroup eZBinaryHandlers
  \brief Handles file downloading by passing the file through PHP

*/
class eZFilePassthroughHandler extends eZBinaryFileHandler
{
    const HANDLER_ID = 'ezfilepassthrough';

    function eZFilePassthroughHandler()
    {
        $this->eZBinaryFileHandler( self::HANDLER_ID, "PHP passthrough", eZBinaryFileHandler::HANDLE_DOWNLOAD );
    }

    function handleFileDownload( $contentObject, $contentObjectAttribute, $type,
                                 $fileInfo )
    {
        $fileName = $fileInfo['filepath'];

        $file = eZClusterFileHandler::instance( $fileName );

        if ( $fileName != "" and $file->exists() )
        {
            $file->fetch( true );
            $fileSize = $file->size();
            $mimeType =  $fileInfo['mime_type'];
            $contentLength = $fileSize;
            $fileOffset = false;
            $fileLength = false;
            if ( isset( $_SERVER['HTTP_RANGE'] ) )
            {
                $httpRange = trim( $_SERVER['HTTP_RANGE'] );
                if ( preg_match( "/^bytes=(\d+)-(\d+)?$/", $httpRange, $matches ) )
                {
                    $fileOffset = $matches[1];
                    if ( isset( $matches[2] ) )
                    {
                        $fileLength = $matches[2] - $matches[1] + 1;
                        $lastPos  = $matches[2];
                    }
                    else
                    {
                        $fileLength = $fileSize - $matches[1];
                        $lastPos = $fileSize -1;
                    }
                    header( "Content-Range: bytes $matches[1]-" . $lastPos . "/$fileSize" );
                    header( "HTTP/1.1 206 Partial Content" );
                    $contentLength = $fileLength;
                }
            }
            // Figure out the time of last modification of the file right way to get the file mtime ... the
            $fileModificationTime = filemtime( $fileName );

            ob_clean();
            header( "Pragma: " );
            header( "Cache-Control: " );
            /* Set cache time out to 10 minutes, this should be good enough to work around an IE bug */
            header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 600) . ' GMT' );
            header( "Last-Modified: ". gmdate( 'D, d M Y H:i:s', $fileModificationTime ) . ' GMT' );
            header( "Content-Length: $contentLength" );
            header( "Content-Type: $mimeType" );
            header( "X-Powered-By: eZ Publish" );
            header( "Content-Disposition: " . self::dispositionType( $mimeType ) );
            header( "Content-Transfer-Encoding: binary" );
            header( "Accept-Ranges: bytes" );

            $fh = fopen( "$fileName", "rb" );
            if ( $fileOffset !== false && $fileLength !== false )
            {
                echo stream_get_contents( $fh, $contentLength, $fileOffset );
            }
            else
            {
                ob_end_clean();
                fpassthru( $fh );
            }
            fclose( $fh );

            eZExecution::cleanExit();
        }
        return eZBinaryFileHandler::RESULT_UNAVAILABLE;
    }

    /**
     * Checks if a file should be downloaded to disk or displayed inline in
     * the browser.
     *
     * This method returns "attachment" if no setting for the mime type is found.
     *
     * @param string $mimetype
     * @return string "attachment" or "inline"
     */
    protected static function dispositionType( $mimeType )
    {
        $ini = eZINI::instance( 'file.ini' );

        $mimeTypes = $ini->variable( 'PassThroughSettings', 'ContentDisposition', array() );
        if ( isset( $mimeTypes[$mimeType] ) )
        {
            return $mimeTypes[$mimeType];
        }

        return "attachment";
    }
}

?>

<?php
/**
 * File containing the eZFile class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
 \class eZFile ezfile.php
 \ingroup eZUtils
 \brief Tool class which has convencience functions for files and directories

*/
class eZFile
{
    /**
     * Number of bytes read per fread() operation.
     *
     * @see downloadContent()
     */
    const READ_PACKET_SIZE = 16384;

    /**
     * Reads the whole contents of the file \a $file and
     * splits it into lines which is collected into an array and returned.
     * It will handle Unix (\n), Windows (\r\n) and Mac (\r) style newlines.
     * \note The newline character(s) are not present in the line string.
     *
     * @deprecated Since 4.4, use file( $file, FILE_IGNORE_NEW_LINES ) instead.
     * @return array|false
     */
    static function splitLines( $file )
    {
        $contents = file_get_contents( $file );
        if ( $contents === false )
            return false;
        $lines = preg_split( "#\r\n|\r|\n#", $contents );
        unset( $contents );
        return $lines;
    }

    /*!
     Creates a file called \a $filename.
     If \a $directory is specified the file is placed there, the directory will also be created if missing.
     if \a $data is specified the file will created with the content of this variable.

     \param $atomic If true the file contents will be written to a temporary file and renamed to the correct file.
    */
    static function create( $filename, $directory = false, $data = false, $atomic = false )
    {
        $filepath = $filename;
        if ( $directory )
        {
            if ( !file_exists( $directory ) )
            {
                eZDir::mkdir( $directory, false, true );
//                 eZDebugSetting::writeNotice( 'ezfile-create', "Created directory $directory", 'eZFile::create' );
            }
            $filepath = $directory . '/' . $filename;
        }
        // If atomic creation is needed we will use a temporary
        // file when writing the data, then rename it to the correct path.
        if ( $atomic )
        {
            $realpath = $filepath;
            $dirname  = dirname( $filepath );
            if ( strlen( $dirname ) != 0 )
                $dirname .= "/";
            $filepath = $dirname . "ezfile-tmp." . md5( $filepath . getmypid() . mt_rand() );
        }

        $file = fopen( $filepath, 'wb' );
        if ( $file )
        {
//             eZDebugSetting::writeNotice( 'ezfile-create', "Created file $filepath", 'eZFile::create' );
            if ( $data )
                fwrite( $file, $data );
            fclose( $file );

            if ( $atomic )
            {
                if ( !eZFile::rename( $filepath, $realpath ) )
                {
                    // If the renaming process fails, delete the temporary file
                    unlink( $filepath );
                }
            }
            return true;
        }
//         eZDebugSetting::writeNotice( 'ezfile-create', "Failed creating file $filepath", 'eZFile::create' );
        return false;
    }

    /*!
     \static
     Read all content of file.

     \param filename

     \return file contents, false if error

     \deprecated since eZ Publish 4.1, use file_get_contents() instead
    */
    static function getContents( $filename )
    {
        eZDebug::writeWarning( __METHOD__ . ' is deprecated, use file_get_contents() instead' );

        if ( function_exists( 'file_get_contents' ) )
        {
            return file_get_contents( $filename );
        }
        else
        {
            $fp = fopen( $filename, 'r' );
            if ( !$fp )
            {
                eZDebug::writeError( 'Could not read contents of ' . $filename, __METHOD__ );
                return false;
            }

            return fread( $fp, filesize( $filename ) );
        }
    }

    /*!
     \static
     Get suffix from filename

     \param filename
     \return suffix, extends: file/to/readme.txt return txt
    */
    static function suffix( $filename )
    {
        $parts = explode( '.', $filename);
        return array_pop( $parts );
    }

    /*!
    \static
    Check if a given file is writeable

    \return TRUE/FALSE
    */
    static function isWriteable( $filename )
    {
        if ( eZSys::osType() != 'win32' )
            return is_writable( $filename );

        /* PHP function is_writable() doesn't work correctly on Windows NT descendants.
         * So we have to use the following hack on those OSes.
         */
        if ( !( $fd = @fopen( $filename, 'a' ) ) )
            return FALSE;

        fclose( $fd );

        return TRUE;
    }

    /*!
    \static
    Renames a file atomically on Unix, and provides a workaround for Windows

    \param $srcFile from filename
    \param $destFile to filename
    \param $mkdir make directory for dest file if needed

    \return rename status. ( true if successful, false if not )
    */
    static function rename( $srcFile, $destFile, $mkdir = false )
    {
        /* On windows we need to unlink the destination file first */
        if ( strtolower( substr( PHP_OS, 0, 3 ) ) == 'win' )
        {
            @unlink( $destFile );
        }
        if( $mkdir )
        {
            eZDir::mkdir( dirname( $destFile ), false, true );
        }
        return rename( $srcFile, $destFile );
    }

    /**
     * Prepares a file for Download and terminates the execution.
     * This method will:
     * - empty the output buffer
     * - stop buffering
     * - stop the active session (in order to allow concurrent browsing while downloading)
     *
     * @param string $file Path to the local file
     * @param bool $isAttachedDownload Determines weather to download the file as an attachment ( download popup box ) or not.
     * @param string $overrideFilename
     * @param int $startOffset Offset to start transfer from, in bytes
     * @param int $length Data size to transfer
     *
     * @return bool false if error
     */
    static function download( $file, $isAttachedDownload = true, $overrideFilename = false, $startOffset = 0, $length = false )
    {
        if ( !file_exists( $file ) )
        {
            return false;
        }

        ob_end_clean();
        eZSession::stop();
        self::downloadHeaders( $file, $isAttachedDownload, $overrideFilename, $startOffset, $length );
        self::downloadContent( $file, $startOffset, $length );

        eZExecution::cleanExit();
    }

    /**
     * Handles the header part of a file transfer to the client
     *
     * @see download()
     *
     * @param string $file Path to the local file
     * @param bool $isAttachedDownload Determines weather to download the file as an attachment ( download popup box ) or not.
     * @param string $overrideFilename Filename to send in headers instead of the actual file's name
     * @param int $startOffset Offset to start transfer from, in bytes
     * @param int $length Data size to transfer
     * @param string $fileSize The file's size. If not given, actual filesize will be queried. Required to work with clusterized files...
     */
    public static function downloadHeaders( $file, $isAttachedDownload = true, $overrideFilename = false, $startOffset = 0, $length = false, $fileSize = false )
    {
        if ( $fileSize === false )
        {
            if ( !file_exists( $file ) )
            {
                eZDebug::writeError( "\$fileSize not given, and file not found", __METHOD__ );
                return false;
            }

            $fileSize = filesize( $file );
        }

        header( 'X-Powered-By: eZ Publish' );
        header( "Content-Length: $fileSize" );
        $mimeinfo = eZMimeType::findByURL( $file );
        header( "Content-Type: {$mimeinfo['name']}" );

        // Fixes problems with IE when opening a file directly
        header( "Pragma: " );
        header( "Cache-Control: " );
        /* Set cache time out to 10 minutes, this should be good enough to work
           around an IE bug */
        header( "Expires: ". gmdate( 'D, d M Y H:i:s', time() + 600 ) . ' GMT' );
        header(
            "Content-Disposition: " .
            ( $isAttachedDownload ? 'attachment' : 'inline' ) .
            ( $overrideFilename !== false ? "; filename={$overrideFilename}" : '' )
        );

        // partial download (HTTP 'Range' header)
        if ( $startOffset !== 0 )
        {
            $endOffset = ( $length !== false ) ? ( $length + $startOffset - 1 ) : $fileSize - 1;
            header( "Content-Range: bytes {$startOffset}-{$endOffset}/{$fileSize}" );
            header( "HTTP/1.1 206 Partial Content" );
        }
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Accept-Ranges: bytes' );
    }

    /**
     * Handles the data part of a file transfer to the client
     *
     * @see download()
     *
     * @param string $file Path to the local file
     * @param int $startOffset Offset to start transfer from, in bytes
     * @param int $length Data size to transfer
     */
    public static function downloadContent( $file, $startOffset = 0, $length = false )
    {
        if ( ( $fp = fopen( $file, 'rb' ) ) === false )
        {
            eZDebug::writeError( "An error occured opening '$file' for reading", __METHOD__ );
            return false;
        }

        $fileSize = filesize( $file );

        // an offset has been given: move the pointer to that offset if it seems valid
        if ( $startOffset !== false && $startOffset <= $fileSize && fseek( $fp, $startOffset ) === -1 )
        {
            eZDebug::writeError( "Error while setting offset on '{$file}'", __METHOD__ );
            return false;
        }

        $transferred = $startOffset;
        $packetSize = self::READ_PACKET_SIZE;
        $endOffset = ( $length === false ) ? $fileSize - 1 : $length + $startOffset - 1;

        while ( !feof( $fp ) && $transferred < $endOffset + 1 )
        {
            if ( $transferred + $packetSize > $endOffset + 1 )
            {
                $packetSize = $endOffset + 1 - $transferred;
            }
            echo fread( $fp, $packetSize );
            $transferred += $packetSize;
        }
        fclose( $fp );

        return true;
    }
}

?>

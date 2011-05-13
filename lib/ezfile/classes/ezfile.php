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
                eZFile::rename( $filepath, $realpath );
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

    /*!
     \static
     Prepares a file for Download and terminates the execution.

     \param $file Filename
     \param $isAttachedDownload Determines weather to download the file as an attachment ( download popup box ) or not.

     \return false if error
    */
    static function download( $file, $isAttachedDownload = true, $overrideFilename = false )
    {
        if ( file_exists( $file ) )
        {
            $mimeinfo = eZMimeType::findByURL( $file );

            ob_clean();

            header( 'X-Powered-By: eZ Publish' );
            header( 'Content-Length: ' . filesize( $file ) );
            header( 'Content-Type: ' . $mimeinfo['name'] );

            // Fixes problems with IE when opening a file directly
            header( "Pragma: " );
            header( "Cache-Control: " );
            /* Set cache time out to 10 minutes, this should be good enough to work
            around an IE bug */
            header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 600) . ' GMT' );
            if( $overrideFilename )
            {
                $mimeinfo['filename'] = $overrideFilename;
            }
            if ( $isAttachedDownload )
            {
                header( 'Content-Disposition: attachment; filename='.$mimeinfo['filename'] );
            }
            else
            {
                header( 'Content-Disposition: inline; filename='.$mimeinfo['filename'] );
            }
            header( 'Content-Transfer-Encoding: binary' );
            header( 'Accept-Ranges: bytes' );

            ob_end_clean();

            @readfile( $file );

            eZExecution::cleanExit();
        }
        else
        {
            return false;
        }
    }
}

?>

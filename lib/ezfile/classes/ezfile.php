<?php
//
// Definition of eZFile class
//
// Created on: <03-Jun-2002 17:19:12 amos>
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

/*! \file ezfile.php
*/

/*!
 \class eZFile ezfile.php
 \ingroup eZUtils
 \brief Tool class which has convencience functions for files and directories

*/

require_once( "lib/ezutils/classes/ezdebug.php" );
//include_once( 'lib/ezfile/classes/ezdir.php' );

class eZFile
{
    /*!
     \static
     Reads the whole contents of the file \a $file and
     splits it into lines which is collected into an array and returned.
     It will handle Unix (\n), Windows (\r\n) and Mac (\r) style newlines.
     \note The newline character(s) are not present in the line string.
    */
    static function splitLines( $file )
    {
        $fp = @fopen( $file, "rb" );
        if ( !$fp )
            return false;
        $size = filesize( $file );
        $contents = fread( $fp, $size );
        fclose( $fp );
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
    */
    static function getContents( $filename )
    {
        if ( function_exists( 'file_get_contents' ) )
        {
            return file_get_contents( $filename );
        }
        else
        {
            $fp = fopen( $filename, 'r' );
            if ( !$fp )
            {
                eZDebug::writeError( 'Could not read contents of ' . $filename, 'eZFile::getContents()' );
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
        //include_once( 'lib/ezutils/classes/ezsys.php' );

        if ( eZSys::osType() != 'win32' )
            return is_writable( $filename );

        /* PHP function is_writable() doesn't work correctly on Windows NT descendants.
         * So we have to use the following hack on those OSes.
         * FIXME: maybe on win9x we shouldn't do this?
         */
        if ( !( $fd = @fopen( $filename, 'a' ) ) )
            return FALSE;

        fclose( $fd );

        return TRUE;
    }

    /*!
    \static
    Renames a file atomically on Unix, and provides a workaround for Windows

    \param from filename
    \param to filename

    \return rename status. ( true if successful, false if not )
    */
    static function rename( $srcFile, $destFile )
    {
        /* On windows we need to unlink the destination file first */
        if ( strtolower( substr( PHP_OS, 0, 3 ) ) == 'win' )
        {
            @unlink( $destFile );
        }
        return rename( $srcFile, $destFile );
    }

    /*!
     \static
     Prepares a file for Download and terminates the execution.

     \param $file Filename
     \param $isAttached Download Determines weather to download the file as an attachment ( download popup box ) or not.

     \return false if error
    */
    static function download( $file, $isAttachedDownload = true, $overrideFilename = false )
    {
        if ( file_exists( $file ) )
        {
            //include_once( 'lib/ezutils/classes/ezmimetype.php' );
            $mimeinfo = eZMimeType::findByURL( $file );

            ob_clean();

            header( 'X-Powered-By: eZ Publish' );
            header( 'Content-Length: ' . filesize( $file ) );
            header( 'Content-Type: ' . $mimeinfo['name'] );

            // Fixes problems with IE when opening a file directly
            header( 'Cache-Control: no-store, no-cache, must-revalidate' ); // HTTP/1.1
            header( 'Cache-Control: pre-check=0, post-check=0, max-age=0' ); // HTTP/1.1
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

            require_once( 'lib/ezutils/classes/ezexecution.php' );
            eZExecution::cleanExit();
        }
        else
        {
            return false;
        }
    }
}

?>

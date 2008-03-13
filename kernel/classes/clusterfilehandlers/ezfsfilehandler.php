<?php
//
// Definition of eZFSFileHandler class
//
// Created on: <09-Mar-2006 16:40:46 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezfsfilehandler.php
*/

require_once( 'lib/ezutils/classes/ezdebugsetting.php' );

class eZFSFileHandler
{
    /**
     * Constructor.
     *
     * $filePath File path. If specified, file metadata is fetched in the constructor.
     */
    function eZFSFileHandler( $filePath = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::ctor( '$filePath' )" );
        $this->metaData['name'] = $filePath;
        $this->loadMetaData();
    }

    /*!
     \public
     Load file meta information.

     \param $force If true, file stats will be refreshed
    */
    function loadMetaData( $force = false )
    {
        if ( $this->metaData['name'] !== false )
        {
            if ( $force )
                clearstatcache();

            // fill $this->metaData
            $filePath = $this->metaData['name'];
            eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
            $this->metaData = @stat( $filePath );
            eZDebug::accumulatorStop( 'dbfile' );
            $this->metaData['name'] = $filePath;
        }
    }

    /**
     * Fetches file from db and saves it in FS under the same name.
     *
     * In case of fetching from filesystem does nothing.
     *
     * \public
     * \static
     */
    function fileFetch( $filePath )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileFetch( '$filePath' )" );
    }

    /**
     * Fetches file from db and saves it in FS under the same name.
     *
     * In case of fetching from filesystem does nothing.
     *
     * \public
     */
    function fetch()
    {
        $filePath = $this->metaData['name'];
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fetch( '$filePath' )" );
    }

    /**
     * Fetches file from db and saves it in FS under unique name.
     *
     * In case of fetching from filesystem does nothing.
     * \public
     */
    function fetchUnique( )
    {
        $filePath = $this->metaData['name'];
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fetchUnique( '$filePath' )" );
        return $filePath;
    }

    /**
     * Store file.
     *
     * In case of storing to filesystem does nothing.
     *
     * \public
     * \static
     * \param $filePath Path to the file being stored.
     * \param $scope    Means something like "file category". May be used to clean caches of a certain type.
     * \param $delete   true if the file should be deleted after storing.
     */
    function fileStore( $filePath, $scope = false, $delete = false, $datatype = false )
    {
        $delete = (int) $delete;
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileStore( '$filePath' )" );
    }

    /**
     * Store file contents.
     *
     * \public
     * \static
     */
    function fileStoreContents( $filePath, $contents, $scope = false, $datatype = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileStoreContents( '$filePath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        if ( !( $fh = fopen( $filePath, 'w' ) ) )
        {
            eZDebug::writeError( "Cannot open file '$filePath'", 'ezfsfilehandler::fileStoreContents()' );
            return false;
        }

        if ( fwrite( $fh, $contents ) === false )
        {
            eZDebug::writeError( "Cannot write to '$filePath'", 'ezfsfilehandler::fileStoreContents()' );
            return false;
        }

        fclose( $fh );

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Store file contents.
     *
     * \public
     * \static
     */
    function storeContents( $contents, $scope = false, $datatype = false )
    {
        $filePath = $this->metaData['name'];

        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::storeContents( '$filePath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        include_once( 'lib/ezfile/classes/ezfile.php' );
        eZFile::create( basename( $filePath ), dirname( $filePath ), $contents );

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Returns file contents.
     *
     * \public
     * \static
     * \return contents string, or false in case of an error.
     */
    function fileFetchContents( $filePath )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileFetchContents( '$filePath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $rslt = file_get_contents( $filePath );
        eZDebug::accumulatorStop( 'dbfile' );

        return $rslt;
    }

    /**
     * Returns file contents.
     *
     * \public
     * \return contents string, or false in case of an error.
     */
    function fetchContents()
    {
        $filePath = $this->metaData['name'];
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fetchContents( '$filePath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $rslt = file_get_contents( $filePath );
        eZDebug::accumulatorStop( 'dbfile' );

        return $rslt;
    }


    /**
     * Returns file metadata.
     *
     * \public
     */
    function stat()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::stat()" );
        return $this->metaData;
    }

    /**
     * Returns file size.
     *
     * \public
     */
    function size()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::size()" );
        return isset( $this->metaData['size'] ) ? $this->metaData['size'] : null;
    }

    /**
     * Returns file modification time.
     *
     * \public
     */
    function mtime()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::mtime()" );
        return isset( $this->metaData['mtime'] ) ? $this->metaData['mtime'] : null;
    }

    /**
     * Returns file name.
     *
     * \public
     */
    function name()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::name()" );
        return isset( $this->metaData['name'] ) ? $this->metaData['name'] : null;
    }

    /**
     * Delete files matching regex $fileRegex under directory $dir.
     *
     * \public
     * \static
     * \sa fileDeleteByWildcard()
     */
    function fileDeleteByRegex( $dir, $fileRegex )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDeleteByRegex( '$dir', '$fileRegex' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        if ( !file_exists( $dir ) )
        {
            //eZDebugSetting::writeDebug( 'kernel-clustering', "Dir '$dir' does not exist", 'dir' );
            eZDebug::accumulatorStop( 'dbfile' );
            return;
        }

        $dirHandle = opendir( $dir );
        if ( !$dirHandle )
        {
            eZDebug::writeError( "opendir( '$dir' ) failed." );
            eZDebug::accumulatorStop( 'dbfile' );
            return;
        }

        while ( ( $file = readdir( $dirHandle ) ) !== false )
        {
            if ( $file == '.' or
                 $file == '..' )
                continue;
            if ( preg_match( "/^$fileRegex/", $file ) )
            {
                //eZDebugSetting::writeDebug( 'kernel-clustering', "\$file = eZDir::path( array( '$dir', '$file' ) );" );
                $file = eZDir::path( array( $dir, $file ) );
                eZDebugSetting::writeDebug( 'kernel-clustering', "Removing cache file '$file'", 'eZFSFileHandler::deleteRegex' );
                unlink( $file );

                // Write log message to storage.log
                include_once( 'lib/ezutils/classes/ezlog.php' );
                eZLog::writeStorageLog( $file );
            }
        }
        closedir( $dirHandle );

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Delete files matching given wildcard.
     *
     * Note that this method is faster than fileDeleteByRegex().
     *
     * \public
     * \static
     * \sa fileDeleteByRegex()
     */
    function fileDeleteByWildcard( $wildcard )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDeleteByWildcard( '$wildcard' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        array_map( 'unlink', glob( $wildcard, GLOB_BRACE ) );
        eZDebug::accumulatorStop( 'dbfile' );
    }


    /**
     * Delete files located in a directories from dirList, with common prefix specified by
     * commonPath, and common suffix with added wildcard at the end
     *
     * \public
     * \static
     * \sa fileDeleteByRegex()
     */
    function fileDeleteByDirList( $dirList, $commonPath, $commonSuffix )
    {
        $dirs = implode( ',', $dirList );
        $wildcard = "$commonPath/\{$dirs}/$commonSuffix*";

        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDeleteByDirList( '$dirList', '$commonPath', '$commonSuffix' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        array_map( 'unlink', glob( $wildcard, GLOB_BRACE ) );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * \public
     * \static
     */
    function fileDelete( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDelete( '$path' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        if ( is_file( $path ) )
        {
            include_once( 'lib/ezfile/classes/ezfilehandler.php' );
            $handler =& eZFileHandler::instance( false );
            $handler->unlink( $path );
            if ( file_exists( $path ) )
                eZDebug::writeError( "File still exists after removal: '$path'", 'fs::fileDelete' );
        }
        else
        {
            include_once( 'lib/ezfile/classes/ezdir.php' );
            eZDir::recursiveDelete( $path );
        }

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Deletes specified file/directory.
     *
     * If a directory specified it is deleted recursively.
     *
     * \public
     * \static
     */
    function delete()
    {
        $path = $this->metaData['name'];

        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::delete( '$path' )" );

        // FIXME: cut&paste from fileDelete()

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        if ( is_file( $path ) )
        {
            include_once( 'lib/ezfile/classes/ezfilehandler.php' );
            $handler =& eZFileHandler::instance( false );
            $handler->unlink( $path );
            if ( file_exists( $path ) )
                eZDebug::writeError( "File still exists after removal: '$path'", 'fs::fileDelete' );
        }
        elseif ( is_dir( $path ) )
        {
            include_once( 'lib/ezfile/classes/ezdir.php' );
            eZDir::recursiveDelete( $path );
        }

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Deletes a file that has been fetched before.
     *
     * In case of fetching from filesystem does nothing.
     *
     * \public
     * \static
     */
    function fileDeleteLocal( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDeleteLocal( '$path' )" );
    }

    /**
     * Deletes a file that has been fetched before.
     *
     * In case of fetching from filesystem does nothing.
     *
     * \public
     */
    function deleteLocal()
    {
        $path = $this->metaData['name'];
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::deleteLocal( '$path' )" );
    }

    /**
     * Check if given file/dir exists.
     *
     * \public
     * \static
     */
    function fileExists( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileExists( '$path' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $rc = file_exists( $path );
        eZDebug::accumulatorStop( 'dbfile' );

        return $rc;
    }

    /**
     * Check if given file/dir exists.
     *
     * NOTE: this function does not interact with filesystem.
     * Instead, it just returns existance status determined in the constructor.
     *
     * \public
     */
    function exists()
    {
        $path = $this->metaData['name'];
        $rc = isset( $this->metaData['mtime'] );
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::exists( '$path' ): " . ( $rc ? 'true' :'false' ) );

        return $rc;
    }

    /**
     * Outputs file contents prepending them with appropriate HTTP headers.
     *
     * \public
     */
    function passthrough()
    {
        $path = $this->metaData['name'];

        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::passthrough()" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $mimeData = eZMimeType::findByFileContents( $path );
        $mimeType = $mimeData['name'];
        $contentLength = filesize( $path );

        header( "Content-Length: $contentLength" );
        header( "Content-Type: $mimeType" );
        header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 6000) . 'GMT');
        header( "Connection: close" );

        readfile( $path );

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Copy file.
     *
     * \public
     * \static
     */
    function fileCopy( $srcPath, $dstPath )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileCopy( '$srcPath', '$dstPath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        require_once( 'lib/ezfile/classes/ezfilehandler.php' );
        eZFileHandler::copy( $srcPath, $dstPath );
        eZDebug::accumulatorStop( 'dbfile', false, 'dbfile' );
    }

    /**
     * Create symbolic or hard link to file.
     *
     * \public
     * \static
     */
    function fileLinkCopy( $srcPath, $dstPath, $symLink )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileLinkCopy( '$srcPath', '$dstPath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        require_once( 'lib/ezfile/classes/ezfilehandler.php' );
        eZFileHandler::linkCopy( $srcPath, $dstPath, $symLink );
        eZDebug::accumulatorStop( 'dbfile', false, 'dbfile' );
    }

    /**
     * Move file.
     *
     * \public
     * \static
     */
    function fileMove( $srcPath, $dstPath )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileMove( '$srcPath', '$dstPath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        require_once( 'lib/ezfile/classes/ezfilehandler.php' );
        eZFileHandler::move( $srcPath, $dstPath );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Move file.
     *
     * \public
     */
    function move( $dstPath )
    {
        $srcPath = $this->metaData['name'];

        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::move( '$srcPath', '$dstPath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        require_once( 'lib/ezfile/classes/ezfilehandler.php' );
        eZFileHandler::move( $srcPath, $dstPath );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    var $metaData = null;
}

?>

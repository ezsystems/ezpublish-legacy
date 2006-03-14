<?php
//
// Definition of eZDBFileHandler class
//
// Created on: <07-Mar-2006 16:55:02 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

/*! \file ezdbfilehandler.php
*/

require_once( 'lib/ezutils/classes/ezdbfile.php' );
require_once( 'lib/ezutils/classes/ezdebugsetting.php' );

class eZDBFileHandler // used in eZFileHandler1
{
    /**
     * Constructor.
     *
     * $filePath File path. If specified, file metadata is fetched in the constructor.
     */
    function eZDBFileHandler( $filePath = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::ctor( '$filePath' )" );
        if ( $filePath !== false )
        {
            // fill $this->metaData

            eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
            $dbfile = new eZDBFile();
            $exists = $dbfile->fileExists( $filePath );
            if ( $exists )
                $this->metaData = $dbfile->fileMetadata( $filePath );
            else
                $this->metaData['name'] = $filePath;

            eZDebug::accumulatorStop( 'dbfile' );

            //eZDebugSetting::writeDebug( 'kernel-clustering', $this->metaData, 'db::ctor: $this->metaData' );
        }
        //eZDebugSetting::writeDebug( 'kernel-clustering', "db::ctor( '$filePath' ) finished" );

    }

    /**
     * \public
     * \static
     * \param $filePath Path to the file being stored.
     * \param $scope    Means something like "file category". May be used to clean caches of a certain type.
     * \param $delete   true if the file should be deleted after storing.
     */
    function fileStore( $filePath, $scope = false, $delete = false, $datatype = 'misc' )
    {
        if ( $scope === false )
            $scope = 'UNKNOWN_SCOPE';

        $delete = (int) $delete;
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileStore( '$filePath', scope='$scope', delete=$delete )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        $dbfile = new eZDBFile();
        $dbfile->storeFile( $filePath, $datatype, $filePath, $scope );

        if ( $delete )
            @unlink( $filePath );

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Fetches file from db and saves it in FS under the same name.
     *
     * \public
     * \static
     */
    function fileFetch( $filePath )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileFetch( '$filePath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $dbfile->saveToFileSystem( $filePath );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Fetches file from db and saves it in FS under the same name.
     *
     * \public
     */
    function fetch()
    {
        $filePath = $this->metaData['name'];

        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fetch( '$filePath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $dbfile->saveToFileSystem( $filePath );
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
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileFetchContents( '$filePath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $contents = $dbfile->fetchFile( $filePath );
        eZDebug::accumulatorStop( 'dbfile' );
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
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fetchContents( '$filePath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $contents = $dbfile->fetchFile( $filePath );
        eZDebug::accumulatorStop( 'dbfile' );

        return $contents;
    }

    /**
     * Returns file metadata.
     *
     * \public
     */
    function stat()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::stat()" );
        return $this->metaData;
    }

    /**
     * Returns file size.
     *
     * \public
     */
    function size()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::size()" );
        return isset( $this->metaData['size'] ) ? $this->metaData['size'] : null;
    }

    /**
     * Returns file modification time.
     *
     * \public
     */
    function mtime()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::mtime()" );
        return isset( $this->metaData['mtime'] ) ? $this->metaData['mtime'] : null;
    }

    /**
     * \public
     * \static
     */
    function fileDeleteByRegex( $dir, $fileRegex )
    {
        $regex = $dir . '/' . $fileRegex;
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::deleteByRegex( '$regex' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $dbfile->deleteRegex( $regex );
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
    function fileDelete( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileDelete( '$path' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $dbfile->delete( $path );
        $dbfile->deleteRegex( $path . '/.+$' );
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

        eZDebugSetting::writeDebug( 'kernel-clustering', "db::delete( '$path' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $dbfile->delete( $path );
        $dbfile->deleteRegex( $path . '/.+$' );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Deletes a file that has been fetched before.
     *
     * \public
     * \static
     */
    function fileDeleteFetched( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileDeleteFetched( '$path' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        @unlink( $path );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Deletes a file that has been fetched before.
     *
     * \public
     */
    function deleteFetched()
    {
        $path = $this->metaData['name'];
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::deleteFetched( '$path' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        @unlink( $path );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Check if given file/dir exists.
     *
     * \public
     * \static
     */
    function fileExists( $path )
    {
        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $rc = $dbfile->fileExists( $path );
        eZDebug::accumulatorStop( 'dbfile' );

        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileExists( '$path' ): " . ( $rc ? 'true' :'false' ) );

        return $rc;
    }

    /**
     * Check if given file/dir exists.
     *
     * \public
     */
    function exists()
    {
        $path = $this->metaData['name'];

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $rc = $dbfile->fileExists( $path );
        eZDebug::accumulatorStop( 'dbfile' );

        eZDebugSetting::writeDebug( 'kernel-clustering', "db::exists( '$path' ): " . ( $rc ? 'true' :'false' ) );

        return $rc;
    }

    /**
     * Outputs file contents prepending them with appropriate HTTP headers.
     *
     * \public
     */
    function passthrough()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::passthrough()" );

        $path = $this->metaData['name'];
        $size = $this->metaData['size'];
        $mimeType = $this->metaData['datatype'];
        $mtime = $this->metaData['mtime'];
        $mdate = gmdate( 'D, d M Y H:i:s T', $mtime );
        //$hash = $this->metaData['name_hash'];

        //header( "Pragma: " );
        //header( "Cache-Control: " );
        /* Set cache time out to 10 minutes, this should be good enough to work around an IE bug */
        //header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 600) . 'GMT');
        //header( "ETag: \"$hash\"" );
        header( "Content-Length: $size" );
        header( "Content-Type: $mimeType" );
        header( "Last-Modified: $mdate" );
        header( "Connection: close" );
        header( "X-Powered-By: eZ publish" );
        header( "Accept-Ranges: bytes" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $dbfile->passThru( $path );
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
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileCopy( '$srcPath', '$dstPath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $dbfile->copy( $srcPath, $dstPath );
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
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileLinkCopy( '$srcPath', '$dstPath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        // FIXME: implement real link creation here.
        $dbfile = new eZDBFile();
        $dbfile->copy( $srcPath, $dstPath );
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
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileMove( '$srcPath', '$dstPath' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $dbfile = new eZDBFile();
        $dbfile->rename( $srcPath, $dstPath );
        eZDebug::accumulatorStop( 'dbfile' );
    }


    var $metaData = null;
}

?>

<?php
//
// Definition of eZDBFileHandler class
//
// Created on: <19-Apr-2006 16:01:30 vs>
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

/*! \file ezdbfilehandler.php
*/

require_once( 'lib/ezutils/classes/ezdebugsetting.php' );
require_once( 'lib/ezutils/classes/ezdebug.php' );

class eZDBFileHandler
{
    /**
     * Constructor.
     *
     * $filePath File path. If specified, file metadata is fetched in the constructor.
     */
    function eZDBFileHandler( $filePath = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::ctor( '$filePath' )" );

        // Init backend.
        if ( !isset( $GLOBALS['eZDBFileHandler_chosen_backend_class'] ) )
        {
            require_once( 'lib/ezutils/classes/ezini.php' );
            $fileINI = eZINI::instance( 'file.ini' );
            $backendName = 'mysql';
            if ( $fileINI->hasVariable( 'ClusteringSettings', 'DBBackend' ) )
                $backendName = $fileINI->variable( 'ClusteringSettings', 'DBBackend' );

            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $searchPathArray = eZClusterFileHandler::searchPathArray();

            foreach ( $searchPathArray as $searchPath )
            {
                $includeFileName = "$searchPath/dbbackends/$backendName.php";
                if ( is_readable( $includeFileName ) )
                {
                    include_once( $includeFileName );
                    $backendClassName = "eZDBFileHandler${backendName}Backend";
                    $GLOBALS['eZDBFileHandler_chosen_backend_class'] = $backendClassName;
                }
            }

            if ( !isset( $GLOBALS['eZDBFileHandler_chosen_backend_class'] ) )
            {
                eZDebug::writeError( "Cannot find ezdb cluster file backend: '$backendName'" );
                return;
            }
        }

        $backendClassName = $GLOBALS['eZDBFileHandler_chosen_backend_class'];
        $this->backend = new $backendClassName;
        $this->backend->_connect();
        $this->metaData['name'] = $filePath;

        $this->loadMetaData();
    }

    /*!
     \public
     Load file meta information.

     \param $force File stats will be refreshed if true
    */
    function loadMetaData( $force = false )
    {
        // Fetch metadata.
        if ( $this->metaData['name'] !== false )
        {
            $metaData = $this->backend->_fetchMetadata( $this->metaData['name'] );
            if ( $metaData )
                $this->metaData = $metaData;
        }
    }

    /**
     * \public
     * \static
     * \param $filePath Path to the file being stored.
     * \param $scope    Means something like "file category". May be used to clean caches of a certain type.
     * \param $delete   true if the file should be deleted after storing.
     */
    function fileStore( $filePath, $scope = false, $delete = false, $datatype = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileStore( '$filePath' )" );

        if ( $scope === false )
            $scope = 'UNKNOWN_SCOPE';

        if ( $datatype === false )
            $datatype = 'misc';

        $this->backend->_store( $filePath, $datatype, $scope );

        if ( $delete )
            @unlink( $filePath );
    }

    /**
     * Store file contents.
     *
     * \public
     * \static
     */
    function fileStoreContents( $filePath, $contents, $scope = false, $datatype = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileStoreContents( '$filePath' )" );

        if ( $scope === false )
            $scope = 'UNKNOWN_SCOPE';

        if ( $datatype === false )
            $datatype = 'misc';

        $this->backend->_storeContents( $filePath, $contents, $scope, $datatype );
    }

    /**
     * Store file contents.
     *
     * \public
     */
    function storeContents( $contents, $scope = false, $datatype = false )
    {
        if ( $scope === false )
            $scope = 'UNKNOWN_SCOPE';

        if ( $datatype === false )
            $datatype = 'misc';

        $filePath = $this->metaData['name'];
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::storeContents( '$filePath' )" );
        $this->backend->_storeContents( $filePath, $contents, $scope, $datatype );
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

        return $this->backend->_fetch( $filePath );
    }

    /**
     * Fetches file from db and saves it in FS under unique name.
     *
     * \public
     * \static
     * \return filename with path of a saved file. You can use this filename to get contents of file from filesystem.
     */
    function fetchUnique( )
    {
        $filePath = $this->metaData['name'];
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fetchUnique( '$filePath' )" );

        $fetchWithUniqueName = true;
        $fetchedFilePath = $this->backend->_fetch( $filePath, $fetchWithUniqueName );
        $this->metaData['unique_name'] = $fetchedFilePath;
        return $fetchedFilePath;
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
        $this->backend->_fetch( $filePath );
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

        $contents = $this->backend->_fetchContents( $filePath );
        return $contents;
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
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileFetchContents( '$filePath' )" );
        $contents = $this->backend->_fetchContents( $filePath );
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
     * Returns file name.
     *
     * \public
     */
    function name()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::name()" );

        return isset( $this->metaData['name'] ) ? $this->metaData['name'] : null;
    }

    /**
     * \public
     * \static
     * \sa fileDeleteByWildcard()
     */
    function fileDeleteByRegex( $dir, $fileRegex )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileDeleteByRegex( '$dir', '$fileRegex' )" );

        $regex = '^' . ( $dir ? $dir . '/' : '' ) . $fileRegex;
        $this->backend->_deleteByRegex( $regex );
    }

    /**
     * \public
     * \static
     * \sa fileDeleteByRegex()
     */
    function fileDeleteByWildcard( $wildcard )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileDeleteByWildcard( '$wildcard' )" );

        $this->backend->_deleteByWildcard( $wildcard );
    }

    /**
     * \public
     * \static
     */
    function fileDeleteByDirList( $dirList, $commonPath, $commonSuffix )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileDeleteByDirList( '$dirList', '$commonPath', '$commonSuffix' )" );

        $this->backend->_deleteByDirList( $dirList, $commonPath, $commonSuffix );
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

        $this->backend->_delete( $path );
        $this->backend->_deleteByLike( $path . '/%' );
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

        $this->backend->_delete( $path );
        $this->backend->_deleteByRegex( $path . '/.+$' );

        // FIXME: update $this->metaData after moving.
    }

    /**
     * Deletes a file that has been fetched before.
     *
     * \public
     * \static
     */
    function fileDeleteLocal( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileDeleteLocal( '$path' )" );

        @unlink( $path );
    }

    /**
     * Deletes a file that has been fetched before.
     *
     * \public
     */
    function deleteLocal()
    {
        $path = $this->metaData['name'];
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::deleteLocal( '$path' )" );
        @unlink( $path );
    }

    /**
     * Check if given file/dir exists.
     *
     * \public
     * \static
     */
    function fileExists( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileExists( '$path' )" );

        $rc = $this->backend->_exists( $path );
        return $rc;
    }

    /**
     * Check if given file/dir exists.
     *
     * NOTE: this function does not interact with database.
     * Instead, it just returns existance status determined in the constructor.
     *
     * \public
     */
    function exists()
    {
        $path = $this->metaData['name'];
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::exists( '$path' )" );
        $rc = $this->backend->_exists( $path );
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
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::passthrough( '$path' )" );
        $size = $this->metaData['size'];
        $mimeType = $this->metaData['datatype'];
        $mtime = $this->metaData['mtime'];
        $mdate = gmdate( 'D, d M Y H:i:s T', $mtime );

        header( "Content-Length: $size" );
        header( "Content-Type: $mimeType" );
        header( "Last-Modified: $mdate GMT" );
        header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 6000) . 'GMT');
        header( "Connection: close" );
        header( "X-Powered-By: eZ publish" );
        header( "Accept-Ranges: bytes" );

        $this->backend->_passThrough( $path );
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

        $this->backend->_copy( $srcPath, $dstPath );
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

        $this->backend->_linkCopy( $srcPath, $dstPath );
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

        $this->backend->_rename( $srcPath, $dstPath );

        // FIXME: update $this->metaData after moving.
    }

    /**
     * Move file.
     *
     * \public
     */
    function move( $dstPath )
    {
        $srcPath = $this->metaData['name'];

        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileMove( '$srcPath', '$dstPath' )" );

        $this->backend->_rename( $srcPath, $dstPath );

        // FIXME: update $this->metaData after moving.
    }

    /**
     * Get list of files stored in database.
     *
     * Used in bin/php/clusterize.php.
     */
    function getFileList( $skipBinaryFiles = false, $skipImages = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering',
                                    sprintf( "db::getFileList( %d, %d )",
                                              (int) $skipBinaryFiles, (int) $skipImages ) );
        return $this->backend->_getFileList( $skipBinaryFiles, $skipImages );
    }
}

?>

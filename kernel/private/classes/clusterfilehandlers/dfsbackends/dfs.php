<?php
//
// Definition of eZDFSFileHandlerMySQLBackend class
//
// Created on: <19-Apr-2006 16:15:17 bd>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.2.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

class eZDFSFileHandlerDFSBackend
{
    public function __construct()
    {
        $mountPointPath = eZINI::instance( 'file.ini' )->variable( 'eZDFSClusteringSettings', 'MountPointPath' );

        if ( !$mountPointPath = realpath( $mountPointPath ) )
            throw new eZDFSFileHandlerNFSMountPointNotFoundException( $mountPointPath );

        if ( !is_writeable( $mountPointPath ) )
            throw new eZDFSFileHandlerNFSMountPointNotWriteableException( $mountPointPath );

        if ( substr( $mountPointPath, -1 ) != '/' )
            $mountPointPath = "$mountPointPath/";

        $this->mountPointPath = $mountPointPath;
    }

    /**
     * Creates a copy of $srcFilePath from DFS to $dstFilePath on DFS
     *
     * @param string $srcFilePath Local source file path
     * @param string $dstFilePath Local destination file path
     **/
    public function copyFromDFSToDFS( $srcFilePath, $dstFilePath )
    {
        $srcFilePath = $this->makeDFSPath( $srcFilePath );
        $dstFilePath = $this->makeDFSPath( $dstFilePath );
        if ( file_exists( dirname( $dstFilePath ) ) )
        {
            $ret = copy( $srcFilePath, $dstFilePath );
        }
        else
        {
            $ret = eZFile::create( basename( $dstFilePath ), dirname( $dstFilePath ), file_get_contents( $srcFilePath ), false );
        }
        return $ret;
    }

    /**
     * Copies the DFS file $srcFilePath to FS
     * @param string $srcFilePath Source file path (on DFS)
     * @param string $dstFilePath
     *        Destination file path (on FS). If not specified, $srcFilePath is
     *        used
     * @return bool
     **/
    public function copyFromDFS( $srcFilePath, $dstFilePath = false )
    {
        if ( $dstFilePath === false )
        {
            $dstFilePath = $srcFilePath;
        }
        $srcFilePath = $this->makeDFSPath( $srcFilePath );

        if ( file_exists( dirname( $dstFilePath ) ) )
            $ret = copy( $srcFilePath, $dstFilePath );
        else
            $ret = eZFile::create( basename( $dstFilePath ), dirname( $dstFilePath ), file_get_contents( $srcFilePath ) );
        return $ret;
    }

    /**
     * Copies the local file $filePath to DFS under the same name, or a new name
     * if specified
     *
     * @param string $srcFilePath Local file path to copy from
     * @param string $dstFilePath
     *        Optional path to copy to. If not specified, $srcFilePath is used
     **/
    public function copyToDFS( $srcFilePath, $dstFilePath = false )
    {
        if ( $dstFilePath === false )
        {
            $dstFilePath = $srcFilePath;
        }
        $dstFilePath = $this->makeDFSPath( $dstFilePath );
        return eZFile::create( basename( $dstFilePath ), dirname( $dstFilePath ), file_get_contents( $srcFilePath ), true );
    }

    /**
     * Deletes one or more files from DFS
     *
     * @param string|array $filePath
     *        Single local filename, or array of local filenames
     * @return bool true if deletion was successful, false otherwise
     * @todo Improve error handling using exceptions
     */
    public function delete( $filePath )
    {
        if ( is_array( $filePath ) )
        {
            $ret = true;
            foreach( $filePath as $file )
            {
                $locRet = @unlink( $this->makeDFSPath( $file ) );
                $ret = $ret and $locRet;
            }
        }
        else
        {
            $ret = @unlink( $this->makeDFSPath( $filePath ) );
        }
    }

    /**
     * Sends the contents of $filePath to default output
     *
     * @param string $filePath
     * @return bool true, or false if operation failed
     */
    public function passthrough( $filePath )
    {
        $filePath = $this->makeDFSPath( $filePath );
        if ( !$fp = @fopen( $filePath, 'rb' ) )
        {
            return false;
        }
        else
        {
            // @todo Optimize this by making $length dependant on the filesize
            while ( $data = fgets( $fp ) )
            {
                echo $data;
            }
            fclose( $fp );
        }
        return true;
    }

    /**
     * Returns the binary content of $filePath from DFS
     *
     * @param string $filePath local file path
     * @return binary|bool file's content, or false
     * @todo Handle errors using exceptions
     */
    public function getContents( $filePath )
    {
        return @file_get_contents( $this->makeDFSPath( $filePath ) );
    }

    /**
     * Creates the file $filePath on DFS with content $contents
     *
     * @param string $filePath
     * @param binary $contents
     *
     * @return bool
     **/
    public function createFileOnDFS( $filePath, $contents )
    {
        $filePath = $this->makeDFSPath( $filePath );
        return eZFile::create( basename( $filePath ), dirname( $filePath ), $contents, false );
    }

    /**
     * Renamed DFS file $oldPath to DFS file $newPath
     *
     * @param string $oldPath
     * @param string $newPath
     * @return bool
     */
    public function renameOnDFS( $oldPath, $newPath )
    {
        $oldPath = $this->makeDFSPath( $oldPath );
        $newPath = $this->makeDFSPath( $newPath );

        return rename( $oldPath, $newPath );
    }

    /**
     * Computes the DFS file path based on a relative file path
     * @param string $filePath
     * @return string the absolute DFS file path
     **/
    protected function makeDFSPath( $filePath )
    {
        return $this->mountPointPath . $filePath;
    }

    /**
     * Path to the local distributed filesystem mount point
     * @var string
     **/
    protected $mountPointPath;
}
?>
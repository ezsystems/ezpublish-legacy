<?php
/**
 * File containing the eZDFSFileHandlerDFSBackend class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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
     */
    public function copyFromDFSToDFS( $srcFilePath, $dstFilePath )
    {
        $this->accumulatorStart();

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

        $this->accumulatorStop();

        return $ret;
    }

    /**
     * Copies the DFS file $srcFilePath to FS
     * @param string $srcFilePath Source file path (on DFS)
     * @param string $dstFilePath
     *        Destination file path (on FS). If not specified, $srcFilePath is
     *        used
     * @return bool
     */
    public function copyFromDFS( $srcFilePath, $dstFilePath = false )
    {
        $this->accumulatorStart();

        if ( $dstFilePath === false )
        {
            $dstFilePath = $srcFilePath;
        }
        $srcFilePath = $this->makeDFSPath( $srcFilePath );

        if ( file_exists( dirname( $dstFilePath ) ) )
            $ret = copy( $srcFilePath, $dstFilePath );
        else
            $ret = eZFile::create( basename( $dstFilePath ), dirname( $dstFilePath ), file_get_contents( $srcFilePath ) );

        $this->accumulatorStop();

        return $ret;
    }

    /**
     * Copies the local file $filePath to DFS under the same name, or a new name
     * if specified
     *
     * @param string $srcFilePath Local file path to copy from
     * @param string $dstFilePath
     *        Optional path to copy to. If not specified, $srcFilePath is used
     */
    public function copyToDFS( $srcFilePath, $dstFilePath = false )
    {
        $this->accumulatorStart();

        if ( $dstFilePath === false )
        {
            $dstFilePath = $srcFilePath;
        }
        $dstFilePath = $this->makeDFSPath( $dstFilePath );
        $ret = eZFile::create( basename( $dstFilePath ), dirname( $dstFilePath ), file_get_contents( $srcFilePath ), true );

        $this->accumulatorStop();

        return $ret;
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
        $this->accumulatorStart();

        if ( is_array( $filePath ) )
        {
            $ret = true;
            foreach( $filePath as $file )
            {
                $dfsPath = $this->makeDFSPath( $file );
                $locRet = @unlink( $dfsPath );
                $ret = $ret and $locRet;

                if ( $locRet )
                    eZClusterFileHandler::cleanupEmptyDirectories( $dfsPath );
            }
        }
        else
        {
            $dfsPath = $this->makeDFSPath( $filePath );
            $ret = @unlink( $dfsPath );

            if ( $ret )
                eZClusterFileHandler::cleanupEmptyDirectories( $dfsPath );
        }

        $this->accumulatorStop();

        return $ret;
    }

    /**
     * Sends the contents of $filePath to default output
     *
     * @param string $filePath File path
     * @param int $startOffset Starting offset
     * @param false|int $length Length to transmit, false means everything
     * @return bool true, or false if operation failed
     */
    public function passthrough( $filePath, $startOffset = 0, $length = false )
    {
        return eZFile::downloadContent(
            $this->makeDFSPath( $filePath ),
            $startOffset,
            $length
        );
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
        $this->accumulatorStart();

        // @todo Throw an exception if it fails
        //       (FileNotFound, or FileNotReadable, depends on testing)
        $ret = @file_get_contents( $this->makeDFSPath( $filePath ) );

        $this->accumulatorStop();

        return $ret;
    }

    /**
     * Creates the file $filePath on DFS with content $contents
     *
     * @param string $filePath
     * @param binary $contents
     *
     * @return bool
     */
    public function createFileOnDFS( $filePath, $contents )
    {
        $this->accumulatorStart();

        $filePath = $this->makeDFSPath( $filePath );
        $ret = eZFile::create( basename( $filePath ), dirname( $filePath ), $contents, false );

        $this->accumulatorStop();

        return $ret;
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
        $this->accumulatorStart();

        $oldPath = $this->makeDFSPath( $oldPath );
        $newPath = $this->makeDFSPath( $newPath );

        $ret = eZFile::rename( $oldPath, $newPath, true );

        if ( $ret )
            eZClusterFileHandler::cleanupEmptyDirectories( $oldPath );

        $this->accumulatorStop();

        return $ret;
    }

    /**
     * Checks if a file exists on the DFS
     *
     * @param string $filePath
     * @return bool
     */
    public function existsOnDFS( $filePath )
    {
        return file_exists( $this->makeDFSPath( $filePath ) );
    }

    /**
     * Returns the mount point
     *
     * @return string
     */
    public function getMountPoint()
    {
        return $this->mountPointPath;
    }

    /**
     * Computes the DFS file path based on a relative file path
     * @param string $filePath
     * @return string the absolute DFS file path
     */
    protected function makeDFSPath( $filePath )
    {
        return $this->mountPointPath . $filePath;
    }

    protected function accumulatorStart()
    {
        eZDebug::accumulatorStart( 'mysql_cluster_dfs_operations', 'MySQL Cluster', 'DFS operations' );
    }

    protected function accumulatorStop()
    {
        eZDebug::accumulatorStop( 'mysql_cluster_dfs_operations' );
    }

    /**
     * Path to the local distributed filesystem mount point
     * @var string
     */
    protected $mountPointPath;
}
?>

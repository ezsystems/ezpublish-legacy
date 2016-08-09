<?php
/**
 * File containing the eZDFSFileHandlerDFSBackend class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class eZDFSFileHandlerDFSBackend implements eZDFSFileHandlerDFSBackendInterface
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

        $this->filePermissionMask = octdec( eZINI::instance()->variable( 'FileSettings', 'StorageFilePermissions' ) );
    }

    /**
     * The legacy handler supports any type of file
     */
    public function supports( $path )
    {
        return true;
    }

    /**
     * Creates a copy of $srcFilePath from DFS to $dstFilePath on DFS
     *
     * @param string $srcFilePath Local source file path
     * @param string $dstFilePath Local destination file path
     *
     * @return bool
     */
    public function copyFromDFSToDFS( $srcFilePath, $dstFilePath )
    {
        $this->accumulatorStart();

        $srcFilePath = $this->makeDFSPath( $srcFilePath );
        $dstFilePath = $this->makeDFSPath( $dstFilePath );
        if ( file_exists( dirname( $dstFilePath ) ) )
        {
            $ret = copy( $srcFilePath, $dstFilePath );
            if ( $ret )
                $this->fixPermissions( $dstFilePath );
        }
        else
        {
            $ret = $this->createFile( $dstFilePath, fopen( $srcFilePath, 'rb' ), false );
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
        {
            $ret = copy( $srcFilePath, $dstFilePath );
            if ( $ret )
                $this->fixPermissions( $dstFilePath );
        }
        else
        {
            $ret = $this->createFile( $dstFilePath, fopen( $srcFilePath, 'rb' ) );
        }

        if ( $ret )
        {
            $ret = $this->copyTimestamp( $srcFilePath, $dstFilePath );
        }

        if ( !$ret && file_exists( $dstFilePath ) )
        {
            unlink( $dstFilePath );
        }

        $this->accumulatorStop();

        return $ret;
    }

    /**
     * Copies the local file $filePath to DFS under the same name, or a new name
     * if specified
     *
     * @param string $srcFilePath Local file path to copy from
     * @param bool|string $dstFilePath
     *        Optional path to copy to. If not specified, $srcFilePath is used
     * @return bool
     */
    public function copyToDFS( $srcFilePath, $dstFilePath = false )
    {
        $this->accumulatorStart();

        $srcFileSize = filesize( $srcFilePath );
        $srcFileHandle = fopen( $srcFilePath, 'rb' );
        if ( $srcFileHandle === false )
        {
            $this->accumulatorStop();
            eZDebug::writeError( "Error getting contents of file 'FS://$srcFilePath'.", __METHOD__ );
            return false;
        }

        if ( $dstFilePath === false )
        {
            $dstFilePath = $srcFilePath;
        }

        $dstFilePath = $this->makeDFSPath( $dstFilePath );
        $ret = $this->createFile( $dstFilePath, $srcFileHandle, true );
        if ( $ret )
        {
            // Double checking if the file has been correctly created
            clearstatcache( true, $dstFilePath );
            $dstFileSize = filesize( $dstFilePath );
            if ( $dstFileSize != $srcFileSize )
            {
                eZDebug::writeError( "Size ($dstFileSize) of written data for file FS://$dstFilePath does not match expected size of original DFS file ($srcFileSize)", __METHOD__ );
                $ret = false;
            }
        }

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
     * @param bool|int $length Length to transmit, false means everything
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
        $ret = $this->createFile( $filePath, $contents, false );

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
        if ( file_exists( $this->makeDFSPath( $filePath ) ) )
        {
            return true;
        }

        // Verify that mount point is still there
        $filePathDir = substr( $filePath, 0, strpos( $filePath, '/' ) + 1 );
        $path = realpath( $this->getMountPoint() ). '/' . $filePathDir;
        if ( !file_exists( $path ) || !is_dir( $path ) )
        {
            throw new eZDFSFileHandlerBackendException( "NFS mount root $path not found" );
        }

        return false;
    }

    /**
     * Returns the mount point
     *
     * @return string
     */
    protected function getMountPoint()
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

    protected function fixPermissions( $filePath )
    {
        if ( ( defined('EZP_USE_FILE_PERMISSIONS') ? EZP_USE_FILE_PERMISSIONS : true ) &&
             eZINI::instance()->variable( 'FileSettings', 'ControlFilePermissions' ) !== 'false' && $this->filePermissionMask ) {
            @chmod( $filePath, $this->filePermissionMask );
        }
    }

    /**
     * copies the timestamp from the original file to the destination file
     * @param string $srcFilePath
     * @param string $dstFilePath
     * @return bool False if the timestamp is not set with success on target file
     */
    protected function copyTimestamp( $srcFilePath, $dstFilePath )
    {
        clearstatcache( true, $srcFilePath );
        $originalTimestamp = filemtime( $srcFilePath );
        if ( !$originalTimestamp )
        {
            return false;
        }

        return touch( $dstFilePath, $originalTimestamp);
    }

    protected function createFile( $filePath, $contents, $atomic = true )
    {
        // $contents can result from a failed file_get_contents(). In this case
        if ( $contents === false )
            return false;

        $createResult = eZFile::create( basename( $filePath ), dirname( $filePath ), $contents, $atomic );

        if ( $createResult )
            $this->fixPermissions( $filePath );

        return $createResult;
    }

    /**
     * Returns size of a file in the DFS backend, from a relative path.
     *
     * @param string $filePath The relative file path we want to get size of
     * @return int
     */
    public function getDfsFileSize( $filePath )
    {
        return filesize( $this->makeDFSPath( $filePath ) );
    }

    /**
     * Returns an iterator over the files within $basePath on the backend
     *
     * @param string $basePath a path relative to the mount point
     *
     * @return Iterator An iterator that returns a DFS File pathname as the value
     */
    public function getFilesList( $basePath )
    {
        // The custom iterator filters out the file path in order to get a relative one
        return new eZDFSFileHandlerDFSBackendFilterIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $this->mountPointPath . '/' . $basePath,
                    FilesystemIterator::SKIP_DOTS|FilesystemIterator::UNIX_PATHS
                )
            ),
            $this->mountPointPath
        );
    }

    /**
     * No transformation is required since files are served from the same host
     */
    public function applyServerUri( $filePath )
    {
        return $filePath;
    }

    /**
     * Path to the local distributed filesystem mount point
     * @var string
     */
    protected $mountPointPath;

    /**
     * Permission mask that must be applied to created files
     * @var int
     */
    private $filePermissionMask;
}
?>

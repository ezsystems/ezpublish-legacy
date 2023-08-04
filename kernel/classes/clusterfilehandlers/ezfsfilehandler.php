<?php
/**
 * File containing the eZFSFileHandler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class eZFSFileHandler implements eZClusterFileHandlerInterface
{
    const EXPIRY_TIMESTAMP = 233366400;

    /**
     * Constructor.
     *
     * @param bool $filePath File path. If specified, file metadata is fetched in the constructor.
     */
    public function __construct( $filePath = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::instance( '$filePath' )", __METHOD__ );
        $this->Mutex = null;
        $this->filePath = $filePath;
        $this->lifetime = 60; // Lifetime of lock
        $this->loadMetaData();
    }

    /*!
     \private
     Acquires an exclusive lock to the current file by using eZMutex.

     If a lock is already present it will sleep 0.5 seconds and try again until
     the lock lifetime is exceeded and the lock is stolen.

     Note: Lock stealing might be removed.

     \param $fname Name of the calling code (usually function name).
     */
    function _exclusiveLock( $fname = false )
    {
        $mutex =& $this->_mutex();
        while ( true )
        {
            $timestamp  = $mutex->lockTS(); // Note: This does not lock, only checks what the timestamp is.
            if ( $timestamp === false )
            {
                if ( !$mutex->lock() )
                {
                    eZDebug::writeWarning( "Failed to acquire lock for file " . $this->filePath );
                    return false;
                }
                $mutex->setMeta( 'pid', getmypid() );
                return true;
            }
            if ( $timestamp >= time() - $this->lifetime )
            {
                usleep( 500000 ); // Sleep 0.5 second
                continue;
            }

            $oldPid = $mutex->meta( 'pid' );
            if ( is_numeric( $oldPid ) &&
                 $oldPid != 0 &&
                 function_exists( 'posix_kill' ) )
            {
                posix_kill( $oldPid, 9 );
            }
            if ( !$mutex->steal() )
            {
                eZDebug::writeWarning( "Failed to steal lock for file " . $this->filePath . " from PID $oldPid", __METHOD__ );
                return false;
            }
            $mutex->setMeta( 'pid', getmypid() );
            return true;
        }
    }

    /*!
     \private
     Frees the current exclusive lock in use.

     \param $fname Name of the calling code (usually function name).
     */
    function _freeExclusiveLock( $fname = false )
    {
        $mutex =& $this->_mutex();
        $mutex->unlock();
    }

    /*!
     \private
     Returns the mutex object for the current file.
     */
    function &_mutex()
    {
        if ( $this->Mutex !== null )
            return $this->Mutex;
        $mutex = new eZMutex( $this->filePath );
        return $mutex;
    }

    /*!
     \public
     Load file meta information.

     \param $force If true, file stats will be refreshed
    */
    function loadMetaData( $force = false )
    {
        if ( $this->filePath !== false )
        {
            eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
            if ( $force )
            {
                clearstatcache();
                eZDebugSetting::writeDebug( 'kernel-clustering', ' clearstatcache called on ' . $this->filePath, __METHOD__ );
            }

            $this->metaData = file_exists( $this->filePath ) ? stat( $this->filePath ) : false;
            eZDebug::accumulatorStop( 'dbfile' );
        }
    }

    /**
     * Fetches file from db and saves it in FS under the same name.
     *
     * In case of fetching from filesystem does nothing.
     */
    function fileFetch( $filePath )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileFetch( '$filePath' )", __METHOD__ );
        return ( file_exists( $filePath ) ? $filePath : false );
    }

    /**
     * Fetches file from db and saves it in FS under the same name.
     *
     * In case of fetching from filesystem does nothing.
     */
    function fetch( $noLocalCache = false )
    {
        $filePath = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fetch( '$filePath' )", __METHOD__ );
    }

    /**
     * Fetches file from db and saves it in FS under unique name.
     *
     * In case of fetching from filesystem, does nothing
     *
     * @return string The unique file path. on FS, the file path.
     */
    function fetchUnique()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fetchUnique( '{$this->filePath}' )", __METHOD__ );
        return $this->filePath;
    }

    /**
     * Store file.
     *
     * In case of storing to filesystem does nothing.
     *
     * @param string $filePath Path to the file being stored.
     * @param string $scope    Means something like "file category". May be used to clean caches of a certain type.
     * @param string $delete   true if the file should be deleted after storing.
     */
    function fileStore( $filePath, $scope = false, $delete = false, $datatype = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileStore( '$filePath' )", __METHOD__ );
    }

    /**
     * Store file contents.
     *
     * \public
     * \static
     */
    function fileStoreContents( $filePath, $contents, $scope = false, $datatype = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileStoreContents( '$filePath' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        eZFile::create( basename( $filePath ), dirname( $filePath ), $contents, true );

        $perm = eZINI::instance()->variable( 'FileSettings', 'StorageFilePermissions' );
        chmod( $filePath, octdec( $perm ) );

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Store file contents to disk
     *
     * @param string $contents Binary file data
     * @param string $datatype Not used in the FS handler
     * @param string $scope Not used in the FS handler
     * @param bool $storeLocally Not used in the FS handler
     *
     * @return void
     */
    function storeContents( $contents, $scope = false, $datatype = false, $storeLocally = false )
    {
        $filePath = $this->filePath;

        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::storeContents( '$filePath' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        eZFile::create( basename( $filePath ), dirname( $filePath ), $contents, true );
        $perm = eZINI::instance()->variable( 'FileSettings', 'StorageFilePermissions' );
        chmod( $filePath, octdec( $perm ) );

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Returns file contents.
     *
     * @return string|false contents string, or false in case of an error.
     */
    function fileFetchContents( $filePath )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileFetchContents( '$filePath' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $rslt = is_readable( $filePath ) ? file_get_contents( $filePath ) : false;
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
        $filePath = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fetchContents( '$filePath' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $rslt = is_readable( $filePath ) ? file_get_contents( $filePath ) : false;
        eZDebug::accumulatorStop( 'dbfile' );

        return $rslt;
    }

    /*!
     Creates a single transaction out of the typical file operations for accessing caches.
     Caches are normally ready from the database or local file, if the entry does not exist
     or is expired then it generates the new cache data and stores it.
     This method takes care of these operations and handles the custom code by performing
     callbacks when needed.

     The $retrieveCallback is used when the file contents can be used (ie. not re-generation) and
     is called when the file is ready locally.
     The function will be called with the file path as the first parameter, the mtime as the second
     and optionally $extraData as the third.
     The function must return the file contents or an instance of eZClusterFileFailure which can
     be used to tell the system that the retrieve data cannot be used after all.
     $retrieveCallback can be set to null which makes the system go directly to the generation.

     The $generateCallback is used when the file content is expired or does not exist, in this
     case the content must be re-generated and stored.
     The function will be called with the file path as the first parameter and optionally $extraData
     as the second.
     The function must return an array with information on the contents, the array consists of:
     - scope    - The current scope of the file, is optional.
     - datatype - The current datatype of the file, is optional.
     - content  - The file content, this can be any type except null.
     - binarydata - The binary data which is written to the file.
     - store      - Whether *content* or *binarydata* should be stored to the file, if false it will simply return the data. Optional, by default it is true.
     Note: Set $generateCallback to false to disable generation callback.
     Note: Set $generateCallback to null to tell the function to perform a write lock but not do any generation, the generation must done be done by the caller by calling storeCache().

     Either *content* or *binarydata* must be supplied, if not an error is issued and it returns null.
     If *content* is set it will be used as the return value of this function, if not it will return the binary data.
     If *binarydata* is set it will be used as the binary data for the file, if not it will perform a var_export on *content* and use that as the binary data.

     For convenience the $generateCallback function can return a string which will be considered as the
     binary data for the file and returned as the content.

     For controlling how long a cache entry can be used the parameters $expiry and $ttl is used.
     $expiry can be set to a timestamp which controls the absolute max time for the cache, after this
     time/date the cache will never be used. If the value is set to a negative value or null there the
     expiration check is disabled.

     $ttl (time to live) tells how many seconds the cache can live from the time it was stored. If the
     value is set to negative or null there is no limit for the lifetime of the cache. A value of 0 means
     that the cache will always expire and practically disables caching.
     For the cache to be used both the $expiry and $ttl check must hold.
     */
    function processCache( $retrieveCallback, $generateCallback = null, $ttl = null, $expiry = null, $extraData = null )
    {
        $fname = $this->filePath;
        $args = array( $fname );
        if ( $extraData !== null )
            $args[] = $extraData;
        $curtime   = time();
        $tries     = 0;
        $noCache   = false;

        if ( $expiry < 0 )
            $expiry = null;
        if ( $ttl < 0 )
            $ttl = null;

        while ( true )
        {
            $forceGeneration = false;
            $storeCache      = true;
            $mtime = file_exists( $fname ) ? filemtime( $fname ) : false;
            if ( $retrieveCallback !== null && !$this->isExpired( $expiry, $curtime, $ttl ) )
            {
                $args = array( $fname, $mtime );
                if ( $extraData !== null )
                    $args[] = $extraData;
                $retval = call_user_func_array( $retrieveCallback, $args );
                if ( !( $retval instanceof eZClusterFileFailure ) )
                {
                    return $retval;
                }
                $forceGeneration = true;
            }

            if ( $tries >= 2 )
            {
                eZDebugSetting::writeDebug( 'kernel-clustering', "Reading was retried $tries times and reached the maximum, forcing generation", __METHOD__ );
                $forceGeneration = true; // We will now generate the cache but not store it
                $storeCache = false; // This disables the cache storage
            }

            // Generation part starts here
            if ( isset( $retval ) &&
                 $retval instanceof eZClusterFileFailure )
            {
                // This error means that the retrieve callback told
                // us NOT to enter generation mode and therefore NOT to store this
                // cache.
                // This parameter will then be passed to the generate callback,
                // and this will set store to false
                if ( $retval->errno() == 3 )
                {
                    $noCache = true;
                }
                elseif ( $retval->errno() != 1 ) // check for non-expiry error codes
                {
                    eZDebug::writeError( "Failed to retrieve data from callback", __METHOD__ );
                    return null;
                }
                $message = $retval->message();
                if ( strlen( $message ) > 0 )
                {
                    eZDebugSetting::writeDebug( 'kernel-clustering', $retval->message(), __METHOD__ );
                }
                // the retrieved data was expired so we need to generate it, let's continue
            }

            // We need to lock if we have a generate-callback or
            // the generation is deferred to the caller.
            // Note: false means no generation
            if ( !$noCache and $generateCallback !== false and $forceGeneration === false )
            {
                // Lock the entry for exclusive access, if the entry does not exist
                // it will be inserted with mtime=-1
                if ( !$this->_exclusiveLock( $fname ) )
                {
                    // Cannot get exclusive lock, so return null.
                    return null;
                }

                // This is where we perform a two-phase commit. If any other
                // process or machine has generated the file data and it is valid
                // we will retry the retrieval part and not do the generation.
                clearstatcache();
                eZDebugSetting::writeDebug( 'kernel-clustering', "clearstatcache called on $fname", __METHOD__ );
                if ( file_exists( $fname ) && !$this->isExpired( $expiry, $curtime, $ttl ) )
                {
                    eZDebugSetting::writeDebug( 'kernel-clustering', "File was generated while we were locked, use that instead", __METHOD__ );
                    $this->metaData = false;
                    $this->_freeExclusiveLock( 'storeCache' );
                    ++$tries;
                    continue; // retry reading file
                }
            }

            // File in DB is outdated or non-existing, call write-callback to generate content
            if ( $generateCallback )
            {
                $args = array( $fname );
                if ( $noCache )
                    $extraData['noCache'] = true;
                if ( $extraData !== null )
                    $args[] = $extraData;
                $fileData = call_user_func_array( $generateCallback, $args );
                return $this->storeCache( $fileData, $storeCache );
            }

            break;
        }

        return new eZClusterFileFailure( 2, "Manual generation of file data is required, calling storeCache is required" );
    }

    /**
     * Calculates if the file data is expired or not.
     *
     * @param string $fname Name of file, available for easy debugging.
     * @param int    $mtime Modification time of file, can be set to false if file does not exist.
     * @param int    $expiry Time when file is to be expired, a value of -1 will disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to disable TTL.
     * @return bool
     */
    public static function isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl )
    {
        if ( $mtime == false )
        {
            $ret = true;
        }
        elseif ( $mtime == self::EXPIRY_TIMESTAMP )
        {
            $ret = true;
        }
        elseif ( $expiry != -1 and $ttl === null )
        {
            $ret = $mtime < $expiry;
        }
        else
        {
            $ret = $mtime < max( $expiry, $curtime - $ttl );
        }
        return $ret;
    }


    /**
     * Calculates if the current file data is expired or not.
     *
     * @param int    $expiry Time when file is to be expired, a value of -1 will disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to disable TTL.
     * @return bool
     */
    public function isExpired( $expiry, $curtime, $ttl )
    {
        if ( !file_exists( $this->filePath ) )
        {
            return true;
        }

        return self::isFileExpired( $this->filePath, filemtime( $this->filePath ), $expiry, $curtime, $ttl );
    }

    /*!
     \private
     Stores the data in $fileData to the remote and local file and commits the transaction.

     The parameter $fileData must contain the same as information as the $generateCallback returns as explained in processCache().

     \note This method is just a continuation of the code in processCache() and is not meant to be called alone since it relies on specific state in the database.
     */
    function storeCache( $fileData, $storeCache = true )
    {
        $fname = $this->filePath;

        $scope       = false;
        $datatype    = false;
        $binaryData  = null;
        $fileContent = null;
        $store       = true;
        if ( is_array( $fileData ) )
        {
            if ( isset( $fileData['scope'] ) )
                $scope = $fileData['scope'];
            if ( isset( $fileData['datatype'] ) )
                $datatype = $fileData['datatype'];
            if ( isset( $fileData['content'] ) )
                $fileContent = $fileData['content'];
            if ( isset( $fileData['binarydata'] ) )
                $binaryData = $fileData['binarydata'];
            if ( isset( $fileData['store'] ) )
                $store = $fileData['store'];
        }
        else
            $binaryData = $fileData;

        // Disable storage if the caller of the function says so
        if ( !$storeCache )
            $store = false;

        $mtime = false;
        $result = null;
        if ( $binaryData === null &&
             $fileContent === null )
        {
            eZDebug::writeError( "Write callback need to set the 'content' or 'binarydata' entry", __METHOD__ );
            return null;
        }

        if ( $binaryData === null )
            $binaryData = "<" . "?php\n\treturn ". var_export( $fileContent, true ) . ";\n?" . ">\n";

        if ( $fileContent === null )
            $result = $binaryData;
        else
            $result = $fileContent;

        // Check if we are allowed to store the data, if not just return the result
        if ( !$store )
        {
            $this->abortCacheGeneration();
            return $result;
        }

        // Store content locally
        $this->storeContents( $binaryData, $scope, $datatype, true );

        $this->_freeExclusiveLock( 'storeCache' );

        return $result;
    }

    /*!
     Provides access to the file contents by downloading the file locally and
     calling $callback with the local filename. The callback can then process the
     contents and return the data in the same way as in processCache().
     Downloading is only done once so the local copy is kept, while updates to the
     remote DB entry is synced with the local one.

     The parameters $expiry and $extraData is the same as for processCache().

     \note Unlike processCache() this returns null if the file cannot be accessed.
     */
    function processFile( $callback, $expiry = false, $extraData = null )
    {
        $result = $this->processCache( $callback, false, null, $expiry, $extraData );
        if ( $result instanceof eZClusterFileFailure )
        {
            return null;
        }
        return $result;
    }

    /**
     * Returns file metadata.
     *
     * \public
     */
    function stat()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', $this->metaData, "fs::stat( {$this->filePath} )" );
        return $this->metaData;
    }

    /**
     * Returns file size.
     *
     * \public
     */
    function size()
    {
        $size = isset( $this->metaData['size'] ) ? $this->metaData['size'] : null;
        eZDebugSetting::writeDebug( 'kernel-clustering', $size, "fs::size( {$this->filePath} )" );
        return $size;
    }

    /**
     * Returns file mime-type / content-type.
     * @return string|null
     */
    public function dataType()
    {
        $fileInfo = new finfo( FILEINFO_MIME_TYPE | FILEINFO_SYMLINK );
        $mimeType = $fileInfo->file( $this->filePath );
        eZDebugSetting::writeDebug( 'kernel-clustering', $mimeType, "fs::dataType( {$this->filePath} )" );
        return $mimeType ?: null;
    }

    /**
     * Returns file modification time.
     *
     * \public
     */
    function mtime()
    {
        $mtime = isset( $this->metaData['mtime'] ) ? $this->metaData['mtime'] : null;
        eZDebugSetting::writeDebug( 'kernel-clustering', $mtime, "fs::mtime( {$this->filePath} )" );
        return $mtime;
    }

    /**
     * Returns file name.
     *
     * \public
     */
    function name()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', $this->filePath, "fs::name( {$this->filePath} )" );
        return $this->filePath;
    }

    /**
     * Delete files matching given wildcard.
     *
     * \public
     * \static
     */
    function fileDeleteByWildcard( $wildcard )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDeleteByWildcard( '$wildcard' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        $unlinkArray = eZSys::globBrace( $wildcard );
        if ( $unlinkArray !== false and ( count( $unlinkArray ) > 0 ) )
        {
            array_map( 'unlink', $unlinkArray );
        }
        eZDebug::accumulatorStop( 'dbfile' );
    }


    /**
     * Delete files located in a directories from dirList, with common prefix specified by
     * commonPath, and common suffix with added wildcard at the end
     *
     * \public
     * \static
     */
    function fileDeleteByDirList( $dirList, $commonPath, $commonSuffix )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDeleteByDirList( '" . implode( ",", $dirList ) . "', '$commonPath', '$commonSuffix' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        foreach ( $dirList as $dir )
        {
            $unlinkArray = eZSys::globBrace( "$commonPath/$dir/$commonSuffix*" );
            if ( $unlinkArray !== false )
            {
                array_map( 'unlink', $unlinkArray );
            }
        }

        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * \public
     * \static
     */
    function fileDelete( $path, $fnamePart = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDelete( '$path' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        $list = array();
        if ( $fnamePart !== false )
        {
            $globResult = glob( $path . "/" . $fnamePart . "*" );
            if ( is_array( $globResult ) )
            {
                $list = $globResult;
            }
        }
        else
        {
            $list = array( $path );
        }

        foreach ( $list as $path )
        {
            if ( is_file( $path ) )
            {
                $handler = eZFileHandler::instance( false );
                $handler->unlink( $path );
                if ( file_exists( $path ) )
                    eZDebug::writeError( "File still exists after removal: '$path'", __METHOD__ );
            }
            else if ( is_dir( $path ) )
            {
                eZDir::recursiveDelete( $path );
            }
            else
            {
                continue;
            }
        }

        eZDebug::accumulatorStop( 'dbfile' );
    }

    public function filePurge( $path )
    {
        $this->fileDelete( $path );
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
        $path = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::delete( '$path' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        if ( is_file( $path ) )
        {
            $handler = eZFileHandler::instance( false );
            $handler->unlink( $path );
            if ( file_exists( $path ) )
                eZDebug::writeError( "File still exists after removal: '$path'", __METHOD__ );
        }
        elseif ( is_dir( $path ) )
        {
            eZDir::recursiveDelete( $path );
        }

        eZClusterFileHandler::cleanupEmptyDirectories( $path );

        $this->loadMetaData( true );

        eZDebug::accumulatorStop( 'dbfile' );
    }


    /**
     * Deletes a file that has been fetched before.
     *
     * @see fetchUnique
     *
     * In case of fetching from filesystem does nothing.
     */
    function fileDeleteLocal( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDeleteLocal( '$path' )", __METHOD__ );
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
        $path = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::deleteLocal( '$path' )", __METHOD__ );
    }

    /*!
     Purge local and remote file data for current file.
     */
    function purge( $printCallback = false, $microsleep = false, $max = false, $expiry = false )
    {
        $file = $this->filePath;
        if ( $max === false )
            $max = 100;
        $count = 0;
        $list = array();
        if ( is_file( $file ) )
        {
            $list = array( $file );
        }
        else
        {
            $globResult = glob( $file . "/*" );
            if ( is_array( $globResult ) )
            {
                $list = $globResult;
            }
        }
        do
        {
            if ( ( $count % $max ) == 0 && $microsleep )
                usleep( $microsleep ); // Sleep a bit to make the filesystem happier

            $count = 0;
            $file = array_shift( $list );

            if ( is_file( $file ) )
            {
                $mtime = @filemtime( $file );
                if ( $expiry === false ||
                     $mtime < $expiry ) // remove it if it is too old
                {
                    @unlink( $file );

                    eZClusterFileHandler::cleanupEmptyDirectories( $file );
                }
                ++$count;
            }
            else if ( is_dir( $file ) )
            {
                $globResult = glob( $file . "/*" );
                if ( is_array( $globResult ) )
                {
                    $list = array_merge( $globResult, $list );
                }
            }

            if ( $printCallback )
                call_user_func_array( $printCallback,
                                      array( $file, 1 ) );
        } while ( count( $list ) > 0 );
    }

    /**
     * Check if given file/dir exists.
     *
     * \public
     * \static
     */
    function fileExists( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileExists( '$path' )", __METHOD__ );

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
        $path = $this->filePath;
        $rc = isset( $this->metaData['mtime'] );
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::exists( '$path' ): " . ( $rc ? 'true' :'false' ), __METHOD__ );
        return $rc;
    }

    /**
     * Outputs file contents to the browser
     * Note: does not handle headers. eZFile::downloadHeaders() can be used for this
     *
     * @param int $offset Transfer start offset
     * @param int $length Transfer length, in bytes
     */
    function passthrough( $offset = 0, $length = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::passthrough( '{$this->filePath}' )", __METHOD__ );
        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        eZFile::downloadContent( $this->filePath, $offset, $length );

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
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileCopy( '$srcPath', '$dstPath' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        eZFileHandler::copy( $srcPath, $dstPath );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Create symbolic or hard link to file.
     *
     * \public
     * \static
     */
    function fileLinkCopy( $srcPath, $dstPath, $symLink )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileLinkCopy( '$srcPath', '$dstPath' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        eZFileHandler::linkCopy( $srcPath, $dstPath, $symLink );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Move file.
     *
     * \public
     * \static
     */
    function fileMove( $srcPath, $dstPath )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileMove( '$srcPath', '$dstPath' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        eZFile::rename( $srcPath, $dstPath, true );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Move file.
     *
     * \public
     */
    function move( $dstPath )
    {
        $srcPath = $this->filePath;

        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::move( '$srcPath', '$dstPath' )", __METHOD__ );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        eZFileHandler::move( $srcPath, $dstPath );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Starts cache generation for the current file.
     *
     * This is done by creating a file named by the original file name, prefixed
     * with '.generating'.
     *
     * @todo add timeout handling...
     *
     * @return mixed true if generation lock was granted, an integer matching the
     *               time before the current generation times out
     */
    public function startCacheGeneration()
    {
        return true;
    }

    /**
     * Ends the cache generation started by startCacheGeneration().
     */
    public function endCacheGeneration( $rename = true )
    {
        return true;
    }

    /**
     * Aborts the current cache generation process.
     *
     * Does so by rolling back the current transaction, which should be the
     * .generating file lock
     */
    public function abortCacheGeneration()
    {
        $this->_freeExclusiveLock( 'storeCache' );

        return true;
    }

    /**
     * Checks if the .generating file was changed, which would mean that generation
     * timed out. If not timed out, refreshes the timestamp so that storage won't
     * be stolen
     */
    public function checkCacheGenerationTimeout()
    {
        return true;
    }

    /**
     * eZFS only stores data to FS and doesn't require/support clusterizing
     *
     * @return bool false
     */
    public function requiresClusterizing()
    {
        return false;
    }

    /**
     * eZFS does not require binary purge.
     * Files are stored on plain FS and removed using FS functions
     *
     * @since 4.5.0
     * @return bool
     */
    public function requiresPurge()
    {
        return false;
    }

    public function hasStaleCacheSupport()
    {
        return false;
    }

    public function getFileList($scopes = false, $excludeScopes = false)
    {
        $return = [];

        if( !empty( $scopes ) )
        {
            foreach( $scopes as $scope )
            {
                switch( $scope )
                {
                    case 'ezjscore':
                    {
                        $varDir = eZINI::instance()->variable( 'FileSettings', 'VarDir' );

                        $return = array_merge(
                            $return,
                            eZDir::recursiveFind( $varDir .'/cache/public/javascript/', '.' )
                        );
                        $return = array_merge(
                            $return,
                            eZDir::recursiveFind( $varDir .'/cache/public/stylesheets/', '.' )
                        );
                    }
                    break;
                }
            }
        }
        return $return;
    }

    public function isDBFileExpired($expiry, $curtime, $ttl)
    {
        return self::isFileExpired( $this->filePath, @filemtime( $this->filePath ), $expiry, $curtime, $ttl );
    }

    public function isLocalFileExpired($expiry, $curtime, $ttl)
    {
        return self::isFileExpired( $this->filePath, @filemtime( $this->filePath ), $expiry, $curtime, $ttl );
    }

    /**
     * No transformation is required since files are served from the same host
     */
    public function applyServerUri( $filePath )
    {
        return $filePath;
    }

    public $metaData = null;
    public $filePath;
}

?>

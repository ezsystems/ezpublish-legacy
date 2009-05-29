<?php
//
// Definition of eZDFSFileHandler class
//
// Created on: <15-May-2009 14:39:01 bd>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/**
* Handles file operations for Distributed File Systems (f.e. NFS)
*
* Uses a dual DB / FS approach:
*  - files metadata are DB based
*  - files data are read/written to a local mount point (outside var/)
*  - actual files are locally written, exactly like the DB handler does
*
* Glossary of terms used in the internal doc:
*  - DFS: Distributed File System. Local NFS mount point
*  - DB:  MetaData database
*  - LFS: Local file system (var)
*
* @since 4.2.0
**/
class eZDFSFileHandler implements eZClusterFileHandlerInterface
{
    /*!
     Controls whether file data from database is cached on the local filesystem.
     \note This is primarily available for debugging purposes.
     */
    const LOCAL_CACHE = 1;

    /*!
     Controls the maximum number of metdata entries to keep in memory for this request.
     If the limit is reached the least used entries are removed.
     */
    const INFOCACHE_MAX = 200;

    /**
    * File statuses
    *  - available:  file can be read or written
    *  - generating: file generation is being processed by a shard, no write
    *                allowed, limited read allowed from DFS/LFS
    *  - writing:    file is being written. LFS/DFS files can not be
    *                read nor written
    **/
    const STATUS_AVAILABLE  = 0;
    const STATUS_GENERATING = 1;
    const STATUS_WRITING    = 2;

    /**
     * Constructor.
     *
     * If provided with $filePath, will use this file for further operations.
     * If not given, the file* methods must be used instead
     *
     * @param string $filePath Path of the file to handle
     *
     * @throws eZDBNoConnectionException DB connection failed
     */
    function __construct( $filePath = false )
    {
        if ( self::$nonExistantStaleCacheHandling === null )
        {
            $fileINI = eZINI::instance( 'file.ini' );
            self::$nonExistantStaleCacheHandling = $fileINI->variable( "ClusteringSettings", "NonExistantStaleCacheHandling" );
            unset( $fileINI );
        }

        // DB Backend init
        $this->dbbackend = eZExtension::getHandlerClass(
            new ezpExtensionOptions(
                array( 'iniFile'     => 'file.ini',
                       'iniSection'  => 'eZDFSClusteringSettings',
                       'iniVariable' => 'DBBackend' ) ) );
        $this->dbbackend->_connect( false );

        $filePath = eZDBFileHandler::cleanPath( $filePath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::ctor( '$filePath' )" );
        $this->filePath = $filePath;
    }

    /**
    * Loads file meta information.
    *
    * @param bool $force File stats will be refreshed if true
    * @return void
    */
    public function loadMetaData( $force = false )
    {
        // Fetch metadata.
        if ( $this->filePath === false )
            return;

        if ( $force && isset( $GLOBALS['eZClusterInfo'][$this->filePath] ) )
            unset( $GLOBALS['eZClusterInfo'][$this->filePath] );

        // Checks for metadata stored in memory, useful for repeated access
        // to the same file in one request
        // TODO: On PHP5 turn into static member
        if ( isset( $GLOBALS['eZClusterInfo'][$this->filePath] ) )
        {
            $GLOBALS['eZClusterInfo'][$this->filePath]['cnt'] += 1;
            $this->_metaData = $GLOBALS['eZClusterInfo'][$this->filePath]['data'];
            return;
        }

        $metaData = $this->dbbackend->_fetchMetadata( $this->filePath );
        if ( $metaData )
            $this->_metaData = $metaData;
        else
            $this->_metaData = false;

        // Clean up old entries if the maximum count is reached
        if ( isset( $GLOBALS['eZClusterInfo'] ) &&
             count( $GLOBALS['eZClusterInfo'] ) >= self::INFOCACHE_MAX )
        {
            usort( $GLOBALS['eZClusterInfo'],
                   create_function( '$a, $b',
                                    '$a=$a["cnt"]; $b=$b["cnt"]; if ( $a > $b ) return -1; else if ( $a == $b ) return 0; else return 1;' ) );
            array_pop( $GLOBALS['eZClusterInfo'] );
        }
        $GLOBALS['eZClusterInfo'][$this->filePath] = array( 'cnt' => 1,
                                                            'data' => $metaData );
    }

    /**
     * Stores a file by path to the backend
     *
     * @param string $filePath Path to the file being stored.
     * @param string $scope    Means something like "file category". May be used
     *        to clean caches of a certain type.
     * @param bool   $delete   true if the file should be deleted after storing.
     * @param string $datatype
     *
     * @return void
     */
    public function fileStore( $filePath, $scope = false, $delete = false, $datatype = false )
    {
        $filePath = eZDBFileHandler::cleanPath( $filePath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileStore( '$filePath' )" );

        if ( $scope === false )
            $scope = 'UNKNOWN_SCOPE';

        if ( $datatype === false )
            $datatype = 'misc';

        $this->dbbackend->_store( $filePath, $datatype, $scope );

        if ( $delete )
            @unlink( $filePath );
    }

    /**
    *
    * Store file contents.
    *
    * @param string $filePath Path to the file being stored.
    * @param string $contents Binary file content
    * @param string $scope    "file category". May be used by cache management
    * @param string $datatype Datatype for the file. Also used to clean cache up
    *
    * @return void
    **/
    public function fileStoreContents( $filePath, $contents, $scope = false, $datatype = false )
    {
        $filePath = eZDBFileHandler::cleanPath( $filePath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileStoreContents( '$filePath' )" );

        if ( $scope === false )
            $scope = 'UNKNOWN_SCOPE';

        if ( $datatype === false )
            $datatype = 'misc';

        // the file is stored with the current time as mtime
        $this->dbbackend->_storeContents( $filePath, $contents, $scope, $datatype );
    }

    /**
     * Store file contents using binary data
     *
     * @param string $contents Binary file content
     * @param string $scope    "file category". May be used by cache management
     * @param string $datatype Datatype for the file. Also used to clean cache up
     * @param bool $storeLocally If true the file will also be stored on the
     *                           local file system.
     **/
    public function storeContents( $contents, $scope = false, $datatype = false, $storeLocally = false )
    {
        if ( $scope === false )
            $scope = 'UNKNOWN_SCOPE';

        if ( $datatype === false )
            $datatype = 'misc';

        $filePath = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::storeContents( '$filePath' )" );

        $mtime = time();

        // the file is stored with the current time as mtime
        $this->dbbackend->_storeContents( $filePath, $contents, $scope, $datatype );

        if ( $storeLocally )
        {
            eZFile::create( basename( $filePath ), dirname( $filePath ), $contents, true );
        }
    }

    /**
     * Fetches file from DFS and saves it in LFS under the same name.
     *
     * @param string $filePath
     *
     * @return string|false the file path, or false if fetching failed
     */
    function fileFetch( $filePath )
    {
        $filePath = eZDBFileHandler::cleanPath( $filePath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileFetch( '$filePath' )" );

        return $this->dbbackend->_fetch( $filePath );
    }

    /*public function fileFetch( $filePath )
    {
        $filePath = eZDBFileHandler::cleanPath( $filePath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileFetch( '$filePath' )" );

        switch ( $this->dbbackend->_prepareFetch( $filePath ) )
        {
            // READ_STATUS_OK: SLOCK granted, DFS read OK
            case self::READ_STATUS_OK:
            {
                // copy file from DFS to FS

                // release the SLOCK on the file
                $this->dbbackend->_endFetch( $filePath );
            } break;

            // READ_STATUS_STALE: SLOCK granted, local version is prefered
            case self::READ_STATUS_STALE:
            {

            }
        }
    }*/

    /**
     * Fetches file from db and saves it in FS under a unique name
     *
     * @return string filename with path of a saved file. You can use this
     *         filename to get contents of file from filesystem.
     */
    public function fetchUnique()
    {
        $filePath = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fetchUnique( '$filePath' )" );

        $fetchedFilePath = $this->dbbackend->_fetch( $filePath, true );
        $this->uniqueName = $fetchedFilePath;
        return $fetchedFilePath;
    }

    /**
     * Fetches file from DFS and saves it in FS under the same name.
     * @param bool $noLocalCache
     */
    function fetch( $noLocalCache = false )
    {
        return $this->fileFetch( $this->filePath );
    }

    /**
     * Returns file contents.
     * @return bool|string contents string, or false in case of an error.
     */
    function fileFetchContents( $filePath )
    {
        $filePath = eZDBFileHandler::cleanPath( $filePath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileFetchContents( '$filePath' )" );

        $contents = $this->dbbackend->_fetchContents( $filePath );
        return $contents;
    }

    /**
     * Returns file contents.
     * @return string|bool contents string, or false in case of an error.
     */
    function fetchContents()
    {
        $filePath = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileFetchContents( '$filePath' )" );
        $contents = $this->dbbackend->_fetchContents( $filePath );
        return $contents;
    }

    /**
    * Handles cache requests / write operations
    *
    * Creates a single transaction out of the typical file operations for
    * accessing caches. Caches are normally ready from the database or local
    * file, if the entry does not exist or is expired then it generates the new
    * cache data and stores it. This method takes care of these operations and
    * handles the custom code by performing callbacks when needed.
    *
    * The $retrieveCallback is used when the file contents can be used (ie. not
    * re-generation) and is called when the file is ready locally.
    * The function will be called with the file path as the first parameter, the
    * mtime as the second and optionally $extraData as the third.
    * The function must return the file contents or an instance of
    * eZClusterFileFailure which can be used to tell the system that the
    * retrieve data cannot be used after all.
    *
    * $retrieveCallback can be set to null which makes the system go directly
    * to the generation.
    *
    * The $generateCallback is used when the file content is expired or does not
    * exist, in this case the content must be re-generated and stored. The
    * function will be called with the file path as the first parameter and
    * optionally $extraData as the second.
    * The function must return an array with information on the contents, the
    * array consists of:
    *  - scope      - The current scope of the file, is optional.
    *  - datatype   - The current datatype of the file, is optional.
    *  - content    - The file content, this can be any type except null.
    *  - binarydata - The binary data which is written to the file.
    *  - store      - Whether *content* or *binarydata* should be stored to the
    *                 file, if false it will simply return the data. Optional,
    *                 by default it is true.
    * Note: Set $generateCallback to false to disable generation callback.
    * Note: Set $generateCallback to null to tell the function to perform a
    *       write lock but not do any generation, the generation must done be
    *       done by the caller by calling @see storeCache().
    *
    * Either *content* or *binarydata* must be supplied, if not an error is
    * issued and it returns null.
    *
    * If *content* is set it will be used as the return value of this function,
    * if not it will return the binary data.
    * If *binarydata* is set it will be used as the binary data for the file, if
    * not it will perform a var_export on *content* and use that as the binary
    * data.
    *
    * For convenience the $generateCallback function can return a string which
    * will be considered as the binary data for the file and returned as the
    * content.
    *
    * For controlling how long a cache entry can be used the parameters
    * @see $expiry and @see $ttl is used.
    * @see $expiry can be set to a timestamp which controls the absolute max
    * time for the cache, after this time/date the cache will never be used.
    * If the value is set to a negative value or null there the expiration check
    * is disabled.
    *
    * $ttl (time to live) tells how many seconds the cache can live from the
    * time it was stored. If the value is set to negative or null there is no
    * limit for the lifetime of the cache. A value of 0 means that the cache
    * will always expire and practically disables caching. For the cache to be
    * used both the $expiry and $ttl check must hold.
    *
    * @todo Reformat the doc so that it's readable
    **/
    function processCache( $retrieveCallback, $generateCallback = null, $ttl = null, $expiry = null, $extraData = null )
    {
        $forceDB = false;
        $timestamp = null;
        $curtime   = time();
        $tries     = 0;

        if ( $expiry < 0 )
            $expiry = null;
        if ( $ttl < 0 )
            $ttl = null;

        // Main loop
        while ( true )
        {
            // Start read checks
            // Note: The while loop is used to make it easier to break out of the "read" code
            while ( true )
            {
                // No retrieve method so go directly to generate+store
                if ( $retrieveCallback === null || !$this->filePath )
                    break;

                if ( !self::LOCAL_CACHE )
                {
                    $forceDB = true;
                }
                else
                {
                    if ( $this->isLocalFileExpired( $expiry, $curtime, $ttl ) )
                    {
                        // if we are in stale cache mode, we only forceDB if the
                        // file does not exist at all
                        if ( $this->useStaleCache )
                        {
                            if ( !file_exists( $this->filePath ) )
                            {
                                eZDebugSetting::writeDebug( 'kernel-clustering', "Local file '{$this->filePath}' does not exist and can not be used for stale cache. Checking with DB", __METHOD__ );
                                $forceDB = true;

                                // forceDB + useStaleCache means that we should check for the DB file.
                            }
                        }
                        else
                        {
                            // Local file is older than global timestamp, check with DB
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Local file (mtime=" . @filemtime( $this->filePath ) . ") is older than timestamp ($expiry) and ttl($ttl), check with DB" );
                            $forceDB = true;
                        }
                    }
                }

                if ( !$forceDB )
                {
                    // check if DB file is deleted
                    if ( !$this->useStaleCache && ( $this->metaData === false || $this->metaData['mtime'] < 0 ) )
                    {
                        if ( $generateCallback !== false )
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, need to regenerate data" );
                        else
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, cannot get data" );
                        break;
                    }

                    // check if FS file is older than DB file
                    if ( !$this->useStaleCache && $this->isLocalFileExpired( $this->metaData['mtime'], $curtime, $ttl ) )
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', "Local file (mtime=" . @filemtime( $this->filePath ) . ") is older than DB, checking with DB", __METHOD__ );
                        $forceDB = true;
                    }
                    else
                    {
                        if ( $this->useStaleCache )
                        {
                            // to get the retrieve callback to accept the cache file,
                            // we force its mtime to the current time
                            $mtime = $curtime;
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Processing local stale cache file {$this->filePath}", __METHOD__ );
                        }
                        else
                        {
                            $mtime = filemtime( $this->filePath );
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Processing local cache file {$this->filePath}", __METHOD__ );
                        }

                        $args = array( $this->filePath, $mtime );
                        if ( $extraData !== null )
                            $args[] = $extraData;
                        $retval = call_user_func_array( $retrieveCallback, $args );
                        if ( $retval instanceof eZClusterFileFailure )
                        {
                            break;
                        }
                        return $retval;
                    }
                }

                if ( $forceDB )
                {
                    // stale cache, and no DB or FS file available
                    if ( $this->useStaleCache && $this->metaData === false )
                    {
                        // configuration says we have to generate our own version
                        if ( $this->nonExistantStaleCacheHandling[ $this->cacheType ] == 'generate' )
                        {
                            // no cache available, but a generate callback exists, skip to generation
                            if ( $generateCallback !== false )
                            {
                                eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, need to regenerate data" );
                                break;
                            }
                            // if no generate callback exists, we can directly skip the main loop
                            else
                            {
                                eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, cannot get data" );
                                break 2;
                            }
                        }
                        // wait for the generating process to be finished (or timedout)
                        else
                        {
                            while ( $this->remainingCacheGenerationTime-- >= 0 )
                            {
                                eZDebug::writeDebug( $this->remainingCacheGenerationTime, '$this->remainingCacheGenerationTime' );
                                // we don't know if the file gets generated on the current
                                // frontend or not. However, we can still try the FS cache
                                // first, then the DB cache if FS is not found, since this
                                // will be much more efficient
                                if ( !file_exists( $this->filePath ) )
                                {
                                    $this->loadMetaData( true );
                                    if ( $this->metaData === false )
                                    {
                                        sleep( 1 );
                                        continue;
                                    }
                                    else
                                    {
                                        break;
                                    }
                                }
                                else
                                {
                                    break;
                                }
                            }

                            // if we reached this point, it means that we are over the estimated timeout value
                            // we try to take the generation over by starting the cache generation. IF this
                            // fails again, it is probably because another waiting process has taken the generation
                            // over. Maybe add a counter here to prevent some kind of death loop ?
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Checking if {$this->filePath} was generating during the wait loop", __METHOD__ );
                            $this->loadMetaData( true );
                            $this->useStaleCache = false;
                            $this->remainingCacheGenerationTime = false;
                            $forceDB = false;

                            // this continues to the main loop 'while (true)'
                            continue 2;
                        }
                    }
                    // no stale cache, and expired DB file
                    elseif ( !$this->useStaleCache && ( $this->metaData === false || $this->isDBFileExpired( $expiry, $curtime, $ttl ) ) ) // no stalecache, and no DB file, generation is required
                    {
                        // no cache available, but a generate callback exists, skip to generation
                        if ( $generateCallback !== false )
                        {
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, need to regenerate data" );

                            // we break out of one loop so that the generateCallback is called
                            break;
                        }
                        // if no generate callback exists, we can directly skip the main loop
                        else
                        {
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, cannot get data" );

                            // we break out of two loops so that we directly exit the method and have
                            // the rest of execution generate the data
                            break 2;
                        }
                    }
                    else
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', "Callback from DB file {$this->filePath}", __METHOD__ );
                        if ( self::LOCAL_CACHE )
                        {
                            $this->fetch();

                            // Figure out which mtime to use for new file, must be larger than
                            // mtime in DB at least.
                            $mtime = $this->metaData['mtime'] + 1;
                            $localmtime = @filemtime( $this->filePath );
                            $mtime = max( $mtime, $localmtime );
                            touch( $this->filePath, $mtime, $mtime );
                            clearstatcache(); // Needed because of touch() call

                            $args = array( $this->filePath, $mtime );
                            if ( $extraData !== null )
                                $args[] = $extraData;
                            $retval = call_user_func_array( $retrieveCallback, $args );
                            if ( $retval instanceof eZClusterFileFailure )
                            {
                                break;
                            }
                            return $retval;
                        }
                        else
                        {
                            $uniquePath = $this->fetchUnique();

                            $args = array( $uniquePath, $this->metaData['mtime'] );
                            if ( $extraData !== null )
                                $args[] = $extraData;
                            $retval = call_user_func_array( $retrieveCallback, $args );
                            $this->fileDeleteLocal( $uniquePath );
                            if ( $retval instanceof eZClusterFileFailure )
                                break;
                            return $retval;
                        }
                    }
                    eZDebugSetting::writeDebug( 'kernel-clustering', "Database file does not exist, need to regenerate data" );
                    break;
                }
            }

            if ( $tries >= 2 )
            {
                eZDebugSetting::writeDebug( 'kernel-clustering', "Reading was retried $tries times and reached the maximum, returning null" );
                return null;
            }

            // Generation part starts here
            if ( isset( $retval ) && $retval instanceof eZClusterFileFailure )
            {
                if ( $retval->errno() != 1 ) // check for non-expiry error codes
                {
                    eZDebug::writeError( "Failed to retrieve data from callback", 'eZDBFileHandler::processCache' );
                    return null;
                }
                $message = $retval->message();
                if ( strlen( $message ) > 0 )
                {
                    eZDebugSetting::writeDebug( 'kernel-clustering', $retval->message(), "eZClusterFileFailure::processCache" );
                }
                // the retrieved data was expired so we need to generate it, let's continue
            }

            // We need to lock if we have a generate-callback or
            // the generation is deferred to the caller.
            // Note: false means no generation, while null means that generation
            // is deferred to the processing that follows (f.i. cache-blocks)
            if ( $generateCallback !== false && $this->filePath )
            {
                if ( !$this->useStaleCache )
                {
                    $res = $this->startCacheGeneration();
                    if ( $res !== true )
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', "{$this->filePath} is being generated, switching to staleCache mode", __METHOD__ );
                        $this->useStaleCache = true;
                        $this->remainingCacheGenerationTime = $res;
                        $forceDB = false;
                        continue;
                    }
                }

                // File in DB is outdated or non-existing, call write-callback to generate content
                if ( $generateCallback )
                {
                    $args = array( $this->filePath );
                    if ( $extraData !== null )
                        $args[] = $extraData;
                    $fileData = call_user_func_array( $generateCallback, $args );
                    return $this->storeCache( $fileData );
                }
            }

            break;
        } // End main loop

        return new eZClusterFileFailure( 2, "Manual generation of file data is required, calling storeCache is required" );
    }

    /**
     * Calculates if the file data is expired or not.
     *
     * @param string $fname Name of file, available for easy debugging.
     * @param int    $mtime Modification time of file, can be set to false if
     *                      file does not exist.
     * @param int    $expiry Time when file is to be expired, a value of -1 will
     *                       disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to
     *                    disable TTL.
     * @return bool
     */
    public function isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl )
    {
        if ( $mtime == false or $mtime < 0 )
        {
            return true;
        }
        elseif ( $ttl === null )
        {
            return $mtime < $expiry;
        }
        else
        {
            return $mtime < max( $expiry, $curtime - $ttl );
        }
    }

    /**
     * Calculates if the current file data is expired or not.
     *
     * @param int    $expiry Time when file is to be expired, a value of -1 will disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to disable TTL.
     * @return bool
     **/
    public function isExpired( $expiry, $curtime, $ttl )
    {
        return self::isFileExpired( $this->filePath, $this->metaData['mtime'], $expiry, $curtime, $ttl );
    }

    /**
     * Calculates if the local file is expired or not.
     * @param int    $expiry Time when file is to be expired, a value of -1 will disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to disable TTL.
     * @return bool
     **/
    public function isLocalFileExpired( $expiry, $curtime, $ttl )
    {
        return self::isFileExpired( $this->filePath, @filemtime( $this->filePath ), $expiry, $curtime, $ttl );
    }

    /**
     * Calculates if the DB file is expired or not.
     * @param int    $expiry Time when file is to be expired, a value of -1 will disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to disable TTL.
     * @return bool
     **/
    public function isDBFileExpired( $expiry, $curtime, $ttl )
    {
        $mtime = isset( $this->metaData['mtime'] ) ? $this->metaData['mtime'] : 0;
        return self::isFileExpired( $this->filePath, $mtime, $expiry, $curtime, $ttl );
    }

    /**
     * Stores the data in $fileData to the remote and local file and commits the
     * transaction.
     *
     * The parameter $fileData must contain the same as information as the
     * $generateCallback returns as explained in processCache().
     * @note This method is just a continuation of the code in processCache()
     *       and is not meant to be called alone since it relies on specific
     *       state in the database.
     **/
    public function storeCache( $fileData )
    {
        // This checks if we entered timeout and got our generating file stolen
        // If this happens, we don't store our cache
        if ( !$this->checkCacheGenerationTimeout() )
            $storeCache = false;
        else
            $storeCache = true;

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

        $mtime = false;
        $result = null;
        if ( $binaryData === null &&
             $fileContent === null )
        {
            eZDebug::writeError( "Write callback need to set the 'content' or 'binarydata' entry" );
            $this->abortCacheGeneration();
            return null;
        }

        if ( $binaryData === null )
            $binaryData = "<" . "?php\n\treturn ". var_export( $fileContent, true ) . ";\n?" . ">\n";

        if ( $fileContent === null )
            $result = $binaryData;
        else
            $result = $fileContent;

        if ( !$this->filePath )
            return $result;

        // no store advice, we just return the result
        if ( !$storeCache )
        {
            return $result;
        }

        // stale cache handling: we just return the result, no lock has been set
        if ( $this->useStaleCache )
        {
            // we write the generated cache to disk if it does not exist yet,
            // to speed up the next uncached operation
            // This file will be overwritten by the real file
            clearstatcache();
            if ( !file_exists( $this->filePath ) )
            {
                eZDebugSetting::writeDebug( 'kernel-clustering', "Writing stale file content to local file {$this->filePath}" );
                eZFile::create( basename( $this->filePath ), dirname( $this->filePath ), $binaryData, true );
            }
            return $result;
        }

        // Check if we are allowed to store the data, if not just return the result
        if ( !$store )
        {
            $this->abortCacheGeneration();
            return $result;
        }

        // The file is finally written to disk with its final name
        $this->fileStoreContents( $this->realFilePath, $binaryData, $scope, $datatype, $storeLocally = self::LOCAL_CACHE );

        if ( self::LOCAL_CACHE )
        {
            eZDebugSetting::writeDebug( 'kernel-clustering',
                "Creating local copy of the file", "dfs::storeCache( '{$this->realFilePath}' )" );
            eZFile::create( basename( $this->realFilePath ), dirname( $this->realFilePath ), $binaryData, true );
        }

        // we end the cache generation process, so that the .generating file
        // is renamed to its final name, and made available to other processes
        $this->endCacheGeneration();

        return $result;
    }

    /**
     * Provides access to the file contents by downloading the file locally and
     * calling $callback with the local filename. The callback can then process
     * the contents and return the data in the same way as in processCache().
     *
     * Downloading is only done once so the local copy is kept, while updates to
     * the remote DB entry is synced with the local one.
     *
     * The parameters $expiry and $extraData is the same as for processCache().
     *
     * @see self::processCache()
     * @note Unlike processCache() this returns null if the file cannot be
     *       accessed.
     **/
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
     */
    public function stat()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::stat()" );
        return $this->metaData;
    }

    /**
     * Returns file size.
     * @return int|null
     */
    public function size()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::size()" );
        return isset( $this->metaData['size'] ) ? (int)$this->metaData['size'] : null;
    }

    /**
     * Returns file modification time.
     * @return int|null
     **/
    public function mtime()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::mtime()" );
        return isset( $this->metaData['mtime'] ) ? (int)$this->metaData['mtime'] : null;
    }

    /**
     * Returns file name.
     * @return string|null
     **/
    public function name()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::name()" );
        return $this->filePath;
    }

    /**
     * Deletes multiple files by regex
     * @param string $dir An optional directory that will be prepended to the
     *                    regex. Set to false to disable
     * @param string $fileRegex The regular expression applied to files
     * @return void
     * @todo -ceZDFSFileHandler write unit test
     **/
    public function fileDeleteByRegex( $dir, $fileRegex )
    {
        $dir = eZDBFileHandler::cleanPath( $dir );
        $fileRegex = eZDBFileHandler::cleanPath( $fileRegex );
        eZDebug::writeWarning( "Using eZDBFileHandler::fileDeleteByRegex is not recommended since it has some severe performance issues" );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileDeleteByRegex( '$dir', '$fileRegex' )" );

        $regex = '^' . ( $dir ? $dir . '/' : '' ) . $fileRegex;
        $this->dbbackend->_deleteByRegex( $regex );
    }

    /**
     * Deletes a list of files by wildcard
     *
     * @param string $wildcard The wildcard used to look for files. Can contain
     *                         * and ?
     * @return void
     * @todo -ceZDFSFileHandler write unit test
     */
    public function fileDeleteByWildcard( $wildcard )
    {
        $wildcard = eZDBFileHandler::cleanPath( $wildcard );
        eZDebug::writeWarning( "Using eZDBFileHandler::fileDeleteByWildcard is not recommended since it has some severe performance issues" );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileDeleteByWildcard( '$wildcard' )" );

        $this->dbbackend->_deleteByWildcard( $wildcard );
    }

    /**
     * Deletes a list of files based on directory / filename components
     *
     * @param array  $dirList Array of directory that will be prefixed with
     *                        $commonPath when looking for files
     * @param string $commonPath Starting path common to every delete request
     * @param string $commonSuffix Suffix appended to every delete request
     * @return void
     * @todo -ceZDFSFileHandler write unit test
     */
    public function fileDeleteByDirList( $dirList, $commonPath, $commonSuffix )
    {
        foreach ( $dirList as $key => $dirItem )
        {
            $dirList[$key] = eZDBFileHandler::cleanPath( $dirItem );

        }
        $commonPath = eZDBFileHandler::cleanPath( $commonPath );
        $commonSuffix = eZDBFileHandler::cleanPath( $commonSuffix );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileDeleteByDirList( '$dirList', '$commonPath', '$commonSuffix' )" );

        $this->dbbackend->_deleteByDirList( $dirList, $commonPath, $commonSuffix );
    }

    /**
     * Deletes specified file/directory.
     *
     * @param string $path the file path to delete
     * @param bool|string $fnamePart If set to true, $path is a directory and
     *                    its content is deleted. If it is a string, this string
     *                    is appended a wildcard and used for deletion
     * @return void
     * @todo -ceZDFSFileHandler write unit test
     */
    public function fileDelete( $path, $fnamePart = false )
    {
        $path = eZDBFileHandler::cleanPath( $path );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileDelete( '$path' )" );

        if ( $fnamePart === false )
        {
            $this->dbbackend->_delete( $path );
        }
        elseif ( $fnamePart === true )
        {
            $this->dbbackend->_deleteByLike( $path . '/%' );
        }
        else
        {
            $fnamePart = eZDBFileHandler::cleanPath( $fnamePart );
            $this->dbbackend->_deleteByLike( $path . '/' . $fnamePart . '%' );
        }
    }

    /**
     * Deletes specified file/directory.
     *
     * If a directory specified it is deleted recursively.
     */
    function delete()
    {
        $path = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::delete( '$path' )" );

        $this->dbbackend->_delete( $path );
        $this->dbbackend->_deleteByLike( $path . '/%' );

        $this->metaData = null;
    }

    /**
     * Deletes a file that has been fetched before.
     */
    function fileDeleteLocal( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileDeleteLocal( '$path' )" );
        @unlink( eZDBFileHandler::cleanPath( $path ) );
    }

    /**
     * Deletes a file that has been fetched before.
     **/
    function deleteLocal()
    {
        $path = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::deleteLocal( '$path' )" );
        @unlink( $path );
    }

    /**
     * Purges local and remote file data for current file path.
     *
     * Can be given a file or a folder. In order to clear a folder, do NOT add
     * a trailing / at the end of the file's path: path/to/file instead of
     * path/to/file/.
     *
     * By default, only expired files will be removed (ezdfsfile.expired = 1).
     * If you specify an $expiry time, it will replace the expired test and
     * only purge files older than the given expiry timestamp.
     *
     * @param callback $printCallback
     *        Callback called after each delete iteration (@see $max) to print
     *        out a report of the deleted files. This callback expects two
     *        parameters, $file (delete pattern used to perform deletion) and
     *        $count (number of deleted items)
     * @param int $microsleep
     *        Wait interval before each purge batch of $max items
     * @param int $max
     *        Maximum number of items to delete in one batch (default: 100)
     * @param int $expiry
     *        If specificed, only files older than this date will be purged
     * @return void
     */
    function purge( $printCallback = false, $microsleep = false, $max = false, $expiry = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::purge( '$this->filePath' )" );

        $file = $this->filePath;
        if ( $max === false )
        {
            $max = 100;
        }
        $count = 0;
        /**
         * The loop starts without knowing how many files are to be deleted.
         * When _purgeByLike is called, it returns the number of affected rows.
         * If rows were affected, _purgeByLike will be called again
         **/
        do
        {
            // @todo this won't work on windows, make a wrapper that uses
            //       either usleep or sleep depending on the OS
            if ( $count > 0 && $microsleep )
            {
                usleep( $microsleep ); // Sleep a bit to make the database happier
            }
            $count = $this->dbbackend->_purgeByLike( $file . "/%", true, $max, $expiry, 'purge' );
            $this->dbbackend->_purge( $file, true, $expiry, 'purge' );
            if ( $printCallback )
            {
                call_user_func_array( $printCallback, array( $file, $count ) );
            }

            // @todo Compare $count to $max. If $count < $max, no more files are to
            // be purged, and we can exit the loop
        } while ( $count > 0 );

        // Remove local copies
        if ( is_file( $file ) )
        {
            @unlink( $file );
        }
        elseif ( is_dir( $file ) )
        {
            eZDir::recursiveDelete( $file );
        }
    }

    /**
     * Check if given file/dir exists.
     * @param string $path File path to test existence for
     * @see eZDFSFileHandler::exists()
     * @return bool
     */
    function fileExists( $path )
    {
        $path = eZDBFileHandler::cleanPath( $path );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileExists( '$path' )" );
        return $this->dbbackend->_exists( $path );
    }

    /**
     * Check if given file/dir exists.
     * @see eZDFSFileHandler::fileExists()
     * @return bool
     **/
    function exists()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::exists( '$this->filePath' )" );
        return $this->dbbackend->_exists( $this->filePath );
    }

    /**
     * Outputs file contents prepending them with appropriate HTTP headers.
     *
     * @deprecated This function should not be used since it cannot handle
     *             reading errors.
     **/
    function passthrough()
    {
        $path = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::passthrough( '$path' )" );
        $size = $this->metaData['size'];
        $mimeType = $this->metaData['datatype'];
        $mtime = $this->metaData['mtime'];
        $mdate = gmdate( 'D, d M Y H:i:s T', $mtime );

        header( "Content-Length: $size" );
        header( "Content-Type: $mimeType" );
        header( "Last-Modified: $mdate GMT" );
        header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 6000) . ' GMT');
        header( "Connection: close" );
        header( "X-Powered-By: eZ Publish" );
        header( "Accept-Ranges: bytes" );

        $this->dbbackend->_passThrough( $path );
    }

    /**
     * Copy file.
     */
    function fileCopy( $srcPath, $dstPath )
    {
        $srcPath = eZDBFileHandler::cleanPath( $srcPath );
        $dstPath = eZDBFileHandler::cleanPath( $dstPath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileCopy( '$srcPath', '$dstPath' )" );

        $this->dbbackend->_copy( $srcPath, $dstPath );
    }

    /**
     * Create symbolic or hard link to file.
     */
    function fileLinkCopy( $srcPath, $dstPath, $symLink )
    {
        $srcPath = eZDBFileHandler::cleanPath( $srcPath );
        $dstPath = eZDBFileHandler::cleanPath( $dstPath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileLinkCopy( '$srcPath', '$dstPath' )" );

        $this->dbbackend->_linkCopy( $srcPath, $dstPath );
    }

    /**
     * Move file.
     */
    function fileMove( $srcPath, $dstPath )
    {
        $srcPath = eZDBFileHandler::cleanPath( $srcPath );
        $dstPath = eZDBFileHandler::cleanPath( $dstPath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileMove( '$srcPath', '$dstPath' )" );

        $this->dbbackend->_rename( $srcPath, $dstPath );

        $this->metaData = null;
    }

    /**
     * Move/rename file to $dstPath
     * @param string $dstPath Destination path
     */
    function move( $dstPath )
    {
        $dstPath = eZDBFileHandler::cleanPath( $dstPath );
        $srcPath = $this->filePath;

        eZDebugSetting::writeDebug( 'kernel-clustering', "dfs::fileMove( '$srcPath', '$dstPath' )" );

        $this->dbbackend->_rename( $srcPath, $dstPath );

        $this->metaData = null;
    }

    /**
     * Get list of files stored in database.
     *
     * Used in bin/php/clusterize.php.
     *
     * @param array $scopes return only files that belong to any of these scopes
     * @param boolean $excludeScopes if true, then reverse the meaning of $scopes, which is
     *                               return only files that do not belong to any of the scopes listed in $scopes
     */
    function getFileList( $scopes = false, $excludeScopes = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering',
                                    sprintf( "dfs::getFileList( array( %s ), %d )",
                                             implode( ', ', $scopes ), (int) $excludeScopes ) );
        return $this->dbbackend->_getFileList( $scopes, $excludeScopes );
    }

    /**
    * Returns a clean version of input $path.
    *  - Backslashes are turned into slashes.
    *  - Multiple consecutive slashes are turned into one slash.
    *  - Ending slashes are removed.
    *
    * Examples:
    *  - my\windows\path => my/windows/path
    *  - extra//slashes/\are/fixed => extra/slashes/are/fixed
    *  - ending/slashes/ => ending/slashes
    *
    * @todo -ceZDFSFileHandler write unit test
    * @return string cleaned up $path
    **/
    static function cleanPath( $path )
    {
        if ( !is_string( $path ) )
            return $path;
        return preg_replace( array( "#[/\\\\]+#", "#/$#" ),
                             array( "/",        "" ),
                             $path );
    }

    /**
    * Starts cache generation for the current file.
    *
    * This is done by creating a file named by the original file name, prefixed
    * with '.generating'.
    *
    * @return bool false if the file is being generated, true if it is not
    **/
    public function startCacheGeneration()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "Starting cache generation", "dfs::startCacheGeneration( '{$this->filePath}' )" );

        $generatingFilePath = $this->filePath . '.generating';
        $ret = $this->dbbackend->_startCacheGeneration( $this->filePath, $generatingFilePath );

        // generation granted
        if ( $ret['result'] == 'ok' )
        {
            $this->realFilePath = $this->filePath;
            $this->filePath = $generatingFilePath;
            $this->generationStartTimestamp = $ret['mtime'];
            return true;
        }
        // failure: the file is being generated
        elseif ( $ret['result'] == 'ko' )
        {
            return $ret['remaining'];
        }
        // unhandled error case, should not happen
        else
        {
            eZLog::write( "An error occured starting cache generation on '$generatingFilePath'", 'cluster.log' );
            return false;
        }
    }

    /**
    * Ends the cache generation started by startCacheGeneration().
    **/
    public function endCacheGeneration()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', 'Ending cache generation', "dfs::endCacheGeneration( '{$this->realFilePath}' )" );
        if ( $this->dbbackend->_endCacheGeneration( $this->realFilePath, $this->filePath ) )
        {
            $this->filePath = $this->realFilePath;
            $this->realFilePath = null;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Aborts the current cache generation process.
     *
     * Does so by rolling back the current transaction, which should be the
     * .generating file lock
     **/
    public function abortCacheGeneration()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', 'Aborting cache generation', "dfs::abortCacheGeneration( '{$this->filePath}' )" );
        $this->dbbackend->_abortCacheGeneration( $this->filePath );
        $this->filePath = $this->realFilePath;
        $this->realFilePath = null;
    }

    /**
     * Checks if the .generating file was changed, which would mean that generation
     * timed out. If not timed out, refreshes the timestamp so that storage won't
     * be stolen
     **/
    public function checkCacheGenerationTimeout()
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', 'Checking cache generation timeout', "dfs::checkCacheGenerationTimeout( '{$this->filePath}' )" );
        return $this->dbbackend->_checkCacheGenerationTimeout( $this->filePath, $this->generationStartTimestamp );
    }

    /**
     * Determines the cache type based on the current file's path
     * @return string viewcache, cacheblock or misc
     **/
    protected function _cacheType()
    {
        if ( strstr( $this->filePath, 'cache/content' ) !== false )
            return 'viewcache';
        elseif ( strstr( $this->filePath, 'cache/template-block' ) !== false )
            return 'cacheblock';
        else
            return 'misc';
    }

    /**
     * Magic getter
     **/
    function __get( $propertyName )
    {
        switch ( $propertyName )
        {
            case 'cacheType':
            {
                static $cacheType = null;
                if ( $cacheType == null )
                    $cacheType = $this->_cacheType();
                return $cacheType;
            } break;

            // we only fetch metadata when the status of _metadata is unknown.
            // it means that the metadata has not been fetched before
            // self::resetMetaData can be called to force reload of metadata
            // on the next call
            case 'metaData':
            {
                if ( $this->_metaData === null )
                {
                    $this->loadMetaData();
                }
                return $this->_metaData;
            }
        }
    }

    /**
     * Since eZDFS uses the database, running clusterize.php is required
     * @return bool
     **/
    public function requiresClusterizing()
    {
        return true;
    }

    /**
     * Database backend class
     * Provides metadata operations
     * @var eZDFSFileHandlerMySQLBackend
     **/
    public $dbbackend;

    /**
    * Path to the current file
    * @var string
    **/
    public $filePath = null;

    /**
    * holds the real file path. This is only used when we are generating a cache
    * file, in which case $filePath holds the generating cache file name,
    * and $realFilePath holds the real name
    **/
    private $realFilePath = null;

    /**
     * Indicates that the current cache item is being generated and an old version
     * should be used
     * @var bool
     **/
    private $useStaleCache = false;

    /**
     * Remaining time before cache generation times out
     * @var int
     **/
    public $remainingCacheGenerationTime = false;

    /**
     * Cache generation start timestamp
     *
     * When the instance generates the cached version for a file, this property
     * holds the timestamp at which generation was started. This is used to control
     * a possible generation timeout
     * @var int
     **/
    protected $generationStartTimestamp = false;

    /**
     * Holds actual file metadata, accessed through the self::metadata
     * magic propery. null means the metadata haven't been loaded, false that
     * they've been loaded but the file was not found.
     * Use eZDFSFileHandler::loadMetaData( true ) to force reloading from DB
     *
     * @var array
     **/
    protected $_metaData = null;

    /**
     * Holds the preferences used when stale cache is activated and no expired
     * file is available.
     * This is loaded from file.ini, ClusteringSettings/NonExistantStaleCacheHandling
     * @var array
     **/
    protected static $nonExistantStaleCacheHandling = null;
}
?>
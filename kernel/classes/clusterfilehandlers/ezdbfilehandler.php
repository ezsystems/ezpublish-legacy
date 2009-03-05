<?php
//
// Definition of eZDBFileHandler class
//
// Created on: <19-Apr-2006 16:01:30 vs>
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

/*! \file

  Note: Not all code is using this class for cluster access, see index_image_mysql.php and index_image_pgsql.php for more custom code.
*/

class eZDBFileHandler
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
     * Constructor.
     *
     * $filePath File path. If specified, file metadata is fetched in the constructor.
     */
    function eZDBFileHandler( $filePath = false )
    {
        $filePath = eZDBFileHandler::cleanPath( $filePath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::ctor( '$filePath' )" );

        $optionArray = array( 'iniFile'     => 'file.ini',
                              'iniSection'  => 'ClusteringSettings',
                              'iniVariable' => 'DBBackend' );

        $options = new ezpExtensionOptions( $optionArray );

        $this->backend = eZExtension::getHandlerClass( $options );
        $this->backend->_connect( false );

        // connection failed
        if( $this->backend->db === false )
            throw new eZDBNoConnectionException( $this->backend->dbparams['host'] );

        $this->backendVerify = null;
        $this->filePath = $filePath;

        if ( !isset( $GLOBALS['eZDBFileHandler_Settings'] ) )
        {
            $fileINI = eZINI::instance( 'file.ini' );
            $GLOBALS['eZDBFileHandler_Settings']['NonExistantStaleCacheHandling'] = $fileINI->variable( "ClusteringSettings", "NonExistantStaleCacheHandling" );
            unset( $fileINI );
        }
        $this->nonExistantStaleCacheHandling = $GLOBALS['eZDBFileHandler_Settings']['NonExistantStaleCacheHandling'];
    }

    /*!
     \public
     Load file meta information.

     \param $force File stats will be refreshed if true
    */
    function loadMetaData( $force = false )
    {
        // Fetch metadata.
        if ( $this->filePath === false )
            return;

        // we don't fetch metaData if self::metaData === false, since this means
        // we already tried and got no results, unless $force == true
        if ( ( $this->metaData === false ) && ( $force !== true ) )
            return;

        if ( $force && isset( $GLOBALS['eZClusterInfo'][$this->filePath] ) )
            unset( $GLOBALS['eZClusterInfo'][$this->filePath] );

        // Checks for metadata stored in memory, useful for repeated access
        // to the same file in one request
        // TODO: On PHP5 turn into static member
        if ( isset( $GLOBALS['eZClusterInfo'][$this->filePath] ) )
        {
            $GLOBALS['eZClusterInfo'][$this->filePath]['cnt'] += 1;
            $this->metaData = $GLOBALS['eZClusterInfo'][$this->filePath]['data'];
            return;
        }

        $metaData = $this->backend->_fetchMetadata( $this->filePath );
        if ( $metaData )
            $this->metaData = $metaData;
        else
            $this->metaData = false;

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
     * \public
     * \static
     * \param $filePath Path to the file being stored.
     * \param $scope    Means something like "file category". May be used to clean caches of a certain type.
     * \param $delete   true if the file should be deleted after storing.
     */
    function fileStore( $filePath, $scope = false, $delete = false, $datatype = false )
    {
        $filePath = eZDBFileHandler::cleanPath( $filePath );
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
        $filePath = eZDBFileHandler::cleanPath( $filePath );
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
     *
     * \param $storeLocally If true the file will also be stored on the local file system.
     */
    function storeContents( $contents, $scope = false, $datatype = false, $storeLocally = false )
    {
        if ( $scope === false )
            $scope = 'UNKNOWN_SCOPE';

        if ( $datatype === false )
            $datatype = 'misc';

        $filePath = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::storeContents( '$filePath' )" );
        $this->backend->_storeContents( $filePath, $contents, $scope, $datatype );
        if ( $storeLocally )
        {
            eZFile::create( basename( $filePath ), dirname( $filePath ), $contents, true );
        }
    }

    /**
     * Fetches file from db and saves it in FS under the same name.
     *
     * \public
     * \static
     */
    function fileFetch( $filePath )
    {
        $filePath = eZDBFileHandler::cleanPath( $filePath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileFetch( '$filePath' )" );

        return $this->backend->_fetch( $filePath );
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

                if ( $this->metaData === null )
                    $this->loadMetaData();

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
     * @param int    $mtime Modification time of file, can be set to false if file does not exist.
     * @param int    $expiry Time when file is to be expired, a value of -1 will disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to disable TTL.
     * @return bool
     */
    public static function isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl )
    {
        if ( $mtime == false or $mtime < 0 )
        {
            return true;
        }
        else if ( $ttl === null )
        {
            $ret = $mtime < $expiry;
            return $ret;
        }
        else
        {
            $ret = $mtime < max( $expiry, $curtime - $ttl );
            return $ret;
        }
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

    /*!
     \private
     Stores the data in $fileData to the remote and local file and commits the transaction.

     The parameter $fileData must contain the same as information as the $generateCallback returns as explained in processCache().

     \note This method is just a continuation of the code in processCache() and is not meant to be called alone since it relies on specific state in the database.
     */
    function storeCache( $fileData )
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

        eZDebugSetting::writeDebug( 'kernel-clustering', "Writing new file content to database for {$this->filePath}" );
        $this->storeContents( $binaryData, $scope, $datatype, $storeLocally = false );

        // we end the cache generation process, so that the .generating file
        // is renamed to its final name
        $this->endCacheGeneration( strlen( $binaryData ), $scope, $datatype );

        // the generated file is written to disk
        if ( self::LOCAL_CACHE )
        {
           // Store content also locally
           eZDebugSetting::writeDebug( 'kernel-clustering', "Writing new file content to local file {$this->filePath}" );
           eZFile::create( basename( $this->filePath ), dirname( $this->filePath ), $binaryData, true );
        }

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
     * Fetches file from db and saves it in FS under unique name.
     *
     * \public
     * \static
     * \return filename with path of a saved file. You can use this filename to get contents of file from filesystem.
     */
    function fetchUnique( )
    {
        $filePath = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fetchUnique( '$filePath' )" );

        $fetchedFilePath = $this->backend->_fetch( $filePath, true );
        $this->uniqueName = $fetchedFilePath;
        return $fetchedFilePath;
    }

    /**
     * Fetches file from db and saves it in FS under the same name.
     *
     * \public
     */
    function fetch( $noLocalCache = false )
    {
        $filePath = $this->filePath;
        $metaData = $this->backend->_fetchMetadata( $filePath );
        $mtime = @filemtime( $filePath );
        if ( !$noLocalCache ||
             $metaData === false ||
             $mtime === false ||
             $mtime < $metaData['mtime'] ||
             @filesize( $filePath ) != $metaData['size'] ||
             !is_readable( $filePath ) )
        {
            eZDebugSetting::writeDebug( 'kernel-clustering', "db::fetch( '$filePath' )" );
            $this->backend->_fetch( $filePath );
        }
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
        $filePath = eZDBFileHandler::cleanPath( $filePath );
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
        $filePath = $this->filePath;
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
        if ( $this->metaData === null )
            $this->loadMetaData();
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

        if ( $this->metaData === null )
            $this->loadMetaData();
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

        if ( $this->metaData === null )
            $this->loadMetaData();
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

        return $this->filePath;
    }

    /**
     * \public
     * \static
     * \sa fileDeleteByWildcard()
     */
    function fileDeleteByRegex( $dir, $fileRegex )
    {
        $dir = eZDBFileHandler::cleanPath( $dir );
        $fileRegex = eZDBFileHandler::cleanPath( $fileRegex );
        eZDebug::writeWarning( "Using eZDBFileHandler::fileDeleteByRegex is not recommended since it has some severe performance issues" );
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
        $wildcard = eZDBFileHandler::cleanPath( $wildcard );
        eZDebug::writeWarning( "Using eZDBFileHandler::fileDeleteByWildcard is not recommended since it has some severe performance issues" );
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileDeleteByWildcard( '$wildcard' )" );

        $this->backend->_deleteByWildcard( $wildcard );
    }

    /**
     * \public
     * \static
     */
    function fileDeleteByDirList( $dirList, $commonPath, $commonSuffix )
    {
        foreach ( $dirList as $key => $dirItem )
        {
            $dirList[$key] = eZDBFileHandler::cleanPath( $dirItem );

        }
        $commonPath = eZDBFileHandler::cleanPath( $commonPath );
        $commonSuffix = eZDBFileHandler::cleanPath( $commonSuffix );
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
    function fileDelete( $path, $fnamePart = false )
    {
        $path = eZDBFileHandler::cleanPath( $path );
        $fnamePart = eZDBFileHandler::cleanPath( $fnamePart );
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileDelete( '$path' )" );

        if ( $fnamePart === false )
            $this->backend->_delete( $path );
        if ( $fnamePart !== false )
            $pattern = $path . '/' . $fnamePart . '%';
        else
            $pattern = $path . '/%';
        $this->backend->_deleteByLike( $pattern );
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
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::delete( '$path' )" );

        $this->backend->_delete( $path );
        $this->backend->_deleteByLike( $path . '/%' );

        $this->metaData = null;
    }

    /**
     * Deletes a file that has been fetched before.
     *
     * \public
     * \static
     */
    function fileDeleteLocal( $path )
    {
        $path = eZDBFileHandler::cleanPath( $path );
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
        $path = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::deleteLocal( '$path' )" );
        @unlink( $path );
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
        do
        {
            if ( $count > 0 && $microsleep )
                usleep( $microsleep ); // Sleep a bit to make the database happier
            $count = $this->backend->_purgeByLike( $file . "/%", true, $max, $expiry, 'purge' );
            $this->backend->_purge( $file, true, $expiry, 'purge' );
            if ( $printCallback )
                call_user_func_array( $printCallback,
                                      array( $file, $count ) );
        } while ( $count > 0 );
        // Remove local copy
        if ( is_file( $file ) )
        {
            @unlink( $file );
        }
        else if ( is_dir( $file ) )
        {
            eZDir::recursiveDelete( $file );
        }
    }

    /**
     * Check if given file/dir exists.
     *
     * \public
     * \static
     */
    function fileExists( $path )
    {
        $path = eZDBFileHandler::cleanPath( $path );
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
        $path = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::exists( '$path' )" );
        $rc = $this->backend->_exists( $path );
        return $rc;
    }

    /**
     * Outputs file contents prepending them with appropriate HTTP headers.
     *
     * \public
     * \deprecated This function should not be used since it cannot handle reading errors.
     *             For the PHP 5 port this should be removed.
     */
    function passthrough()
    {
        $path = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::passthrough( '$path' )" );
        if ( $this->metaData === null )
            $this->loadMetaData();
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
        $srcPath = eZDBFileHandler::cleanPath( $srcPath );
        $dstPath = eZDBFileHandler::cleanPath( $dstPath );
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
        $srcPath = eZDBFileHandler::cleanPath( $srcPath );
        $dstPath = eZDBFileHandler::cleanPath( $dstPath );
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
        $srcPath = eZDBFileHandler::cleanPath( $srcPath );
        $dstPath = eZDBFileHandler::cleanPath( $dstPath );
        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileMove( '$srcPath', '$dstPath' )" );

        $this->backend->_rename( $srcPath, $dstPath );

        $this->metaData = null;
    }

    /**
     * Move file.
     *
     * \public
     */
    function move( $dstPath )
    {
        $dstPath = eZDBFileHandler::cleanPath( $dstPath );
        $srcPath = $this->filePath;

        eZDebugSetting::writeDebug( 'kernel-clustering', "db::fileMove( '$srcPath', '$dstPath' )" );

        $this->backend->_rename( $srcPath, $dstPath );

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
                                    sprintf( "db::getFileList( array( %s ), %d )",
                                             implode( ', ', $scopes ), (int) $excludeScopes ) );
        return $this->backend->_getFileList( $scopes, $excludeScopes );
    }

    /*!
     \static
     Returns a clean version of input $path.

     - Backslashes are turned into slashes.
     - Multiple consecutive slashes are turned into one slash.
     - Ending slashes are removed.

     Examples:
     - my\windows\path => my/windows/path
     - extra//slashes/\are/fixed => extra/slashes/are/fixed
     - ending/slashes/ => ending/slashes
     */
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
    private function startCacheGeneration()
    {
        $generatingFilePath = $this->filePath . '.generating';
        $ret = $this->backend->_startCacheGeneration( $this->filePath, $generatingFilePath );

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
    private function endCacheGeneration()
    {
        if ( $this->backend->_endCacheGeneration( $this->realFilePath, $this->filePath ) )
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
    private function abortCacheGeneration()
    {
        $this->backend->_abortCacheGeneration( $this->filePath );
        $this->filePath = $this->realFilePath;
        $this->realFilePath = null;
    }

    /**
     * Checks if the .generating file was changed, which would mean that generation
     * timed out. If not timed out, refreshes the timestamp so that storage won't
     * be stolen
     **/
    private function checkCacheGenerationTimeout()
    {
        return $this->backend->_checkCacheGenerationTimeout( $this->filePath, $this->generationStartTimestamp );
    }

    /**
     * Determines the cache type based on the path
     * @return string viewcache, cacheblock or misc
     **/
    private function _cacheType()
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
        }
    }

    /**
    * Database backend class
    * @var eZDBFileHandlerMysqlBackend
    **/
    public $backend;

    /**
     * Secondary database backend class, used to check for modifications outside
     * of the main transaction scope
     * @var eZDBFileHandlerMysqlBackend
     **/
    public $backendVerify;

    /**
    * Path to the current file
    * @var string
    **/
    public $filePath;

    /**
    * holds the real file path. This is only used when we are generating a cache
    * file, in which case $filePath holds the generating cache file name,
    * and $realFilePath holds the real name
    **/
    public $realFilePath = null;

    /**
    * holds the file's metaData loaded from database
    * The variable's type indicates the exact status:
    *   - null: means that metaData have not been loaded yet
    *   - false: means that metaData were loaded but the file was not found in DB
    *   - array: metaData have been loaded and file exists
    * @todo refactor to a magic property:
    *   - when the property is requested, we check if it's null.
    *   - if it is, we load the metadata from the database and cache them
    *   - if it is not, we return the metaData
    *   - then we add a reinitMetaData() method that resets the property to null
    *     by erasing the cache
    **/
    public $metaData = null;

    /**
     * Indicates that the current cache item is being generated and an old version
     * should be used
     * @var bool
     **/
    private $useStaleCache = false;

    /**
 * Holds the preferences used when stale cache is activated and no expired
 * file is available.
 * This is loaded from file.ini, ClusteringSettings.NonExistantStaleCacheHandling
 **/
    private $nonExistantStaleCacheHandling;

    /**
 * Holds the number of seconds remaining before the generating cache times out
 * @var int
 **/
    private $remainingCacheGenerationTime = false;

    /**
 * When the instance generates the cached version for a file, this property
 * holds the timestamp at which generation was started. This is used to control
 * a possible generation timeout
 * @var int
 **/
    private $generationStartTimestamp = false;
}
?>

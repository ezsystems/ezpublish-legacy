<?php
//
// Definition of  class
//
// Created on: <29-Dec-2008 12:23 bd>
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

/**
* This class implements the new version of the FS file handler.
* It provides support for stalecache, but can not be used on every platform:
* support for unlink(), widely used, is not available on windows platforms
* before PHP 5.3. If you use windows, you can not use this handler unless you
* use this PHP version (beta at this time).
* It is perfectly safe to use it on linux / unix
*
* @property-read cacheType
**/
class eZFS2FileHandler extends eZFSFileHandler
{

    function __construct(  $filePath = false  )
    {
        parent::__construct( $filePath );
        if ( !isset( $GLOBALS['eZFS2FileHandler_Settings'] ) )
        {
            $siteINI = eZINI::instance();
            $GLOBALS['eZFS2FileHandler']['GenerationTimeout'] = $siteINI->variable( "ContentSettings", "CacheGenerationTimeout" );
            unset( $siteINI );

            $fileINI = eZINI::instance( 'file.ini' );
            $GLOBALS['eZFS2FileHandler']['NonExistantStaleCacheHandling'] = $fileINI->variable( "ClusteringSettings", "NonExistantStaleCacheHandling" );
            unset( $fileINI );
        }
        $this->generationTimeout = $GLOBALS['eZFS2FileHandler']['GenerationTimeout'];
        $this->nonExistantStaleCacheHandling = $GLOBALS['eZFS2FileHandler']['NonExistantStaleCacheHandling'];
    }

    /**
    * Creates a single transaction out of the typical file operations for accessing caches.
    * Caches are normally ready from the database or local file, if the entry does not exist
    * or is expired then it generates the new cache data and stores it.
    * This method takes care of these operations and handles the custom code by performing
    * callbacks when needed.
    *
    * The $retrieveCallback is used when the file contents can be used (ie. not re-generation) and
    * is called when the file is ready locally.
    * The function will be called with the file path as the first parameter, the mtime as the second
    * and optionally $extraData as the third.
    * The function must return the file contents or an instance of eZClusterFileFailure which can
    * be used to tell the system that the retrieve data cannot be used after all.
    * $retrieveCallback can be set to null which makes the system go directly to the generation.
    *
    * The $generateCallback is used when the file content is expired or does not exist, in this
    * case the content must be re-generated and stored.
    * The function will be called with the file path as the first parameter and optionally $extraData
    * as the second.
    * The function must return an array with information on the contents, the array consists of:
    *   - scope    - The current scope of the file, is optional.
    *   - datatype - The current datatype of the file, is optional.
    *   - content  - The file content, this can be any type except null.
    *   - binarydata - The binary data which is written to the file.
    *   - store      - Whether *content* or *binarydata* should be stored to the file, if false it will simply return the data. Optional, by default it is true.
    * Note: Set $generateCallback to false to disable generation callback.
    * Note: Set $generateCallback to null to tell the function to perform a write lock but not do any generation, the generation must done be done by the caller by calling storeCache().
    *
    * Either *content* or *binarydata* must be supplied, if not an error is issued and it returns null.
    * If *content* is set it will be used as the return value of this function, if not it will return the binary data.
    * If *binarydata* is set it will be used as the binary data for the file, if not it will perform a var_export on *content* and use that as the binary data.
    *
    * For convenience the $generateCallback function can return a string which will be considered as the
    * binary data for the file and returned as the content.
    *
    * For controlling how long a cache entry can be used the parameters $expiry and $ttl is used.
    * $expiry can be set to a timestamp which controls the absolute max time for the cache, after this
    * time/date the cache will never be used. If the value is set to a negative value or null there the
    * expiration check is disabled.
    *
    * $ttl (time to live) tells how many seconds the cache can live from the time it was stored. If the
    * value is set to negative or null there is no limit for the lifetime of the cache. A value of 0 means
    * that the cache will always expire and practically disables caching.
    * For the cache to be used both the $expiry and $ttl check must hold.
    *
    * @param mixed $retrieveCallback
    * @param mixed $generateCallback
    * @param int   $ttl
    * @param int   $expiry
    * @param array $extraData
    **/
    public function processCache( $retrieveCallback, $generateCallback = null, $ttl = null, $expiry = null, $extraData = null )
    {
        $forceDB = false;
        $timestamp = null;
        $curtime   = time();
        $tries     = 0;

        if ( $expiry < 0 )
            $expiry = null;
        if ( $ttl < 0 )
            $ttl = null;

        while ( true )
        {
            $forceGeneration = false;
            $storeCache      = true;

            // a local, non expired cache file exists (it may still be expired by
            // expiry timestamp, this is tested by the retrieve callback)
            if ( $retrieveCallback !== null && !$this->isExpired( $expiry, $curtime, $ttl ) )
            {
                $mtime = @filemtime( $this->filePath );
                $args = array( $this->filePath, $mtime );
                if ( $extraData !== null )
                    $args[] = $extraData;
                $retval = call_user_func_array( $retrieveCallback, $args );
                if ( !( $retval instanceof eZClusterFileFailure ) )
                {
                    return $retval;
                }

                // if the retrieve callback returns an cluster failure,
                // data have to be generated
                $forceGeneration = true;
            }
            // stale cache mode has been triggered, we use the old cache file
            // if it exists
            elseif ( $this->useStaleCache )
            {
                // a stalecache file exists
                if ( $this->exists() )
                {
                    $args = array( $this->filePath, $curtime );
                    if ( $extraData !== null )
                        $args[] = $extraData;
                    $retval = call_user_func_array( $retrieveCallback, $args );
                    if ( !( $retval instanceof eZClusterFileFailure ) )
                    {
                        return $retval;
                    }
                    $forceGeneration = true;
                }
                // no stalecache file exists, what we do depends on settings
                else
                {
                    // generate the dynamic data without storage
                    if ( $this->nonExistantStaleCacheHandling[ $this->cacheType ] == 'generate' )
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', $this->filePath, "Generation is being processed, generating own version" );
                        break;
                    }
                    // wait for the generating process to be finished (or timedout)
                    else
                    {
                        // write a specific wait loop that uses the max generation time as a limit
                        eZDebugSetting::writeDebug( 'kernel-clustering', "Generation of '$this->filePath' is being processed, waiting", __METHOD__ );
                        $waitedSeconds = 0;
                        while ( $waitedSeconds < $this->remainingCacheGenerationTime )
                        {
                            sleep( 1 );
                            $this->loadMetaData( true );
                            if ( $this->exists() )
                            {
                                eZDebugSetting::writeDebug( 'kernel-clustering', "'$this->filePath' has been generated while waiting, using this newer file", __METHOD__ );
                                $this->useStaleCache = false;
                                $this->remainingCacheGenerationTime = false;

                                // this continues to the main loop 'while (true)'
                                continue 2;
                            }
                            $waitedSeconds++;
                        }

                        // if we reached this point, it means that we are over the estimated timeout value
                        // we try to take the generation over by starting the cache generation. IF this
                        // fails again, it is probably because another waiting process has taken the generation
                        // over. Maybe add a counter here to prevent some kind of death loop ?
                        eZDebugSetting::writeDebug( 'kernel-clustering', "The cache file '$this->filePath' was not generated during the WAIT loop, restarting process", __METHOD__ );
                        $this->useStaleCache = false;
                        $this->remainingCacheGenerationTime = false;
                        $tries++;

                        // this continues to the main loop 'while (true)'
                        continue;
                    }
                }
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
                if ( $retval->errno() != 1 ) // check for non-expiry error codes
                {
                    eZDebug::writeError( "Failed to retrieve data from callback: " . print_r( $retrieveCallback, true ), __METHOD__ );
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
            if ( $generateCallback !== false )
            {
                if ( !$this->useStaleCache )
                {
                    $res = $this->startCacheGeneration();

                    // an integer means that the file is being generated by another process
                    // and we should use an old cache if available.
                    // We do this by setting $useStaleCache
                    // to true, and restarting the loop from the beggining
                    if ( $res !== true )
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', "{$this->filePath} is being generated, switching to staleCache mode", __METHOD__ );
                        $this->useStaleCache = true;
                        $this->remainingCacheGenerationTime = $res;
                        $forceDB = false;
                        continue;
                    }
                }
            }

            // File in DB is outdated or non-existing, call write-callback to generate content
            if ( $generateCallback )
            {
                $args = array( $this->filePath );
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
    * Stores the data in $fileData to the remote and local file and commits the
    * transaction.
    * The parameter $fileData must contain the same as information as the
    * $generateCallback returns as explained in processCache().
    * @note This method is just a continuation of the code in processCache() and
    *       is not meant to be called alone since it relies on specific state in
    *       the database.
    */
    function storeCache( $fileData, $storeCache = true )
    {
        // si on a été placé en timeout, le TS du fichier .generating aura changé
        // dans ce cas, on considère que l'autre process prend la main sur le
        // stockage et on ne stocke rien
        if ( !$this->checkCacheGenerationTimeout() )
            $storeCache = false;

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
            return null;
        }

        if ( $binaryData === null )
            $binaryData = "<" . "?php\n\treturn ". var_export( $fileContent, true ) . ";\n?" . ">\n";

        if ( $fileContent === null )
            $result = $binaryData;
        else
            $result = $fileContent;

        // stale cache handling: we just return the result, no lock has been set
        if ( $this->useStaleCache )
        {
            eZDebugSetting::writeDebug( 'kernel-clustering', "Returning locally generated data without storing" );
            return $result;
        }

        if ( !$storeCache )
        {
            $this->abortCacheGeneration();
            return $result;
        }

        // Store content locally
        $this->storeContents( $binaryData, $scope, $datatype, true );

        // we end the cache generation process, so that the .generating file
        // is renamed to its final name
        $this->endCacheGeneration();

        return $result;
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
     **/
    private function startCacheGeneration()
    {
        eZDebugSetting::writeDebug( "kernel-clustering", $this->filePath, __METHOD__ );

        $ret = true;

        $generatingFilePath = $this->filePath . '.generating';

        // the x flag will throw a warning if the file exists. Allows existence
        // check AND creation at the same time
        if ( !$fp = @fopen( $generatingFilePath, 'x' ) )
        {
            $directory = dirname( $generatingFilePath ) . DIRECTORY_SEPARATOR;

            // the directory we're trying to create the file in does not exist
            eZDebugSetting::writeDebug( 'kernel-clustering', $this->filePath, "Creation failed, checking if '$directory' exists" );

            if ( !file_exists( $directory ) )
            {
                eZDebugSetting::writeDebug( 'kernel-clustering', $this->filePath, "Target directory does not exist, creating and trying again" );

                if ( eZDir::mkdir( $directory, false, true ) )
                    eZDebugSetting::writeDebug( 'kernel-clustering', "Directory '$directory' created, trying to start generation again", __METHOD__ );
                else
                    eZDebugSetting::writeDebug( 'kernel-clustering', "Directory '$directory' failed to be created, it might have been created by another process", __METHOD__ );

                // we check again since the folder may have been created by another
                // process in between. Not likely, though.
                if ( !$fp = @fopen( $generatingFilePath, 'x' ) )
                {
                    $ret = $this->remainingCacheGenerationTime();
                }
            }
            // directory exists, we now check for timeout
            else
            {
                // timeout check
                if ( $mtime = @filemtime( $generatingFilePath ) )
                {
                    $remainingGenerationTime = $this->remainingCacheGenerationTime( $generatingFilePath );
                    eZDebugSetting::writeDebug( 'kernel-clustering', "Remaining generation time: $remainingGenerationTime", __METHOD__ );
                    if ( $remainingGenerationTime < 0 )
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', $this->filePath, "Generating file exists, but generation has timedout, taking over" );
                        touch( $generatingFilePath, time(), time() );
                    }
                    else
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', $this->filePath, "Failed opening file for writing, generation is underway" );
                        $ret = $remainingGenerationTime;
                    }
                }
            }
        }

        // if the generation lock was granted, we can perform our specific file
        // operations: change the file name to the generation name, and store
        // required generating informations
        if ( $ret === true )
        {
            // $fp might not be a valid handle if timeout has occured
            if ( $fp )
                fclose( $fp );

            $this->realFilePath = $this->filePath;
            $this->filePath = $generatingFilePath;
            $this->generationStartTimestamp = filemtime( $this->filePath );
        }

        return $ret;
    }

    /**
     * Ends the cache generation started by startCacheGeneration().
     **/
    private function endCacheGeneration()
    {
        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        $ret = false;
        eZDebugSetting::writeDebug( "kernel-clustering", $this->filePath, __METHOD__ );

        // rename the file to its final name
        if ( eZFile::rename( $this->filePath, $this->realFilePath ) )
        {
            $this->filePath = $this->realFilePath;
            $this->realFilePath = null;
            $this->remainingCacheGenerationTime = false;
            $ret = true;
        }
        else
        {
            eZLog::write( "eZFS2FileHandler::endCacheGeneration: Failed renaming '$this->filePath' to '$this->realFilePath'", 'cluster.log' );
        }

        eZDebug::accumulatorStop( 'dbfile' );

        return $ret;
    }

    /**
     * Aborts the current cache generation process.
     *
     * Does so by rolling back the current transaction, which should be the
     * .generating file lock
     **/
    private function abortCacheGeneration()
    {
        @unlink( $this->filePath );
        $this->filePath = $this->realFilePath;
        $this->realFilePath = null;
        $this->remainingCacheGenerationTime = false;
    }

    /**
    * Checks if the .generating file was changed, which would mean that generation
    * timed out. If not timed out, refreshes the timestamp so that storage won't
    * be stolen
    **/
    private function checkCacheGenerationTimeout()
    {
        clearstatcache();
        // file_exists = false: another process stole the lock and finished the generation
        // filemtime != stored one: another process is generating the file
        if ( !file_exists( $this->filePath ) or ( @filemtime( $this->filePath ) != $this->generationStartTimestamp ) )
        {
            eZDebugSetting::writeDebug( 'kernel-clustering', "'$this->filePath' was changed during generation, looks like a generation timeout", __METHOD__ );
            eZLog::write( "Generation of '$this->filePath' timed out", 'cluster.log' );
            return false;
        }
        else
        {
            $mtime = time();
            touch( $this->filePath, $mtime, $mtime );
            return true;
        }
    }

    /**
    * Returns the remaining time, in seconds, before the generating file times
    * out
    *
    * @param string $filePath
    *
    * @return int Remaining generation seconds. A negative value indicates a timeout.
    **/
    private function remainingCacheGenerationTime( $filePath )
    {
        clearstatcache();
        $mtime = @filemtime( $filePath );
        $remainingGenerationTime = ( $mtime + $this->generationTimeout ) - time();
        return $remainingGenerationTime;
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
        $wildcard = $commonPath .'/{' . $dirs . '}/' . $commonSuffix . '*';

        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDeleteByDirList( '".implode(', ', $dirList)."', '$commonPath', '$commonSuffix' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );
        array_map( array( __CLASS__, '_expire' ), eZSys::globBrace( $wildcard ) );
        eZDebug::accumulatorStop( 'dbfile' );
    }

    /**
     * Deletes the file(s) or directory matching $path and $fnamePart if given
     * @param $path path of the file to delete
     * @param $fnamePart path of the file to delete
     **/
    function fileDelete( $path, $fnamePart = false )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::fileDelete( '$path' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        $list = array();
        if ( $fnamePart !== false )
        {
            $list = glob( $path . "/" . $fnamePart . "*" );
        }
        else
        {
            $list = array( $path );
        }

        foreach ( $list as $path )
        {
            if ( is_file( $path ) )
            {
                self::_expire( $path );
            }
            else
            {
                self::_recursiveExpire( $path );
            }
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
        $path = $this->filePath;
        eZDebugSetting::writeDebug( 'kernel-clustering', "fs::delete( '$path' )" );

        eZDebug::accumulatorStart( 'dbfile', false, 'dbfile' );

        if ( is_file( $path ) )
        {
            self::_expire( $path );
        }
        elseif ( is_dir( $path ) )
        {
            self::_recursiveExpire( $path );
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

    /**
    * Purge local and remote file data for current file.
    **/
    function purge( $printCallback = false, $microsleep = false, $max = false, $expiry = false )
    {
        $file = $this->filePath;
        if ( $max === false )
            $max = 100;
        $count = 0;
        if ( is_file( $file ) )
            $list = array( $file );
        else
            $list = glob( $file . "/*" );
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
                    @unlink( $file );
                ++$count;
            }
            else if ( is_dir( $file ) )
            {
                $list = array_merge( $list, glob( $file . "/*" ) );
            }

            if ( $printCallback )
                call_user_func_array( $printCallback,
                                      array( $file, 1 ) );
        } while ( count( $list ) > 0 );
    }

    /**
    * Expire the given file
    * @param string $path Path of the file to expire
    * @return bool
    **/
    private static function _expire( $path )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', $path, __METHOD__ );
        $ret = touch( $path, self::EXPIRY_TIMESTAMP, self::EXPIRY_TIMESTAMP );
        eZDebugSetting::writeDebug( 'kernel-clustering', date( 'd/m/Y', filemtime( $path ) ), __METHOD__ . ': mtime' );
        return $ret;
    }

    /**
    * Expires all files in a directory
    * @param string $directory
    * @return void
    **/
    private static function _recursiveExpire( $directory )
    {
        eZDebugSetting::writeDebug( 'kernel-clustering', $directory, __METHOD__ );

        if ( $handle = @opendir( $directory ) )
        {
            while ( ( $file = readdir( $handle ) ) !== false )
            {
                if ( ( $file == "." ) || ( $file == ".." ) )
                {
                    continue;
                }
                if ( is_dir( $directory . '/' . $file ) )
                {
                    self::_recursiveExpire( $directory . '/' . $file );
                }
                else
                {
                    self::_expire( $directory . '/' . $file );
                }
            }
            @closedir( $handle );
        }
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
     * holds the real file path. This is only used when we are generating a cache
     * file, in which case $filePath holds the generating cache file name,
     * and $realFilePath holds the real name
     **/
    public $realFilePath = null;

    /**
    * Indicates that the current cache item is being generated and an old version
    * should be used
    * @var bool
    **/
    private $useStaleCache = false;

    /**
    * Generation timeout, in seconds. If a generating file exists for more than
    * $generationTimeout seconds, it is taken over
    * @var int
    **/
    private $generationTimeout;

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
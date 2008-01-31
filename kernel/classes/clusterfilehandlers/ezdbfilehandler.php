<?php
//
// Definition of eZDBFileHandler class
//
// Created on: <19-Apr-2006 16:01:30 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

  Note: Not all code is using this class for cluster access, see index_image_mysql.php and index_image_pgsql.php for more custom code.
*/

require_once( 'lib/ezutils/classes/ezdebugsetting.php' );
require_once( 'lib/ezutils/classes/ezdebug.php' );

/*!
 Controls whether file data from database is cached on the local filesystem.
 \note This is primarily available for debugging purposes.
 */
define( 'EZCLUSTER_LOCAL_CACHE', 1 );
/*!
 Controls the maximum number of metdata entries to keep in memory for this request.
 If the limit is reached the least used entries are removed.
 */
define( 'EZCLUSTER_INFOCACHE_MAX', 200 );

class eZDBFileHandler
{
    /**
     * Constructor.
     *
     * $filePath File path. If specified, file metadata is fetched in the constructor.
     */
    function eZDBFileHandler( $filePath = false )
    {
        $filePath = eZDBFileHandler::cleanPath( $filePath );
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
        $this->backend->_connect( false );
        $this->backendVerify = null;
        $this->filePath = $filePath;
        $this->metaData = false;
//        $this->metaData['name'] = $filePath;

//        $this->loadMetaData();
    }

    /*!
     \public
     Load file meta information.
    */
    function loadMetaData()
    {
        // Fetch metadata.
        if ( $this->filePath === false )
            return;

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
             count( $GLOBALS['eZClusterInfo'] ) >= EZCLUSTER_INFOCACHE_MAX )
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
            include_once( 'lib/ezfile/classes/ezfile.php' );
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
        $fname = $this->filePath;
        $args = array( $fname );
        if ( $extraData !== null )
            $args[] = $extraData;
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
                if ( $retrieveCallback === null ) // No retrieve method so go directly to generate+store
                    break;

                $mtime = @filemtime( $fname );

                if ( eZDBFileHandler::isExpired( $fname, $mtime, $expiry, $curtime, $ttl ) )
                {
                    // Local file is older than global timestamp, check with DB
                    eZDebugSetting::writeDebug( 'kernel-clustering', "Local file (mtime=$mtime) is older than timestamp ($expiry) and ttl($ttl), check with DB" );
                    $forceDB = true;
                }
                if ( !EZCLUSTER_LOCAL_CACHE )
                    $forceDB = true;

                $hasSharedLock = false;

                if ( !$forceDB )
                {
                    if ( $this->metaData === false )
                        $this->loadMetaData();

                    if ( $this->metaData['mtime'] < 0 )
                    {
                        if ( $generateCallback !== false )
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, need to regenerate data" );
                        else
                            eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, cannot get data" );
                        break;
                    }

                    if ( $this->metaData === false || eZDBFileHandler::isExpired( $fname, $mtime, $this->metaData['mtime'], $curtime, $ttl ) )
//                      if ( $this->metaData === false || eZDBFileHandler::isExpired( $fname, $mtime, $expiry, $curtime, $ttl ) )
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', "Local file (mtime=$mtime) is older than DB, check with DB" );
                        $forceDB = true;
                    }
                    else
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', "Processing local file $fname" );
                        $args = array( $fname, $mtime );
                        if ( $extraData !== null )
                            $args[] = $extraData;
                        $retval = call_user_func_array( $retrieveCallback, $args );
                        if ( get_class( $retval ) == 'ezclusterfilefailure' )
                        {
                            break;
                        }
                        return $retval;
                    }
                }

                if ( $this->metaData === false )
                    $this->loadMetaData();

                if ( $this->metaData['mtime'] < 0 )
                {
                    if ( $generateCallback !== false )
                        eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, need to regenerate data" );
                    else
                        eZDebugSetting::writeDebug( 'kernel-clustering', "Database file is deleted, cannot get data" );
                    break;
                }

                if ( !eZDBFileHandler::isExpired( $fname, $this->metaData['mtime'], $expiry, $curtime, $ttl ) )
                {
                    eZDebugSetting::writeDebug( 'kernel-clustering', "Callback from DB file $fname" );
                    if ( EZCLUSTER_LOCAL_CACHE )
                    {
                        $this->fetch();

                        // Figure out which mtime to use for new file, must be larger than
                        // mtime in DB at least.
                        $mtime = $this->metaData['mtime'] + 1;
                        $localmtime = @filemtime( $fname );
                        $mtime = max( $mtime, $localmtime );
                        touch( $fname, $mtime, $mtime );
                        clearstatcache(); // Needed because of touch() call

                        $args = array( $fname, $mtime );
                        if ( $extraData !== null )
                            $args[] = $extraData;
                        $retval = call_user_func_array( $retrieveCallback, $args );
                        if ( get_class( $retval ) == 'ezclusterfilefailure' )
                        {
                            break;
                        }
                        return $retval;
                    }
                    else
                    {
                        $path = $this->fetchUnique();
                        array_shift( $args );
                        array_unshift( $args, $path );

                        $args = array( $fname, $mtime );
                        if ( $extraData !== null )
                            $args[] = $extraData;
                        $retval = call_user_func_array( $retrieveCallback, $args );
                        $this->deleteLocal();
                        if ( get_class( $retval ) == 'ezclusterfilefailure' )
                        {
                            break;
                        }
                        return $retval;
                    }
                }
                eZDebugSetting::writeDebug( 'kernel-clustering', "Database file does not exist, need to regenerate data" );
                break;
            }

            if ( $tries >= 2 )
            {
                eZDebugSetting::writeDebug( 'kernel-clustering', "Reading was retried $tries times and reached the maximum, returning null" );
                return null;
            }

            // Generation part starts here
            if ( isset( $retval ) &&
                 get_class( $retval ) == 'ezclusterfilefailure' )
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
            // Note: false means no generation
            if ( $generateCallback !== false )
            {
                // Lock the entry for exclusive access, if the entry does not exist
                // it will be inserted with mtime=-1
                $res = $this->backend->_exclusiveLock( $fname, 'processCache' );
                if ( !$res || get_class( $res ) == 'ezmysqlbackenderror' )
                {
                    // Cannot get exclusive lock, so return null.
                    return null;
                }

                if ( $this->backendVerify === null )
                {
                    $backendclass = get_class( $this->backend );
                    $this->backendVerify = new $backendclass();
                    $this->backendVerify->_connect( true );
                }

                // This is where we perform a two-phase commit. If any other
                // process or machine has generated the file data and it is valid
                // we will retry the retrieval part and not do the generation.
                $metaData = $this->backendVerify->_fetchMetadata( $fname );

                // If we get metadata we need to check if the file is newer
                if ( $metaData !== false )
                {
                    $mtime = $metaData['mtime'];
                    $expiry = max( $curtime, $expiry );
                    if ( $mtime > 0 && !eZDBFileHandler::isExpired( $fname, $mtime, $expiry, $curtime, $ttl ) )
                    {
                        eZDebugSetting::writeDebug( 'kernel-clustering', "File was generated while we were locked, use that instead" );
                        $this->backend->_rollback( 'processCache' );
                        $this->metaData = false;
                        unset( $GLOBALS['eZClusterInfo'][$fname] );
                        ++$tries;
                        continue; // retry reading file
                    }
                }
            }

            // File in DB is outdated or non-existing, call write-callback to generate content
            if ( $generateCallback )
            {
                $args = array( $fname );
                if ( $extraData !== null )
                    $args[] = $extraData;
                $fileData = call_user_func_array( $generateCallback, $args );
                return $this->storeCache( $fileData );
            }

            break;
        }
        include_once( 'kernel/classes/ezclusterfilefailure.php' );
        return new eZClusterFileFailure( 2, "Manual generation of file data is required, calling storeCache is required" );
    }

    /*!
     \static
     \private
     Calculates if the file data is expired or not.

     \param $fname Name of file, available for easy debugging.
     \param $mtime Modification time of file, can be set to false if file does not exist.
     \param $expiry Time when file is to be expired, a value of -1 will disable this check.
     \param $curtime The current time to check against.
     \param $ttl Number of seconds the data can live, set to null to disable TTL.
     */
    function isExpired( $fname, $mtime, $expiry, $curtime, $ttl )
    {
        if ( $mtime == false )
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

    /*!
     \private
     Stores the data in $fileData to the remote and local file and commits the transaction.

     The parameter $fileData must contain the same as information as the $generateCallback returns as explained in processCache().

     \note This method is just a continuation of the code in processCache() and is not meant to be called alone since it relies on specific state in the database.
     */
    function storeCache( $fileData )
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

        $mtime = false;
        $result = null;
        if ( $binaryData === null &&
             $fileContent === null )
        {
            eZDebug::writeError( "Write callback need to set the 'content' or 'binarydata' entry" );
            $this->backend->_rollback( 'storeCache' );
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
            $this->backend->_rollback( 'storeCache' );
            return $result;
        }

        if ( EZCLUSTER_LOCAL_CACHE )
        {
            // Store content also locally
            eZDebugSetting::writeDebug( 'kernel-clustering', "Writing new file content to local file $fname" );
            include_once( 'lib/ezfile/classes/ezfile.php' );
            eZFile::create( basename( $fname ), dirname( $fname ), $binaryData, true );
            $mtime = @filemtime( $fname );
        }

        eZDebugSetting::writeDebug( 'kernel-clustering', "Writing new file content to database for $fname" );
        $this->storeContents( $binaryData, $scope, $datatype, $mtime );

        $this->backend->_freeExclusiveLock( 'storeCache' );

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
        if ( get_class( $result ) == 'ezclusterfilefailure' )
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

        $fetchWithUniqueName = true;
        $fetchedFilePath = $this->backend->_fetch( $filePath, $fetchWithUniqueName );
        $this->uniqueName = $fetchedFilePath;
        return $fetchedFilePath;
    }

    /**
     * Fetches file from db and saves it in FS under the same name.
     *
     * \public
     */
    function fetch( $cacheLocally = false )
    {
        $filePath = $this->filePath;
        $metaData = $this->backend->_fetchMetadata( $filePath );
        $mtime = @filemtime( $filePath );
        if ( !$cacheLocally ||
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
        if ( $this->metaData === false )
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

        if ( $this->metaData === false )
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

        if ( $this->metaData === false )
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

        $this->metaData = false;
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
            include_once( 'lib/ezfile/classes/ezdir.php' );
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
        if ( $this->metaData === false )
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

        $this->metaData = false;
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

        $this->metaData = false;
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

    /*!
     \static
     Returns a clean version of input $path.

     - Backslashes are turned into slashes.
     - Multiple consecutive slashes are turned into one slash.
     - Ending slashes are removed.

     \example
     my\windows\path => my/windows/path
     extra//slashes/\are/fixed => extra/slashes/are/fixed
     ending/slashes/ => ending/slashes
     \endexample
     */
    function cleanPath( $path )
    {
        if ( !is_string( $path ) )
            return $path;
        return preg_replace( array( "#[/\\\\]+#", "#/$#" ),
                             array( "/",        "" ),
                             $path );
    }
}

?>

<?php
//
// Definition of eZClusterFileHandlerInterface interface
//
// <creation-tag>
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
 * Cluster file handlers interface
 **/
interface eZClusterFileHandlerInterface
{
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
    public function fileStore( $filePath, $scope = false, $delete = false, $datatype = false );

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
    public function fileStoreContents( $filePath, $contents, $scope = false, $datatype = false );

    /**
     * Store file contents using binary data
     *
     * @param string $contents Binary file content
     * @param string $scope    "file category". May be used by cache management
     * @param string $datatype Datatype for the file. Also used to clean cache up
     * @param bool $storeLocally If true the file will also be stored on the
     *                           local file system.
     **/
    public function storeContents( $contents, $scope = false, $datatype = false, $storeLocally = false );

    /**
     * Fetches a file locally
     *
     * @param string $filePath
     *
     * @return string|false the file path, or false if fetching failed
     */
    public function fileFetch( $filePath );

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
    public function processCache( $retrieveCallback, $generateCallback = null, $ttl = null, $expiry = null, $extraData = null );

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
    public function isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl );

    /**
     * Calculates if the current file data is expired or not.
     *
     * @param int    $expiry Time when file is to be expired, a value of -1 will disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to disable TTL.
     * @return bool
     **/
    public function isExpired( $expiry, $curtime, $ttl );

    /**
     * Calculates if the local file is expired or not.
     * @param int    $expiry Time when file is to be expired, a value of -1 will disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to disable TTL.
     * @return bool
     **/
    public function isLocalFileExpired( $expiry, $curtime, $ttl );

    /**
     * Calculates if the DB file is expired or not.
     * @param int    $expiry Time when file is to be expired, a value of -1 will disable this check.
     * @param int    $curtime The current time to check against.
     * @param int    $ttl Number of seconds the data can live, set to null to disable TTL.
     * @return bool
     **/
    public function isDBFileExpired( $expiry, $curtime, $ttl );

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
    public function processFile( $callback, $expiry = false, $extraData = null );

    /**
     * Fetches a cluster file and saves it locally under a new name
     *
     * @return string path to the saved file
     */
    public function fetchUnique();

    /**
     * Fetches file from db and saves it in FS under the same name.
     * @param bool $noLocalCache
     */
    function fetch( $noLocalCache = false );

    /**
     * Returns file contents.
     * @return contents string, or false in case of an error.
     */
    public function fileFetchContents( $filePath );

    /**
     * Returns file contents.
     * @return string|bool contents string, or false in case of an error.
     */
    public function fetchContents();

    /**
     * Returns file metadata.
     */
    public function stat();

    /**
     * Returns file size.
     * @return int|null
     */
    public function size();

    /**
     * Returns file modification time.
     * @return int|null
     **/
    public function mtime();

    /**
     * Returns file name.
     * @return string
     **/
    public function name();

    /**
     * @note has severe performance issues
     */
    public function fileDeleteByRegex( $dir, $fileRegex );

    /**
     * @note has some severe performance issues
     */
    public function fileDeleteByWildcard( $wildcard );

    public function fileDeleteByDirList( $dirList, $commonPath, $commonSuffix );

    /**
     * Deletes specified file/directory.
     *
     * If a directory specified it is deleted recursively.
     */
    public function fileDelete( $path, $fnamePart = false );

    /**
     * Deletes specified file/directory.
     *
     * If a directory specified it is deleted recursively.
     */
    public function delete();

    /**
     * Deletes a file that has been fetched before.
     */
    public function fileDeleteLocal( $path );

    /**
     * Deletes a file that has been fetched before.
     **/
    public function deleteLocal();

    /**
     * Purges local and remote file data for current file.
     **/
    public function purge( $printCallback = false, $microsleep = false, $max = false, $expiry = false );

    /**
     * Check if given file/dir exists.
     * @param string $file
     * @return bool
     */
    public function fileExists( $path );

    /**
     * Check if given file/dir exists.
     *
     * @note This function does not interact with database. Instead, it just
     *       returns existance status determined in the constructor.
     *
     * @return bool
     **/
    public function exists();

    /**
     * Outputs file contents prepending them with appropriate HTTP headers.
     *
     * @deprecated This function should not be used since it cannot handle
     *             reading errors.
     **/
    public function passthrough();

    /**
     * Starts cache generation for the current file.
     *
     * This is done by creating a file named by the original file name, prefixed
     * with '.generating'.
     *
     * @return bool false if the file is being generated, true if it is not
     **/
    public function startCacheGeneration();

    /**
     * Ends the cache generation started by startCacheGeneration().
     **/
    public function endCacheGeneration( $rename = true );

    /**
     * Aborts the current cache generation process.
     *
     * Does so by rolling back the current transaction, which should be the
     * .generating file lock
     **/
    public function abortCacheGeneration();

    /**
     * Checks if the .generating file was changed, which would mean that generation
     * timed out. If not timed out, refreshes the timestamp so that storage won't
     * be stolen
     **/
    public function checkCacheGenerationTimeout();

    /**
     * This method indicates if the cluster file handler requires clusterizing.
     *
     * If the handler does require clusterizing, it will be required/possible to
     * use bin/php/clusterize.php to get data in/out of the cluster when setting
     * it up or disabling it.
     *
     * @return bool
     **/
    public function requiresClusterizing();
}
?>
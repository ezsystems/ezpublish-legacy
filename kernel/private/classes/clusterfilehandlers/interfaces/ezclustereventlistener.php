<?php
/**
 * File containing the eZClusterEventListener class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

/**
 * Interface to be implemented by any cluster event listener
 */
interface eZClusterEventListener
{
    /**
     * Initializes the listener.
     * Here you may sets various options depending on your needs.
     *
     * @return void
     */
    public function initialize();

    /**
     * Returns metadata array for $filepath, as supported by cluster.
     * This array must have following keys :
     *  - name
     *  - name_trunk (name trunk for the entry, if none, equals to "name")
     *  - name_hash (md5 hash of "name")
     *  - scope
     *  - datatype
     *  - mtime (integer)
     *  - expired (integer, 0/1)
     *
     * If no metadata is available, this method must return false
     *
     * @param string $filepath
     * @return array|false
     */
    public function loadMetadata( $filepath );

    /**
     * Updates a file's metadata
     *
     * @param array $metadata Same array as {@link eZClusterEventListener::loadMetadata()}
     * @return void
     */
    public function storeMetadata( array $metadata );

    /**
     * Checks if a file exists on the cluster.
     * If file does exist, this method must return an array (numeric indexes) containing following data:
     *  - name
     *  - mtime
     *
     * <code>
     * return array( 'filename.txt', 1329921039 );
     * </code>
     *
     * Returns false if file doesn't exist
     *
     * @param string $filepath
     * @return array|false
     */
    public function fileExists( $filepath );

    /**
     * Deletes $filepath
     *
     * @param string $filepath
     * @return void
     */
    public function deleteFile( $filepath );

    /**
     * Notifies of a deleteByLike operation
     *
     * @param string $like
     * @return void
     */
    public function deleteByLike( $like );

    /**
     * Notifies of a deleteByWildcard operation
     *
     * @param string $wildcard
     * @return void
     */
    public function deleteByWildcard( $wildcard );

    /**
     * Notifies of a deleteByDirList operation
     *
     * @param array $dirList
     * @param string $commonPath
     * @param string $commonSuffix
     * @return void
     */
    public function deleteByDirList( array $dirList, $commonPath, $commonSuffix );

    /**
     * Deletes all files matching the provided $nametrunk string
     *
     * @param string $nametrunk
     * @return void
     */
    public function deleteByNametrunk( $nametrunk );
}

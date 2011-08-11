<?php
/**
 * File containing the ezpStaticCache interface
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * This interface is used as the basis for the different StaticCache engine implementation
 * @package kernel
 */
interface ezpStaticCache
{
    /**
     * Generates the caches for all URLs that must always be generated.
     *
     * @param bool $quiet If true then the function will not output anything.
     * @param eZCLI|false $cli The eZCLI object or false if no output can be done.
     * @param bool $delay
     */
    public function generateAlwaysUpdatedCache( $quiet = false, $cli = false, $delay = true );
    
    /**
     * Requests the StaticCache handler to (re-)generate cache for a given node list.
     *
     * @param array $nodeList Node list
     * @return bool True if the operation succeed.
     */
    public function generateNodeListCache( $nodeList );
    
    /**
     * Generates the static cache from the configured INI settings.
     *
     * @param bool $force If true then it will create all static caches even if it is not outdated.
     * @param bool $quiet If true then the function will not output anything.
     * @param eZCLI|false $cli The eZCLI object or false if no output can be done.
     * @param bool $delay
     */
    public function generateCache( $force = false, $quiet = false, $cli = false, $delay = true );
    
    /**
     * Generates the caches for the url $url using the currently configured storageDirectory().
     *
     * @param string $url The URL to cache, e.g /news
     * @param int|false $nodeID The ID of the node to cache, if supplied it will also cache content/view/full/xxx.
     * @param bool $skipExisting If true it will not unlink existing cache files.
     * @return bool
     */
    public function cacheURL( $url, $nodeID = false, $skipExisting = false, $delay = true );

    /**
     * Removes the static cache file (index.html) and its directory if it exists.
     * The directory path is based upon the URL $url and the configured static storage dir.
     *
     * @param string $url The URL for the current item, e.g /news
     */
    public function removeURL( $url );

    /**
     * This function goes over the list of recorded actions and excecutes them.
     */
    static function executeActions();
}
?>
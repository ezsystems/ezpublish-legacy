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
    public function generateAlwaysUpdatedCache( $quiet = false, $cli = false, $delay = true );
    
    /**
     * Requests the StaticCache handler to (re-)generate cache for a given node list.
     *
     * @param array $nodeList Node list
     * @return bool True if the operation succeed.
     */
    public function generateNodeListCache( $nodeList );
    
    public function generateCache( $force = false, $quiet = false, $cli = false, $delay = true );
    
    public function cacheURL( $url, $nodeID = false, $skipExisting = false, $delay = true );

    public function removeURL( $url );

    static function executeActions();
}
?>
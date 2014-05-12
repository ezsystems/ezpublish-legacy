<?php
/**
 * File containing the ezjscCacheManager class.
 *
 * @copyright Copyright (C) 2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

class ezjscCacheManager
{
    /**
     * Recursively expires the ezjscore public cache folder
     * @param array $cacheItem
     */
    public static function clearCache( array $cacheItem )
    {
        eZClusterFileHandler::instance()->fileDeleteByDirList(
            array( 'javascript', 'stylesheets' ),
            eZSys::cacheDirectory() . '/' . $cacheItem['path'], ''
        );
    }
}

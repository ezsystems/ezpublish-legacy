<?php
/**
 * File containing the ezjscCacheManager class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

class ezjscCacheManager
{
    static protected $purge = false;

    /**
     * Expires the ezjscore public cache folder's JS/CSS files by deleting all files that are more than n days old
     * The expiry date for JS/CSS files is defined in [Cache_ezjscore]maxage in site.ini
     * @param array $cacheItem
     */
    public static function clearCache( array $cacheItem )
    {
        $clusterHandler = eZClusterFileHandler::instance();

        // Get the existing JavaScript and stylesheet files
        $existingFiles = $clusterHandler->getFileList( [ 'ezjscore' ] );

        // Loop through all the existing files
        foreach( $existingFiles as $file )
        {
            $clusterHandler->filePath = $file;
            $clusterHandler->loadMetaData();
            $metaData = $clusterHandler->metaData;

            // If the timestamp is before than $maxAge
            if( $metaData[ 'mtime' ] < self::getMaxAge() )
            {
                if( self::$purge )
                {
                    eZClusterFileHandler::instance()->filePurge( $file );
                }
                else
                {
                    eZClusterFileHandler::instance()->fileDelete( $file );
                }
            }
        }
    }

    /**
     * Run when --purge is applied, but we do the same as without --purge
     *
     * @param array $cacheItem
     */
    public static function purgeCache( array $cacheItem )
    {
        self::$purge = true;
        self::clearCache( $cacheItem );
    }

    private static function getMaxAge()
    {
        $ini = eZINI::instance();
        if( $ini->hasVariable( 'Cache_ezjscore', 'Maxage' ) )
        {
            if( $ini->variable( 'Cache_ezjscore', 'Maxage' ) )
            {
                return strtotime(
                    '-' .
                    (int) $ini->variable( 'Cache_ezjscore', 'Maxage' ) .
                    ' days'
                );
            }
            else
            {
                return time();
            }
        }
        else
        {
            return strtotime( '-90 days' );
        }
    }
}

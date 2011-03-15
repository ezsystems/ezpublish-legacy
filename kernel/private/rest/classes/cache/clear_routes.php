<?php
/**
 * File containing ezpRestRoutesCacheClear class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */
/**
 * Clear cache handler.
 * Deletes REST routes from APC
 */
class ezpRestRoutesCacheClear
{
    /**
     * Force Route cache expiration,
     * so that APC cache will be flushed and regenerated next REST call
     */
    public static function clearCache()
    {
        $expiryHandler = eZExpiryHandler::instance();
        if( $expiryHandler->hasTimestamp( ezpRestRouter::ROUTE_CACHE_KEY ) )
        {
            $expiryHandler->setTimestamp( ezpRestRouter::ROUTE_CACHE_KEY, 0 );
            $expiryHandler->store();
        }
    }

    public static function purgeCache()
    {
        self::clearCache();
    }
}
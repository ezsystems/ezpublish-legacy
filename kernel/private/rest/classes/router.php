<?php
/**
 * File containing rest router
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
class ezpRestRouter extends ezcMvcRouter
{
    const ROUTE_CACHE_ID = 'ezpRestRouteApcCache',
          ROUTE_CACHE_KEY = 'ezpRestRouteApcCacheKey',
          ROUTE_CACHE_PATH = 'restRouteAPC';

    /**
     * Flag to indicate if APC route cache has already been created
     * @var bool
     */
    public static $isRouteCacheCreated = false;

    /**
     * (non-PHPdoc)
     * @see lib/ezc/MvcTools/src/ezcMvcRouter::createRoutes()
     */
    public function createRoutes()
    {
        if( empty( $this->routes ) )
        {
            // Check if route caching is enabled and if APC is available
            $isRouteCacheEnabled = eZINI::instance( 'rest.ini' )->variable( 'CacheSettings', 'RouteApcCache' ) === 'enabled';
            if( $isRouteCacheEnabled && ezcBaseFeatures::hasExtensionSupport( 'apc' ) )
            {
                $this->routes = $this->getCachedRoutes();
            }
            else
            {
                $this->routes = $this->doCreateRoutes();
            }
        }

        return $this->routes;
    }

    /**
     * Do create the REST routes
     * @return array The route objects
     */
    protected function doCreateRoutes()
    {
        $providerRoutes = ezpRestProvider::getProvider( ezpRestPrefixFilterInterface::getApiProviderName() )->getRoutes();

        $routes = array(
            'fatal'        => new ezpMvcRailsRoute( '/fatal', 'ezpRestErrorController', 'show' ),
        );

        $prefix = eZINI::instance( 'rest.ini' )->variable( 'System', 'ApiPrefix' );
        $prefixedRoutes = ezcMvcRouter::prefix( $prefix, array_merge( $providerRoutes, $routes ) );

        return $prefixedRoutes;
    }

    /**
     * Extract REST routes from APC cache.
     * Cache is generated if needed
     * @return array The route objects
     */
    protected function getCachedRoutes()
    {
        $ttl = (int)eZINI::instance( 'rest.ini' )->variable( 'CacheSettings', 'RouteApcCacheTTL' );

        if( self::$isRouteCacheCreated === false )
        {
            $options = array( 'ttl' => $ttl );
            ezcCacheManager::createCache( self::ROUTE_CACHE_ID, self::ROUTE_CACHE_PATH, 'ezpRestCacheStorageApcCluster', $options );
            self::$isRouteCacheCreated = true;
        }

        $cache = ezcCacheManager::getCache( self::ROUTE_CACHE_ID );
        $cacheKey = self::ROUTE_CACHE_KEY . '_' . ezpRestPrefixFilterInterface::getApiProviderName();
        if( ( $prefixedRoutes = $cache->restore( $cacheKey ) ) === false )
        {
            try
            {
                $prefixedRoutes = $this->doCreateRoutes();
                $cache->store( $cacheKey, $prefixedRoutes );
            }
            catch( Exception $e )
            {
                // Sometimes APC can miss a write. No big deal, just log it.
                // Cache will be regenerated next time
                ezpRestDebug::getInstance()->log( $e->getMessage(), ezcLog::ERROR );
            }
        }

        return $prefixedRoutes;
    }
}
?>

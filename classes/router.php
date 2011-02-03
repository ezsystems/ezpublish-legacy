<?php
/**
 * File containing rest router
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
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
        // Check if route caching is enabled and if APC is available
        $isRouteCacheEnabled = eZINI::instance( 'rest.ini' )->variable( 'CacheSettings', 'RouteApcCache' ) === 'enabled';
        if( $isRouteCacheEnabled && ezcBaseFeatures::hasExtensionSupport( 'apc' ) )
        {
            $prefixedRoutes = $this->getCachedRoutes();
        }
        else
        {
            $prefixedRoutes = $this->doCreateRoutes();
        }
        
        return $prefixedRoutes;
    }
    
    /**
     * Do create the REST routes
     * @return array The route objects
     */
    protected function doCreateRoutes()
    {
        $providerRoutes = ezpRestProvider::getProvider( ezpRestPrefixFilterInterface::getApiProviderName() )->getRoutes();

        $routes = array(
            new ezpMvcRailsRoute( '/fatal', 'ezpRestErrorController', 'show' ),
            new ezpMvcRailsRoute( '/http-basic-auth', 'ezpRestAuthController', 'basicAuth' ),
            new ezpMvcRailsRoute( '/login/oauth', 'ezpRestAuthController', 'oauthRequired' ),
            new ezpMvcRailsRoute( '/oauth/token', 'ezpRestOauthTokenController', 'handleRequest'),

            // ezpRestVersionedRoute( $route, $version )
            // $version == 1 should be the same as if the only the $route had been present
            new ezpRestVersionedRoute( new ezpMvcRailsRoute( '/foo', 'myController', 'myActionOne' ), 1 ),
            new ezpRestVersionedRoute( new ezpMvcRailsRoute( '/foo', 'myController', 'myActionOneBetter' ), 2 ),

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
        if( ( $prefixedRoutes = $cache->restore( self::ROUTE_CACHE_KEY ) ) === false )
        {
            try
            {
                $prefixedRoutes = $this->doCreateRoutes();
                $cache->store( self::ROUTE_CACHE_KEY, $prefixedRoutes );
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

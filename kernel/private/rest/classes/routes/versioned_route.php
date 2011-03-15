<?php
/**
 * File containing the ezpRestVersionedRoute class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Route wrapping around existing instance of ezcMvcRoute providing multiple versions of it.
 */
class ezpRestVersionedRoute implements ezcMvcRoute, ezcMvcReversibleRoute
{
    /**
     * @var ezcMvcRoute Contained route implementing ezcMvcRoute interface
     */
    protected $route;

    /**
     * @var int The version number
     */
    protected $version;

    public function __construct( ezcMvcRoute $route, $version )
    {
        $this->route = $route;
        $this->version = (int)$version;
    }

    public function matches( ezcMvcRequest $request )
    {
        // IF we put versionToken back into route pattern, then the following is true
        // new ezcMvcRailsRoute( '/api/:versionToken/foo', 'myController', 'myAction' ),
        // the token is available at: $request->variables['versionToken']
        // Which means, specifying it in the route pattern, allows us to reuse more of the current MvcTols code.
        // But results in more code and configuration up-front for developers.
        //
        // In the case of ezpRestRequest, a specific getVersion() could also be implemented.
        // /api/v1 + /foo ezpRestVersionedRailsRoute
        // /api/v1/foo -> ezPrestVersionedRailsRoute /api/v1/foo -> /foo

        // matches() ==> is this version string registered? if so call it, if not call the default, as if no version info is provided or fail?
        switch ( ezpRestPrefixFilterInterface::getApiVersion() )
        {
            case $this->version:
                return $this->route->matches( $request );
                break;
        }
    }

    /**
     * Adds a prefix to the route.
     *
     * @param mixed $prefix Prefix to add, for example: '/blog'
     * @return void
     */
    public function prefix( $prefix )
    {
        $this->route->prefix( $prefix );
    }

    /**
     * Generates an URL back out of a route, including possible arguments
     *
     * @param array $arguments
     */
    public function generateUrl( array $arguments = null )
    {
        // ezpRestPrefixFilterInterface::getScheme() ==> '/v'
        $apiPrefix = ezpRestPrefixFilterInterface::getApiPrefix() . '/';
        $apiProviderName = ezpRestPrefixFilterInterface::getApiProviderName();

        return $apiPrefix . ( !$apiProviderName ? ''  : $apiProviderName . '/' ) . 'v' . $this->version . '/' . str_replace( $apiPrefix, '', $this->route->generateUrl( $arguments ) );
    }
}

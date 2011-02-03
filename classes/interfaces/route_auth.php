<?php
/**
 * File containing the ezpRestRouteAuthInterface interface
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */
abstract class ezpRestRouteSecurityInterface
{
    /**
     * Returns the routes for which do not require authentication.
     * @abstract
     * @return array
     */
    abstract public function getOpenRoutes();

    /**
     * Returns the currently configured class for handling Route security.
     *
     * @static
     * @throws ezpRestRouteSecurityFilterNotFoundException
     * @return object
     */
    public static function getRouteSecurityFilter()
    {
        $opt = new ezpExtensionOptions();
        $opt->iniFile = 'rest.ini';
        $opt->iniSection = 'RouteSecurity';
        $opt->iniVariable = 'RouteSecurityImpl';

        $routeSecurityFilterInstance = eZExtension::getHandlerClass( $opt );

        if ( ! $routeSecurityFilterInstance instanceof self )
        {
            throw new ezpRestRouteSecurityFilterNotFoundException();
        }

        return $routeSecurityFilterInstance;
    }
}

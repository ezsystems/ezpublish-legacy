<?php
/**
 * File containing the ezpRestIniRouteSecurity class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestIniRouteSecurity extends ezpRestRouteSecurityInterface
{
    private static $skipRoutes;
    private static $parsedSkipRoutes;

    public function __construct()
    {
        self::$skipRoutes = $this->getSkipRoutes();
    }

    /**
     * Returns the routes which do not require authentication.
     * @return array
     */
    protected function getSkipRoutes()
    {
        $skipRoutes = eZINI::instance( 'rest.ini' )->variableArray( 'RouteSettings', 'SkipFilter' );
        return $skipRoutes;

    }

    public function shallDoActionWithRoute( ezcMvcRoutingInformation $routeInfo )
    {
        $selectedRoute = $routeInfo->controllerClass . '_' . $routeInfo->action;
        return $this->checkRoute( $selectedRoute );
    }

    protected function checkRoute( $route )
    {
        if (self::$parsedSkipRoutes === null )
        {
            self::$parsedSkipRoutes = array();
            foreach ( self::$skipRoutes as $routeRule )
            {
                $route = $routeRule[0];
                $routeVersion = isset( $routeRule[1] ) ? (int)$routeRule[1] : 1;
                self::$parsedSkipRoutes[$route] = $routeVersion;
            }
        }

        return !( isset( self::$parsedSkipRoutes[$route] ) &&
                 self::$parsedSkipRoutes[$route] === ezpRestPrefixFilterInterface::getApiVersion() );
    }


}

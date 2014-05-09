<?php
/**
 * File containing the ezpRestIniRouteFilter class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpRestIniRouteFilter extends ezpRestRouteFilterInterface
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
        return $this->checkRoute( $routeInfo->controllerClass, $routeInfo->action);
    }

    protected function checkRoute( $selectedController, $selectedAction )
    {
        if (self::$parsedSkipRoutes === null )
        {
            self::$parsedSkipRoutes = array();
            foreach ( self::$skipRoutes as $routeRule )
            {
                list( $routeController, $routeAction ) = explode( '_', $routeRule[0] );
                $routeVersion = isset( $routeRule[1] ) ? (int)$routeRule[1] : 1;
                self::$parsedSkipRoutes[$routeController][$routeAction] = $routeVersion;
            }
        }
        $retVal = false;
        if ( isset( self::$parsedSkipRoutes[$selectedController] ) )
        {
            if ( isset( self::$parsedSkipRoutes[$selectedController][$selectedAction] ) )
            {
                $retVal = self::$parsedSkipRoutes[$selectedController][$selectedAction] === ezpRestPrefixFilterInterface::getApiVersion();
            }
            else if ( isset( self::$parsedSkipRoutes[$selectedController]['*'] ) )
            {
                $retVal = self::$parsedSkipRoutes[$selectedController]['*'] === ezpRestPrefixFilterInterface::getApiVersion();
            }
        }
        return !$retVal;
    }


}

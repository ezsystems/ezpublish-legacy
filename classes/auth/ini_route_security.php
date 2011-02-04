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
                self::$parsedSkipRoutes[$routeController] = array( 'action'  => $routeAction,
                                                                   'version' => $routeVersion);
            }
        }

        return !( isset( self::$parsedSkipRoutes[$selectedController] ) &&
                  ( self::$parsedSkipRoutes[$selectedController]['action'] === $selectedAction || self::$parsedSkipRoutes[$selectedController]['action'] === '*' ) &&
                  self::$parsedSkipRoutes[$selectedController]['version'] === ezpRestPrefixFilterInterface::getApiVersion() );
    }


}

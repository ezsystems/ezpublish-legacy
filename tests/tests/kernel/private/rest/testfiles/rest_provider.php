<?php
/**
 * File containing ezpRestTestApiProvider class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */
class ezpRestTestApiProvider implements ezpRestProviderInterface
{
    public function getRoutes()
    {
        $routes = array(
            'ezpRestTest'       => new ezpRestVersionedRoute( new ezpMvcRailsRoute( '/rest/foo', 'ezpRestTestController', 'test' ), 1 ),
            'ezpRestTestVar'    => new ezpRestVersionedRoute( new ezpMvcRailsRoute( '/rest/foo/:dummyVar', 'ezpRestTestController', 'test' ), 1 )
        );

        return $routes;
    }

    /**
     * Returns associated with provider view controller
     *
     * @return ezpRestViewController
     */
    public function getViewController()
    {
        return new ezpRestApiViewController();
    }
}
?>

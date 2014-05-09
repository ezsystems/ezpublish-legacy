<?php
/**
 * File containing ezpRestTestApiProvider class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
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

<?php
/**
 * File containing ezpRestAuthProvider class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */
class ezpRestAuthProvider implements ezpRestProviderInterface
{
    /**
     * @see ezpRestProviderInterface::getRoutes()
     */
    public function getRoutes()
    {
        $routes = array(
            'basicAuth'    => new ezpMvcRailsRoute( '/http-basic-auth', 'ezpRestAuthController', 'basicAuth' ),
            'oauthLogin'   => new ezpMvcRailsRoute( '/oauth/login', 'ezpRestAuthController', 'oauthRequired' ),
            'oauthToken'   => new ezpMvcRailsRoute( '/oauth/token', 'ezpRestOauthTokenController', array( 'http-post' => 'handleRequest' ))
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

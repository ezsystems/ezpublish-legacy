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
    public function createRoutes()
    {
        $routes = array(
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/content/node/:nodeId/listAtom', 'ezpRestAtomController', 'collection' ), 1 ),
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/content/node/:nodeId/list', 'ezpRestContentController', 'list' ), 1 ),
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/content/node/:nodeId', 'ezpRestContentController', 'viewContent' ), 1 ),
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/content/node/:nodeId/fields', 'ezpRestContentController', 'viewFields' ), 1 ),
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/content/node/:nodeId/field/:fieldIdentifier', 'ezpRestContentController', 'viewField' ), 1 ),
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/content/object/:objectId', 'ezpRestContentController', 'viewContent' ), 1 ),
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/content/object/:objectId/fields', 'ezpRestContentController', 'viewFields' ), 1 ),
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/content/object/:objectId/field/:fieldIdentifier', 'ezpRestContentController', 'viewField' ), 1 ),
            new ezcMvcRailsRoute( '/fatal', 'ezpRestErrorController', 'show' ),
            new ezcMvcRailsRoute( '/http-basic-auth', 'ezpRestAuthController', 'basicAuth' ),
            new ezcMvcRailsRoute( '/login/oauth', 'ezpRestAuthController', 'oauthRequired' ),

            // ezpRestVersionedRoute( $route, $version )
            // $version == 1 should be the same as if the only the $route had been present
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/foo', 'myController', 'myActionOne' ), 1 ),
            new ezpRestVersionedRoute( new ezcMvcRailsRoute( '/foo', 'myController', 'myActionOneBetter' ), 2 ),

        );
        return ezcMvcRouter::prefix( '/api', $routes );
    }
}
?>

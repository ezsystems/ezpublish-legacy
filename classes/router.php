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
            new ezcMvcRailsRoute( '/content/node/:nodeId/listAtom', 'ezpRestAtomController', 'collection' ),
            new ezcMvcRailsRoute( '/content/node/:nodeId/list', 'ezpRestContentController', 'list' ),
            new ezcMvcRailsRoute( '/content/node/:nodeId', 'ezpRestContentController', 'viewContent' ),
            new ezcMvcRailsRoute( '/content/node/:nodeId/fields', 'ezpRestContentController', 'viewFields' ),
            new ezcMvcRailsRoute( '/content/node/:nodeId/field/:fieldIdentifier', 'ezpRestContentController', 'viewField' ),
            new ezcMvcRailsRoute( '/content/object/:objectId', 'ezpRestContentController', 'viewContent' ),
            new ezcMvcRailsRoute( '/content/object/:objectId/fields', 'ezpRestContentController', 'viewFields' ),
            new ezcMvcRailsRoute( '/content/object/:objectId/field/:fieldIdentifier', 'ezpRestContentController', 'viewField' ),
            new ezcMvcRailsRoute( '/fatal', 'ezpRestErrorController', 'show' ),
            new ezcMvcRailsRoute( '/http-basic-auth', 'ezpRestAuthController', 'basicAuth' ),
            new ezcMvcRailsRoute( '/login/oauth', 'ezpRestAuthController', 'oauthRequired' )
        );
        return ezcMvcRouter::prefix( '/api', $routes );
    }
}
?>

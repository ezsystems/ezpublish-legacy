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
        return array(
            new ezcMvcRailsRoute( '/api/content/node/:nodeId/listAtom', 'ezpRestAtomController', 'collection' ),
            new ezcMvcRailsRoute( '/api/content/node/:nodeId/list', 'ezpRestContentController', 'list' ),
            new ezcMvcRailsRoute( '/api/content/node/:nodeId', 'ezpRestContentController', 'viewContent' ),
            new ezcMvcRailsRoute( '/api/content/node/:nodeId/fields', 'ezpRestContentController', 'viewFields' ),
            new ezcMvcRailsRoute( '/api/content/node/:nodeId/field/:fieldIdentifier', 'ezpRestContentController', 'viewField' ),
            new ezcMvcRailsRoute( '/api/content/object/:objectId', 'ezpRestContentController', 'viewContent' ),
            new ezcMvcRailsRoute( '/api/content/object/:objectId/fields', 'ezpRestContentController', 'viewFields' ),
            new ezcMvcRailsRoute( '/api/content/object/:objectId/field/:fieldIdentifier', 'ezpRestContentController', 'viewField' ),
            new ezcMvcRailsRoute( '/api/fatal', 'ezpRestErrorController', 'show' ),
            new ezcMvcRailsRoute( '/http-basic-auth', 'ezpRestAuthController', 'basicAuth' ),
            new ezcMvcRailsRoute( '/login/oauth', 'ezpRestAuthController', 'oauthRequired' ),
            new ezcMvcRailsRoute( '/api/dummy', 'ezpRestDummyController', 'foo' ),
            new ezcMvcRailsRoute( '/api/oauth/token', 'ezpRestOauthTokenController', 'handleRequest'),
            );
    }
}
?>

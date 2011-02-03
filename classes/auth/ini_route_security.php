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
    /**
     * Returns the routes which do not require authentication.
     * @return array
     */
    public function getOpenRoutes( )
    {
        $openRoutes = eZINI::instance( 'rest.ini' )->variable( 'RouteSecurity', 'OpenRoutes' );
        return $openRoutes;

    }

}

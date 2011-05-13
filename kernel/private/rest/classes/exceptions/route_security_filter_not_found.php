<?php
/**
 * File containing the ezpRestRouteSecurityFilterNotFoundException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpRestRouteSecurityFilterNotFoundException extends ezpRestException
{
    public function __construct()
    {
        parent::__construct( "Could not find the route security filter." );
    }
}

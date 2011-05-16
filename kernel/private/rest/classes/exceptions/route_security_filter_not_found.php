<?php
/**
 * File containing the ezpRestRouteSecurityFilterNotFoundException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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

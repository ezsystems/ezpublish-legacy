<?php
/**
 * File containing the ezpRestRouteSecurityFilterNotFoundException exception
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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

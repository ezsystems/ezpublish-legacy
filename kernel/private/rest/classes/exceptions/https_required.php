<?php
/**
 * File containing the ezpRestHTTPSRequiredException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestHTTPSRequiredException extends ezpRestException
{
    public function __construct()
    {
        parent::__construct( "Communication over HTTPS is required." );
    }
}

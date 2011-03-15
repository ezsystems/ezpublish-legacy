<?php
/**
 * File containing the ezpRestFilterNotFoundException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestFilterNotFoundException extends ezpRestException
{
    public function __construct( $missingFilter )
    {
        parent::__construct( "Could not find filter {$missingFilter} in the system. Are your settings correct?" );
    }
}

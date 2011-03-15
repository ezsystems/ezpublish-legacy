<?php
/**
 * File containing the ezpRestAuthStyleNotFoundException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestAuthStyleNotFoundException extends ezpRestException
{
    public function __construct()
    {
        parent::__construct( "Selected authentication style was not found" );
    }
}

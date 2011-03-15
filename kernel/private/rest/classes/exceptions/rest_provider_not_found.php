<?php
/**
 * File containing the ezpRestProviderNotfoundException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestProviderNotFoundException extends ezpRestException
{
    public function __construct( $providerName )
    {
        parent::__construct( "The API provider '{$providerName}' could not be found." );
    }
}

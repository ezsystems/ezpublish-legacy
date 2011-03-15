<?php
/**
 * File containing the ezpRestContentRendererNotfoundException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestContentRendererNotFoundException extends ezpRestException
{
    public function __construct( $renderer )
    {
        parent::__construct( "The output content renderer '{$renderer}' could not be found." );
    }
}

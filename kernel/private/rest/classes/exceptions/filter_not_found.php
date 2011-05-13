<?php
/**
 * File containing the ezpRestFilterNotFoundException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpRestFilterNotFoundException extends ezpRestException
{
    public function __construct( $missingFilter )
    {
        parent::__construct( "Could not find filter {$missingFilter} in the system. Are your settings correct?" );
    }
}

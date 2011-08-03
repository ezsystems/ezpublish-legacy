<?php
/**
 * File containing the ezpRestAuthStyleNotFoundException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpRestAuthStyleNotFoundException extends ezpRestException
{
    public function __construct()
    {
        parent::__construct( "Selected authentication style was not found" );
    }
}

<?php
/**
 * File containing the ezpRestAuthStyleNotFoundException exception
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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

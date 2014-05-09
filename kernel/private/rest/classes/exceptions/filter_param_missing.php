<?php
/**
 * File containing the ezpRestFilterMissingParameterException exception
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpRestFilterMissingParameterException extends ezpRestException
{
    public function __construct()
    {
        parent::__construct( "Missing parameter for filter constructor." );
    }
}

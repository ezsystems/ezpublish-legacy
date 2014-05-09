<?php
/**
 * File containing the ezpRestContentRendererNotfoundException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpRestContentRendererNotFoundException extends ezpRestException
{
    public function __construct( $renderer )
    {
        parent::__construct( "The output content renderer '{$renderer}' could not be found." );
    }
}

<?php
/**
 * File containing the ezpContentNotFoundException exception
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpContentNotFoundException extends ezpContentException
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}

?>

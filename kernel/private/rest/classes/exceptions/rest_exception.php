<?php
/**
 * File containing the ezpRestException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * This is the base exception for the eZ Publish REST layer
 *
 * @package rest
 */
abstract class ezpRestException extends ezcBaseException
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>

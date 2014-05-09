<?php
/**
 * File containing the ezpContentException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * This is the base exception for the eZ Publish content API
 *
 * @package ezp_api
 */
abstract class ezpContentException extends ezcBaseException
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>

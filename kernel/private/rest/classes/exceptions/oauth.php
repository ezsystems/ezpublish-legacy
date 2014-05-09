<?php
/**
 * File containing the ezpOauthException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * This is the base exception for the eZ Publish oauth library.
 *
 * @package oauth
 */
abstract class ezpOauthException extends ezcBaseException
{
    public $errorType = null;
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>

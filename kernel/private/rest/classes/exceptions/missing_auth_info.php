<?php
/**
 * File containing the ezpOauthNoAuthInfoException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * This exception is thrown when the client did not provide any authentication
 * information in the request.
 *
 * @package oauth
 */
class ezpOauthNoAuthInfoException extends ezpOauthRequiredException
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>

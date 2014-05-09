<?php
/**
 * File containing the ezpOauthTokenNotFoundException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * This exception is thrown when a token is invalid.
 *
 * Also this exception can be used when a token is expired but can be refreshed.
 *
 * @package oauth
 */
class ezpOauthInvalidTokenException extends ezpOauthRequiredException
{
    public function __construct( $message )
    {
        $this->errorType = ezpOauthErrorType::INVALID_TOKEN;
        parent::__construct( $message );
    }
}
?>

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
 * This exception is thrown when a request is made with a token not
 * repsresenting sufficient scope for the request to be accepted.
 *
 * @package oauth
 */
class ezpOauthInsufficientScopeException extends ezpOauthRequiredException
{
    public function __construct( $message )
    {
        $this->errorType = ezpOauthErrorType::INSUFFICIENT_SCOPE;
        parent::__construct( $message );
    }
}
?>

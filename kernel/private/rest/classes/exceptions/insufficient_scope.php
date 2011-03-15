<?php
/**
 * File containing the ezpOauthTokenNotFoundException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
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
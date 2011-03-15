<?php
/**
 * File containing the ezpOauthTokenNotFoundException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
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
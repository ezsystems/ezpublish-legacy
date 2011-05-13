<?php
/**
 * File containing the ezpOauthTokenNotFoundException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
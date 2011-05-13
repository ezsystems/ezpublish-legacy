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
 * This exception is thrown when the accept token has expired, but can be
 * renewed.
 *
 * @package oauth
 */
class ezpOauthExpiredTokenException extends ezpOauthRequiredException
{
    public function __construct( $message )
    {
        $this->errorType = ezpOauthErrorType::EXPIRED_TOKEN;
        parent::__construct( $message );
    }
}
?>
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
 * This exception is thrown when a request is invalid.
 *
 * An invalid request is the case when a required parameter is missing, when
 * multiple methods of transferring the token is used, or when parameters are
 * repeated.
 *
 * @package oauth
 */
class ezpOauthInvalidRequestException extends ezpOauthBadRequestException
{
    public function __construct( $message )
    {
        $this->errorType = ezpOauthErrorType::INVALID_REQUEST;
        parent::__construct( $message );
    }
}
?>
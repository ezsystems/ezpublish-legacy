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

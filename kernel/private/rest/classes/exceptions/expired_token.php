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

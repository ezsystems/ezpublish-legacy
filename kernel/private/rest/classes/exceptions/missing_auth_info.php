<?php
/**
 * File containing the ezpOauthNoAuthInfoException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
<?php
/**
 * File containing the ezpOauthTokenEndpointErrorType class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpOauthTokenEndpointErrorType
{
    const INVALID_REQUEST = "invalid_request";
    const INVALID_CLIENT = "invalid_client";
    const UNAUTHORIZED_CLIENT = "unauthorized_client";
    const INVALID_GRANT = "invalid_grant";
    const UNSUPPORTED_GRANT_TYPE = "unsupported_grant_type";
    const INVALID_SCOPE = "invalid_scope";

    public static function httpCodeForError( $error )
    {
        switch ( $error )
        {
            case self::UNAUTHORIZED_CLIENT:
                return ezpHttpResponseCodes::UNAUTHORIZED;
                break;
            default:
                return ezpHttpResponseCodes::BAD_REQUEST;
                break;
        }
    }
}

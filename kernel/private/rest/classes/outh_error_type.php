<?php
/**
 * File containing the ezpOauthErrorType
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpOauthErrorType
{
    const INVALID_REQUEST = 'invalid_request';
    const INVALID_TOKEN = 'invalid_token';
    const EXPIRED_TOKEN = 'expired_token';
    const INSUFFICIENT_SCOPE = 'insufficient_scope';

    public static function httpCodeforError( $error )
    {
        // These HTTP response codes are extracted from Section 5.2.1 of the oauth2.0 spec.
        switch ( $error )
        {
            case self::INVALID_REQUEST:
                return ezpHttpResponseCodes::BAD_REQUEST;
                break;
            case self::INVALID_TOKEN:
            case self::EXPIRED_TOKEN:
                return ezpHttpResponseCodes::UNAUTHORIZED;
                break;
            case self::INSUFFICIENT_SCOPE:
                return ezpHttpResponseCodes::FORBIDDEN;
                break;
            default:
                return ezpHttpResponseCodes::SERVER_ERROR;
                break;
        }
    }
}
?>
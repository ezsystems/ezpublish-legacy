<?php
/**
 * File containing the ezpOauthTokenEndpointErrorType class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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

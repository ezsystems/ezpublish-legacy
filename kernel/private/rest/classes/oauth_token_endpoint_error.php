<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oms
 * Date: 21.12.10
 * Time: 12.17
 * To change this template use File | Settings | File Templates.
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

<?php
/**
 * File containing the ezpOauthFilter
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpOauthFilter extends ezcAuthenticationFilter
{
    const STATUS_TOKEN_INVALID = 1;
    const STATUS_TOKEN_EXPIRED = 2;
    const STATUS_TOKEN_INSUFFICIENT_SCOPE = 3;
    const STATUS_TOKEN_UNAVAILABLE = 4;

    /**
     * Struct holding oAuth token info
     * @var ezpRestToken
     */
    public static $tokenInfo;

    // @TODO Need to setup status codes for oauth
    public function run( $credentials )
    {
        $status = self::STATUS_TOKEN_INVALID; // Assume invalid token as a default, safer
        if ( !isset( $credentials->id ) )
        {
            return self::STATUS_TOKEN_UNAVAILABLE;
        }
        else
        {
            // Fetch and validate token for validity and optionally scope.
            // Either let the request pass, or immediately bail with 401.
            // Section 5.2.1 for error handling.
            //
            // invalid_request missing required params -> 400
            //
            // invalid_token Expired token which cannot be refreshed -> 401
            //
            // expired_token Token has expired -> 401
            //
            // insufficient_scope The requested scope is outside scope associated with token -> 403
            //
            // Do not include error info for requests which did not contain auth details.ref. 5.2.1

            // Checking for existance of token
            // Query made below will check if user's access grant is still valid
            $tokenInfo = ezpRestToken::fetch( $credentials->id );
            if ( !$tokenInfo instanceof ezpRestToken )
            {
                return self::STATUS_TOKEN_INVALID;
                //throw new ezpOauthInvalidTokenException( "Specified token does not exist." );
            }

            // Check expiry of token
            if ( $tokenInfo->expirytime > 0 )
            {
                if ( $tokenInfo->expirytime < time() )
                {
                    $d = date( "c", $tokenInfo->expirytime );
                    return self::STATUS_TOKEN_EXPIRED;
                    //throw new ezpOauthExpiredTokenException( "Token expired on {$d}" );
                }
            }

            self::$tokenInfo = $tokenInfo;

            // Scope checking to be implemented.
            // Currently some hooks ought to be added to eZP to maximise the
            // benefit to this field.

            $status = self::STATUS_OK;
        }

        return $status;
    }
}
?>

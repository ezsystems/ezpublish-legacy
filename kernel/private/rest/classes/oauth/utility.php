<?php
/**
 * File containing the ezpOuathUtility class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Functionality for working against the draft 10 of the oauth2 spec.
 *
 * @package rest
 */
class ezpOauthUtility extends ezpRestModel
{
    const AUTH_HEADER_NAME     = 'Authorization';
    const AUTH_CGI_HEADER_NAME = 'HTTP_AUTHORIZATION';

    /**
     * Retrieving token as per section 5 of draft-ietf-oauth-v2-10
     *
     * Token can be present inside the Authorize header, inside a URI query
     * parameter, or in the HTTP body.
     *
     * According to section 5.1 the header is the preferred way, and the query
     * component and HTTP body are only looked at if no such header can be found.
     *
     * @TODO A configuration mechanism should alternatively let us select which
     * method to use: 1. header, 2. query component, 3. http body, in other words
     * to override the default behaviour according to spec.
     *
     * @param string $ezcMvcRequest
     * @return void
     */
    public static function getToken( ezcMvcRequest $request )
    {
        // 1. Should first extract required token from the request object
        //    as we know that the request parser does not support this at the
        //    moment we, will skip to the fallback right away. That is to say,
        //    ideally the request parser would make this header available to us,
        //    when available, automatically.

        $token = null;
        $checkStack = array( 'header', 'get', 'post' );

        foreach ( $checkStack as $step )
        {
            switch ( $step )
            {
                case 'header':
                    $token = self::getTokenFromAuthorizationHeader();
                break;

                case 'get':
                    $token = self::getTokenFromQueryComponent( $request );
                break;

                case 'post':
                    $token = self::getTokenFromHttpBody( $request );
                break;
            }

            if ( isset( $token ) ) // Go out of the loop if we find the token during the iteration
            {
                break;
            }
        }

        return $token;
    }


    /**
     * Extracts the OAuth token from the HTTP header, Authorization.
     *
     * The token is transmitted via the OAuth Authentication scheme ref.
     * Section 5.1.1.
     *
     * PHP does not expose the Authorization header unless it uses the 'Basic'
     * or 'Digest' schemes, and it is therefore extracted from the raw Apache
     * headers.
     *
     * On systems running CGI or Fast-CGI PHP makes this header available via
     * the <var>HTTP_AUTHORIZATION</var> header.
     * @link http://php.net/manual/en/features.http-auth.php
     * @throws ezpOauthInvalidRequestException
     * @return string The access token string.
     */
    protected static function getTokenFromAuthorizationHeader()
    {
        $token = null;
        $authHeader = null;
        if ( function_exists( 'apache_request_headers' ) )
        {
            $apacheHeaders = apache_request_headers();
            if ( isset( $apacheHeaders[self::AUTH_HEADER_NAME] ) )
                $authHeader = $apacheHeaders[self::AUTH_HEADER_NAME];
        }
        else
        {
            if ( isset( $_SERVER[self::AUTH_CGI_HEADER_NAME] ) )
                $authHeader = $_SERVER[self::AUTH_CGI_HEADER_NAME];
        }

        if ( isset( $authHeader ) )
        {
            $tokenPattern = "/^(?P<authscheme>OAuth)\s(?P<token>[a-zA-Z0-9]+)$/";
            $match = preg_match( $tokenPattern, $authHeader, $m );
            if ( $match > 0 )
            {
                $token = $m['token'];
            }
        }


        return $token;
    }

    /**
     * Extracts OAuth token query component aka GET parameter.
     *
     * For more information See section 5.1.2 of  oauth2.0 v10
     *
     * @throws ezpOauthInvalidRequestException
     * @param ezcMvcRequest $request
     * @return string The access token string
     */
    protected static function getTokenFromQueryComponent( ezpRestRequest $request )
    {
        $token = null;
        if( isset( $request->get['oauth_token'] ) )
        {
            //throw new ezpOauthInvalidRequestException( "OAuth token not found in query component." );
            $token = $request->get['oauth_token'];
        }

        return $token;
    }

    /**
     * Extracts OAuth token fro HTTP Post body.
     *
     * For more information see section 5.1.3 oauth2.0 v10
     * @param ezpRestRequest $request
     * @return string The access token string
     */
    protected static function getTokenFromHttpBody( ezpRestRequest $request )
    {
        $token = null;
        if ( isset( $request->post['oauth_token'] ) )
        {
            $token = $request->post['oauth_token'];
        }

        return $token;
    }

    /**
     * Handles a refresh_token request.
     * Returns the new token object as ezpRestToken
     * @param string $clientId Client identifier
     * @param string $clientSecret Client secret key
     * @param string $refreshToken Refresh token
     * @return ezpRestToken
     * @throws ezpOauthInvalidRequestException
     */
    public static function doRefreshToken( $clientId, $clientSecret, $refreshToken )
    {
        $client = ezpRestClient::fetchByClientId( $clientId );
        $tokenTTL = (int)eZINI::instance( 'rest.ini' )->variable( 'OAuthSettings', 'TokenTTL' );

        if (! ( $client instanceof ezpRestClient ) )
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );

        if ( !$client->validateSecret( $clientSecret ) )
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );

        $session = ezcPersistentSessionInstance::get();

        $q = $session->createFindQuery( 'ezpRestToken' );
        $q->where( $q->expr->eq( 'refresh_token', $q->bindValue( $refreshToken ) ) );
        $refreshInfo = $session->find( $q, 'ezpRestToken' );

        if ( empty( $refreshInfo) )
        {
            throw new ezpOauthInvalidRequestException( "Specified refresh-token does not exist." );
        }

        $refreshInfo = array_shift( $refreshInfo );

        // Validate client is still authorized, then validate code is not expired
        $authorized = ezpRestAuthorizedClient::fetchForClientUser( $client, eZUser::fetch( $refreshInfo->user_id ) );

        if ( !($authorized instanceof ezpRestAuthorizedClient ) )
        {
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );
        }


        // Ideally there should be a separate expiry for refresh tokens here, for now allow.
        $newToken = new ezpRestToken();
        $newToken->id = ezpRestToken::generateToken( '' );
        $newToken->refresh_token = ezpRestToken::generateToken( '' );
        $newToken->client_id = $clientId;
        $newToken->user_id = $refreshInfo->user_id;
        $newToken->expirytime = time() + $tokenTTL;

        $session->save( $newToken );
        $session->delete( $refreshInfo );

        return $newToken;
    }

    /**
     * Generates a new token against an authorization_code
     * Auth code is checked against clientId, clientSecret and redirectUri as registered for client in admin
     * Auth code is for one-use only and will be removed once the access token generated
     * @param string $clientId Client identifier
     * @param string $clientSecret Client secret key
     * @param string $authCode Authorization code provided by the client
     * @param string $redirectUri Redirect URI. Must be the same as registered in admin
     * @return ezpRestToken
     * @throws ezpOauthInvalidRequestException
     * @throws ezpOauthInvalidTokenException
     * @throws ezpOauthExpiredTokenException
     */
    public static function doRefreshTokenWithAuthorizationCode( $clientId, $clientSecret, $authCode, $redirectUri )
    {
        $client = ezpRestClient::fetchByClientId( $clientId );
        $tokenTTL = (int)eZINI::instance( 'rest.ini' )->variable( 'OAuthSettings', 'TokenTTL' );

        if (! ( $client instanceof ezpRestClient ) )
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );

        if ( !$client->validateSecret( $clientSecret ) )
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );

        if ( !$client->isEndPointValid( $redirectUri ) )
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_REQUEST );

        $session = ezcPersistentSessionInstance::get();

        $q = $session->createFindQuery( 'ezpRestAuthcode' );
        $q->where( $q->expr->eq( 'id', $q->bindValue( $authCode ) ) );
        $codeInfo = $session->find( $q, 'ezpRestAuthcode' );

        if ( empty( $codeInfo ) )
        {
            throw new ezpOauthInvalidTokenException( "Specified authorization code does not exist." );
        }
        $codeInfo = array_shift( $codeInfo );

        // Validate client is still authorized, then validate code is not expired
        $authorized = ezpRestAuthorizedClient::fetchForClientUser( $client, eZUser::fetch( $codeInfo->user_id ) );

        if ( !($authorized instanceof ezpRestAuthorizedClient ) )
        {
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );
        }

        // Check expiry of authorization_code
        if ( $codeInfo->expirytime != 0 )
        {
            if ( $codeInfo->expirytime < time() )
            {
                $d = date( "c", $codeInfo->expirytime );
                throw new ezpOauthExpiredTokenException( "Authorization code expired on {$d}" );
            }
        }

        // code ok, create access and refresh tokens
        $accessToken = ezpRestToken::generateToken( '' );
        $refreshToken = ezpRestToken::generateToken( '' );

        $token = new ezpRestToken();
        $token->id = $accessToken;
        $token->refresh_token = $refreshToken;
        $token->client_id = $clientId;
        $token->user_id = $codeInfo->user_id;
        $token->expirytime = time() + $tokenTTL;

        $session = ezcPersistentSessionInstance::get();
        $session->save( $token );

        // After an auth code is used, we'll remove it so it is not abused
        $session->delete( $codeInfo );

        return $token;
    }
}
?>

<?php
/**
 * File containing the ezpRestOauthTokenController class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Controller for OAuth token endpoint.
 *
 * @throws ezpOauthExpiredTokenException|ezpOauthInvalidRequestException|ezpOauthInvalidTokenException
 * 
 */
class ezpRestOauthTokenController extends ezcMvcController
{
    /**
     * Handles the POST request which is sent to obtain token data.
     *
     * Currently only authorization code access grant is supported, section 4.1.1
     *
     * @return ezcMvcResult
     */
    public function doHandleRequest()
    {
        // Check that the correct params are present
        // Params for token endpoint per section 4
        // REQUIRED: grant_type, client_id, client_secret
        // OPTIONAL: scope
        //
        // Supported grant types are: authorization_code and refresh_token
        //
        // Additional params for 'authorization_code', Section 4.1.1
        // REQUIRED: code, redirect_uri
        //
        // Additional param for 'refresh_token", Section 4.1.4
        // REQUIRED: refresh_token

        // Defining the required params for each stage of operation
        $initialRequiredParams = array( 'grant_type', 'client_id', 'client_secret' );

        // params for grant_type=authorization_code
        $codeRequiredParams = array( 'code', 'redirect_uri' );

        // params for grant_type=refresh_token
        $refreshRequiredParams = array( 'refresh_token' );

        $this->checkParams( $initialRequiredParams );

        // We can get the first set of required params
        $grant_type = $this->request->post['grant_type'];
        $client_id = $this->request->post['client_id'];
        $client_secret = $this->request->post['client_secret'];

        if ( !$this->validateGrantType( $grant_type ) )
        {
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::UNSUPPORTED_GRANT_TYPE );
        }

        switch ( $grant_type )
        {
            case 'authorization_code':
                $this->checkParams( $codeRequiredParams );

                $authCode = $this->request->post['code'];
                $redirect_uri = $this->request->post['redirect_uri'];

                $client = ezpRestClient::fetchByClientId( $client_id );

                if (! ( $client instanceof ezpRestClient ) )
                    throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );

                if ( !$client->validateSecret( $client_secret ) )
                    throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );


                if ( !$client->isEndPointValid( $redirect_uri ) )
                    throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_REQUEST );


                $session = ezcPersistentSessionInstance::get();

                $q = $session->createFindQuery( 'ezpRestAuthcode' );
                $q->where( $q->expr->eq( 'id', $q->bindValue( $authCode ) ) );
                $codeInfo = $session->find( $q, 'ezpRestAuthcode' );

                if ( empty( $codeInfo ) )
                {
                    // return self::STATUS_TOKEN_INVALID;
                    throw new ezpOauthInvalidTokenException( "Specified authorization code does not exist." );
                }
                $codeInfo = array_shift( $codeInfo );

                // Validate client is still authorized, then validate code is not expired
                $authorized = ezpRestAuthorizedClient::fetchForClientUser( $client, eZUser::fetch( $codeInfo->user_id ) );

                if ( !($authorized instanceof ezpRestAuthorizedClient ) )
                {
                    throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );
                }


                // Check expiry of token
                if ( $codeInfo->expirytime !== 0 )
                {
                    if ( $codeInfo->expirytime < time() )
                    {
                        $d = date( "c", $codeInfo->expirytime );
                        // return self::STATUS_TOKEN_EXPIRED;
                        throw new ezpOauthExpiredTokenException( "Token expired on {$d}" );
                    }
                }

                // code eok, create access token
                $accessToken = ezpRestToken::generateToken( '' );
                $refreshToken = ezpRestToken::generateToken( '' );

                $token = new ezpRestToken();
                $token->id = $accessToken;
                $token->refresh_token = $refreshToken;
                $token->client_id = $client_id;
                $token->user_id = $codeInfo->user_id;
                $token->expirytime = time() + 3600;

                $session = ezcPersistentSessionInstance::get();
                $session->save( $token );

                // After an auth code is used, we'll remove it so it is not abused
                $session->delete( $codeInfo );

                $r = new ezcMvcResult;
                //Spec not clear whether to return json response or redirect_uri, for client device workflow
                $r->status = new ezcMvcExternalRedirect( $redirect_uri . '?access_token=' . $token->id );
                return $r;
                break;

            case 'refresh_token':
                $this->checkParams( $refreshRequiredParams );

                $refreshToken = $this->request->post['refresh_token'];

                $client = ezpRestClient::fetchByClientId( $client_id );

                if (! ( $client instanceof ezpRestClient ) )
                    throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_CLIENT );

                if ( !$client->validateSecret( $client_secret ) )
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
                $newToken->client_id = $client_id;
                $newToken->user_id = $refreshInfo->user_id;
                $newToken->expirytime = time() + 3600;

                $session->save( $newToken );
                $session->delete( $refreshInfo );

                $result = new ezcMvcResult();
                $result->status = new ezcMvcExternalRedirect( $client->endpoint_uri . '?access_token=' . $newToken->id . '&refresh_token=' . $newToken->refresh_token );
                return $result;
                break;
        }



    }

    protected function validateClient( $client_id, $client_secret )
    {

    }

    protected function checkParams( $paramsToCheck )
    {
        foreach( $paramsToCheck as $param )
        {
            $missingParams = array();
            if ( !isset( $this->request->post[$param] ) )
            {
                $missingParams[] = $param;
            }
        }
        // For now bail out on first missing param, as spec does not yet specify
        // how to specify multiple missing params simultaneously.

        if ( !empty( $missingParams ))
        {
            $error = array_pop( $missingParams );

            // @TODO need a more sophisticated error type detection here.
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::INVALID_REQUEST );
        }

    }

    protected function validateGrantType( $grant )
    {
        $allowedGrantTypes = array( 'authorization_code',
                                    'refresh_token',
                                  );


        return in_array( $grant, $allowedGrantTypes );
    }
}

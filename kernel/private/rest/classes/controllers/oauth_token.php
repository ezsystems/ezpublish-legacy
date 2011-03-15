<?php
/**
 * File containing the ezpRestOauthTokenController class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
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
        $tokenTTL = (int)eZINI::instance( 'rest.ini' )->variable( 'OAuthSettings', 'TokenTTL' );

        if ( !$this->validateGrantType( $grant_type ) )
        {
            throw new ezpOauthInvalidRequestException( ezpOauthTokenEndpointErrorType::UNSUPPORTED_GRANT_TYPE );
        }

        $result = new ezcMvcResult();
        $newToken = null;

        switch ( $grant_type )
        {
            case 'authorization_code':
                $this->checkParams( $codeRequiredParams );
                $newToken = ezpOauthUtility::doRefreshTokenWithAuthorizationCode( $client_id, $client_secret, $this->request->post['code'], $this->request->post['redirect_uri']);
                break;

            case 'refresh_token':
                $this->checkParams( $refreshRequiredParams );
                $newToken = ezpOauthUtility::doRefreshToken( $client_id, $client_secret, $this->request->post['refresh_token'] );
                break;
        }

        if ( !$newToken instanceof ezpRestToken )
        {
            throw new ezpOauthInvalidTokenException( ezpOauthTokenEndpointErrorType::INVALID_REQUEST );
        }

        $result->variables['access_token'] = $newToken->id;
        $result->variables['refresh_token'] = $newToken->refresh_token;
        $result->variables['expires_in'] = $tokenTTL;
        return $result;
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

            throw new ezpOauthInvalidRequestException( "Missing {$error} parameter." );
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

<?php
/**
 * File containing ezpRestOauthAuthenticationStyle
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestOauthAuthenticationStyle extends ezpRestAuthenticationStyle implements ezpRestAuthenticationStyleInterface
{
    // @TODO auth vars should probably be shared internally here.
    public function setup( ezcMvcRequest $request )
    {
        // Setup for testing credentials
        // Check for required components (fail if not present)
        // Fail if too many components are required (according to spec, later)
        // Validate components

        $token = ezpOauthUtility::getToken( $request );
        $cred = new ezcAuthenticationIdCredentials( $token );
        $oauthFilter = new ezpOauthFilter();

        $auth = new ezcAuthentication( $cred );
        $auth->addFilter( $oauthFilter );
        return $auth;
    }

    public function authenticate( ezcAuthentication $auth, ezcMvcRequest $request )
    {
        if ( !$auth->run() )
        {
            $aStatuses = $auth->getStatus();
            $statusCode = null;
            foreach ( $aStatuses as $status )
            {
                if ( key( $status ) === 'ezpOauthFilter' )
                {
                    $statusCode = current( $status );
                    break;
                }
            }

            $request->variables['ezpAuth_redirUrl'] = $request->uri;
            $request->variables['ezpAuth_reason'] = $statusCode;
            $request->uri = "{$this->prefix}/auth/oauth/login";
            return new ezcMvcInternalRedirect( $request );
        }
        else
        {
            $user = eZUser::fetch( ezpOauthFilter::$tokenInfo->user_id );
            if ( !$user instanceof eZUser )
            {
                throw new ezpUserNotFoundException( ezpOauthFilter::$tokenInfo->user_id );
            }

            return $user;
        }
    }

    /**
     * Method extracted from MvcAuthenticationTiein
     *
     * Checks the status from the authentication run and adds the reasons as
     * variable to the $result.
     *
     * This method uses the information that is set by the
     * runAuthRequiredFilter() filter to generate an user-readable text of the
     * found $reasons and sets these as the variable ezcAuth_reasons in
     * the $result. You can supply your own mapping from status codes to
     * messages, but a default is provided. Please refer to the Authentication
     * tutorial for information about status codes.
     *
     * @param ezcMvcResult $result
     * @param array(string) $reasons
     * @param array(string=>array(int=>string) $errorMap
     */
    function processLoginRequired( ezcMvcResult $res, $reasons, $errorMap = null )
    {
        $reasonText = array();

        if ( $errorMap === null )
        {
            $errorMap = array(
                'ezpOauthFilter' => array(
                    ezpOauthFilter::STATUS_TOKEN_INVALID            => 'Token has expired.',
                    ezpOauthFilter::STATUS_TOKEN_EXPIRED            => 'Token has expired, please refresh it.',
                    ezpOauthFilter::STATUS_TOKEN_INSUFFICIENT_SCOPE => 'You do have do have sufficient scope to access this resource.',
                ),
            );
        }

        foreach ( $reasons as $line )
        {
            list( $key, $value ) = each( $line );
            $reasonText[] = $errorMap[$key][$value];
        }
        $res->variables['ezcAuth_reasons']  = $reasonText;
    }
}
?>

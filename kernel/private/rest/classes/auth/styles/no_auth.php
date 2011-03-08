<?php
/**
 * File containing ezpRestNoAuthStyle class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

/**
 * This auth style is used when no authentication is required for current REST request
 * A default user will be returned. UserID to use has to be set in rest.ini/[Authentication].DefaultUserID (fallback is anonymous user)
 */
class ezpRestNoAuthStyle extends ezpRestAuthenticationStyle implements ezpRestAuthenticationStyleInterface
{
    /**
     * @see ezpRestAuthenticationStyleInterface::setup()
     */
    public function setup( ezcMvcRequest $request )
    {
        // Use either rest.ini/[Authentication].DefaultUserID if provided, or AnonymousUserID
        $defaultUserID = (int)eZINI::instance()->variable( 'UserSettings', 'AnonymousUserID' );
        $restDefaultUserID = eZINI::instance( 'rest.ini' )->variable( 'Authentication', 'DefaultUserID' );
        if ( $restDefaultUserID !== '' )
        {
            $defaultUserID = (int)$restDefaultUserID;
        }

        $cred = new ezcAuthenticationIdCredentials( $defaultUserID );
        $auth = new ezcAuthentication( $cred );
        $auth->addFilter( new ezpNativeUserAuthFilter() );

        return $auth;
    }

    /**
     * @see ezpRestAuthenticationStyleInterface::authenticate()
     */
    public function authenticate( ezcAuthentication $auth, ezcMvcRequest $request )
    {
        if ( !$auth->run() && $request->uri !== "{$this->prefix}/fatal" ) // /fatal URI should never fail
        {
            throw new ezpUserNotFoundException( $auth->credentials->id );
        }
        else
        {
            return eZUser::fetch( $auth->credentials->id );
        }
    }
}
?>
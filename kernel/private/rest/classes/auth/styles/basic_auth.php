<?php
/**
 * File containing the basic auth style
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpRestBasicAuthStyle extends ezpRestAuthenticationStyle implements ezpRestAuthenticationStyleInterface
{
    public function setup( ezcMvcRequest $request )
    {
        if ( $request->authentication === null )
        {
            $authRequest = clone $request;
            $authRequest->uri = "{$this->prefix}/auth/http-basic-auth";
            $authRequest->protocol = "http-get";

            return new ezcMvcInternalRedirect( $authRequest );
        }

        $cred = new ezcAuthenticationPasswordCredentials( $request->authentication->identifier,
                                                          md5( "{$request->authentication->identifier}\n{$request->authentication->password}" ) );
        $authDbInfo = new ezcAuthenticationDatabaseInfo( ezcDbInstance::get(), 'ezuser', array( 'login', 'password_hash' ) );

        $auth = new ezcAuthentication( $cred );
        $auth->addFilter( new ezcAuthenticationDatabaseFilter( $authDbInfo ) );
        return $auth;
    }

    public function authenticate( ezcAuthentication $auth, ezcMvcRequest $request )
    {
        if ( !$auth->run() )
        {
            $request->uri = "{$this->prefix}/auth/http-basic-auth";
            return new ezcMvcInternalRedirect( $request );
        }
        else
        {
            // We're in. Get the ezp user and return it
            return eZUser::fetchByName( $auth->credentials->id );
        }
    }
}
?>

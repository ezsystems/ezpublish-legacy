<?php
/**
 * File containing the basic auth style
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestBasicAuthStyle implements ezpRestAuthenticationStyle
{
    public function setup( ezcMvcRequest $request )
    {
        // Testing basic auth
        $logger = ezcLog::getInstance();
        $logger->source = __FUNCTION__;
        $logger->category = "auth";

        if ( $request->authentication === null )
        {
            $logger->log( "No credentials available", ezcLog::DEBUG );
            $request->uri = '/http-basic-auth';
            return new ezcMvcInternalRedirect( $request );
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
            // @TODO: Proper error messages required of course.
            $request->uri = '/http-basic-auth';
            return new ezcMvcInternalRedirect( $request );
        }
        else
        {
            // We're in
            $logger->log( "Authentication successful", ezcLog::DEBUG );
            // $logger->log( var_export( $request->raw, true), ezcLog::DEBUG );
        }
    }
}
?>
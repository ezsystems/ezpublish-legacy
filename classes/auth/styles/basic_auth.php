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
    protected $prefix;

    public function __construct()
    {
        $this->prefix = eZINI::instance( 'rest.ini' )->variable( 'System', 'ApiPrefix' );
    }
    public function setup( ezcMvcRequest $request )
    {
        if ( $request->authentication === null )
        {
            $request->uri = "{$this->prefix}/http-basic-auth";
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
            $request->uri = "{$this->prefix}/http-basic-auth";
            return new ezcMvcInternalRedirect( $request );
        }
        else
        {
            // We're in
        }
    }
}
?>

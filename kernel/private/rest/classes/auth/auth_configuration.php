<?php
/**
 * File containing ezpRestAuthConfiguration
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Class controlling authentication inside REST layer.
 *
 * This class sets up the defaults for which routes require auth and which
 * can be omitted. This class acts as a compatibility bridge between the REST-
 * layer and the traditional eZ Publish permission configuration.
 */
class ezpRestAuthConfiguration
{
    protected $info = null;
    protected $req = null;

    protected $filter = null;

    public function __construct( ezcMvcRoutingInformation $info, ezpRestRequest $req )
    {
        $this->info = $info;
        $this->req = $req;
    }

    public function setFilter( ezpRestAuthenticationStyle $filter )
    {
        $this->filter = $filter;
    }

    public function filter()
    {
        if ( eZINI::instance( 'rest.ini' )->variable( 'Authentication', 'RequireHTTPS') === 'enabled' &&
             $this->req->isEncrypted === false )
        {
            // When an unencrypted connection is identified, we have to alter the
            // flag to avoid infinite loop, when the request is rerouted to the error controller.
            // This should be improved in the future.
            $this->req->isEncrypted = true;
            throw new ezpRestHTTPSRequiredException();
        }

        // 0. Check if the given route needs authentication.
        if ( !$this->shallAuthenticate() )
        {
            $this->filter = new ezpRestNoAuthStyle();
        }
        else if ( $this->filter === null )
        {
            $opt = new ezpExtensionOptions();
            $opt->iniFile = 'rest.ini';
            $opt->iniSection = 'Authentication';
            $opt->iniVariable = 'AuthenticationStyle';
            $authFilter = eZExtension::getHandlerClass( $opt );
            if ( !$authFilter instanceof ezpRestAuthenticationStyle )
            {
                throw new ezpRestAuthStyleNotFoundException();
            }

            $this->filter = $authFilter;
        }

        // 1. Initialize the context needed for authenticating the user.
        $auth = $this->filter->setup( $this->req );
        if ( $auth instanceof ezcMvcInternalRedirect )
            return $auth;

        // 2.Perform the authentication
        // Result of authentication filter can be a valid ezp user (auth succeeded) or an internal redirect (ezcMvcInternalRedirect)
        $user = $this->filter->authenticate( $auth, $this->req );
        if ( $user instanceof eZUser )
        {
            eZUser::setCurrentlyLoggedInUser( $user, $user->attribute( 'contentobject_id' ) );
            $this->filter->setUser( $user );
        }
        else if ( $user instanceof ezcMvcInternalRedirect )
        {
            return $user;
        }
    }

    /**
     * Checks if authentication should be requested or not
     * @return bool
     */
    private function shallAuthenticate()
    {
        $shallAuthenticate = true;
        if ( eZINI::instance( 'rest.ini' )->variable( 'Authentication', 'RequireAuthentication' ) !== 'enabled' )
        {
            $shallAuthenticate = false;
        }
        else
        {
            $routeFilter = ezpRestRouteFilterInterface::getRouteFilter();
            $shallAuthenticate = $routeFilter->shallDoActionWithRoute( $this->info );
        }

        return $shallAuthenticate;
    }
}

?>

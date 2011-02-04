<?php
/**
 * File containing ezpRestAuthConfiguration
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
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

    public function __construct( ezcMvcRoutingInformation $info, ezcMvcRequest $req )
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
        if ( eZINI::instance( 'rest.ini' )->variable( 'Authentication', 'RequireAuthentication' ) !== 'enabled' )
            return;

        // 0. Check if the given route needs authentication.
        if ( !$this->shallAuthenticate() )
            return;

        if ( $this->filter === null )
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

        // 2.Perform the authentication
        return $this->filter->authenticate( $auth, $this->req );

    }

    public function shallAuthenticate()
    {
        $routeFilter = ezpRestRouteFilterInterface::getRouteFilter();
        return $routeFilter->shallDoActionWithRoute( $this->info );
    }
}

?>

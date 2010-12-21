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
        // 0. Check if the given route needs authentication.
        if ( !$this->shallAuthenticate() )
            return;

        if ( $this->filter === null )
        {
            // By standard we setup the oauth filter
            // @TODO Make the default a choice by configuration
            $this->filter = new ezpRestOauthAuthenticationStyle();
        }

        // 1. Initialize the context needed for authenticating the user.
        $auth = $this->filter->setup( $this->req );

        // 2.Perform the authentication check this will redirect properly if
        // unsuccessful
        return $this->filter->authenticate( $auth, $this->req );

    }

    public function shallAuthenticate()
    {
        // @TODO route filter should be extensible.
        // @TODO the route list should be configurable via settings
        switch ( $this->info->matchedRoute )
        {
            case '/http-basic-auth':
            case '/login/oauth':
            case '/login/oauth/authorize':
            case '/api/oauth/token':
            case '/api/fatal':
                return false;
                break;
            default:
                return true;
                break;
        }
    }
}

?>
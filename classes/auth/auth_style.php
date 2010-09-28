<?php
/**
 * File containing the ezpRestAuthenticationStyle interface.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

interface ezpRestAuthenticationStyle
{
    /**
     * Setting up the state to allow for later authentcation checks.
     *
     * @param ezcMvcRequest $request
     * @return void
     */
    public function setup( ezcMvcRequest $request );

    /**
     * Method to be run inside the runRequestFilters hook inside MvcTools.
     * 
     * This method should take care of seting up proper redirections to MvcTools
     *
     * @return void
     */
    public function authenticate( ezcAuthentication $auth, ezcMvcRequest $request );
}
?>
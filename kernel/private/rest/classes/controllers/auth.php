<?php
/**
 * File containing ezpRestAuthController
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
* Handles authentication with the REST interface.
*/
class ezpRestAuthController extends ezcMvcController
{
    public function doBasicAuth()
    {
        $res = new ezcMvcResult();
        $res->status = new ezcMvcResultUnauthorized( "eZ Publish REST" );
        return $res;
    }

    public function doOauthRequired()
    {
        $res = new ezcMvcResult();
        $res->status = new ezpOauthRequired( "eZ Publish REST" );
        return $res;
    }
}

?>
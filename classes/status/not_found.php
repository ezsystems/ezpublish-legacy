<?php
/**
 * File containing the ezpRestNotFound status object.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestNotFound implements ezcMvcResultStatusObject
{
    // @TODO Make this dynamic to return all types of HTTP response codes
    // e.g. For instance constructor can accept code as param.

    // Messages should also in some cases be added to the HTTP body, with
    // info from app, extending the normal short http default boiler plate message.

    public function process( ezcMvcResponseWriter $writer )
    {
        if ( $writer instanceof ezcMvcHttpResponseWriter )
        {
            $writer->headers["HTTP/1.1 " . ezpHttpResponseCodes::NOT_FOUND] = "Not Found";
        }
    }
}
?>
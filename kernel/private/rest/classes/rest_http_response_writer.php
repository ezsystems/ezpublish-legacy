<?php
/**
 * File containing the ezpRestHttpResponseWriter class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestHttpResponseWriter extends ezcMvcHttpResponseWriter
{
    /**
     * The response struct object.
     *
     * In the ezp rest version this variable is public, so that error messages
     * can be injected into the response body.
     *
     * @var ezcMvcResponse
     */
    public $response;

}

<?php
/**
 * File containing the ezpRestHttpResponse status object.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestHttpResponse implements ezcMvcResultStatusObject
{
    public $code;
    public $message;

    public function __construct( $code = null, $message = null )
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function process( ezcMvcResponseWriter $writer )
    {
        if ( $writer instanceof ezcMvcHttpResponseWriter )
        {
            $writer->headers["HTTP/1.1 " . $this->code] = $this->message;
        }
    }
}
?>

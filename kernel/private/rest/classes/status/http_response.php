<?php
/**
 * File containing the ezpRestHttpResponse status object.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
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

        if ( $this->message !== null )
        {
            $writer->headers['Content-Type'] = 'application/json; charset=UTF-8';
            $writer->response->body = json_encode( array( 'error_message' => $this->message ) );
        }
    }
}
?>

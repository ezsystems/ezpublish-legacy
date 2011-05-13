<?php
/**
 * File containing the ezpRestHttpResponse status object.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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

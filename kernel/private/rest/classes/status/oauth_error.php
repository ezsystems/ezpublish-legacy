<?php
/**
 * File containing the ezpRestOauthErrorStatus class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpRestOauthErrorStatus implements ezcMvcResultStatusObject
{
    public $errorType;
    public $message;

    public function __construct( $errorType = null, $message = null )
    {
        $this->errorType = $errorType;
        $this->message = $message;
    }

    public function process( ezcMvcResponseWriter $writer )
    {
        if ( $writer instanceof ezcMvcHttpResponseWriter )
        {
            $writer->headers["HTTP/1.1 " . ezpOauthErrorType::httpCodeForError( $this->errorType )] = "";
        }

        if ( $this->message !== null )
        {
            $writer->response->body = $this->message;
        }
    }
}
?>

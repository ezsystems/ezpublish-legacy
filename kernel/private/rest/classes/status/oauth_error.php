<?php
/**
 * File containing the ezpRestOauthErrorStatus class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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

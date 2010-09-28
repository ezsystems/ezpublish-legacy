<?php
class ezpRestOauthErrorStatus implements ezcMvcResultStatusObject
{
    public $errorType = null;

    public function __construct( $errorType = null )
    {
        $this->errorType = $errorType;
    }

    public function process( ezcMvcResponseWriter $writer )
    {
        if ( $writer instanceof ezcMvcHttpResponseWriter )
        {
            // @TODO message lookup
            $writer->headers["HTTP/1.1 " . ezpOauthErrorType::httpCodeForError( $this->errorType )] = "";
        }
    }
}
?>
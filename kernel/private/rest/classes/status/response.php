<?php
/**
 * File containing the ezpRestStatusResponse class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpRestStatusResponse implements ezcMvcResultStatusObject
{
    /**
     * HTTP code
     *
     * @var int
     */
    public $code;

    /**
     * Message to be provided
     *
     * @var string
     */
    public $message;

    /**
     * HTTP headers to set
     *
     * @var array
     */
    public $headers;

    /**
     * HTTP Status code map
     *
     * @var array
     */
    public static $statusCodes = array(
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        307 => "Temporary Redirect",
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Request Entity Too Large",
        415 => "Unsupported Media Type",
        500 => "Internal Server Error",
        501 => "Not Implemented",
        503 => "Service Unavailable",
    );

    /**
     * Construct an ezpRestStatusResponse object
     *
     * @param int $code HTTP code
     * @param string $message Message to be provided
     * @param array $headers Headers
     */
    public function __construct( $code = null, $message = null, array $headers = array() )
    {
        $this->code = $code;
        $this->message = $message;
        $this->headers = $headers;
    }

    /**
     * This method is called by the response writers to process the data
     * contained in the status objects.
     *
     * The process method it responsible for undertaking the proper action
     * depending on which response writer is used.
     *
     * @param ezcMvcResponseWriter $writer
     */
    public function process( ezcMvcResponseWriter $writer )
    {
        if ( $writer instanceof ezcMvcHttpResponseWriter )
        {
            $writer->headers["HTTP/1.1 " . $this->code] = self::$statusCodes[$this->code];
            $writer->headers = $this->headers + $writer->headers;
        }

        if ( $this->message !== null )
        {
            $writer->headers['Content-Type'] = 'application/json; charset=UTF-8';
            $writer->response->body = json_encode( $this->message );
        }
    }
}

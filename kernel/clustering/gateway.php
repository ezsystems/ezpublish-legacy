<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Basis for a cluster gateway.
 *
 * Inherited by cluster index gateways
 */
abstract class ezpClusterGateway
{
    /**
     * The active gateway class
     * @var string
     */
    private static $gatewayClass;

    /**
     * Database instance, optional
     *
     * @var mixed
     */
    protected $db;

    /**
     * Database hostname
     *
     * @var string
     */
    protected $host;

    /**
     * Database port
     *
     * @var int
     */
    protected $port;

    /**
     * Database user
     *
     * @var string
     */
    protected $user;

    /**
     * Database password
     *
     * @var string
     */
    protected $password;

    /**
     * Database name
     *
     * @var string
     */
    protected $name;

    /**
     * Database charset
     *
     * @var string
     */
    protected $charset;

    /**
     * Instantiate a gateway with the database parameters.
     *
     * @param array $params Database parameters.
     */
    public function __construct( array $params = array() )
    {
        if ( isset( $params["host"] ) )
            $this->host = $params["host"];

        if ( isset( $params["port"] ) )
            $this->port = $params["port"];

        if ( isset( $params["user"] ) )
            $this->user = $params["user"];

        if ( isset( $params["password"] ) )
            $this->password = $params["password"];

        if ( isset( $params["name"] ) )
            $this->name = $params["name"];

        if ( isset( $params["charset"] ) )
            $this->charset = $params["charset"];
    }

    /**
     * Creates the necessary database connection
     *
     * The database connexion must be usable as is after return, meaning
     * that database connection, charset choice must be set
     *
     * @throws RuntimeException if connection failed
     */
    abstract public function connect();

    /**
     * Fetches file metadata for $filepath
     *
     * @param string $filepath
     *
     * @return array|false
     */
    abstract public function fetchFileMetadata( $filepath );

    /**
     * Passes the $filepath data through
     * @param mixed $db
     * @param string $filepath
     * @param int $filesize
     * @param int $offset
     * @param int $length
     * @return void
     */
    abstract public function passthrough( $filepath, $filesize, $offset = false, $length = false );

    /**
     * Closes any connection that should be closed
     */
    abstract public function close();

    /**
     * @TODO: evaluate whether it is the right place for those methods or if it
     *        should belong to other dedicated classes.
     * @TODO: get rid of constants
     */
    public function retrieve( $filename )
    {
        // connection
        $tries = 0;
        $maxTries = 3;
        while ( true )
        {
            try
            {
                $this->connect();
                break;
            }
            catch ( RuntimeException $e )
            {
                if ( ++$tries === $maxTries )
                {
                    $this->interrupt( $e->getMessage() );
                }
            }
        }

        // Fetch file metadata.
        try
        {
            $metaData = $this->fetchFileMetadata( $filename );
        }
        catch ( RuntimeException $e )
        {
            $this->interrupt( $e->getMessage() );
        }
        if ( $metaData === false || $metaData['mtime'] < 0 )
        {
            $this->interrupt( '', 404, $filename );
        }

        // Output HTTP headers.
        $filesize = $metaData['size'];
        $mtime = $metaData['mtime'];
        $datatype = $metaData['datatype'];

        header( "Content-Type: {$datatype}" );
        header( "Connection: close" );
        header( 'Served-by: ' . $_SERVER["SERVER_NAME"] );

        if ( CLUSTER_HEADER_X_POWERED_BY !== false )
            header( "X-Powered-By: " . CLUSTER_HEADER_X_POWERED_BY );

        // Request headers: eTag + IF-MODIFIED-SINCE
        if ( CLUSTER_ENABLE_HTTP_CACHE )
        {
            header( "Last-Modified: " . gmdate( 'D, d M Y H:i:s', $mtime ) . ' GMT' );

            if ( CLUSTER_EXPIRY_TIMEOUT !== false )
                header( "Expires: " . gmdate( 'D, d M Y H:i:s', time() + CLUSTER_EXPIRY_TIMEOUT ) . ' GMT' );

            header( "ETag: $mtime-$filesize" );
            $serverVariables = array_change_key_case( $_SERVER, CASE_UPPER );
            if ( isset( $serverVariables['HTTP_IF_NONE_MATCH'] ) && trim( $serverVariables['HTTP_IF_NONE_MATCH'] ) != "$mtime-$filesize" )
            {
                $this->notModified();
            }

            if ( isset( $serverVariables['HTTP_IF_MODIFIED_SINCE'] ) )
            {
                $value = $serverVariables['HTTP_IF_MODIFIED_SINCE'];

                // strip the garbage prepended by a semi-colon used by some browsers
                if ( ( $pos = strpos( $value , ';' ) ) !== false )
                    $value = substr( $value, 0, $pos );
                if ( strtotime( $value ) <= $mtime )
                {
                    $this->notModified();
                }
            }
        }

        // Request headers:  HTTP Range
        $contentLength = $filesize;
        $startOffset = false;
        if ( CLUSTER_ENABLE_HTTP_RANGE )
        {
            // let the client know we do accept range by bytes
            header( 'Accept-Ranges: bytes' );

            if ( isset( $_SERVER['HTTP_RANGE'] ) && strpos( $_SERVER['HTTP_RANGE'], 'bytes=' ) === 0 && strpos( $_SERVER['HTTP_RANGE'], ',' ) === false )
            {
                $matches = explode( '-', substr( $_SERVER['HTTP_RANGE'], 6 ) );
                $startOffset = $matches[0];
                $endOffset = !empty( $matches[1] ) ? $matches[1] : false;
                if ( $endOffset !== false && empty( $startOffset ) && $startOffset !== "0" )
                {
                    $startOffset = $filesize - $endOffset;
                    $endOffset = $filesize - 1;
                }
                elseif ( $endOffset === false || $endOffset > ( $filesize - 1 ) )
                {
                    $endOffset = $filesize - 1;
                }
                $contentLength = $endOffset - $startOffset + 1;
                if ( $startOffset >= $endOffset )
                {
                    header( "Content-Range: bytes */$filesize" );
                    $this->interrupt( '', 416 );
                }
                else
                {
                    header( "Content-Range: bytes $startOffset-$endOffset/$filesize" );
                    header( "HTTP/1.1 206 Partial Content" );
                }
            }
        }
        else
        {
            header( 'Accept-Ranges: none' );
        }

        header( "Content-Length: $contentLength" );

        // Output file data
        try
        {
            $this->passthrough( $filename, $filesize, $startOffset, $contentLength );
        }
        catch ( Exception $e )
        {
            $this->interrupt( $e->getMessage() );
        }

        $this->close();
    }

    /**
     * Error termination
     *
     * @param string $data
     * @param int $errorCode HTTP error code
     * @param string $filename
     *
     * @return
     */
    private function interrupt( $message, $errorCode = false, $filename = false )
    {
        header( 'Content-Type: text/html' );
        switch ( $errorCode )
        {
            case 404:
                $filename = htmlspecialchars( $filename );
                header( $_SERVER['SERVER_PROTOCOL'] . " 404 Not Found" );
                echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<H1>Not Found</H1>
<p>The requested URL {$filename} was not found on this server.<p>
</body></html>
EOF;
            exit( 1 );

            case 416:
                header( $_SERVER['SERVER_PROTOCOL'] . " 416 Requested Range Not Satisfiable" );
                echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>416 Requested Range Not Satisfiable</title>
</head><body>
<h1>416 Requested Range Not Satisfiable</h1>
</body></html>
EOF;
            exit( 1 );

            case 500:
            default:
                if ( !CLUSTER_ENABLE_DEBUG )
                    $message = "An error has occured";
                header( $_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error" );
                echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>500 Internal Server Error</title>
</head><body>
<h1>$message</h1>
</body></html>
EOF;
                trigger_error( $message, E_USER_ERROR );
        }
    }

    private function notModified()
    {
        header( "HTTP/1.1 304 Not Modified" );
        if ( !defined( 'CLUSTER_PERSISTENT_CONNECTION' ) || CLUSTER_PERSISTENT_CONNECTION == false )
        {
            $this->close();
        }
        exit;
    }

    /**
     * Sets the gateway class to $gatewayClass
     *
     * @param string $gatewayClass
     */
    public static function setGatewayClass( $gatewayClass )
    {
        self::$gatewayClass = $gatewayClass;
    }

    /**
     * Returns an instance of the gateway class depending on {@link setGatewayClass()}
     *
     * @return ezpClusterGateway
     */
    public static function getGateway()
    {
        $gatewayClass = self::$gatewayClass;

        return new $gatewayClass(
            array(
                // some databases don't need a hostname (oracle for instance)
                "host" => defined( "CLUSTER_STORAGE_HOST" ) ? CLUSTER_STORAGE_HOST : null,
                "port" => defined( "CLUSTER_STORAGE_PORT" ) ? CLUSTER_STORAGE_PORT : null,
                "user" => CLUSTER_STORAGE_USER,
                "password" => CLUSTER_STORAGE_PASS,
                "name" => CLUSTER_STORAGE_DB,
                "charset" => CLUSTER_STORAGE_CHARSET,
            )
        );
    }
}

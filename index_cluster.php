<?php
include 'config.php';

if ( defined( 'CLUSTER_DEBUG' ) || CLUSTER_DEBUG === true )
    ini_set( 'display_errors', 1 );
else
    ini_set( 'display_errors', 0 );

if ( !defined( 'CLUSTER_STORAGE_BACKEND' ) || CLUSTER_STORAGE_BACKEND === null )
{
    _die( "Clustering is disabled" );
}

// default values
if ( !defined( 'CLUSTER_ENABLE_HTTP_RANGE' ) )     define( 'CLUSTER_ENABLE_HTTP_RANGE', true );
if ( !defined( 'CLUSTER_ENABLE_HTTP_CACHE' ) )     define( 'CLUSTER_ENABLE_HTTP_CACHE', true );
if ( !defined( 'CLUSTER_ENABLE_DEBUG' ) )          define( 'CLUSTER_ENABLE_DEBUG', false );
if ( !defined( 'CLUSTER_PERSISTENT_CONNECTION' ) ) define( 'CLUSTER_PERSISTENT_CONNECTION', false );
if ( !defined( 'CLUSTER_STORAGE_USER' ) )          define( 'CLUSTER_STORAGE_USER', '' );
if ( !defined( 'CLUSTER_STORAGE_PASS' ) )          define( 'CLUSTER_STORAGE_PASS', '' );
if ( !defined( 'CLUSTER_STORAGE_DB' ) )            define( 'CLUSTER_STORAGE_DB', '' );

include "kernel/clustering/gateway.php";
$clusterFunctionsFile = "kernel/clustering/" . CLUSTER_STORAGE_BACKEND . ".php";
if ( !file_exists( $clusterFunctionsFile ) )
{
    _die( "Unable to open storage backend functions file '$clusterFunctionsFile'" );
}
$gatewayClass = require $clusterFunctionsFile;
$gateway = new $gatewayClass;

if ( !defined( 'STORAGE_PORT' ) )
{
    define( 'STORAGE_PORT', $gateway->getDefaultPort() );
}

if ( !defined( 'STORAGE_SOCKET' ) )
{
    define( 'STORAGE_SOCKET', '' );
}

// connection
$errorMessage = "No error specified";
$tries = 0; $maxTries = 3;
/*while ( $tries < $maxTries )
{
    echo "Try: " . $tries . "<br />";*/
    try {
        $gateway->connect(
            CLUSTER_STORAGE_HOST, CLUSTER_STORAGE_PORT, CLUSTER_STORAGE_USER,
            CLUSTER_STORAGE_PASS, CLUSTER_STORAGE_DB, CLUSTER_STORAGE_CHARSET );
    } catch( RuntimeException $e ) {
        echo "RuntimeException:\n";
        var_dump( $e );
        $tries++;
        trigger_error( $errorMessage = $e->getMessage(), E_USER_WARNING );
    }
// }

if ( $tries >= $maxTries )
{
    _die( "Unable to connect to storage server: $errorMessage" );
}

$filename = ltrim( $_SERVER['REQUEST_URI'], '/' );
if ( ( $queryPos = strpos( $filename, '?' ) ) !== false )
    $filename = substr( $filename, 0, $queryPos );

// Fetch file metadata.
try {
    $metaData = $gateway->fetchFileMetadata( $filename );
} catch( RuntimeException $e ) {
    _die( $e->getMessage() );
}
if ( $metaData === false || $metaData['mtime'] < 0 )
{
    _die( '', 404, $filename );
}

// Output HTTP headers.
$path     = $metaData['name'];
$size     = $metaData['size'];
$mimeType = $metaData['datatype'];
$mtime    = $metaData['mtime'];
$mdate    = gmdate( 'D, d M Y H:i:s', $mtime ) . ' GMT';

header( "Content-Type: $mimeType" );
header( "Last-Modified: $mdate" );
header( "Connection: close" );
header( "Accept-Ranges: none" );
header( 'Served-by: ' . $_SERVER["SERVER_NAME"] );

if ( CLUSTER_EXPIRY_TIMEOUT !== false )
    header( "Expires: " . gmdate( 'D, d M Y H:i:s', time() + CLUSTER_EXPIRY_TIMEOUT ) . ' GMT' );

if ( CLUSTER_HEADER_X_POWERED_BY !== false)
    header( "X-Powered-By: " . CLUSTER_HEADER_X_POWERED_BY );

// Request headers: eTag  + IF-MODIFIED-SINCE
if ( CLUSTER_ENABLE_HTTP_CACHE )
{
    header( "ETag: $mtime-$size" );
    foreach ( $_SERVER as $header => $value )
    {
        $header = strtoupper( $header );
        switch( $header )
        {
            case 'HTTP_IF_NONE_MATCH':
                if ( trim( $value ) != "$mtime-$size" )
                {
                    trigger_error( "etag", E_USER_ERROR );
                    _304();
                }

            case 'HTTP_IF_MODIFIED_SINCE':
                // strip the garbage prepended by a semi-colon used by some browsers
                if ( ( $pos = strpos( $value , ';' ) ) !== false )
                    $value = substr( $value, 0, $pos );
                if ( strtotime( $value ) < $mtime )
                    _304();
        }
    }
}

// Request headers:  HTTP Range
$contentLength = $size;
if ( CLUSTER_ENABLE_HTTP_RANGE && isset( $_SERVER['HTTP_RANGE'] ) )
{
    $httpRange = trim( $_SERVER['HTTP_RANGE'] );
    if ( preg_match( "/^bytes=(\d+)-(\d+)?$/", $httpRange, $matches ) )
    {
        $contentOffset = $matches[1];
        if ( isset( $matches[2] ) )
        {
            $contentLength = $matches[2] - $matches[1] + 1;
            $lastPos  = $matches[2];
        }
        else
        {
            $contentLength = $fileSize - $matches[1];
            $lastPos = $fileSize -1;
        }
        header( "Content-Range: bytes $matches[1]-$lastPos/$fileSize" );
        header( "HTTP/1.1 206 Partial Content" );
    }
}
header( "Content-Length: $contentLength" );

// Output file data
try {
    $gateway->passthrough( $filename, $contentOffset, $contentLength );
} catch( RuntimeException $e ) {
    _die( $e->getMessage );
}

/**
 * Error termination
 *
 * @param string $data
 * @param int $errorCode HTTP error code
 * @param string $filename
 * @return
 */
function _die( $message, $errorCode = false, $filename = false)
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
            break;

        case 500:
        default:
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
            break;


    }
}

function _304()
{
    header( "HTTP/1.1 304 Not Modified" );
    if ( !defined( 'CLUSTER_PERSISTENT_CONNECTION' ) || CLUSTER_PERSISTENT_CONNECTION == false )
    {
        $gateway->close();
    }
    exit;
}
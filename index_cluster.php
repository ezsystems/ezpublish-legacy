<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Cluster index file.
 *
 * Used to serve eZ Publish binary files through HTTP when using one of the eZ Publish clustering implementations.
 * Configuration is made in config.php, using the CLUSTER_* constants.
 */
if ( file_exists( 'config.php' ) )
    include 'config.php';

if ( file_exists( 'config.cluster.php' ) )
    include( 'config.cluster.php' );

if ( !defined( 'CLUSTER_STORAGE_BACKEND' ) || CLUSTER_STORAGE_BACKEND === null )
{
    if ( CLUSTER_ENABLE_DEBUG )
    {
        $message = "Clustering is disabled";
    }
    else
    {
        $message = "An error has occured";
    }
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

// default values
if ( !defined( 'CLUSTER_ENABLE_HTTP_RANGE' ) )     define( 'CLUSTER_ENABLE_HTTP_RANGE', true );
if ( !defined( 'CLUSTER_ENABLE_HTTP_CACHE' ) )     define( 'CLUSTER_ENABLE_HTTP_CACHE', true );
if ( !defined( 'CLUSTER_HEADER_X_POWERED_BY' ) )   define( 'CLUSTER_HEADER_X_POWERED_BY', 'eZ Publish' );
if ( !defined( 'CLUSTER_ENABLE_DEBUG' ) )          define( 'CLUSTER_ENABLE_DEBUG', false );
if ( !defined( 'CLUSTER_PERSISTENT_CONNECTION' ) ) define( 'CLUSTER_PERSISTENT_CONNECTION', false );
if ( !defined( 'CLUSTER_STORAGE_USER' ) )          define( 'CLUSTER_STORAGE_USER', '' );
if ( !defined( 'CLUSTER_STORAGE_PASS' ) )          define( 'CLUSTER_STORAGE_PASS', '' );
if ( !defined( 'CLUSTER_STORAGE_DB' ) )            define( 'CLUSTER_STORAGE_DB', '' );
if ( !defined( 'CLUSTER_EXPIRY_TIMEOUT' ) || CLUSTER_EXPIRY_TIMEOUT === true )
    define( 'CLUSTER_EXPIRY_TIMEOUT', 86400 );

ini_set( 'display_errors', CLUSTER_ENABLE_DEBUG );

require_once "kernel/clustering/gateway.php";

if ( defined( 'CLUSTER_STORAGE_GATEWAY_PATH' ) && CLUSTER_STORAGE_GATEWAY_PATH )
    $clusterGatewayFile = CLUSTER_STORAGE_GATEWAY_PATH;
else
    $clusterGatewayFile = "kernel/clustering/" . CLUSTER_STORAGE_BACKEND . ".php";

if ( !file_exists( $clusterGatewayFile ) )
{
    if ( CLUSTER_ENABLE_DEBUG )
    {
        $message = "Unable to open storage backend gateway class definition file '$clusterGatewayFile'";
    }
    else
    {
        $message = "An error has occured";
    }
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

// We use require_once as the gateway file may have been included before for initialization purpose
require_once $clusterGatewayFile;
$gateway = ezpClusterGateway::getGateway();

// Use rawurldecode() because if the file contains " characters, they are url encoded.
$filename = rawurldecode( ltrim( $_SERVER['REQUEST_URI'], '/' ) );
if ( ( $queryPos = strpos( $filename, '?' ) ) !== false )
    $filename = substr( $filename, 0, $queryPos );

$gateway->retrieve( $filename );

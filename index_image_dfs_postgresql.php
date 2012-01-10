<?php
define( 'TABLE_METADATA', 'ezdfsfile' );

if ( !defined( 'STORAGE_PORT' ) )
{
    define( 'STORAGE_PORT', 5433 );
}

if ( !defined( 'STORAGE_SOCKET' ) )
{
    define( 'STORAGE_SOCKET', false );
}

if ( !defined ( 'EXPIRY_TIMEOUT' ) )
{
    define( 'EXPIRY_TIMEOUT', 6000 );
}

if ( !defined( 'HEADER_X_POWERED_BY' ) )
{
    define( 'HEADER_X_POWERED_BY', 'eZ Publish' );
}

$maxTries = 3;

$db = null;
$connectString = sprintf( 'pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s',
    STORAGE_HOST,
    STORAGE_PORT,
    STORAGE_DB,
    STORAGE_USER,
    STORAGE_PASS
);
$connectString  = sprintf( 'pgsql:host=%s;dbname=%s;port=%s', STORAGE_HOST, STORAGE_DB, STORAGE_PORT );
while ( $tries < $maxTries )
{
    try {
        $db = new PDO( $connectString, STORAGE_USER, STORAGE_PASS );
    } catch( PDOException $e ) {

    }
    ++$tries;
}

if ( $tries > $maxTries || !$db)
{
    _die( "Unable to connect to storage server" );
}

if ( $db->exec( "SET NAMES 'utf8'" ) === false )
    _die( "Failed to set character set.\n" );

$filename = ltrim( $_SERVER['REQUEST_URI'], '/' );
if ( ( $queryPos = strpos( $filename, '?' ) ) !== false )
    $filename = substr( $filename, 0, $queryPos );

// Fetch file metadata.
$filePathHash = md5( $filename );
$sql = "SELECT * FROM " . TABLE_METADATA . " WHERE name_hash='$filePathHash'" ;
if ( !$stmt = $db->query( $sql ) )
    _die( "Failed to retrieve file metadata\n" );

if ( !( $metaData = $stmt->fetch( PDO::FETCH_ASSOC ) ) || $metaData['mtime'] < 0 )
{
    _die( '', 404, $filename );
}


unset( $stmt );

// Fetch file data.
$dfsFilePath = MOUNT_POINT_PATH . '/' . $filename;

// Set cache time out to 100 minutes by default
if ( file_exists( $dfsFilePath ) )
{
    // Output HTTP headers.
    $path     = $metaData['name'];
    $size     = $metaData['size'];
    $mimeType = $metaData['datatype'];
    $mtime    = $metaData['mtime'];
    $mdate    = gmdate( 'D, d M Y H:i:s', $mtime ) . ' GMT';

    header( "Content-Length: $size" );
    header( "Content-Type: $mimeType" );
    header( "Last-Modified: $mdate" );
    if ( EXPIRY_TIMEOUT !== false )
        header( "Expires: " . gmdate( 'D, d M Y H:i:s', time() + EXPIRY_TIMEOUT ) . ' GMT' );
    header( "Connection: close" );
    header( "X-Powered-By: " . HEADER_X_POWERED_BY );
    header( "Accept-Ranges: none" );
    header( 'Served-by: ' . $_SERVER["SERVER_NAME"] );

    // Output file data
    $fp = fopen( $dfsFilePath, 'r' );
    fpassthru( $fp );
    fclose( $fp );
}
else
{
    _die( "Server error: DFS File not found.", 404, $filename );
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
            echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>500 Internal Server Error</title>
</head><body>
<h1>$dieMessage</h1>
</body></html>
EOF;
            header( $_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error" );
            exit( $dieMessage );
            break;


    }
}

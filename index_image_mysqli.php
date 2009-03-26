<?php

define( 'TABLE_METADATA', 'ezdbfile' );
define( 'TABLE_DATA', 'ezdbfile_data' );

function _die( $value )
{
    header( $_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error" );
    die( $value );
}

// Connect to storage database.
$serverString = STORAGE_HOST;
if ( !defined( 'STORAGE_SOCKET' ) )
    define ( 'STORAGE_SOCKET', false );
if ( defined( 'STORAGE_PORT' ) )
    define ( 'STORAGE_PORT', false );

$maxTries = 3;
$tries = 0;
while ( $tries < $maxTries )
{
    if ( $db = mysqli_connect( $serverString, STORAGE_USER, STORAGE_PASS, null, STORAGE_PORT, STORAGE_SOCKET ) )
        break;
    ++$tries;
}
if ( !$db )
    _die( "Unable to connect to storage server.\n" );

if ( !mysqli_select_db( $db, STORAGE_DB ) )
    _die( "Unable to select database " . STORAGE_DB . ".\n" );

$filename = ltrim( $_SERVER['REQUEST_URI'], "/");

// Fetch file metadata.
$filePathHash = mysqli_real_escape_string( $filename );
$sql = "SELECT * FROM " . TABLE_METADATA . " WHERE name_hash=MD5('$filePathHash')" ;
if ( !$res = mysqli_query( $db, $sql ) )
    _die( "Failed to retrieve file metadata\n" );

if ( !( $metaData = mysqli_fetch_array( $res, MYSQLI_ASSOC ) ) ||
     $metaData['mtime'] < 0 )
{
    header( $_SERVER['SERVER_PROTOCOL'] . " 404 Not Found" );
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>404 Not Found</TITLE>
</HEAD><BODY>
<H1>Not Found</H1>
The requested URL <?php echo htmlspecialchars( $filename ); ?> was not found on this server.<P>
</BODY></HTML>
<?php
    //mysql_free_result( $res );
    mysqli_close( $db );
    exit( 1 );
}

mysql_free_result( $res );

// Verify the filesize
$sql = "SELECT SUM(LENGTH(filedata)) AS size FROM " . TABLE_DATA . " WHERE name_hash=MD5('$filePathHash')";
if ( !$res = mysqli_query( $db, $sql ) )
{
    header( $_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error" );
    exit();
}

$row = mysqli_fetch_row( $res );
if ( $row[0] != $metaData['size'] )
{
    header( $_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error" );
    exit();
}

// Fetch file data.
$sql = "SELECT filedata, offset FROM " . TABLE_DATA . " WHERE name_hash=MD5('$filePathHash') ORDER BY offset";
if ( $res = mysqli_query( $db, $sql ) )
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
    /* Set cache time out to 10 minutes, this should be good enough to work around an IE bug */
    header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 6000) . ' GMT' );
    header( "Connection: close" );
    header( "X-Powered-By: eZ Publish" );
    header( "Accept-Ranges: none" );
    header( 'Served-by: ' . $_SERVER["SERVER_NAME"] );

    // Output image data.
    while ( $row = mysqli_fetch_row( $res ) )
        echo $row[0];
    //mysql_free_result( $res );
}
mysqli_close( $db );

?>
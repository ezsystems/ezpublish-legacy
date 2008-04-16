<?php

define( 'TABLE_METADATA', 'ezdbfile' );
define( 'TABLE_DATA', 'ezdbfile_data' );

// Connect to storage database.
$serverString = STORAGE_HOST;
if ( defined( 'STORAGE_SOCKET' ) && STORAGE_SOCKET )
    $serverString .= ':' . STORAGE_SOCKET;
elseif ( defined( 'STORAGE_PORT' ) )
    $serverString .= ':' . STORAGE_PORT;
if ( !$db = mysql_connect( $serverString, STORAGE_USER, STORAGE_PASS ) )
    die( "Unable to connect to storage server.\n" );

if ( !mysql_select_db( STORAGE_DB, $db ) )
    die( "Unable to connect to storage database.\n" );

$filename = ltrim( $_SERVER['SCRIPT_URL'], "/");

// Fetch file metadata.
$filePathHash = md5( mysql_real_escape_string( $filename ) );
$sql = "SELECT * FROM " . TABLE_METADATA . " WHERE name_hash='$filePathHash'" ;
if ( !$res = mysql_query( $sql, $db ) )
    die( "Failed to retrive file metadata: $filePath.\n" );

if ( !( $metaData = mysql_fetch_array( $res, MYSQL_ASSOC ) ) )
{
    header( "HTTP/1.1 404 Not Found" );
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>404 Not Found</TITLE>
</HEAD><BODY>
<H1>Not Found</H1>
The requested URL <?php echo htmlspecialchars( $filename ); ?> was not found on this server.<P>
</BODY></HTML>
<?php
    mysql_free_result( $res );
    mysql_close( $db );
    exit( 1 );
}

mysql_free_result( $res );

// Fetch file data.
$fileID = $metaData['id'];
$sql = "SELECT filedata FROM " . TABLE_DATA . " WHERE masterid=$fileID";
if ( $res = mysql_query( $sql, $db ) )
{
    // Output HTTP headers.
    $path     = $metaData['name'];
    $size     = $metaData['size'];
    $mimeType = $metaData['datatype'];
    $mtime    = $metaData['mtime'];
    $mdate    = gmdate( 'D, d M Y H:i:s T', $mtime );

    header( "Content-Length: $size" );
    header( "Content-Type: $mimeType" );
    header( "Last-Modified: $mdate" );
    header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 6000) . ' GMT' );
    header( "Connection: close" );
    header( "X-Powered-By: eZ publish" );
    header( "Accept-Ranges: bytes" );
    header( 'Served-by: ' . $_SERVER["SERVER_NAME"] );

    // Output image data.
    while ( $row = mysql_fetch_row( $res ) )
        echo $row[0];
    mysql_free_result( $res );
}
mysql_close( $db );

?>

<?php

define( 'TABLE_METADATA', 'ezdbfile' );

// Connect to storage database.
$connStr  = "host=" . STORAGE_HOST . " ";
$connStr .= "port=" . STORAGE_PORT . " ";
$connStr .= "dbname=" . STORAGE_DB . " ";
$connStr .= "user=" . STORAGE_USER . " ";
$connStr .= "password=" . STORAGE_PASS;
if ( !( $db = @pg_connect( $connStr ) ) )
    die( "Unable to connect to storage server." );

$filename = ltrim( $_SERVER['SCRIPT_URL'], "/");

// Fetch file metadata.

//------
$filePathHash = md5( $filename );
if( !( $res = pg_query( $db, "SELECT * FROM " . TABLE_METADATA . " WHERE name_hash='$filePathHash'" ) ) )
    die( "Unable to fetch image metadata.\n" );
if ( !( $metaData = pg_fetch_array( $res, null, PGSQL_ASSOC ) ) )
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
    pg_free_result( $res );
    pg_close( $db );
    exit( 1 );
}
pg_free_result( $res );

// Fetch file data.
pg_query( $db, "BEGIN" );
$lobHandle = pg_lo_open( $db, $metaData['lob_id'], 'r' );
if ( $lobHandle )
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

    while ( $chunk = pg_lo_read( $lobHandle, STORAGE_CHUNK_SIZE ) )
        echo $chunk;
    pg_lo_close( $lobHandle );

}

pg_query( $db, "COMMIT" );
pg_close( $db );
?>

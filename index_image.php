<?php
$filename = ltrim( $_SERVER['SCRIPT_URL'], "/");

require_once( 'kernel/classes/ezclusterfilehandler.php' );
$file = eZClusterFileHandler::instance( $filename );

if ( $file->exists() )
    $file->passthrough();
else
{
    header( "HTTP/1.1 404 Not Found" );
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>404 Not Found</TITLE>
</HEAD><BODY>
<H1>Not Found</H1>
The requested URL <?=$filename?> was not found on this server.<P>
</BODY></HTML>
<?php
}

/*
$fp = fopen( '/tmp/index_image.log', 'a+' );
fwrite( $fp, "$filename\n" );
fclose( $fp );
*/
?>

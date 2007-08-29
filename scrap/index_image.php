<?php


class eZDBImageServer
{
    function handeImageDownload( $filename )
    {
        include_once( "scrap/ezdbfile.php" );
        $dbFile = new eZDBFile();

        $contentLength = $dbFile->getFileSize( $filename );
        // FIXME : have to check if filename realy is a image/image variation
        // OR do some other permission test
        if ( $dbFile->file_exists( $filename ) )
        {
            $mimeType = $dbFile->getFileType( $filename );
            $originalFileName = "vidar.jpg";
            header( "Pragma: " );
            header( "Cache-Control: " );
            /* Set cache time out to 10 minutes, this should be good enough to work around an IE bug */
            header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 600) . 'GMT');
            header( "Content-Length: $contentLength" );
            header( "Content-Type: $mimeType" );
            header( "X-Powered-By: eZ publish" );
            header( "Content-disposition: attachment; filename=\"$originalFileName\"" );
            header( "Content-Transfer-Encoding: binary" );
            header( "Accept-Ranges: bytes" );

/*   Header ( "Content-Type: $FileObj->datatype" );
   Header ( "Content-Length: " . $FileObj->size );
   Header ( "Content-Disposition: attachment; filename=\"$originalFileName\"" );  */




//            echo ( $dbFile->fetchFile( $filename ) );
            $dbFile->passThru( $filename );




        } else
        {
            // FIXME
            echo ("Image does not exist in database<br>");
        echo ($_SERVER['SCRIPT_FILENAME'] . "<br>");
        echo ( $filename . "<br>");
        }



    }
}

$filename = $_SERVER['SCRIPT_URL'];
// remove heading "/" from filename
$filename = ltrim( $filename, "/");
eZDBImageServer::handeImageDownload( $filename );


?>

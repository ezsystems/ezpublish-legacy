<?php

$Result = array();
$Result["title"] = "File Reading";
$Result["content"] = "";

include_once( "lib/ezi18n/classes/eztextcodec.php" );

header( "Content-Type: text/html; charset=utf8" );

$files = array( array( "file" => "file1.txt",
                       "charset" => "iso-8859-1" ),
                array( "file" => "file2.txt",
                       "charset" => "iso-8859-15" ),
                array( "file" => "file3.txt",
                       "charset" => "utf8" ) );

// print( "&#8482;&trade;" );

foreach( $files as $fileItem )
{
    $file = $fileItem["file"];
    $charset = $fileItem["charset"];
    $codec =& eZTextCodec::instance( $charset );
    $fd = fopen( "lib/ezi18n/sdk/" . $file, "r" );
    $text = fread( $fd, filesize( "lib/ezi18n/sdk/" . $file ) );
    fclose( $fd );
    $convertedText = $codec->convertString( $text );
    print( "<h1>File: $file ($charset)</h1>" );
    print( "<h2>Original text</h2>" );
//     print( "<pre class=\"example\">$text</pre>" );
    print( "<p>$text</p>" );
    print( "<h2>Converted text</h2>" );
//     print( "<pre class=\"example\">$convertedText</pre>" );
    print( "<p>$convertedText</p>" );
}

?>

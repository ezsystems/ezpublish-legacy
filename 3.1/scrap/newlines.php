<?php

$strs = array( "abc\ndef \nhij\n",
               "abc\r\ndef \r\nhij\r\n",
               "abc\rdef \rhij\r" );
foreach( $strs as $str )
{
    $arr = preg_split( "#\r\n|\r|\n#", $str );
    foreach( $arr as $text )
    {
        print( "=> \"$text\"\n" );
    }
    print( "\n" );
}

?>

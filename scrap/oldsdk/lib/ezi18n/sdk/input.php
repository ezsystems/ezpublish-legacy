<?php

include_once( "lib/ezi18n/classes/eztextcodec.php" );
include_once( "lib/ezi18n/classes/ezcodepage.php" );

if ( !isset( $Input ) )
    header( "Content-Type: text/html;charset=utf8" );
else
header( "Content-Type: text/html;charset=iso-8859-1" );

print( 'All text written here will be input as utf8 and output as your standard charset<h1>Enter text</h1><form action="/example/ezi18n/input" method="post">
<input name="Input" size="6" type="text">
</form>' );

if ( isset( $Input ) )
    print( "Input without utf8 $Input" );

?>

<?php

include_once( 'lib/ezutils/classes/ezdir.php' );
$path = eZDir::path( array( 'var/', '/cache', 'template', '/tree/' ) );
print( $path . "\n" );

$path2 = eZDir::path( array( 'var/', '///', '/cache', '/', '/', 'template', '/tree/' ), true );
print( $path2 . "\n" );

?>

<?php

ini_set( 'include_path', "../" );

$endl = "<br/>";

include_once( 'lib/ezutils/classes/ezdir.php' );
$path = eZDir::path( array( 'var/', '/cache', 'template', '/tree/' ) );
print( $path . $endl );

$path2 = eZDir::path( array( 'var/', '///', '/cache', '/', '/', 'template', '/tree/' ), true );
print( $path2 . $endl );

$path3 = eZDir::path( array( './var/./storage\../cache/ini\test/../../template', '/tree/' ), true );
print( $path3 . $endl );

?>

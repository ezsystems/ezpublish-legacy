<?php

include_once( 'lib/ezi18n/classes/ezchartransform.php' );
include_once( 'lib/ezi18n/classes/ezcodemapper.php' );


$map = new eZCodeMapper();

$map->parseTransformationFile( "share/transformations/basic.tr" );
var_dump( $map->TransformationTables );

?>

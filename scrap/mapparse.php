<?php

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Map Parser\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "", "", array() );

$script->initialize();

include_once( 'lib/ezi18n/classes/ezchartransform.php' );

$tr = new eZCharTransform();

$in = 'A space';
$out = $tr->transform( $in, 'ascii_lowercase' );

print( $in . "\n" );
print( $out . "\n" );

//$map = new eZCodeMapper();

//$map->parseTransformationFile( "share/transformations/basic.tr" );
var_dump( $tr->Mapper->TransformationTables );
// var_dump( $map->mappingTable( 'ascii_uppercase' ) );

$script->shutdown();

?>

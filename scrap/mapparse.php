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

$codec1 =& eZTextCodec::instance( 'latin1', 'utf8' );
$codec2 =& eZTextCodec::instance( 'utf8', 'latin1' );

//$in = 'A "space" and a \'word\'. Ut på kjøretur for å lære. ĀāŃĦŉŬİıœŒſŮů';
$in = 'A "space" and a \'word\'. Ut p� kj�retur for � l�re';
//$in = $codec1->convertString( $inSrc );
$out = $tr->transformByGroup( $in, 'identifier' );
//$out2 = $codec2->convertString( $outSrc );

print( $in . "\n" );
print( $out . "\n" );
//print( $out2 . "\n" );

//$map = new eZCodeMapper();

//$map->parseTransformationFile( "share/transformations/basic.tr" );
// var_dump( $tr->Mapper->TransformationTables );
// var_dump( $map->mappingTable( 'ascii_uppercase' ) );

$script->shutdown();

?>

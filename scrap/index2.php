<?php

include_once( 'kernel/common/template.php' );

$tpl =& templateInit();
$tpl->setVariable( 'test', 42 );
$tpl->setVariable( 'test2', array( 1, 3, 7 ) );

print( $tpl->fetch( 'design:test.tpl' ) );

print( eZDebug::printReport( false, true, true ) );

?>

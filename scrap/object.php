<?php


include_once( "lib/ezutils/classes/ezmodule.php" );

$module = eZModule::exists( array( "kernel" ), "content" );
$result =& $module->run( "view", array( "full", 1 ) );


eZDebug::printReport( false, false);

?>

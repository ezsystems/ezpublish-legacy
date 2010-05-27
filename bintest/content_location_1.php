<?php
$out = new ezcConsoleOutput();

$out->outputLine( "Getting ezpContentLocation for node #2" );
$location = ezpContentLocation::fetchByNodeId( 2 );

$out->outputLine( "Getting a few attributes" );
$out->outputLine( "path_string: {$location->path_string}" );
$out->outputLine( "name:        {$location->name}" );
?>
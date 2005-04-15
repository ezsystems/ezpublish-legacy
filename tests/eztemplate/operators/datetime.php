<?php

// exclude timezone from timestamp
$timestamp = 1077552775;

$localTimestamp = mktime ( 16, 12, 55, 2, 2, 2004 );
$gmTimestamp = gmmktime ( 16, 12, 55, 2, 2, 2004 );
$timezone = $gmTimestamp - $localTimestamp;

$timestamp -= $timezone;

$tpl->setVariable( 'timestamp', "$timestamp" );

?>
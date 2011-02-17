<?php
// by node ID
$content = ezpContentRepository::fetch( ezpContentIdentifier::nodeId( 2, 4, 6 ) );

// by object ID
$content = ezpContentRepository::fetch( ezpContentIdentifier::objectId( 3, 6, 9 ) );
?>
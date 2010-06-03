<?php
// by node ID
$id = new ezpContentIdentifier;
$id->nodeId = 2;
$content = ezpContentRepository::fetch( $id );

// by object ID
$id = new ezpContentIdentifier;
$id->objectId = 2;
$content = ezpContentRepository::fetch( $id );
?>
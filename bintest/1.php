<?php
/**
 * This file contains a dirty API test
 * @package DirtyStuff
 */
$c = new ezpContentCriteria();

$c->accept[] = ezpContentCriteria::location()->subtree( ezpContentLocation::fetchByNodeId( 2 ) );
$c->accept[] = ezpContentCriteria::contentClass()->is( 'article' );

echo "Status:\n";
echo "\t" . count( $c->accept ) . " accept criteria\n";
echo "\t" . count( $c->deny ) . " deny criteria\n";

echo "\nRaw output:\n";
var_dump( $c );

$articles = ezpContentRepository::query( $c );
?>
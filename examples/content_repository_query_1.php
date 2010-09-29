<?php
/**
 * This file contains a dirty API test
 * @package DirtyStuff
 */
$c = new ezpContentCriteria();

$c->accept[] = ezpContentCriteria::location()->subtree( ezpContentLocation::fetchByNodeId( 2 ) );
$c->accept[] = ezpContentCriteria::contentClass()->is( 'article' );

echo (string)$c;
echo "\n";

$articles = ezpContentRepository::query( $c );
foreach( $articles as $article )
{
    echo "English title: {$article->fields['eng-GB']->title}\n";
}
?>
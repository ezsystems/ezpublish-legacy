<?php
// instanciate a content of type 'article'
$article = ezpContent::create( 'article' );

// add a location for this object
$article->locations[] = ezpContentLocation::fromNodeId( 143 );

// define attributes values
$article->fields->title = 'My article has a title';
$article->fields->body = '<tag>myxmlcontent</tag>';

// At that stage, our article is ready to be published
?>
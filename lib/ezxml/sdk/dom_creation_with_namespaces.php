<?php

include_once( "lib/ezxml/classes/ezxml.php" );

print( "<h3>eZ xml&trade; dom creation example with namespaces:</h3>" );

$doc = new eZDOMDocument();
$doc->setName( "SOAPTest" );

// set the default namespace
$root =& $doc->createElementNodeNS( "http://ez.no/", "document" );

// connect the http://ez.no/ namespace to the ez prefix
$root->setPrefix( "ez" );
$doc->setRoot( $root );

$ezBooks =& $doc->createElementNode( "bookshelf" );
$ezBooks->setPrefix( "ez" );

$root->appendChild( $ezBooks );

// define a new namespace http://ez.no/books/ with the prefix ezbooks
$ezBooks->appendAttribute( $doc->createAttributeNamespaceDefNode( "ezbooks", "http://ez.no/books/" ) );

// define a new namespace http://books.com/ with the prefix otherbooks
$ezBooks->appendAttribute( $doc->createAttributeNamespaceDefNode( "otherbooks", "http://books.com/" ) );

$book1 =& $doc->createElementNode( "book" );

// this book belongs to the http://ez.no/book/ namespace
$book1->setPrefix( "ezbook" );

$book1->appendAttribute( $doc->createAttributeNode( "name", "eZ publish bible" ) );
$ezBooks->appendChild( $book1 );

$book2 =& $doc->createElementNode( "book" );
$book2->setPrefix( "otherbooks" );
$book2->appendAttribute( $doc->createAttributeNode( "name", "Definitive XML Schemas" ) );
$ezBooks->appendChild( $book2 );


$xml =& $doc->toString();

print( "<b>Document output:</b><br>" );
print( "<pre>". nl2br( htmlspecialchars( $xml ) ) . "</pre>" );



?>

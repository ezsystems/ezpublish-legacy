<?php
include_once( "lib/ezxml/classes/ezxml.php" );


print( "<h3>eZ xml&trade; simple dom creation example:</h3>" );


$doc = new eZDOMDocument();
$doc->setName( "SOAPTest" );

$root =& $doc->createElementNode( "document" );
$doc->setRoot( $root );

$book1 =& $doc->createElementNode( "book" );
$book1->appendAttribute( $doc->createAttributeNode( "name", "eZ publish bible" ) );
$root->appendChild( $book1 );

$book2 =& $doc->createElementNode( "book" );
$book2->appendAttribute( $doc->createAttributeNode( "name", "eZ publish development " ) );
$root->appendChild( $book2 );


$xml =& $doc->toString();

print( "<b>Document output:</b><br>" );
print( "<pre>". nl2br( htmlspecialchars( $xml ) ) . "</pre>" );

?>

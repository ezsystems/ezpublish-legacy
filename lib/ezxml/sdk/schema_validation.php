<?php

include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "lib/ezxml/classes/ezschema.php" );


//eZDebug::writeNotice( $dom );

print( "<h2>eZ xml&trade; schema validation examples:</h2>" );


$schemaDoc ='
<?xml version="1.0"?>
<xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.w3.org/2001/XMLSchema/default">

<xsd:annotation>
  <xsd:documentation xml:lang="en">
  Copyright (c) 2002 eZ systems as. All rights reserved.
  </xsd:documentation>
</xsd:annotation>

<xsd:element name="section" type="eZSection"/>

<xsd:complexType name="eZSection">
  <xsd:sequence>
   <xsd:element name="paragraph" type="xsd:string" />
   <xsd:element name="line" type="xsd:string" />
  </xsd:sequence>
 </xsd:complexType>
';

// $schema = new eZSchema();
// $schema->setSchema( $schemaDoc );


// print( "<pre>" );
// print_r( $schema );
// print( "</pre>" );

$doc = "
<?xml version=\"1.0\"?>
<section>
  <paragraph>
  This is contents<line/> this should be on a new line..
  </paragraph>
</section>";


$xml = new eZXML();

$dom =& $xml->domTree( $doc );

//$dom->getNSElement( "http://trolltech.com/fnord/book/", "title" );
//$dom->getNSElement( "book", "title" );

$schema = new eZSchema( );
$schema->setSchema( $schemaDoc );

//$schema->setSchemaFromFile( "lib/ezxml/test/BookStore.xsd" );

// debug
$schema->printSchemaTree();

$schema->printTree( $dom );

$schema->validate( $dom );

//$dom =& $xml->domTree( $schemaDoc, array( "Schema" => $schema ) );

include_once( "lib/ezxml/classes/ezdomdocument.php" );

$document = new eZDOMDocument( "Test document" );

print( "<pre>" );
//print_r( $document );
print( "</pre>" );

print( "<pre>" );
//print_r( $dom );
print( "</pre>" );

?>

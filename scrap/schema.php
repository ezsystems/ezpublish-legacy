<?php

include_once( "lib/ezxml/classes/ezxml.php" );
$xml = new eZXML();

$schema_xml = '<xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns="http://www.w3.org/2001/XMLSchema/default">

<xsd:annotation>
  <xsd:documentation xml:lang="en">
   Translation message schema for ez.no.
   Copyright 2002 ez.no. All rights reserved.
  </xsd:documentation>
 </xsd:annotation>

 <xsd:element name="context" type="ContextType"/>

 <xsd:complexType name="ContextType">
  <xsd:sequence>
   <xsd:element name="name" type="xsd:string" />
   <xsd:element name="message" type="MessageType"/>
  </xsd:sequence>
 </xsd:complexType>

 <xsd:complexType name="MessageType">
  <xsd:sequence>
   <xsd:element name="source"      type="xsd:string"/>
   <xsd:element name="translation" type="TranslationType"/>
   <xsd:element name="comment"     type="xsd:string" minOccurs="0" maxOccurs="1"/>
  </xsd:sequence>
 </xsd:complexType>

 <xsd:simpleType name="TranslationType">
  <xsd:restriction base="xsd:string">
  </xsd:restriction>
  <xsd:attribute name="type" type="xsd:string" />
 </xsd:simpleType>

</xsd:schema>';

$trans_xml = '<!DOCTYPE TS><TS>
<context>
    <name>default</name>
    <message>
        <source>Preview</source>
        <translation>Forhåndsvisning</translation>
    </message>
    <message>
        <source>Versions</source>
        <translation>Versjoner</translation>
    </message>
    <message>
        <source>Translate</source>
        <translation>Oversett</translation>
    </message>
    <message>
        <source>Permission</source>
        <translation>Rettigheter</translation>
    </message>
    <message>
        <source>Store Draft</source>
        <translation>Lagre Draft</translation>
    </message>
    <message>
        <source>Send for publising</source>
        <translation>Publiser</translation>
    </message>
    <message>
        <source>Discard</source>
        <translation>Forkast</translation>
    </message>
    <message>
        <source>Find object</source>
        <translation>Finn objekt</translation>
    </message>
</context>
</TS>';

$tree =& $xml->domTree( $trans_xml );

include_once( "lib/ezxml/classes/ezschema.php" );

$schema = new eZSchema( );
$schema->setSchema( $schema_xml );

$schema->validate( $tree );


?>

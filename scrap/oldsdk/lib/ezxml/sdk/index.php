<?php
//
// Created on: <28-May-2002 13:44:43 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$infoArray = array();
$infoArray["name"] = "eZ xml";
$infoArray["description"] = "
<h1>eZ xml&trade;</h1>

<blockquote>
The Extensible Markup Language (XML) is the universal format for structured documents and data on the Web.
</blockquote>

<p>
eZ xml&trade; is a DOM XML parser written in PHP. It does not need any external libraries
to work. eZ xml&trade; enables you to parse and manipulate XML documents. eZ xml supports handling of
XML documents as defined in
<a href='http://www.w3.org/TR/2000/REC-xml-20001006'>Extensible Markup Language (XML) 1.0</a>.
Namespaces are supported as described by W3C in
<a href='http://www.w3.org/TR/1999/REC-xml-names-19990114/'>Namespaces in XML</a>.
eZ xml supports the
<a href='http://www.w3.org/TR/xmlschema-0/'>XML Schema</a> standard for validating XML documents.
It follows the
<a href='http://www.w3.org/TR/2000/REC-DOM-Level-2-Core-20001113/'>DOM level 2</a> as far as possible.
</p>

<h2>Useful links</h2>
<ul>
<li><a href='http://www.w3.org/'>W3C</a></li>
<li><a href='http://www.w3.org/XML/'>XML information on W3C</a></li>
<li><a href='http://www.nitf.org/'>News Industri Text Format</a></li>
</ul>
";

$dependArray = array();
$dependArray[] = array( "uri" => "ezutils",
                        "name" => "eZ utils" );

$infoArray["dependencies"] =& $dependArray;

$featureArray = array();

$featureArray[] = array( "level" => 0,
                         "name" => "XML creation" );
$featureArray[] = array( "uri" => "dom_creation",
                         "level" => 1,
                         "name" => "DOM creation" );
$featureArray[] = array( "uri" => "dom_creation_with_namespaces",
                         "level" => 1,
                         "name" => "DOM creation with namespaces" );
// $featureArray[] = array( "uri" => "schema_validation",
//                          "level" => 1,
//                          "name" => "Schema validation" );

$featureArray[] = array( "level" => 0,
                         "name" => "XML parsing" );
$featureArray[] = array( "uri" => "parsing",
                         "level" => 1,
                         "name" => "Plain parsing" );
$featureArray[] = array( "uri" => "parsing_with_namespaces",
                         "level" => 1,
                         "name" => "Parsing with namespaces" );

$featureArray[] = array( "level" => 0,
                         "name" => "Diagrams" );
$featureArray[] = array( "uri" => "classdiagram",
                         "level" => 1,
                         "name" => "Class diagram" );

$infoArray["features"] =& $featureArray;

$docArray = array();
$docArray[] = array( "uri" => "eZXML",
                     "name" => "XML parser" );
$docArray[] = array( "uri" => "eZDOMDocument",
                     "name" => "DOM document" );
$docArray[] = array( "uri" => "eZDOMNode",
                     "name" => "DOM node" );
$docArray[] = array( "uri" => "eZSchema",
                     "name" => "Schema handler" );

$infoArray["doc"] =& $docArray;

?>

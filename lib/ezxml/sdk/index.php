<?php
//
// Created on: <28-May-2002 13:44:43 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
<h1>eZ xml&trade; - xml made easy</h1>
<p>
eZ xml&trade; is a DOM XML parser written in PHP and does not need any external libraries
to work. eZ xml&trade; enables you to parse and manupulate XML documents. It follows the
<a href='http://www.w3.org/TR/2000/REC-DOM-Level-2-Core-20001113/'>DOM level 2</a> as
far as possible.
</p>
<blockquote>
The Extensible Markup Language (XML) is the universal format for structured documents and data on the Web.
</blockquote>
";

$dependArray = array();
$dependArray[] = array( "uri" => "ezutils",
                        "name" => "eZ utils" );

$infoArray["dependencies"] =& $dependArray;

$featureArray = array();
$featureArray[] = array( "level" => 0,
                         "name" => "Diagrams" );
$featureArray[] = array( "uri" => "classdiagram",
                         "level" => 1,
                         "name" => "Class diagram" );

$featureArray[] = array( "level" => 0,
                         "name" => "XML parsing" );
$featureArray[] = array( "uri" => "parsing",
                         "level" => 1,
                         "name" => "Plain parsing" );
$featureArray[] = array( "uri" => "parsing_with_namespaces",
                         "level" => 1,
                         "name" => "Namespace XML parsing" );
$featureArray[] = array( "level" => 0,
                         "name" => "XML creation" );
$featureArray[] = array( "uri" => "dom_creation",
                         "level" => 1,
                         "name" => "DOM creation" );
$featureArray[] = array( "uri" => "dom_creation_with_namespaces",
                         "level" => 1,
                         "name" => "DOM creation with namespaces" );
$featureArray[] = array( "uri" => "schema_validation",
                         "level" => 1,
                         "name" => "Schema validation" );

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

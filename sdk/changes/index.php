<?php

include_once( "lib/ezutils/classes/ezsys.php" );

$indexPathPrepend = eZSys::wwwDir() .  eZSys::indexFile();

$infoArray = array();
$infoArray["name"] = "What's New";
$infoArray["description"] = '<centre><h1>Key Features in eZ publish 3.0</h1></centre>

<h2>Integrated search engine</h2>
<p>
eZ publish has a fully integrated search engine architecture which enabled
you to use the powerful built in search engine or create a plugin for your
favourite search engine.
</p>

<h2>XML handling and parsing library</h2>
<p>
eZ xml handles parsing, creation and validating of XML documents.
</p>

<h2>Advanced template engine</h2>
<p>
The new template engine, eZ template, is a sophisticated and integrated
template engine. The site designers can now quickly do powerful customizations
without touching PHP code.
</p>

<h2>SOAP communication library</h2>
<p>
</p>

<h2>Database library</h2>
<h2>Image handline library</h1>
<h2>Localisation and internationalization libraries</h2>
<h2>Unicode</h2>

<h2>Fully documented API</h2>
<p>
Lots of tutorials and howtos
- UML diagrams
</p>
';
$infoArray["partfile"] = "changes.php";
$infoArray["trademark"] = false;

$featureArray = array();
//$featureArray[] = array( "level" => 0,
//                         "uri" => "todo",
//                         "name" => "Todo" );
$featureArray[] = array( "level" => 0,
                         "name" => "Changes" );
$featureArray[] = array( "uri" => "changelog/2.9",
                         "level" => 0,
                         "name" => "2.9 series" );

$infoArray["features"] =& $featureArray;

?>

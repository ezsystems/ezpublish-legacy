<?php

include_once( "lib/ezutils/classes/ezsys.php" );

$indexPathPrepend = eZSys::wwwDir() .  eZSys::indexFile();

$infoArray = array();
$infoArray["name"] = "API Reference";
$infoArray["description"] = '<h1>Introduction</h1>
<p>The API Reference for eZ publish&trade; consists of multiple sections which
each have a different view on the reference. The sections can be accessed at
menu to the left. </p>
<p>The reference will give an overview of the API of eZ publish&trade;. For user guides
you should visit the <a href="' . $indexPathPrepend . '/sdk">SDK area</a></p>

<p class="footnote">All API reference has been generated with <a href="http://www.doxygen.org">doxygen</a></p>

';
$infoArray["partfile"] = "ref.php";
$infoArray["trademark"] = false;

$featureArray = array();
$featureArray[] = array( "level" => 0,
                         "name" => "Sections" );
$featureArray[] = array( "uri" => "modules",
                         "level" => 0,
                         "name" => "Modules" );
$featureArray[] = array( "uri" => "hierarchy",
                         "level" => 0,
                         "name" => "Hierarchy" );
$featureArray[] = array( "uri" => "annotated",
                         "level" => 0,
                         "name" => "Annotated" );
$featureArray[] = array( "uri" => "files",
                         "level" => 0,
                         "name" => "Files" );
$featureArray[] = array( "uri" => "functions",
                         "level" => 0,
                         "name" => "Functions" );
$featureArray[] = array( "uri" => "related",
                         "level" => 0,
                         "name" => "Related" );
$featureArray[] = array( "uri" => "todo",
                         "level" => 1,
                         "name" => "Todo" );

$infoArray["features"] =& $featureArray;

?>

<?php

include_once( "lib/ezutils/classes/ezsys.php" );

$indexPathPrepend = eZSys::wwwDir() .  eZSys::indexFile();

$infoArray = array();
$infoArray["name"] = "Kernel tutorials";
$infoArray["description"] = '<h1>Introduction</h1>
<p>These kernel tutorials will give you an introduction into how programming with kernel classes,
objects and similar items is done.</p>
';
$infoArray["partfile"] = "kernel.php";
$infoArray["trademark"] = false;

$featureArray = array();

$featureArray[] = array( "level" => 0,
                         "name" => "Key concepts" );

$featureArray[] = array( "uri" => "module_system",
                         "level" => 0,
                         "name" => "Module system" );

$featureArray[] = array( "uri" => "content_classes",
                         "level" => 0,
                         "name" => "Content classes" );
$featureArray[] = array( "uri" => "content_objects",
                         "level" => 0,
                         "name" => "Content objects" );
$featureArray[] = array( "uri" => "permissions",
                         "level" => 0,
                         "name" => "Permissions" );
$featureArray[] = array( "uri" => "object_workflow",
                         "level" => 0,
                         "name" => "Object workflow" );
$featureArray[] = array( "uri" => "object_version",
                         "level" => 0,
                         "name" => "Object version" );
$featureArray[] = array( "uri" => "object_translation",
                         "level" => 0,
                         "name" => "Object translation" );
$featureArray[] = array( "uri" => "content_datatypes",
                         "level" => 0,
                         "name" => "Content DataTypes" );
$featureArray[] = array( "uri" => "content_datatype_ezstring",
                         "level" => 1,
                         "name" => "ezstring" );

$featureArray[] = array( "uri" => "data_storage",
                         "level" => 0,
                         "name" => "Data storage" );
$featureArray[] = array( "uri" => "input_conversion",
                         "level" => 0,
                         "name" => "Input conversion" );

$featureArray[] = array( "level" => 0,
                         "name" => "Templates" );
$featureArray[] = array( "uri" => "template_operators",
                         "level" => 0,
                         "name" => "Operators" );
$featureArray[] = array( "uri" => "template_functions",
                         "level" => 0,
                         "name" => "Functions" );

$infoArray["features"] =& $featureArray;

?>

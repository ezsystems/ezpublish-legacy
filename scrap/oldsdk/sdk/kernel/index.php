<?php

include_once( "lib/ezutils/classes/ezsys.php" );

$indexPathPrepend = eZSys::wwwDir() .  eZSys::indexFile();

$infoArray = array();
$infoArray["name"] = "Kernel";
$infoArray["description"] = '<h1>Introduction</h1>
<p>The eZ publish<sup>TM</sup> kernel is the core code of eZ publish and controls all low level
functionality like content classes, content objects, workflows, permissions and more.</p>
';
$infoArray["partfile"] = "kernel.php";
$infoArray["trademark"] = false;

$featureArray = array();

// $featureArray[] = array( "level" => 0,
//                          "name" => "Key concepts" );

$featureArray[] = array( "uri" => "module_system",
                         "level" => 0,
                         "name" => "Module system" );
$featureArray[] = array( "uri" => "content_classes",
                         "level" => 0,
                         "name" => "Content classes" );
$featureArray[] = array( "uri" => "content_objects",
                         "level" => 0,
                         "name" => "Content objects" );
$featureArray[] = array( "uri" => "content_datatypes",
                         "level" => 0,
                         "name" => "Content datatypes" );
$featureArray[] = array( "uri" => "permissions",
                         "level" => 0,
                         "name" => "Permissions" );
$featureArray[] = array( "uri" => "workflows",
                         "level" => 0,
                         "name" => "Workflows" );
// $featureArray[] = array( "uri" => "object_version",
//                          "level" => 0,
//                          "name" => "Object version" );
// $featureArray[] = array( "uri" => "object_translation",
//                          "level" => 0,
//                          "name" => "Object translation" );
// $featureArray[] = array( "uri" => "content_datatype_ezstring",
//                          "level" => 0,
//                          "name" => "ezstring" );
// $featureArray[] = array( "uri" => "information_collector",
//                          "level" => 0,
//                          "name" => "Information collector" );
$featureArray[] = array( "uri" => "data_storage",
                         "level" => 0,
                         "name" => "Data storage" );
// $featureArray[] = array( "level" => 0,
//                          "name" => "Templates" );
$featureArray[] = array( "uri" => "template_operators",
                         "level" => 0,
                         "name" => "Template operators" );
// $featureArray[] = array( "uri" => "template_functions",
//                          "level" => 0,
//                          "name" => "Functions" );
$featureArray[] = array( "uri" => "other_concepts",
                         "level" => 0,
                         "name" => "Other concepts" );

$infoArray["features"] =& $featureArray;

?>

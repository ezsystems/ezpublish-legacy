<?php

include_once( "lib/ezutils/classes/ezsys.php" );

$indexPathPrepend = eZSys::wwwDir() . eZSys::indexFile();

$infoArray = array();
$infoArray["name"] = "What's New";
$infoArray["description"] = "<centre><h1>What's new in eZ publish 3.0</h1></centre>";
$infoArray["partfile"] = "changes.php";
$infoArray["trademark"] = false;

$featureArray = array();
//$featureArray[] = array( "level" => 0,
//                         "uri" => "todo",
//                         "name" => "Todo" );
$featureArray[] = array( "uri" => "features",
                         "level" => 0,
                         "name" => "Key features" );
$featureArray[] = array( "level" => 0,
                         "name" => "Changelog" );
$featureArray[] = array( "uri" => "changelog/2.9",
                         "level" => 0,
                         "name" => "2.9 series" );

$infoArray["features"] =& $featureArray;

?>

<?php

$infoArray = array();
$infoArray["name"] = "eZ locale";
$infoArray["description"] = "
<h1>eZ locale&trade;</h1>
<p>eZ locale&trade; is a system for handling locale specific settings.</p>
<h2>eZ locale&trade; supports:</h2>
<ul>
<li>Currency display</li>
<li>Date and time display</li>
<li>Language details such as character set and country codes</li>
</ul>";

$dependArray = array();
$dependArray[] = array( "uri" => "ezutils",
                        "name" => "eZ utils" );

$infoArray["dependencies"] =& $dependArray;

$exampleArray = array();
$exampleArray[] = array( "uri" => "locale",
                         "name" => "Locale settings" );

$infoArray["features"] =& $exampleArray;

$docArray = array();
$docArray[] = array( "uri" => "eZLocale",
                     "name" => "Locale handler" );
$docArray[] = array( "uri" => "eZData",
                     "name" => "Date handling" );
$docArray[] = array( "uri" => "eZTime",
                     "name" => "Time handling" );
$docArray[] = array( "uri" => "eZDateTime",
                     "name" => "Date and time handling" );
$docArray[] = array( "uri" => "eZCurrency",
                     "name" => "Currency handling" );

$infoArray["doc"] =& $docArray;

?>

<?php

$infoArray = array();
$infoArray["name"] = "eZ image";
$infoArray["description"] = "
<h1>eZ image&trade;</h1>

<p>The eZ image&trade; library allows for transparent conversion of one image format
to another. The conversion may be done in one step if the required
conversion type is available, or it may build a tree of conversion
rules which is needed to reach the desired end format.</p>

<p>It's also possible to run operations on images. It's up to each conversion
rule to report whether or not the operation is supported, the manager will
then distribute the operations on the available rules that can handle them.</p>

<p>Examples of operations are scaling and grayscale.</p>";

$dependArray = array();
$dependArray[] = array( "uri" => "ezutils",
                        "name" => "eZ utils" );

$infoArray["dependencies"] =& $dependArray;

$exampleArray = array();
$exampleArray[] = array( "uri" => "rules",
                         "name" => "Image rules" );
$exampleArray[] = array( "uri" => "conversion",
                         "name" => "Image conversion" );
$exampleArray[] = array( "uri" => "scaling",
                         "name" => "Image scaling" );

$infoArray["features"] =& $exampleArray;

$docArray = array();
$docArray[] = array( "uri" => "eZImageManager",
                     "name" => "Image manager" );
$docArray[] = array( "uri" => "eZImageShell",
                     "name" => "Shell executor" );
$docArray[] = array( "uri" => "eZImageGD",
                     "name" => "Image conversion with ImageGD" );

$infoArray["doc"] =& $docArray;

?>

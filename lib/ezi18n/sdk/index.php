<?php

$infoArray = array();
$infoArray["name"] = "eZ i18n";
$infoArray["description"] = "
<h1>eZ i18n&trade;</h1>

<p>
eZ i18n&trade; handles text conversion between various charsets, its primary use is for
converting to and from UTF8. Text manipulation regardless of the charset used is possible
by using the eZTextCodec class. It's also possible to query for information on various
charsets.
</p>

<p>
eZ i18n&trade; supports the mbstring extension which can be compiled in PHP, when used
the conversion will be much faster. mbstring is only used for those charsets it supports.
</p>

<p>
Text translation is also possible by using the eZTranslatorManager and XML translation files&#185;.
</p>

<p class=\"footnote\">&#185; Use Qt's linguist to translate files</p>

<h2>TODO</h2>
<ul>
<li>Add support for Unicode collation algorithm, other encoding schemes should be supported as well</li>
</ul>

<h2>Resources</h2>
<ul>
<li><a href=\"http://doc.trolltech.com/3.0/linguist-manual.html\">Linguist</a></li>
</ul>
";

$dependArray = array();
$dependArray[] = array( "uri" => "ezutils",
                        "name" => "eZ utils" );

$infoArray["dependencies"] =& $dependArray;

$featureArray = array();
$featureArray[] = array( "uri" => "character_encoding_scheme",
                         "name" => "Character encoding" );
// $featureArray[] = array( "uri" => "codepage_example",
//                          "name" => "Codepage example" );
$featureArray[] = array( "uri" => "codepage_mapping",
                         "name" => "Codepage mapping" );
$featureArray[] = array( "uri" => "mbstring",
                         "name" => "MBString extension" );
$featureArray[] = array( "uri" => "textcodec",
                         "name" => "TextCodec conversion" );
// $featureArray[] = array( "uri" => "rev_textcodec",
//                          "name" => "Reverse TextCodec conversion" );
// $featureArray[] = array( "uri" => "output",
//                          "name" => "Charset output" );
// $featureArray[] = array( "uri" => "input",
//                          "name" => "Charset input" );
$featureArray[] = array( "uri" => "translate",
                         "name" => "Translation" );
// $featureArray[] = array( "uri" => "file_reading",
//                          "name" => "File Reading" );
$featureArray[] = array( "uri" => "databases",
                         "name" => "Databases" );

$featureArray[] = array( "name" => "Diagrams" );
$featureArray[] = array( "uri" => "charset_sources",
                         "name" => "Charset sources" );
// $featureArray[] = array( "uri" => "charset_conversions",
//                          "name" => "Charset conversions" );
$featureArray[] = array( "uri" => "dia_translation",
                         "name" => "Translation System" );

$infoArray["features"] =& $featureArray;

$docArray = array();
// $docArray[] = array( "uri" => "eZCodepageCodec",
//                      "name" => "Codepage Codec" );
// $docArray[] = array( "uri" => "ezcodepage",
//                      "name" => "Codepage reader" );
// $docArray[] = array( "uri" => "eztextcodec",
//                      "name" => "General TextCodec" );
// $docArray[] = array( "uri" => "ezutf8codec",
//                      "name" => "UTF8 Codec" );
$docArray[] = array( "uri" => "eZTranslatorManager",
                     "name" => "Translator Manager" );

// Disabled for now, until the classes are done
$infoArray["doc"] =& $docArray;

?>

<?php

include_once( "lib/ezi18n/classes/eztextcodec.php" );
include_once( "lib/ezi18n/classes/ezcodepage.php" );

$uri =& eZURI::instance();

$charset = "iso-8859-1";
if ( $uri->element() != "" )
    $charset = $uri->element();
header( "Content-Type: text/html;charset=utf8" );
$codec =& eZTextCodec::codecForName( $charset );
$utf8_str = $codec->process( "abcXYZæøåÆØÅ" );
eZDebug::writeNotice( "Test of codepages: " . $utf8_str );
eZDebug::writeNotice( "strlen(): " . strlen( $utf8_str ) );
eZDebug::writeNotice( "length(): " . $codec->length( $utf8_str ) );
eZDebug::writeNotice( "strtoupper(): " . strtoupper( $utf8_str ) );
eZDebug::writeNotice( "toUpper(): " . $codec->toUpper( $utf8_str ) );
eZDebug::writeNotice( "strtolower(): " . strtoupper( $utf8_str ) );
eZDebug::writeNotice( "toLower(): " . $codec->toUpper( $utf8_str ) );

?>

<?php

$Result = array();
$Result["title"] = "TextCodec";
$Result["content"] = "";

include_once( "lib/ezi18n/classes/eztextcodec.php" );

header( "Content-Type: text/html; charset=utf8" );

$latinstr = "";
for ( $i = 0x41; $i < 0x60; ++$i )
    $latinstr .= chr( $i );

$cyrstr = "";
for ( $i = 0xb0; $i < 0xd0; ++$i )
    $cyrstr .= chr( $i );

$greekstr = "";
for ( $i = 0xc1; $i < 0xe0; ++$i )
    $greekstr .= chr( $i );

$hebrewstr = "";
for ( $i = 0xe0; $i < 0xfb; ++$i )
    $hebrewstr .= chr( $i );

$arabicstr = "";
for ( $i = 0xc0; $i < 0xdf; ++$i )
    $arabicstr .= chr( $i );

$northeurstr = "";
for ( $i = 0xc0; $i < 0xe0; ++$i )
    $northeurstr .= chr( $i );

$sjisstr = "";
for ( $i = 0x829f; $i < 0x82c2; ++$i )
    $sjisstr .= chr( $i >> 8 ) . chr( $i & 0xff );

$sjisstr2 = "";
for ( $i = 0x8340; $i < 0x8362; ++$i )
    $sjisstr2 .= chr( $i >> 8 ) . chr( $i & 0xff );

$sjisstr3 = "";
for ( $i = 0x889f; $i < 0x88cb; ++$i )
    $sjisstr3 .= chr( $i >> 8 ) . chr( $i & 0xff );

$sjisstr4 = "";
for ( $i = 0xa6; $i < 0xc5; ++$i )
    $sjisstr4 .= chr( $i );

$convert_list = array( array( "text" => $latinstr,
                              "charset" => "iso-8859-1",
                              "name" => "Latin1" ),
                       array( "text" => $cyrstr,
                              "charset" => "cyrillic",
                              "name" => "Cyrillic" ),
                       array( "text" => $greekstr,
                              "charset" => "iso-8859-7",
                              "name" => "Greek" ),
                       array( "text" => $northeurstr,
                              "charset" => "iso-8859-4",
                              "name" => "Northern Europe" ),
                       array( "text" => $hebrewstr,
                              "charset" => "iso-8859-8",
                              "name" => "Hebrew" ),
                       array( "text" => $arabicstr,
                              "charset" => "iso-8859-6",
                              "name" => "Arabic" ),
                       array( "text" => $sjisstr,
                              "charset" => "cp932",
                              "name" => "Hiragana" ),
                       array( "text" => $sjisstr2,
                              "charset" => "cp932",
                              "name" => "Katakana" ),
                       array( "text" => $sjisstr3,
                              "charset" => "cp932",
                              "name" => "CJK Unified" ),
                       array( "text" => $sjisstr4,
                              "charset" => "cp932",
                              "name" => "Halfwidth Katakana" )
                       );

print( "<p>
The TextCodec allows for uniform conversion from one charset to another, it does conversion using codepages
and using the mbstring extension for some charset conversions. This page does not use the mbstring extension
to show that it can handle these charsets itself.
</p>

<p>
The table below displays some of the various charsets it can convert and it's utf8 conversion. Using
utf8 allows us to display the various characters in one page.
</p>
" );

print( "<table>
<tr><th>Requested charset</th><th>Charset</th><th>Name</th><th>Text</th><th>Original strlen</th><th>Correct strlen</th></tr>\n" );

eZTextCodec::setUseMBString( false );

foreach( $convert_list as $convert_item )
{
    $charset = $convert_item["charset"];
    $text = $convert_item["text"];
    $name = $convert_item["name"];
    $codec =& eZTextCodec::instance( $charset, "utf-8" );
    $req_charset = $codec->requestedInputCharsetCode();
    $charset = $codec->inputCharsetCode();

    $out = $codec->convertString( $text );
    print( "<tr><td>$req_charset</td><td>$charset</td><td>$name</td><td>$out</td><td>" . strlen( $text ) . "</td><td>" . $codec->strlen( $text ) . "</td></tr>\n" );
}
print( "</table>
<p class=\"footnote\">Not all characters may be visible depending on your browser font support.</p>
" );

?>

<?php
//
// Created on: <04-Jun-2002 12:31:42 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

?>

<h1>TextCodec conversion</h1>

<p>
The TextCodec allows for uniform conversion from one charset to another, it does conversion using codepages
and using the mbstring extension for some charset conversions.
</p>

<p>
The table below displays some of the various charsets it can convert and it's utf8 conversion. Using
utf8 allows us to display the various characters in one page.
</p>

<img src="/doc/images/mbstring.jpg"/>

<p>
This table can be produced by the following code:
</p>

<pre class="example">
include_once( "lib/ezi18n/classes/eztextcodec.php" );

header( "Content-Type: text/html; charset=utf8" );

$latinstr = "";
for ( $i = 0x41; $i &lt; 0x60; ++$i )
    $latinstr .= chr( $i );

$cyrstr = "";
for ( $i = 0xb0; $i &lt; 0xd0; ++$i )
    $cyrstr .= chr( $i );

$greekstr = "";
for ( $i = 0xc1; $i &lt; 0xe0; ++$i )
    $greekstr .= chr( $i );

$hebrewstr = "";
for ( $i = 0xe0; $i &lt; 0xfb; ++$i )
    $hebrewstr .= chr( $i );

$arabicstr = "";
for ( $i = 0xc0; $i &lt; 0xdf; ++$i )
    $arabicstr .= chr( $i );

$northeurstr = "";
for ( $i = 0xc0; $i &lt; 0xe0; ++$i )
    $northeurstr .= chr( $i );

$sjisstr = "";
for ( $i = 0x829f; $i &lt; 0x82c2; ++$i )
    $sjisstr .= chr( $i &gt;&gt; 8 ) . chr( $i & 0xff );

$sjisstr2 = "";
for ( $i = 0x8340; $i &lt; 0x8362; ++$i )
    $sjisstr2 .= chr( $i &gt;&gt; 8 ) . chr( $i & 0xff );

$sjisstr3 = "";
for ( $i = 0x889f; $i &lt; 0x88cb; ++$i )
    $sjisstr3 .= chr( $i &gt;&gt; 8 ) . chr( $i & 0xff );

$sjisstr4 = "";
for ( $i = 0xa6; $i &lt; 0xc5; ++$i )
    $sjisstr4 .= chr( $i );

$convert_list = array( array( "text" =&gt; $latinstr,
                              "charset" =&gt; "iso-8859-1",
                              "name" =&gt; "Latin1" ),
                       array( "text" =&gt; $cyrstr,
                              "charset" =&gt; "cyrillic",
                              "name" =&gt; "Cyrillic" ),
                       array( "text" =&gt; $greekstr,
                              "charset" =&gt; "iso-8859-7",
                              "name" =&gt; "Greek" ),
                       array( "text" =&gt; $northeurstr,
                              "charset" =&gt; "iso-8859-4",
                              "name" =&gt; "Northern Europe" ),
                       array( "text" =&gt; $hebrewstr,
                              "charset" =&gt; "iso-8859-8",
                              "name" =&gt; "Hebrew" ),
                       array( "text" =&gt; $arabicstr,
                              "charset" =&gt; "iso-8859-6",
                              "name" =&gt; "Arabic" ),
                       array( "text" =&gt; $sjisstr,
                              "charset" =&gt; "cp932",
                              "name" =&gt; "Hiragana" ),
                       array( "text" =&gt; $sjisstr2,
                              "charset" =&gt; "cp932",
                              "name" =&gt; "Katakana" ),
                       array( "text" =&gt; $sjisstr3,
                              "charset" =&gt; "cp932",
                              "name" =&gt; "CJK Unified" ),
                       array( "text" =&gt; $sjisstr4,
                              "charset" =&gt; "cp932",
                              "name" =&gt; "Halfwidth Katakana" )
                       );

print( "&lt;p&gt;
The TextCodec allows for uniform conversion from one charset to another, it does conversion using codepages
and using the mbstring extension for some charset conversions. This page does not use the mbstring extension
to show that it can handle these charsets itself.
&lt;/p&gt;

&lt;p&gt;
The table below displays some of the various charsets it can convert and it's utf8 conversion. Using
utf8 allows us to display the various characters in one page.
&lt;/p&gt;
" );

print( "&lt;table&gt;
&lt;tr&gt;&lt;th&gt;Requested charset&lt;/th&gt;&lt;th&gt;Charset&lt;/th&gt;&lt;th&gt;Name&lt;/th&gt;&lt;th&gt;Text&lt;/th&gt;&lt;th&gt;Original strlen&lt;/th&gt;&lt;th&gt;Correct strlen&lt;/th&gt;&lt;/tr&gt;\n" );

eZTextCodec::setUseMBString( false );

foreach( $convert_list as $convert_item )
{
    $charset = $convert_item["charset"];
    $text = $convert_item["text"];
    $name = $convert_item["name"];
    $codec =& eZTextCodec::instance( $charset, "utf-8" );
    $req_charset = $codec-&gt;requestedInputCharsetCode();
    $charset = $codec-&gt;inputCharsetCode();

    $out = $codec-&gt;convertString( $text );
    print( "&lt;tr&gt;&lt;td&gt;$req_charset&lt;/td&gt;&lt;td&gt;$charset&lt;/td&gt;&lt;td&gt;$name&lt;/td&gt;&lt;td&gt;$out&lt;/td&gt;&lt;td&gt;" . strlen( $text ) . "&lt;/td&gt;&lt;td&gt;" . $codec-&gt;strlen( $text ) . "&lt;/td&gt;&lt;/tr&gt;\n" );
}
print( "&lt;/table&gt;
&lt;p class=\"footnote\"&gt;Not all characters may be visible depending on your browser font support.&lt;/p&gt;
" );
</pre>

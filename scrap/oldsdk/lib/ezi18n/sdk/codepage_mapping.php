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

<h1>Codepage mapping</h1>

<p>
Mapping from one codepage to another can be done using the eZCodePageMapper class,
it provides instantaneous mapping from one character code in one charset to the
code in the other charset.
</p>

<img src="/doc/images/codepage_mapping.jpg"/>

<p>
This table can be produced by the following code.
Table <b>1</b> shows the charset to map from, table <b>2</b> shows the charset to map to
and table <b>3</b> shows the characters that could be mapped.
</p>

<pre class="example">
function displayCharsetTable( $values,
                              $x_start, $x_stop,
                              $y_start, $y_stop )
{
    print( "&lt;table&gt;\n&lt;tr&gt;&lt;td&gt;&lt;/td&gt;" );
    for ( $x = $x_start; $x &lt; $x_stop; ++$x )
        print( "&lt;td&gt;$x&lt;/td&gt;" );
    print( "&lt;/tr&gt;\n" );
    for ( $y = $y_start; $y &lt; $y_stop; ++$y )
    {
        print( "&lt;tr&gt;&lt;td&gt;$y&lt;/td&gt;" );
        for ( $x = $x_start; $x &lt; $x_stop; ++$x )
        {
            if ( isset( $values[$x][$y] ) )
            {
                $value = $values[$x][$y];
                $char_utf8 = htmlspecialchars( $value[0] );
                $char_code = $value[1];
                print( "&lt;td&gt;$char_utf8&lt;/td&gt;" );
            }
            else
                print( "&lt;td&gt;&lt;/td&gt;" );
        }
        print( "&lt;/tr&gt;\n" );
    }
    print( "&lt;/table&gt;\n" );
}

function generateCharsetTable( &$values, &$codepage,
                               $x_start, $x_stop,
                               $y_start, $y_stop )
{
    $values = array();
    for ( $y = $y_start; $y &lt; $y_stop; ++$y )
    {
        for ( $x = $x_start; $x &lt; $x_stop; ++$x )
        {
            $code = ($y*$y_stop) + $x;
            $utf8 = $codepage-&gt;codeToUtf8( $code );
            $values[$x][$y] = array( $utf8, $code );
        }
    }
}

header( "Content-Type: text/html; charset=utf8" );

include_once( "lib/ezi18n/classes/ezcodepagemapper.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );

$input_charset = "cyrillic";
$output_charset = "wincyrillic";

$http =& eZHTTPTool::instance();
if ( $http-&gt;hasPostVariable( "ChangeCharset" ) )
{
    if ( $http-&gt;hasPostVariable( "InputCharset" ) )
    {
        $input_charset = $http-&gt;postVariable( "InputCharset" );
    }
    if ( $http-&gt;hasPostVariable( "OutputCharset" ) )
    {
        $output_charset = $http-&gt;postVariable( "OutputCharset" );
    }
}

$input_codepage =& eZCodePage::instance( $input_charset );
$output_codepage =& eZCodePage::instance( $output_charset );
$codepage_mapper =& eZCodePageMapper::instance( $input_charset, $output_charset );

print( "
&lt;form action=\"\" method=\"post\" name=\"CodepageMapping\"&gt;
&lt;p&gt;
Table &lt;b&gt;1&lt;/b&gt; shows the charset to map from, table &lt;b&gt;2&lt;/b&gt; shows the charset to map to
and table &lt;b&gt;3&lt;/b&gt; shows the characters that could be mapped.
&lt;/p&gt;

&lt;p class=\"footnote\"&gt;
This page uses utf8 output to allow displaying two different charsets at the same time,
you might not see all characters depending on your font support.
&lt;/p&gt;
" );


generateCharsetTable( $input_values, $input_codepage,
                      0, 16,
                      0, 16 );
generateCharsetTable( $output_values, $output_codepage,
                      0, 16,
                      0, 16 );

$mapped_values = array();
for ( $y = 0; $y &lt; 16; ++$y )
{
    for ( $x = 0; $x &lt; 16; ++$x )
    {
        $input_code = ($y*16) + $x;
        $output_code = $codepage_mapper-&gt;mapInputCode( $input_code );
        $output_utf8 = $output_codepage-&gt;codeToUtf8( $output_code );
        $output_x = ( $output_code % 16 );
        $output_y = ( $output_code / 16 );
        $mapped_values[$output_x][$output_y] = array( $output_utf8, $input_code );
    }
}

$charset_list = eZCodePage::codepageList();

print( "&lt;table width=\"100%\"&gt;
&lt;tr&gt;&lt;td&gt;
&lt;select name=\"InputCharset\"&gt;" );
foreach( $charset_list as $charset )
{
    print( "&lt;option value=\"$charset\" " . ( $input_codepage-&gt;charsetCode() == $charset ? "selected=\"selected\"" : "" ) . "&gt;$charset&lt;/option&gt;" );
}
print( "&lt;/select&gt;
&lt;/td&gt;&lt;td&gt;
&lt;select name=\"OutputCharset\"&gt;" );
foreach( $charset_list as $charset )
{
    print( "&lt;option value=\"$charset\" " . ( $output_codepage-&gt;charsetCode() == $charset ? "selected=\"selected\"" : "" ) . "&gt;$charset&lt;/option&gt;" );
}
print( "&lt;/select&gt;

&lt;/td&gt;&lt;td&gt;
&lt;input class=\"stdbutton\" type=\"submit\" Name=\"ChangeCharset\" value=\"Change charset\"&gt;
&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;" );
print( "&lt;h2&gt;1. " . $input_codepage-&gt;charsetCode() );
if ( $input_codepage-&gt;charsetCode() != $input_codepage-&gt;requestedCharsetCode() )
    print( "(" . $input_codepage-&gt;requestedCharsetCode() . ")" );
print( "&lt;/h2&gt;" );
displayCharsetTable( $input_values,
                     0, 16,
                     0, 16 );
print( "&lt;/td&gt;&lt;td&gt;" );
print( "&lt;h2&gt;2. " . $output_codepage-&gt;charsetCode() );
if ( $output_codepage-&gt;charsetCode() != $output_codepage-&gt;requestedCharsetCode() )
    print( "(" . $output_codepage-&gt;requestedCharsetCode() . ")" );
print( "&lt;/h2&gt;" );
displayCharsetTable( $output_values,
                     0, 16,
                     0, 16 );
print( "&lt;/td&gt;" );
print( "&lt;td colspan=\"2\"&gt;" );
print( "&lt;h2&gt;3. " . $input_codepage-&gt;charsetCode() . " =&gt; " . $output_codepage-&gt;charsetCode() . "&lt;/h2&gt;" );
displayCharsetTable( $mapped_values,
                     0, 16,
                     0, 16 );
print( "&lt;/td&gt;&lt;/tr&gt;" );
print( "&lt;/table&gt;" );
print( "&lt;/form&gt;" );
</pre>

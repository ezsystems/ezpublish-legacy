<?php
//
// Created on: <04-Jun-2002 12:31:42 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

function displayCharsetTable( $values,
                              $x_start, $x_stop,
                              $y_start, $y_stop )
{
    print( "<table>\n<tr><td></td>" );
    for ( $x = $x_start; $x < $x_stop; ++$x )
        print( "<td>$x</td>" );
    print( "</tr>\n" );
    for ( $y = $y_start; $y < $y_stop; ++$y )
    {
        print( "<tr><td>$y</td>" );
        for ( $x = $x_start; $x < $x_stop; ++$x )
        {
            if ( isset( $values[$x][$y] ) )
            {
                $value = $values[$x][$y];
                $char_utf8 = htmlspecialchars( $value[0] );
                $char_code = $value[1];
                print( "<td>$char_utf8</td>" );
            }
            else
                print( "<td></td>" );
        }
        print( "</tr>\n" );
    }
    print( "</table>\n" );
}

function generateCharsetTable( &$values, &$codepage,
                               $x_start, $x_stop,
                               $y_start, $y_stop )
{
    $values = array();
    for ( $y = $y_start; $y < $y_stop; ++$y )
    {
        for ( $x = $x_start; $x < $x_stop; ++$x )
        {
            $code = ($y*$y_stop) + $x;
            $utf8 = $codepage->codeToUtf8( $code );
            $values[$x][$y] = array( $utf8, $code );
        }
    }
}

header( "Content-Type: text/html; charset=utf8" );

$Result = array();
$Result["title"] = "Codepage mapping";
$Result["content"] = "";

include_once( "lib/ezi18n/classes/ezcodepagemapper.php" );

$input_charset = "cyrillic";
$output_charset = "wincyrillic";

include_once( "lib/ezutils/classes/ezhttptool.php" );

$http =& eZHTTPTool::instance();
if ( $http->hasPostVariable( "ChangeCharset" ) )
{
    if ( $http->hasPostVariable( "InputCharset" ) )
    {
        $input_charset = $http->postVariable( "InputCharset" );
    }
    if ( $http->hasPostVariable( "OutputCharset" ) )
    {
        $output_charset = $http->postVariable( "OutputCharset" );
    }
}

$input_codepage =& eZCodePage::instance( $input_charset );
$output_codepage =& eZCodePage::instance( $output_charset );
$codepage_mapper =& eZCodePageMapper::instance( $input_charset, $output_charset );

$text = "abc123[];רזו₪½";
// $text = "abc123[];רזו" . chr( 0xa4 ) . chr( 0xbc );

print( "
<form action=\"\" method=\"post\" name=\"CodepageMapping\">
<p>
Mapping from one codepage to another can be done using the eZCodePageMapper class,
it provides instantantious mapping from one character code in one charset to the
code in the other charset.
</p>
<p>
Table <b>1</b> shows the charset to map from, table <b>2</b> shows the charset to map to
and table <b>3</b> shows the characters which could be mapped.
</p>

<p class=\"footnote\">
This page uses utf8 output to allow displaying two different charsets at the same time,
you might not see all characters depending on your font support.
</p>
" );


generateCharsetTable( $input_values, $input_codepage,
                      0, 16,
                      0, 16 );
generateCharsetTable( $output_values, $output_codepage,
                      0, 16,
                      0, 16 );

$mapped_values = array();
for ( $y = 0; $y < 16; ++$y )
{
    for ( $x = 0; $x < 16; ++$x )
    {
        $input_code = ($y*16) + $x;
        $output_code = $codepage_mapper->mapInputCode( $input_code );
        $output_utf8 = $output_codepage->codeToUtf8( $output_code );
        $output_x = ( $output_code % 16 );
        $output_y = ( $output_code / 16 );
        $mapped_values[$output_x][$output_y] = array( $output_utf8, $input_code );
    }
}

$charset_list = eZCodePage::codepageList();

print( "<table width=\"100%\">
<tr><td>
<select name=\"InputCharset\">" );
foreach( $charset_list as $charset )
{
    print( "<option value=\"$charset\" " . ( $input_codepage->charsetCode() == $charset ? "selected=\"selected\"" : "" ) . ">$charset</option>" );
}
print( "</select>
</td><td>
<select name=\"OutputCharset\">" );
foreach( $charset_list as $charset )
{
    print( "<option value=\"$charset\" " . ( $output_codepage->charsetCode() == $charset ? "selected=\"selected\"" : "" ) . ">$charset</option>" );
}
print( "</select>

</td><td>
<input class=\"stdbutton\" type=\"submit\" Name=\"ChangeCharset\" value=\"Change charset\">
</td></tr>
<tr><td>" );
print( "<h2>1. " . $input_codepage->charsetCode() );
if ( $input_codepage->charsetCode() != $input_codepage->requestedCharsetCode() )
    print( "(" . $input_codepage->requestedCharsetCode() . ")" );
print( "</h2>" );
displayCharsetTable( $input_values,
                     0, 16,
                     0, 16 );
print( "</td><td>" );
print( "<h2>2. " . $output_codepage->charsetCode() );
if ( $output_codepage->charsetCode() != $output_codepage->requestedCharsetCode() )
    print( "(" . $output_codepage->requestedCharsetCode() . ")" );
print( "</h2>" );
displayCharsetTable( $output_values,
                     0, 16,
                     0, 16 );
print( "</td>" );
print( "<td colspan=\"2\">" );
print( "<h2>3. " . $input_codepage->charsetCode() . " => " . $output_codepage->charsetCode() . "</h2>" );
displayCharsetTable( $mapped_values,
                     0, 16,
                     0, 16 );
print( "</td></tr>" );
print( "</table>" );
print( "</form>" );

?>

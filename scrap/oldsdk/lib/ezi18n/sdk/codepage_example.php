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


$Result = array();
$Result["title"] = "Codepage examples";
$Result["content"] = "";

// header( "Content-Type: text/html; charset=iso-8859-1" );

include_once( "lib/ezi18n/classes/ezcodepage.php" );
include_once( "lib/ezi18n/classes/ezcodepagemapper.php" );

$codepage =& eZCodePage::instance( "latin1" );

$text = "abc123[];רזו";

$utf8str = chr( 0x89 ) . chr( 0x93 );
$conv = mb_convert_encoding( $utf8str, "sjis", "utf-8"  );

// 0xE64B	0x8993	#CJK UNIFIED IDEOGRAPH

$str = "0x" . dechex( ord( $conv[0] ) ) . dechex( ord( $conv[1] ) );

print( "0xE64B($utf8str)=$str($conv)<br/>" );

print( "Min character value: " . $codepage->minCharValue() . "<br/>" );
print( "Max character value: " . $codepage->maxCharValue() . "<br/>" );
print( "Requested charset code: " . $codepage->requestedCharsetCode() . "<br/>" );
print( "Charset code: " . $codepage->charsetCode() . "<br/>" );

print( "<table>
<tr><th>Character</th><th>Unicode</th></tr>\n" );
for ( $i = 0; $i < strlen( $text ); ++$i )
{
    $char = $text[$i];
    $utf8 = $codepage->charToUtf8( $char );
    $unicode = $codepage->charToUnicode( $char );
    print( "<tr><td>$utf8</td><td>$unicode</td></tr>\n" );
}
print( "</table>" );

?>

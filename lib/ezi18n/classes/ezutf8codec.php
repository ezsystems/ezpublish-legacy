<?php
//
// Definition of eZUTF8Codec class
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZUTF8Codec ezutf8codec.php
  \ingroup eZI18N
  \brief Converter for utf8 and 32bit unicode

  Allows for conversion from utf8 charactes and to 32bit unicode values,
  and vice versa.

*/

class eZUTF8Codec
{
    /*!
     Initializes utf8 codec.
    */
    function eZUTF8Codec()
    {
    }

    /*!
     Converts an UTF8 string into Unicode values and returns an array with the values.
    */
    function convertStringToUnicode( $str )
    {
        $unicodeValues = array();
        $strLen = strlen( $str );
        for ( $offset = 0; $offset < $strLen; )
        {
            $charLen = 1;
            $unicodeValue = eZUTF8Codec::fromUTF8( $str, $offset, $charLen );
            if ( $unicodeValue !== false )
                $unicodeValues[] = $unicodeValue;
            $offset += $charLen;
        }
        return $unicodeValues;
    }

    /*!
     Converts an array with Unicode values into an UTF8 string and returns it.
    */
    function convertUnicodeToString( $unicodeValues )
    {
        if ( !is_array( $unicodeValues ) )
            return false;
        $text = '';
        foreach ( $unicodeValues as $unicodeValue )
        {
            $utf8Char = eZUTF8Codec::toUTF8( $unicodeValue );
            $text .= $utf8Char;
        }
        return $text;
    }

    /*!
     \static
     Converts the 32 bit integer $char_code to a utf8 string representing the Unicode character.
    */
    function &toUTF8( $char_code )
    {
        switch ( $char_code )
        {
            case 0:
                $char = chr( 0 );
            case !($char_code & 0xffffff80): // 7 bit
                $char = chr( $char_code );
                break;
            case !($char_code & 0xfffff800): // 11 bit
                $char = ( chr(0xc0 | (($char_code >> 6) & 0x1f)) .
                          chr(0x80 | ($char_code & 0x3f)) );
                break;
            case !($char_code & 0xffff0000): // 16 bit
                $char = ( chr(0xe0 | (($char_code >> 12) & 0x0f)) .
                          chr(0x80 | (($char_code >> 6) & 0x3f)) .
                          chr(0x80 | ($char_code & 0x3f)) );
                break;
            case !($char_code & 0xffe00000): // 21 bit
                $char = ( chr(0xf0 | (($char_code >> 18) & 0x07)) .
                          chr(0x80 | (($char_code >> 12) & 0x3f)) .
                          chr(0x80 | (($char_code >> 6) & 0x3f)) .
                          chr(0x80 | ($char_code & 0x3f)) );
                break;
            case !($char_code & 0xfc000000): // 26 bit
                $char = ( chr(0xf8 | (($char_code >> 24) & 0x03)) .
                          chr(0x80 | (($char_code >> 18) & 0x3f)) .
                          chr(0x80 | (($char_code >> 12) & 0x3f)) .
                          chr(0x80 | (($char_code >> 6) & 0x3f)) .
                          chr(0x80 | ($char_code & 0x3f)) );
            default: // 31 bit
                $char = ( chr(0xfc | (($char_code >> 30) & 0x01)) .
                          chr(0x80 | (($char_code >> 24) & 0x3f)) .
                          chr(0x80 | (($char_code >> 18) & 0x3f)) .
                          chr(0x80 | (($char_code >> 12) & 0x3f)) .
                          chr(0x80 | (($char_code >> 6) & 0x3f)) .
                          chr(0x80 | ($char_code & 0x3f)) );
        }
        return $char;
    }

    /*!
     \static
     Converts the first utf8 char in the string $multi_char to a 32 bit integer.
     $offs is the offset in the string.
     $len will contain the length of utf8 char in the string which can be used to
     find the next char.
    */
    function &fromUtf8( $multi_char, $offs, &$len )
    {
        $char_code = false;
        if ( ( ord( $multi_char[$offs + 0] ) & 0x80 ) == 0x00 ) // 7 bit, 1 char
        {
            $char_code = ord( $multi_char[$offs + 0] );
            $len = 1;
        }
        else if ( ( ord( $multi_char[$offs + 0] ) & 0xe0 ) == 0xc0 ) // 11 bit, 2 chars
        {
            $len = 2;
            if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 )
                return $char_code;
            $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x1f ) << 6) +
                           (( ord( $multi_char[$offs + 1] ) & 0x3f )) );
            if ( $char_code < 128 ) // Illegal multibyte, should use less than 2 chars
            {
                $char_code == false;
            }
        }
        else if ( ( ord( $multi_char[$offs + 0] ) & 0xf0 ) == 0xe0 ) // 16 bit, 3 chars
        {
            $len = 3;
            if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 2] ) & 0xc0 ) != 0x80 )
                return $char_code;
            $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x0f ) << 12) +
                           (( ord( $multi_char[$offs + 1] ) & 0x3f ) << 6) +
                           (( ord( $multi_char[$offs + 2] ) & 0x3f )) );
            if ( $char_code < 2048 ) // Illegal multibyte, should use less than 3 chars
            {
                $char_code == false;
            }
        }
        else if ( ( ord( $multi_char[$offs + 0] ) & 0xf8 ) == 0xf0 ) // 21 bit, 4 chars
        {
            $len = 4;
            if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 2] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 3] ) & 0xc0 ) != 0x80 )
                return $char_code;
            $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x07 ) << 18) +
                           (( ord( $multi_char[$offs + 1] ) & 0x3f ) << 12) +
                           (( ord( $multi_char[$offs + 2] ) & 0x3f ) << 6) +
                           (( ord( $multi_char[$offs + 3] ) & 0x3f )) );
            if ( $char_code < 65536 ) // Illegal multibyte, should use less than 4 chars
            {
                $char_code == false;
            }
        }
        else if ( ( ord( $multi_char[$offs + 0] ) & 0xfc ) == 0xf8 ) // 26 bit, 5 chars
        {
            $len = 5;
            if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 2] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 3] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 4] ) & 0xc0 ) != 0x80 )
                return $char_code;
            $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x03 ) << 24) +
                           (( ord( $multi_char[$offs + 1] ) & 0x3f ) << 18) +
                           (( ord( $multi_char[$offs + 2] ) & 0x3f ) << 12) +
                           (( ord( $multi_char[$offs + 3] ) & 0x3f ) << 6) +
                           (( ord( $multi_char[$offs + 4] ) & 0x3f )) );
            if ( $char_code < 2097152 ) // Illegal multibyte, should use less than 5 chars
            {
                $char_code == false;
            }
        }
        else if ( ( ord( $multi_char[$offs + 0] ) & 0xfe ) == 0xfc ) // 31 bit, 6 chars
        {
            $len = 6;
            if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 2] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 3] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 4] ) & 0xc0 ) != 0x80 or
                 ( ord( $multi_char[$offs + 5] ) & 0xc0 ) != 0x80 )
                return $char_code;
            $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x01 ) << 30) +
                           (( ord( $multi_char[$offs + 1] ) & 0x3f ) << 24) +
                           (( ord( $multi_char[$offs + 2] ) & 0x3f ) << 18) +
                           (( ord( $multi_char[$offs + 3] ) & 0x3f ) << 12) +
                           (( ord( $multi_char[$offs + 4] ) & 0x3f ) << 6) +
                           (( ord( $multi_char[$offs + 5] ) & 0x3f )) );
            if ( $char_code < 67108864 ) // Illegal multibyte, should use less than 6 chars
            {
                $char_code == false;
            }
        }
        return $char_code;
    }

    function &utf8LengthTable()
    {
        $table =& $GLOBALS["eZUTF8LengthTable"];
        if ( !is_array( $table ) )
            $table = array( 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                            1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                            0, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 5, 6 );
        return $table;
    }

    function characterByteLength( &$str, $pos )
    {
        $table =& eZUTF8Codec::utf8LengthTable();
        $char = ord( $str[$pos] );
        return $table[($char >> 2) & 0x3f];
    }

    function strlen( &$str )
    {
        $table =& eZUTF8Codec::utf8LengthTable();
        $len = strlen( $str );
        $strlen = 0;
        for ( $i = 0; $i < $len; )
        {
            $char = ord( $str[$i] );
            $char_len = $table[($char >> 2) & 0x3f];
            $i += $char_len;
            ++$strlen;
        }
        return $strlen;
    }

    /*!
     \return a unique instance of the UTF8 codec.
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZUTF8CodecInstance"];
        if ( get_class( $instance ) != "ezutf8codec" )
        {
            $instance = new eZUTF8Codec();
        }
        return $instance;
    }
}

?>

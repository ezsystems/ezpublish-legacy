<?php
//
// Definition of eZUTF8Codec class
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
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
    static function convertStringToUnicode( $str )
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
    static function convertUnicodeToString( $unicodeValues )
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
    static function toUTF8( $char_code )
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
    static function fromUtf8( $multi_char, $offs, &$len )
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

    static function utf8LengthTable()
    {
        if ( empty( $GLOBALS['eZUTF8LengthTable'] ) )
        {
            $GLOBALS['eZUTF8LengthTable'] =
                array( 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                       1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                       0, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 5, 6 );
        }
        return $GLOBALS['eZUTF8LengthTable'];
    }

    static function characterByteLength( $str, $pos )
    {
        $table = eZUTF8Codec::utf8LengthTable();
        $char = ord( $str[$pos] );
        return $table[($char >> 2) & 0x3f];
    }

    static function strlen( $str )
    {
        $table = eZUTF8Codec::utf8LengthTable();
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

    /**
     * Returns a shared instance of the eZUTF8Codec class.
     *
     * @return eZUTF8Codec
     */
    static function instance()
    {
        if ( empty( $GLOBALS['eZUTF8CodecInstance'] ) )
        {
            $GLOBALS['eZUTF8CodecInstance'] = new eZUTF8Codec();
        }
        return $GLOBALS['eZUTF8CodecInstance'];
    }
}

?>

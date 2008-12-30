<?php
//
// Definition of eZMBStringMapper class
//
// Created on: <12-Jul-2002 12:56:48 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZMBStringMapper ezmbstringmapper.php
  \ingroup eZI18N
  \brief The class eZMBStringMapper does

  The mbstring extension supports the following charset:
  UCS-4, UCS-4BE, UCS-4LE, UCS-2, UCS-2BE, UCS-2LE, UTF-32, UTF-32BE, UTF-32LE, UCS-2LE, UTF-16,
  UTF-16BE, UTF-16LE, UTF-8, UTF-7, ASCII, EUC-JP, SJIS, eucJP-win, SJIS-win, ISO-2022-JP, JIS,
  ISO-8859-1, ISO-8859-2, ISO-8859-3, ISO-8859-4, ISO-8859-5, ISO-8859-6, ISO-8859-7, ISO-8859-8,
  ISO-8859-9, ISO-8859-10, ISO-8859-13, ISO-8859-14, ISO-8859-15, byte2be, byte2le, byte4be,
  byte4le, BASE64, 7bit, 8bit and UTF7-IMAP.
*/

class eZMBStringMapper
{
    /*!
     Constructor
    */
    function eZMBStringMapper( $input_charset_code, $output_charset_code )
    {
        $this->RequestedInputCharsetCode = $input_charset_code;
        $this->InputCharsetCode = eZCharsetInfo::realCharsetCode( $input_charset_code );
        $this->RequestedOutputCharsetCode = $output_charset_code;
        $this->OutputCharsetCode = eZCharsetInfo::realCharsetCode( $output_charset_code );
        $this->Valid = false;
        if ( !$this->isCharsetSupported( $input_charset_code ) )
        {
            eZDebug::writeError( "Input charset $input_charset_code not supported", "eZMBStringMapper" );
        }
        else if ( !$this->isCharsetSupported( $output_charset_code ) )
        {
            eZDebug::writeError( "Output charset $output_charset_code not supported", "eZMBStringMapper" );
        }
        else if ( $this->hasMBStringExtension() )
            $this->Valid = true;
        else
            eZDebug::writeError( "No mbstring functions available", "eZMBStringMapper" );
    }

    /*!
     \static
     \note This function is duplicated in eZTextCodec::eZTextCodec(), remember to update both places.
    */
    static function &charsetList()
    {
        $charsets =& $GLOBALS["eZMBCharsetList"];
        if ( !is_array( $charsets ) )
        {
            $charsetList = array( "ucs-4", "ucs-4be", "ucs-4le", "ucs-2", "ucs-2be", "ucs-2le", "utf-32", "utf-32be", "utf-32le", "utf-16",
                                  "utf-16be", "utf-16le", "utf-8", "utf-7", "ascii", "euc-jp", "sjis", "eucjp-win", "sjis-win", "iso-2022-jp", "jis",
                                  "iso-8859-1", "iso-8859-2", "iso-8859-3", "iso-8859-4", "iso-8859-5", "iso-8859-6", "iso-8859-7", "iso-8859-8",
                                  "iso-8859-9", "iso-8859-10", "iso-8859-13", "iso-8859-14", "iso-8859-15", "byte2be", "byte2le", "byte4be",
                                  "byte4le", "base64", "7bit", "8bit", "utf7-imap" );
            $charsets = array();
            foreach ( $charsetList as $charset )
            {
                $charsets[$charset] = $charset;
            }
        }
        return $charsets;
    }

    /*!
     \static
     \return \c true if the mbstring can be used.
     \note The following function must be present for the function to return \c true.
           mb_convert_encoding
           mb_substitute_character
           mb_strcut
           mb_strlen
           mb_strpos
           mb_strrpos
           mb_strwidth
           mb_substr
     \note This function is duplicated in eZTextCodec::eZTextCodec(), remember to update both places.
    */
    static function hasMBStringExtension()
    {
        return ( function_exists( "mb_convert_encoding" ) and
                 function_exists( "mb_substitute_character" ) and
                 function_exists( "mb_strcut" ) and
                 function_exists( "mb_strlen" ) and
                 function_exists( "mb_strpos" ) and
                 function_exists( "mb_strrpos" ) and
                 function_exists( "mb_strwidth" ) and
                 function_exists( "mb_substr" ) );
    }

    function inputCharsetCode()
    {
        return $this->InputCharsetCode;
    }

    function outputCharsetCode()
    {
        return $this->OutputCharsetCode;
    }

    function requestedInputCharsetCode()
    {
        return $this->RequestedInputCharsetCode;
    }

    function requestedOutputCharsetCode()
    {
        return $this->RequestedOutputCharsetCode;
    }

    function isCharsetSupported( $charset_code )
    {
        $charset_code = eZCharsetInfo::realCharsetCode( $charset_code );
        return in_array( $charset_code, eZMBStringMapper::charsetList() );
    }

    function substituteCharacter()
    {
        if ( !$this->Valid )
            return null;
        return mb_substitute_character();
    }

    function setSubstituteCharacter( $char )
    {
        if ( $this->Valid )
            mb_substitute_character( $char );
    }

    function convertString( $str )
    {
        if ( !$this->Valid )
            return $str;
        return mb_convert_encoding( $str, $this->OutputCharsetCode, $this->InputCharsetCode );
    }

    function strlen( $str )
    {
        return mb_strlen( $str, $this->InputCharsetCode );
    }

    function strpos( $haystack, $needle, $offset = 0 )
    {
        return mb_strpos( $haystack, $needle, $offset, $this->InputCharsetCode );
    }

    function strrpos( $haystack, $needle )
    {
        return mb_strrpos( $haystack, $needle, $this->InputCharsetCode );
    }

    function substr( $str, $start, $length )
    {
        return mb_substr( $str, $start, $length, $this->InputCharsetCode );
    }

    static function instance( $input_charset_code, $output_charset_code )
    {
        $globalsKey = "eZMBStringMapper-$input_charset_code-$output_charset_code";

        if ( !isset( $GLOBALS[$globalsKey] ) ||
             !( $GLOBALS[$globalsKey] instanceof eZMBStringMapper ) )
        {
            $GLOBALS[$globalsKey] = new eZMBStringMapper( $input_charset_code, $output_charset_code );
        }

        return $GLOBALS[$globalsKey];
    }
}

?>

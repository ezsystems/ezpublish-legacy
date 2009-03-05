<?php
//
// Definition of eZCodePageMapper class
//
// Created on: <11-Jul-2002 15:39:41 amos>
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

/*! \file
*/

/*!
  \class eZCodePageMapper ezcodepagemapper.php
  \brief The class eZCodePageMapper does

*/

class eZCodePageMapper
{
    const CACHE_CODE_DATE = 1026316422;

    /*!
     Constructor
    */
    function eZCodePageMapper( $input_charset_code, $output_charset_code, $use_cache = true )
    {
        $this->RequestedInputCharsetCode = $input_charset_code;
        $this->InputCharsetCode = eZCharsetInfo::realCharsetCode( $input_charset_code );
        $this->RequestedOutputCharsetCode = $output_charset_code;
        $this->OutputCharsetCode = eZCharsetInfo::realCharsetCode( $output_charset_code );
        $this->Valid = false;
        $this->load( $use_cache );
        $this->setSubstituteCharacter( 63 ); // ?
    }

    function isValid()
    {
        return $this->Valid;
    }

    function &mapInputCode( $in_code )
    {
        if ( isset( $this->InputOutputMap[$in_code] ) )
            return $this->InputOutputMap[$in_code];
        $retValue = null;
        return $retValue;
    }

    function &mapOutputCode( $out_code )
    {
        if ( isset( $this->OutputInputMap[$out_code] ) )
            return $this->OutputInputMap[$out_code];
        $retValue = null;
        return $retValue;
    }

    function mapInputChar( $in_char )
    {
        $in_code = ord( $in_char );
        if ( isset( $this->InputOutputMap[$in_code] ) )
            return chr( $this->InputOutputMap[$in_code] );
        return $this->SubstituteOutputChar;
    }

    function mapOutputChar( $out_char )
    {
        $out_code = ord( $out_char );
        if ( isset( $this->OutputInputMap[$out_code] ) )
            return chr( $this->OutputInputMap[$out_code] );
        return $this->SubstituteInputChar;
    }

    function substituteCharacterFor( $char )
    {
    }

    function substituteCharacter()
    {
        return $this->SubstituteCharValue;
    }

    function convertString( $str )
    {
        $out = "";
        $len = strlen( $str );
        for ( $i = 0; $i < $len; ++$i )
        {
            $char = $str[$i];
            $out .= $this->mapInputChar( $char );
        }
        return $out;
    }

    function strlen( $str )
    {
        return strlen( $str );
    }

    function strpos( $haystack, $needle, $offset = 0 )
    {
        return strpos( $haystack, $needle, $offset );
    }

    function strrpos( $haystack, $needle )
    {
        return strrpos( $haystack, $needle );
    }

    function substr( $str, $start, $length )
    {
        return substr( $str, $start, $length );
    }

    function setSubstituteCharacter( $char_code )
    {
        $this->SubstituteCharValue = $char_code;
        $input_codepage = eZCodePage::instance( $this->InputCharsetCode );
        $output_codepage = eZCodePage::instance( $this->OutputCharsetCode );
        if ( !$input_codepage->isValid() )
        {
            eZDebug::writeError( "Input codepage for " . $this->InputCharsetCode . " is not valid", "eZCodePageMapper" );
            return false;
        }
        if ( !$output_codepage->isValid() )
        {
            eZDebug::writeError( "Output codepage for " . $this->OutputCharsetCode . " is not valid", "eZCodePageMapper" );
            return false;
        }
        $this->SubstituteInputChar = chr( $input_codepage->unicodeToCode( $char_code ) );
        $this->SubstituteOutputChar = chr( $output_codepage->unicodeToCode( $char_code ) );
    }

    function load( $use_cache = true )
    {
        // temporarely hide the cache display problem
        // http://ez.no/community/bugs/char_transform_cache_file_is_not_valid_php
        //$use_cache = false;
        $cache_dir = "var/cache/codepages/";
        $cache_filename = md5( $this->InputCharsetCode . $this->OutputCharsetCode );
        $cache = $cache_dir . $cache_filename . ".php";

        if ( !eZCodePage::exists( $this->InputCharsetCode ) )
        {
            $input_file = eZCodePage::fileName( $this->InputCharsetCode );
            eZDebug::writeWarning( "Couldn't load input codepage file $input_file", "eZCodePageMapper" );
            return false;
        }
        if ( !eZCodePage::exists( $this->OutputCharsetCode ) )
        {
            $output_file = eZCodePage::fileName( $this->OutputCharsetCode );
            eZDebug::writeWarning( "Couldn't load output codepage file $output_file", "eZCodePageMapper" );
            return false;
        }

        $this->Valid = false;
        if ( file_exists( $cache ) and $use_cache )
        {
            $cache_m = filemtime( $cache );
            if ( eZCodePage::fileModification( $this->InputCharsetCode ) <= $cache_m and
                 eZCodePage::fileModification( $this->OutputCharsetCode ) <= $cache_m )
            {
                unset( $eZCodePageMapperCacheCodeDate );
                $in_out_map =& $this->InputOutputMap;
                $out_in_map =& $this->OutputInputMap;
                eZDebug::writeDebug( 'loading cache from: ' . $cache, 'eZCodePageMapper::load' );
                include( $cache );
                if ( isset( $eZCodePageMapperCacheCodeDate ) or
                     $eZCodePageMapperCacheCodeDate == self::CACHE_CODE_DATE )
                {
                    $this->Valid = true;
                    return;
                }
            }
        }

        $this->InputOutputMap = array();
        $this->OutputInputMap = array();

        $input_codepage = eZCodePage::instance( $this->InputCharsetCode );
        $output_codepage = eZCodePage::instance( $this->OutputCharsetCode );

        if ( !$input_codepage->isValid() )
        {
            eZDebug::writeError( "Input codepage for " . $this->InputCharsetCode . " is not valid", "eZCodePageMapper" );
            return false;
        }
        if ( !$output_codepage->isValid() )
        {
            eZDebug::writeError( "Output codepage for " . $this->OutputCharsetCode . " is not valid", "eZCodePageMapper" );
            return false;
        }

        $min = max( $input_codepage->minCharValue(),
                    $output_codepage->minCharValue() );
        $max = min( $input_codepage->maxCharValue(),
                    $output_codepage->maxCharValue() );

        for ( $i = $min; $i <= $max; ++$i )
        {
            $code = $i;
            $unicode = $input_codepage->codeToUnicode( $code );
            if ( $unicode !== null )
            {
                $output_code = $output_codepage->unicodeToCode( $unicode );
                if ( $output_code !== null )
                {
                    $this->InputOutputMap[$code] = $output_code;
                    $this->OutputInputMap[$output_code] = $code;
                }
            }
        }
    }

    /*!
     Returns the only instance of the codepage mapper for $input_charset_code and $output_charset_code.
    */
    static function instance( $input_charset_code, $output_charset_code, $use_cache = true )
    {
        $globalsKey = "eZCodePageMapper-$input_charset_code-$output_charset_code";

        if ( !isset( $GLOBALS[$globalsKey] ) ||
             !( $GLOBALS[$globalsKey] instanceof eZCodePageMapper ) )
        {
            $GLOBALS[$globalsKey] = new eZCodePageMapper( $input_charset_code, $output_charset_code, $use_cache );
        }

        return $GLOBALS[$globalsKey];
    }

}

?>

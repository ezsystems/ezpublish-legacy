<?php
//
// Definition of eZCodeMapper class
//
// Created on: <18-Jun-2004 14:56:15 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezcodemapper.php
*/

/*!
  \class eZCodeMapper ezcodemapper.php
  \brief Handles mapping of character codes

*/

define( "EZ_CODEMAPPER_TYPE_DIRECT", 1 );
define( "EZ_CODEMAPPER_TYPE_RANGE", 2 );
define( "EZ_CODEMAPPER_TYPE_REPLACE", 3 );

class eZCodeMapper
{
    /*!
     Constructor
    */
    function eZCodeMapper()
    {
    }

    function mappingTable( $identifier )
    {
        // Note: This is currently hardcoded but will be moved to configurable
        //       text files, the syntax will be something like:
        // U+0402="D%"
        // U+0041-U+005A+=32
        // U+00BC-U+00BE=U+0020

        if ( $identifier == 'search' )
        {
//             $list = array( 'space', 'hyphen', 'quote', 'latin_lowercase', 'diacritical' );
            $list = array( 'diacritical', 'latin_uppercase' );
            return $list;
        }
        else if ( $identifier == 'space' )
        {
            // Space control, turns special spaces into ASCII space (32)
            $spaceMap = array( array( EZ_CODEMAPPER_TYPE_DIRECT,
                                      array( 160 => 32 // non-break space
                                             ),
                                      'space' ) );
            return $spaceMap;
        }
        else if ( $identifier == 'hyphen' )
        {
            // Dash and hyphen control, turns them into hyphen-minus (45) or removed
            $hyphenMap = array( array( EZ_CODEMAPPER_TYPE_DIRECT,
                                       array( 126 => 45, // tilde or swung dash
                                              173 => false // soft hyphen, remove it to get proper words
                                              ),
                                       'hyphen' ) );
            return $hyphenMap;
        }
        else if ( $identifier == 'apostrophe_normalize' )
        {
            // Fancy quotes, turns them into apostrophe (39) (often used as single quote)
            $quoteMap = array( array( EZ_CODEMAPPER_TYPE_DIRECT,
                                      array( 96 => 39 // grave accent
                                             ,180 => 39 // acute accent
                                             ,714 => 39 // modified letter accute accent
                                             ,715 => 39 // modified letter grave accent
                                             ,719 => 39 // modified letter low accute accent
                                             ,718 => 39 // modified letter low grave accent
                                             ),
                                      'apostrophe_normalize' ) );
            return $quoteMap;
        }
        else if ( $identifier == 'doublequote_normalize' )
        {
            // Fancy quotes, turns them into double quote (34)
            $quoteMap = array( array( EZ_CODEMAPPER_TYPE_DIRECT,
                                      array( 171 => 34 // left-pointing double angle quotation mark
                                             ,187 => 34 // right-pointing double angle quotation mark
                                             ,733 => 39 // double accute accent
                                             ),
                                      'doublequote_normalize' ) );
            return $quoteMap;
        }
        else if ( $identifier == 'doublequote_to_apostrophe' )
        {
            // Turn apostrophe into double quote
            $quoteMap = array( array( EZ_CODEMAPPER_TYPE_DIRECT,
                                      array( 39 => 34 // apostrophe to double quote
                                             ),
                                      'doublequote_to_apostrophe' ) );
            return $quoteMap;
        }
        else if ( $identifier == 'questionmark' )
        {
            $questionMap = array( array( EZ_CODEMAPPER_TYPE_DIRECT,
                                         array( 191 => 63 // inverted question mark
                                                ),
                                         'questionmark' ) );
            return $questionMap;
        }
        else if ( $identifier == 'special' )
        {
            // Turns several special characters into spaces
            $specialMap = array( array( EZ_CODEMAPPER_TYPE_REPLACE,
                                        array( array( 188, 190, 32 ), // special characters
                                               array( 161, 169, 32 ), // special characters
                                               array( 172, 177, 32 ), // special characters
                                               array( 181, 184, 32 ) // special characters
                                               ),
                                        'special_range' ),
                                 array( EZ_CODEMAPPER_TYPE_DIRECT,
                                        array( 170 => false, // feminine ordinal indicator
                                               186 => false, // masculine ordinal indicator
                                               185 => false, // superscript one
                                               178 => false, // superscript two
                                               179 => false // superscript three
                                               ),
                                        'special_direct' ) );
            return $specialMap;
        }
        else if ( $identifier == 'ascii_lowercase' )
        {
            // Basic latin lowercase
            $latinRangeMap = array( array( EZ_CODEMAPPER_TYPE_RANGE,
                                           array( array( 65, 90, 32 ) ),
                                           'latin1_lowercase' ) );
            return $latinRangeMap;
        }
        else if ( $identifier == 'ascii_uppercase' )
        {
            // Basic latin lowercase
            $latinRangeMap = array( array( EZ_CODEMAPPER_TYPE_RANGE,
                                           array( array( 97, 122, -32 ) ),
                                           'latin1_lowercase' ) );
            return $latinRangeMap;
        }
        else if ( $identifier == 'cyrillic_transliterate' )
        {
            // Transliteration of cyrrilic characters into reduced ASCII equivelants
            $latinMap = array( array( EZ_CODEMAPPER_TYPE_DIRECT,
                                      array(  0x402 => 'D%'
                                             ,0x403 => 'G%'
                                             ,0x404 => 'IE'
                                             ,0x405 => 'DS'
                                             ,0x406 => 'II'
                                             ,0x407 => 'YI'
                                             ,0x408 => 'J%'
                                             ,0x409 => 'LJ'
                                             ,0x40a => 'NJ'
                                             ,0x40b => 'Ts'
                                             ,0x40c => 'KJ'
                                             ,0x40e => 'V%'
                                             ,0x40f => 'DZ'

                                             ,0x401 => 'IO'

                                             ,0x410 => 'A'
                                             ,0x411 => 'B'
                                             ,0x412 => 'V'
                                             ,0x413 => 'G'
                                             ,0x414 => 'D'
                                             ,0x415 => 'E'
                                             ,0x416 => 'ZH'
                                             ,0x417 => 'Z'
                                             ,0x418 => 'I'
                                             ,0x419 => 'J'
                                             ,0x41a => 'K'
                                             ,0x41b => 'L'
                                             ,0x41c => 'M'
                                             ,0x41d => 'N'
                                             ,0x41e => 'O'
                                             ,0x41f => 'P'
                                             ,0x420 => 'R'
                                             ,0x421 => 'S'
                                             ,0x422 => 'T'
                                             ,0x423 => 'U'
                                             ,0x424 => 'F'
                                             ,0x425 => 'H'
                                             ,0x426 => 'C'
                                             ,0x427 => 'CH'
                                             ,0x428 => 'SH'
                                             ,0x429 => 'SCH'
                                             ,0x42a => '"'
                                             ,0x42b => 'Y'
                                             ,0x42c => "'"
                                             ,0x42d => "`E"
                                             ,0x42e => "YU"
                                             ,0x42f => "YA"

                                             ,0x430 => 'a'
                                             ,0x431 => 'b'
                                             ,0x432 => 'v'
                                             ,0x433 => 'g'
                                             ,0x434 => 'd'
                                             ,0x435 => 'e'
                                             ,0x436 => 'zh'
                                             ,0x437 => 'z'
                                             ,0x438 => 'i'
                                             ,0x439 => 'j'
                                             ,0x43a => 'k'
                                             ,0x43b => 'l'
                                             ,0x43c => 'm'
                                             ,0x43d => 'n'
                                             ,0x43e => 'o'
                                             ,0x43f => 'p'
                                             ,0x440 => 'r'
                                             ,0x441 => 's'
                                             ,0x442 => 't'
                                             ,0x443 => 'u'
                                             ,0x444 => 'f'
                                             ,0x445 => 'h'
                                             ,0x446 => 'c'
                                             ,0x447 => 'ch'
                                             ,0x448 => 'sh'
                                             ,0x449 => 'sch'
                                             ,0x44a => '"'
                                             ,0x44b => 'y'
                                             ,0x44c => "'"
                                             ,0x44d => "`e"
                                             ,0x44e => "yu"
                                             ,0x44f => "ya"

                                             ,0x451 => "io"

                                             ),
                                      'cyrillic_transliterate' ) );
            return $latinMap;
        }
        else if ( $identifier == 'latin1_transliterate' )
        {
            // Transliteration of latin characters into reduced ASCII equivelants
            $latinMap = array( array( EZ_CODEMAPPER_TYPE_DIRECT,
                                      array( 230 => "ae", // æ => ae
                                             198 => "AE", // Æ => AE
                                             229 => "aa", // å => aa
                                             197 => "AA", // Å => AA
                                             248 => "oe", // ø => oe
                                             216 => "OE", // Ø => OE
                                             156 => "oe", // oe ligature
                                             140 => "OE" // OE ligature
                                             ),
                                      'latin1_transliterate' ) );
            return $latinMap;
        }
        else if ( $identifier == 'diacritical' )
        {
            // Diacriticals
            $diacriticMap = array( array( EZ_CODEMAPPER_TYPE_REPLACE,
                                          array( array( 192, 196, 65 ), // A
                                                 array( 224, 228, 97 ), // a
                                                 array( 200, 203, 69 ), // E
                                                 array( 232, 235, 101 ), // e
                                                 array( 204, 207, 73 ), // I
                                                 array( 236, 239, 105 ), // i
                                                 array( 210, 214, 79 ), // o
                                                 array( 242, 246, 111 ), // o
                                                 array( 217, 220, 85 ), // u
                                                 array( 249, 252, 117 ) // u
                                                 ),
                                          'diacritical_range' ),
                                   array( EZ_CODEMAPPER_TYPE_DIRECT,
                                          array( 221 => 89, // Y
                                                 159 => 89, // Y
                                                 253 => 121, // y
                                                 255 => 121, // y
                                                 199 => 67, // C
                                                 231 => 99, // c
                                                 209 => 78, // N
                                                 241 => 110 // n
                                                 ),
                                          'diacritical_direct' ) );
            return $diacriticMap;
        }
    }

    function expandInheritance( $table )
    {
        $newTable = array();
        foreach ( $table as $tableItem )
        {
            if ( is_string( $tableItem ) )
            {
                $identifier = $tableItem;
                $subTable = $this->mappingTable( $identifier );
                if ( !$subTable )
                {
                    eZDebug::writeError( "Failed to fetch mapping table for identifier: '$identifier'" );
                }
                else
                {
                    $subTable = $this->expandInheritance( $subTable );
                    $newTable = array_merge( $newTable, $subTable );
                }
            }
            else
            {
                $newTable[] = $tableItem;
            }
        }
        return $newTable;
    }

    /*!
     Turns the character list $list into an array with ordinal values
     \param $list Can be on of these types:
                  - String - each character is turned into an ordinal value
                  - Numeric - the numeric is used as ordinal value
                  - Boolean - means no character
                  - Array - each element is turned into an ordinal value by recursion
    */
    function ordinalValues( $table, $list )
    {
        $ordinals = array();
        if ( is_string( $list ) )
        {
            $len = strlen( $list );
            for ( $offset = 0; $offset < $len; ++$offset )
            {
                $ordinals[] = ord( $list[$offset] );
            }
        }
        else if ( is_numeric( $list ) )
        {
            $ordinals[] = $list;
        }
        else if ( is_array( $list ) )
        {
            foreach ( $list as $item )
            {
                $ordinals = array_merge( eZCodeMapper::ordinalValues( $table, $item ) );
            }
        }
        $ordinals = eZCodeMapper::mapOrdinals( $table, $ordinals );
        return $ordinals;
    }

    /*!
     Goes trough each ordinal in \a $ordinals and sees if there is mapping for it.
     If it is the mapping is applied and used as the new ordinal, if the mapping refers to
     an array it will be mapped recursively.
    */
    function mapOrdinals( $table, $ordinals )
    {
        $mappedOrdinals = array();
        foreach ( $ordinals as $ordinal )
        {
            while ( !is_array( $ordinal ) and isset( $table[$ordinal] ) )
            {
                $ordinal = $table[$ordinal];
                if ( is_array( $ordinal ) )
                {
                    $ordinal = eZCodeMapper::mapOrdinals( $table, $ordinal );
                }
            }
            if ( is_array( $ordinal ) )
                $mappedOrdinals = array_merge( $mappedOrdinals, $ordinal );
            else
                $mappedOrdinals[] = $ordinal;
        }
        return $mappedOrdinals;
    }

    /*!
     Goes trough all to codes in the mapping table \a $unicodeMap and maps
     those that match \a $fromCode into \a $toCode.
    */
    function mapExistingCodes( &$unicodeMap, $fromCode, $toCode )
    {
        foreach ( $unicodeMap as $from => $to )
        {
            if ( is_array( $to ) )
            {
                $newTo = array();
                foreach ( $to as $ordinal )
                {
                    if ( $ordinal == $fromCode )
                    {
                        $newTo = array_merge( $newTo, $toCode );
                    }
                    else
                    {
                        $newTo[] = $ordinal;
                    }
                }
                $unicodeMap[$from]=  $newTo;
            }
            else if ( $to == $fromCode )
            {
                $unicodeMap[$from]=  $toCode;
            }
        }
    }

    /*!
     Goes trough the mapping rules in the table \a $table and generates a simple
     mapping table which maps from one Unicode value to another (or array of values).

     The generation uses backward and forward propagation of the defined mappings
     to get the proper end result of a given value.

     \note This method can take a while if lots of rules are used
    */
    function generateSimpleMappingTable( $table, $allowedRanges )
    {
        if ( !is_array( $table ) )
            return false;
        $unicodeMap = array();
        foreach ( $table as $tableItem )
        {
            $type = $tableItem[0];
            $item = $tableItem[1];
            if ( isset( $tableItem[2] ) )
            {
                $identifier = $tableItem[2];
//                print( "identifier: $identifier\n" );
            }
            if ( $type == EZ_CODEMAPPER_TYPE_DIRECT )
            {
                foreach ( $item as $fromCode => $toCode )
                {
//                    print( "from: $fromCode, to: $toCode\n" );
                    $toCode = eZCodeMapper::ordinalValues( $unicodeMap, $toCode );
                    if ( count( $allowedRanges ) == 0 )
                    {
                        if ( count( $toCode ) == 1 )
                            $toCode = $toCode[0];
                        $unicodeMap[$fromCode] = $toCode;
                    }
                    else
                    {
                        $allowed = false;
                        foreach ( $allowedRanges as $allowedRange )
                        {
                            if ( $fromCode >= $allowedRange[0] and
                                 $fromCode <= $allowedRange[1] )
                            {
                                $allowed = true;
                                break;
                            }
                        }
                        if ( !$allowed )
                            continue;

                        $toCodeList = $toCode;
                        $newToCodeList = array();
                        foreach ( $toCodeList as $toCode )
                        {
                            if ( is_bool( $toCode ) )
                            {
                                $newToCodeList[] = $toCode;
                                continue;
                            }
                            foreach ( $allowedRanges as $allowedRange )
                            {
                                if ( $toCode >= $allowedRange[0] and
                                     $toCode <= $allowedRange[1] )
                                {
                                    break;
                                }
                            }
                            if ( $allowed )
                            {
                                $newToCodeList[] = $toCode;
                            }
                        }
                        $toCode = $newToCodeList;
                        if ( count( $toCode ) > 0 )
                        {
                            if ( count( $toCode ) == 1 )
                                $toCode = $toCode[0];

                            eZCodeMapper::mapExistingCodes( $unicodeMap, $fromCode, $toCode );

                            $unicodeMap[$fromCode] = $toCode;
                        }
                    }
                }
            }
            else if ( $type == EZ_CODEMAPPER_TYPE_RANGE )
            {
                foreach ( $item as $rangeItem )
                {
                    $start = $rangeItem[0];
                    $stop = $rangeItem[1];
                    if ( $start > $stop )
                    {
                        $tmp = $stop;
                        $stop = $start;
                        $start = $tmp;
                    }
                    $add = $rangeItem[2];
                    for ( $i = $start; $i <= $stop; ++$i )
                    {
                        if ( count( $allowedRanges ) >= 0 )
                        {
                            $allowed = false;
                            foreach ( $allowedRanges as $allowedRange )
                            {
                                if ( $i >= $allowedRange[0] and
                                     $i <= $allowedRange[1] )
                                {
                                    $allowed = true;
                                    break;
                                }
                            }
                            if ( !$allowed )
                                continue;
                        }

                        $replace = $i + $add;
                        $replace = eZCodeMapper::ordinalValues( $unicodeMap, $replace );
                        if ( count( $allowedRanges ) == 0 )
                        {
                            if ( count( $replace ) == 1 )
                                $replace = $replace[0];
                            eZCodeMapper::mapExistingCodes( $unicodeMap, $i, $replace );
                            $unicodeMap[$i] = $replace;
                        }
                        else
                        {
                            $newReplace = array();
                            foreach ( $allowedRanges as $allowedRange )
                            {
                                foreach ( $replace as $replaceOrdinal )
                                {
                                    if ( $replaceOrdinal >= $allowedRange[0] and
                                         $replaceOrdinal <= $allowedRange[1] )
                                    {
                                        $newReplace[] = $replaceOrdinal;
                                    }
                                }
                            }
                            if ( count( $newReplace ) == 0 )
                                $replace = false;
                            else if ( count( $newReplace ) == 1 )
                                $replace = $newReplace[0];
                            else
                                $replace = $newReplace;

                            eZCodeMapper::mapExistingCodes( $unicodeMap, $i, $replace );
                            $unicodeMap[$i] = $replace;
                        }
                    }
                }
            }
            else if ( $type == EZ_CODEMAPPER_TYPE_REPLACE )
            {
                foreach ( $item as $rangeItem )
                {
                    $start = $rangeItem[0];
                    $stop = $rangeItem[1];
                    if ( $start > $stop )
                    {
                        $tmp = $stop;
                        $stop = $start;
                        $start = $tmp;
                    }
                    $replace = $rangeItem[2];
                    $replace = eZCodeMapper::ordinalValues( $unicodeMap, $replace );
                    if ( count( $allowedRanges ) == 0 )
                    {
                        if ( count( $replace ) == 1 )
                            $replace = $replace[0];
                        for ( $i = $start; $i <= $stop; ++$i )
                        {
                            eZCodeMapper::mapExistingCodes( $unicodeMap, $i, $replace );
                            $unicodeMap[$i] = $replace;
                        }
                    }
                    else
                    {
                        $newReplace = array();
                        foreach ( $allowedRanges as $allowedRange )
                        {
                            foreach ( $replace as $replaceOrdinal )
                            {
                                if ( $replaceOrdinal >= $allowedRange[0] and
                                     $replaceOrdinal <= $allowedRange[1] )
                                {
                                    $newReplace[] = $replaceOrdinal;
                                }
                            }
                        }
                        if ( count( $newReplace ) == 0 )
                            $replace = false;
                        else if ( count( $newReplace ) == 1 )
                            $replace = $newReplace[0];
                        else
                            $replace = $newReplace;
                        for ( $i = $start; $i <= $stop; ++$i )
                        {
                            $allowed = false;
                            foreach ( $allowedRanges as $allowedRange )
                            {
                                if ( $i >= $allowedRange[0] and
                                     $i <= $allowedRange[1] )
                                {
                                    $allowed = true;
                                    break;
                                }
                            }
                            if ( $allowed )
                            {
                                eZCodeMapper::mapExistingCodes( $unicodeMap, $i, $replace );
                                $unicodeMap[$i] = $replace;
                            }
                        }
                    }
                }
            }
        }
        return $unicodeMap;
    }

    function generateMappingCode( $identifier )
    {
        if ( is_array( $identifier ) )
        {
            $table = $identifier;
        }
        else
        {
            $table = $this->mappingTable( $identifier );
        }
        $table = $this->expandInheritance( $table );
        // Currently hard coded range, should be defined by the current charset
        $allowedRanges = array( array( 0, 2000 ) );
        $simpleTable = $this->generateSimpleMappingTable( $table, $allowedRanges );
        ksort( $simpleTable );
        return $simpleTable;
    }

    /*!
     Generates a mapping table for the character set $charset.
     This will mapping table will only work for that character set but will be much faster
     and be fed directly to the strtr() PHP function.
     \return the table or \c false if something failed.
    */
    function generateCharsetMappingTable( $unicodeTable, $charset )
    {
        include_once( 'lib/ezi18n/classes/eztextcodec.php' );

        $codec =& eZTextCodec::instance( 'unicode', $charset );
        if ( !$codec )
        {
            eZDebug::writeError( "Failed to create textcodec for charset '$charset'" );
            return false;
        }

        $charsetTable = array();
        foreach ( $unicodeTable as $match => $replacement )
        {
            $matchLocal = $codec->convertString( array( $match ) );
            if ( is_array( $replacement ) )
            {
                $replacementLocal = $codec->convertString( $replacement );
            }
            else
            {
                $replacementLocal = $codec->convertString( array( $replacement ) );
            }
            $charsetTable[$matchLocal] = $replacementLocal;
        }
        return $charsetTable;
    }

}

?>

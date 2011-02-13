<?php
//
// Definition of eZCharsetInfo class
//
// Created on: <10-Jul-2002 16:44:29 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  Provides information on charset.
*/

/*!
  \class eZCharsetInfo ezcharsetinfo.php
  \ingroup eZI18N
  \brief Allows for quering information about charsets

  A charset can be known by multiple names but the internationlization
  system only works with one name. To fetch the real internal name use
  the static realCharsetCode() function.
  Each charset also has a specific encoding scheme associated with it
  which can be fetched with characterEncodingScheme().

*/

class eZCharsetInfo
{
    /*!
     \private
     \static
     \return the hash table with aliases, creates if it doesn't already exist.
    */
    static function &aliasTable()
    {
        $aliasTable =& $GLOBALS['eZCharsetInfoTable'];
        if ( !is_array( $aliasTable ) )
        {
            $aliasTable = array( 'ascii' => 'us-ascii',
                                 'latin1' => 'iso-8859-1',
                                 'latin2' => 'iso-8859-2',
                                 'latin3' => 'iso-8859-3',
                                 'latin4' => 'iso-8859-4',
                                 'latin5' => 'iso-8859-9',
                                 'latin6' => 'iso-8859-10',
                                 'latin7' => 'iso-8859-13',
                                 'latin8' => 'iso-8859-14',
                                 'latin9' => 'iso-8859-15',
                                 'cyrillic' => 'iso-8859-5',
                                 'arabic' => 'iso-8859-6',
                                 'greek' => 'iso-8859-7',
                                 'hebrew' => 'iso-8859-8',
                                 'thai' => 'iso-8859-11',

                                 'koi8-r' => 'koi8-r',
                                 'koi-8-r' => 'koi8-r',
                                 'koi8r' => 'koi8-r',

                                 'koi8-u' => 'koi8-u',
                                 'koi-8-u' => 'koi8-u',
                                 'koi8u' => 'koi8-u',

                                 'cp1250' => 'windows-1250',
                                 'cp1251' => 'windows-1251',
                                 'cp1252' => 'windows-1252',
                                 'cp1253' => 'windows-1253',
                                 'cp1254' => 'windows-1254',
                                 'cp1255' => 'windows-1255',
                                 'cp1256' => 'windows-1256',
                                 'cp1257' => 'windows-1257',
                                 'cp1258' => 'windows-1258',
                                 'winlatin1' => 'windows-1252',
                                 'winlatin2' => 'windows-1250',
                                 'wincyrillic' => 'windows-1251',
                                 'wingreek' => 'windows-1253',
                                 'winturkish' => 'windows-1254',
                                 'winhebrew' => 'windows-1255',
                                 'winarabic' => 'windows-1256',
                                 'winbaltic' => 'windows-1257',
                                 'winvietnamese' => 'windows-1258',

                                 'doslatinus' => 'cp437',
                                 'dosgreek' => 'cp737',
                                 'dosbaltrim' => 'cp775',
                                 'doslatin1' => 'cp850',
                                 'doslatin2' => 'cp852',
                                 'doscyrillic' => 'cp855',
                                 'dosturkish' => 'cp857',
                                 'dosportuguese' => 'cp860',
                                 'dosicelandic' => 'cp861',
                                 'doshebrew' => 'cp862',
                                 'doscanadaf' => 'cp863',
                                 'dosarabic' => 'cp864',
                                 'dosnordic' => 'cp865',
                                 'dosgreek2' => 'cp869',
                                 'doscyrillicrussian' => 'cp866',
                                 'dosthai' => 'cp874',

                                 'macroman' => 'macintosh',
                                 'nextstep' => 'next',

                                 'utf8' => 'utf-8',
                                 'utf7' => 'utf-7',

                                 'utf16' => 'utf-16',
                                 'utf16be' => 'utf-16be',
                                 'utf16le' => 'utf-16le',

                                 'utf32' => 'utf-32',
                                 'utf32be' => 'utf-32be',
                                 'utf32le' => 'utf-32le',

                                 'ucs2le' => 'ucs-2le',

                                 'ucs4' => 'ucs-4',
                                 'ucs4be' => 'ucs-4be',
                                 'ucs4le' => 'ucs-4le',

                                 'ucs2' => 'ucs-2',
                                 'ucs2be' => 'ucs-2be',
                                 'ucs2le' => 'ucs-2le',

                                 'shift-jis' => 'cp932',
                                 'gbk' => 'gbk',
                                 'euc-cn' => 'euc-cn',
                                 'unifiedhangul' => 'cp849',
                                 'uhc' => 'cp849',
                                 'big5' => 'cp850'
                                 );
            for ( $i = 1; $i <= 15; ++$i )
            {
                $aliasTable["iso8859-$i"] = "iso-8859-$i";
                $aliasTable["iso8859$i"] = "iso-8859-$i";
            }
            $aliasTable['unicode'] = 'unicode';
        }
        return $aliasTable;
    }

    /*!
     \private
     \static
     \return the character encoding hash table, creates it if it does not exist.
     The table will map from a character encoding scheme to an array of character sets.
     \sa reverseEncodingTable
    */
    static function &encodingTable()
    {
        $encodingTable =& $GLOBALS['eZCharsetInfoEncodingTable'];
        if ( !is_array( $encodingTable ) )
        {
            $encodingTable = array( 'doublebyte' => array( 'cp932',
                                                           'GBK',
                                                           'euc-cn',
                                                           'cp849',
                                                           'cp850' ),
                                    'unicode' => array( 'unicode' ),
                                    'utf-8' => array( 'utf-8' ) );
        }
        return $encodingTable;
    }

    /*!
     \private
     \static
     \return the reverse character encoding hash table, creates it if it does not exist.
     The table will map from a character set to a character encoding scheme.
     \sa encodingTable
    */
    static function &reverseEncodingTable()
    {
        $reverseEncodingTable =& $GLOBALS['eZCharsetInfoReverseEncodingTable'];
        if ( !is_array( $reverseEncodingTable ) )
        {
            $encodingTable =& eZCharsetInfo::encodingTable();
            $reverseEncodingTable = array();
            foreach( $encodingTable as $encodingScheme => $charsetMatches )
            {
                foreach( $charsetMatches as $charsetMatch )
                    $reverseEncodingTable[$charsetMatch] = $encodingScheme;
            }
        }
        return $reverseEncodingTable;
    }

    /*!
     Tries to find an alias for the charset code and returns it. If no
     alias code could be find the original charset code is returned.
     \note The resulting charset code will be an all lowercase letters.
    */
    static function realCharsetCode( $charsetCode )
    {
        $aliasTable =& eZCharsetInfo::aliasTable();
        $charsetCode = strtolower( $charsetCode );
        if ( isset( $aliasTable[$charsetCode] ) )
            return $aliasTable[$charsetCode];
        // Check alias without any dashes
        $charsetCodeNoDash = str_replace( '-', '', $charsetCode );
        if ( isset( $aliasTable[$charsetCodeNoDash] ) )
            return $aliasTable[$charsetCodeNoDash];
        return $charsetCode;
    }

    /*!
     Tries to figure out the character encoding scheme for the given character set.
     It uses realCharsetCode() to get the correct internal charset so any charset
     can be given to this function.
     Either returns the found encoding scheme or 'singlebyte' if no scheme was found.
     \sa realCharsetCode
    */
    static function characterEncodingScheme( $charsetCode, $isRealCharset = false )
    {
        if ( !$isRealCharset )
            $charsetCode = eZCharsetInfo::realCharsetCode( $charsetCode );
        $reverseEncodingTable =& eZCharsetInfo::reverseEncodingTable();
        if ( isset( $reverseEncodingTable[$charsetCode] ) )
            return $reverseEncodingTable[$charsetCode];
        return 'singlebyte';
    }
}

?>

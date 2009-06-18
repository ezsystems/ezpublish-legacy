<?php
//
// Definition of eZCodePage class
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
  \class eZCodePage ezcodepage.php
  \ingroup eZI18N
  \brief Handles codepage files for charset mapping

*/

class eZCodePage
{
    const CACHE_CODE_DATE = 1028204478;

    /*!
     Initializes the codepage with the charset code $charset_code, and then loads it.
    */
    function eZCodePage( $charset_code, $use_cache = true )
    {
        $this->RequestedCharsetCode = $charset_code;
        $this->CharsetCode = eZCharsetInfo::realCharsetCode( $charset_code );
        $this->CharsetEncodingScheme = eZCharsetInfo::characterEncodingScheme( $charset_code );
        $this->Valid = false;
        $this->SubstituteChar = 63; // the ? character
        $this->MinCharValue = 0;
        $this->MaxCharValue = 0;

        $this->load( $use_cache );
    }

    function convertString( $str )
    {
        $len = strlen( $str );
        $chars = '';
        $utf8_codec = eZUTF8Codec::instance();
        for ( $i = 0; $i < $len; )
        {
            $charLen = 1;
            $char = $this->charToUTF8( $str, $i, $charLen );
            if ( $char !== null )
                $chars .= $char;
            else
                $chars .= $utf8_codec->toUtf8( $this->SubstituteChar );
            $i += $charLen;
        }
        return $chars;
    }

    function convertStringToUnicode( $str )
    {
        $len = strlen( $str );
        $unicodeValues = array();
        for ( $i = 0; $i < $len; )
        {
            $charLen = 1;
            $unicodeValue = $this->charToUnicode( $str, $i, $charLen );
            if ( $unicodeValue !== null )
                $unicodeValues[] = $unicodeValue;
            $i += $charLen;
        }
        return $unicodeValues;
    }

    function convertUnicodeToString( $unicodeValues )
    {
        if ( !is_array( $unicodeValues ) )
            return false;
        $text = '';
        foreach ( $unicodeValues as $unicodeValue )
        {
            $char = $this->unicodeToChar( $unicodeValue );
            $text .= $char;
        }
        return $text;
    }

    function convertStringFromUTF8( $multi_char )
    {
        $strlen = strlen( $multi_char );
        $text = '';
        $codeMap = $this->CodeMap;
        $subChar = $this->SubstituteChar;
        for ( $offs = 0; $offs < $strlen; )
        {
//          The following code has been copied from lib/ezi18n/classes/ezutf8codec.php
//          It has been optimized a bit from the original code due to inlining

            $char_code = false;
            if ( ( ord( $multi_char[$offs + 0] ) & 0x80 ) == 0x00 ) // 7 bit, 1 char
            {
                $char_code = ord( $multi_char[$offs + 0] );
                $offs += 1;
            }
            else if ( ( ord( $multi_char[$offs + 0] ) & 0xe0 ) == 0xc0 ) // 11 bit, 2 chars
            {
                if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 )
                {
                    $offs += 2;
                    continue;
                }
                $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x1f ) << 6) +
                               (( ord( $multi_char[$offs + 1] ) & 0x3f )) );
                $offs += 2;
                if ( $char_code < 128 ) // Illegal multibyte, should use less than 2 chars
                    continue;
            }
            else if ( ( ord( $multi_char[$offs + 0] ) & 0xf0 ) == 0xe0 ) // 16 bit, 3 chars
            {
                if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 2] ) & 0xc0 ) != 0x80 )
                {
                    $offs += 3;
                    continue;
                }
                $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x0f ) << 12) +
                               (( ord( $multi_char[$offs + 1] ) & 0x3f ) << 6) +
                               (( ord( $multi_char[$offs + 2] ) & 0x3f )) );
                $offs += 3;
                if ( $char_code < 2048 ) // Illegal multibyte, should use less than 3 chars
                    continue;
            }
            else if ( ( ord( $multi_char[$offs + 0] ) & 0xf8 ) == 0xf0 ) // 21 bit, 4 chars
            {
                if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 2] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 3] ) & 0xc0 ) != 0x80 )
                {
                    $offs += 4;
                    continue;
                }
                $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x07 ) << 18) +
                               (( ord( $multi_char[$offs + 1] ) & 0x3f ) << 12) +
                               (( ord( $multi_char[$offs + 2] ) & 0x3f ) << 6) +
                               (( ord( $multi_char[$offs + 3] ) & 0x3f )) );
                $offs += 4;
                if ( $char_code < 65536 ) // Illegal multibyte, should use less than 4 chars
                    continue;
            }
            else if ( ( ord( $multi_char[$offs + 0] ) & 0xfc ) == 0xf8 ) // 26 bit, 5 chars
            {
                if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 2] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 3] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 4] ) & 0xc0 ) != 0x80 )
                {
                    $offs += 5;
                    continue;
                }
                $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x03 ) << 24) +
                               (( ord( $multi_char[$offs + 1] ) & 0x3f ) << 18) +
                               (( ord( $multi_char[$offs + 2] ) & 0x3f ) << 12) +
                               (( ord( $multi_char[$offs + 3] ) & 0x3f ) << 6) +
                               (( ord( $multi_char[$offs + 4] ) & 0x3f )) );
                $offs += 5;
                if ( $char_code < 2097152 ) // Illegal multibyte, should use less than 5 chars
                    continue;
            }
            else if ( ( ord( $multi_char[$offs + 0] ) & 0xfe ) == 0xfc ) // 31 bit, 6 chars
            {
                if ( ( ord( $multi_char[$offs + 1] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 2] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 3] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 4] ) & 0xc0 ) != 0x80 or
                     ( ord( $multi_char[$offs + 5] ) & 0xc0 ) != 0x80 )
                {
                    $offs += 6;
                    continue;
                }
                $char_code = ( (( ord( $multi_char[$offs + 0] ) & 0x01 ) << 30) +
                               (( ord( $multi_char[$offs + 1] ) & 0x3f ) << 24) +
                               (( ord( $multi_char[$offs + 2] ) & 0x3f ) << 18) +
                               (( ord( $multi_char[$offs + 3] ) & 0x3f ) << 12) +
                               (( ord( $multi_char[$offs + 4] ) & 0x3f ) << 6) +
                               (( ord( $multi_char[$offs + 5] ) & 0x3f )) );
                $offs += 6;
                if ( $char_code < 67108864 ) // Illegal multibyte, should use less than 6 chars
                    continue;
            }
            else // Unknown state, just increase one to make sure we don't get stuck
            {
                $offs += 1;
                continue;
            }

//          The following code has been copied from the member function unicodeToChar
            if ( isset( $codeMap[$char_code] ) )
            {
                $code = $codeMap[$char_code];
                if ( $code <= 0xff )
                    $text .= chr( $code );
                else
                    $text .= chr( ( $code >> 8 ) & 0xff ) . chr( $code & 0xff );
            }
            else
                $text .= chr( $subChar );
        }
        return $text;
    }

    function strlen( $str )
    {
        if ( $this->CharsetEncodingScheme == "doublebyte" )
        {
            $len = strlen( $str );
            $strlen = 0;
            for ( $i = 0; $i < $len; )
            {
                $charLen = 1;
                $code = ord( $str[$i] );
                if ( isset( $this->ReadExtraMap[$code] ) )
                    $charLen = 2;
                ++$strlen;
                $i += $charLen;
            }
            return $strlen;
        }
        else
            return strlen( $str );
    }

    function strlenFromUTF8( $str )
    {
        return eZUTF8Codec::instance()->strlen( $str );
    }

    function charToUtf8( $str, $pos, &$charLen )
    {
        $code = ord( $str[$pos] );
        $charLen = 1;
        if ( isset( $this->ReadExtraMap[$code] ) )
        {
            $code = ( $code << 8 ) | ord( $str[$pos+1] );
            $charLen = 2;
        }
        if ( isset( $this->UTF8Map[$code] ) )
            return $this->UTF8Map[$code];
        return null;
    }

    function charToUnicode( $str, $pos, &$charLen )
    {
        $code = ord( $str[$pos] );
        $charLen = 1;
        if ( isset( $this->ReadExtraMap[$code] ) )
        {
            $code = ( $code << 8 ) | ord( $str[$pos+1] );
            $charLen = 2;
        }
        if ( isset( $this->UnicodeMap[$code] ) )
            return $this->UnicodeMap[$code];
        return null;
    }

    function codeToUtf8( $code )
    {
        return $this->UTF8Map[$code];
    }

    function codeToUnicode( $code )
    {
        if ( isset( $this->UnicodeMap[$code] ) )
        {
            return $this->UnicodeMap[$code];
        }
        return null;
    }

    function utf8ToChar( $ucode )
    {
        if ( isset( $this->UTF8CodeMap[$ucode] ) )
        {
            $code = $this->UTF8CodeMap[$ucode];
            if ( $code <= 0xff )
                return chr( $code );
            else
                return chr( ( $code >> 8 ) & 0xff ) . chr( $code & 0xff );
        }
        else
            return chr( $this->SubstituteChar );
    }

    function unicodeToChar( $ucode )
    {
        if ( isset( $this->CodeMap[$ucode] ) )
        {
            $code = $this->CodeMap[$ucode];
            if ( $code <= 0xff )
                return chr( $code );
            else
                return chr( ( $code >> 8 ) & 0xff ) . chr( $code & 0xff );
        }
        else
            return chr( $this->SubstituteChar );
    }

    function utf8ToCode( $ucode )
    {
        if ( isset( $this->UTF8CodeMap[$ucode] ) )
            return $this->UTF8CodeMap[$ucode];
        return null;
    }

    function unicodeToCode( $ucode )
    {
        if ( isset( $this->CodeMap[$ucode] ) )
            return $this->CodeMap[$ucode];
        return null;
    }

    function substituteChar()
    {
        return $this->SubstituteChar;
    }

    function setSubstituteChar( $char )
    {
        $this->SubstituteChar = $char;
    }

    /*!
     \static
     Returns true if the codepage $charset_code exists.
    */
    static function exists( $charset_code )
    {
        $file = eZCodePage::fileName( $charset_code );
        return file_exists( $file );
    }

    /*!
     \static
     Returns the filename of the charset code \a $charset_code.
    */
    static function fileName( $charset_code )
    {
        $charset_code = eZCharsetInfo::realCharsetCode( $charset_code );
        $file = "share/codepages/" . $charset_code;
        return $file;
    }

    function cacheFileName( $charset_code )
    {
        $permissionArray = eZCodePage::permissionSetting();

        if ( $permissionArray === false )
            return false;
        $charset_code = eZCharsetInfo::realCharsetCode( $charset_code );
        $cache_dir = $permissionArray['var_directory'] . "/codepages/";
        $cache_filename = md5( $charset_code );
        $cache = $cache_dir . $cache_filename . ".php";
        return $cache;
    }

    function fileModification( $charset_code )
    {
        $file = eZCodePage::fileName( $charset_code );
        if ( !file_exists( $file ) )
            return false;
        return filemtime( $file );
    }

    function codepageList()
    {
        $list = array();
        $dir = "share/codepages/";
        $dh = opendir( $dir );
        while ( ( $file = readdir( $dh ) ) !== false )
        {
            if ( $file == "." or
                 $file == ".." or
                 preg_match( "/^\./", $file ) or
                 preg_match( "/~$/", $file ) )
                continue;
            $list[] = $file;
        }
        closedir( $dh );
        sort( $list );
        return $list;
    }


    /*!
    Stores the cache object.
    */
    function storeCacheObject( $filename, $permissionArray )
    {
        $dir = dirname( $filename );
        $file = basename( $filename );
        $php = new eZPHPCreator( $dir, $file );

        $php->addVariable( "umap", array() );
        $php->addVariable( "utf8map", array() );
        $php->addVariable( "cmap", array() );
        $php->addVariable( "utf8cmap", array() );

        reset( $this->UnicodeMap );
        while ( ( $key = key( $this->UnicodeMap ) ) !== null )
        {
            $item = $this->UnicodeMap[$key];
            $php->addVariable( "umap[$key]", $item );
            next( $this->UnicodeMap );
        }

        reset( $this->UTF8Map );
        while ( ( $key = key( $this->UTF8Map ) ) !== null )
        {
            $item = $this->UTF8Map[$key];
            if ( $item == 0 )
            {
                $php->addCodePiece( "\$utf8map[0] = chr(0);\n" );
            }
            else
            {
                $val = str_replace( array( "\\", "'" ),
                                    array( "\\\\", "\\'" ),
                                    $item );
                $php->addVariable( "utf8map[$key]", $val );
            }
            next( $this->UTF8Map );
        }

        reset( $this->CodeMap );
        while ( ( $key = key( $this->CodeMap ) ) !== null )
        {
            $item = $this->CodeMap[$key];
            $php->addVariable( "cmap[$key]", $item );
            next( $this->CodeMap );
        }

        reset( $this->UTF8CodeMap );
        while ( ( $key = key( $this->UTF8CodeMap ) ) !== null )
        {
            $item = $this->UTF8CodeMap[$key];
            if ( $item == 0 )
            {
                $php->addVariable( "utf8cmap[chr(0)]", 0 );
            }
            else
            {
                $val = str_replace( array( "\\", "'" ),
                                    array( "\\\\", "\\'" ),
                                    $key );
                $php->addVariable( "utf8cmap['$val']", $item );
            }
            next( $this->UTF8CodeMap );
        }

        reset( $this->ReadExtraMap );
        while ( ( $key = key( $this->ReadExtraMap ) ) !== null )
        {
            $item = $this->ReadExtraMap[$key];
            $php->addVariable( "read_extra[$key]", $item );
            next( $this->ReadExtraMap );
        }

        $php->addVariable( "eZCodePageCacheCodeDate", self::CACHE_CODE_DATE );
        $php->addVariable( "min_char", $this->MinCharValue );
        $php->addVariable( "max_char", $this->MaxCharValue );
        $php->store( true );

        if ( file_exists( $filename ) )
        {
            // Store the old umask and set a new one.
            $oldPermissionSetting = umask( 0 );

            // Change the permission setting.
            @chmod( $filename, $permissionArray['file_permission'] );

            // Restore the old umask.
            umask( $oldPermissionSetting );
        }
    }


    function cacheFilepath()
    {
        $permissionArray = eZCodePage::permissionSetting();

        if ( $permissionArray === false )
            return false;
        $cache_dir = $permissionArray['var_directory'] . "/codepages/";
        $cache_filename = md5( $this->CharsetCode );
        $cache = $cache_dir . $cache_filename . ".php";

        return $cache;
    }



    /*!
     Loads the codepage from disk.
     If $use_cache is true and a cached version is found it is used instead.
     If $use_cache is true and no cache was found a new cache is created.
    */
    function load( $use_cache = true )
    {
        // temporarely hide the cache display problem
        // http://ez.no/community/bugs/char_transform_cache_file_is_not_valid_php
        //$use_cache = false;
        $file = "share/codepages/" . $this->CharsetCode;
//         eZDebug::writeDebug( "ezcodepage::load was called for $file..." );

        $permissionArray = self::permissionSetting();
        if ( $permissionArray !== false )
        {
            $cache_dir = $permissionArray['var_directory'] . "/codepages/";
            $cache_filename = md5( $this->CharsetCode );
            $cache = $cache_dir . $cache_filename . ".php";
        }
        else
        {
            $cache = false;
        }

        if ( !file_exists( $file ) )
        {
            eZDebug::writeWarning( "Couldn't load codepage file $file", "eZCodePage" );
            return;
        }
        $file_m = filemtime( $file );
        $this->Valid = false;
        if ( isset( $GLOBALS['eZSiteBasics'] ) )
        {
            $siteBasics = $GLOBALS['eZSiteBasics'];
            if ( isset( $siteBasics['no-cache-adviced'] ) and
                 $siteBasics['no-cache-adviced'] )
                $use_cache = false;
        }
        if ( $cache && file_exists( $cache ) and $use_cache )
        {
            $cache_m = filemtime( $cache );
            if ( $file_m <= $cache_m )
            {
                unset( $eZCodePageCacheCodeDate );
                $umap = $utf8map = $cmap = $utf8cmap = $min_char = $max_char = $read_extra = null;
                include( $cache );
                $this->UnicodeMap = $umap;
                $this->UTF8Map = $utf8map;
                $this->CodeMap = $cmap;
                $this->UTF8CodeMap = $utf8cmap;
                $this->MinCharValue = $min_char;
                $this->MaxCharValue = $max_char;
                $this->ReadExtraMap = $read_extra;

                if ( isset( $eZCodePageCacheCodeDate ) and
                     $eZCodePageCacheCodeDate == self::CACHE_CODE_DATE )
                {
                    $this->Valid = true;
                    return;
                }
            }
        }

        $utf8_codec = eZUTF8Codec::instance();

        $this->UnicodeMap = array();
        $this->UTF8Map = array();
        $this->CodeMap = array();
        $this->UTF8CodeMap = array();
        $this->ReadExtraMap = array();
        for ( $i = 0; $i < 32; ++$i )
        {
            $code = $i;
            $ucode = $i;
            $utf8_code = $utf8_codec->toUtf8( $ucode );
            $this->UnicodeMap[$code] = $ucode;
            $this->UTF8Map[$code] = $utf8_code;
            $this->CodeMap[$ucode] = $code;
            $this->UTF8CodeMap[$utf8_code] = $code;
        }
        $this->MinCharValue = 0;
        $this->MaxCharValue = 31;

        $lines = file( $file );
        reset( $lines );
        while ( ( $key = key( $lines ) ) !== null )
        {
            if ( preg_match( "/^#/", $lines[$key] ) )
            {
                next( $lines );
                continue;
            }
            $line = trim( $lines[$key] );
            $items = explode( "\t", $line );
            if ( count( $items ) == 3 )
            {
                $code = false;
                $ucode = false;
                $desc = $items[2];
                if ( preg_match( "/(=|0x)([0-9a-fA-F]{4})/", $items[0], $args ) )
                {
                    $code = hexdec( $args[2] );
//                    eZDebug::writeNotice( $args, "doublebyte" );
                }
                else if ( preg_match( "/(=|0x)([0-9a-fA-F]{2})/", $items[0], $args ) )
                {
                    $code = hexdec( $args[2] );
//                    eZDebug::writeNotice( $args, "singlebyte" );
                }
                if ( preg_match( "/(U\+|0x)([0-9a-fA-F]{4})/", $items[1], $args ) )
                {
                    $ucode = hexdec( $args[2] );
                }

                if ( $code !== false and
                     $ucode !== false )
                {
                    $utf8_code = $utf8_codec->toUtf8( $ucode );
                    $this->UnicodeMap[$code] = $ucode;
                    $this->UTF8Map[$code] = $utf8_code;
                    $this->CodeMap[$ucode] = $code;
                    $this->UTF8CodeMap[$utf8_code] = $code;
                    $this->MinCharValue = min( $this->MinCharValue, $code );
                    $this->MaxCharValue = max( $this->MaxCharValue, $code );
                }
                else if ( $code !== false )
                {
                    $this->ReadExtraMap[$code] = true;
                }
            }
            next( $lines );
        }

        $this->Valid = true;
        $this->MinCharValue = min( $this->MinCharValue, $code );
        $this->MaxCharValue = max( $this->MaxCharValue, $code );

        if ( $use_cache )
        {
            // If there is no setting; do nothing:
            if ( $permissionArray === false )
            {
                if ( !isset ( $GLOBALS['EZCODEPAGECACHEOBJECTLIST'] ) )
                {
                    $GLOBALS['EZCODEPAGECACHEOBJECTLIST'] = array();
                }

                // The array already exists; we simply append to it.
                $GLOBALS['EZCODEPAGECACHEOBJECTLIST'][] = $this;
            }
            // Else: a permission setting exists:
            else
            {
                // Store the cache object with the correct permission setting.
                $this->storeCacheObject( $cache, $permissionArray );

                // Check if the global array for codepage cache objects exist:
            }
        }
    }

    /*!
     \return the charset code which is in use. This may not be the charset that was
     requested due to aliases.
     \sa requestedCharsetCode
    */
    function charsetCode()
    {
        return $this->CharsetCode;
    }

    /*!
     \return the charset code which was requested, may differ from charsetCode()
    */
    function requestedCharsetCode()
    {
        return $this->RequestedCharsetCode;
    }

    /*!
     \return the lowest character value used in the mapping table.
    */
    function minCharValue()
    {
        return $this->MinCharValue;
    }

    /*!
     \return the largest character value used in the mapping table.
    */
    function maxCharValue()
    {
        return $this->MaxCharValue;
    }

    /*!
     Returns true if the codepage is valid for use.
    */
    function isValid()
    {
        return $this->Valid;
    }

    /**
     * Returns a shared instance of the eZCodePage pr the
     * $charset_code param.
     *
     * @param $charset_code string
     * @param $use_cache bool
     * @return eZCodePage
     */
    static function instance( $charset_code, $use_cache = true )
    {
        if ( empty( $GLOBALS["eZCodePage-$charset_code"] ) )
        {
            $GLOBALS["eZCodePage-$charset_code"] = new eZCodePage( $charset_code, $use_cache );
        }
        return $GLOBALS["eZCodePage-$charset_code"];
    }

    /*!
     \private

     Gets the permission setting for codepage files & returns it.
     If the permission setting doesnt exists: returns false.
    */
    static function permissionSetting()
    {
//         eZDebug::writeDebug( "permissionSetting was called..." );

        if ( isset( $GLOBALS['EZCODEPAGEPERMISSIONS'] ) )
        {
            return $GLOBALS['EZCODEPAGEPERMISSIONS'];
        }
        else
        {
            return false;
        }
    }

    /*!
     \private

     Sets the permission setting for codepagefiles.
    */
    static function setPermissionSetting( $permissionArray )
    {
//         eZDebug::writeDebug( "setPermissionSetting was called..." );

        $GLOBALS['EZCODEPAGEPERMISSIONS'] = $permissionArray;

        if ( $permissionArray !== false )
        {
            eZCodePage::flushCacheObject();
        }
    }

    /*!
     \private
    */
    static function flushCacheObject()
    {
//         eZDebug::writeDebug("flushCacheObject is called... ","");
        if ( !isset( $GLOBALS['EZCODEPAGECACHEOBJECTLIST'] ) )
        {
            return false;
        }

        // Grab the permission setting for codepage cache files.
        $permissionArray = self::permissionSetting();

        // If we were unable to extract the permission setting:
        if ( $permissionArray === false )
        {
//             eZDebug::writeDebug( "permissionSetting: unable to grab permission setting from global array..." );

            // Bail with false.
            return false;
        }
        // Else: permission setting is available:
        else
        {
//             eZDebug::writeDebug( "permissionSetting: grabbed permission setting from global array..." );

            // For all the cache objects:
            foreach( array_keys( $GLOBALS['EZCODEPAGECACHEOBJECTLIST'] ) as $codePageKey )
            {
                $codePage = $GLOBALS['EZCODEPAGECACHEOBJECTLIST'][$codePageKey];

                $filename = $codePage->cacheFilepath();

                // Store __FIX_ME__
                $codePage->storeCacheObject( $filename, $permissionArray );
            }

            //
            unset( $GLOBALS['EZCODEPAGECACHEOBJECTLIST'] );
        }
    }

    /// \privatesection
    /// The charset code which was requested, may differ from $CharsetCode
    public $RequestedCharsetCode;
    /// The read charset code, may differ from $RequestedCharsetCode
    public $CharsetCode;
    /// Encoding scheme for current charset, for instance utf-8, singlebyte, multibyte
    public $CharsetEncodingScheme;
    /// Maps normal codes to unicode
    public $UnicodeMap;
    /// Maps normal codes to utf8
    public $UTF8Map;
    /// Maps unicode to normal codes
    public $CodeMap;
    /// Maps utf8 to normal codes
    public $UTF8CodeMap;
    /// The minimum key value for the mapping tables
    public $MinCharValue;
    /// The maximum key value for the mapping tables
    public $MaxCharValue;
    /// Whether the codepage is valid or not
    public $Valid;
    /// The character to use when an alternative doesn't exist
    public $SubstituteChar;
}

// Checks if index.php or any other script has set any codepage permissions
if ( isset( $GLOBALS['EZCODEPAGEPERMISSIONS'] ) and
     $GLOBALS['EZCODEPAGEPERMISSIONS'] !== false )
{
    eZCodePage::flushCacheObject();
}

?>

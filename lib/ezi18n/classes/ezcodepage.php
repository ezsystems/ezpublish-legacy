<?php
//
// Definition of eZCodePage class
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
  \class eZCodePage ezcodepage.php
  \ingroup eZI18N
  \brief Handles codepage files for charset mapping

*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezi18n/classes/ezutf8codec.php" );
include_once( "lib/ezi18n/classes/ezcharsetinfo.php" );

define( "EZ_CODEPAGE_CACHE_CODE_DATE", 1028204478 );

class eZCodePage
{
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

    function convertString( &$str )
    {
        $len = strlen( $str );
        $chars = array();
        $utf8_codec =& eZUTF8Codec::instance();
        for ( $i = 0; $i < $len; )
        {
            $charLen = 1;
            $char = $this->charToUTF8( $str, $i, $charLen );
            if ( $char !== null )
                $chars[] = $char;
            else
                $chars[] = $utf8_codec->toUtf8( $this->SubstituteChar );
            $i += $charLen;
        }
        return implode( '', $chars );
    }

    function convertStringToUnicode( &$str )
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

    function convertStringFromUTF8( &$multi_char )
    {
        $strlen = strlen( $multi_char );
        $text = '';
        $codeMap =& $this->CodeMap;
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

    function strlen( &$str )
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

    function strlenFromUTF8( &$str )
    {
        $utf8_codec =& eZUTF8Codec::instance();
        return $utf8_codec->strlen( $str );
    }

    function charToUtf8( &$str, $pos, &$charLen )
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

    function charToUnicode( &$str, $pos, &$charLen )
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

    function codeToUtf8( &$code )
    {
        return $this->UTF8Map[$code];
    }

    function codeToUnicode( &$code )
    {
        if ( isset( $this->UnicodeMap[$code] ) )
        {
            return $this->UnicodeMap[$code];
        }
        return null;
    }

    function utf8ToChar( &$ucode )
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

    function unicodeToChar( &$ucode )
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

    function utf8ToCode( &$ucode )
    {
        if ( isset( $this->UTF8CodeMap[$ucode] ) )
            return $this->UTF8CodeMap[$ucode];
        return null;
    }

    function unicodeToCode( &$ucode )
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
    function exists( $charset_code )
    {
        $file = eZCodePage::fileName( $charset_code );
        return file_exists( $file );
    }

    /*!
     \static
     Returns the filename of the charset code \a $charset_code.
    */
    function fileName( $charset_code )
    {
        $charset_code = eZCharsetInfo::realCharsetCode( $charset_code );
        $file = "share/codepages/" . $charset_code;
        return $file;
    }

    function cacheFileName( $charset_code )
    {
        $permissionArray = eZCodepage::permissionSetting();
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
        //
        $cache_dir = $permissionArray['var_directory'] . "/codepages/";

//         eZDebug::writeDebug("storeCacheObject is called... filname is: $filename ","");

        $str = "\$umap = array();\n\$utf8map = array();\n\$cmap = array();\n\$utf8cmap = array();\n";
        reset( $this->UnicodeMap );
        while ( ( $key = key( $this->UnicodeMap ) ) !== null )
        {
            $item =& $this->UnicodeMap[$key];
            $str .= "\$umap[$key] = $item;\n";
            next( $this->UnicodeMap );
        }
        reset( $this->UTF8Map );
        while ( ( $key = key( $this->UTF8Map ) ) !== null )
        {
            $item =& $this->UTF8Map[$key];
            $val = str_replace( array( "\\", "'" ),
                                array( "\\\\", "\\'" ),
                                $item );
            $str .= "\$utf8map[$key] = '$val';\n";
            next( $this->UTF8Map );
        }
        reset( $this->CodeMap );
        while ( ( $key = key( $this->CodeMap ) ) !== null )
        {
            $item =& $this->CodeMap[$key];
            $str .= "\$cmap[$key] = $item;\n";
            next( $this->CodeMap );
        }
        reset( $this->UTF8CodeMap );
        while ( ( $key = key( $this->UTF8CodeMap ) ) !== null )
        {
            $item =& $this->UTF8CodeMap[$key];
            if ( $item == 0 )
            {
                $str .= "\$utf8cmap[chr(0)] = 0;\n";
            }
            else
            {
                $val = str_replace( array( "\\", "'" ),
                                    array( "\\\\", "\\'" ),
                                    $key );
                $str .= "\$utf8cmap['$val'] = $item;\n";
            }
            next( $this->UTF8CodeMap );
        }
        reset( $this->ReadExtraMap );
        while ( ( $key = key( $this->ReadExtraMap ) ) !== null )
        {
            $item =& $this->ReadExtraMap[$key];
            $str .= "\$read_extra[$key] = $item;\n";
            next( $this->ReadExtraMap );
        }
        $str = "<?" . "php
$str
\$eZCodePageCacheCodeDate = " . EZ_CODEPAGE_CACHE_CODE_DATE . ";
\$min_char = " . $this->MinCharValue . ";
\$max_char = " . $this->MaxCharValue . ";
?" . ">";

        if ( !file_exists( $cache_dir ) )
        {
//             eZDebug::writeDebug( "Cache dir doesn't exist, attempting to create it with perm.:".$permissionArray['dir_permission'], "");

            // Store the old umask and set a new one.
            $oldPermissionSetting = umask( 0 );

            include_once( 'lib/ezfile/classes/ezdir.php' );
            if ( ! eZDir::mkdir( $cache_dir, $permissionArray['dir_permission'], true ) )
                eZDebug::writeError( "Couldn't create cache directory $cache_dir, perhaps wrong permissions", "eZCodepage" );

            // Restore the old umask.
            umask( $oldPermissionSetting );

        }
        $fd = @fopen( $filename, "w+" );
        if ( ! $fd )
        {
            eZDebug::writeError( "Couldn't write cache file $filename, perhaps wrong permissions or leading directories not created", "eZCodepage" );
        }
        else
        {
            fwrite( $fd, $str );
            fclose( $fd );
        }

        if ( file_exists( $filename) )
        {
            // Store the old umask and set a new one.
            $oldPermissionSetting = umask( 0 );

            // Change the permission setting.
            @chmod( $filename, $permissionArray['file_permission'] );

            // Restore the old umask.
            umask( $oldPermissionSetting );
        }
    }


   /*!
    */
    function cacheFilepath()
    {
        $permissionArray = eZCodepage::permissionSetting();
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
        $file = "share/codepages/" . $this->CharsetCode;
//         eZDebug::writeDebug( "ezcodepage::load was called for $file..." );

        $permissionArray = eZCodepage::permissionSetting();
        $cache_dir = $permissionArray['var_directory'] . "/codepages/";
        $cache_filename = md5( $this->CharsetCode );
        $cache = $cache_dir . $cache_filename . ".php";

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
        if ( file_exists( $cache ) and $use_cache )
        {
            $cache_m = filemtime( $cache );
            if ( $file_m <= $cache_m )
            {
                unset( $eZCodePageCacheCodeDate );
                $umap =& $this->UnicodeMap;
                $utf8map =& $this->UTF8Map;
                $cmap =& $this->CodeMap;
                $utf8cmap =& $this->UTF8CodeMap;
                $min_char =& $this->MinCharValue;
                $max_char =& $this->MaxCharValue;
                $read_extra =& $this->ReadExtraMap;
                include( $cache );
                unset( $umap );
                unset( $utf8map );
                unset( $cmap );
                unset( $utf8map );
                unset( $min_char );
                unset( $max_char );
                unset( $read_extra );
                if ( isset( $eZCodePageCacheCodeDate ) and
                     $eZCodePageCacheCodeDate == EZ_CODEPAGE_CACHE_CODE_DATE )
                {
                    $this->Valid = true;
                    return;
                }
            }
        }

        $utf8_codec =& eZUTF8Codec::instance();

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
            // Grab the permission setting(s).
            $permissionArray = $this->permissionSetting();

            // If there is no setting; do nothing:
            if ( $permissionArray === false )
            {
                if ( !isset ( $GLOBALS['EZCODEPAGECACHEOBJECTLIST'] ) )
                {
                    $GLOBALS['EZCODEPAGECACHEOBJECTLIST'] = array();
                }

                // The array already exists; we simply append to it.
                $GLOBALS['EZCODEPAGECACHEOBJECTLIST'][] =& $this;
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

    /*!
     Returns the only instance of the codepage for $charset_code.
    */
    function &instance( $charset_code, $use_cache = true )
    {
        $cp =& $GLOBALS["eZCodePage-$charset_code"];
        if ( get_class( $cp ) != "ezcodepage" )
        {
            $cp = new eZCodePage( $charset_code, $use_cache );
        }
        return $cp;
    }

    /*!
     \private

     Gets the permission setting for codepage files & returns it.
     If the permission setting doesnt exists: returns false.
    */
    function permissionSetting()
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
    function setPermissionSetting( $permissionArray )
    {
//         eZDebug::writeDebug( "setPermissionSetting was called..." );

        $GLOBALS['EZCODEPAGEPERMISSIONS'] = $permissionArray;

        if ( $permissionArray !== false )
        {
            eZCodepage::flushCacheObject();
        }
    }

    /*!
     \private
    */
    function flushCacheObject()
    {
//         eZDebug::writeDebug("flushCacheObject is called... ","");
        if ( !isset( $GLOBALS['EZCODEPAGECACHEOBJECTLIST'] ) )
        {
            return false;
        }

        // Grab the permission setting for codepage cache files.
        $permissionArray = eZCodepage::permissionSetting();

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
                $codePage =& $GLOBALS['EZCODEPAGECACHEOBJECTLIST'][$codePageKey];

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
    var $RequestedCharsetCode;
    /// The read charset code, may differ from $RequestedCharsetCode
    var $CharsetCode;
    /// Encoding scheme for current charset, for instance utf-8, singlebyte, multibyte
    var $CharsetEncodingScheme;
    /// Maps normal codes to unicode
    var $UnicodeMap;
    /// Maps normal codes to utf8
    var $UTF8Map;
    /// Maps unicode to normal codes
    var $CodeMap;
    /// Maps utf8 to normal codes
    var $UTF8CodeMap;
    /// The minimum key value for the mapping tables
    var $MinCharValue;
    /// The maximum key value for the mapping tables
    var $MaxCharValue;
    /// Whether the codepage is valid or not
    var $Valid;
    /// The character to use when an alternative doesn't exist
    var $SubstituteChar;
}

// Checks if index.php or any other script has set any codepage permissions
if ( isset( $GLOBALS['EZCODEPAGEPERMISSIONS'] ) and
     $GLOBALS['EZCODEPAGEPERMISSIONS'] !== false )
{
    eZCodepage::flushCacheObject();
}

?>

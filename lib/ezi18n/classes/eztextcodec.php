<?php
//
// Definition of eZTextCodec class
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

/*! \defgroup eZI18N Internationalization */

/*!
  \class eZTextCodec eztextcodec.php
  \ingroup eZI18N
  \brief Handles conversion from one charset to another

  Supports <a href="http://www.ietf.org/rfc/rfc2279.txt">utf8</a> encoding/decoding

*/

include_once( "lib/ezi18n/classes/ezcodepagemapper.php" );
include_once( "lib/ezi18n/classes/ezmbstringmapper.php" );
include_once( "lib/ezi18n/classes/ezcharsetinfo.php" );

class eZTextCodec
{
    /*!
    */
    function eZTextCodec( $inputCharsetCode, $outputCharsetCode )
    {
        $this->RequestedInputCharsetCode = $inputCharsetCode;
        $this->RequestedOutputCharsetCode = $outputCharsetCode;
        $this->InputCharsetCode = eZCharsetInfo::realCharsetCode( $inputCharsetCode );
        $this->OutputCharsetCode = eZCharsetInfo::realCharsetCode( $outputCharsetCode );
        $this->InputCharacterEncodingScheme = eZCharsetInfo::characterEncodingScheme( $this->InputCharsetCode );
        $this->OutputCharacterEncodingScheme = eZCharsetInfo::characterEncodingScheme( $this->OutputCharsetCode );

        $useMBStringExtension = false;
        if ( isset( $GLOBALS['eZTextCodecMBStringExtension'] ) )
            $useMBStringExtension = $GLOBALS['eZTextCodecMBStringExtension'];

        $useMBString = ( $useMBStringExtension and
                         eZTextCodec::useMBString() and
                         eZMBStringMapper::hasMBStringExtension() );

        // Map for conversion functions using encoding functions
        $encodingConvertMap = array();
        $encodingConvertInitMap = array();
        $encodingStrlenMap = array();

        $encodingStrlenMap['unicode'] = 'strlenUnicode';
        $encodingStrlenMap['utf-8'] = 'strlenCodepageRev';
        $encodingStrlenMap['singlebyte'] = 'strlenCodepage';
        $encodingStrlenMap['doublebyte'] = 'strlenCodepage';

        // Unicode -> other
        $encodingConvertMap['unicode']['unicode'] = 'convertNone';
        $encodingConvertMap['unicode']['utf-8'] = 'convertUnicodeToUTF8';
        $encodingConvertMap['unicode']['singlebyte'] = 'convertUnicodeToCodepage';
        $encodingConvertMap['unicode']['doublebyte'] = 'convertUnicodeToCodepage';

        $encodingConvertInitMap['unicode']['singlebyte'] = 'initializeOutputCodepage';
        $encodingConvertInitMap['unicode']['doublebyte'] = 'initializeOutputCodepage';

        // UTF8 -> other
        $encodingConvertMap['utf-8']['unicode'] = 'convertUTF8ToUnicode';
        $encodingConvertMap['utf-8']['utf-8'] = 'convertNone';
        $encodingConvertMap['utf-8']['singlebyte'] = 'convertCodepageRev';
        $encodingConvertMap['utf-8']['doublebyte'] = 'convertCodepageRev';

        $encodingConvertInitMap['utf-8']['singlebyte'] = 'initializeOutputCodepage';
        $encodingConvertInitMap['utf-8']['doublebyte'] = 'initializeOutputCodepage';

        // singlebyte -> other
        $encodingConvertMap['singlebyte']['unicode'] = 'convertCodepageToUnicode';
        $encodingConvertMap['singlebyte']['utf-8'] = 'convertCodepage';
        $encodingConvertMap['singlebyte']['singlebyte'] = 'convertCodepageMapper';
        $encodingConvertMap['singlebyte']['doublebyte'] = 'convertCodepageMapper';

        $encodingConvertInitMap['singlebyte']['unicode'] = 'initializeInputCodepage';
        $encodingConvertInitMap['singlebyte']['utf-8'] = 'initializeInputCodepage';
        $encodingConvertInitMap['singlebyte']['singlebyte'] = 'initializeCodepageMapper';
        $encodingConvertInitMap['singlebyte']['doublebyte'] = 'initializeCodepageMapper';

        // doublebyte -> other
        $encodingConvertMap['doublebyte']['unicode'] = 'convertCodepageToUnicode';
        $encodingConvertMap['doublebyte']['utf-8'] = 'convertCodepage';
        $encodingConvertMap['doublebyte']['singlebyte'] = 'convertCodepageMapper';
        $encodingConvertMap['doublebyte']['doublebyte'] = 'convertCodepageMapper';

        $encodingConvertInitMap['doublebyte']['unicode'] = 'initializeInputCodepage';
        $encodingConvertInitMap['doublebyte']['utf-8'] = 'initializeInputCodepage';
        $encodingConvertInitMap['doublebyte']['singlebyte'] = 'initializeCodepageMapper';
        $encodingConvertInitMap['doublebyte']['doublebyte'] = 'convertCodepageMapper';

        $noneConversionFunction = 'convertNone';
        $noneStrlenFunction = 'strlenNone';
        $conversionFunction = null;
        $strlenFunction = null;
        $encodingConvertInitFunction = null;

//         $unicodeConversionFunction = 'convertNoneToUnicode';
//         $useMapper = false;

//         print( "inp=" . $this->InputCharsetCode . ", out=" . $this->OutputCharsetCode .
//                ", ienc=" . $this->InputCharacterEncodingScheme . ", oenc=" . $this->OutputCharacterEncodingScheme . "<br/>" );

        // First detect conversion type
        if ( $this->InputCharsetCode == $this->OutputCharsetCode ) // Direct match, no conversion
        {
//             print( "Similar<br/>" );
            $conversionFunction = $noneConversionFunction;
            $encodingConvertInitFunction = 'initializeInputCodepage';
//             eZDebug::writeNotice( "none " . $this->InputCharsetCode . "/" . $this->OutputCharsetCode, "eZTextCodec" );
        }
        else if ( $useMBString and
                  eZMBStringMapper::isCharsetSupported( $this->InputCharsetCode ) and
                  eZMBStringMapper::isCharsetSupported( $this->OutputCharsetCode ) ) // Use MBString for converting if charsets supported
        {
//             print( "MBString<br/>" );
            $this->MBStringMapper = eZMBStringMapper::instance( $this->InputCharsetCode,
                                                                $this->OutputCharsetCode );
            $conversionFunction = "convertMBString";
            $strlenFunction = "strlenMBString";
            $encodingConvertInitFunction = false;
//             eZDebug::writeNotice( "mbstring " . $this->InputCharsetCode . "/" . $this->OutputCharsetCode, "eZTextCodec" );
        }
        else // See if we support encoding scheme and codepage
        {
//             print( "else<br/>" );
            $inpenc = $this->InputCharacterEncodingScheme;
            $outenc = $this->OutputCharacterEncodingScheme;
            if ( isset( $encodingConvertMap[$inpenc][$outenc] ) )
            {
                $conversionFunction = $encodingConvertMap[$inpenc][$outenc];
            }
        }

        if ( $strlenFunction === null )
        {
            $inpenc = $this->InputCharacterEncodingScheme;
            if ( isset( $encodingStrlenMap[$inpenc] ) )
            {
                $strlenFunction = $encodingStrlenMap[$inpenc];
            }
        }

//         {
//             if ( $this->InputCharacterEncodingScheme == $this->OutputCharacterEncodingScheme ) // If they match use direct mappers
//             {
//                 if ( $this->InputCharacterEncodingScheme == "singlebyte" or
//                      $this->InputCharacterEncodingScheme == "doublebyte" )
//                 {
//                     $this->CodepageMapper =& eZCodepageMapper::instance( $this->InputCharsetCode,
//                                                                          $this->OutputCharsetCode );
//                     if ( $this->CodepageMapper->isValid() )
//                     {
//                         $conversionFunction = "convertCodepageMapper";
//                         $strlenFunction = "strlenCodepageMapper";
//                         $useMapper = true;
// //                         eZDebug::writeNotice( "codepagemapper", "eZTextCodec" );
//                     }
//                     else
//                         unset( $this->CodepageMapper );
//                 }
//             }
//             else if ( $this->OutputCharacterEncodingScheme == "utf-8" )
//             {
//                 if ( eZCodePage::exists( $this->InputCharsetCode ) )
//                 {
//                     $this->Codepage =& eZCodepage::instance( $this->InputCharsetCode );
//                     if ( $this->Codepage->isValid() )
//                     {
//                         $conversionFunction = "convertCodepage";
//                         $strlenFunction = "strlenCodepage";
//                         $useMapper = false;
// //                         eZDebug::writeNotice( "codepage", "eZTextCodec" );
//                     }
//                     else
//                     {
//                         unset( $this->Codepage );
//                     }
//                 }
//             }
//             else if ( $this->InputCharacterEncodingScheme == "utf-8" )
//             {
//                 if ( eZCodePage::exists( $this->OutputCharsetCode ) )
//                 {
//                     $this->Codepage =& eZCodepage::instance( $this->OutputCharsetCode );
//                     if ( $this->Codepage->isValid() )
//                     {
//                         $conversionFunction = "convertCodepageRev";
//                         $strlenFunction = "strlenCodepageRev";
//                         $useMapper = false;
// //                         eZDebug::writeNotice( "codepage", "eZTextCodec" );
//                     }
//                     else
//                     {
//                         unset( $this->Codepage );
//                     }
//                 }
//             }
//             else if ( $this->OutputCharacterEncodingScheme == "unicode" )
//             {
//                 if ( eZCodePage::exists( $this->InputCharsetCode ) )
//                 {
//                     $this->Codepage =& eZCodepage::instance( $this->InputCharsetCode );
//                     if ( $this->Codepage->isValid() )
//                     {
//                         $conversionFunction = "convertCodepageToUnicode";
//                         $strlenFunction = "strlenCodepage";
//                         $useMapper = false;
// //                         eZDebug::writeNotice( "codepage", "eZTextCodec" );
//                     }
//                     else
//                     {
//                         unset( $this->Codepage );
//                     }
//                 }
//             }
//             else if ( $this->InputCharacterEncodingScheme == "unicode" )
//             {
//                 if ( eZCodePage::exists( $this->OutputCharsetCode ) )
//                 {
//                     $this->Codepage =& eZCodepage::instance( $this->OutputCharsetCode );
//                     if ( $this->Codepage->isValid() )
//                     {
//                         $conversionFunction = "convertCodepageRev";
//                         $strlenFunction = "strlenCodepageRev";
//                         $useMapper = false;
// //                         eZDebug::writeNotice( "codepage", "eZTextCodec" );
//                     }
//                     else
//                     {
//                         unset( $this->Codepage );
//                     }
//                 }
//             }
//         }
//         if ( $this->InputCharacterEncodingScheme == "utf-8" )
//         {
//             $unicodeConversionFunction = 'convertUTF8ToUnicode';
//         }
//         else
//         {
//             if ( eZCodePage::exists( $this->InputCharsetCode ) )
//             {
//                 $this->UnicodeCodepage =& eZCodepage::instance( $this->InputCharsetCode );
//                 if ( $this->UnicodeCodepage->isValid() )
//                 {
//                     $unicodeConversionFunction = 'convertCodepageToUnicode';
//                 }
//                 else
//                 {
//                     unset( $this->UnicodeCodepage );
//                 }
//             }
//         }
//         print( "$conversionFunction<br/>" );
//         print( "$strlenFunction<br/>" );

        if ( $conversionFunction and
             $strlenFunction )
            $this->initializeConversionFunction( $encodingConvertInitMap, $encodingConvertInitFunction );
        if ( !$conversionFunction or
             !$strlenFunction )
        {
            eZDebug::writeError( "Cannot create textcodec from characterset " . $this->RequestedInputCharsetCode .
                                 " to characterset " . $this->RequestedOutputCharsetCode,
                                 "eZTextCodec" );
            if ( !$conversionFunction )
                $conversionFunction = $noneConversionFunction;
            if ( !$strlenFunction )
                $strlenFunction = $noneStrlenFunction;
        }

        $this->ConversionFunction = $conversionFunction;
        $this->StrlenFunction = $strlenFunction;
        $this->RequireConversion = $conversionFunction != $noneConversionFunction;
    }

    function initializeConversionFunction( $encodingConvertInitMap, $encodingConvertInitFunction )
    {
        $inpenc = $this->InputCharacterEncodingScheme;
        $outenc = $this->OutputCharacterEncodingScheme;
        $initFunction = false;
        if ( $encodingConvertInitFunction !== null )
        {
            if ( $encodingConvertInitFunction )
                $initFunction = $encodingConvertInitFunction;
        }
        else if ( isset( $encodingConvertInitMap[$inpenc][$outenc] ) )
        {
            $initFunction = $encodingConvertInitMap[$inpenc][$outenc];
        }
        if ( $initFunction )
        {
//             print( "$initFunction<br/>" );
            $this->$initFunction();
        }
    }

    function initializeCodepageMapper()
    {
        $this->CodepageMapper =& eZCodepageMapper::instance( $this->InputCharsetCode,
                                                             $this->OutputCharsetCode );
    }

    function initializeInputCodepage()
    {
        $this->Codepage =& eZCodepage::instance( $this->InputCharsetCode );
    }

    function initializeOutputCodepage()
    {
        $this->Codepage =& eZCodepage::instance( $this->OutputCharsetCode );
    }

    /*!/
     \return true if a conversion is required, if false there's no need to call the textcodec functions.
    */
    function conversionRequired()
    {
        return $this->RequireConversion;
    }

    function setUseMBString( $use )
    {
        $GLOBALS["eZTextCodecUseMBString"] = $use;
    }

    function useMBString()
    {
        $use =& $GLOBALS["eZTextCodecUseMBString"];
        if ( !isset( $use ) )
            $use = true;
        return $use;
    }

    function requestedInputCharsetCode()
    {
        return $this->RequestedInputCharsetCode;
    }

    function requestedOutputCharsetCode()
    {
        return $this->RequestedOutputCharsetCode;
    }

    function inputCharsetCode()
    {
        return $this->InputCharsetCode;
    }

    function outputCharsetCode()
    {
        return $this->OutputCharsetCode;
    }

    function &convertString( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_conversion', false, 'String conversion' );
        $conversionFunction = $this->ConversionFunction;
        $tmp =& $this->$conversionFunction( $str );
        eZDebug::accumulatorStop( 'textcodec_conversion' );
        return $tmp;
    }

//     function &convertStringToUnicode( $str )
//     {
//         eZDebug::accumulatorStart( 'textcodec_unicode_conversion', false, 'String conversion to Unicode' );
//         $conversionFunction = $this->UnicodeConversionFunction;
//         $tmp =& $this->$conversionFunction( $str );
//         eZDebug::accumulatorStop( 'textcodec_unicode_conversion' );
//         return $tmp;
//     }

    function strlen( $str )
    {
        $strlenFunction = $this->StrlenFunction;
        return $this->$strlenFunction( $str );
    }


//         'convertNone';
//         'convertUTF8ToUnicode';
//         'convertCodepageToUnicode';
//         'convertCodepage';
//         'convertCodepageMapper';
//         'convertCodepageRev';
//         'convertUnicodeToUTF8';
//         'convertUnicodeToCodepage';

    /*!
     \return an empty array since no conversion is possible.
    */
    function &convertNoneToUnicode( $str )
    {
        return array();
    }

    function &convertCodepageToUnicode( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_codepage_unicode', false, 'String conversion w/ codepage to Unicode' );
        $tmp = $this->Codepage->convertStringToUnicode( $str );
        eZDebug::accumulatorStop( 'textcodec_codepage_unicode' );
        return $tmp;
    }

    function &convertUTF8ToUnicode( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_utf8_unicode', false, 'String conversion w/ UTF-8 to Unicode' );
        $tmp = eZUTF8Codec::convertStringToUnicode( $str );
        eZDebug::accumulatorStop( 'textcodec_utf8_unicode' );
        return $tmp;
    }

    function &convertUnicodeToCodepage( $unicodeValues )
    {
        eZDebug::accumulatorStart( 'textcodec_unicode_codepage', false, 'String conversion w/ Unicode to codepage' );
        $tmp = $this->Codepage->convertUnicodeToString( $unicodeValues );
        eZDebug::accumulatorStop( 'textcodec_unicode_codepage' );
        return $tmp;
    }

    function &convertUnicodeToUTF8( $unicodeValues )
    {
        eZDebug::accumulatorStart( 'textcodec_unicode_utf8', false, 'String conversion w/ Unicode to UTF8' );
        $tmp = eZUTF8Codec::convertUnicodeToString( $unicodeValues );
        eZDebug::accumulatorStop( 'textcodec_unicode_utf8' );
        return $tmp;
    }

    function &convertNone( $str )
    {
        return $str;
    }

    function &convertCodepage( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_codepage', false, 'String conversion w/ codepage' );
        $tmp =& $this->Codepage->convertString( $str );
        eZDebug::accumulatorStop( 'textcodec_codepage', false, 'String conversion w/ codepage' );
        return $tmp;
    }

    function &convertCodepageRev( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_codepage_rev', false, 'String conversion w/ codepage reverse' );
        $tmp =& $this->Codepage->convertStringFromUTF8( $str );
        eZDebug::accumulatorStop( 'textcodec_codepage_rev', false, 'String conversion w/ codepage reverse' );
        return $tmp;
    }

    function &convertCodepageMapper( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_codepage_mapper', false, 'String conversion w/ codepage mapper' );
        $tmp =& $this->CodepageMapper->convertString( $str );
        eZDebug::accumulatorStop( 'textcodec_codepage_mapper', false, 'String conversion w/ codepage mapper' );
        return $tmp;
    }

    function &convertMBString( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_mbstring', false, 'String conversion w/ mbstring' );
        $tmp =& $this->MBStringMapper->convertString( $str );
        eZDebug::accumulatorStop( 'textcodec_mbstring', false, 'String conversion w/ mbstring' );
        return $tmp;
    }

    function strlenNone( $str )
    {
        return strlen( $str );
    }

    function strlenUnicode( $unicodeValues )
    {
        return count( $unicodeValues );
    }

    function strlenCodepage( $str )
    {
        return $this->Codepage->strlen( $str );
    }

    function strlenCodepageRev( $str )
    {
        return $this->Codepage->strlenFromUTF8( $str );
    }

    function strlenCodepageMapper( $str )
    {
        return $this->CodepageMapper->strlen( $str );
    }

    function strlenMBString( $str )
    {
        return $this->MBStringMapper->strlen( $str );
    }

    function &instance( $inputCharsetCode, $outputCharsetCode = false )
    {
        if ( $inputCharsetCode === false )
            $inputCharsetCode = eZTextCodec::internalCharset();
        if ( $outputCharsetCode === false )
            $outputCharsetCode = eZTextCodec::internalCharset();
        $realInputCharsetCode = eZCharsetInfo::realCharsetCode( $inputCharsetCode );
        $realOutputCharsetCode = eZCharsetInfo::realCharsetCode( $outputCharsetCode );
        $codec =& $GLOBALS["eZTextCodec-$realInputCharsetCode-$realOutputCharsetCode"];
        if ( get_class( $codec ) != "eztextcodec" )
        {
            $codec = new eZTextCodec( $inputCharsetCode, $outputCharsetCode );
        }
        return $codec;
    }

    /*!
     \static
     Initializes the eZTextCodec settings to the ones in the array \a $settings.
     \sa internalCharset, httpCharset.
    */
    function updateSettings( $settings )
    {
        $GLOBALS['eZTextCodecInternalCharset'] = $settings['internal-charset'];
        $GLOBALS['eZTextCodecHTTPCharset'] = $settings['http-charset'];
        $GLOBALS['eZTextCodecMBStringExtension'] = $settings['mbstring-extension'];
    }

    /*!
     \static
     \return the charset which is used internally,
     this is the charset which all external files and resources are converted to.
     \note will return iso-8859-1 if eZTextCodec has been updated with proper settings.
    */
    function internalCharset()
    {
        if ( !isset( $GLOBALS['eZTextCodecInternalCharset'] ) )
            $charsetCode = 'iso-8859-1';
        else
            $charsetCode = $GLOBALS['eZTextCodecInternalCharset'];
        $charset = eZCharsetInfo::realCharsetCode( $charsetCode );
        return $charset;
    }

    /*!
     \static
     \return a charset value which can be used in HTTP headers.
     \note Will return the internalCharset() if not http charset is set.
    */
    function httpCharset()
    {
        $charset = '';
        if ( isset( $GLOBALS['eZTextCodecHTTPCharset'] ) )
            $charset = $GLOBALS['eZTextCodecHTTPCharset'];
        if ( $charset == '' )
            $charset = eZTextCodec::internalCharset();
        else
            $charset = eZCharsetInfo::realCharsetCode( $charset );
        return $charset;
    }
}

?>

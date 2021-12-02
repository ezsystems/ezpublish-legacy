<?php
/**
 * File containing the eZTextCodec class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*! \defgroup eZI18N Internationalization */

/*!
  \class eZTextCodec eztextcodec.php
  \ingroup eZI18N
  \brief Handles conversion from one charset to another

  Supports <a href="http://www.ietf.org/rfc/rfc2279.txt">utf8</a> encoding/decoding

*/

class eZTextCodec
{
    /**
     * @param string $inputCharsetCode
     * @param string $outputCharsetCode
     * @param string $realInputCharsetCode
     * @param string $realOutputCharsetCode
     * @param string $inputEncoding
     * @param string $outputEncoding
     */
    public function __construct( $inputCharsetCode, $outputCharsetCode,
                          $realInputCharsetCode, $realOutputCharsetCode,
                          $inputEncoding, $outputEncoding )
    {
        $this->RequestedInputCharsetCode = $inputCharsetCode;
        $this->RequestedOutputCharsetCode = $outputCharsetCode;
        $this->InputCharsetCode = $realInputCharsetCode;
        $this->OutputCharsetCode = $realOutputCharsetCode;
        $this->InputCharacterEncodingScheme = $inputEncoding;
        $this->OutputCharacterEncodingScheme = $outputEncoding;

        // Map for conversion functions using encoding functions
        $encodingConvertMap = array();
        $encodingConvertInitMap = array();
        $encodingStrlenMap = array();

        $encodingStrlenMap['unicode'] = 'strlenUnicode';
        $encodingStrlenMap['utf-8'] = 'strlenUTF8';
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

        // NOTE:
        // The method eZMBStringMapper::charsetList() hash been copied and inlined here
        // Any modification must be reflected in the method
        $mbStringCharsets =& $GLOBALS["eZMBCharsetList"];
        if ( !is_array( $mbStringCharsets ) )
        {
            $charsetList = array( "ucs-4", "ucs-4be", "ucs-4le", "ucs-2", "ucs-2be", "ucs-2le", "utf-32", "utf-32be", "utf-32le", "utf-16",
                                  "utf-16be", "utf-16le", "utf-8", "utf-7", "ascii", "euc-jp", "sjis", "eucjp-win", "sjis-win", "iso-2022-jp", "jis",
                                  "iso-8859-1", "iso-8859-2", "iso-8859-3", "iso-8859-4", "iso-8859-5", "iso-8859-6", "iso-8859-7", "iso-8859-8",
                                  "iso-8859-9", "iso-8859-10", "iso-8859-13", "iso-8859-14", "iso-8859-15", "byte2be", "byte2le", "byte4be",
                                  "byte4le", "base64", "7bit", "8bit", "utf7-imap" );
            $mbStringCharsets = array();
            foreach ( $charsetList as $charset )
            {
                $mbStringCharsets[$charset] = $charset;
            }
        }

        // Is to true if the charsets are the same and they have singlebyte encoding
        $isSinglebyteSame = false;
        $isSame = false;

        // First detect conversion type
        if ( $this->InputCharsetCode == $this->OutputCharsetCode ) // Direct match, no conversion
        {
            $conversionFunction = $noneConversionFunction;
            $encodingConvertInitFunction = 'initializeInputCodepage';
            $inpenc = $this->InputCharacterEncodingScheme;
            if ( $inpenc == 'singlebyte' )
            {
                $isSinglebyteSame = true;
            }
            $isSame = true;
        }
        else if ( isset( $mbStringCharsets[$this->InputCharsetCode] ) and
                  isset( $mbStringCharsets[$this->OutputCharsetCode] ) ) // Use MBString for converting if charsets supported
        {
            // NOTE:
            // The mbstringmapper object is no longer needed since all functionality is inlined
//             $this->MBStringMapper = eZMBStringMapper::instance( $this->InputCharsetCode,
//                                                                 $this->OutputCharsetCode );
            $conversionFunction = "convertMBString";
            $strlenFunction = "strlenMBString";
            $encodingConvertInitFunction = false;
        }
        else // See if we support encoding scheme and codepage
        {
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
            if ( $isSinglebyteSame )
            {
                $strlenFunction = 'strlenNone';
            }
            else if ( isset( $mbStringCharsets[$this->InputCharsetCode] ) )
            {
                $strlenFunction = 'strlenMBString';
            }
            else if ( isset( $encodingStrlenMap[$inpenc] ) )
            {
                $strlenFunction = $encodingStrlenMap[$inpenc];
                if ( $inpenc == 'utf-8')
                {
                }
            }
        }

        if ( !$isSame and
             $conversionFunction and
             $strlenFunction )
        {
            $this->initializeConversionFunction( $encodingConvertInitMap, $encodingConvertInitFunction );
        }
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
            {
                $initFunction = $encodingConvertInitFunction;
            }
        }
        else if ( isset( $encodingConvertInitMap[$inpenc][$outenc] ) )
        {
            $initFunction = $encodingConvertInitMap[$inpenc][$outenc];
        }
        if ( $initFunction )
        {
            $this->$initFunction();
        }
    }

    function initializeCodepageMapper()
    {
        $this->CodepageMapper = eZCodePageMapper::instance( $this->InputCharsetCode,
                                                             $this->OutputCharsetCode );
    }

    function initializeInputCodepage()
    {
        $this->Codepage = eZCodePage::instance( $this->InputCharsetCode );
    }

    function initializeOutputCodepage()
    {
        $this->Codepage = eZCodePage::instance( $this->OutputCharsetCode );
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
    }

    function useMBString()
    {
        return true;
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

    function convertString( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_conversion', false, 'String conversion' );
        //eZDebug::writeDebug( $this->ConversionFunction, 'conversion function' );
        $conversionFunction = $this->ConversionFunction;
        $tmp = $this->$conversionFunction( $str );
        eZDebug::accumulatorStop( 'textcodec_conversion' );
        return $tmp;
    }

    function strlen( $str )
    {
        $strlenFunction = $this->StrlenFunction;
        return $this->$strlenFunction( $str );
    }

    /*!
     \return an empty array since no conversion is possible.
    */
    function convertNoneToUnicode( $str )
    {
        return array();
    }

    function convertCodepageToUnicode( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_codepage_unicode', false, 'String conversion w/ codepage to Unicode' );
        $tmp = $this->Codepage->convertStringToUnicode( $str );
        eZDebug::accumulatorStop( 'textcodec_codepage_unicode' );
        return $tmp;
    }

    function convertUTF8ToUnicode( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_utf8_unicode', false, 'String conversion w/ UTF-8 to Unicode' );
        $tmp = eZUTF8Codec::convertStringToUnicode( $str );
        eZDebug::accumulatorStop( 'textcodec_utf8_unicode' );
        return $tmp;
    }

    function convertUnicodeToCodepage( $unicodeValues )
    {
        eZDebug::accumulatorStart( 'textcodec_unicode_codepage', false, 'String conversion w/ Unicode to codepage' );
        $tmp = $this->Codepage->convertUnicodeToString( $unicodeValues );
        eZDebug::accumulatorStop( 'textcodec_unicode_codepage' );
        return $tmp;
    }

    function convertUnicodeToUTF8( $unicodeValues )
    {
        eZDebug::accumulatorStart( 'textcodec_unicode_utf8', false, 'String conversion w/ Unicode to UTF8' );
        $tmp = eZUTF8Codec::convertUnicodeToString( $unicodeValues );
        eZDebug::accumulatorStop( 'textcodec_unicode_utf8' );
        return $tmp;
    }

    function convertNone( $str )
    {
        return $str;
    }

    function convertCodepage( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_codepage', false, 'String conversion w/ codepage' );
        $tmp = $this->Codepage->convertString( $str );
        eZDebug::accumulatorStop( 'textcodec_codepage' );
        return $tmp;
    }

    function convertCodepageRev( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_codepage_rev', false, 'String conversion w/ codepage reverse' );
        $tmp = $this->Codepage->convertStringFromUTF8( $str );
        eZDebug::accumulatorStop( 'textcodec_codepage_rev' );
        return $tmp;
    }

    function convertCodepageMapper( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_codepage_mapper', false, 'String conversion w/ codepage mapper' );
        $tmp = $this->CodepageMapper->convertString( $str );
        eZDebug::accumulatorStop( 'textcodec_codepage_mapper' );
        return $tmp;
    }

    function convertMBString( $str )
    {
        eZDebug::accumulatorStart( 'textcodec_mbstring', false, 'String conversion w/ mbstring' );
//        $tmp = $this->MBStringMapper->convertString( $str );
        // NOTE:
        // Uses the mbstring function directly instead of going trough the class
        $tmp = mb_convert_encoding( $str, $this->OutputCharsetCode, $this->InputCharsetCode );
        eZDebug::accumulatorStop( 'textcodec_mbstring' );
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

    function strlenUTF8( $str )
    {
        $utf8_codec = eZUTF8Codec::instance();
        return $utf8_codec->strlen( $str );
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
//        return $this->MBStringMapper->strlen( $str );
        // NOTE:
        // Uses the mbstring function directly instead of going trough the class
        return mb_strlen( $str, $this->InputCharsetCode );
    }

    /**
     * Returns a shared instance of the eZTextCodec pr the
     * $inputCharsetCode and $outputCharsetCode params.
     *
     * @param string|false $inputCharsetCode Uses {@link eZTextCodec::internalCharset()} if false
     * @param string|false $outputCharsetCode Uses {@link eZTextCodec::internalCharset()} if false
     * @param bool $alwaysReturn
     * @return eZTextCodec|null Returns null if $alwaysReturn is false and text codec is not needed for
     *         current $inputCharsetCode and $outputCharsetCode.
     */
    static function instance( $inputCharsetCode, $outputCharsetCode = false, $alwaysReturn = true )
    {
        if ( $inputCharsetCode === false or $outputCharsetCode === false )
        {
            if ( isset( $GLOBALS['eZTextCodecInternalCharsetReal'] ) )
            {
                $internalCharset = $GLOBALS['eZTextCodecInternalCharsetReal'];
            }
            else
            {
                $internalCharset = eZTextCodec::internalCharset();
            }
        }

        if ( $inputCharsetCode === false )
        {
            $realInputCharsetCode = $inputCharsetCode = $internalCharset;
        }
        else
        {
            $realInputCharsetCode = eZCharsetInfo::realCharsetCode( $inputCharsetCode );
        }

        if ( $outputCharsetCode === false )
        {
            $realOutputCharsetCode = $outputCharsetCode = $internalCharset;
        }
        else
        {
            $realOutputCharsetCode = eZCharsetInfo::realCharsetCode( $outputCharsetCode );
        }

        $check =& $GLOBALS["eZTextCodecCharsetCheck"]["$realInputCharsetCode-$realOutputCharsetCode"];
        if ( !$alwaysReturn and isset( $check ) and !$check )
        {
            $check = null;
            return $check;
        }
        if ( isset( $check ) and is_object( $check ) )
        {
            return $check;
        }

        if ( !$realInputCharsetCode )
        {
            $realInputCharsetCode = eZCharsetInfo::realCharsetCode( $inputCharsetCode );
        }
        if ( !$realOutputCharsetCode )
        {
            $realOutputCharsetCode = eZCharsetInfo::realCharsetCode( $outputCharsetCode );
        }
        $inputEncoding = eZCharsetInfo::characterEncodingScheme( $realInputCharsetCode, true );
        $outputEncoding = eZCharsetInfo::characterEncodingScheme( $realOutputCharsetCode, true );
        if ( !$alwaysReturn and
             $inputEncoding == 'singlebyte' and
             $inputEncoding == $outputEncoding and
             $realInputCharsetCode == $realOutputCharsetCode )
        {
            $check = null;
            return $check;
        }

        $globalsKey = "eZTextCodec-$realInputCharsetCode-$realOutputCharsetCode";
        if ( !isset( $GLOBALS[$globalsKey] ) ||
             !( $GLOBALS[$globalsKey] instanceof eZTextCodec ) )
        {
            $GLOBALS[$globalsKey] = new eZTextCodec( $inputCharsetCode, $outputCharsetCode,
                                                     $realInputCharsetCode, $realOutputCharsetCode,
                                                     $inputEncoding, $outputEncoding );
        }

        $check = $GLOBALS[$globalsKey];
        return $GLOBALS[$globalsKey];
    }

    /*!
     \static
     Initializes the eZTextCodec settings to the ones in the array \a $settings.
     \sa internalCharset, httpCharset.
    */
    static function updateSettings( $settings )
    {
        unset( $GLOBALS['eZTextCodecInternalCharsetReal'] );
        unset( $GLOBALS['eZTextCodecHTTPCharsetReal'] );
        unset( $GLOBALS['eZTextCodecCharsetCheck'] );
        $GLOBALS['eZTextCodecInternalCharset'] = $settings['internal-charset'];
        $GLOBALS['eZTextCodecHTTPCharset'] = $settings['http-charset'];
        @mb_internal_encoding( $settings['internal-charset'] );
    }

    /*!
     \static
     \return the charset which is used internally,
     this is the charset which all external files and resources are converted to.
     \note will return iso-8859-1 if eZTextCodec has been updated with proper settings.
    */
    static function internalCharset()
    {
        $realCharset =& $GLOBALS['eZTextCodecInternalCharsetReal'];
        if ( !isset( $realCharset ) )
        {
            if ( !isset( $GLOBALS['eZTextCodecInternalCharset'] ) )
            {
                $i18n = eZINI::instance( 'i18n.ini', '', false );
                $charsetCode = $i18n->variable( 'CharacterSettings', 'Charset' );
            }
            else
                $charsetCode = $GLOBALS['eZTextCodecInternalCharset'];
            $realCharset = eZCharsetInfo::realCharsetCode( $charsetCode );
        }
        return $realCharset;
    }

    /*!
     \static
     \return a charset value which can be used in HTTP headers.
     \note Will return the internalCharset() if not http charset is set.
    */
    static function httpCharset()
    {
        $realCharset =& $GLOBALS['eZTextCodecHTTPCharsetReal'];
        if ( !isset( $realCharset ) )
        {
            $charset = '';
            if ( isset( $GLOBALS['eZTextCodecHTTPCharset'] ) )
                $charset = $GLOBALS['eZTextCodecHTTPCharset'];
            if ( $charset == '' )
            {
                if ( isset( $GLOBALS['eZTextCodecInternalCharsetReal'] ) )
                    $realCharset = $GLOBALS['eZTextCodecInternalCharsetReal'];
                else
                    $realCharset = eZTextCodec::internalCharset();
            }
            else
            {
                $realCharset = eZCharsetInfo::realCharsetCode( $charset );
            }
        }
        return $realCharset;
    }
}

?>

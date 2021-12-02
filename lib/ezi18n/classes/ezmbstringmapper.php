<?php
/**
 * File containing the eZMBStringMapper class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
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
    /**
     * Constructor
     *
     * @param string $input_charset_code
     * @param string $output_charset_code
     */
    public function __construct( $input_charset_code, $output_charset_code )
    {
        $this->RequestedInputCharsetCode = $input_charset_code;
        $this->InputCharsetCode = eZCharsetInfo::realCharsetCode( $input_charset_code );
        $this->RequestedOutputCharsetCode = $output_charset_code;
        $this->OutputCharsetCode = eZCharsetInfo::realCharsetCode( $output_charset_code );
        if ( !$this->isCharsetSupported( $input_charset_code ) )
        {
            eZDebug::writeError( "Input charset $input_charset_code not supported", "eZMBStringMapper" );
        }
        else if ( !$this->isCharsetSupported( $output_charset_code ) )
        {
            eZDebug::writeError( "Output charset $output_charset_code not supported", "eZMBStringMapper" );
        }
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
        return mb_substitute_character();
    }

    function setSubstituteCharacter( $char )
    {
        mb_substitute_character( $char );
    }

    function convertString( $str )
    {
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

    /**
     * Returns a shared instance of the eZMBStringMapper pr the $input_charset_code
     * and $output_charset_code params.
     *
     * @param string $input_charset_code
     * @param string $output_charset_code
     * @return eZMBStringMapper
     */
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

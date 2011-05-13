<?php
/**
 * File containing the eZCodePageCodec class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZCodePageCodec ezcodepagecodec.php
  \ingroup eZI18N
  \brief The class eZCodePageCodec does

*/

class eZCodePageCodec extends eZTextCodec
{
    /*!
     Initializes the codepage codec with $name
    */
    function eZCodePageCodec( $name )
    {
        $this->eZTextCodec( $name );
        $this->CodePage = false;
    }

    function toUnicode( $str )
    {
        $ustr = "";
        if ( !is_string( $str ) or
             !$this->isValid() )
            return $ustr;
        $len = strlen( $str );
        for ( $i = 0; $i < $len; ++$i )
        {
//             $char_code = $this->CodePage->
//             $ustr .= $this->toUtf8( $char_code );
            $char = $str[$i];
            $ustr .= $this->CodePage->charToUtf8( $char );
        }
        return $ustr;
    }

    function fromUnicode( $str )
    {
        $ustr = "";
        if ( !is_string( $str ) or
             !$this->isValid() )
            return $ustr;
        $utf8_codec = eZUTF8Codec::instance();
        $len = strlen( $str );
        for ( $i = 0; $i < $len; )
        {
            $char_code = $utf8_codec->fromUtf8( $str, $i, $l );
            if ( $char_code !== false )
            {
                $i += $l;
                $ustr .= chr( $this->CodePage->unicodeToChar( $char_code ) );
            }
            else
                ++$i;
        }
        return $ustr;
    }

    /*!
     Returns true if a codepage has been set.
    */
    function isValid()
    {
        return $this->CodePage instanceof eZCodePage;
    }

    /*!
     Returns the codepage.
    */
    function codePage()
    {
        return $this->CodePage;
    }

    /*!
     Sets the current codepage which is used for utf8/text translation.
    */
    function setCodePage( $cp )
    {
        $this->CodePage = $cp;
    }

    public $CodePage;
}

?>

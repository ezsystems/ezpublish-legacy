<?php
//
// Definition of eZCodePageCodec class
//
// Created on: <07-Mar-2002 10:19:23 amos>
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

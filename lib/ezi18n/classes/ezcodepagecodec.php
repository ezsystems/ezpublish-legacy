<?php
//
// Definition of eZCodePageCodec class
//
// Created on: <07-Mar-2002 10:19:23 amos>
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
  \class eZCodePageCodec ezcodepagecodec.php
  \ingroup eZI18N
  \brief The class eZCodePageCodec does

*/

include_once( "lib/ezi18n/classes/eztextcodec.php" );
include_once( "lib/ezi18n/classes/ezutf8codec.php" );

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

    function &toUnicode( $str )
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

    function &fromUnicode( $str )
    {
        $ustr = "";
        if ( !is_string( $str ) or
             !$this->isValid() )
            return $ustr;
        $utf8_codec =& eZUTF8Codec::instance();
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
        return get_class( $this->CodePage ) == "ezcodepage";
    }

    /*!
     Returns the codepage.
    */
    function &codePage()
    {
        return $this->CodePage;
    }

    /*!
     Sets the current codepage which is used for utf8/text translation.
    */
    function setCodePage( &$cp )
    {
        $this->CodePage =& $cp;
    }

    var $CodePage;
}

?>

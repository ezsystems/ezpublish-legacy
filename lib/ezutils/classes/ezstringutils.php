<?php
//
// Definition of eZStringUtils class
//
// Created on: <29-Sep-2006 21:35:51 sp>
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

/*! \file ezstringutils.php
*/

/*!
  \class eZStringUtils ezstringutils.php
  \brief The class eZStringUtils does

*/

class eZStringUtils
{
    /*!
     Constructor
    */
    function eZStringUtils()
    {
    }

    function  explodeStr( $str, $delimiter = '|' )
    {
        $offset = 0;
        $array = array();

        while( ( $pos = strpos( $str, $delimiter, $offset )  ) !== false )
        {
            $strPart = substr( $str, 0, $pos );
            if ( preg_match( '/(\\\\+)$/', $strPart, $matches ) )
            {
                if ( strlen( $matches[0] ) % 2 !== 0 )
                {
                    $offset = $pos+1;
                    continue;
                }
            }
            $array[] = str_replace( '\\\\', '\\', str_replace("\\$delimiter", $delimiter, $strPart ) );
            $str = substr( $str, $pos + 1 );
            $offset = 0;

        }
        $array[] = str_replace( '\\\\', '\\', str_replace("\\$delimiter", $delimiter, $str ) );
        return $array;
    }

    function implodeStr( $values, $delimiter = '|' )
    {
        $str = '';
        while ( list( $key, $value ) = each( $values ) )
        {
            $values[$key] = str_replace( $delimiter, "\\$delimiter", str_replace( '\\', '\\\\', $value ) );
        }
        return implode( $delimiter, $values );
    }


}

?>

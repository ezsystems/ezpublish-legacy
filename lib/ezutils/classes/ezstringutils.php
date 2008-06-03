<?php
//
// Definition of eZStringUtils class
//
// Created on: <29-Sep-2006 21:35:51 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

    static function  explodeStr( $str, $delimiter = '|' )
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

    static function implodeStr( $values, $delimiter = '|' )
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

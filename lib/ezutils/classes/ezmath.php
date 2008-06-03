<?php
//
// Created on: <16-Mar-2003 17:56:32 kk>
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

/*! \file eztemplateautoload.php
*/

/*!
  \defgroup eZMath eZ Math library
*/

/*!
  \class eZMath ezmath.php
  \brief eZMath provide a simple math library for common math operations
*/

class eZMath
{
    /*!
     Constructor
    */
    function eZMath()
    {
    }

    /*!
     \static

     Normalize RGB color array to 0..1 range

     \param array to normalize

     \return normalized array
    */
    static function normalizeColorArray( $array )
    {
        foreach ( array_keys( $array ) as $key )
        {
            $array[$key] = (float)$array[$key]/256;
        }

        return $array;
    }

    /*!
     \static

     Convert RGB to CMYK, Normalized values, between 0 and 1

     \param RGB array
     \return CMYK array
    */
    static function rgbToCMYK( $rgbArray )
    {
        $cya = 1 - min( 1, max( (float)$rgbArray['r'], 0 ) );
        $mag = 1 - min( 1, max( (float)$rgbArray['g'], 0 ) );
        $yel = 1 - min( 1, max( (float)$rgbArray['b'], 0 ) );

        $min = min( $cya, $mag, $yel );
        if ( 1 - $min == 0 )
        {
            return array( 'c' => 1,
                          'm' => 1,
                          'y' => 1,
                          'k' => 0 );
        }

        return array( 'c' => ( $cya - $min ) / ( 1 - $min ),
                      'm' => ( $mag - $min ) / ( 1 - $min ),
                      'y' => ( $yel - $min ) / ( 1 - $min ),
                      'k' => $min );
    }

    /*!
     \static

     Convert rgb to CMYK

     \param R
     \param B
     \param G

     \return CMYK return array
    */
    static function rgbToCMYK2( $r, $g, $b )
    {
        return eZMath::rgbToCMYK( array( 'r' => $r,
                                         'g' => $g,
                                         'b' => $b ) );
    }
}
?>

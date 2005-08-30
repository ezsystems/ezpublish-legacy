<?php
//
// Created on: <16-Mar-2003 17:56:32 kk>
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

     \param array to normalize (changes values in array)

     \return normalized array
    */
    function &normalizeColorArray( &$array )
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
    function rgbToCMYK( $rgbArray )
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
    function rgbToCMYK2( $r, $g, $b )
    {
        return eZMath::rgbToCMYK( array( 'r' => $r,
                                         'g' => $g,
                                         'b' => $b ) );
	}
}
?>

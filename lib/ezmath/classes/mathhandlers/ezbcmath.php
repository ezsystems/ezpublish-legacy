<?php
//
// Definition of eZBCMath class
//
// Created on: <04-Nov-2005 12:26:52 dl>
//
// Copyright (C) 1999-2006 eZ systems as. All rights reserved.
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

/*! \file ezbcmath.php
*/

/*!
  \class eZBCMath ezbcmath.php
  \brief Handles calculation using bcmath library.
*/

include_once( 'lib/ezmath/classes/mathhandlers/ezphpmath.php' );

define( 'EZ_BCMATH_DEFAULT_SCALE', 10 );

class eZBCMath extends eZPHPMath
{
    function eZBCMath( $params = array () )
    {
        if( isset( $params['scale'] ) && is_numeric( $params['scale'] ) )
            $this->setScale( $params['scale'] );
        else
            $this->setScale( EZ_BCMATH_DEFAULT_SCALE );
    }

    function scale()
    {
        return $this->Scale;
    }

    function setScale( $scale )
    {
        $this->Scale = $scale;
    }

    function add( $a, $b )
    {
        return ( bcadd( $a, $b, $this->Scale ) );
    }

    function sub( $a, $b )
    {
        return ( bcsub( $a, $b, $this->Scale ) );
    }

    function mul( $a, $b )
    {
        return ( bcmul( $a, $b, $this->Scale ) );
    }

    function div( $a, $b )
    {
        return ( bcdiv( $a, $b, $this->Scale ) );
    }

    function pow( $base, $exp )
    {
        return ( bcpow( $base, $exp, $this->Scale ) );
    }

    function round( $value, $precision, $target )
    {
        $result = $value;
        $fractPart = $this->fractval( $value, $precision + 1 );
        if ( strlen( $fractPart ) > $precision )
        {
            $lastDigit = (int)substr( $fractPart, -1, 1 );
            $fractPart = substr( $fractPart, 0, $precision );
            if ( $lastDigit >= 5 )
                $fractPart = $this->add( $fractPart, 1 );

            $fractPart = $this->div( $fractPart, $this->pow( 10, $precision ) );

            $result = $this->add( $this->intval( $value ), $fractPart );
            $result = $this->adjustFractPart( $result, $precision, $target );
        }

        return $result;
    }


    /// \privatesection
    var $Scale;
};

?>
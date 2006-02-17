<?php
//
// Definition of eZPHPMath class
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

/*! \file ezphpmath.php
*/

/*!
  \class eZPHPMath ezphpmath.php
  \brief Handles calculation using standard php's functions.
*/

class eZPHPMath
{
    function eZPHPMath( $params = array() )
    {
    }

    function &create( $type, $params = array() )
    {
        $impl = false;
        $filename = 'lib/ezmath/classes/mathhandlers/' . $type . '.php';

        if ( file_exists( $filename ) )
        {
            include_once( $filename );
            $impl = new $type( $params );
        }

        return $impl;
    }

    function add( $a, $b )
    {
        return ( (float)$a + (float)$b );
    }

    function sub( $a, $b )
    {
        return ( (float)$a - (float)$b );
    }

    function mul( $a, $b )
    {
        $c = ( (float)$a * (float)$b );
        return ( (float)$a * (float)$b );
    }

    function div( $a, $b )
    {
        return ( (float)$a / (float)$b );
    }

    function pow( $base, $exp )
    {
        return ( pow( $base, $exp ) );
    }

    function round( $value, $precision, $target )
    {
        $result = round( $value, $precision );
        $result = $this->adjustFractPart( $result, $precision, $target );
        return $result;
    }

    function ceil( $value, $precision, $target )
    {
        $fractStr = $this->fractval( $value );

        $fractPart = (int)substr( $fractStr, 0, $precision );

        // actual ceiling
        if ( strlen( $fractStr ) > $precision )
            $fractPart += 1;

        // create resulting value
        $fractPart = $this->div( $fractPart, $this->pow( 10, $precision ) );

        $result = $this->add( $this->intval( $value ), $fractPart );
        $result = $this->adjustFractPart( $result, $precision, $target );

        return $result;
    }

    function floor( $value, $precision, $target )
    {
        $fractPart = $this->fractval( $value, $precision );
        $fractPart = $this->div( $fractPart, $this->pow( 10, $precision ) );

        $result = $this->add( $this->intval( $value ), $fractPart );
        $result = $this->adjustFractPart( $result, $precision, $target );

        return $result;
    }


    function adjustFractPart( $number, $precision, $target )
    {
        if ( is_numeric( $target ) )
        {
            $target = substr( $target, 0, $precision );
            $targetPrecision = strlen( $target );
            $target = $this->div( $target, $this->pow( 10, $precision ) );

            $intPart = $this->intval( $number );
            $fractPart = $this->fractval( $number, $precision - $targetPrecision );
            $fractPart = $this->div( $fractPart, $this->pow( 10, $this->sub( $precision, $targetPrecision ) ) );
            $fractPart = $this->add( $fractPart, $target );

            $number = $this->add( $intPart, $fractPart );
        }

        return $number;
    }

    function intval( $number )
    {
        $intPart = 0;
        $pos = strpos( $number, '.' );
        if ( $pos !== false )
            $intPart = substr( $number, 0, $pos );
        else
            $intPart = $number;

        return $intPart;
    }

    function fractval( $number, $precision = false )
    {
        $fractPart = 0;
        $pos = strpos( $number, '.' );
        if ( $pos !== false )
        {
            if ( $precision === false )
                $fractPart = substr( $number, $pos + 1 );
            else
                $fractPart = substr( $number, $pos + 1, $precision );
        }

        return $fractPart;
    }
}

?>
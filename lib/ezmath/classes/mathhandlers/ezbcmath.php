<?php
/**
 * File containing the eZBCMath class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZBCMath ezbcmath.php
  \brief Handles calculation using bcmath library.
*/

class eZBCMath extends eZPHPMath
{
    const DEFAULT_SCALE = 10;

    function eZBCMath( $params = array () )
    {
        if( isset( $params['scale'] ) && is_numeric( $params['scale'] ) )
            $this->setScale( $params['scale'] );
        else
            $this->setScale( self::DEFAULT_SCALE );
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

    function ceil( $value, $precision, $target )
    {
        $result = eZPHPMath::ceil( $value, $precision, $target );
        $result = rtrim( $result, '0' );
        $result = rtrim( $result, '.' );
        return $result;
    }

    function floor( $value, $precision, $target )
    {
        $result = eZPHPMath::floor( $value, $precision, $target );
        $result = rtrim( $result, '0' );
        $result = rtrim( $result, '.' );
        return $result;
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

            $result = rtrim( $result, '0' );
            $result = rtrim( $result, '.' );
        }

        return $result;
    }


    /// \privatesection
    public $Scale;
}

?>

<?php
/**
 * File containing the eZPaymentLogger class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZPaymentLogger
*/

class eZPaymentLogger
{
    public function __construct( $fileName, $mode )
    {
        $this->file = fopen( $fileName, $mode );
    }

    static function CreateNew($fileName)
    {
        return new eZPaymentLogger( $fileName, "wt" );
    }

    static function CreateForAdd($fileName)
    {
        return new eZPaymentLogger( $fileName, "a+t" );
    }

    function writeString( $string, $label='' )
    {
        if( $this->file )
        {
            if ( is_object( $string ) || is_array( $string ) )
                $string = eZDebug::dumpVariable( $string );

            if( $label == '' )
                fputs( $this->file, $string."\r\n" );
            else
                fputs( $this->file, $label . ': ' . $string."\r\n" );
        }
    }

    function writeTimedString( $string, $label='' )
    {
        if( $this->file )
        {
            $time = $this->getTime();

            if ( is_object( $string ) || is_array( $string ) )
                $string = eZDebug::dumpVariable( $string );

            if( $label == '' )
                fputs( $this->file, $time. '  '. $string. "\n" );
            else
                fputs( $this->file, $time. '  '. $label. ': '. $string. "\n" );
        }
    }

    static function getTime()
    {
        $time = date( "d-m-Y H-i" );
        return $time;
    }

    public $file;
}
?>

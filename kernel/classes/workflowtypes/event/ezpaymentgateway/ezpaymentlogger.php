<?php
/**
 * File containing the eZPaymentLogger class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZPaymentLogger
*/

class eZPaymentLogger
{
    function eZPaymentLogger( $fileName, $mode )
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
        $time = strftime( "%d-%m-%Y %H-%M" );
        return $time;
    }

    public $file;
}
?>

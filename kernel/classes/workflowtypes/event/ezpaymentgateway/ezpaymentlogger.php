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
    /**
     * @var string
     */
    public $fileName;

    /**
     * @param $fileName
     */
    function eZPaymentLogger( $fileName )
    {
        // If $fileName is a filepath, strip the path elements
        $parts = explode( '/', $fileName );
        $this->fileName = array_pop( $parts );
    }

    /**
     * @deprecated
     * @param $fileName
     * @return eZPaymentLogger
     */
    static function CreateNew($fileName)
    {
        eZDebug::writeStrict( 'Method ' . __METHOD__ . ' has been deprecated. Use \'new eZPaymentLogger($fileName)\' instead.', 'Deprecation' );
        return new self( $fileName );
    }

    /**
     * @deprecated
     * @param string $fileName
     * @return eZPaymentLogger
     */
    static function CreateForAdd($fileName)
    {
        eZDebug::writeStrict( 'Method ' . __METHOD__ . ' has been deprecated. Use \'new eZPaymentLogger($fileName)\' instead.', 'Deprecation' );
        return new self( $fileName );
    }

    /**
     * @param string|array|object $string
     * @param string $label
     * @return bool
     */
    function writeString( $string, $label = '' )
    {
        if( $this->fileName )
        {
            if ( is_object( $string ) || is_array( $string ) )
            {
                $string = eZDebug::dumpVariable( $string );
            }

            if ( $label !== '' )
            {
                $string = "{$label}: {$string}";
            }

            eZLog::write( $string, $this->fileName );

            return true;
        }

        return false;
    }

    /**
     * @deprecated
     * @see writeString()
     * @param $string
     * @param string $label
     * @return bool
     */
    function writeTimedString( $string, $label='' )
    {
        eZDebug::writeStrict( 'Method ' . __METHOD__ . ' has been deprecated. Use eZPaymentLogger::writeString() instead.', 'Deprecation' );

        return $this->writeString( $string, $label );
    }

    /**
     * @deprecated
     * @return string
     */
    static function getTime()
    {
        eZDebug::writeStrict( 'Method ' . __METHOD__ . ' has been deprecated.', 'Deprecation' );
        $time = strftime( "%d-%m-%Y %H-%M" );
        return $time;
    }

    public $file;
}
?>

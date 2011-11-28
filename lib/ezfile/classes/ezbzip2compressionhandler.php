<?php
/**
 * File containing the eZBZIP2Handler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZBZIP2Handler ezbzip2handler.php
  \brief Handles files compressed with bzip2


NOTE: This is not done yet.
*/

class eZBZIP2Handler extends eZCompressionHandler
{
    /*!
     See eZCompressionHandler::eZCompressionHandler
    */
    function eZBZIP2Handler()
    {
        $this->eZCompressionHandler( 'BZIP2', 'bz2' );
    }

    function doOpen( $filename, $mode )
    {
    }

    function doClose()
    {
    }

    function doRead( $uncompressedLength = false )
    {
    }

    function doWrite( $data, $uncompressedLength = false )
    {
    }

    function doFlush()
    {
    }

    function compress( $source )
    {
    }

    function decompress( $source )
    {
    }

    function error()
    {
    }

    function errorString()
    {
    }

    function errorNumber()
    {
    }

    /// \privatesection
    public $WorkFactor;
    public $BlockSize;
    public $SmallDecompress;
}

?>

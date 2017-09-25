<?php
/**
 * File containing the eZCompressionHandler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZCompressionHandler ezcompressionhandler.php
  \brief Interface for file handlers using compression

  Generic interface for all file handlers using compression.

  This class introduces two new functions from the eZFileHandler base class,
  they are compress() and decompress() and are used for string based
  compression.

  <h1>Creating specific handlers</h1>

  The compressor handlers must inherit from this class and reimplement
  some virtual functions.

  For dealing with compressed strings the following functions must be reimplemented.
  compress() and decompress()

  The handlers must also implement the virtual functions defined in eZFileHandler.

*/

class eZCompressionHandler extends eZFileHandler
{
    /*!
     \pure
     Compress the \a $source string and return it as compressed data.
    */
    function compress( $source )
    {
    }

    /*!
     \pure
     Decompress the \a $source string containing compressed data and return it as a string.
    */
    function decompress( $source )
    {
    }
}

?>

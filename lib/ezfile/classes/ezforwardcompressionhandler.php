<?php
/**
 * File containing the eZForwardCompressionHandler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZForwardCompressionHandler ezgzipcompressionhandler.php
  \brief Handles files compressed with gzip

  This class is a wrapper of the eZGZIPZLIBCompressionHandler and
  eZGZIPShellCompressionHandler classes.
*/

class eZForwardCompressionHandler extends eZCompressionHandler
{
    public $ForwardHandler;
    /**
     * Constructor
     *
     * @param bool|string $handler
     * @param bool|string $name
     * @param string $identifier
     */
    public function __construct( &$handler, $name, $identifier )
    {
        $this->ForwardHandler =& $handler;
        parent::__construct( $name, $identifier );
    }

    /*!
     \return the current handler which all requests are forwarded to.
    */
    function &forwardHandler()
    {
        return $this->ForwardHandler;
    }

    function doOpen( $filename, $mode )
    {
        return $this->ForwardHandler->doOpen( $filename, $mode );
    }

    function doClose()
    {
        return $this->ForwardHandler->doClose();
    }

    function doRead( $uncompressedLength = false )
    {
        return $this->ForwardHandler->doRead( $uncompressedLength );
    }

    function doWrite( $data, $uncompressedLength = false )
    {
        return $this->ForwardHandler->doWrite( $data, $uncompressedLength );
    }

    function doFlush()
    {
        return $this->ForwardHandler->doFlush();
    }

    function doSeek( $offset, $whence )
    {
        return $this->ForwardHandler->doSeek( $offset, $whence );
    }

    function doRewind()
    {
        return $this->ForwardHandler->doRewind();
    }

    function doTell()
    {
        return $this->ForwardHandler->doTell();
    }

    function doEOF()
    {
        return $this->ForwardHandler->doEOF();
    }

    function doPasstrough( $closeFile = true )
    {
        return $this->ForwardHandler->doPasstrough( $closeFile );
    }

    function compress( $source )
    {
        return $this->ForwardHandler->compress( $source );
    }

    function decompress( $source )
    {
        return $this->ForwardHandler->decompress( $source );
    }

    function error()
    {
        return $this->ForwardHandler->error();
    }

    function errorString()
    {
        return $this->ForwardHandler->errorString();
    }

    function errorNumber()
    {
        return $this->ForwardHandler->errorNumber();
    }

    /*!
     Duplicates the forward compression handler by calling duplicate() on the handler
     which gets the forwarded requests and then creates a new eZForwardCompressionHandler.
    */
    function duplicate()
    {
        $forwardCopy = $this->ForwardHandler->duplicate();
        $copy = new eZForwardCompressionHandler( $forwardCopy, $this->name(), $this->identifier() );
        return $copy;
    }
}

?>

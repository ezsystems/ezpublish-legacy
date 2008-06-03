<?php
//
// Definition of eZForwardCompressionHandler class
//
// Created on: <13-Aug-2003 16:20:19 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezgzipcompressionhandler.php
*/

/*!
  \class eZForwardCompressionHandler ezgzipcompressionhandler.php
  \brief Handles files compressed with gzip

  This class is a wrapper of the eZGZIPZLIBCompressionHandler and
  eZGZIPShellCompressionHandler classes.
*/

//include_once( 'lib/ezfile/classes/ezcompressionhandler.php' );

class eZForwardCompressionHandler extends eZCompressionHandler
{
    /*!
     See eZCompressionHandler::eZCompressionHandler
    */
    function eZForwardCompressionHandler( &$handler,
                                          $name, $identifier )
    {
        $this->ForwardHandler =& $handler;
        $this->eZCompressionHandler( $name, $identifier );
    }

    /*!
     \return the current handler which all requests are forwarded to.
    */
    function &forwardHandler()
    {
        return $this->ForwardHandler;
    }

    /*!
     \reimp
    */
    function doOpen( $filename, $mode )
    {
        return $this->ForwardHandler->doOpen( $filename, $mode );
    }

    /*!
     \reimp
    */
    function doClose()
    {
        return $this->ForwardHandler->doClose();
    }

    /*!
     \reimp
    */
    function doRead( $uncompressedLength = false )
    {
        return $this->ForwardHandler->doRead( $uncompressedLength );
    }

    /*!
     \reimp
    */
    function doWrite( $data, $uncompressedLength = false )
    {
        return $this->ForwardHandler->doWrite( $data, $uncompressedLength );
    }

    /*!
     \reimp
    */
    function doFlush()
    {
        return $this->ForwardHandler->doFlush();
    }

    /*!
     \reimp
    */
    function doSeek( $offset, $whence )
    {
        return $this->ForwardHandler->doSeek( $offset, $whence );
    }

    /*!
     \reimp
    */
    function doRewind()
    {
        return $this->ForwardHandler->doRewind();
    }

    /*!
     \reimp
    */
    function doTell()
    {
        return $this->ForwardHandler->doTell();
    }

    /*!
     \reimp
    */
    function doEOF()
    {
        return $this->ForwardHandler->doEOF();
    }

    /*!
     \reimp
    */
    function doPasstrough( $closeFile = true )
    {
        return $this->ForwardHandler->doPasstrough( $closeFile );
    }

    /*!
     \reimp
    */
    function compress( $source )
    {
        return $this->ForwardHandler->compress( $source );
    }

    /*!
     \reimp
    */
    function decompress( $source )
    {
        return $this->ForwardHandler->decompress( $source );
    }

    /*!
     \reimp
    */
    function error()
    {
        return $this->ForwardHandler->error();
    }

    /*!
     \reimp
    */
    function errorString()
    {
        return $this->ForwardHandler->errorString();
    }

    /*!
     \reimp
    */
    function errorNumber()
    {
        return $this->ForwardHandler->errorNumber();
    }

    /*!
     \reimp
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

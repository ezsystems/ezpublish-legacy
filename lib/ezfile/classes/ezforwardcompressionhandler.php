<?php
//
// Definition of eZForwardCompressionHandler class
//
// Created on: <13-Aug-2003 16:20:19 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file ezgzipcompressionhandler.php
*/

/*!
  \class eZForwardCompressionHandler ezgzipcompressionhandler.php
  \brief Handles files compressed with gzip

  This class is a wrapper of the eZGZIPZLIBCompressionHandler and
  eZGZIPShellCompressionHandler classes.
*/

include_once( 'lib/ezfile/classes/ezcompressionhandler.php' );

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
    function doPasstrough()
    {
        return $this->ForwardHandler->doPasstrough();
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
    function &duplicate()
    {
        $forwardCopy =& $this->ForwardHandler->duplicate();
        $copy = new eZForwardCompressionHandler( $forwardCopy, $this->name(), $this->identifier() );
        return $copy;
    }
}

?>

<?php
//
// Definition of eZGZIPCompressionHandler class
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
  \class eZGZIPCompressionHandler ezgzipcompressionhandler.php
  \brief Handles files compressed with gzip

  This class is a wrapper of the eZGZIPZLIBCompressionHandler and
  eZGZIPShellCompressionHandler classes.

  Duplication of this handler is done by the eZForwardCompressionHandler class.
*/

include_once( 'lib/ezfile/classes/ezforwardcompressionhandler.php' );
include_once( 'lib/ezfile/classes/ezgzipzlibcompressionhandler.php' );
include_once( 'lib/ezfile/classes/ezgzipshellcompressionhandler.php' );
include_once( 'lib/ezfile/classes/eznocompressionhandler.php' );

class eZGZIPCompressionHandler extends eZForwardCompressionHandler
{
    /*!
     See eZCompressionHandler::eZCompressionHandler and eZForwardCompressionHandler::eZForwardCompressionHandler.
    */
    function eZGZIPCompressionHandler()
    {
        if ( eZGZIPZLIBCompressionHandler::isAvailable() )
            $handler = new eZGZIPZLIBCompressionHandler();
        else if ( eZGZIPShellCompressionHandler::isAvailable() )
            $handler = new eZGZIPShellCompressionHandler();
        else
            $handler = new eZNoCompressionHandler();
        $this->eZForwardCompressionHandler( $handler,
                                            'GZIP', 'gzip' );
    }

    /*!
     Forwards the compression level to the current handler.
    */
    function setCompressionLevel( $level )
    {
        $handler =& $this->handler();
        if ( method_exists( $handler, 'setCompressionLevel' ) )
            $handler->setCompressionLevel( $level );
    }

    /*!
     Forwards the request for compression level to the current handler and returns the value.
    */
    function compressionLevel()
    {
        $handler =& $this->handler();
        if ( method_exists( $handler, 'compressionLevel' ) )
            return $handler->compressionLevel();
        return false;
    }
}

?>

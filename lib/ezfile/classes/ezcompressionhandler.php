<?php
//
// Definition of eZCompressionHandler class
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

/*! \file ezcompressionhandler.php
*/

/*!
  \class eZCompressionHandler ezcompressionhandler.php
  \brief Interface for file handlers using compression

  Generic interface for all file handlers using compression.

  This class introduces two new functions from the eZFileHandler base class,
  they are compress() and decompress() and are used for string based
  compression.

  \h1 Creating specific handlers

  The compressor handlers must inherit from this class and reimplement
  some virtual functions.

  For dealing with compressed strings the following functions must be reimplemented.
  compress() and decompress()

  The handlers must also implement the virtual functions defined in eZFileHandler.

*/

include_once( 'lib/ezfile/classes/ezfilehandler.php' );

class eZCompressionHandler extends eZFileHandler
{
    /*!
     Initializes the handler. Optionally the parameters \a $filename
     and \a $mode may be provided to automatically open the file.
    */
    function eZCompressionHandler( $handlerIdentifier, $handlerName )
    {
        $this->eZFileHandler( $handlerIdentifier, $handlerName );
    }

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

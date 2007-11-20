<?php
//
// Definition of eZCompressionHandler class
//
// Created on: <13-Aug-2003 16:20:19 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'lib/ezfile/classes/ezfilehandler.php' );

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

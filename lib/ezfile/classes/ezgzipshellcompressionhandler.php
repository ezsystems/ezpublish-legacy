<?php
//
// Definition of eZGZIPShellCompressionHandler class
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

/*! \file ezgzipshellcompressionhandler.php
*/

/*!
  \class eZGZIPShellCompressionHandler ezgzipshellcompressionhandler.php
  \brief Handles files compressed with gzip using the shell commands

  Handles GZIP compression by executing the 'gzip' executable,
  without this the handler cannot work.

NOTE: This is not done yet.
*/

include_once( 'lib/ezfile/classes/ezcompressionhandler.php' );

class eZGZIPShellCompressionHandler extends eZCompressionHandler
{
    /*!
    */
    function eZGZIPShellCompressionHandler()
    {
        $this->File = false;
        $thus->Level = false;
        $this->eZCompressionHandler( 'GZIP (shell)', 'gzipshell' );
    }

    /*!
     Sets the current compression level.
    */
    function setCompressionLevel( $level )
    {
        if ( $level < 0 or $level > 9 )
            $level = false;
        $this->Level = $level;
    }

    /*!
     \return the current compression level which is a number between 0 and 9,
             or \c false if the default is to be used.
    */
    function compressionLevel()
    {
        return $this->Level;
    }

    /*!
     \return true if this handler can be used.
    */
    function isAvailable()
    {
        return false;
    }

    function gunzipFile( $filename )
    {
        $command = 'gzip -dc $filename > $';
    }

    /*!
     \reimp
    */
    function doOpen( $filename, $mode )
    {
        $this->File = @gzopen( $filename, $mode );
    }

    /*!
     \reimp
    */
    function doClose()
    {
        return @gzclose( $this->File );
    }

    /*!
     \reimp
    */
    function doRead( $uncompressedLength = false )
    {
        return @gzread( $this->File, $uncompressedLength );
    }

    /*!
     \reimp
    */
    function doWrite( $data, $uncompressedLength = false )
    {
        return @gzwrite( $this->File, $uncompressedLength );
    }

    /*!
     \reimp
    */
    function doFlush()
    {
        return @fflush( $this->File );
    }

    /*!
     \reimp
    */
    function doSeek( $offset, $whence )
    {
        if ( $whence == SEEK_SET )
            $offset = $offset - gztell( $this->File );
        else if ( $whence == SEEK_END )
        {
            eZDebugSetting::writeError( 'lib-ezfile-gziplibz',
                                        "Seeking from end is not supported for gzipped files" );
            return false;
        }
        return @gzseek( $this->File, $offset );
    }

    /*!
     \reimp
    */
    function doRewind()
    {
        return @gzrewind( $this->File );
    }

    /*!
     \reimp
    */
    function doTell()
    {
        return @gztell( $this->File );
    }

    /*!
     \reimp
    */
    function doEOF()
    {
        return @gzeof( $this->File );
    }

    /*!
     \reimp
    */
    function doPasstrough()
    {
        return @gzpasstru( $this->File );
    }

    /*!
     \reimp
    */
    function compress( $source )
    {
        return @gzcompress( $source, $this->Level );
    }

    /*!
     \reimp
    */
    function decompress( $source )
    {
        return @gzuncompress( $source );
    }

    /*!
     \reimp
    */
    function errorString()
    {
        return false;
    }

    /*!
     \reimp
    */
    function errorNumber()
    {
        return false;
    }

    /// \privatesection
    /// File pointer, returned by gzopen
    var $File;
    /// The compression level
    var $Level;
}

?>

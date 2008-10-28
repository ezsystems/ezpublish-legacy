<?php
//
// Definition of eZGZIPShellCompressionHandler class
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

/*! \file ezgzipshellcompressionhandler.php
*/

/*!
  \class eZGZIPShellCompressionHandler ezgzipshellcompressionhandler.php
  \brief Handles files compressed with gzip using the shell commands

  Handles GZIP compression by executing the 'gzip' executable,
  without this the handler cannot work.

NOTE: This is not done yet.
*/

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
    static function isAvailable()
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
    function doPasstrough( $closeFile = true )
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
    public $File;
    /// The compression level
    public $Level;
}

?>

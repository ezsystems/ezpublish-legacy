<?php
//
// Definition of eZGZIPZLIBCompressionHandler class
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

/*! \file ezgzipzlibcompressionhandler.php
*/

/*!
  \class eZGZIPZLIBCompressionHandler ezgzipzlibcompressionhandler.php
  \brief Handles files compressed with gzip using the zlib extension

   More information on the zlib extension can be found here:
   http://www.php.net/manual/en/ref.zlib.php
*/

class eZGZIPZLIBCompressionHandler extends eZCompressionHandler
{
    function eZGZIPZLIBCompressionHandler()
    {
        $this->File = false;
        $this->Level = false;
        $this->eZCompressionHandler( 'GZIP (zlib)', 'gzipzlib' );
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
     This function checks if the zlib extension is available.
    */
    static function isAvailable()
    {
        $extensionName = 'zlib';
        if ( !extension_loaded( $extensionName ) )
        {
            $dlExtension = ( eZSys::osType() == 'win32' ) ? '.dll' : '.so';
            @dl( $extensionName . $dlExtension );
        }
        return extension_loaded( $extensionName );
    }

    function doOpen( $filename, $mode )
    {
        $this->File = @gzopen( $filename, $mode );
        return $this->File;
    }

    function doClose()
    {
        $result = @gzclose( $this->File );
        $this->File = false;
        return $result;
    }

    function doRead( $uncompressedLength = false )
    {
        return @gzread( $this->File, $uncompressedLength );
    }

    function doWrite( $data, $uncompressedLength = false )
    {
        if ( $uncompressedLength )
            return @gzwrite( $this->File, $data, $uncompressedLength );
        else
            return @gzwrite( $this->File, $data );
    }

    function doFlush()
    {
        return @fflush( $this->File );
    }

    function doSeek( $offset, $whence )
    {
        if ( $whence == SEEK_CUR )
        {
            $offset = gztell( $this->File ) + $offset;
        }
        else if ( $whence == SEEK_END )
        {
            eZDebug::writeError( "Seeking from end is not supported for gzipped files",
                                 'eZGZIPZLIBCompressionHandler::doSeek' );
            return false;
        }
        return @gzseek( $this->File, $offset );
    }

    function doRewind()
    {
        return @gzrewind( $this->File );
    }

    function doTell()
    {
        return @gztell( $this->File );
    }

    function doEOF()
    {
        return @gzeof( $this->File );
    }

    function doPasstrough( $closeFile = true )
    {
        $result = @gzpasstru( $this->File );
        if ( !$closeFile )
        {
            // The file must be reopened because gzpasstru will close the file.
            $this->File = @gzopen( $this->filename(), $this->mode(), $this->isBinaryMode() );
        }
        else
            $this->File = false;
        return $result;
    }

    function compress( $source )
    {
        return @gzcompress( $source, $this->Level );
    }

    function decompress( $source )
    {
        return @gzuncompress( $source );
    }

    function errorString()
    {
        return false;
    }

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

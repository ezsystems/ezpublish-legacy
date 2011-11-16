<?php
/**
 * File containing the eZGZIPZLIBCompressionHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
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
            eZDebug::writeError( "Seeking from end is not supported for gzipped files", __METHOD__ );
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
        $result = @gzpassthru( $this->File );
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

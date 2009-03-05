<?php
//
// Definition of eZFileHandler class
//
// Created on: <13-Aug-2003 16:20:19 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZFileHandler ezfilehandler.php
  \brief Interface for file handlers

  Generic interface for all file handlers.

  Using this class i divided into five areas, they are:

  File handling using open(), close(), read(), write() and flush().

  File position handling using seek(), tell(), rewind() and eof().

  Quick output of the file using passtrough().

  Error handling with error(), errorString() and errorNumber().

  \h1 Creating specific handlers

  The file handlers must inherit from this class and reimplement
  some virtual functions.

  For dealing with files the following functions must be reimplemented.
  doOpen(), doClose(), doRead(), doWrite() and doFlush().

  For dealing with file positions the following functions must be reimplemented.
  doSeek(), doTell(), doRewind() and doEOF().

  For dealing with quick output the passtrough() function must be reimplemented,
  if not reimplemented the default implemententation will simply read n bytes
  using read() and output it with print.

  Also the errorString() and errorNumber() functions must be reimplemented
  to provide proper error handler. The error() function is not required
  to be implemented since it has a default implementation, for speed
  it might be wise to implement the function.

  This class will handle most of the generic logic, like checking that
  the filename is correct and if the open succeded, and give error
  messages based on this.
  The actual implementations will only have to execute the specific
  code and return a result.

*/

class eZFileHandler
{
    /*!
     Initializes the handler. Optionally the parameters \a $filename
     and \a $mode may be provided to automatically open the file.
    */
    function eZFileHandler( $handlerIdentifier = false, $handlerName = false )
    {
        if ( !$handlerIdentifier )
        {
            $handlerIdentifier = 'plain';
            $handlerName = 'Plain';
        }
        $this->Name = $handlerName;
        $this->Identifier = $handlerIdentifier;
        $this->FileName = false;
        $this->FileHandler = false;
        $this->Mode = false;
        $this->IsOpen = false;
        $this->IsBinary = false;
    }

    /*!
     \return \c true if a file is opened, \c false otherwise.
    */
    function isOpen()
    {
        return $this->IsOpen;
    }

    /*!
     \return \c true if the file was opened in binary mode.
    */
    function isBinaryMode()
    {
        return $this->IsBinary;
    }

    /*!
     \return the filename currently in use.
    */
    function filename()
    {
        return $this->FileName;
    }

    /*!
     \return the mode which was used to open the currently used file.
    */
    function mode()
    {
        return $this->Mode;
    }

    /*!
     \return the name of current handler.
     \sa identifier
    */
    function name()
    {
        return $this->Name;
    }

    /*!
     \return the identifier of the current handler.
     \sa name
    */
    function identifier()
    {
        return $this->Identifier;
    }

    /*!
     \return true if this handler can be used.
     \note The default implementation is to return \c true for all handlers.
    */
    static function isAvailable()
    {
        return true;
    }

    /*!
     Links the file \a $sourceFilename to \a $destinationFilename.
     If \a $destinationFilename is a directory then the filename is taken from \a $sourceFilename and appended to the destination.
     It will use symbolic links for the operating system that support it and file copy for all others.
     \param $symbolicLink if \c true then the files will be made as symbolic links, otherwise as hard links.
     \return \c true if sucessful or \c false if the copy failed.
    */
    static function linkCopy( $sourceFilename, $destinationFilename, $symbolicLink = true )
    {
        if ( in_array( eZSys::osType(),
                       array( 'unix', 'linux', 'mac' ) ) )
        {
            if ( $symbolicLink )
                $result = eZFileHandler::symlink( $sourceFilename, $destinationFilename );
            else
                $result = eZFileHandler::link( $sourceFilename, $destinationFilename );
            if ( $result )
                return $result;
            return eZFileHandler::copy( $sourceFilename, $destinationFilename );
        }
        else
        {
            return eZFileHandler::copy( $sourceFilename, $destinationFilename );
        }
    }

    /*!
     Creates a symbolic link to the file \a $sourceFilename on the destination \a $destinationFilename.
     This means that if someone tries to open \a $destinationFilename they will infact open \a $sourceFilename.
     If \a $destinationFilename is a directory then the filename is taken from \a $sourceFilename and appended to the destination.
     It will first try to rename the file and if that does not work copy the file and unlink.
     \return \c true if sucessful or \c false if the copy failed.
    */
    static function symlink( $sourceFilename, $destinationFilename )
    {
        if ( !file_exists( $sourceFilename ) and
             !is_link( $sourceFilename ) )
        {
            eZDebug::writeError( "Cannot symbolicly link to file $sourceFilename, it does not exist",
                                 'eZFileHandler::symlink' );
            return false;
        }
        $isDir = false;
        if ( is_dir( $destinationFilename ) )
        {
            $isDir = true;
            $dirPosition = strrpos( $sourceFilename, '/' );
            $filePosition = 0;
            if ( $dirPosition !== false )
                $filePosition = $dirPosition + 1;
            if ( strlen( $destinationFilename ) > 0 and
                 $destinationFilename[strlen( $destinationFilename ) - 1] == '/' )
                $destinationFilename .= substr( $sourceFilename, $filePosition );
            else
                $destinationFilename .= '/' . substr( $sourceFilename, $filePosition );
        }
        $destinationFilename = preg_replace( "#/+#", '/', $destinationFilename );
        $sourceDir = $sourceFilename;
        $sourceName = false;
        $sourceDirPos = strrpos( $sourceDir, '/' );
        if ( $sourceDirPos !== false )
        {
            $sourceName = substr( $sourceDir, $sourceDirPos + 1 );
            $sourceDir = substr( $sourceDir, 0, $sourceDirPos );
        }
        $commonOffset = 0;
        for ( $i = 0; $i < strlen( $sourceFilename ) and $i < strlen( $sourceDir ); ++$i )
        {
            if ( $sourceFilename[$i] != $sourceDir[$i] )
                break;
            $commonOffset = $i;
        }
        if ( $commonOffset > 0 )
            $sourceDir = substr( $sourceDir, $commonOffset + 1 );
        $directoryCount = substr_count( $sourceDir, '/' );
        $cdupText = str_repeat( '../', $directoryCount );
        if ( file_exists( $destinationFilename ) and
             !is_dir( $destinationFilename ) )
        {
            if ( !@unlink( $destinationFilename ) )
            {
                eZDebug::writeError( "Cannot symbolicly link to file $sourceFilename on destination $destinationFilename, destination file cannot be removed",
                                     'eZFileHandler::symlink' );
                return false;
            }
        }
        if ( $sourceDir )
            $sourceDir = $sourceDir . '/' . $sourceName;
        else
            $sourceDir = $sourceName;
        if ( symlink( $cdupText . $sourceDir, $destinationFilename ) )
        {
            return true;
        }
        eZDebug::writeError( "Failed to symbolicly link to $sourceFilename on destination $destinationFilename",
                             'eZFileHandler::symlink' );
        return false;
    }

    /*!
     Creates a symbolic link to the file \a $sourceFilename on the destination \a $destinationFilename.
     This means that if someone tries to open \a $destinationFilename they will infact open \a $sourceFilename.
     If \a $destinationFilename is a directory then the filename is taken from \a $sourceFilename and appended to the destination.
     It will first try to rename the file and if that does not work copy the file and unlink.
     \return \c true if sucessful or \c false if the copy failed.
    */
    static function link( $sourceFilename, $destinationFilename )
    {
        if ( !file_exists( $sourceFilename ) and
             !is_link( $sourceFilename ) )
        {
            eZDebug::writeError( "Cannot link to file $sourceFilename, it does not exist",
                                 'eZFileHandler::link' );
            return false;
        }
        $isDir = false;
        if ( is_dir( $destinationFilename ) )
        {
            $isDir = true;
            $dirPosition = strrpos( $sourceFilename, '/' );
            $filePosition = 0;
            if ( $dirPosition !== false )
                $filePosition = $dirPosition + 1;
            if ( strlen( $destinationFilename ) > 0 and
                 $destinationFilename[strlen( $destinationFilename ) - 1] == '/' )
                $destinationFilename .= substr( $sourceFilename, $filePosition );
            else
                $destinationFilename .= '/' . substr( $sourceFilename, $filePosition );
        }
        $destinationFilename = preg_replace( "#/+#", '/', $destinationFilename );
        if ( file_exists( $destinationFilename ) and
             !is_dir( $destinationFilename ) )
        {
            if ( !@unlink( $destinationFilename ) )
            {
                eZDebug::writeError( "Cannot link to file $sourceFilename on destination $destinationFilename, destination file cannot be removed",
                                     'eZFileHandler::link' );
                return false;
            }
        }
        if ( link( $sourceFilename, $destinationFilename ) )
        {
            return true;
        }
        eZDebug::writeError( "Failed to link to $sourceFilename on destination $destinationFilename",
                             'eZFileHandler::link' );
        return false;
    }

    /*!
     Moves the file \a $sourceFilename to \a $destinationFilename.
     If \a $destinationFilename is a directory then the filename is taken from \a $sourceFilename and appended to the destination.
     It will first try to rename the file and if that does not work copy the file and unlink.
     \return \c true if sucessful or \c false if the copy failed.
    */
    static function move( $sourceFilename, $destinationFilename )
    {
        if ( !file_exists( $sourceFilename ) and
             !is_link( $sourceFilename ) )
        {
            eZDebug::writeError( "Cannot rename file $sourceFilename, it does not exist",
                                 'eZFileHandler::move' );
            return false;
        }
        $isDir = false;
        if ( is_dir( $destinationFilename ) )
        {
            $isDir = true;
            $dirPosition = strrpos( $sourceFilename, '/' );
            $filePosition = 0;
            if ( $dirPosition !== false )
                $filePosition = $dirPosition + 1;
            if ( strlen( $destinationFilename ) > 0 and
                 $destinationFilename[strlen( $destinationFilename ) - 1] == '/' )
                $destinationFilename .= substr( $sourceFilename, $filePosition );
            else
                $destinationFilename .= '/' . substr( $sourceFilename, $filePosition );
        }


        // If source and destination are the same files we just return true
        if ( $sourceFilename == $destinationFilename )
        {
            return true;
        }

        if ( file_exists( $destinationFilename ) and
             !is_dir( $destinationFilename ) )
        {
            if ( !@unlink( $destinationFilename ) )
            {
                eZDebug::writeError( "Cannot move file $sourceFilename to destination $destinationFilename, destination file cannot be removed",
                                     'eZFileHandler::move' );
                return false;
            }
        }
        $isLink = false;
        if ( is_link( $sourceFilename ) )
        {
            $isLink = true;
        }
        if ( !$isLink and
             eZFile::rename( $sourceFilename, $destinationFilename ) )
        {
            return true;
        }
        if ( eZFileHandler::copy( $sourceFilename, $destinationFilename ) )
        {
            if ( !@unlink( $sourceFilename ) )
            {
                eZDebug::writeError( "Cannot remove source file $sourceFilename, file was not succesfully moved",
                                     'eZFileHandler::move' );
                @unlink( $destinationFilename );
                return false;
            }
            return true;
        }
                eZDebug::writeError( "Failed to copy $sourceFilename to $destinationFilename, file was not succesfully moved",
                                     'eZFileHandler::move' );
        return false;
    }

    /*!
     Copies the file \a $sourceFilename to \a $destinationFilename.
     \return \c true if sucessful or \c false if the copy failed.
    */
    static function copy( $sourceFilename, $destinationFilename )
    {
        if ( is_dir( $sourceFilename ) )
        {
            eZDebug::writeError( "Unable to copy directory $sourceFilename, use eZDir::copy instead",
                                 'eZFileHandler::copy' );
            return false;
        }
        $sourceFD = @fopen( $sourceFilename, 'rb' );
        if ( !$sourceFD )
        {
            eZDebug::writeError( "Unable to open source file $sourceFilename in read mode",
                                 'eZFileHandler::copy' );
            return false;
        }
        if ( is_dir( $destinationFilename ) )
        {
            $dirPosition = strrpos( $sourceFilename, '/' );
            $filePosition = 0;
            if ( $dirPosition !== false )
                $filePosition = $dirPosition + 1;
            if ( strlen( $destinationFilename ) > 0 and
                 $destinationFilename[strlen( $destinationFilename ) - 1] == '/' )
                $destinationFilename .= substr( $sourceFilename, $filePosition );
            else
                $destinationFilename .= '/' . substr( $sourceFilename, $filePosition );
        }

        // If source and destination are the same files we just return true
        if ( $sourceFilename == $destinationFilename )
        {
            @fclose( $sourceFD );
            return true;
        }

        $destinationFD = fopen( $destinationFilename, 'wb' );
        chmod( $destinationFilename, octdec( eZINI::instance()->variable( 'FileSettings', 'StorageFilePermissions' ) ) );
        if ( !$destinationFD )
        {
            @fclose( $sourceFD );
            eZDebug::writeError( "Unable to open destination file $destinationFilename in write mode",
                                 'eZFileHandler::copy' );
            return false;
        }
        $bytesCopied = 0;
        do
        {
            $data = fread( $sourceFD, 4096 );
            if ( strlen( $data ) == 0 )
                break;
            fwrite( $destinationFD, $data );
            $bytesCopied += strlen( $data );
        } while( true );

        @fclose( $sourceFD );
        @fclose( $destinationFD );
        return true;
    }

    /*!
     \return \c true if the filename \a $filename exists.
     If \a $filename is not specified the filename is taken from the one used in open().
    */
    function exists( $filename = false )
    {
        if ( !$filename )
            $filename = $this->FileName;
        return $this->doExists( $filename );
    }

    /*!
     \return \c true if \a $filename is a directory.
    */
    function isDirectory( $filename = false )
    {
        if ( !$filename )
            $filename = $this->FileName;
        return $this->doIsDirectory( $filename );
    }

    /*!
     \return \c true if \a $filename is executable.
    */
    function isExecutable( $filename = false )
    {
        if ( !$filename )
            $filename = $this->FileName;
        return $this->doIsExecutable( $filename );
    }

    /*!
     \return \c true if \a $filename is a file.
    */
    function isFile( $filename = false )
    {
        if ( !$filename )
            $filename = $this->FileName;
        return $this->doIsFile( $filename );
    }

    /*!
     \return \c true if \a $filename is a link.
    */
    function isLink( $filename = false )
    {
        if ( !$filename )
            $filename = $this->FileName;
        return $this->doIsLink( $filename );
    }

    /*!
     \return \c true if \a $filename is readable.
    */
    function isReadable( $filename = false )
    {
        if ( !$filename )
            $filename = $this->FileName;
        return $this->doIsReadable( $filename );
    }

    /*!
     \return \c true if \a $filename is writeable.
    */
    function isWriteable( $filename = false )
    {
        if ( !$filename )
            $filename = $this->FileName;
        return $this->doIsWriteable( $filename );
    }

    /*!
     \return the statitistics for the file \a $filename.
    */
    function statistics( $filename = false )
    {
        if ( !$filename )
            $filename = $this->FileName;
        return $this->doStatistics( $filename );
    }

    /*!
     Tries to open the file \a $filename with mode \a $mode and returns
     the file resource if succesful.
     \return \c false if the file could not be opened.
     \param $mode If false the file will be opened in read mode using 'r'
     \param $binaryFile If true file will be opened in binary mode (default),
                        otherwise text mode is used.
     \note Parameter $binaryFile will only have effect on the Windows operating system.
    */
    function open( $filename, $mode, $binaryFile = true )
    {
        if ( $this->isOpen() )
        {
            eZDebug::writeError( "A file is already open (" . $this->FileName . "), close the file before opening a new one.",
                                 'eZFileHandler::open' );
            return false;
        }
        if ( !$filename and
             !$this->FileName )
        {
            eZDebug::writeError( "The supplied filename is empty and no filename set for object, cannot open any file",
                                 'eZFileHandler::open' );
            return false;
        }
        if ( !$filename )
            $filename = $this->FileName;
        if ( !$mode )
            $mode = 'r';
        if ( strpos( $mode, 'b' ) !== false )
            $binaryFile = true;
        $mode = str_replace( 'b', '', $mode );
        if ( $binaryFile )
            $mode .= 'b';
        $this->IsBinary = $binaryFile;
        $result = $this->doOpen( $filename, $mode );
        if ( $result )
        {
//             eZDebugSetting::writeNotice( 'lib-ezfile-openclose',
//                                          "Opened file $filename with mode '$mode'",
//                                          'eZFileHandler::open' );
            $this->FileName = $filename;
            $this->Mode = $mode;
            $this->IsOpen = true;
        }
        else
            eZDebug::writeError( "Failed opening file $filename with mode $mode",
                                 'eZFileHandler::open' );
        return $result;
    }

    /*!
     Tries to close an open file and returns \c true if succesful, \c false otherwise.
    */
    function close()
    {
        if ( !$this->isOpen() )
        {
            eZDebug::writeError( "A file is not currently opened, cannot close.",
                                 'eZFileHandler::close' );
            return false;
        }
//         eZDebugSetting::writeNotice( 'lib-ezfile-openclose',
//                                      "Closing file " . $this->filename() . " with previously opened mode '" . $this->mode() . "'",
//                                      'eZFileHandler::close' );
        $result = $this->doClose();
        if ( !$result )
            eZDebug::writeError( "Failed closing file " . $this->FileName . " opened with mode " . $this->Mode,
                                 'eZFileHandler::close' );
        else
            $this->IsOpen = false;
        return $result;
    }

    /*!
     Tries to unlink the file from the file system.
    */
    static function unlink( $filename = false )
    {
        if ( !$filename )
        {
            if ( $this->isOpen() )
                $this->close();
            $filename = $this->FileName;
        }
        $result = eZFileHandler::doUnlink( $filename );
        if ( !$result )
            eZDebug::writeError( "Failed unlinking file " . $filename,
                                 'eZFileHandler::unlink' );
        return $result;
    }

    /*!
     Renames the file \a $sourceFilename to \a $destinationFilename.
     If \a $sourceFilename is not supplied then filename() is used,
     it will also close the current file connection and reopen it again if
     was already open.
    */
    function rename( $destinationFilename, $sourceFilename = false )
    {
        if ( !$sourceFilename )
        {
            $wasOpen = $this->isOpen();
            if ( $wasOpen )
                $this->close();
            $result = $this->doRename( $destinationFilename, $this->filename() );
            if ( $wasOpen and
                 $result )
                $this->open( $destinationFilename, $this->mode() );
        }
        else
            $result = $this->doRename( $destinationFilename, $sourceFilename );
        return $result;
    }

    /*!
     Reads up to \a $length bytes from file. Reading stops when
     \a $length has been read or \c EOF is reached, whichever comes first.
     If the optional parameter \a $length is not specified it will
     read bytes until \c EOF is reached.
     \return a \c string or \c false if something fails.
    */
    function read( $length = false )
    {
        if ( !$this->isOpen() )
        {
            eZDebug::writeError( "A file is not currently opened, cannot read.",
                                 'eZFileHandler::read' );
            return false;
        }
        if ( $length < 0 )
        {
            eZDebug::writeError( "length cannot be negative ($length)",
                                 'eZFileHandler::read' );
            return false;
        }
        if ( $length )
        {
            return $this->doRead( $length );
        }
        else
        {
            $string = '';
            $data = false;
            do
            {
                $data = $this->doRead( 1024 );
                if ( $data )
                    $string .= $data;
            } while( $data );
            return $string;
        }
    }

    /*!
     Writes the content of the string \a $data to the file.
     If optional \c $length parameter is supplied writing will stop after
     length is reached or the end of the string is reached, whichever comes first.
     \return the number of bytes that was written.
    */
    function write( $data, $length = false )
    {
        if ( !$this->isOpen() )
        {
            eZDebug::writeError( "A file is not currently opened, cannot write.",
                                 'eZFileHandler::write' );
            return false;
        }
        if ( $length < 0 )
        {
            eZDebug::writeError( "length cannot be negative ($length)",
                                 'eZFileHandler::write' );
            return false;
        }
        return $this->doWrite( $data, $length );
    }

    /*!
     Force a write of all buffered data.
     \return \c true if succesful or \c false if something failed.
    */
    function flush()
    {
        if ( !$this->isOpen() )
        {
            eZDebug::writeError( "A file is not currently opened, cannot flush.",
                                 'eZFileHandler::flush' );
            return false;
        }
        return $this->doFlush();
    }

    /*!
     Seeks to position in file determined by \a $offset and \a $whence.
     - SEEK_SET - Set position equal to offset bytes.
     - SEEK_CUR - Set position to current location plus offset.
     - SEEK_END - Set position to end-of-file plus offset. (To move to a position before the end-of-file, you need to pass a negative value in offset.)

     \note Not all handlers supports all types for \a $whence
     \return \c 0 if succesful or \c -1 if something failed.
    */
    function seek( $offset, $whence = SEEK_SET )
    {
        if ( !$this->isOpen() )
        {
            eZDebug::writeError( "A file is not currently opened, cannot seek.",
                                 'eZFileHandler::seek' );
            return false;
        }
        return $this->doSeek( $offset, $whence );
    }

    /*!
     Rewinds the file position to the beginning of the file.
     \return \c 0 if something went wrong.
    */
    function rewind()
    {
        if ( !$this->isOpen() )
        {
            eZDebug::writeError( "A file is not currently opened, cannot rewind.",
                                 'eZFileHandler::rewind' );
            return false;
        }
        return $this->doRewind();
    }

    /*!
     Tells the current file position.
     \return \c false if something failed.
    */
    function tell()
    {
        if ( !$this->isOpen() )
        {
            eZDebug::writeError( "A file is not currently opened, cannot tell position.",
                                 'eZFileHandler::tell' );
            return false;
        }
        return $this->doTell();
    }

    /*!
     \return \c true if the file pointer is at the end of the file or an error occured, otherwise \c false.
    */
    function eof()
    {
        if ( !$this->isOpen() )
        {
            eZDebug::writeError( "A file is not currently opened, cannot report EOF status.",
                                 'eZFileHandler::eof' );
            return false;
        }
        return $this->doEOF();
    }

    /*!
     Passes the data from the file to the standard output.
     \param $closeFile If \c true the file will be closed after output is done.
     \return \c false if something failed.
    */
    function passtrough( $closeFile = true )
    {
        if ( !$this->isOpen() )
        {
            eZDebug::writeError( "A file is not currently opened, cannot do a data passtrough.",
                                 'eZFileHandler::passtrough' );
            return false;
        }
        return $this->doPasstrough( $closeFile );
    }

    /*!
     \pure
     Does the actual file opening.
     \sa open
    */
    function doOpen( $filename, $mode )
    {
        $this->FileHandler = @fopen( $filename, $mode );
        return $this->FileHandler;
    }

    /*!
     \pure
     Does the actual file closing.
     \sa close
    */
    function doClose()
    {
        $result = @fclose( $this->FileHandler );
        $this->FileHandler = false;
        return $result;
    }

    /*!
     \pure
     Does the actual file unlinking.
     \sa unlink
    */
    static function doUnlink( $filename )
    {
        return @unlink( $filename );
    }

    /*!
     \pure
     Does the actual file exists checking.
     \sa exists
    */
    static function doExists( $filename )
    {
        return file_exists( $filename );
    }

    /*!
     \pure
     Does the actual directory checking.
     \sa isDirectory
    */
    static function doIsDirectory( $filename )
    {
        return @is_dir( $filename );
    }

    /*!
     \pure
     Does the actual executable checking.
     \sa isExecutable
    */
    static function doIsExecutable( $filename )
    {
        return @is_executable( $filename );
    }

    /*!
     \pure
     Does the actual file checking.
     \sa isFile
    */
    static function doIsFile( $filename )
    {
        return @is_file( $filename );
    }

    /*!
     \pure
     Does the actual link checking.
     \sa isLink
    */
    static function doIsLink( $filename )
    {
        return @is_link( $filename );
    }

    /*!
     \pure
     Does the actual readable checking.
     \sa isReadable
    */
    static function doIsReadable( $filename )
    {
        return @is_readable( $filename );
    }

    /*!
     \pure
     Does the actual writeable checking.
     \sa isWriteable
    */
    static function doIsWriteable( $filename )
    {
        return @is_writable( $filename );
    }

    /*!
     \pure
     Does the actual writeable checking.
     \sa isWriteable
    */
    static function doStatistics( $filename )
    {
        return @stat( $filename );
    }

    /*!
     \pure
     Does the actual file seek.
     \sa seek
    */
    function doSeek( $offset, $whence )
    {
        return @fseek( $this->FileHandler, $offset, $whence );
    }

    /*!
     Does the actual file rewind.
     \sa rewind
     \note Default implementation calls seek with offset set to 0 from the file start.
    */
    function doRewind()
    {
        $this->doSeek( $offset, SEEK_SET );
    }

    /*!
     \pure
     Does the actual file telling.
     \sa tell
    */
    function doTell()
    {
        return @ftell( $this->FileHandler );
    }

    /*!
     \pure
     Does the actual file eof detection.
     \sa eof
    */
    function doEOF()
    {
        return @feof( $this->FileHandler );
    }

    /*!
     \pure
     Does the actual file passtrough.
     \sa eof
    */
    function doPasstrough( $closeFile = true )
    {
        $result = @fpasstru( $this->FileHandler );
        if ( $closeFile )
        {
            @fclose( $this->FileHandler );
            $this->FileHandler = false;
        }
    }

    /*!
     \pure
     Does the actual file reading.
     \sa read
    */
    function doRead( $length = false )
    {
        return @fread( $this->FileHandler, $length );
    }

    /*!
     \pure
     Does the actual file writing.
     \sa write
    */
    function doWrite( $data, $length = false )
    {
        if ( $length === false )
            return @fwrite( $this->FileHandler, $data );
        else
            return @fwrite( $this->FileHandler, $data, $length );
    }

    /*!
     \pure
     Does the actual file flushing.
     \sa flush
    */
    function doFlush()
    {
        return @fflush( $this->FileHandler );
    }

    /*!
     \pure
     Does the actual file renaming.
     \sa rename
    */
    static function doRename( $destinationFilename, $sourceFilename )
    {
        return eZFile::rename( $sourceFilename, $destinationFilename );
    }

    /*!
     Returns error data as an associative array, the array contains:
     - string - The error string
     - number - The error number
     \note The default implementation calls errorString() and errorNumber().
    */
    function error()
    {
        return array( 'string' => $this->errorString(),
                      'number' => $this->errorNumber() );
    }

    /*!
     \pure
     \return the error string from the last error that occured.
    */
    function errorString()
    {
        return false;
    }

    /*!
     \pure
     \return the error number from the last error that occured.
    */
    function errorNumber()
    {
        return false;
    }

    /*!
     Creates a copy of the current handler and returns a reference to the copy.
     \note The default does a simple copy of \c $this, this method must be reimplemented for specific handlers.
    */
    function duplicate()
    {
        $copy = clone $this;
        return $copy;
    }

    /*!
     Returns the handler for the identifier \a $identifier.
             The parameters \a $filename, \a $mode and \a $binaryFile is passed to the handler.
     \return \c false if the handler could not be created.
    */
    static function instance( $identifier, $filename = false, $mode = false, $binaryFile = true )
    {
        $ini = eZINI::instance( 'file.ini' );
        $handlers = $ini->variable( 'FileSettings', 'Handlers' );
        $instance = false;
        if ( !$identifier )
        {
            $instance = new eZFileHandler();
        }
        else if ( isset( $handlers[$identifier] ) )
        {
            $className = $handlers[$identifier];
            $includeFile = 'lib/ezfile/classes/' . $className . '.php';
            include_once( $includeFile );
            $instance = new $className();
            if ( $instance->isAvailable() )
            {
                if ( $filename )
                    $instance->open( $filename, $mode, $binaryFile );
            }
            else
            {
                unset( $instance );
                $instance = false;
            }
        }
        return $instance;
    }

    /// \privatesection
    public $Name;
    public $FileName;
    public $Mode;
    public $IsBinary;
    public $IsOpen;
}

?>
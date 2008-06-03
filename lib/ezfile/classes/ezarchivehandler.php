<?php
//
// Definition of eZArchiveHandler class
//
// Created on: <14-Aug-2003 11:25:33 amos>
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

/*! \file ezarchivehandler.php
*/

/*!
  \class eZArchiveHandler ezarchivehandler.php
  \brief General handling of file archives

  This class handles the abstraction of handling various
  kinds of archive formats. The actual handling of the
  formats is sent of the specific archive handlers.

\code
$handler = eZArchiveHandler::instance( 'tar', 'ezpublish.tar' );
\endcode

*/

//include_once( 'lib/ezfile/classes/ezfilehandler.php' );

class eZArchiveHandler
{
    /*!
     Constructor
    */
    function eZArchiveHandler( $fileHandler, $archiveFilename = false )
    {
        $this->FileHandler = $fileHandler;
        $this->ArchiveFilename = $archiveFilename;
    }

    function setArchiveFileName( $filename )
    {
        $this->ArchiveFilename = $filename;
    }

    function fileIsOpen()
    {
        return $this->FileHandler->isOpen();
    }

    function fileIsBinaryMode()
    {
        return $this->FileHandler->isBinaryMode();
    }

    function fileName()
    {
        return $this->FileHandler->filename();
    }

    function fileMode()
    {
        return $this->FileHandler->mode();
    }

    function fileOpen( $archiveFilename = false, $mode = false )
    {
        if ( !$archiveFilename )
            $archiveFilename = $this->ArchiveFilename;
        $result = $this->FileHandler->open( $archiveFilename, $mode );
        if ( $result )
            $this->ArchiveFilename = $archiveFilename;
        return $result;
    }

    function fileClose()
    {
        return $this->FileHandler->close();
    }

    function fileRead( $length = false )
    {
        return $this->FileHandler->read( $length );
    }

    function fileWrite( $data, $length = false )
    {
        return $this->FileHandler->write( $data, $length );
    }

    function fileFlush()
    {
        return $this->FileHandler->flush();
    }

    function fileSeek( $offset, $whence = SEEK_SET )
    {
        return $this->FileHandler->seek( $offset, $whence );
    }

    function fileRewind()
    {
        return $this->FileHandler->rewind();
    }

    function fileTell()
    {
        return $this->FileHandler->tell();
    }

    function fileEOF()
    {
        return $this->FileHandler->eof();
    }

    function filePasstrough( $closeFile = true )
    {
        return $this->FileHandler->passtrough( $closeFile );
    }

    function fileError()
    {
        return $this->FileHandler->error();
    }

    function fileErrorString()
    {
        return $this->FileHandler->errorString();
    }

    function fileErrorNumber()
    {
        return $this->FileHandler->errorNumber();
    }

    /*!
     Calls the rename() function for the current handler.
    */
    function fileRename( $destinationFilename, $sourceFilename = true )
    {
        return $this->FileHandler->rename( $destinationFilename, $sourceFilename );
    }

    function fileUnlink( $filename = false )
    {
        return $this->FileHandler->unlink( $filename );
    }

    function fileCopy( $sourceFilename, $destinationFilename )
    {
        return $this->FileHandler->copy( $sourceFilename, $destinationFilename );
    }

    function fileExists( $filename = false )
    {
        return $this->FileHandler->exists( $filename );
    }

    function fileIsDirectory( $filename = false )
    {
        return $this->FileHandler->isDirectory( $filename );
    }

    function fileIsExecutable( $filename = false )
    {
        return $this->FileHandler->isExecutable( $filename );
    }

    function fileIsFile( $filename = false )
    {
        return $this->FileHandler->isFile( $filename );
    }

    function fileIsLink( $filename = false )
    {
        return $this->FileHandler->isLink( $filename );
    }

    function fileIsReadable( $filename = false )
    {
        return $this->FileHandler->isReadable( $filename );
    }

    function fileIsWriteable( $filename = false )
    {
        return $this->FileHandler->isWriteable( $filename );
    }

    function fileStatistics( $filename = false )
    {
        return $this->FileHandler->statistics( $filename );
    }

    /*!
     \return the current file handler used for opening the archive file.
    */
    function fileHandler()
    {
        return $this->FileHandler;
    }

    /*!
     \return \c true if the handler is available for use.
    */
    function isAvailable()
    {
        return true;
    }

    /*!
     Detaches the current file handler and instanties a new duplicate as current.
     \return the old file handler.
    */
    function &detachHandler()
    {
        $oldHandler = $this->FileHandler;
        $this->FileHandler = $oldHandler->duplicate();
        return $oldHandler;
    }

    /*!
     Returns the handler for the identifier \a $identifier.
     The parameter \a $fileHandler must contain the filehandler object.
     \return \c false if the handler could not be created.
    */
    static function instance( $identifier, $fileHandlerType = false, $arhiveFilename = false )
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance( 'file.ini' );
        $handlers = $ini->variable( 'ArchiveSettings', 'Handlers' );
        $instance = false;
        if ( isset( $handlers[$identifier] ) )
        {
            $className = $handlers[$identifier];
            $includeFile = 'lib/ezfile/classes/' . $className . '.php';
            include_once( $includeFile );
            $fileHandler = eZFileHandler::instance( $fileHandlerType );
            $instance = new $className( $fileHandler, $arhiveFilename );
            if ( !$instance->isAvailable() )
            {
                unset( $instance );
                $instance = false;
            }
        }
        return $instance;
    }

    /// \privatesection
    public $FileHandler;
}

?>

<?php
//
// Definition of eZExpiryHandler class
//
// Created on: <28-Feb-2003 16:52:53 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezexpiryhandler.php
*/

/*!
  \class eZExpiryHandler ezexpiryhandler.php
  \brief Keeps track of expiry keys and their timestamps

*/

//include_once( 'lib/ezutils/classes/ezphpcreator.php' );

class eZExpiryHandler
{
    /*!
     Constructor
    */
    function eZExpiryHandler()
    {
        $this->Timestamps = array();
        $this->IsModified = false;
        $this->restore();
    }

    /*!
     Will load timestamp values from disk.
    */
    function restore()
    {
        // VS-DBFILE

        $cacheDirectory = eZSys::cacheDirectory();
        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $expiryFile = eZClusterFileHandler::instance( $cacheDirectory . '/' . 'expiry.php' );
        if ( $expiryFile->exists() )
        {

            $fetchedFilePath = $expiryFile->fetchUnique();
            include( $fetchedFilePath );
            $expiryFile->fileDeleteLocal( $fetchedFilePath );

//            $expiryFile->fetch();
//            include( $cacheDirectory . "/" . 'expiry.php' );
            $this->Timestamps = $Timestamps;
            $this->IsModified = false;

//            $expiryFile->deleteLocal();
        }
    }

    /*!
     Will store the current timestamp values to disk.
    */
    function store()
    {
        // VS-DBFILE

        $cacheDirectory = eZSys::cacheDirectory();

        $uniqid = md5( uniqid( "ezp". getmypid(), true ) );
        $fp = @fopen( "$cacheDirectory/.expiry.php.$uniqid.tmp", 'w' );
        if ( $fp )
        {
            $storeString = "<?php\n\$Timestamps = array( ";
            $i = 0;
            foreach ( $this->Timestamps as $key => $value )
            {
                if ( $i > 0 )
                    $storeString .= ",\n" . str_repeat( ' ', 21 );
                $storeString .= "'$key' => $value";
                ++$i;
            }
            $storeString .= " );\n?>";

            fwrite( $fp, $storeString );
            fclose( $fp );
            include_once( 'lib/ezutils/classes/ezfile.php' );
            eZFile::rename( "$cacheDirectory/.expiry.php.$uniqid.tmp", "$cacheDirectory/expiry.php" );

            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $fileHandler = eZClusterFileHandler::instance();
            $fileHandler->fileStore(  "$cacheDirectory/expiry.php", 'expirycache', true );

            $this->IsModified = false;
        }
    }

    /*!
     Sets the timestamp value \a $value for expiry key \a $name.
    */
    function setTimestamp( $name, $value )
    {
        $this->Timestamps[$name] = $value;
        $this->IsModified = true;
    }

    /*!
     \return true if the expiry key \a $name exists.
    */
    function hasTimestamp( $name )
    {
        return isset( $this->Timestamps[$name] );
    }

    /*!
     \return the timestamp value for the expiry key \a $name if it exists or \c false if not,
    */
    function timestamp( $name )
    {
        if ( !isset( $this->Timestamps[$name] ) )
        {
            eZDebug::writeError( "Unknown expiry timestamp called '$name'", 'eZExpiryHandler::timestamp' );
            return false;
        }
        return $this->Timestamps[$name];
    }

    /*!
     \static
     \return the unique instance of the expiry handler.
    */
    function &instance()
    {
        $expiryInstance =& $GLOBALS['eZExpiryHandlerInstance'];
        if ( !isset( $expiryInstance ) )
        {
            $expiryInstance = new eZExpiryHandler();
        }
        return $expiryInstance;
    }

    /*!
     \return true if the expiry handler has modified data.
    */
    function isModified()
    {
        return $this->IsModified;
    }

    /// \privatesection
    var $Timestamps;
    var $IsModified;
}

/*!
 Called at the end of execution and will store the data if it is modified.
*/
function eZExpiryHandlerShutdownHandler()
{
    $expiryInstance =& $GLOBALS['eZExpiryHandlerInstance'];
    if ( isset( $expiryInstance ) and
         get_class( $expiryInstance ) == 'ezexpiryhandler' )
    {
        $instance =& eZExpiryHandler::instance();
        if ( $instance->isModified() )
        {
            $instance->store();
        }
    }
}

register_shutdown_function( 'eZExpiryHandlerShutdownHandler' );

?>

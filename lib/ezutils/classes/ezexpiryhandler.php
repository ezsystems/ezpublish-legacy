<?php
//
// Definition of eZExpiryHandler class
//
// Created on: <28-Feb-2003 16:52:53 amos>
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
        $cacheDirectory = eZSys::cacheDirectory();
        if ( file_exists( $cacheDirectory . "/" . 'expiry.php' ) )
        {
            include( $cacheDirectory . "/" . 'expiry.php' );
            $this->Timestamps = $Timestamps;
            $this->IsModified = false;
        }
    }

    /*!
     Will store the current timestamp values to disk.
    */
    function store()
    {
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
            include "lib/ezutils/classes/ezfile.php";
            eZFile::rename( "$cacheDirectory/.expiry.php.$uniqid.tmp", "$cacheDirectory/expiry.php" );

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

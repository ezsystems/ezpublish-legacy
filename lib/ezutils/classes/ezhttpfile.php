<?php
//
// Definition of eZHTTPFile class
//
// Created on: <06-May-2002 10:06:57 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZHTTPFile ezhttpfile.php
  \ingroup eZHTTP
  \brief Provides access to HTTP post files

  This class provides easy access to files posted by clients over HTTP.
  The HTTP file will be present as a temporary file which can be moved
  to store it, if not the file will be removed when the PHP script is done.

*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );

class eZHTTPFile
{
    /*!
     Initializes with a name and http variable.
    */
    function eZHTTPFile( /*! Name of the HTTP variable */ $http_name,
                         /*! The HTTP variable structure */ $variable )
    {
        $this->HTTPName = $http_name;
        $this->OriginalFilename = $variable["name"];
        $this->Type = $variable["type"];
        $mime = explode( "/", $this->Type );
        $this->MimeCategory = $mime[0];
        $this->MimePart = $mime[1];
        $this->Filename = $variable["tmp_name"];
        $this->Size = $variable["size"];
        $this->IsTemporary = true;
    }

    /*!
     \return the directory where the file should be stored.
    */
    function storageDir( $sub_dir = false )
    {
        $sys =& eZSys::instance();
        $storage_dir = $sys->storageDirectory();
        if ( $sub_dir !== false )
            $dir = $storage_dir . "/$sub_dir/" . $this->MimeCategory;
        else
            $dir = $storage_dir . "/" . $this->MimeCategory;
        return $dir;
    }

    /*!
     Stores the temporary file to the destination dir $dir.
    */
    function store( $sub_dir = false, $suffix=false )
    {
        include_once( 'lib/ezfile/classes/ezdir.php' );
        if ( !$this->IsTemporary )
        {
            eZDebug::writeError( "Cannot store non temporary file: " . $this->Filename,
                                 "eZHTTPFile" );
            return false;
        }
        $this->IsTemporary = false;

        $ini =& eZINI::instance();
        $storage_dir = $ini->variable( "FileSettings", "VarDir" ) . '/' . $ini->variable( "FileSettings", "StorageDir" );
        if ( $sub_dir !== false )
            $dir = $storage_dir . "/$sub_dir/";
        else
            $dir = $storage_dir . "/";

        if ( !file_exists( $dir ) )
        {
            $oldumask = umask( 0 );
            $perm = $ini->variable( "FileSettings", "TemporaryPermissions" );
            if ( !eZDir::mkdir( $dir, octdec( $perm ), true ) )
            {
                umask( $oldumask );
                return false;
            }
            umask( $oldumask );
        }

        $dir .= $this->MimeCategory;
        if ( !file_exists( $dir ) )
        {
            $oldumask = umask( 0 );
            $perm = $ini->variable( "FileSettings", "TemporaryPermissions" );
            if ( !eZDir::mkdir( $dir, octdec( $perm ), true ) )
            {
                umask( $oldumask );
                return false;
            }
            umask( $oldumask );
        }

        $suffixString = false;
        if ( $suffix != false )
            $suffixString = ".$suffix";

//        $dest_name = tempnam( $dir, $this->MimePart . "-" );
//        $dest_name = tempnam( $dir , '');
        // the above code does not work on windows.
        $dest_name = $dir .  "/". basename( $this->Filename );

        eZDebug::writeDebug( $this->Filename . " " . $dest_name . $suffixString );
        if ( !move_uploaded_file( $this->Filename, $dest_name . $suffixString ) )
        {
            eZDebug::writeError( "Failed moving uploaded file " . $this->Filename . " to destination $dest_name$suffixString" );
            unlink( $dest_name . $suffixString );
            $ret = false;
        }
        else
        {
            $ret = true;
            $this->Filename = $dest_name . $suffixString;
            $perm = $ini->variable( "FileSettings", "TemporaryPermissions" );
            $oldumask = umask( 0 );
            chmod( $dest_name . $suffixString, octdec( $perm ) );
            umask( $oldumask );

            // Write log message to storage.log
            include_once( 'lib/ezutils/classes/ezlog.php' );
            $storageDir = $dir . "/";
            eZLog::writeStorageLog( basename( $this->Filename ), $storageDir );
        }
        return $ret;
    }

    /*!
     \return an array with the attributes for this object.
    */
    function attributes()
    {
        return array( "original_filename", "filename", "filesize", "is_temporary",
                      "mime_type", "mime_type_category", "mime_type_part" );
    }

    /*!
     \return true if the attribute $attr exists
    */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /*!
     \return the value for the attribute $attr or null if the attribute does not exist.
    */
    function attribute( $attr )
    {
        switch ( $attr )
        {
            case "original_filename":
                return $this->OriginalFilename;
            case "mime_type":
                return $this->Type;
            case "mime_type_category":
                return $this->MimeCategory;
            case "mime_type_part":
                return $this->MimePart;
            case "filename":
                return $this->Filename;
            case "filesize":
                return $this->Size;
            case "is_temporary":
                return $this->IsTemporary;
        };
        return null;
    }

    /*!
     \return true if the HTTP file $http_name can be fetched.
    */
    function canFetch( $http_name )
    {
        $file =& $GLOBALS["eZHTTPFile-$http_name"];
        if ( get_class( $file ) != "ezhttpfile" )
        {
            global $_FILES;

            return isset( $_FILES[$http_name] ) and $_FILES[$http_name]["name"] != "";
        }
        return true;
    }

    /*!
     Fetches the HTTP file named $http_name and returns a eZHTTPFile object,
     or null if the file could not be fetched.
    */
    function &fetch( $http_name )
    {
        $file =& $GLOBALS["eZHTTPFile-$http_name"];
        if ( get_class( $file ) != "ezhttpfile" )
        {
            $file = null;

            global $_FILES;

            if ( isset( $_FILES[$http_name] ) and
                 $_FILES[$http_name]["name"] != "" )
            {
                $file = new eZHTTPFile( $http_name, $_FILES[$http_name] );
            }
            else
                eZDebug::writeError( "Unknown file for post variable: $http_name",
                                     "eZHTTPFile" );
        }
        return $file;
    }

    /// The name of the HTTP file
    var $HTTPName;
    /// The original name of the file from the client
    var $OriginalFilename;
    /// The mime type of the file
    var $Type;
    /// The mimetype category (first part)
    var $MimeCategory;
    /// The mimetype type (second part)
    var $MimePart;
    /// The local filename
    var $Filename;
    /// The size of the local file
    var $Size;
    /// Whether the file is a temporary file or if it has been moved(stored).
    var $IsTemporary;
}

?>

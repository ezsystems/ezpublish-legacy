<?php
//
// Definition of eZHTTPFile class
//
// Created on: <06-May-2002 10:06:57 amos>
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

define( 'EZ_UPLOADEDFILE_OK', 0 );
define( 'EZ_UPLOADEDFILE_DOES_NOT_EXIST', -1 );
define( 'EZ_UPLOADEDFILE_EXCEEDS_PHP_LIMIT', -2 );
define( 'EZ_UPLOADEDFILE_EXCEEDS_MAX_SIZE', -3 );

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
    function store( $sub_dir = false, $suffix = false, $mimeData = false )
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
//         $storage_dir = $ini->variable( "FileSettings", "VarDir" ) . '/' . $ini->variable( "FileSettings", "StorageDir" );
        $storage_dir = eZSys::storageDirectory();
        if ( $sub_dir !== false )
            $dir = $storage_dir . "/$sub_dir/";
        else
            $dir = $storage_dir . "/";
        if ( $mimeData )
            $dir = $mimeData['dirpath'];

        if ( !file_exists( $dir ) )
        {
            $oldumask = umask( 0 );
            if ( !eZDir::mkdir( $dir, eZDir::directoryPermission(), true ) )
            {
                umask( $oldumask );
                return false;
            }
            umask( $oldumask );
        }

        if ( !$mimeData )
        {
            $dir .= $this->MimeCategory;
            if ( !file_exists( $dir ) )
            {
                $oldumask = umask( 0 );
                $perm = $ini->variable( "FileSettings", "StorageDirPermissions" );
                if ( !eZDir::mkdir( $dir, octdec( $perm ), true ) )
                {
                    umask( $oldumask );
                    return false;
                }
                umask( $oldumask );
            }
        }

        $suffixString = false;
        if ( $suffix != false )
            $suffixString = ".$suffix";

        if ( $mimeData )
        {
            $dest_name = $mimeData['url'];
        }
        else
        {
            $dest_name = $dir . "/" . md5( basename( $this->Filename ) . microtime() . mt_rand() ) . $suffixString;
        }

        if ( !move_uploaded_file( $this->Filename, $dest_name ) )
        {
            eZDebug::writeError( "Failed moving uploaded file " . $this->Filename . " to destination $dest_name" );
            unlink( $dest_name );
            $ret = false;
        }
        else
        {
            $ret = true;
            $this->Filename = $dest_name;
            $perm = $ini->variable( "FileSettings", "StorageFilePermissions" );
            $oldumask = umask( 0 );
            chmod( $dest_name, octdec( $perm ) );
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
        return array( "original_filename",
                      "filename",
                      "filesize",
                      "is_temporary",
                      "mime_type",
                      "mime_type_category",
                      "mime_type_part" );
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
    function &attribute( $attr )
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
            default:
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZHTTPFile::attribute' );
                $retValue = null;
                return $retValue;
            } break;
        };
    }

    /*!
     \return true if the HTTP file $http_name can be fetched. If $maxSize is given,
     the function returns
        0 (EZ_UPLOADEDFILE_OK) if the file can be fetched,
       -1 (EZ_UPLOADEDFILE_DOES_NOT_EXIST) if there has been no file uploaded,
       -2 (EZ_UPLOADEDFILE_EXCEEDS_PHP_LIMIT) if the file was uploaded but size
          exceeds the upload_max_size limit (set in the PHP configuration),
       -3 (EZ_UPLOADEDFILE_EXCEEDS_MAX_SIZE) if the file was uploaded but size
          exceeds $maxSize or MAX_FILE_SIZE variable in the form.
    */
    function canFetch( $http_name, $maxSize = false )
    {
        $file =& $GLOBALS["eZHTTPFile-$http_name"];
        if ( get_class( $file ) != "ezhttpfile" )
        {
            if ( $maxSize === false )
            {
                return isset( $_FILES[$http_name] ) and $_FILES[$http_name]['name'] != "" and $_FILES[$http_name]['error'] == 0;
            }

            if ( isset( $_FILES[$http_name] ) and $_FILES[$http_name]['name'] != "" )
            {
                switch ( $_FILES[$http_name]['error'] )
                {
                    case ( UPLOAD_ERR_NO_FILE ):
                    {
                        return EZ_UPLOADEDFILE_DOES_NOT_EXIST;
                    }break;

                    case ( UPLOAD_ERR_INI_SIZE ):
                    {
                        return EZ_UPLOADEDFILE_EXCEEDS_PHP_LIMIT;
                    }break;

                    case ( UPLOAD_ERR_FORM_SIZE ):
                    {
                        return EZ_UPLOADEDFILE_EXCEEDS_MAX_SIZE;
                    }break;

                    default:
                    {
                        return ( $maxSize == 0 || $_FILES[$http_name]['size'] <= $maxSize )? EZ_UPLOADEDFILE_OK:
                                                                                             EZ_UPLOADEDFILE_EXCEEDS_MAX_SIZE;
                    }
                }
            }
            else
            {
                return EZ_UPLOADEDFILE_DOES_NOT_EXIST;
            }
        }
        if ( $maxSize === false )
            return EZ_UPLOADEDFILE_OK;
        else
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

            if ( isset( $_FILES[$http_name] ) and
                 $_FILES[$http_name]["name"] != "" )
            {
                include_once( 'lib/ezutils/classes/ezmimetype.php' );
                include_once( 'lib/ezfile/classes/ezfile.php' );
                $mimeType = eZMimeType::findByURL( $_FILES[$http_name]['name'] );
                $_FILES[$http_name]['type'] = $mimeType['name'];
                $file = new eZHTTPFile( $http_name, $_FILES[$http_name] );
            }
            else
                eZDebug::writeError( "Unknown file for post variable: $http_name",
                                     "eZHTTPFile" );
        }
        return $file;
    }

    /*!
     Changes the MIME-Type to $mime.
    */
    function setMimeType( $mime )
    {
        $this->Type = $mime;
        list ( $this->MimeCategory, $this->MimePart ) = explode( '/', $mime, 2 );
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

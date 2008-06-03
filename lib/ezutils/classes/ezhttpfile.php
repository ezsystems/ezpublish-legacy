<?php
//
// Definition of eZHTTPFile class
//
// Created on: <06-May-2002 10:06:57 amos>
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

/*!
  \class eZHTTPFile ezhttpfile.php
  \ingroup eZHTTP
  \brief Provides access to HTTP post files

  This class provides easy access to files posted by clients over HTTP.
  The HTTP file will be present as a temporary file which can be moved
  to store it, if not the file will be removed when the PHP script is done.

*/

require_once( "lib/ezutils/classes/ezdebug.php" );
//include_once( "lib/ezutils/classes/ezini.php" );

class eZHTTPFile
{
    const UPLOADEDFILE_OK = 0;
    const UPLOADEDFILE_DOES_NOT_EXIST = -1;
    const UPLOADEDFILE_EXCEEDS_PHP_LIMIT = -2;
    const UPLOADEDFILE_EXCEEDS_MAX_SIZE = -3;

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
        $sys = eZSys::instance();
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
        //include_once( 'lib/ezfile/classes/ezdir.php' );
        if ( !$this->IsTemporary )
        {
            eZDebug::writeError( "Cannot store non temporary file: " . $this->Filename,
                                 "eZHTTPFile" );
            return false;
        }
        $this->IsTemporary = false;

        $ini = eZINI::instance();
//         $storage_dir = $ini->variable( "FileSettings", "VarDir" ) . '/' . $ini->variable( "FileSettings", "StorageDir" );
        $storage_dir = eZSys::storageDirectory();
        if ( $sub_dir !== false )
            $dir = $storage_dir . "/$sub_dir/";
        else
            $dir = $storage_dir . "/";
        if ( $mimeData )
            $dir = $mimeData['dirpath'];

        // VS-DBFILE : TODO

        if ( !$mimeData )
        {
            $dir .= $this->MimeCategory;
        }

        if ( !file_exists( $dir ) )
        {
            if ( !eZDir::mkdir( $dir, eZDir::directoryPermission(), true ) )
            {
                return false;
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

        // VS-DBFILE : TODO

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
            //include_once( 'lib/ezfile/classes/ezlog.php' );
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
            default:
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZHTTPFile::attribute' );
                return null;
            } break;
        };
    }

    /*!
     \return true if the HTTP file $http_name can be fetched. If $maxSize is given,
     the function returns
        0 (eZHTTPFile::UPLOADEDFILE_OK) if the file can be fetched,
       -1 (eZHTTPFile::UPLOADEDFILE_DOES_NOT_EXIST) if there has been no file uploaded,
       -2 (eZHTTPFile::UPLOADEDFILE_EXCEEDS_PHP_LIMIT) if the file was uploaded but size
          exceeds the upload_max_size limit (set in the PHP configuration),
       -3 (eZHTTPFile::UPLOADEDFILE_EXCEEDS_MAX_SIZE) if the file was uploaded but size
          exceeds $maxSize or MAX_FILE_SIZE variable in the form.
    */
    static function canFetch( $http_name, $maxSize = false )
    {
        if ( !isset( $GLOBALS["eZHTTPFile-$http_name"] ) ||
             !( $GLOBALS["eZHTTPFile-$http_name"] instanceof eZHTTPFile ) )
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
                        return eZHTTPFile::UPLOADEDFILE_DOES_NOT_EXIST;
                    }break;

                    case ( UPLOAD_ERR_INI_SIZE ):
                    {
                        return eZHTTPFile::UPLOADEDFILE_EXCEEDS_PHP_LIMIT;
                    }break;

                    case ( UPLOAD_ERR_FORM_SIZE ):
                    {
                        return eZHTTPFile::UPLOADEDFILE_EXCEEDS_MAX_SIZE;
                    }break;

                    default:
                    {
                        return ( $maxSize == 0 || $_FILES[$http_name]['size'] <= $maxSize )? eZHTTPFile::UPLOADEDFILE_OK:
                                                                                             eZHTTPFile::UPLOADEDFILE_EXCEEDS_MAX_SIZE;
                    }
                }
            }
            else
            {
                return eZHTTPFile::UPLOADEDFILE_DOES_NOT_EXIST;
            }
        }
        if ( $maxSize === false )
            return eZHTTPFile::UPLOADEDFILE_OK;
        else
            return true;
    }

    /*!
     Fetches the HTTP file named $http_name and returns a eZHTTPFile object,
     or null if the file could not be fetched.
    */
    static function fetch( $http_name )
    {
        if ( !isset( $GLOBALS["eZHTTPFile-$http_name"] ) ||
             !( $GLOBALS["eZHTTPFile-$http_name"] instanceof eZHTTPFile ) )
        {
            $GLOBALS["eZHTTPFile-$http_name"] = null;

            if ( isset( $_FILES[$http_name] ) and
                 $_FILES[$http_name]["name"] != "" )
            {
                //include_once( 'lib/ezutils/classes/ezmimetype.php' );
                //include_once( 'lib/ezfile/classes/ezfile.php' );
                $mimeType = eZMimeType::findByURL( $_FILES[$http_name]['name'] );
                $_FILES[$http_name]['type'] = $mimeType['name'];
                $GLOBALS["eZHTTPFile-$http_name"] = new eZHTTPFile( $http_name, $_FILES[$http_name] );
            }
            else
            {
                eZDebug::writeError( "Unknown file for post variable: $http_name",
                                     "eZHTTPFile" );
            }
        }
        return $GLOBALS["eZHTTPFile-$http_name"];
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
    public $HTTPName;
    /// The original name of the file from the client
    public $OriginalFilename;
    /// The mime type of the file
    public $Type;
    /// The mimetype category (first part)
    public $MimeCategory;
    /// The mimetype type (second part)
    public $MimePart;
    /// The local filename
    public $Filename;
    /// The size of the local file
    public $Size;
    /// Whether the file is a temporary file or if it has been moved(stored).
    public $IsTemporary;
}

?>

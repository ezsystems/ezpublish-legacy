<?php
//
// Definition of eZFileUpload class
//
// Created on: <01-Mar-2002 13:47:37 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of eZ publish, publishing software.
//
// This file may be distributed and/or modified under the terms of the
// GNU General Public License version 2 as published by the Free Software
// Foundation and appearing in the file LICENSE.GPL included in the
// packaging of this file.
//
// Licensees holding valid eZ publish professional licenses may use this
// file in accordance with the eZ publish professional license Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING THE
// WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
//
// See http://ez.no/pricing or email info@ez.no for information about
// eZ publish Commercial License Agreements.
// See http://ez.no/licenses/gpl/ for GPL licensing information.
//
// Contact info@ez.no if any conditions of this licensing are not clear to you.
//

/*!
  \class eZFileUpload ezfileupload.php
  \ingroup eZKernel
  \brief The eZFileUpload class handles fileuploads and other temporary files.

  Example:
  \code
    $file = new eZFileUpload();

    if ( $file->fetchInfo( "userfile" ) )
    {
        print( $file->name() . " uploaded successfully" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    $file2 = new eZFileUpload();
    $file2->createTemporaryFile( "Read this", "readme.txt" );

    $file->move( "filerepository/" );
    $file2->copy( "archive/" );
  \endcode

*/
include_once( "lib/ezutils/classes/ezini.php" );

class eZFileUpload
{
    /*!
      Constructs a new eZFileUpload object
    */
    function eZFileUpload()
    {
    }

    /*!
      Fetches the uploaded file information.

      The $name_var variable is refering to the html <input name="userfile" type="file">

      See the example for more details.
    */
    function fetchInfo( $post_file )
    {
        global $HTTP_POST_FILES;

        $name_var =& $HTTP_POST_FILES[ $post_file ];
        $ret = true;

        $this->FileName = $name_var['name'];
        $type_arr = explode( ";", $name_var['type'] );
        $ftype = $type_arr[0]; // Required for some browsers since the type field may contain more than the mime type.
        $this->FileType = $name_var['type'];
        $this->FileSize = $name_var['size'];
        $this->TmpFileName = $name_var['tmp_name'];

        if ( ( $this->FileSize == "0" ) || ( $this->FileSize == "" ) || ( $this->FileName == "" ) )
        {
            $ret = false;
        }

        return $ret;
    }

    /*!
      Creates a new temporary file and dumps data to it.
    */
    function createTemporaryFile( $data, $fname )
    {
        $this->FileName = $fname;
        $ini =& eZINI::instance();
        $tmpDir = $ini->variable( "FileSettings", "TemporaryDir" );
        $tmpfileName = tempnam( $tmpDir, "ezp" );
        $this->TmpFileName = $tmpfileName;
        $fh = fopen( $tmpfileName, 'wb' );
        fwrite( $fh, $data );
        fclose( $fh );
    }

    /*!
      Moves the uploaded file to the desired directory.

      Returns true if successful.
    */
    function move( $dest )
    {
        if ( $dest != "" )
        {
            // \todo Prepend virtualhost dir to $dest
        }

        $ret = true;
        if ( !move_uploaded_file( $this->TmpFileName, $dest ) )
        {
            $ret = false;
        }
        else
        {
            $ini =& eZINI::instance();
            $perm = $ini->variable( "FileSettings", "TemporaryPermissions" );
            chmod( $dest, $perm );
        }
        return $ret;
    }

   /*!
      Copies the uploaded file to the desired directory.

      Returns true if successful.
    */
    function copy( $dest )
    {
        if ( $dest != "" )
        {
            // \todo Prepend virtualhost dir to $dest
        }

        $ret = true;

        if ( !copy( $this->TmpFileName, $dest ) )
        {
            $ret = false;
        }
        else
        {
            $ini =& eZINI::instance();
            $perm = $ini->variable( "FileSettings", "TemporaryPermissions" );
            chmod( $dest, $perm );
        }
        return $ret;
    }

    /*!
      Returns the original file name.
    */
    function name()
    {
        return $this->FileName;
    }

    /*!
      Returns the file mime type.
    */
    function mimeType()
    {
        return $this->FileType;
    }

    /*!
      Returns the file size.
    */
    function size()
    {
        return $this->FileSize;
    }

    /*!
      Returns the temporary file name.
    */
    function temporaryName()
    {
        return $this->TmpFileName;
    }

    /*!
     Sets the name of the file.
    */
    function setName( $name )
    {
        $this->FileName = $name;
    }

    /*!
      Sets the mime type of the file.
    */
    function setMimeType( $type )
    {
        $this->FileType = $type;
    }

    // The name of the file as it was before being uploaded
    var $FileName;
    // The name of the file in the temporary directory
    var $TmpFileName;
    // The mime type of the file
    var $FileType;
    // The size of the file
    var $FileSize;
}


?>

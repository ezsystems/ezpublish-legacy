<?php
//
// Definition of eZFile class
//
// Created on: <28-Feb-2002 17:31:42 bf>
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
  \class eZFile ezfile.php
  \ingroup eZKernel
  \brief The eZFile class handles files.

  Example:
  \code
    $file = new eZFile( "readme.txt" );
    if ( $file->open( "r+" ) )
    {
    }
  \endcode

*/

class eZFile
{
    /*!
      Constructs a new eZFile object
    */
    function eZFile( $fname = false )
    {
        $this->FileName = $fname;
        $this->FileDesc = false;
    }

    /*!
     Opens the file with mode $mode, if $fd is supplied the file will
     not be opened but instead use $fd as the filedescriptor.
    */
    function open( $mode, $fd = false )
    {
        if ( $fd !== false )
            $fd = fopen( $this->FileName, $mode );
        $this->FileDesc = $fd;
        return $fd !== false;
    }

    /*
     Closes the file and returns true if successfull.
     If no file has been previously opened it will return false.
    */
    function close()
    {
        if ( $this->FileDesc === false )
            return false;
        else
        {
            $ret = fclose( $this->FileDesc );
            $this->FileDesc = false;
            return $ret;
        }
    }

   /*!
      Copies the uploaded file to the desired directory $dest.

      Returns true if successful.
    */
    function copy( $dest )
    {
        if ( $dest != "" )
        {
			global $GlobalSiteIni;
            $dest = $GlobalSiteIni->SiteDir . $dest;
        }

        $ret = true;

        if ( !copy( $this->TmpFileName, $dest ) )
        {
            $ret = false;
        }
        else
            chmod( $dest, 0644 );

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
      Returns the file type.
    */
    function type()
    {
        return $this->FileType;
    }

    /*!
      Returns the file size.
    */
    function size()
    {
        return filesize( $this->FileSize );
    }

    /*!
      Changes the current file to $name.
    */
    function setName( $name )
    {
        $this->FileName = $name;
    }

    /*!
      Sets the mime type of the file.
    */
    function setType( $type )
    {
        $this->FileType = $type;
    }

    /*!
     Prepends the eZSys::siteDir() to the filename and returns it.
     If the filename is returned without any changes.
    */
    function &sitePath( $filename )
    {
        if ( $filename != "" )
            $filename = eZSys::siteDir() . $filename;
        return $filename;
    }

    var $FileName;
    var $FileType;
    var $FileSize;
    var $FileDesc;
}


?>

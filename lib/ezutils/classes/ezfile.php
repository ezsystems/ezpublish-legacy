<?php
//
// Definition of eZFile class
//
// Created on: <03-Jun-2002 17:19:12 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezfile.php
*/

/*!
 \class eZFile ezfile.php
 \ingroup eZUtils
 \brief Tool class which has convencience functions for files and directories

*/

include_once( "lib/ezutils/classes/ezdebug.php" );

class eZFile
{
    /*!
     Constructor
    */
    function eZFile()
    {
    }

    /*!
     \static
     Reads the whole contents of the file \a $file and
     splits it into lines which is collected into an array and returned.
     It will handle Unix (\n), Windows (\r\n) and Mac (\r) style newlines.
     \note The newline character(s) are not present in the line string.
    */
    function &splitLines( $file )
    {
        $fp = @fopen( $file, "r" );
        if ( !$fp )
            return false;
        $size = filesize( $file );
        $contents =& fread( $fp, $size );
        fclose( $fp );
        $lines =& preg_split( "#\r\n|\r|\n#", $contents );
        unset( $contents );
        return $lines;
    }

}

?>

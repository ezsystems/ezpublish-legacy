<?php
//
// Definition of eZMD5 class
//
// Created on: <04-Feb-2004 22:01:19 kk>
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

/*! \file ezmd5.php
*/

/*!
  \class eZMD5 ezmd5.php
  \brief Class handling MD5 file operations
*/

class eZMD5
{
    /*!
     \static

     Check MD5 sum file to check if files have changed. Return array of changed files.

     \param file name of md5 check sums

     \return array of missmatching files.
    */
    function checkMD5Sums( $file )
    {
        include_once( 'lib/ezfile/classes/ezfile.php' );
        $lines = eZFile::splitLines( $file );
        $result = array();

        foreach ( array_keys( $lines ) as $key )
        {
            $line =& $lines[$key];
            if ( strlen( $line ) > 34 )
            {
                $md5Key = substr( $line, 0, 32 );
                $filename = substr( $line, 34 );
                if ( !file_exists( $filename ) || $md5Key != md5_file( $filename ) )
                {
                    $result[] = $filename;
                }
            }
        }

        return $result;
    }
}
?>

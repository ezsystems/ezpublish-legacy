<?php
//
// Definition of eZDir class
//
// Created on: <02-Jul-2002 15:33:41 sp>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*! \file ezdir.php
*/

/*!
  \class eZDir ezdir.php
  \brief The class eZDir does

*/
include_once( "lib/ezutils/classes/ezini.php" );

class eZDir
{
    /*!
     Constructor
    */
    function eZDir()
    {
    }
    function getPathFromFilename( $filename )
    {
        $ini =& eZINI::instance();
        $dirDepth = $ini->variable( "FileSettings" , "DirDepth" );
        $path = '';
        for( $i = 0; $i < $dirDepth; $i++)
        {
            $path= $path . substr( $filename, $i, 1 ) . '/';
        }

        return $path;
    }

    /*!
     \static
     Creates the directory \a $dir with permissions \a $perm.
     If \a $parents is true it will create any missing parent directories,
     just like 'mkdir -p'.
    */
    function mkdir( $dir, $perm, $parents = false )
    {
        if ( !$parents )
            return eZDir::doMkdir( $dir, $perm );
        else
            return eZDir::mkdirRecursive( $dir, $perm );
    }

    /*!
     \static
     \private
     Creates directories recursively like 'mkdir -p', calls
     either itself or doMkDir.
    */
    function mkdirRecursive( $dir, $perm )
    {
        if ( file_exists( $dir ) )
            return true;
        else
        {
            $new_dir = preg_replace( "#/+#", "/", $dir );
            if ( $dir[0] != "/" )
                $new_dir = realpath( "." ) . "/$new_dir";
            if ( preg_match( "#^(.+/)([^/]+)/?$#", $new_dir, $regs ) )
            {
                $new_dir = $regs[1];
            }
            if ( !eZDir::mkdirRecursive( $new_dir, $perm ) )
                return false;
        }
        if ( !eZFile::doMkdir( $dir, $perm ) )
            return false;
        return true;
    }

    /*!
     \static
     \private
     Creates the directory \a $dir with permission \a $perm.
    */
    function doMkdir( $dir, $perm )
    {
        $oldumask = umask( 0 );
        if ( !mkdir( $dir, $perm ) )
        {
            umask( $oldumask );
            return false;
        }
        umask( $oldumask );
        return true;
    }


}

?>

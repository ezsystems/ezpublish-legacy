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
include_once( "lib/ezutils/classes/ezsys.php" );

define( 'EZ_DIR_SEPARATOR_LOCAL', 1 );
define( 'EZ_DIR_SEPARATOR_UNIX', 2 );
define( 'EZ_DIR_SEPARATOR_DOS', 3 );

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
                $new_dir = "$new_dir";
// Fix to make this work on windows.. To quick ?
//                $new_dir = realpath( "." ) . "/$new_dir";
            if ( preg_match( "#^(.+/)([^/]+)/?$#", $new_dir, $regs ) )
            {
                $new_dir = $regs[1];
            }
            if ( !eZDir::mkdirRecursive( $new_dir, $perm ) )
                return false;
        }
        if ( !eZDir::doMkdir( $dir, $perm ) )
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
        if ( ! @mkdir( $dir, $perm ) )
        {
            umask( $oldumask );
			// eZDebug::writeError( "Couldn't create the directory \"$dir\".", "eZDir::doMkdir()" );
            return false;
        }
        umask( $oldumask );
        return true;
    }

    /*!
     \static
     \return the separator used between directories and files according to \a $type.

     Type can be one of the following:
     - EZ_DIR_SEPARATOR_LOCAL - Returns whatever is applicable for the current machine.
     - EZ_DIR_SEPARATOR_UNIX  - Returns a /
     - EZ_DIR_SEPARATOR_DOS   - Returns a \
    */
    function separator( $type )
    {
        switch ( $type )
        {
            case EZ_DIR_SEPARATOR_LOCAL:
                return eZSys::fileSeparator();
            case EZ_DIR_SEPARATOR_UNIX:
                return '/';
            case EZ_DIR_SEPARATOR_DOS:
                return "\\";
        }
        return null;
    }

    /*!
     \static
     Creates a path out of all the dir and file items in the array \a $names
     with correct separators in between them.
     It will also remove unneeded separators.
     \a $type is used to determine the separator type, see eZDir::separator.
     If \a $includeEndSeparator is true then it will make sure that the path ends with a
     separator if false it make sure there are no end separator.
    */
    function path( $names, $includeEndSeparator = false, $type = EZ_DIR_SEPARATOR_LOCAL )
    {
        $separator = eZDir::separator( $type );
        $path = implode( $separator, $names );
        $path = preg_replace( '#//+#', '/', $path );
        $hasEndSeparator = ( strlen( $path ) > 0 and
                         $path[strlen( $path ) - 1] == $separator );
        if ( $includeEndSeparator and
             !$hasEndSeparator )
            $path .= $separator;
        else if ( !$includeEndSeparator and
                  $hasEndSeparator )
            $path = substr( $path, 0, strlen( $path ) - 1 );
        return $path;
    }

}

?>

<?php
//
// Definition of eZDir class
//
// Created on: <02-Jul-2002 15:33:41 sp>
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
        for ( $i = 0; $i < $dirDepth and $i < strlen( $filename ); $i++ )
        {
            $path = $path . substr( $filename, $i, 1 ) . '/';
        }

        return $path;
    }

    function filenamePath( $filename, $maxCharLen = 2 )
    {
        $path = '';
        for ( $i = 0; $i < strlen( $filename ) and ( strlen( $filename ) - $i ) > $maxCharLen;
              $i++ )
        {
            $path = $path . substr( $filename, $i, 1 ) . '/';
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
        $dir = eZDir::cleanPath( $dir, EZ_DIR_SEPARATOR_UNIX );
//        print( "About to mkdir( '$dir' )<br/>" );
//        exit;
        if ( !$parents )
            return eZDir::doMkdir( $dir, $perm );
        else
        {
            $dirElements = explode( '/', $dir );
            if ( count( $dirElements ) == 0 )
                return true;
            $currentDir = $dirElements[0];
            $result = true;
            if ( !file_exists( $currentDir ) and $currentDir != "" )
                $result = eZDir::doMkdir( $currentDir, $perm );
            if ( !$result )
                return false;
            for ( $i = 1; $i < count( $dirElements ); ++$i )
            {
                $dirElement = $dirElements[$i];
                $currentDir .= '/' . $dirElement;
//                print( "trying '$dir' )<br/>" );
                $result = true;
                if ( !file_exists( $currentDir ) )
                    $result = eZDir::doMkdir( $currentDir, $perm );
                if ( !$result )
                    return false;
            }
        }
    }

/*
    function mkdirRecursive( $dir, $perm )
    {
        if ( file_exists( $dir ) )
            return true;
        else
        {
//            $new_dir = preg_replace( "#/+#", "/", $dir );
//            if ( $dir[0] != "/" )
//                $new_dir = "$new_dir";
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
*/

    /*!
     \static
     \private
     Creates the directory \a $dir with permission \a $perm.
    */
    function doMkdir( $dir, $perm )
    {
        eZDebugSetting::writeDebug( 'lib-ezutils-dir', "Make dir $dir with perms 0" . decoct( $perm ) );
//        print( "About to doMkdir( '$dir' )<br/>" );
//        exit;
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
     Converts any directory separators found in \a $path, in both unix and dos style, into
     the separator type specified by \a $toType and returns it.
    */
    function convertSeparators( $path, $toType = EZ_DIR_SEPARATOR_UNIX )
    {
        $separator = eZDir::separator( $toType );
        return preg_replace( "#[/\\\\]#", $separator, $path );
    }

    /*!
     \static
     Removes all unneeded directory separators and resolves any "."s and ".."s found in \a $path.

     For instance: "var/../lib/ezdb" becomes "lib/ezdb", while "../site/var" will not be changed.
     \note Will also convert separators
     \sa convertSeparators.
    */
    function cleanPath( $path, $toType = EZ_DIR_SEPARATOR_UNIX )
    {
        $path = eZDir::convertSeparators( $path, $toType );
        $separator = eZDir::separator( $toType );
        $path = preg_replace( "#$separator$separator+#", $separator, $path );
        $pathElements = explode( $separator, $path );
        $newPathElements = array();
        foreach ( $pathElements as $pathElement )
        {
            if ( $pathElement == '.' )
                continue;
            if ( $pathElement == '..' and
                 count( $newPathElements ) > 0 )
                array_pop( $newPathElements );
            else
                $newPathElements[] = $pathElement;
        }
        if ( count( $newPathElements ) == 0 )
            $newPathElements[] = '.';
        $path = implode( $separator, $newPathElements );
        return $path;
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
    function path( $names, $includeEndSeparator = false, $type = EZ_DIR_SEPARATOR_UNIX )
    {
        $separator = eZDir::separator( $type );
        $path = implode( $separator, $names );
        $path = eZDir::cleanPath( $path, $type );
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

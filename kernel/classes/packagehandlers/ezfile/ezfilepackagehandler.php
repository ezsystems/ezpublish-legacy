<?php
//
// Definition of eZFilePackageHandler class
//
// Created on: <23-Jul-2003 16:11:42 amos>
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

/*! \file ezfilepackagehandler.php
*/

/*!
  \class eZFilePackageHandler ezfilepackagehandler.php
  \brief Handles content classes in the package system

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezpackagehandler.php' );

class eZFilePackageHandler extends eZPackageHandler
{
    /*!
     Constructor
    */
    function eZFilePackageHandler()
    {
        $this->eZPackageHandler();
    }

    function install( &$package, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content )
    {
        print( "name=$name, os=$os, filename=$filename, subdirectory=$subdirectory, $content\n" );
    }

    /*!
     \reimp
    */
    function add( &$package, &$cli, $parameters )
    {
        foreach ( $parameters['file-list'] as $fileItem )
        {
            $cli->notice( "Adding file " . $fileItem['file'] . " (" . $fileItem['type'] . ", " . $fileItem['design'] . ", " . $fileItem['role'] . ") to package" );
//             $package->appendInstall( 'ezfile', false, false, true,
//                                      'class-' . $classID, 'ezfile',
//                                      array( 'content' => $classNode ) );
//             $package->appendProvides( 'ezfile', $class->attribute( 'identifier' ) );
//             $package->appendInstall( 'ezfile', false, false, false,
//                                      'class-' . $classID, 'ezfile',
//                                      array( 'content' => false ) );
        }
    }

    function handleAddParameters( &$package, &$cli, $arguments )
    {
        return $this->handleParameters( $package, $cli, 'add', $arguments );
    }

    function handleParameters( &$package, &$cli, $type, $arguments )
    {
        $fileList = array();
        $currentType = 'file';
        $currentRole = false;
        $currentDesign = false;
        for ( $i = 0; $i < count( $arguments ); ++$i )
        {
            $argument = $arguments[$i];
            if ( $argument[0] == '-' )
            {
                if ( strlen( $argument ) > 1 and
                     $argument[1] == '-' )
                {
                }
                else
                {
                    $flag = substr( $argument, 1, 1 );
                    if ( $flag == 't' or
                         $flag == 'r' or
                         $flag == 'd' )
                    {
                        if ( strlen( $argument ) > 2 )
                        {
                            $data = substr( $argument, 2 );
                        }
                        else
                        {
                            $data = $arguments[$i+1];
                            ++$i;
                        }
                        if ( $flag == 't' )
                        {
                            if ( !in_array( $data, array( 'design', 'file' ) ) )
                            {
                                $cli->error( "Unknown file type $data, allowed values are design and file" );
                                return false;
                            }
                            $currentType = $data;
                            $currentRole = false;
                            $currentDesign = false;
                            if ( $currentType == 'design' )
                            {
                                $currentRole = 'template';
                                $currentDesign = 'standard';
                            }
                        }
                        else if ( $flag == 'r' )
                        {
                            if ( $currentType != 'design' )
                            {
                                $cli->error( "The current file type is not 'design' ($currentType), cannot set specific roles for files" );
                                return false;
                            }
                            if ( !$this->roleExists( $currentType, $data ) )
                            {
                                $cli->error( "Unknown file role $data for file type $currentType" );
                                return false;
                            }
                            $currentRole = $data;
                        }
                        else if ( $flag == 'd' )
                        {
                            if ( $currentType != 'design' )
                            {
                                $cli->error( "The current file type is not 'design' ($currentType), cannot set specific designs for files" );
                                return false;
                            }
                            if ( !$this->designExists( $data ) )
                            {
                                $cli->error( "The design $data does not exist" );
                                return false;
                            }
                            $currentDesign = $data;
                        }
                    }
                }
            }
            else
            {
                $file = $argument;
                if ( !$this->fileExists( $file, $currentType, $currentRole, $currentDesign ) )
                {
                    $cli->error( "File $file does not exist" );
                    return false;
                }
                $fileList[] = array( 'file' => $file,
                                     'type' => $currentType,
                                     'role' => $currentRole,
                                     'design' => $currentDesign );
            }
        }
        if ( count( $fileList ) == 0 )
        {
            $cli->error( "No files were added" );
            return false;
        }
        return array( 'file-list' => $fileList );
    }

    function roleExists( $type, $role )
    {
        if ( $type == 'design' )
            return in_array( $role,
                             array( 'template', 'image', 'stylesheet', 'font' ) );
        return false;
    }

    function designExists( $design )
    {
        return file_exists( 'design/' . $design );
    }

    function fileExists( $file, $type, $role, $design )
    {
        switch ( $type )
        {
            case 'file':
            {
                return file_exists( $file );
            } break;
            case 'design':
            {
                switch ( $role )
                {
                    case 'template':
                    {
                        $roleFileName = 'templates';
                    } break;
                    case 'image':
                    {
                        $roleFileName = 'images';
                    } break;
                    case 'stylesheet':
                    {
                        $roleFileName = 'stylesheets';
                    } break;
                    case 'font':
                    {
                        $roleFileName = 'fonts';
                    } break;
                }
                return file_exists( 'design/' . $design . '/' . $roleFileName . '/' . $file );
            } break;
        }
        return false;
    }

}

?>

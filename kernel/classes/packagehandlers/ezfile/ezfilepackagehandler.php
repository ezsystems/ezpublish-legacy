<?php
//
// Definition of eZFilePackageHandler class
//
// Created on: <23-Jul-2003 16:11:42 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
        $this->eZPackageHandler( 'ezfile' );
    }

    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, $installParameters )
    {
        $collectionName = $parameters['collection'];
        $installVariables = array();
        if ( isset( $installParameters['variables'] ) )
            $installVariables = $installParameters['variables'];
        $iniFileVariables = false;
        if ( isset( $installParameters['ini'] ) )
            $iniFileVariables = $installParameters['ini'];
        $fileList = $package->fileList( $collectionName );
        if ( $fileList )
        {
            foreach ( $fileList as $fileItem )
            {
                $newFilePath = false;
                if ( $fileItem['type'] == 'thumbnail' )
                {
                }
                else
                {
                    $filePath = $package->fileItemPath( $fileItem, $collectionName );
                    if ( is_dir( $filePath ) )
                    {
                        $newFilePath = $package->fileStorePath( $fileItem, $collectionName, $installParameters['path'], $installVariables );
                        eZDir::mkdir( $newFilePath, eZDir::directoryPermission(), true );
                    }
                    else
                    {
                        $newFilePath = $package->fileStorePath( $fileItem, $collectionName, $installParameters['path'], $installVariables );
                        if ( preg_match( "#^(.+)/[^/]+$#", $newFilePath, $matches ) )
                        {
                            eZDir::mkdir( $matches[1], eZDir::directoryPermission(), true );
                        }
                        eZFileHandler::copy( $filePath, $newFilePath );
                    }
                }
                if ( $fileItem['type'] == 'ini' and $iniFileVariables and $newFilePath )
                {
                    $fileRole = $fileItem['role'];
                    $fileRoleValue = $fileItem['role-value'];
                    $fileVariableName = $fileItem['variable-name'];
                    $fileName = $fileItem['name'];
                    if ( $fileVariableName and
                         isset( $installParameters['variables'][$fileVariableName] ) )
                        $fileRoleValue = $installParameters['variables'][$fileVariableName];
                    if ( isset( $iniFileVariables[$fileRole][$fileRoleValue][$fileName] ) )
                    {
                        $variables = $iniFileVariables[$fileRole][$fileRoleValue][$fileName];
                        $ini =& eZINI::fetchFromFile( $newFilePath );
                        $ini->setVariables( $variables );
                        $ini->save( false, false, false, false, false );
                    }
                }
            }
        }
        return true;
    }

    /*!
     \reimp
    */
    function add( $packageType, &$package, &$cli, $parameters )
    {
        $collections = array();
        foreach ( $parameters['file-list'] as $fileItem )
        {
            $package->appendFile( $fileItem['file'], $fileItem['type'], $fileItem['role'],
                                  $fileItem['design'], $fileItem['path'], $fileItem['collection'],
                                  null, null, true, null,
                                  $fileItem['file-type'], $fileItem['role-value'], $fileItem['variable-name'] );
            if ( !in_array( $fileItem['collection'], $collections ) )
                $collections[] = $fileItem['collection'];
            $addString = "Adding file " . $cli->stylize( 'file', $fileItem['file'] ) . " (" . $fileItem['type'] . ", " . $fileItem['design'] . ", " . $fileItem['role'] . ")";
            if ( $fileItem['variable-name'] )
                $addString .= '[' . $fileItem['variable-name'] . ']';
            $addString .= " to package";
            $cli->notice( $addString );
        }
        foreach ( $collections as $collection )
        {
            $installItems = $package->installItems( 'ezfile', false, $collection, true );
            if ( count( $installItems ) == 0 )
                $package->appendInstall( 'ezfile', false, false, true,
                                         false, false,
                                         array( 'collection' => $collection ) );
            $dependencyItems = $package->dependencyItems( 'provides', 'ezfile', 'collection', $collection );
            if ( count( $dependencyItems ) == 0 )
                $package->appendDependency( 'provides', 'ezfile', 'collection', $collection );
            $installItems = $package->installItems( 'ezfile', false, $collection, false );
            if ( count( $installItems ) == 0 )
                $package->appendInstall( 'ezfile', false, false, false,
                                         false, false,
                                         array( 'collection' => $collection ) );
        }
    }

    function handleAddParameters( $packageType, &$package, &$cli, $arguments )
    {
        return $this->handleParameters( $packageType, $package, $cli, 'add', $arguments );
    }

    function handleParameters( $packageType, &$package, &$cli, $type, $arguments )
    {
        $fileList = array();
        $currentType = 'file';
        $currentVariableName = false;
        $currentRole = false;
        $currentRoleValue = false;
        $currentDesign = false;
        $currentCollection = 'default';
        if ( $packageType == 'design' )
        {
            $currentType = 'design';
        }
        else if ( $packageType == 'ini' )
        {
            $currentType = 'ini';
            $currentRole = 'standard';
        }
        else if ( $packageType == 'template' )
        {
            $currentType = 'design';
            $currentRole = 'template';
        }
        else if ( $packageType == 'thumbnail' )
        {
            $currentType = 'thumbnail';
            $currentRole = false;
        }
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
                         $flag == 'n' or
                         $flag == 'v' or
                         $flag == 'd' or
                         $flag == 'c' )
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
                            if ( !in_array( $data, array( 'design', 'ini', 'file', 'thumbnail' ) ) )
                            {
                                $cli->error( "Unknown file type $data, allowed values are design, ini, thumbnail and file" );
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
                            if ( $currentType != 'design' and
                                 $currentType != 'ini' )
                            {
                                $cli->error( "The current file type is not 'design' or 'ini' ($currentType), cannot set specific roles for files" );
                                return false;
                            }
                            if ( !$this->roleExists( $currentType, $data ) )
                            {
                                $cli->error( "Unknown file role $data for file type $currentType" );
                                return false;
                            }
                            $currentRole = $data;
                        }
                        else if ( $flag == 'v' )
                        {
                            $currentRoleValue = $data;
                        }
                        else if ( $flag == 'n' )
                        {
                            $currentVariableName = $data;
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
                        else if ( $flag == 'c' )
                        {
                            $currentCollection = $data;
                        }
                    }
                }
            }
            else
            {
                $file = $argument;
                $type = $currentType;
                $role = $currentRole;
                $roleValue = $currentRoleValue;
                $design = $currentDesign;
                $realFilePath = $this->fileExists( $file, $type, $role, $roleValue, $design,
                                                   $triedFiles );
                if ( !$realFilePath )
                {
                    $cli->error( "File " . $cli->style( 'file' ) . $file . $cli->style( 'file-end' ) . " does not exist\n" .
                                 "The following files were searched for:\n" .
                                 implode( "\n", $triedFiles ) );
                    return false;
                }
                $fileFileType = false;
                if ( is_dir( $realFilePath ) )
                    $fileFileType = 'dir';
                $fileList[] = array( 'file' => $file,
                                     'type' => $type,
                                     'role' => $role,
                                     'role-value' => $roleValue,
                                     'variable-name' => $currentVariableName,
                                     'file-type' => $fileFileType,
                                     'design' => $design,
                                     'collection' => $currentCollection,
                                     'path' => $realFilePath );
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
        if ( $type == 'ini' )
            return in_array( $role,
                             array( 'standard', 'siteaccess', 'override' ) );
        return false;
    }

    function designExists( $design )
    {
        return file_exists( 'design/' . $design );
    }

    function fileExists( &$file, &$type, &$role, &$roleValue, &$design,
                         &$triedFiles )
    {
        $triedFiles = array();
        switch ( $type )
        {
            case 'file':
            {
                if ( file_exists( $file ) )
                    return $file;
                $triedFiles[] = $file;
            } break;
            case 'ini':
            {
                $filePath = $file;
                if ( file_exists( $filePath ) )
                {
                    if ( preg_match( "#^settings/siteaccess/([^/]+)/([^/]+)$#", $filePath, $matches ) )
                    {
                        $role = 'siteaccess';
                        $roleValue = $matches[1];
                        $file = $matches[2];
                        return $filePath;
                    }
                    else if ( preg_match( "#^settings/override/([^/]+)$#", $filePath, $matches ) )
                    {
                        $role = 'override';
                        $roleValue = false;
                        $file = $matches[1];
                        return $filePath;
                    }
                    else if ( preg_match( "#^settings/([^/]+)$#", $filePath, $matches ) )
                    {
                        $role = 'standard';
                        $roleValue = false;
                        $file = $matches[1];
                        return $filePath;
                    }
                }
                $triedFiles[] = $filePath;
                $filePath = 'settings';
                if ( $role == 'siteaccess' )
                {
                    $filePath = 'settings/siteaccess';
                    if ( $roleValue )
                        $filePath .= '/' . $roleValue;
                }
                else if ( $role == 'override' )
                    $filePath = 'settings/override';
                $filePath .= '/' . $file;
                if ( file_exists( $filePath ) )
                {
                    return $filePath;
                }
                $triedFiles[] = $filePath;
                $filePath = $file;
                if ( file_exists( $filePath ) )
                {
                    if ( preg_match( "#^.+/([^/]+)$#", $filePath, $matches ) )
                        $file = $matches[1];
                    return $filePath;
                }
                $triedFiles[] = $filePath;
            } break;
            case 'thumbnail':
            {
                if ( file_exists( $file ) )
                {
                    $filePath = $file;
//                     if ( preg_match( "#^(.+)/([^/]+)$#", $file, $matches ) )
//                     {
//                         $file = $matches[2];
//                     }
                    if ( preg_match( "#^(.+)\.([^.]+)$#", $file, $matches ) )
                    {
                        $file = 'thumbnail.' . $matches[2];
                    }
                    return $filePath;
                }
                $triedFiles[] = $file;
            } break;
            case 'design':
            {
                $roleFileName = false;
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
                $designDirectories = array( 'design' );
                $extensionBaseDirectory = eZExtension::baseDirectory();
                $ini =& eZINI::instance( 'design.ini' );
                $extensionDesigns = $ini->variable( 'ExtensionSettings', 'DesignExtensions' );
                foreach ( $extensionDesigns as $extensionDesign )
                {
                    $designDirectories[] = $extensionBaseDirectory . '/' . $extensionDesign . '/design';
                }
                if ( file_exists( $file ) )
                {
                    $preg = '#^';
                    $i = 0;
                    foreach ( $designDirectories as $designDirectory )
                    {
                        if ( $i > 0 )
                            $preg .= '|';
                        $preg .= '(?:' . $designDirectory . ')';
                        ++$i;
                    }
                    $preg .= '/([^/]+)/(.+)$#';
                    $realFile = $file;
                    if ( preg_match( $preg, $file, $matches ) )
                    {
                        $design = $matches[1];
                        if ( preg_match( '#^(template(?:s)|image(?:s)|stylesheet(?:s)|font(?:s))/(.+)$#', $matches[2], $matches ) )
                        {
                            $role = $matches[1];
                            $file = $matches[2];
                        }
                        else
                        {
                            $file = $matches[2];
                            $role = false;
                        }
                    }
                    else
                    {
                        $type = 'file';
                        $role = false;
                        $design = false;
                    }
                    return $realFile;
                }
                $triedFiles[] = $file;
                if ( !$design )
                {
                    foreach ( $designDirectories as $designDirectory )
                    {
                        $filePath = $designDirectory . '/standard/' . $roleFileName . '/' . $file;
                        if ( file_exists( $filePath ) )
                        {
                            $design = 'standard';
                            return $filePath;
                        }
                        $triedFiles[] = $filePath;
                        if ( !file_exists( $designDirectory ) or
                             !is_dir( $designDirectory ) )
                            continue;
                        $dirHandler = @opendir( $designDirectory );
                        if ( !$dirHandler )
                            continue;
                        while ( ( $designSubDirectory = @readdir( $dirHandler ) ) !== false )
                        {
                            if ( $designSubDirectory == '.' or $designSubDirectory == '..' )
                                continue;
                            $filePath = $designDirectory . '/' . $designSubDirectory . '/' . $roleFileName . '/' . $file;
                            if ( file_exists( $filePath ) )
                            {
                                @closedir( $dirHandler );
                                $design = $designSubDirectory;
                                return $filePath;
                            }
                            $triedFiles[] = $filePath;
                        }
                        @closedir( $dirHandler );
                    }
                }
                foreach ( $designDirectories as $designDirectory )
                {
                    if ( !file_exists( $designDirectory ) or
                         !is_dir( $designDirectory ) )
                        continue;

                    $filePath = $designDirectory . '/' . $file;
                    if ( file_exists( $filePath ) )
                    {
                        if ( preg_match( "#^([^/]+)/(.+)$#", $file, $matches ) )
                        {
                            $design = $matches[1];
                            $file = $matches[2];
                        }
                        else
                        {
                            $design = $file;
                            $file = false;
                        }
                        return $filePath;
                    }

                    $filePath = $designDirectory . '/' . $design . '/' . $roleFileName . '/' . $file;
                    if ( file_exists( $filePath ) )
                        return $filePath;
                    $triedFiles[] = $filePath;
                }
            } break;
        }
        return false;
    }

    /*!
     \reimp
    */
    function createInstallNode( &$package, $export, &$installNode, $installItem, $installType )
    {
        $installNode->appendAttribute( eZDOMDocument::createAttributeNode( 'collection', $installItem['collection'] ) );
    }

    /*!
     \reimp
    */
    function parseInstallNode( &$package, &$installNode, &$installParameters, $isInstall )
    {
        $collection = $installNode->attributeValue( 'collection' );
        $installParameters['collection'] = $collection;
    }
}

?>

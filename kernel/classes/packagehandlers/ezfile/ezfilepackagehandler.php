<?php
//
// Definition of eZFilePackageHandler class
//
// Created on: <23-Jul-2003 16:11:42 amos>
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

/*! \file ezfilepackagehandler.php
*/

/*!
  \class eZFilePackageHandler ezfilepackagehandler.php
  \brief Handles content classes in the package system

*/

class eZFilePackageHandler extends eZPackageHandler
{
    /*!
     Constructor
    */
    function eZFilePackageHandler()
    {
        $this->eZPackageHandler( 'ezfile' );
    }

    function install( $package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      $content, &$installParameters,
                      &$installData )
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
                        eZDir::mkdir( $newFilePath, false, true );
                    }
                    else
                    {
                        $newFilePath = $package->fileStorePath( $fileItem, $collectionName, $installParameters['path'], $installVariables );
                        if ( preg_match( "#^(.+)/[^/]+$#", $newFilePath, $matches ) )
                        {
                            eZDir::mkdir( $matches[1], false, true );
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

    function add( $packageType, $package, $cli, $parameters )
    {
        $collections = array();
        foreach ( $parameters['file-list'] as $fileItem )
        {
            $package->appendFile( $fileItem['file'], $fileItem['type'], $fileItem['role'],
                                  $fileItem['design'], $fileItem['path'], $fileItem['collection'],
                                  null, null, true, null,
                                  $fileItem['file-type'], $fileItem['role-value'], $fileItem['variable-name'],
                                  $fileItem['package-path'] );
            if ( !in_array( $fileItem['collection'], $collections ) )
                $collections[] = $fileItem['collection'];
            $addString = "Adding " . $cli->stylize( 'mark', $fileItem['type'] );
            if ( $fileItem['type'] != 'design' )
                $addString .= " " . $cli->stylize( 'file', $fileItem['file'] );
            if ( $fileItem['type'] == 'design' )
                $addString .= " " . $cli->stylize( 'dir', $fileItem['design'] );
            if ( ( $fileItem['type'] == 'design' or $fileItem['type'] == 'ini' ) and
                 $fileItem['role'] )
                $addString .= " using role " . $cli->stylize( 'italic', $fileItem['role'] );
            if ( $fileItem['variable-name'] )
                $addString .= " bound to variable " . $cli->stylize( 'variable', $fileItem['variable-name'] );
//            . " (" . $fileItem['design'] . ", " . $fileItem['role'] . ")";
//            if ( $fileItem['variable-name'] )
//                $addString .= '[' . $fileItem['variable-name'] . ']';
            $cli->notice( $addString );
        }
        foreach ( $collections as $collection )
        {
            $installItems = $package->installItemsList( 'ezfile', false, $collection, true );
            if ( count( $installItems ) == 0 )
                $package->appendInstall( 'ezfile', false, false, true,
                                         false, false,
                                         array( 'collection' => $collection ) );
            $dependencyItems = $package->dependencyItems( 'provides',
                                                          array( 'type'  => 'ezfile',
                                                                 'name'  => 'collection',
                                                                 'value' =>  $collection ) );
            if ( count( $dependencyItems ) == 0 )
                $package->appendDependency( 'provides',
                                            array( 'type'  => 'ezfile',
                                                   'name'  => 'collection',
                                                   'value' => $collection ) );
            $installItems = $package->installItemsList( 'ezfile', false, $collection, false );
            if ( count( $installItems ) == 0 )
                $package->appendInstall( 'ezfile', false, false, false,
                                         false, false,
                                         array( 'collection' => $collection ) );
        }
    }

    function handleAddParameters( $packageType, $package, $cli, $arguments )
    {
        return $this->handleParameters( $packageType, $package, $cli, 'add', $arguments );
    }

    function handleParameters( $packageType, $package, $cli, $type, $arguments )
    {
        $fileList = array();
        $currentType = 'file';
        $currentVariableName = false;
        $currentRole = false;
        $currentRoleValue = false;
        $currentDesign = false;
        $currentCollection = 'default';
        $packagePath = false;
        if ( $packageType == 'design' )
        {
            $currentType = 'design';
        }
        else if ( $packageType == 'dir' )
        {
            $currentType = 'dir';
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
                         $flag == 'p' or
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
                        else if ( $flag == 'p' )
                        {
                            $packagePath = $data;
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
                    $error = ( "File " . $cli->stylize( 'file', $file ) . " does not exist\n" .
                               "The following files were searched for:\n" );
                    $files = array();
                    foreach ( $triedFiles as $triedFile )
                    {
                        $files[] = $cli->stylize( 'file', $triedFile );
                    }
                    $cli->output( $error . implode( "\n", $files ) );
                    return false;
                }
                $fileFileType = false;
                if ( is_dir( $realFilePath ) )
                    $fileFileType = 'dir';
                if ( $currentType == 'ini' and
                     $fileFileType == 'dir' )
                {
                    $iniFiles = eZDir::recursiveFind( $realFilePath, "" );
                    $fileFileType = 'file';
                    foreach ( $iniFiles as $iniFile )
                    {
                        $iniFile = $this->iniMatch( $iniFile, $role, $roleValue, $file, $triedFiles );
                        if ( !$iniFile )
                            continue;
                        $fileList[] = array( 'file' => $file,
                                             'package-path' => $packagePath,
                                             'type' => $type,
                                             'role' => $role,
                                             'role-value' => $roleValue,
                                             'variable-name' => $currentVariableName,
                                             'file-type' => $fileFileType,
                                             'design' => $design,
                                             'collection' => $currentCollection,
                                             'path' => $iniFile );
                    }
                }
                else
                {
                    $fileList[] = array( 'file' => $file,
                                         'package-path' => $packagePath,
                                         'type' => $type,
                                         'role' => $role,
                                         'role-value' => $roleValue,
                                         'variable-name' => $currentVariableName,
                                         'file-type' => $fileFileType,
                                         'design' => $design,
                                         'collection' => $currentCollection,
                                         'path' => $realFilePath );
                }
                $realPath = false;
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
            case 'dir':
            {
                if ( file_exists( $file ) and is_dir( $file ) )
                    return $file;
                $triedFiles[] = $file;
            } break;
            case 'ini':
            {
                $filePath = $file;
                if ( file_exists( $filePath ) )
                {
                    $filePath = $this->iniMatch( $filePath, $role, $roleValue, $file, $triedFiles );
                    if ( $filePath )
                        return $filePath;
                }
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
                $ini = eZINI::instance( 'design.ini' );
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

    function iniMatch( $filePath, &$role, &$roleValue, &$file )
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
        return false;
    }

    function createInstallNode( $package, $installNode, $installItem, $installType )
    {
        $installNode->setAttribute( 'collection', $installItem['collection'] );
    }

    function parseInstallNode( $package, $installNode, &$installParameters, $isInstall )
    {
        $collection = $installNode->getAttribute( 'collection' );
        $installParameters['collection'] = $collection;
    }
}

?>

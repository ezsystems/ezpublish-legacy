<?php
//
// Definition of eZDBPackageHandler class
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

/*! \file ezdbpackagehandler.php
*/

/*!
  \class eZDBPackageHandler ezdbpackagehandler.php
  \brief Handles content classes in the package system

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezpackagehandler.php' );

class eZDBPackageHandler extends eZPackageHandler
{
    /*!
     Constructor
    */
    function eZDBPackageHandler()
    {
        $this->eZPackageHandler( 'ezdb' );
    }

    /*!
     Installs the package type
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, $installParameters )
    {
        if ( $installType == 'sql' )
        {
            $path = $package->path();
            $databaseType = false;
            if ( isset( $parameters['database-type'] ) )
                $databaseType = $parameters['database-type'];
            $path .= '/' . eZDBPackageHandler::sqlDirectory();
            if ( $databaseType )
                $path .= '/' . $databaseType;
            if ( file_exists( $path ) )
            {
                $db =& eZDB::instance();
                $canInsert = true;
                if ( $databaseType and
                     $databaseType != $db->databaseName() )
                    $canInsert = false;
                if ( $canInsert )
                {
                    eZDebug::writeDebug( "Installing SQL file $path/$filename" );
                    $db->insertFile( $path, $filename, false );
                    return true;
                }
                else
                    eZDebug::writeDebug( "Skipping SQL file $path/$filename" );
            }
            else
                eZDebug::writeError( "Could not find SQL file $path/$filename" );
        }
        return false;
    }

    /*!
     \reimp
    */
    function add( $packageType, &$package, &$cli, $parameters )
    {
        if ( isset( $parameters['sql-file-list'] ) )
        {
            foreach ( $parameters['sql-file-list'] as $fileItem )
            {
                $package->appendInstall( 'sql', false, false, true,
                                         $fileItem['file'], false,
                                         array( 'path' => $fileItem['path'],
                                                'database-type' => $fileItem['database_type'],
                                                'copy-file' => true ) );
                if ( $fileItem['database_type'] )
                    $package->appendDependency( 'requires', 'ezdb', $fileItem['database_type'], false );
                $noticeText = "Adding sql file " . $cli->style( 'file' ) . $fileItem['path'] . $cli->style( 'file-end' );
                if ( $fileItem['database_type'] )
                    $noticeText .= " for database " . $cli->style( 'emphasize' ) . $fileItem['database_type'] . $cli->style( 'emphasize-end' );
                $noticeText .= " to package";
                $cli->notice( $noticeText );
            }
        }
    }

    function handleAddParameters( $packageType, &$package, &$cli, $arguments )
    {
        return $this->handleParameters( $packageType, $package, $cli, 'add', $arguments );
    }

    function handleParameters( $packageType, &$package, &$cli, $type, $arguments )
    {
        $sqlFileList = array();
        $currentType = false;
        $currentDatabaseType = false;
        if ( $packageType == 'sql' )
        {
            $currentType = 'sql';
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
                    if ( $flag == 'd' )
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
                        if ( $flag == 'd' )
                        {
                            $currentDatabaseType = $data;
                        }
                    }
                }
            }
            else
            {
                if ( $currentType == 'sql' )
                {
                    $sqlFile = $argument;
                    $databaseType = $currentDatabaseType;
                    $realFilePath = $this->sqlFileExists( $sqlFile, $databaseType,
                                                          $triedFiles );
                    if ( !$realFilePath )
                    {
                        $cli->error( "SQL file " . $cli->style( 'file' ) . $sqlFile . $cli->style( 'file-end' ) . " does not exist\n" .
                                     "The following files were searched for:\n" .
                                     implode( "\n", $triedFiles ) );
                        return false;
                    }
                    $fileList[] = array( 'file' => $sqlFile,
                                         'database_type' => $databaseType,
                                         'path' => $realFilePath );
                }
            }
        }
        if ( count( $fileList ) == 0 )
        {
            $cli->error( "No files were added" );
            return false;
        }
        return array( 'sql-file-list' => $fileList );
    }

    function sqlFileExists( &$sqlFile, &$databaseType,
                            &$triedFiles )
    {
        $triedFiles = array();
        if ( file_exists( $sqlFile ) )
        {
            $filePath = $sqlFile;
            if ( preg_match( '#^.+/([^/]+$)#', $sqlFile, $matches ) )
                $sqlFile = $matches[1];
            return $filePath;
        }
        $filePath = 'kernel/sql/' . $databaseType . '/' . $sqlFile;
        if ( file_exists( $filePath ) )
            return $filePath;
        $triedFiles[] = $filePath;
        $filePath = 'kernel/sql/' . $databaseType . '/' . $sqlFile . '.sql';
        if ( file_exists( $filePath ) )
        {
            $sqlFile .= '.sql';
            return $filePath;
        }
        $triedFiles[] = $filePath;
        return false;
    }

    function sqlDirectory()
    {
        return 'sql';
    }

    /*!
     \reimp
    */
    function createInstallNode( &$package, $export, &$installNode, $installItem, $installType )
    {
        if ( $installNode->attributeValue( 'type' ) == 'sql' )
        {
            if ( !$export )
                $installNode->appendAttribute( eZDOMDocument::createAttributeNode( 'original-path',
                                                                                   $installItem['path'] ) );
            $installNode->appendAttribute( eZDOMDocument::createAttributeNode( 'database-type',
                                                                               $installItem['database-type'] ) );
            if ( $export )
            {
                $originalPath = $package->path() . '/' . eZDBPackageHandler::sqlDirectory();
                if ( $installItem['database-type'] )
                    $originalPath .= '/' . $installItem['database-type'];
                $originalPath .= '/' . $installItem['filename'];
                $exportPath = $export['path'];
                $installDirectory = $exportPath . '/' . eZDBPackageHandler::sqlDirectory();
                if ( $installItem['database-type'] )
                    $installDirectory .= '/' . $installItem['database-type'];
                if ( !file_exists(  $installDirectory ) )
                    eZDir::mkdir( $installDirectory, eZDir::directoryPermission(), true );
                eZFileHandler::copy( $originalPath, $installDirectory . '/' . $installItem['filename'] );
            }
            else if ( isset( $installItem['copy-file'] ) and $installItem['copy-file'] )
            {
                $originalPath = $installItem['path'];
                $installDirectory = $package->path() . '/' . eZDBPackageHandler::sqlDirectory();
                if ( $installItem['database-type'] )
                    $installDirectory .= '/' . $installItem['database-type'];
                if ( !file_exists(  $installDirectory ) )
                    eZDir::mkdir( $installDirectory, eZDir::directoryPermission(), true );
                eZFileHandler::copy( $originalPath, $installDirectory . '/' . $installItem['filename'] );
            }
        }
    }

    /*!
     \reimp
    */
    function parseInstallNode( &$package, &$installNode, &$installParameters, $isInstall )
    {
        if ( $installNode->attributeValue( 'type' ) == 'sql' )
        {
            $installParameters['path'] = $installNode->attributeValue( 'original-path' );
            $installParameters['database-type'] = $installNode->attributeValue( 'database-type' );
        }
    }
}

?>

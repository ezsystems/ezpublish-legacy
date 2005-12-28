<?php
//
// Definition of eZContentClassPackageHandler class
//
// Created on: <15-Dec-2005 11:15:42 ks>
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

/*! \file ezcontentobjectpackagehandler.php
*/

/*!
  \class eZContentObjectPackageHandler ezcontentobjectpackagehandler.php
  \brief Handles content objects in the package system

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezpackagehandler.php' );

class eZExtensionPackageHandler extends eZPackageHandler
{
    /*!
     Constructor
    */
    function eZExtensionPackageHandler()
    {
        $this->eZPackageHandler( 'ezextension',
                                 array( 'extract-install-content' => true ) );
    }

    function generatePackage( &$package, $persistentData )
    {
        $this->Package =& $package;

        $siteINI = eZINI::instance();
        $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
        
        $fileList = array();
        $sourceDir = $extensionDir . '/' . $persistentData['extensionname'];
        $targetDir = $package->path() . '/ezextension';

        eZDir::mkdir( $targetDir, false, true );
        eZDir::copy( $sourceDir, $targetDir );

        $dirName = $targetDir;
        eZDir::recursiveList( $dirName, '', $fileList );
        
        $doc = new eZDOMDocument;

        $packageRoot = $doc->createElement( 'extension' );
        $packageRoot->setAttribute( 'name', $persistentData['extensionname'] );

        foreach( $fileList as $file )
        {
            $fileNode = $doc->createElement( 'file' );
            $fileNode->setAttribute( 'name', $file['name'] );

            if ( $file['path'] )
                $fileNode->setAttribute( 'path', $file['path'] );

            $fullPath = $dirName . $file['path'] . '/' . $file['name'];
            //$fileNode->setAttribute( 'full-path', $fullPath );
            $fileNode->setAttribute( 'md5sum', $package->md5sum( $fullPath ) );

            if ( $file['type'] == 'dir' )
                 $fileNode->setAttribute( 'type', 'dir' );

            $packageRoot->appendChild( $fileNode );
            unset( $fileNode );
        }

        $filename = 'extension-' . $persistentData['extensionname'];

        $this->Package->appendInstall( 'ezextension', false, false, true,
                                       $filename, 'ezextension',
                                       array( 'content' => $packageRoot ) );
        $this->Package->appendInstall( 'ezextension', false, false, false,
                                       $filename, 'ezextension',
                                       array( 'content' => false ) );
    }


    /*!
     \reimp
     Returns an explanation for the extension install item.
    */
    function explainInstallItem( &$package, $installItem )
    {
        if ( $installItem['filename'] )
        {
            $filename = $installItem['filename'];
            $subdirectory = $installItem['sub-directory'];
            if ( $subdirectory )
                $filepath = $subdirectory . '/' . $filename . '.xml';
            else
                $filepath = $filename . '.xml';

            $filepath = $package->path() . '/' . $filepath;

            $dom =& $package->fetchDOMFromFile( $filepath );
            if ( $dom )
            {
                $root =& $dom->root();
                $extensionName = $root->getAttribute( 'name' );
                return array( 'description' => ezi18n( 'kernel/package', 'Extension \'%extensionname\'', false,
                                                       array( '%extensionname' => $extensionName ) ) );
            }
        }
    }



    /*!
     \reimp
     Uninstalls extensions.
    */
    function uninstall( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, $installParameters,
                      &$installData )
    {
        $extensionName = $content->getAttribute( 'name' );
        
        $siteINI = eZINI::instance();
        $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' ) . '/' . $extensionName;

        // TODO: check md5 and don't delete modified files.

        if ( file_exists( $extensionDir ) )
            eZDir::recursiveDelete( $extensionDir );
    }

    /*!
     \reimp
     Copy extension from the package to an extension repository.
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, $installParameters,
                      &$installData )
    {
        //$this->Package =& $package;
        
        $extensionName = $content->getAttribute( 'name' );
        
        $siteINI = eZINI::instance();
        $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' ) . '/' . $extensionName;
        $packageExtensionDir = $package->path() . '/' . $parameters['sub-directory'] . '/' . $extensionName;

        // Error: extension already exists.
        if ( file_exists( $extensionDir ) )
        {
            eZDebug::writeError( 'Extension already exists' );
            return false;
        }

        eZDir::mkdir( $extensionDir, eZDir::directoryPermission(), true );
        
        include_once( 'lib/ezfile/classes/ezfilehandler.php' );

        $files = $content->Children;
        foreach( $files as $file )
        {
            $path = $file->getAttribute( 'path' );
            $destPath = $extensionDir . $path . '/' . $file->getAttribute( 'name' );

            if ( $file->getAttribute( 'type' ) == 'dir' )
            {
                eZDir::mkdir( $destPath, eZDir::directoryPermission() );
            }
            else
            {
                $sourcePath = $packageExtensionDir . $path . '/' . $file->getAttribute( 'name' );
                eZFileHandler::copy( $sourcePath, $destPath );
            }
        }
        return true;
    }

    var $Package = null;
}

?>

<?php
//
// Definition of eZExtensionPackageHandler class
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

/*! \file ezextensionpackagehandler.php
*/

/*!
  \class eZExtensionPackageHandler ezextensionpackagehandler.php
  \brief Handles extenstions in the package system

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezpackagehandler.php' );

define( "EZ_PACKAGE_EXTENSION_ERROR_EXISTS", 1 );

define( "EZ_PACKAGE_EXTENSION_REPLACE", 1 );
define( "EZ_PACKAGE_EXTENSION_SKIP", 2 );


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
                      &$content, &$installParameters,
                      &$installData )
    {
        $extensionName = $content->getAttribute( 'name' );
        
        $siteINI = eZINI::instance();
        $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' ) . '/' . $extensionName;

        // TODO: don't delete modified files?

        if ( file_exists( $extensionDir ) )
            eZDir::recursiveDelete( $extensionDir );

        return true;
    }

    /*!
     \reimp
     Copy extension from the package to extension repository.
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, &$installParameters,
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
            $description = "Extension '$extensionName' already exists.";
            $choosenAction = $this->errorChoosenAction( EZ_PACKAGE_EXTENSION_ERROR_EXISTS,
                                                        $installParameters, $description );
            switch( $choosenAction )
            {
            case EZ_PACKAGE_EXTENSION_SKIP:
                return true;
        
            case EZ_PACKAGE_NON_INTERACTIVE:
            case EZ_PACKAGE_EXTENSION_REPLACE:
                eZDir::recursiveDelete( $extensionDir );
                break;

            default:
                $installParameters['error'] = array( 'error_code' => EZ_PACKAGE_EXTENSION_ERROR_EXISTS,
                                                     'element_id' => $extensionName,
                                                     'description' => $description,
                                                     'actions' => array( EZ_PACKAGE_EXTENSION_REPLACE => "Replace extension",
                                                                         EZ_PACKAGE_EXTENSION_SKIP => 'Skip' ) );
                return false;
            }
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

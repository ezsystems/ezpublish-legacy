<?php
//
// Definition of eZStylePackageCreator class
//
// Created on: <21-Nov-2003 12:39:59 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezstylepackagecreator.php
*/

/*!
  \ingroup package
  \class eZStylePackageCreator ezstylepackagecreator.php
  \brief The class eZStylePackageCreator does

*/

include_once( 'kernel/classes/ezpackagecreationhandler.php' );

class eZStylePackageCreator extends eZPackageCreationHandler
{
    /*!
     Constructor
    */
    function eZStylePackageCreator( $id )
    {
        $steps = array();
        $steps[] = $this->packageThumbnailStep();
        $steps[] = array( 'id' => 'cssfile',
                          'name' => ezi18n( 'kernel/package', 'CSS files' ),
						  'methods' => array( 'initialize' => 'initializeCSSFile',
						                      'validate' => 'validateCSSFile',
											  'commit' => 'commitCSSFile' ),
                          'template' => 'cssfile.tpl' );
        $steps[] = array( 'id' => 'imagefiles',
                          'name' => ezi18n( 'kernel/package', 'Image files' ),
						  'methods' => array( 'initialize' => 'initializeImageFiles',
						                      'validate' => 'validateImageFiles',
											  'commit' => 'commitImageFiles' ),
                          'template' => 'imagefiles.tpl' );
        $steps[] = $this->packageInformationStep();
        $steps[] = $this->packageMaintainerStep();
        $steps[] = $this->packageChangelogStep();
        $this->eZPackageCreationHandler( $id,
                                         ezi18n( 'kernel/package', 'Site style' ),
                                         $steps );
    }

    function finalize( &$package, &$http, &$persistentData )
    {
        $cleanupFiles = array();
		$this->createPackage( $package, $http, $persistentData, $cleanupFiles );

        $collections = array();

		$siteCssfile = $persistentData['sitecssfile'];
        $fileItem = array( 'file' => $siteCssfile['filename'],
                           'type' => 'file',
                           'role' => false,
                           'design' => false,
                           'path' => $siteCssfile['url'],
                           'collection' => 'default',
                           'file-type' => false,
                           'role-value' => false,
                           'variable-name' => 'sitecssfile' );

        $package->appendFile( $fileItem['file'], $fileItem['type'], $fileItem['role'],
                              $fileItem['design'], $fileItem['path'], $fileItem['collection'],
                              null, null, true, null,
                              $fileItem['file-type'], $fileItem['role-value'], $fileItem['variable-name'] );

        $classesCssfile = $persistentData['classescssfile'];
        $fileItem = array( 'file' => $classesCssfile['filename'],
                           'type' => 'file',
                           'role' => false,
                           'design' => false,
                           'path' => $classesCssfile['url'],
                           'collection' => 'default',
                           'file-type' => false,
                           'role-value' => false,
                           'variable-name' => 'classescssfile' );

        $package->appendFile( $fileItem['file'], $fileItem['type'], $fileItem['role'],
                              $fileItem['design'], $fileItem['path'], $fileItem['collection'],
                              null, null, true, null,
                              $fileItem['file-type'], $fileItem['role-value'], $fileItem['variable-name'] );

        if ( !in_array( $fileItem['collection'], $collections ) )
            $collections[] = $fileItem['collection'];
        $cleanupFiles[] = $fileItem['path'];

        $imageFiles = $persistentData['imagefiles'];
        foreach ( $imageFiles as $imageFile )
        {
            $fileItem = array( 'file' => $imageFile['filename'],
                               'type' => 'file',
                               'role' => false,
                               'design' => false,
                               'subdirectory' => 'images',
                               'path' => $imageFile['url'],
                               'collection' => 'default',
                               'file-type' => false,
                               'role-value' => false,
                               'variable-name' => 'imagefiles' );

            $package->appendFile( $fileItem['file'], $fileItem['type'], $fileItem['role'],
                                  $fileItem['design'], $fileItem['path'], $fileItem['collection'],
                                  $fileItem['subdirectory'], null, true, null,
                                  $fileItem['file-type'], $fileItem['role-value'], $fileItem['variable-name'] );
            if ( !in_array( $fileItem['collection'], $collections ) )
                $collections[] = $fileItem['collection'];
            $cleanupFiles[] = $fileItem['path'];
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

        $package->setAttribute( 'is_active', true );
        $package->store();

        foreach ( $cleanupFiles as $cleanupFile )
        {
            unlink( $cleanupFile );
        }
    }

    /*!
     \reimp
     \return \c 'import'
    */
    function packageInstallType( &$package, &$persistentData )
    {
        return 'import';
    }

    /*!
     \reimp
     Returns \c 'stable', site style packages are always stable.
    */
    function packageInitialState( &$package, &$persistentData )
    {
        return 'stable';
    }

    /*!
     \return \c 'sitestyle'.
    */
	function packageType( &$package, &$persistentData )
	{
	    return 'sitestyle';
	}


    function initializeCSSFile( &$package, &$http, $step, &$persistentData, &$tpl )
    {
    }

    /*!
     Checks if the css file was uploaded.
    */
    function validateCSSFile( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        include_once( 'lib/ezutils/classes/ezhttpfile.php' );
        $siteFile =& eZHTTPFile::fetch( 'PackageSiteCSSFile' );

        $classesFile =& eZHTTPFile::fetch( 'PackageClassesCSSFile' );

        $result = true;
        if ( !$siteFile or !$classesFile )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'CSS file' ),
                                  'description' => ezi18n( 'kernel/package', 'You must upload both CSS files' ) );
            $result = false;
        }
        else if ( !preg_match( "#\.css$#", strtolower( $siteFile->attribute( 'original_filename' ) ) ) or
                  !preg_match( "#\.css$#", strtolower( $classesFile->attribute( 'original_filename' ) ) ) )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'CSS file' ),
                                  'description' => ezi18n( 'kernel/package', 'File did not have a .css suffix, this is most likely not a CSS file' ) );
            $result = false;
        }
        return $result;
    }

    function commitCSSFile( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        include_once( 'lib/ezutils/classes/ezhttpfile.php' );
        $siteFile =& eZHTTPFile::fetch( 'PackageSiteCSSFile' );
        $classesFile =& eZHTTPFile::fetch( 'PackageClassesCSSFile' );
        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $siteMimeData = eZMimeType::findByFileContents( $siteFile->attribute( 'original_filename' ) );
        $dir = eZSys::storageDirectory() . '/temp';
        eZMimeType::changeDirectoryPath( $siteMimeData, $dir );
        $siteFile->store( false, false, $siteMimeData );
        $persistentData['sitecssfile'] = $siteMimeData;

        $classesMimeData = eZMimeType::findByFileContents( $classesFile->attribute( 'original_filename' ) );
        eZMimeType::changeDirectoryPath( $classesMimeData, $dir );
        $classesFile->store( false, false, $classesMimeData );
        $persistentData['classescssfile'] = $classesMimeData;
	}

    function initializeImageFiles( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        $persistentData['imagefiles'] = array();
    }

    /*!
     Checks if the css file was uploaded.
    */
    function validateImageFiles( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        include_once( 'lib/ezutils/classes/ezhttpfile.php' );
        $file =& eZHTTPFile::fetch( 'PackageImageFile' );

        $result = true;
        if ( $file )
        {
            include_once( 'lib/ezutils/classes/ezmimetype.php' );
            $mimeData = eZMimeType::findByFileContents( $file->attribute( 'original_filename' ) );
            $dir = eZSys::storageDirectory() .  '/temp';
            eZMimeType::changeDirectoryPath( $mimeData, $dir );
            $file->store( false, false, $mimeData );
            $persistentData['imagefiles'][] = $mimeData;
            $result = false;
        }
        return $result;
    }

    function commitImageFiles( &$package, &$http, $step, &$persistentData, &$tpl )
    {
	}

    /*!
     \reimp
     Fetches the selected content classes and generates a name, summary and description from the selection.
    */
	function generatePackageInformation( &$packageInformation, &$package, &$http, $step, &$persistentData )
	{
        $cssfile = $persistentData['cssfile'];
		if ( $cssfile )
		{
			$packageInformation['name'] = $cssfile['basename'];
			$packageInformation['summary'] = $cssfile['basename'] . ' site style';
			$packageInformation['description'] = 'A site style called ' . $cssfile['basename'];
		}
	}
}

?>

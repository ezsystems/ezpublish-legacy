<?php
//
// Definition of eZStylePackageCreator class
//
// Created on: <21-Nov-2003 12:39:59 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/

/*!
  \ingroup package
  \class eZStylePackageCreator ezstylepackagecreator.php
  \brief The class eZStylePackageCreator does

*/

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
                          'name' => eZi18n::translate( 'kernel/package', 'CSS files' ),
                          'methods' => array( 'initialize' => 'initializeCSSFile',
                                              'validate' => 'validateCSSFile',
                                              'commit' => 'commitCSSFile' ),
                          'template' => 'cssfile.tpl' );
        $steps[] = array( 'id' => 'imagefiles',
                          'name' => eZi18n::translate( 'kernel/package', 'Image files' ),
                          'methods' => array( 'initialize' => 'initializeImageFiles',
                                              'validate' => 'validateImageFiles',
                                              'commit' => 'commitImageFiles' ),
                          'template' => 'imagefiles.tpl' );
        $steps[] = $this->packageInformationStep();
        $steps[] = $this->packageMaintainerStep();
        $steps[] = $this->packageChangelogStep();
        $this->eZPackageCreationHandler( $id,
                                         eZi18n::translate( 'kernel/package', 'Site style' ),
                                         $steps );
    }

    function finalize( &$package, $http, &$persistentData )
    {
        $cleanupFiles = array();
        $this->createPackage( $package, $http, $persistentData, $cleanupFiles, false );

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
        $cleanupFiles[] = $fileItem['path'];

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
        $cleanupFiles[] = $fileItem['path'];

        if ( !in_array( $fileItem['collection'], $collections ) )
            $collections[] = $fileItem['collection'];

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
            $cleanupFiles[] = $fileItem['path'];

            if ( !in_array( $fileItem['collection'], $collections ) )
                $collections[] = $fileItem['collection'];
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
                                                                 'value' => $collection ) );
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

        $package->setAttribute( 'is_active', true );
        $package->store();

        foreach ( $cleanupFiles as $cleanupFile )
        {
            unlink( $cleanupFile );
        }
    }

    /*!
     \return \c 'import'
    */
    function packageInstallType( $package, &$persistentData )
    {
        return 'import';
    }

    /*!
     Returns \c 'stable', site style packages are always stable.
    */
    function packageInitialState( $package, &$persistentData )
    {
        return 'stable';
    }

    /*!
     \return \c 'sitestyle'.
    */
    function packageType( $package, &$persistentData )
    {
        return 'sitestyle';
    }


    function initializeCSSFile( $package, $http, $step, &$persistentData, $tpl )
    {
    }

    /*!
     Checks if the css file was uploaded.
    */
    function validateCSSFile( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $hasSiteFile = eZHTTPFile::canFetch( 'PackageSiteCSSFile' );
        $hasClassesFile = eZHTTPFile::canFetch( 'PackageClassesCSSFile' );

        $result = true;
        if ( !$hasSiteFile or !$hasClassesFile )
        {
            $errorList[] = array( 'field' => eZi18n::translate( 'kernel/package', 'CSS file' ),
                                  'description' => eZi18n::translate( 'kernel/package', 'You must upload both CSS files' ) );
            return false;
        }

        $siteFile = eZHTTPFile::fetch( 'PackageSiteCSSFile' );
        $classesFile = eZHTTPFile::fetch( 'PackageClassesCSSFile' );
        if ( !preg_match( "#\.css$#", strtolower( $siteFile->attribute( 'original_filename' ) ) ) or
             !preg_match( "#\.css$#", strtolower( $classesFile->attribute( 'original_filename' ) ) ) )
        {
            $errorList[] = array( 'field' => eZi18n::translate( 'kernel/package', 'CSS file' ),
                                  'description' => eZi18n::translate( 'kernel/package', 'File did not have a .css suffix, this is most likely not a CSS file' ) );
            $result = false;
        }
        return $result;
    }

    function commitCSSFile( $package, $http, $step, &$persistentData, $tpl )
    {
        $siteFile = eZHTTPFile::fetch( 'PackageSiteCSSFile' );
        $classesFile = eZHTTPFile::fetch( 'PackageClassesCSSFile' );
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

    function initializeImageFiles( $package, $http, $step, &$persistentData, $tpl )
    {
        $persistentData['imagefiles'] = array();
    }

    /*!
     Checks if the css file was uploaded.
    */
    function validateImageFiles( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        // If we don't have an image we continue as normal
        if ( !eZHTTPFile::canFetch( 'PackageImageFile' ) )
            return true;

        $file = eZHTTPFile::fetch( 'PackageImageFile' );

        $result = true;
        if ( $file )
        {
            $mimeData = eZMimeType::findByFileContents( $file->attribute( 'original_filename' ) );
            $dir = eZSys::storageDirectory() .  '/temp';
            eZMimeType::changeDirectoryPath( $mimeData, $dir );
            $file->store( false, false, $mimeData );
            $persistentData['imagefiles'][] = $mimeData;
            $result = false;
        }
        return $result;
    }

    function commitImageFiles( $package, $http, $step, &$persistentData, $tpl )
    {
    }

    /*!
     Fetches the selected content classes and generates a name, summary and description from the selection.
    */
    function generatePackageInformation( &$packageInformation, $package, $http, $step, &$persistentData )
    {
        $cssfile = $persistentData['sitecssfile'];
        if ( $cssfile )
            $cssfile = $persistentData['classescssfile'];

        if ( $cssfile )
        {
            $packageInformation['name'] = $cssfile['basename'];
            $packageInformation['summary'] = $cssfile['basename'] . ' site style';
            $packageInformation['description'] = 'A site style called ' . $cssfile['basename'];
        }
    }
}

?>

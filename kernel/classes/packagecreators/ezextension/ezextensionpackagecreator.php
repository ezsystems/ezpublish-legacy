<?php
//
// Definition of eZStylePackageCreator class
//
// Created on: <14-Dec-2005 12:39:59 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezstylepackagecreator.php
*/

/*!
  \ingroup package
  \class eZStylePackageCreator ezstylepackagecreator.php
  \brief The class eZStylePackageCreator does

*/

//include_once( 'kernel/classes/ezpackagecreationhandler.php' );
//include_once( 'lib/ezfile/classes/ezdir.php' );

class eZExtensionPackageCreator extends eZPackageCreationHandler
{
    /*!
     Constructor
    */
    function eZExtensionPackageCreator( $id )
    {
        $steps = array();
        $steps[] = array( 'id' => 'extensionname',
                          'name' => ezi18n( 'kernel/package', 'Select an extension to be exported' ),
                          'methods' => array( 'initialize' => 'initializeExtensionName',
                                              'load' => 'loadExtensionName',
                                              'validate' => 'validateExtensionName',
                                              'commit' => 'commitExtensionName' ),
                          'template' => 'extension.tpl' );
        $steps[] = $this->packageInformationStep();
        $steps[] = $this->packageMaintainerStep();
        $steps[] = $this->packageChangelogStep();
        $this->eZPackageCreationHandler( $id,
                                         ezi18n( 'kernel/package', 'Extension export' ),
                                         $steps );
    }

    function finalize( &$package, $http, &$persistentData )
    {
        $this->createPackage( $package, $http, $persistentData, $cleanupFiles, false );

        $siteINI = eZINI::instance();
        $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );

        $fileList = array();
        $sourceDir = $extensionDir . '/' . $persistentData['extensionname'];
        $targetDir = $package->path() . '/ezextension';

        eZDir::mkdir( $targetDir, false, true );
        eZDir::copy( $sourceDir, $targetDir );

        eZDir::recursiveList( $targetDir, '', $fileList );

        $doc = new DOMDocument();

        $packageRoot = $doc->createElement( 'extension' );
        $packageRoot->setAttribute( 'name', $persistentData['extensionname'] );

        foreach( $fileList as $file )
        {
            $fileNode =& $doc->createElement( 'file' );
            $fileNode->setAttribute( 'name', $file['name'] );

            if ( $file['path'] )
                $fileNode->setAttribute( 'path', $file['path'] );

            $fullPath = $targetDir . $file['path'] . '/' . $file['name'];
            $fileNode->setAttribute( 'md5sum', $package->md5sum( $fullPath ) );

            if ( $file['type'] == 'dir' )
                 $fileNode->setAttribute( 'type', 'dir' );

            $packageRoot->appendChild( $fileNode );
            unset( $fileNode );
        }

        $filename = 'extension-' . $persistentData['extensionname'];

        $package->appendInstall( 'ezextension', false, false, true,
                                       $filename, 'ezextension',
                                       array( 'content' => $packageRoot ) );
        $package->appendInstall( 'ezextension', false, false, false,
                                       $filename, 'ezextension',
                                       array( 'content' => false ) );

        $package->setAttribute( 'is_active', true );
        $package->store();
    }

    /*!
     \reimp
     \return \c 'import'
    */
    function packageInstallType( $package, &$persistentData )
    {
        return 'install';
    }

    /*!
     \reimp
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
        return 'extension';
    }

    function initializeExtensionName( $package, $http, $step, &$persistentData, $tpl )
    {
    }

    function loadExtensionName( $package, $http, $step, &$persistentData, $tpl )
    {
        $siteINI = eZINI::instance();
        $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
        $extensionList = eZDir::findSubItems( $extensionDir );
        $tpl->setVariable( 'extension_list', $extensionList );
    }

    function validateExtensionName( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        if ( !$http->hasPostVariable( 'PackageExtensionName' ) )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Extension:' ),
                                  'description' => ezi18n( 'kernel/package', 'You must select an extension' ) );
            return false;
        }
        return true;
    }

    function commitExtensionName( $package, $http, $step, &$persistentData, $tpl )
    {
        $persistentData['extensionname'] = $http->postVariable( 'PackageExtensionName' );
    }

    /*!
     \reimp
     Fetches the selected content classes and generates a name, summary and description from the selection.
    */
    function generatePackageInformation( $packageInformation, $package, $http, $step, &$persistentData )
    {
        $extensionName = $persistentData['extensionname'];

        if ( $extensionName )
        {
            $packageInformation['name'] = $extensionName;
            $packageInformation['summary'] = $extensionName . ' extension';
            $packageInformation['description'] = $extensionName . ' eZ Publish extension';
        }
    }
}

?>

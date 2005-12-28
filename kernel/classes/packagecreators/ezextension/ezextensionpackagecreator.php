<?php
//
// Definition of eZStylePackageCreator class
//
// Created on: <14-Dec-2005 12:39:59 ks>
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

/*! \file ezstylepackagecreator.php
*/

/*!
  \ingroup package
  \class eZStylePackageCreator ezstylepackagecreator.php
  \brief The class eZStylePackageCreator does

*/

include_once( 'kernel/classes/ezpackagecreationhandler.php' );
include_once( 'lib/ezfile/classes/ezdir.php' );

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

    function finalize( &$package, &$http, &$persistentData )
    {
		$this->createPackage( $package, $http, $persistentData, $cleanupFiles, false );

        $extensionHandler = eZPackage::packageHandler( 'ezextension' );
        $extensionHandler->generatePackage( $package, $persistentData );

        $package->setAttribute( 'is_active', true );
        $package->store();
    }

    /*!
     \reimp
     \return \c 'import'
    */
    function packageInstallType( &$package, &$persistentData )
    {
        return 'install';
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
	    return 'extension';
	}

    function initializeExtensionName( &$package, &$http, $step, &$persistentData, &$tpl )
    {
    }

    function loadExtensionName( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        $siteINI = eZINI::instance();
        $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
        $extensionList = eZDir::findSubItems( $extensionDir );
        $tpl->setVariable( 'extension_list', $extensionList );
    }

    function validateExtensionName( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        if ( !$http->hasPostVariable( 'PackageExtensionName' ) )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Extension:' ),
                                  'description' => ezi18n( 'kernel/package', 'You must select an extension' ) );
            return false;
        }
        return true;
    }

    function commitExtensionName( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        $persistentData['extensionname'] = $http->postVariable( 'PackageExtensionName' );
    }

    /*!
     \reimp
     Fetches the selected content classes and generates a name, summary and description from the selection.
    */
	function generatePackageInformation( &$packageInformation, &$package, &$http, $step, &$persistentData )
	{
        $extensionName = $persistentData['extensionname'];
        
		if ( $extensionName )
		{
            $packageInformation['name'] = $extensionName;
			$packageInformation['summary'] = $extensionName . ' extension';
			$packageInformation['description'] = $extensionName . ' eZ publish extension';
		}
	}
}

?>

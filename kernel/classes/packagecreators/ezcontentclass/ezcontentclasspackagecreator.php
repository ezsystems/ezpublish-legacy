<?php
//
// Definition of eZContentClassPackageCreator class
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

/*! \file ezcontentclasspackagecreator.php
*/

/*!
  \ingroup package
  \class eZContentClassPackageCreator ezcontentclasspackagecreator.php
  \brief A package creator for content classes

*/

include_once( 'kernel/classes/ezpackagecreationhandler.php' );

class eZContentClassPackageCreator extends eZPackageCreationHandler
{
    /*!
     \reimp
    */
    function eZContentClassPackageCreator( $id )
    {
        $steps = array();
        $steps[] = array( 'id' => 'class',
                          'name' => ezi18n( 'kernel/package', 'Content classes to include' ),
						  'methods' => array( 'initialize' => 'initializeClassData',
						                      'validate' => 'validateClassData',
											  'commit' => 'commitClassData' ),
                          'template' => 'class.tpl' );
        $steps[] = $this->packageInformationStep();
        $steps[] = $this->packageMaintainerStep();
        $steps[] = $this->packageChangelogStep();
        $this->eZPackageCreationHandler( $id,
                                         ezi18n( 'kernel/package', 'Content class export' ),
                                         $steps );
    }

    /*!
     \reimp
     Creates the package and adds the selected content classes.
    */
    function finalize( &$package, &$http, &$persistentData )
    {
		$this->createPackage( $package, $http, $persistentData, $cleanupFiles );

        $classHandler = eZPackage::packageHandler( 'ezcontentclass' );
        $classList = $persistentData['classlist'];
        foreach ( $classList as $classID )
        {
            $classHandler->addClass( $package, $classID );
        }
        $package->setAttribute( 'is_active', true );
        $package->store();
    }

    /*!
     \reimp
     Returns \c 'stable', content class packages are always stable.
    */
    function packageInitialState( &$package, &$persistentData )
    {
        return 'stable';
    }

    /*!
     \return \c 'contentclass'.
    */
	function packageType( &$package, &$persistentData )
	{
	    return 'contentclass';
	}

    function initializeClassData( &$package, &$http, $step, &$persistentData, &$tpl )
    {
    }

    /*!
     Checks if at least one content class has been selected.
    */
    function validateClassData( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $classList = array();
        if ( $http->hasPostVariable( 'ClassList' ) )
            $classList = $http->postVariable( 'ClassList' );

        $persistentData['classlist'] = $classList;

        $result = true;
        if ( count( $classList ) == 0 )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Class list' ),
                                  'description' => ezi18n( 'kernel/package', 'You must select at least one class for inclusion' ) );
            $result = false;
        }
        return $result;
    }

    function commitClassData( &$package, &$http, $step, &$persistentData, &$tpl )
    {
	}

    /*!
     \reimp
     Fetches the selected content classes and generates a name, summary and description from the selection.
    */
	function generatePackageInformation( &$packageInformation, &$package, &$http, $step, &$persistentData )
	{
        $classList = $persistentData['classlist'];
		if ( count( $classList ) == 1 )
		{
			$classID = $classList[0];
			$class =& eZContentClass::fetch( $classID );
			if ( $class )
			{
				$packageInformation['name'] = $class->attribute( 'name' );
				$packageInformation['summary'] = 'Export of content class ' . $class->attribute( 'name' );
				$packageInformation['description'] = 'This package contains an exported definition of the content class ' . $class->attribute( 'name' ) . ' which can be imported to another eZ publish site';
			}
		}
		else if ( count( $classList ) > 1 )
		{
			$classNames = array();
			foreach ( $classList as $classID )
			{
				$class =& eZContentClass::fetch( $classID );
				if ( $class )
				{
					$classNames[] = $class->attribute( 'name' );
				}
			}
			$packageInformation['name'] = count( $classList ) . ' Classes';
			$packageInformation['summary'] = 'Export of ' . count( $classList ) . ' content classes';
			$description = 'This package contains exported definitions of the following content classes:' . "\n";
			foreach ( $classNames as $className )
			{
			    $description .= '- ' . $className . "\n";
			}
			$packageInformation['description'] = $description;
		}
	}
}

?>

<?php
//
// Definition of eZContentClassPackageCreator class
//
// Created on: <21-Nov-2003 12:39:59 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file ezcontentclasspackagecreator.php
*/

/*!
  \class eZContentClassPackageCreator ezcontentclasspackagecreator.php
  \brief The class eZContentClassPackageCreator does

*/

include_once( 'kernel/classes/ezpackagecreationhandler.php' );

class eZContentClassPackageCreator extends eZPackageCreationHandler
{
    /*!
     Constructor
    */
    function eZContentClassPackageCreator( $id )
    {
        $steps = array();
        $steps[] = array( 'id' => 'start',
                          'name' => ezi18n( 'kernel/package', 'Package information' ),
                          'use_standard_template' => true,
                          'template' => 'info.tpl' );
        $steps[] = array( 'id' => 'class',
                          'name' => ezi18n( 'kernel/package', 'Content classes to include' ),
                          'template' => 'class.tpl' );
        $this->eZPackageCreationHandler( $id,
                                         ezi18n( 'kernel/package', 'Content class export' ),
                                         $steps );
    }

    function nextstepMethodMap()
    {
        return array( 'start' => 'checkStartData',
                      'class' => 'checkClassData' );
    }

    function runstepMethodMap()
    {
        return array( 'start' => 'initializeData',
                      'class' => 'initializeClassData' );
    }

    function finalize( &$package, &$http, &$persistentData )
    {
        print( "finalize( &$package, &$http, &$persistentData )<br/>" );
        $classHandler = eZPackage::packageHandler( 'ezcontentclass' );
        $classList = $persistentData['classlist'];
        var_dump( $classList );
        foreach ( $classList as $classID )
        {
            $classHandler->addClass( $package, $classID );
        }
        $package->setAttribute( 'is_active', true );
        $package->store();
    }

    function checkStartData( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $packageName = false;
        $packageSummary = false;
        $packageVersion = false;
        $packageDescription = false;
        $packageLicence = 'GPL';
        if ( $http->hasPostVariable( 'PackageName' ) )
            $packageName = trim( $http->postVariable( 'PackageName' ) );
        if ( $http->hasPostVariable( 'PackageSummary' ) )
            $packageSummary = $http->postVariable( 'PackageSummary' );
        if ( $http->hasPostVariable( 'PackageDescription' ) )
            $packageDescription = $http->postVariable( 'PackageDescription' );
        if ( $http->hasPostVariable( 'PackageVersion' ) )
            $packageVersion = trim( $http->postVariable( 'PackageVersion' ) );
        if ( $http->hasPostVariable( 'PackageLicence' ) )
            $packageLicence = $http->postVariable( 'PackageLicence' );

        $persistentData['name'] = $packageName;
        $persistentData['summary'] = $packageSummary;
        $persistentData['description'] = $packageDescription;
        $persistentData['version'] = $packageVersion;
        $persistentData['licence'] = $packageLicence;

        $result = true;
        if ( $packageName == '' )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Package name' ),
                                  'description' => ezi18n( 'kernel/package', 'Package name is missing' ) );
            $result = false;
        }
        if ( !$packageSummary )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Summary' ),
                                  'description' => ezi18n( 'kernel/package', 'Summary is missing' ) );
            $result = false;
        }
        if ( !preg_match( "#^[0-9](\.[0-9])*$#", $packageVersion ) )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Version' ),
                                  'description' => ezi18n( 'kernel/package', 'The version must only contain numbers and must be delimited by dots (.), e.g. 1.0' ) );
            $result = false;
        }
        return $result;
    }

    function checkClassData( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        print( "checkClassData( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )<br/>" );
        $classList = array();
        if ( $http->hasPostVariable( 'ClassList' ) )
            $classList = $http->postVariable( 'ClassList' );

        $persistentData['classlist'] = $classList;
        var_dump( $classList );

        $result = true;
        if ( count( $classList ) == 0 )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Class list' ),
                                  'description' => ezi18n( 'kernel/package', 'You must select at least one class for inclusion' ) );
            $result = false;
        }
        return $result;
    }

    function initializeClassData( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        print( "initializeClassData( &$package, &$http, $step, &$persistentData, &$tpl )<br/>" );
        $package = eZPackage::create( $persistentData['name'],
                                      array( 'summary' => $persistentData['summary'] ) );

        $package->setAttribute( 'is_active', false );

        $package->setRelease( $persistentData['version'], '1', false,
                              $persistentData['licence'], 'alpha' );

        $package->setAttribute( 'description', $persistentData['description'] );
        $package->setAttribute( 'install_type', 'install' );
        $user =& eZUser::currentUser();
        $userObject =& $user->attribute( 'contentobject' );
        if ( $userObject )
        {
            $package->appendMaintainer( $userObject->attribute( 'name' ), $user->attribute( 'email' ), 'lead' );
            $package->appendChange( $userObject->attribute( 'name' ), $user->attribute( 'email' ), 'Creation of package' );
        }

        $package->store();
    }

    function initializeData( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        print( "initializeData( &$package, &$http, $step, &$persistentData, &$tpl )<br/>" );
        $persistentData['name'] = false;
        $persistentData['summary'] = false;
        $persistentData['description'] = false;
        $persistentData['licence'] = 'GPL';
        $persistentData['version'] = '1.0';
    }
}

?>

<?php
//
// Definition of eZPackageInstallationHandler class
//
// Created on: <31-Mar-2004 10:15:36 kk>
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

/*! \file ezpackageinstallationhandler.php
*/

/*!
  \ingroup package
  \class eZPackageInstallationHandler ezpackageinstallationhandler.php
  \brief The class eZPackageInstallationHandler does

*/

class eZPackageInstallationHandler
{
    /*!
     Constructor
    */
    function eZPackageInstallationHandler( &$package, $id, $name, $steps, $installItem )
    {
        $this->Package = $package;
        $this->Attributes = array( 'id' => $id,
                                   'name' => $name,
                                   'steps' => $steps,
                                   'step_map' => false,
                                   'current_steps' => $steps );
        $this->InitializeStepMethodMap = array();
        $this->ValidateStepMethodMap = array();
        $this->CommitStepMethodMap = array();
        $this->InstallItem = $installItem;
    }

    /*!
	 Will go over the steps and make sure that:
	 - The next and previous links are correct
	 - Steps that aren't needed are removed

 	 It will also make sure that steps can be looked up by their ID.
	*/
	function generateStepMap( &$package, &$persistentData )
	{
		$steps = $this->attribute( 'steps' );
        $map = array();
        $lastStep = false;
        $currentSteps = array();
        for ( $i = 0; $i < count( $steps ); ++$i )
        {
            $step =& $steps[$i];
            if ( !isset( $step['previous_step'] ) )
            {
                if ( $lastStep )
                    $step['previous_step'] = $lastStep['id'];
                else
                    $step['previous_step'] = false;
            }
            if ( !isset( $step['next_step'] ) )
            {
                if ( $i + 1 < count( $steps ) )
                    $step['next_step'] = $steps[$i+1]['id'];
                else
                    $step['next_step'] = false;
            }
			if ( isset( $step['methods']['initialize'] ) )
			    $this->InitializeStepMethodMap[$step['id']] = $step['methods']['initialize'];
			if ( isset( $step['methods']['validate'] ) )
			    $this->ValidateStepMethodMap[$step['id']] = $step['methods']['validate'];
			if ( isset( $step['methods']['commit'] ) )
			    $this->CommitStepMethodMap[$step['id']] = $step['methods']['commit'];
			$isStepIncluded = true;
			if ( isset( $step['methods']['check'] ) )
			{
				$checkMethod = $step['methods']['check'];
				$isStepIncluded = $this->$checkMethod( $package, $persistentData );
			}
			if ( $isStepIncluded )
			{
	            $map[$step['id']] =& $step;
    	        $lastStep =& $step;
    	        $currentSteps[] =& $step;
			}
        }
        $this->StepMap = array( 'first' => &$steps[0],
                                'map' => &$map,
                                'steps' => &$steps );
        $this->Attributes['step_map'] =& $this->StepMap;
        $this->Attributes['current_steps'] = $currentSteps;
	}

    function attributes()
    {
        return array_keys( $this->Attributes );
    }

    function hasAttribute( $name )
    {
        return array_key_exists( $name, $this->Attributes );
    }

    function &attribute( $name )
    {
        if ( array_key_exists( $name, $this->Attributes ) )
            return $this->Attributes[$name];
        return null;
    }

    function initializeStepMethodMap()
    {
        return $this->InitializeStepMethodMap;
    }

    function validateStepMethodMap()
    {
        return $this->ValidateStepMethodMap;
    }

    function commitStepMethodMap()
    {
        return $this->CommitStepMethodMap;
    }

    /*!
	 \return a process step map which has proper next/previous links,
	         method maps and allows lookup of steps by ID.
    */
    function &stepMap()
    {
        return $this->StepMap;
    }

    /*!
     \virtual
    */
    function stepTemplate( $step )
    {
        $stepTemplateName = $step['template'];
        if ( isset( $step['use_standard_template'] ) and
             $step['use_standard_template'] )
            $stepTemplateDir = "create";
        else
            $stepTemplateDir = "installers/" . $this->attribute( 'id' );
        return array( 'name' => $stepTemplateName,
                      'dir' => $stepTemplateDir );
    }

    /*!
     \virtual
     This is called the first time the step is entered (ie. not on validations)
     and can be used to fill in values in the \a $persistentData variable
     for use in the template or later retrieval.
    */
    function initializeStep( &$package, &$http, $step, &$persistentData, &$tpl, &$module )
    {
        $methodMap = $this->initializeStepMethodMap();
        if ( count( $methodMap ) > 0 )
        {
            if ( isset( $methodMap[$step['id']] ) )
            {
                $method = $methodMap[$step['id']];
                return $this->$method( $package, $http, $step, $persistentData, $tpl, $module );
            }
        }
    }

    /*!
     This is called after a step is finished. Reimplement this function to validate
     the step values and give back errors.
     \return \c false if the next step should not be fetched (ie. errors) or
             \c true if the all is OK and the next step should be fetched.
             It is also possible to return a step identifier, in which case
             this will be the next step.
    */
    function validateStep( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $nextStep = $this->validateAndAdvanceStep( $package, $http, $currentStepID, $stepMap, $persistentData, $errorList );
        if ( $nextStep === true )
        {
            if ( !isset( $stepMap['map'][$currentStepID] ) )
            {
                $nextStep = $stepMap['first']['id'];
            }
            else
            {
                $currentStep =& $stepMap['map'][$currentStepID];
                $nextStep = $currentStep['next_step'];
            }
        }
        else if ( $nextStep === false )
            $nextStep = $currentStepID;
        return $nextStep;
    }

    /*!
     \virtual
    */
    function validateAndAdvanceStep( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $methodMap = $this->validateStepMethodMap();
        if ( count( $methodMap ) > 0 )
        {
            if ( isset( $methodMap[$currentStepID] ) )
            {
                $method = $methodMap[$currentStepID];
                return $this->$method( $package, $http, $currentStepID, $stepMap, $persistentData, $errorList );
            }
        }
        return true;
    }

    /*!
     \virtual
     This is called after a step has validated it's information. It can
     be used to put values in the \a $persistentData variable for later retrieval.
    */
    function commitStep( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        $methodMap = $this->commitStepMethodMap();
        if ( count( $methodMap ) > 0 )
        {
            if ( isset( $methodMap[$step['id']] ) )
            {
                $method = $methodMap[$step['id']];
                return $this->$method( $package, $http, $step, $persistentData, $tpl );
            }
        }
    }

    /*!
     \virtual
     Finalizes the creation process with the gathered information.
     This is usually the function that creates the package and
     adds the proper elements.
    */
    function finalize( &$package, &$http, &$persistentData )
    {
    }

    /*!
     \return the package installation handler object for the handler named \a $handlerName.

     \param handler name'
     \param install Item
    */
    function &instance( &$package, $handlerName, $installItem )
    {
        $handlers =& $GLOBALS['eZPackageCreationInstallers'];
        if ( !isset( $handlers ) )
            $handlers = array();
        $handler = false;
        if ( eZExtension::findExtensionType( array( 'ini-name' => 'package.ini',
                                                    'repository-group' => 'PackageSettings',
                                                    'repository-variable' => 'RepositoryDirectories',
                                                    'extension-group' => 'PackageSettings',
                                                    'extension-variable' => 'ExtensionDirectories',
                                                    'subdir' => 'packageinstallers',
                                                    'extension-subdir' => 'packageinstallers',
                                                    'suffix-name' => 'packageinstaller.php',
                                                    'type-directory' => true,
                                                    'type' => $handlerName,
                                                    'alias-group' => 'InstallerSettings',
                                                    'alias-variable' => 'HandlerAlias' ),
                                             $result ) )
        {
            $handlerFile = $result['found-file-path'];
            if ( file_exists( $handlerFile ) )
            {
                include_once( $handlerFile );
                $handlerClassName = $result['type'] . 'PackageInstaller';
                if ( isset( $handlers[$result['type']] ) )
                {
                    $handler =& $handlers[$result['type']];
                    $handler->reset();
                }
                else
                {
                    $handler =& new $handlerClassName( $package, $handlerName, $installItem );
                    $handlers[$result['type']] =& $handler;
                }
            }
        }
        return $handler;
    }

    /*!
     \virtual
     \return The package type taken from \a $package if the package exists,
             otherwise \c false.
     If the creator should have a specific package type this function should be reimplemented.
     See eZPackage::typeList() for more information on available types.

     \note This function is called from createPackage and checkPackageMaintainer()
    */
	function packageType( &$package, &$persistentData )
	{
		if ( get_class( $package ) == 'ezpackage' )
		{
		    return $package->attribute( 'type' );
		}
		return false;
	}

    /*!
     \private
     Get root doom node of current install item.
    */
    function rootDOMNode()
    {
        if ( !$this->InstallItem['content'] )
        {
            $filename = $this->InstallItem['filename'];
            $subdirectory = $this->InstallItem['sub-directory'];
            if ( $subdirectory )
                $filepath = $subdirectory . '/' . $filename . '.xml';
            else
                $filepath = $filename . '.xml';

            $filepath = $this->Package->path() . '/' . $filepath;

            $dom =& $this->Package->fetchDOMFromFile( $filepath );
            if ( $dom )
            {
                $this->InstallItem['content'] =& $dom->root();
            }
            else
            {
                eZDebug::writeError( 'Failed fetching dom from file ' . $filepath,
                                     'eZPackageInstallationHandler::rootDOMNode()' );
                exit(0);
            }
        }

        return $this->InstallItem['content'];
    }

}
?>

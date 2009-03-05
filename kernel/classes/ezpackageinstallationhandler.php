<?php
//
// Definition of eZPackageInstallationHandler class
//
// Created on: <31-Mar-2004 10:15:36 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  \class eZPackageInstallationHandler ezpackageinstallationhandler.php
  \brief The class eZPackageInstallationHandler does

*/

class eZPackageInstallationHandler
{
    /*!
     Constructor
    */
    function eZPackageInstallationHandler( $package, $type, $installItem, $name = null, $steps = null )
    {
        $this->Package = $package;
        $this->Attributes = array( 'type' => $type,
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
    function generateStepMap( $package, &$persistentData )
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

    function attribute( $name )
    {
        if ( array_key_exists( $name, $this->Attributes ) )
        {
            return $this->Attributes[$name];
        }

        eZDebug::writeError( "Attribute '$name' does not exist", 'eZPackageInstallationHandler::attribute' );
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

    function stepTemplate( $package, $installItem, $step )
    {
        $stepTemplatePath = 'design:package/';
        $stepTemplateName = $step['template'];
        if ( isset( $step['use_standard_template'] ) and
             $step['use_standard_template'] )
            $stepTemplatePath .= "create";
        else
            $stepTemplatePath .= "installers/" . $this->attribute( 'type' );
        return array( 'name' => $stepTemplateName,
                      'path' => $stepTemplatePath );
    }

    /*!
     This is called the first time the step is entered (ie. not on validations)
     and can be used to fill in values in the \a $persistentData variable
     for use in the template or later retrieval.
    */
    function initializeStep( $package, $http, $step, &$persistentData, $tpl, $module )
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
    function validateStep( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
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

    function validateAndAdvanceStep( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
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
     This is called after a step has validated it's information. It can
     be used to put values in the \a $persistentData variable for later retrieval.
    */
    function commitStep( $package, $http, $step, &$persistentData, $tpl )
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
     Used to reset the instalation handler if needed
    */
    function reset( )
    {
    }


    /*!
     Finalizes the creation process with the gathered information.
     This is usually the function that creates the package and
     adds the proper elements.
    */
    function finalize( $package, $http, &$persistentData )
    {
    }

    /*!
     \return the package installation handler object for the handler named \a $handlerName.

     \param handler name'
     \param install Item
    */
    static function instance( $package, $handlerName, $installItem )
    {
        // if no installItem is given, then this is the whole package installer
        /*if ( $installItem == null )
        {
            include_once( $package->path() . '/' . $package->installerDirectory() . '/' . $package->installerFileName() );
            $handlerClassName = $package->installerFileName();
            $handler =& new $handlerClassName( $package, null, null );
            return $handler;
        }*/

        $handlers =& $GLOBALS['eZPackageCreationInstallers'];
        if ( !isset( $handlers ) )
            $handlers = array();
        $handler = false;

        if( isset( $handlers[$handlerName] ) )
        {
            $handler =& $handlers[$handlerName];
            $handler->reset();
        }
        else
        {
            $optionArray = array( 'iniFile'       => 'package.ini',
                                  'iniSection'    => 'InstallerSettings',
                                  'iniVariable'   => 'HandlerAlias',
                                  'handlerIndex'  => $handlerName,
                                  'handlerParams' => array( $package, $handlerName, $installItem ) );

            $options = new ezpExtensionOptions( $optionArray );

            $handler = eZExtension::getHandlerClass( $options );

            if( $handler !== null and $handler !== false )
            {
                $handlers[$handlerName] =& $handler;
                // if custom install handler is available in the package, we use it
                $customInstallHandler = $handler->customInstallHandlerInfo( $package, $installItem );
                if ( $customInstallHandler )
                {
                    unset( $handler );
                    $handlerClassName = $customInstallHandler['classname'];
                    $handlerFile = $customInstallHandler['file-path'];

                    // include_once( $handlerFile );
                    $handler = new $handlerClassName( $package, $handlerName, $installItem );
                }
            }
        }

        return $handler;
    }

    /*!
     \return The package type taken from \a $package if the package exists,
             otherwise \c false.
     If the creator should have a specific package type this function should be reimplemented.
     See eZPackage::typeList() for more information on available types.

     \note This function is called from createPackage and checkPackageMaintainer()
    */
    function packageType( $package, &$persistentData )
    {
        if ( $package instanceof eZPackage )
        {
            return $package->attribute( 'type' );
        }
        return false;
    }

    /*!
     \private
     Get root dom node of current install item.
    */
    function rootDOMNode()
    {
        if ( !isset( $this->InstallItem['content'] ) || !$this->InstallItem['content'] )
        {
            $filename = $this->InstallItem['filename'];
            $subdirectory = $this->InstallItem['sub-directory'];
            if ( $subdirectory )
                $filepath = $subdirectory . '/' . $filename . '.xml';
            else
                $filepath = $filename . '.xml';

            $filepath = $this->Package->path() . '/' . $filepath;

            $dom = $this->Package->fetchDOMFromFile( $filepath );
            if ( $dom )
            {
                $this->InstallItem['content'] = $dom->documentElement;
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

    /*!
     \private
     Support for custom installers (stored within the package)
    */
    function customInstallHandlerInfo( $package, $installItem )
    {
        return false;
    }

}
?>

<?php
//
// Definition of eZPackageCreationHandler class
//
// Created on: <21-Nov-2003 11:52:36 amos>
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

/*! \file ezpackagecreationhandler.php
*/

/*!
  \class eZPackageCreationHandler ezpackagecreationhandler.php
  \brief The class eZPackageCreationHandler does

*/

class eZPackageCreationHandler
{
    /*!
     Constructor
    */
    function eZPackageCreationHandler( $id, $name, $steps )
    {
        $this->Attributes = array( 'id' => $id,
                                   'name' => $name,
                                   'steps' => $steps );
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

    function nextstepMethodMap()
    {
        return false;
    }

    function runstepMethodMap()
    {
        return false;
    }

    /*!
     Goes over the steps for the creator and creats an associative lookup table
     with the steps and also sets proper next/previous links.
    */
    function &stepMap()
    {
        $steps =& $this->attribute( 'steps' );
        $map = array();
        $lastStep = false;
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
            $map[$step['id']] =& $step;
            $lastStep =& $step;
        }
        $stepMap = array( 'first' => &$steps[0],
                          'map' => &$map,
                          'steps' => &$steps );
        return $stepMap;
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
            $stepTemplateDir = "creators/" . $this->attribute( 'id' );
        return array( 'name' => $stepTemplateName,
                      'dir' => $stepTemplateDir );
    }

    /*!
     \virtual
    */
    function runStep( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        $methodMap = $this->runstepMethodMap();
        if ( $methodMap )
        {
            if ( isset( $methodMap[$step['id']] ) )
            {
                $method = $methodMap[$step['id']];
                return $this->$method( $package, $http, $step, $persistentData, $tpl );
            }
        }
    }

    /*!
    */
    function advanceStep( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $nextStep = $this->nextStep( $package, $http, $currentStepID, $stepMap, $persistentData, $errorList );
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
    function nextStep( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $methodMap = $this->nextstepMethodMap();
        if ( $methodMap )
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
     \return a list of the available creators.
    */
    function &creatorList()
    {
        $creators =& $GLOBALS['eZPackageCreatorList'];
        if ( !isset( $creators ) )
        {
            $creators = array();
            $ini =& eZINI::instance( 'package.ini' );
            $list = $ini->variable( 'CreationSettings', 'HandlerList' );
            foreach ( $list as $name )
            {
                $handler =& eZPackageCreationHandler::instance( $name );
                $creators[] =& $handler;
            }
        }
        return $creators;
    }

    /*!
     \return the package creation handler object for the handler named \a $handlerName.
    */
    function &instance( $handlerName )
    {
        $handlers =& $GLOBALS['eZPackageCreationHandlers'];
        if ( !isset( $handlers ) )
            $handlers = array();
        $handler = false;
        if ( eZExtension::findExtensionType( array( 'ini-name' => 'package.ini',
                                                    'repository-group' => 'CreationSettings',
                                                    'repository-variable' => 'RepositoryDirectories',
                                                    'extension-group' => 'CreationSettings',
                                                    'extension-variable' => 'ExtensionDirectories',
                                                    'subdir' => 'packagecreators',
                                                    'extension-subdir' => 'packagecreators',
                                                    'suffix-name' => 'packagecreator.php',
                                                    'type-directory' => true,
                                                    'type' => $handlerName,
                                                    'alias-group' => 'CreationSettings',
                                                    'alias-variable' => 'HandlerAlias' ),
                                             $result ) )
        {
            $handlerFile = $result['found-file-path'];
            if ( file_exists( $handlerFile ) )
            {
                include_once( $handlerFile );
                $handlerClassName = $result['type'] . 'PackageCreator';
                if ( isset( $handlers[$result['type']] ) )
                {
                    $handler =& $handlers[$result['type']];
                    $handler->reset();
                }
                else
                {
                    $handler =& new $handlerClassName( $handlerName );
                    $handlers[$result['type']] =& $handler;
                }
            }
        }
        return $handler;
    }

}

?>

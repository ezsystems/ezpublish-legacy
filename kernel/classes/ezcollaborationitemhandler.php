<?php
//
// Definition of eZCollaborationItemHandler class
//
// Created on: <22-Jan-2003 16:24:33 amos>
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezcollaborationitemhandler.php
*/

/*!
  \class eZCollaborationItemHandler ezcollaborationitemhandler.php
  \brief The class eZCollaborationItemHandler does

*/

include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'lib/ezutils/classes/ezdir.php' );

class eZCollaborationItemHandler
{
    /*!
     Constructor
    */
    function eZCollaborationItemHandler( $typeIdentifier, $typeName, $typeClassList = array() )
    {
        $this->Info['type-identifier'] = $typeIdentifier;
        $this->Info['type-class-list'] = $typeClassList;
        $this->Info['type-name'] = $typeName;
    }

    function hasAttribute( $attribute )
    {
        return $attribute == 'info';
    }

    function &attribute( $attribute )
    {
        if ( $attribute == 'info' )
            return $this->Info;
        return null;
    }

    function classes()
    {
        return $this->Info['type-class-list'];
    }

    function template( $viewMode )
    {
        $templateName = $this->templateName();
        return "design:collaboration/handlers/view/$viewMode/$templateName";
    }

    function templateName()
    {
        return $this->Info['type-identifier'] . '.tpl';
    }

    function title( &$collaborationItem )
    {
        return $this->Info['type-name'];
    }

    function &ini()
    {
        return eZINI::instance( 'collaboration.ini' );
    }

    function handleCustomAction( &$module, &$collaborationItem )
    {
    }

    function hasCustomInput( $name )
    {
        $http =& eZHTTPTool::instance();
        $postVariable = 'Collaboration_' . $name;
        return $http->hasPostVariable( $postVariable );
    }

    function customInput( $name )
    {
        $http =& eZHTTPTool::instance();
        $postVariable = 'Collaboration_' . $name;
        return $http->postVariable( $postVariable );
    }

    function defaultRepositories()
    {
        $collabINI =& eZCollaborationItemHandler::ini();
        return $collabINI->variable( 'HandlerSettings', 'Repositories' );
    }

    function extensionRepositories()
    {
        $collabINI =& eZCollaborationItemHandler::ini();
        return $collabINI->variable( 'HandlerSettings', 'Extensions' );
    }

    function handlerRepositories()
    {
        $extensions = eZCollaborationItemHandler::extensionRepositories();
        $repositories = eZCollaborationItemHandler::defaultRepositories();
        $extensionRoot = eZExtension::baseDirectory();
        foreach ( $extensions as $extension )
        {
            $handlerPath = eZDir::path( array( $extensionRoot, $extension, 'collaboration' ) );
            if ( file_exists( $handlerPath ) )
                $repositories[] = $handlerPath;
        }
        return $repositories;
    }

    function activeHandlers()
    {
        $collabINI =& eZCollaborationItemHandler::ini();
        return $collabINI->variable( 'HandlerSettings', 'Active' );
    }

    function &instantiate( $handler, $repositories = false )
    {
        $objectCache =& $GLOBALS["eZCollaborationHandlerObjectCache"];
        if ( !isset( $objectCache ) )
            $objectCache = array();
        if ( isset( $objectCache[$handler] ) )
            return $objectCache[$handler];
        if ( $repositories === false )
        {
            $repositories = eZCollaborationItemHandler::handlerRepositories();
        }
        $handlerInstance = null;
        $foundHandlerFile = false;
        $foundHandler = false;
        foreach ( $repositories as $repository )
        {
            $handlerFile = $handler . 'collaborationhandler.php';
            $handlerClass = $handler . 'collaborationhandler';
            $handlerPath = eZDir::path( array( $repository, $handler, $handlerFile ) );
            if ( file_exists( $handlerPath ) )
            {
                $foundHandlerFile = true;
                include_once( $handlerPath );
                if ( class_exists( $handlerClass ) )
                {
                    $foundHandler = true;
                    $handlerInstance = new $handlerClass();
                    $objectCache[$handler] =& $handlerInstance;
                    $handlerClasses = $handlerInstance->classes();
                    foreach ( $handlerClasses as $handlerClass )
                    {
                    }
                }
            }
        }
        if ( !$foundHandlerFile )
            eZDebug::writeWarning( "Collaboration file '$handlerFile' could not be found in " . implode( ', ', $repositories ), 'eZCollaborationItemHandler::fetchList' );
        else if ( !$foundHandler )
            eZDebug::writeWarning( "Collaboration class '$handlerClass' does not exist", 'eZCollaborationItemHandler::fetchList' );
        return $handlerInstance;
    }

    function &fetchList()
    {
        $list =& $GLOBALS['eZCollaborationList'];
        if ( isset( $list ) )
            return $list;
        $list = array();
        $activeHandlers = eZCollaborationItemHandler::activeHandlers();
        $repositories = eZCollaborationItemHandler::handlerRepositories();
        foreach ( $activeHandlers as $handler )
        {
            $handlerInstance =& eZCollaborationItemHandler::instantiate( $handler, $repositories );
            if ( $handlerInstance !== null )
                $list[] =& $handlerInstance;
        }
        return $list;
    }

}

?>

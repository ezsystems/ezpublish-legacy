<?php
//
// Definition of eZTemplateToolbarFunction class
//
// Created on: <04-Mar-2004 13:22:32 wy>
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

/*! \file eztemplatetoolbarfunction.php
*/

/*!
  \class eZTemplateToolbarFunction eztemplatetoolbarfunction.php
  \brief The class eZTemplateToolarFunction does

*/

class eZTemplateToolbarFunction
{

    /*!
     Initializes the object with names.
    */
    function eZTemplateToolbarFunction( $blockName = 'tool_bar' )
    {
        $this->BlockName = $blockName;
    }

    /*!
     Returns an array containing the name of the block function, default is "block".
     The name is specified in the constructor.
    */
    function functionList()
    {
        return array( $this->BlockName );
    }

    function functionTemplateHints()
    {
        return array( $this->BlockName => array( 'parameters' => true,
                                                 'static' => false,
                                                 'transform-children' => false,
                                                 'tree-transformation' => true,
                                                 'transform-parameters' => true ) );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         &$tpl, $parameters, $privateData )
    {
        if ( $functionName != $this->BlockName )
            return false;

        $parameters = eZTemplateNodeTool::extractFunctionNodeParameters( $node );

        if ( !isset( $parameters['name'] ) )
            return false;

        // Read ini file
        $toolbarIni =& eZINI::instance( "toolbar.ini" );

        if ( isset( $parameters["view"] ) )
        {
            $viewData = $parameters["view"];
            $viewMode = eZTemplateNodeTool::elementStaticValue( $viewData );
        }
        else
        {
            $viewMode = "full";
        }

        $params = $parameters;
        $namespaceValue = false;
        if ( isset( $parameters["name"] ) )
        {
            $nameData = $parameters["name"];
            if ( !eZTemplateNodeTool::isStaticElement( $nameData ) )
                return false;

            $nameValue = eZTemplateNodeTool::elementStaticValue( $nameData );

            $toolbarPosition = $nameValue;
            $toolbarName = "Toolbar_" . $toolbarPosition;
            $toolArray = $toolbarIni->variable( $toolbarName, 'Tool' );

            $newNodes = array();
            foreach ( array_keys( $toolArray ) as $toolKey )
            {
                $tool = $toolArray[$toolKey];
                $placement = $toolKey + 1;

                $uriString = "design:toolbar/$viewMode/$tool.tpl";

                $resourceName = "";
                $templateName = "";
                $resource =& $tpl->resourceFor( $uriString, $resourceName, $templateName );
                $resourceData =& $tpl->resourceData( $resource, $uriString, $resourceName, $templateName );

                $includeNodes = $resource->templateNodeTransformation( $functionName, $node, $tpl, $resourceData, $parameters, $namespaceValue );
                if ( $includeNodes === false )
                    return false;

                $variableList = array();
                foreach ( array_keys( $parameters ) as $parameterName )
                {
                    if ( $parameterName == 'name' or
                         $parameterName == 'view' )
                        continue;
                    $parameterData =& $parameters[$parameterName];
                    $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameterData, false, array(),
                                                                          array( $namespaceValue, EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, $parameterName ) );
                    $variableList[] = $parameterName;
                }

                $actionParameters = array();
                if ( $toolbarIni->hasGroup( "Tool_" . $tool ) )
                {
                    $actionParameters = $toolbarIni->group( "Tool_" . $tool );
                }
                if ( $toolbarIni->hasGroup( "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement ) )
                {
                    $actionParameters = $toolbarIni->group( "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement );
                }
                foreach ( array_keys( $actionParameters ) as $key )
                {
                    $itemValue = $actionParameters[$key];
                    $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $itemValue, false, array(),
                                                                          array( $namespaceValue, EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, $key ) );
                    $variableList[] = $itemValue;
                }

                $newNodes = array_merge( $newNodes, $includeNodes );

                foreach ( $variableList as $variableName )
                {
                    $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( array( $namespaceValue, EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, $variableName ) );
                }
            }
        }
        return $newNodes;
    }

    /*!
     Processes the function with all it's children.
    */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $params = $functionParameters;
        switch ( $functionName )
        {
            case $this->BlockName:
            {
                $viewMode = "full";
                $name ="";
                // Read ini file
                $toolbarIni =& eZINI::instance( "toolbar.ini" );

                if ( isset( $functionParameters["view"] ) )
                {
                    $viewMode = $tpl->elementValue( $functionParameters["view"], $rootNamespace, $currentNamespace, $functionPlacement );
                }

                if ( isset( $functionParameters["name"] ) )
                {
                    reset( $params );
                    while ( ( $key = key( $params ) ) !== null )
                    {
                        $item =& $params[$key];
                        switch ( $key )
                        {
                            case "name":
                            case "view":
                                break;

                            default:
                            {
                                $item_value = $tpl->elementValue( $item, $rootNamespace, $currentNamespace, $functionPlacement );
                                $tpl->setVariable( $key, $item_value, $name );
                            } break;
                        }
                        next( $params );
                    }
                    $toolbarPosition = $tpl->elementValue( $functionParameters["name"], $rootNamespace, $currentNamespace, $functionPlacement );
                    $definedVariables = array();
                    $toolbarName = "Toolbar_" . $toolbarPosition;
                    $toolArray = $toolbarIni->variable( $toolbarName, 'Tool' );
                    foreach ( array_keys( $toolArray ) as $toolKey )
                    {
                        $tool = $toolArray[$toolKey];
                        $placement = $toolKey + 1;
                        if ( $toolbarIni->hasGroup( "Tool_" . $tool ) )
                        {
                            $actionParameters = $toolbarIni->group( "Tool_" . $tool );
                        }
                        if ( $toolbarIni->hasGroup( "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement ) )
                        {
                            $actionParameters = $toolbarIni->group( "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement );
                        }
                        foreach ( array_keys( $actionParameters ) as $key )
                        {
                            $itemValue = $actionParameters[$key];
                            $tpl->setVariable( $key, $itemValue, $name );
                            $definedVariables[] = $key;
                        }
                        $uri = "design:toolbar/$viewMode/$tool.tpl";
                        $tpl->processURI( $uri, true, $extraParameters, $textElements, $name, $name );
                        foreach ( $definedVariables as $variable )
                        {
                            $tpl->unsetVariable( $variable, $currentNamespace );
                        }
                    }
                }
            }
        }
    }

    /*!
     Returns false.
    */
    function hasChildren()
    {
        return false;
    }
}

?>

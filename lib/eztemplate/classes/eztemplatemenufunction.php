<?php
//
// Definition of eZTemplateMenuFunction class
//
// Created on: <10-Mar-2004 15:34:50 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file eztemplatemenufunction.php
*/

/*!
  \class eZTemplateMenuFunction eztemplatemenufunction.php
  \brief The class eZTemplateMenuFunction does

*/

class eZTemplateMenuFunction
{
    /*!
     Initializes the object with names.
    */
    function eZTemplateMenuFunction( $blockName = 'menu' )
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
                                         $tpl, $parameters, $privateData )
    {
        if ( $functionName != $this->BlockName )
            return false;

        $parameters = eZTemplateNodeTool::extractFunctionNodeParameters( $node );

        if ( !isset( $parameters['name'] ) )
            return false;

        $namespaceValue = false;
        $newNodes = array();
        if ( isset( $parameters["name"] ) )
        {
            $menuIni = eZINI::instance( "menu.ini" );
            $nameData = $parameters["name"];
            if ( !eZTemplateNodeTool::isStaticElement( $nameData ) )
                return false;

            $menuName = eZTemplateNodeTool::elementStaticValue( $nameData );

            if ( $menuIni->hasVariable( 'SelectedMenu', $menuName ) )
            {
                $menuTemplate = $menuIni->variable( "SelectedMenu", $menuName );

                if ( $menuTemplate != null )
                {
                    $uriString = "design:menu/$menuTemplate.tpl";
                    $resourceName = "";
                    $templateName = "";
                    $resource = $tpl->resourceFor( $uriString, $resourceName, $templateName );
                    $resourceData = $tpl->resourceData( $resource, $uriString, $resourceName, $templateName );
                    $resourceData['use-comments'] = eZTemplateCompiler::isCommentsEnabled();

                    $includeNodes = $resource->templateNodeTransformation( $functionName, $node, $tpl, $resourceData, $parameters, $namespaceValue );
                    if ( $includeNodes === false )
                        return false;

                    $variableList = array();
                    foreach ( array_keys( $parameters ) as $parameterName )
                    {
                        if ( $parameterName == 'name' )
                            continue;
                        $parameterData =& $parameters[$parameterName];
                        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameterData, false, array(),
                                                                              array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, $parameterName ) );
                        $variableList[] = $parameterName;
                    }

                    $newNodes = array_merge( $newNodes, $includeNodes );

                    foreach ( $variableList as $variableName )
                    {
                        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, $variableName ) );
                    }
                }
                else
                {
                    // to do: not use this function to generate empty code.
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "" );
                }
            }
            else
            {
                // to do: not use this function to generate empty code.
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "" );
            }
        }
        return $newNodes;
    }

    /*!
     Processes the function with all it's children.
    */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $params = $functionParameters;
        switch ( $functionName )
        {
            case $this->BlockName:
            {
                $name ="";
                $menuIni = eZINI::instance( "menu.ini" );

                if ( isset( $functionParameters["name"] ) )
                {
                    $menuName = $tpl->elementValue( $functionParameters["name"], $rootNamespace, $currentNamespace, $functionPlacement );
                    if ( $menuIni->hasVariable( 'SelectedMenu', $menuName ) )
                    {
                        $menuTemplate = $menuIni->variable( 'SelectedMenu', $menuName );
                        if ( $menuTemplate != null )
                        {
                            $uri = "design:menu/$menuTemplate.tpl";
                            $tpl->processURI( $uri, true, $extraParameters, $textElements, $name, $name );
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

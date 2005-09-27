<?php
//
// Definition of eZTemplateMenuFunction class
//
// Created on: <10-Mar-2004 15:34:50 wy>
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
                                         &$tpl, $parameters, $privateData )
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
            $menuIni =& eZINI::instance( "menu.ini" );
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
                    $resource =& $tpl->resourceFor( $uriString, $resourceName, $templateName );
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
                                                                              array( $namespaceValue, EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, $parameterName ) );
                        $variableList[] = $parameterName;
                    }

                    $newNodes = array_merge( $newNodes, $includeNodes );

                    foreach ( $variableList as $variableName )
                    {
                        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( array( $namespaceValue, EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, $variableName ) );
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
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $params = $functionParameters;
        switch ( $functionName )
        {
            case $this->BlockName:
            {
                $name ="";
                $menuIni =& eZINI::instance( "menu.ini" );

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

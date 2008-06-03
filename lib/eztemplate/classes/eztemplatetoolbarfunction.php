<?php
//
// Definition of eZTemplateToolbarFunction class
//
// Created on: <04-Mar-2004 13:22:32 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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
                                         $tpl, $parameters, $privateData )
    {
        if ( $functionName != $this->BlockName )
            return false;

        $parameters = eZTemplateNodeTool::extractFunctionNodeParameters( $node );

        if ( !isset( $parameters['name'] ) )
            return false;

        // Read ini file
        $toolbarIni = eZINI::instance( "toolbar.ini" );

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
            if ( !is_array( $toolArray ) )
                $toolArray = array();

            $newNodes = array();
            foreach ( array_keys( $toolArray ) as $toolKey )
            {
                $tool = $toolArray[$toolKey];
                $placement = $toolKey + 1;

                $uriString = "design:toolbar/$viewMode/$tool.tpl";

                $placementValue = "";
                $firstValue = false;
                $lastValue = false;
                if ( $placement == 1 )
                {
                    if ( $placement == count( $toolArray ) )
                    {
                        $firstValue = true;
                        $lastValue = true;
                        $placementValue = "last";
                    }
                    else
                    {
                        $firstValue = true;
                        $placementValue = "first";
                    }
                }
                else if ( $placement == count( $toolArray ) )
                {
                    $lastValue = true;
                    $placementValue = "last";
                }

                $resourceName = "";
                $templateName = "";
                $resource = $tpl->resourceFor( $uriString, $resourceName, $templateName );
                $resourceData = $tpl->resourceData( $resource, $uriString, $resourceName, $templateName );
                $resourceData['use-comments'] = eZTemplateCompiler::isCommentsEnabled();

                $includeNodes = $resource->templateNodeTransformation( $functionName, $node, $tpl, $resourceData, $parameters, $namespaceValue );
                if ( $includeNodes === false )
                    return false;

                $uniqID = md5( uniqid('inc') );
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$oldRestoreIncludeArray" . "_$uniqID = isset( \$restoreIncludeArray ) ? \$restoreIncludeArray : array();\n".
                                                                       "\$restoreIncludeArray = array();\n");
                $variableList = array();
                foreach ( array_keys( $parameters ) as $parameterName )
                {
                    if ( $parameterName == 'name' or
                         $parameterName == 'view' )
                        continue;
                    $parameterData =& $parameters[$parameterName];
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( isset( \$vars['']['$parameterName'] ) )\n".
                                                                           "    \$restoreIncludeArray[] = array( '', '$parameterName', \$vars['']['$parameterName'] );\n".
                                                                           "elseif ( !isset( \$vars['']['$parameterName'] ) ) \n".
                                                                           "    \$restoreIncludeArray[] = array( '', '$parameterName', 'unset' );\n" );

                    $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameterData, false, array(),
                                                                          array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, $parameterName ) );
                    $variableList[] = $parameterName;
                }

                $actionParameters = array();
                if ( $toolbarIni->hasGroup( "Tool_" . $tool ) )
                {
                    $actionParameters = $toolbarIni->group( "Tool_" . $tool );
                }
                if ( $toolbarIni->hasGroup( "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement ) )
                {
                    $actionParameters = array_merge( $actionParameters, $toolbarIni->group( "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement ) );
                }
                foreach ( array_keys( $actionParameters ) as $key )
                {
                    $itemValue = $actionParameters[$key];
                    $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $itemValue, false, array(),
                                                                          array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, $key ) );
                    $variableList[] = $key;
                }

                // Add parameter tool_id and offset
                $toolIDValue =  "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement;
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $toolIDValue, false, array(),
                                                                      array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, "tool_id" ) );
                $variableList[] = "tool_id";

                $toolOffset = $placement;
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $toolOffset, false, array(),
                                                                      array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, "offset" ) );
                $variableList[] = "offset";

                // Add parameter first, last and placement
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $firstValue, false, array(),
                                                                      array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, "first" ) );
                $variableList[] = "first";

                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $lastValue, false, array(),
                                                                      array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, "last" ) );
                $variableList[] = "last";

                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $placementValue, false, array(),
                                                                      array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, "placement" ) );
                $variableList[] = "placement";

                $newNodes = array_merge( $newNodes, $includeNodes );

                // Restore previous variables, before including
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "foreach ( \$restoreIncludeArray as \$element )\n".
                                                                       "{\n".
                                                                       "    if ( \$element[2] === 'unset' )\n".
                                                                       "    {\n".
                                                                       "        unset( \$vars[\$element[0]][\$element[1]] );\n".
                                                                       "        continue;\n".
                                                                       "    }\n".
                                                                       "    \$vars[\$element[0]][\$element[1]] = \$element[2];\n".
                                                                       "}\n".
                                                                       "\$restoreIncludeArray = \$oldRestoreIncludeArray" . "_$uniqID;\n" );
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
                $viewMode = "full";
                $name = $currentNamespace;
                // Read ini file
                $toolbarIni = eZINI::instance( "toolbar.ini" );

                if ( isset( $functionParameters["view"] ) )
                {
                    $viewMode = $tpl->elementValue( $functionParameters["view"], $rootNamespace, $currentNamespace, $functionPlacement );
                }

                if ( isset( $functionParameters["name"] ) )
                {
                    reset( $params );
                    $whatParamsShouldBeUnset = array();
                    $whatParamsShouldBeReplaced = array();
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
                                if ( !$tpl->hasVariable( $key, $name ) )
                                {
                                    $whatParamsShouldBeUnset[] = $key; // Tpl vars should be removed after including
                                }
                                else
                                {
                                    $whatParamsShouldBeReplaced[$key] = $tpl->variable( $key, $name ); // Tpl vars should be replaced after including
                                }

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

                    if ( !is_array( $toolArray ) )
                        $toolArray = array();

                    $actionParameters = array();
                    foreach ( array_keys( $toolArray ) as $toolKey )
                    {
                        $definedVariables = array();
                        $tool = $toolArray[$toolKey];
                        $placement = $toolKey + 1;
                        if ( $toolbarIni->hasGroup( "Tool_" . $tool ) )
                        {
                            $actionParameters = $toolbarIni->group( "Tool_" . $tool );
                        }
                        if ( $toolbarIni->hasGroup( "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement ) )
                        {
                            $actionParameters = array_merge( $actionParameters, $toolbarIni->group( "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement ) );
                        }
                        foreach ( array_keys( $actionParameters ) as $key )
                        {
                            $itemValue = $actionParameters[$key];
                            $tpl->setVariable( $key, $itemValue, $name );
                            $definedVariables[] = $key;
                        }
                        $toolIDValue =  "Tool_" . $toolbarPosition . "_" . $tool . "_" . $placement;
                        $tpl->setVariable( "tool_id", $toolIDValue, $name );
                        $definedVariables[] = "tool_id";
                        $toolOffset = $placement;
                        $tpl->setVariable( "offset", $toolOffset, $name );
                        $definedVariables[] = "offset";
                        $uri = "design:toolbar/$viewMode/$tool.tpl";

                        if ( $placement == 1 )
                        {
                            if ( count( $toolArray ) == 1 )
                            {
                                $tpl->setVariable( "first", true );
                                $tpl->setVariable( "last", true );
                                $tpl->setVariable( "placement", "last" );
                                $definedVariables[] = "first";
                                $definedVariables[] = "last";
                                $definedVariables[] = "placement";
                            }
                            else
                            {
                                $tpl->setVariable( "first", true );
                                $tpl->setVariable( "last", false );
                                $tpl->setVariable( "placement", "first" );
                                $definedVariables[] = "first";
                                $definedVariables[] = "last";
                                $definedVariables[] = "placement";
                            }
                        }
                        else if ( $placement == count( $toolArray ) )
                        {
                            $tpl->setVariable( "first", false );
                            $tpl->setVariable( "last", true );
                            $tpl->setVariable( "placement", "last" );
                            $definedVariables[] = "first";
                            $definedVariables[] = "last";
                            $definedVariables[] = "placement";
                        }
                        else
                        {
                            $tpl->setVariable( "first", false );
                            $tpl->setVariable( "last",  false );
                            $tpl->setVariable( "placement", "" );
                            $definedVariables[] = "placement";
                        }
                        $tpl->processURI( $uri, true, $extraParameters, $textElements, $name, $name );
                        // unset var
                        foreach ( $whatParamsShouldBeUnset as $key )
                        {
                            $tpl->unsetVariable( $key, $name );
                        }
                        // replace var
                        foreach ( $whatParamsShouldBeReplaced as $key => $item_value )
                        {
                            $tpl->setVariable( $key, $item_value, $name );
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

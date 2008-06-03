<?php
//
// Definition of eZTemplateSwitchFunction class
//
// Created on: <06-Mar-2002 08:07:54 amos>
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

/*!
  \class eZTemplateSwitchFunction eztemplateswitchfunction.php
  \ingroup eZTemplateFunctions
  \brief Handles conditional output in templates using function "switch"

  This allows for writing switch/case sentences (similar to if/else if/else)
  which you normally find in programming languages. With this you can display
  text depending on a certain template variable.

\code
// Example template code
{* Matches $a against $b or $c *}
{switch match=$a}
{case match=$b}
Matched $b
{/case}
{case match=$c}
Matched $c
{/case}
{/switch}

\endcode

TODO: Add support for custom operations when matching
{case process=$match|gt(5)}
Matched $c
{/case}


*/

class eZTemplateSwitchFunction
{
    /*!
     Initializes the function with the name $name, default is "switch".
    */
    function eZTemplateSwitchFunction()
    {
        $this->SwitchName = 'switch';
    }

    /*!
     Returns an array of the function names, required for eZTemplate::registerFunctions.
    */
    function functionList()
    {
        return array( $this->SwitchName );
    }

    function functionTemplateHints()
    {
        return array( $this->SwitchName => array( 'parameters' => true,
                                                  'static' => false,
                                                  'transform-children' => false,
                                                  'tree-transformation' => true,
                                                  'transform-parameters' => true ) );
    }

    /*!
     Returns the attribute list which is case.
    */
    function attributeList()
    {
        return array( "case" => true );
    }

    function templateNodeCaseTransformation( $tpl, &$newNodes, &$caseNodes, &$caseCounter, &$node, $privateData )
    {
        if ( $node[2] == 'case' )
        {
            if ( is_array( $node[3] ) && count( $node[3] ) )
            {
                if ( isset( $node[3]['match'] ) )
                {
                    $match = $node[3]['match'];
                    $match = eZTemplateCompiler::processElementTransformationList( $tpl, $node, $match, $privateData );

                    $dynamicCase = false;
                    if ( eZTemplateNodeTool::isStaticElement( $match ) )
                    {
                        $matchValue = eZTemplateNodeTool::elementStaticValue( $match );
                        $caseText = eZPHPCreator::variableText( $matchValue, 0, 0, false );
                    }
                    else
                    {
                        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $match, false, array(), 'case' . $caseCounter );
                        $caseText = "\$case" . $caseCounter;
                        ++$caseCounter;
                        $dynamicCase = true;
                    }

                    $caseNodes[] = eZTemplateNodeTool::createCodePieceNode( "    case $caseText:\n    {" );
                    if ( $dynamicCase )
                        $caseNodes[] = eZTemplateNodeTool::createCodePieceNode( "        unset( $caseText );" );
                }
                else if ( isset( $node[3]['in'] ) )
                {
                    return false;
                }
            }
            else
            {
                $caseNodes[] = eZTemplateNodeTool::createCodePieceNode( "    default:\n    {" );
            }

            $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
            if ( $children === false )
            {
                $children = array();
            }
            else
            {
                $children = eZTemplateCompiler::processNodeTransformationNodes( $tpl, $node, $children, $privateData );
            }

            $caseNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode( 8 );

            $caseNodes = array_merge( $caseNodes, $children );
            $caseNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode( 8 );

            $caseNodes[] = eZTemplateNodeTool::createCodePieceNode( "    } break;" );
        }
    }


    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, $parameters, $privateData )
    {
        $newNodes = array();
        $namespaceValue = false;
        $varName = 'match';

        if ( !isset( $parameters['match'] ) )
        {
            return false;
        }

        if ( isset( $parameters['name'] ) )
        {
            $nameData = $parameters['name'];
            if ( !eZTemplateNodeTool::isStaticElement( $nameData ) )
                return false;
            $namespaceValue = eZTemplateNodeTool::elementStaticValue( $nameData );
        }

        if ( isset( $parameters['var'] ) )
        {
            $varData = $parameters['var'];
            if ( !eZTemplateNodeTool::isStaticElement( $varData ) )
                return false;
            $varName = eZTemplateNodeTool::elementStaticValue( $varData );
        }

        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['match'], false, array(),
                                                              array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, $varName ) );
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['match'],
                                                              eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                              array( 'variable-name' => 'match',
                                                                     'text-result' => true ), 'match' );
//                                                                       'text-result' => false ) );
        if ( isset( $parameters['name'] ) )
        {
            $newNodes[] = eZTemplateNodeTool::createNamespaceChangeNode( $parameters['name'] );
        }

        $tmpNodes = array();
        $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
        $caseNodes = array();
        $caseCounter = 1;
        if ( is_array( $children ) )
        {
            foreach ( $children as $child )
            {
                $childType = $child[0];
                if ( $childType == eZTemplate::NODE_FUNCTION )
                {
                    if ( $this->templateNodeCaseTransformation( $tpl, $tmpNodes, $caseNodes, $caseCounter, $child, $privateData ) === false )
                    {
                        return false;
                    }
                }
            }
        }
        $newNodes = array_merge( $newNodes, $tmpNodes );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "switch ( \$match )\n{" );
        $newNodes = array_merge( $newNodes, $caseNodes );

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( 'match' );
        if ( isset( $parameters['name'] ) )
        {
            $newNodes[] = eZTemplateNodeTool::createNamespaceRestoreNode();
        }
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, 'match' ) );

        return $newNodes;
    }

    /*!
     Processes the function with all it's children.
    */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $children = $functionChildren;
        $params = $functionParameters;
        $name = "";
        if ( isset( $params["name"] ) )
            $name = $tpl->elementValue( $params["name"], $rootNamespace, $currentNamespace, $functionPlacement );
        $varName = false;
        if ( isset( $params["var"] ) )
            $varName = $tpl->elementValue( $params["var"], $rootNamespace, $currentNamespace, $functionPlacement );
        if ( $currentNamespace != "" )
        {
            if ( $name != "" )
                $name = "$currentNamespace:$name";
            else
                $name = $currentNamespace;
        }
        if ( isset( $params["match"] ) )
            $match = $tpl->elementValue( $params["match"], $rootNamespace, $currentNamespace, $functionPlacement );
        else
        {
            $tpl->missingParameter( $this->SwitchName, "match" );
            return false;
        }

        $items = array();
        $in_items = array();
        $def = null;
        $case = null;
        if ( is_array( $children ) )
        {
            foreach( $children as $child )
            {
                $childType = $child[0];
                if ( $childType == eZTemplate::NODE_FUNCTION )
                {
                    switch ( $child[2] )
                    {
                        case "case":
                        {
                            $child_params = $child[3];
                            if ( isset( $child_params["match"] ) )
                            {
                                $child_match = $child_params["match"];
                                $child_match = $tpl->elementValue( $child_match, $rootNamespace, $currentNamespace, $functionPlacement );
                                if ( !isset( $items[$child_match] ) )
                                {
                                    $items[$child_match] = $child;
                                    if ( is_null( $case ) and
                                         $match == $child_match )
                                    {
                                        $case = $child;
                                    }
                                }
                                else
                                {
                                    $tpl->warning( $this->SwitchName, "Match value $child_match already set, skipping", $functionPlacement );
                                }
                            }
                            else if ( isset( $child_params["in"] ) )
                            {
                                $key_name = null;
                                if ( isset( $child_params["key"] ) )
                                {
                                    $child_key = $child_params["key"];
                                    $key_name = $tpl->elementValue( $child_key, $rootNamespace, $currentNamespace, $functionPlacement );
                                }
                                $child_in = $child_params["in"];
                                $child_in = $tpl->elementValue( $child_in, $rootNamespace, $currentNamespace, $functionPlacement );
                                if ( !is_array( $child_in ) )
                                    break;
                                if ( is_null( $case ) )
                                {
                                    if ( is_null( $key_name ) )
                                    {
                                        if ( in_array( $match, $child_in ) )
                                        {
                                            $case = $child;
                                        }
                                    }
                                    else
                                    {
                                        foreach( $child_in as $child_in_element )
                                        {
                                            if ( !is_array( $key_name ) )
                                                $key_name_array = array( $key_name );
                                            else
                                                $key_name_array = $key_name;
                                            $child_value = $tpl->variableAttribute( $child_in_element, $key_name );
                                            if ( $child_value == $match )
                                            {
                                                $case = $child;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $def = $child;
                            }
                        } break;
                        default:
                        {
                            $tpl->warning( $this->SwitchName, "Only case functions are allowed as children, found \""
                                           . $child[2] . "\"", $functionPlacement );
                        } break;
                    }
                }
                else if ( $childType == eZTemplate::NODE_TEXT )
                {
                    // Ignore text.
                }
                else
                {
                    $tpl->warning( $this->SwitchName, "Only functions are allowed as children, found \""
                                   . $childType . "\"", $functionPlacement );
                }
            }
        }

        if ( is_null( $case ) )
        {
            $case = $def;
        }

        if ( $case !== null )
        {
            if ( $varName !== false )
                $tpl->setVariable( $varName, $match, $name );
            else
                $tpl->setVariable( "match", $match, $name );
            $case_children = $case[1];
            if ( $case_children )
            {
                foreach( $case_children as $case_child )
                {
                    $tpl->processNode( $case_child, $textElements, $rootNamespace, $name );
                }
            }
        }
        else
            $tpl->warning( $this->SwitchName, "No case match and no default case", $functionPlacement );
        return;
    }

    /*!
     Returns true.
    */
    function hasChildren()
    {
        return true;
    }

    /// The name of the switch function
    public $SwitchName;
}

?>

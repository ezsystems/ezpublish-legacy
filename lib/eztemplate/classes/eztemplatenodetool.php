<?php
//
// Definition of eZTemplateNodeTool class
//
// Created on: <13-May-2003 14:12:01 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file eztemplatenodetool.php
*/

/*!
  \class eZTemplateNodeTool eztemplatenodetool.php
  \brief Various tool functions for working with template nodes

*/

class eZTemplateNodeTool
{
    /*!
     Constructor
    */
    function eZTemplateNodeTool()
    {
    }

    /*!
     Removes the children from the function node \a $node.
    */
    function removeFunctionNodeChildren( &$node )
    {
        $node[1] = false;
    }

    /*!
     Removes the parameters from the function node \a $node.
    */
    function removeFunctionNodeParameters( &$node )
    {
        $node[3] = false;
    }

    /*!
     Removes the placement info from the function node \a $node.
    */
    function removeFunctionNodePlacement( &$node )
    {
        $node[4] = false;
    }

    /*!
     Creates a new function node hook with name \a $hookName and optional parameters \a $hookParameters
     and function data \a $hookFunction and returns it.
    */
    function createFunctionNodeHook( &$node, $hookName, $hookParameters = array(), $hookFunction = false )
    {
        $node[5] = array( 'name' => $hookName,
                          'parameters' => $hookParameters,
                          'function' => $hookFunction );
    }

    /*!
     Creates a new variable node and returns it.
    */
    function createVariableNode( $originalNode = false, $variableData = false, $variablePlacement = false,
                                 $parameters = array() )
    {
        $node = array();
        if ( $originalNode )
            $node = $originalNode;
        else
        {
            $node[0] = EZ_TEMPLATE_NODE_VARIABLE;
            $node[1] = false;
            if ( is_array( $variableData ) )
                $node[2] = $variableData;
            else if ( is_numeric( $variableData ) )
                $node[2] = array( array( EZ_TEMPLATE_TYPE_NUMERIC,
                                         $variableData,
                                         false ) );
            else
                $node[2] = array( array( EZ_TEMPLATE_TYPE_STRING,
                                         $variableData,
                                         false ) );
            $node[3] = $variablePlacement;
        }
        $node[4] = $parameters;
        return $node;
    }

    function createCodePieceNode( $codePiece, $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_CODE_PIECE,
                       $codePiece,
                       $parameters );
        return $node;
    }

    function createVariableUnsetNode( $variableName, $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_VARIABLE_UNSET,
                       $variableName,
                       $parameters );
        return $node;
    }

    function createNamespaceChangeNode( $variableData, $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_NAMESPACE_CHANGE,
                       $variableData,
                       $parameters );
        return $node;
    }

    function createNamespaceRestoreNode( $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_NAMESPACE_RESTORE,
                       $parameters );
        return $node;
    }

    function extractNodes( $nodeList, $parameters = array() )
    {
        $match = false;
        if ( isset( $parameters['match'] ) )
            $match = $parameters['match'];
        $newNodes = array();
        $skipNode = false;
        if ( $match['type'] == 'after' )
            $skipNode = true;
        foreach ( $nodeList as $node )
        {
            if ( $match )
            {
                $isMatch = false;
                foreach ( $match['matches'] as $matchItem )
                {
                    $operand1 = $matchItem['match-with'];
                    $matchKeys = $matchItem['match-keys'];
                    $operand2 = $node;
                    foreach ( $matchKeys as $matchKey )
                    {
                        $operand2 = $operand2[$matchKey];
                    }
                    if ( isset( $matchItem['match-function'] ) )
                    {
                        $function = $matchItem['match-function'];
                        $functionResult = $function( $operand1, $operand2 );
                        $isMatch = $functionResult == 0;
                    }
                    else
                    {
                        $isMatch = ( $operand1 == $operand2 );
                    }
                }
                if ( $match['type'] == 'before' )
                {
                    if ( $isMatch )
                        break;
                }
                else if ( $match['type'] = 'after' )
                {
                    if ( $isMatch )
                    {
                        $skipNode = false;
                        $match = false;
                        continue;
                    }
                }
            }
            if ( $skipNode )
                continue;
            $newNodes[] = $node;
        }
        return $newNodes;
    }

    /*!
     \return the placement info from the function node \a $node.
    */
    function extractFunctionNodePlacement( &$node )
    {
        return $node[4];
    }

    /*!
     \return the children of the function node \a $node.
    */
    function extractFunctionNodeChildren( &$node )
    {
        return $node[1];
    }

    /*!
     \return the parameters of the function node \a $node.
    */
    function extractFunctionNodeParameters( &$node )
    {
        return $node[3];
    }

    /*!
     \return the parameters of the function node \a $node.
    */
    function extractFunctionNodeParameterNames( &$node )
    {
        return array_keys( $node[3] );
    }

    /*!
     Creates a pre and post hook for the function node \a $node
     with the children in between the nodes. This means that a nested
     function node will be deflated to a pre/children/post list.
    */
    function deflateFunctionNode( &$node, $preHook, $postHook )
    {
        $newNodes = array();
        $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
        eZTemplateNodeTool::removeFunctionNodeChildren( $node );
        $preNode = $node;
        $preHookParameters = array();
        if ( isset( $preHook['parameters'] ) )
            $preHookParameters = $preHook['parameters'];
        $preHookFunction = false;
        if ( isset( $preHook['function'] ) )
            $preHookFunction = $preHook['function'];
        eZTemplateNodeTool::createFunctionNodeHook( $preNode, $preHook['name'], $preHookParameters, $preHookFunction );
        if ( isset( $preHook['use-parameters'] ) and
             !$preHook['use-parameters'] )
            eZTemplateNodeTool::removeFunctionNodeParameters( $preNode );
        $newNodes[] = $preNode;
        $newNodes = array_merge( $newNodes, $children );
        $postNode = $node;
        $postHookParameters = array();
        if ( isset( $postHook['parameters'] ) )
            $postHookParameters = $postHook['parameters'];
        $postHookFunction = false;
        if ( isset( $postHook['function'] ) )
            $postHookFunction = $postHook['function'];
        eZTemplateNodeTool::createFunctionNodeHook( $postNode, $postHook['name'], $postHookParameters, $postHookFunction );
        if ( isset( $postHook['use-parameters'] ) and
             !$postHook['use-parameters'] )
            eZTemplateNodeTool::removeFunctionNodeParameters( $postNode );
        $newNodes[] = $postNode;
        return $newNodes;
    }
}

?>

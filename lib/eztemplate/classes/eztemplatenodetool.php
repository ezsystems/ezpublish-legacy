<?php
//
// Definition of eZTemplateNodeTool class
//
// Created on: <13-May-2003 14:12:01 amos>
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

/*! \file eztemplatenodetool.php
*/

/*!
  \class eZTemplateNodeTool eztemplatenodetool.php
  \ingroup eZTemplate
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
     \static
     Removes the children from the function node \a $node.
    */
    function removeFunctionNodeChildren( &$node )
    {
        $node[1] = false;
    }

    /*!
     \static
     Removes the parameters from the function node \a $node.
    */
    function removeFunctionNodeParameters( &$node )
    {
        $node[3] = false;
    }

    /*!
     \static
     Removes the placement info from the function node \a $node.
    */
    function removeFunctionNodePlacement( &$node )
    {
        $node[4] = false;
    }

    /*!
     \static
     Creates an element which represents nothing (void).
    */
    function createVoidElement()
    {
        return array( EZ_TEMPLATE_TYPE_VOID );
    }

    /*!
     \static
     Creates an element which represents the static value and returns it,
     the type of the variable determines the type of the element.
    */
    function createConstantElement( $constant, $variablePlacement = false )
    {
        if ( is_array( $constant ) )
            return eZTemplateNodeTool::createArrayElement( $constant, $variablePlacement );
        else if ( is_string( $constant ) )
            return eZTemplateNodeTool::createStringElement( $constant, $variablePlacement );
        else if ( is_bool( $constant ) )
            return eZTemplateNodeTool::createBooleanElement( $constant, $variablePlacement );
        else if ( is_numeric( $constant ) )
            return eZTemplateNodeTool::createNumericElement( $constant, $variablePlacement );
        else
            return eZTemplateNodeTool::createVoidElement();
    }

    /*!
     \static
     \deprecated Use createConstantElement instead.
    */
    function createStaticElement( $static, $variablePlacement = false )
    {
        if ( is_array( $static ) )
            return eZTemplateNodeTool::createArrayElement( $static, $variablePlacement );
        else if ( is_string( $static ) )
            return eZTemplateNodeTool::createStringElement( $static, $variablePlacement );
        else if ( is_bool( $static ) )
            return eZTemplateNodeTool::createBooleanElement( $static, $variablePlacement );
        else if ( is_numeric( $static ) )
            return eZTemplateNodeTool::createNumericElement( $static, $variablePlacement );
        else
            return eZTemplateNodeTool::createVoidElement();
    }

    /*!
     \static
     Creates an element which represents a string and returns it.
    */
    function createStringElement( $string, $variablePlacement = false )
    {
        return array( EZ_TEMPLATE_TYPE_STRING,
                      $string, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents a number (float or integer) and returns it.
    */
    function createNumericElement( $number, $variablePlacement = false )
    {
        return array( EZ_TEMPLATE_TYPE_NUMERIC,
                      $number, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an identifier and returns it.
    */
    function createIdentifierElement( $identifier, $variablePlacement = false )
    {
        return array( EZ_TEMPLATE_TYPE_IDENTIFIER,
                      $identifier, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an array and returns it.

     \param array key list, static.
     \param array values as php code.
     \param values.
    */
    function createDynamicArrayElement( &$arrayKeys, &$arrayValues, $variablePlacement = false )
    {
        return array( EZ_TEMPLATE_TYPE_DYNAMIC_ARRAY,
                      $arrayKeys, $arrayValues, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an array and returns it.
    */
    function createArrayElement( $array, $variablePlacement = false )
    {
        return array( EZ_TEMPLATE_TYPE_ARRAY,
                      $array, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents a boolean and returns it.
    */
    function createBooleanElement( $boolean, $variablePlacement = false )
    {
        if ( !is_bool( $boolean ) )
            $boolean = (bool)$boolean;
        return array( EZ_TEMPLATE_TYPE_BOOLEAN,
                      $boolean, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an array and returns it.
    */
    function createPHPVariableElement( $variableName, $variablePlacement = false )
    {
        return array( EZ_TEMPLATE_TYPE_PHP_VARIABLE,
                      $variableName, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an variable lookup and returns it.
     \param $namespaceScope Type of variable lookup, can be one of:
                            - \b EZ_TEMPLATE_NAMESPACE_SCOPE_GLOBAL, Look for variables at the very top of the namespace tree
                            - \b EZ_TEMPLATE_NAMESPACE_SCOPE_LOCAL, Look for variables at the top of the current file being processed
                            - \b EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, Look for variables from the current namespace
    */
    function createVariableElement( $variableName, $namespaceName, $namespaceScope = EZ_TEMPLATE_NAMESPACE_SCOPE_LOCAL, $variablePlacement = false )
    {
        return array( EZ_TEMPLATE_TYPE_VARIABLE,
                      array( $namespaceName, $namespaceScope, $variableName ), $variablePlacement );
    }

    /*!
     \static
     Creates an element which does lookup on an attribute and returns it.
     \param $attributeValues Must be an array with elements that result in scalar value or string.
    */
    function createAttributeLookupElement( $attributeValues = array(), $variablePlacement = false )
    {
        if ( is_numeric( $attributeValues ) )
            $attributeValues = array( eZTemplateNodeTool::createNumericElement( $attributeValues, $variablePlacement ) );
        else if ( !is_array( $attributeValues ) )
            $attributeValues = array( eZTemplateNodeTool::createStringElement( $attributeValues, $variablePlacement ) );
        return array( EZ_TEMPLATE_TYPE_ATTRIBUTE,
                      $attributeValues, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an operator and returns it.
     \param $name The name of the operator to run.
     \param $parameters An array with parameters, each parameter is an array of variable elements.
    */
    function createOperatorElement( $name, $parameters = array(), $variablePlacement = false )
    {
        return array( EZ_TEMPLATE_TYPE_ATTRIBUTE,
                      array_merge( array( $name ), $parameters ), $variablePlacement );
    }

    /*!
     \return The value of the constant element or \c null if the element is not constant.
     \note Make sure the element is checked with isConstantElement() before running this.
     \note Can also be used on PHP variable elements, it will then fetch the variable name.
    */
    function elementConstantValue( $elements )
    {
        if ( eZTemplateNodeTool::isConstantElement( $elements ) or
             eZTemplateNodeTool::isPHPVariableElement( $elements ) )
            return $elements[0][1];
        return null;
    }

    /*!
     \static
     \deprecated Use elementConstantValue instead.
    */
    function elementStaticValue( $elements )
    {
        if ( eZTemplateNodeTool::isConstantElement( $elements ) or
             eZTemplateNodeTool::isPHPVariableElement( $elements ) )
            return $elements[0][1];
        return null;
    }

    /*!
     \return the array keys of the Dynamic array
    */
    function elementDynamicArrayKeys( $elements )
    {
        if ( !eZTemplateNodeTool::isDynamicArrayElement( $elements ) )
            return null;
        return $elements[0][1];
    }

    /*!
     \return assosiative array of parameters in Dynamic Array
    */
    function elementDynamicArray( $elements )
    {
        if ( !eZTemplateNodeTool::isDynamicArrayElement( $elements ) )
            return null;
        return $elements[0][2];
    }


    /*!
     \return \c true if the element list \a $elements is considered to have a constant value.
             It is considered constant if the following is true:
             - The start value is either numeric, text, identifier, array or boolean
             - It has no operators
             - It has no attribute lookup
    */
    function isConstantElement( $elements )
    {
        $constantElements = array( EZ_TEMPLATE_TYPE_VOID,
                                   EZ_TEMPLATE_TYPE_STRING, EZ_TEMPLATE_TYPE_IDENTIFIER,
                                   EZ_TEMPLATE_TYPE_NUMERIC, EZ_TEMPLATE_TYPE_BOOLEAN, EZ_TEMPLATE_TYPE_ARRAY );

        if ( count( $elements ) == 0 )
            return false;
        if ( count( $elements ) > 1 )
            return false;

        if ( in_array( $elements[0][0], $constantElements ) )
            return true;
        return false;
    }

    /*!
     \deprecated Use isConstantElement instead.
    */
    function isStaticElement( $elements )
    {
        $staticElements = array( EZ_TEMPLATE_TYPE_VOID,
                                 EZ_TEMPLATE_TYPE_STRING, EZ_TEMPLATE_TYPE_IDENTIFIER,
                                 EZ_TEMPLATE_TYPE_NUMERIC, EZ_TEMPLATE_TYPE_BOOLEAN, EZ_TEMPLATE_TYPE_ARRAY );

        if ( count( $elements ) == 0 )
            return false;
        if ( count( $elements ) > 1 )
            return false;

        if ( in_array( $elements[0][0], $staticElements ) )
            return true;
        return false;
    }

    /*!
     \return \c true if the element list \a $elements is considered to be an internal code piece.
    */
    function isInternalCodePiece( $elements )
    {
        if ( isset( $elements[0][0]) && ( $elements[0][0] == EZ_TEMPLATE_TYPE_INTERNAL_CODE_PIECE ) )
            return true;
        return false;
    }

    /*!
     \return \c true if the element list \a $elements is considered to be a variable element.
    */
    function isVariableElement( $elements )
    {
        if ( isset( $elements[0][0] ) && ( $elements[0][0] == EZ_TEMPLATE_TYPE_VARIABLE ) )
            return true;
        return false;
    }

    /*!
     \return \c true if the element list \a $elements is considered to have a PHP variable element.
             The following must be true.
             - The start value is PHP variable
             - It has no operators
             - It has no attribute lookup
    */
    function isPHPVariableElement( $elements )
    {
        if ( count( $elements ) == 0 )
            return false;
        if ( count( $elements ) > 1 )
            return false;

        if ( $elements[0][0] == EZ_TEMPLATE_TYPE_PHP_VARIABLE )
            return true;
        return false;
    }

    /*!
     \return \c true if the element list \a $elements is considered to be numerical.
             It is considered constant if the following is true:
             - The start value is numeric (integer or float)
             - It has no operators
             - It has no attribute lookup
     \sa isConstantElement
     \note If you don't care about pure integers or floats use isConstantElement instead and just use the
           element value as numerical value.
    */
    function isNumericElement( $elements )
    {
        $constantElements = array( EZ_TEMPLATE_TYPE_NUMERIC );

        if ( count( $elements ) == 0 )
            return false;

        if ( in_array( $elements[0][0], $constantElements ) )
            return true;
        return false;
    }

    /*!
     \return \c true if the element list \a $elements is considered to be a string.
             It is considered constant if the following is true:
             - The start value is string or identifier
             - It has no operators
             - It has no attribute lookup
     \sa isConstantElement
     \note If you don't care about pure strings use isConstantElement instead and just use the
           element value as string value.
    */
    function isStringElement( $elements )
    {
        $constantElements = array( EZ_TEMPLATE_TYPE_STRING, EZ_TEMPLATE_TYPE_IDENTIFIER );

        if ( count( $elements ) == 0 )
            return false;

        if ( in_array( $elements[0][0], $constantElements ) )
            return true;
        return false;
    }

    /*!
     \return \c true if the element list \a $elements is considered to be an identifier.
             It is considered constant if the following is true:
             - The start value is identifier
             - It has no operators
             - It has no attribute lookup
     \sa isConstantElement
     \note If you don't care about pure identifiers use isStringElement or isConstantElement instead.
    */
    function isIdentifierElement( $elements )
    {
        $constantElements = array( EZ_TEMPLATE_TYPE_IDENTIFIER );

        if ( count( $elements ) == 0 )
            return false;

        if ( in_array( $elements[0][0], $constantElements ) )
            return true;
        return false;
    }

    /*!
     \return \c true if the element list \a $elements is considered to be a boolean.
             It is considered constant if the following is true:
             - The start value is boolean
             - It has no operators
             - It has no attribute lookup
     \sa isConstantElement
     \note If you don't care about pure booleans use isConstantElement instead and just use the
           element value as boolean value.
    */
    function isBooleanElement( $elements )
    {
        $constantElements = array( EZ_TEMPLATE_TYPE_BOOLEAN );

        if ( count( $elements ) == 0 )
            return false;

        if ( in_array( $elements[0][0], $constantElements ) )
            return true;
        return false;
    }

    /*!
      \static
      Check if element id Dynamic Array
    */
    function isDynamicArrayElement( $elements )
    {
        if ( count( $elements ) == 0 )
            return false;

        if ( $elements[0][0] == EZ_TEMPLATE_TYPE_DYNAMIC_ARRAY )
            return true;
        return false;
    }

    /*!
     \return \c true if the element list \a $elements is considered to be an array.
             It is considered constant if the following is true:
             - The start value is array
             - It has no operators
             - It has no attribute lookup
     \sa isConstantElement
    */
    function isArrayElement( $elements )
    {
        $constantElements = array( EZ_TEMPLATE_TYPE_ARRAY );

        if ( count( $elements ) == 0 )
            return false;

        if ( in_array( $elements[0][0], $constantElements ) )
            return true;
        return false;
    }

    /*!
     \static
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
     \static
     Creates a new variable node and returns it.
    */
    function createVariableNode( $originalNode = false, $variableData = false, $variablePlacement = false,
                                 $parameters = array(), $variableAssignmentName = false, $onlyExisting = false,
                                 $overWrite = true, $assignFromVariable = false, $rememberSet = false )
    {
        $node = array();
        if ( $originalNode )
            $node = $originalNode;
        else
        {
            $node[0] = EZ_TEMPLATE_NODE_VARIABLE;
            $node[1] = $variableAssignmentName;
            if ( is_array( $variableData ) )
                $node[2] = $variableData;
            else if ( $assignFromVariable )
                $node[2] = array( array( EZ_TEMPLATE_TYPE_PHP_VARIABLE,
                                         $variableData,
                                         false ) );
            else if ( is_bool( $variableData ) )
                $node[2] = array( array( EZ_TEMPLATE_TYPE_BOOLEAN,
                                         $variableData,
                                         false ) );
            else if ( is_string( $variableData ) )
                $node[2] = array( array( EZ_TEMPLATE_TYPE_STRING,
                                         $variableData,
                                         false ) );
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
        $node[5] = $onlyExisting;
        $node[6] = $overWrite;
        $node[7] = $rememberSet;
        return $node;
    }

    function createCodePieceElement( $codePiece, $values = false, $placement = false, $tmpValues = false, $knownTypes = true )
    {
        $element = array( EZ_TEMPLATE_TYPE_INTERNAL_CODE_PIECE,
                          $codePiece,
                          $placement,
                          $values, $tmpValues, $knownTypes );
        return $element;
    }

    function createTextNode( $text )
    {
        $node = array( EZ_TEMPLATE_NODE_TEXT, false, $text, false );
        return $node;
    }

    function createWarningNode( $text, $label, $placement = false, $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_WARNING,
                       $text, $label,
                       $parameters, $placement );
        return $node;
    }

    function createErrorNode( $text, $label, $placement = false, $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_ERROR,
                       $text, $label,
                       $parameters, $placement );
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

    /*!
     Creates a new template node that will assign the content of the current output variable
     to the variable named \a $variableName.

     The assignment type is by default text concat (.) and can be changed using \a $assignmentType.
     \param $parameters An array with optional parameters, can contain the followin:
            - spacing - The number of spaces to added for each line this expression creates.
    */
    function createWriteToOutputVariableNode( $variableName, $parameters = array(), $assignmentType = EZ_PHPCREATOR_VARIABLE_APPEND_TEXT )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_OUTPUT_ASSIGN,
                       $variableName,
                       $parameters,
                       $assignmentType );
        return $node;
    }

    /*!
     Creates a new template node that will assign the content of the current output variable
     to the variable named \a $variableName.

     The assignment type is by default variable assignment (=) and can be changed using \a $assignmentType.
     \param $parameters An array with optional parameters, can contain the followin:
            - spacing - The number of spaces to added for each line this expression creates.
    */
    function createAssignFromOutputVariableNode( $variableName, $parameters = array(), $assignmentType = EZ_PHPCREATOR_VARIABLE_ASSIGNMENT )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_OUTPUT_READ,
                       $variableName,
                       $parameters,
                       $assignmentType );
        return $node;
    }

    function createOutputVariableIncreaseNode( $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_OUTPUT_INCREASE,
                       $parameters );
        return $node;
    }

    function createOutputVariableDecreaseNode( $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_OUTPUT_DECREASE,
                       $parameters );
        return $node;
    }

    function createSpacingIncreaseNode( $spacing = 4, $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_SPACING_INCREASE,
                       $spacing, $parameters );
        return $node;
    }

    function createSpacingDecreaseNode( $spacing = 4, $parameters = array() )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_SPACING_DECREASE,
                       $spacing, $parameters );
        return $node;
    }

    function createNamespaceChangeNode( $variableData, $parameters = array() )
    {
        if ( is_string( $variableData ) )
            $variableData = array( eZTemplateNodeTool::createStringElement( $variableData ) );
        else if ( is_numeric( $variableData ) )
            $variableData = array( eZTemplateNodeTool::createNumericElement( $variableData ) );
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

    function createResourceAcquisitionNode( $resourceName, $templateName, $fileName,
                                            $method, $extraParameters, $placement = false,
                                            $parameters = array(), $newRootNamespace = false, $resourceVariableName = false )
    {
        $node = array( EZ_TEMPLATE_NODE_INTERNAL_RESOURCE_ACQUISITION,
                       $resourceName, $templateName, $fileName,
                       $method, $extraParameters, $placement );
        if ( count( $parameters ) > 0 )
            $node[] = $parameters;
        else
            $node[] = false;
        $node[] = $newRootNamespace;
        $node[] = $resourceVariableName;
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
                $isMatch = true;
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
                        $wasMatch = $functionResult == 0;
                    }
                    else
                    {
                        if ( is_array( $operand1 ) )
                            $wasMatch = in_array( $operand2, $operand1 );
                        else
                            $wasMatch = ( $operand1 == $operand2 );
                    }
                    if ( !$wasMatch )
                    {
                        $isMatch = false;
                        break;
                    }
                }
                if ( $match['type'] == 'equal' )
                {
                    if ( !$isMatch )
                        continue;
                }
                else if ( $match['type'] == 'before' )
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
            if ( $match and isset( $match['filter'] ) )
            {
                $isMatch = true;
                foreach ( $match['filter'] as $matchFilterItem )
                {
                    foreach ( $matchFilterItem as $matchItem )
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
                            $wasMatch = $functionResult == 0;
                        }
                        else
                        {
                            if ( is_array( $operand1 ) )
                                $wasMatch = in_array( $operand2, $operand1 );
                            else
                                $wasMatch = ( $operand1 == $operand2 );
                        }
                        if ( !$wasMatch )
                        {
                            $isMatch = false;
                            break;
                        }
                    }
                    if ( $isMatch )
                        break;
                }
                if ( $isMatch )
                    continue;
            }
            $newNodes[] = $node;
        }
        return $newNodes;
    }

    /*!
     \static
     \return the placement info from the function node \a $node.
    */
    function extractFunctionNodePlacement( &$node )
    {
        return $node[4];
    }

    /*!
     \static
     \return the children of the function node \a $node.
    */
    function extractFunctionNodeChildren( &$node )
    {
        return $node[1];
    }

    /*!
     \static
     \return the parameters of the function node \a $node.
    */
    function extractFunctionNodeParameters( &$node )
    {
        return $node[3];
    }

    /*!
     \static
     \return the parameters of the function node \a $node.
    */
    function extractFunctionNodeParameterNames( &$node )
    {
        return array_keys( $node[3] );
    }

    /*!
     \static
     \return the variable data from the variable node \a $node.
    */
    function extractVariableNodeData( &$node )
    {
        return $node[1];
    }

    /*!
     \static
     \return the name of the function for the function node \a $node.
    */
    function extractFunctionNodeName( &$node )
    {
        return $node[2];
    }

    /*!
     \static
     \return the variable placement from the variable node \a $node.
    */
    function extractVariableNodePlacement( &$node )
    {
        return $node[2];
    }

    /*!
     \static
     \return the parameters for the operator node \a $node.
    */
    function extractOperatorNodeParameters( &$node )
    {
        return array_slice( $node[1], 1 );
    }

    /*!
     \static
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

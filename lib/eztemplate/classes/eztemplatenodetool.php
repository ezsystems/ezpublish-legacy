<?php
/**
 * File containing the eZTemplateNodeTool class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateNodeTool eztemplatenodetool.php
  \ingroup eZTemplate
  \brief Various tool functions for working with template nodes

*/

class eZTemplateNodeTool
{
    /*!
     \static
     Removes the children from the function node \a $node.
    */
    static function removeFunctionNodeChildren( &$node )
    {
        $node[1] = false;
    }

    /*!
     \static
     Removes the parameters from the function node \a $node.
    */
    static function removeFunctionNodeParameters( &$node )
    {
        $node[3] = false;
    }

    /*!
     \static
     Removes the placement info from the function node \a $node.
    */
    static function removeFunctionNodePlacement( &$node )
    {
        $node[4] = false;
    }

    /*!
     \static
     Creates an element which represents nothing (void).
    */
    static function createVoidElement()
    {
        return array( eZTemplate::TYPE_VOID );
    }

    /*!
     \static
     Creates an element which represents the static value and returns it,
     the type of the variable determines the type of the element.
    */
    static function createConstantElement( $constant, $variablePlacement = false )
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

    /**
     * @deprecated Use createConstantElement() instead.
     * @see createConstantElement()
     */
    static function createStaticElement( $static, $variablePlacement = false )
    {
        return self::createConstantElement( $static, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents a string and returns it.
    */
    static function createStringElement( $string, $variablePlacement = false )
    {
        return array( eZTemplate::TYPE_STRING,
                      $string, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents a number (float or integer) and returns it.
    */
    static function createNumericElement( $number, $variablePlacement = false )
    {
        return array( eZTemplate::TYPE_NUMERIC,
                      $number, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an identifier and returns it.
    */
    static function createIdentifierElement( $identifier, $variablePlacement = false )
    {
        return array( eZTemplate::TYPE_IDENTIFIER,
                      $identifier, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an array and returns it.

     \param array key list, static.
     \param array values as php code.
     \param values.
    */
    static function createDynamicArrayElement( &$arrayKeys, &$arrayValues, $variablePlacement = false )
    {
        return array( eZTemplate::TYPE_DYNAMIC_ARRAY,
                      $arrayKeys, $arrayValues, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an array and returns it.
    */
    static function createArrayElement( $array, $variablePlacement = false )
    {
        return array( eZTemplate::TYPE_ARRAY,
                      $array, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents a boolean and returns it.
    */
    static function createBooleanElement( $boolean, $variablePlacement = false )
    {
        if ( !is_bool( $boolean ) )
            $boolean = (bool)$boolean;
        return array( eZTemplate::TYPE_BOOLEAN,
                      $boolean, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an array and returns it.
    */
    static function createPHPVariableElement( $variableName, $variablePlacement = false )
    {
        return array( eZTemplate::TYPE_PHP_VARIABLE,
                      $variableName, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an variable lookup and returns it.
     \param $namespaceScope Type of variable lookup, can be one of:
                            - \b eZTemplate::NAMESPACE_SCOPE_GLOBAL, Look for variables at the very top of the namespace tree
                            - \b eZTemplate::NAMESPACE_SCOPE_LOCAL, Look for variables at the top of the current file being processed
                            - \b eZTemplate::NAMESPACE_SCOPE_RELATIVE, Look for variables from the current namespace
    */
    static function createVariableElement( $variableName, $namespaceName, $namespaceScope = eZTemplate::NAMESPACE_SCOPE_LOCAL, $variablePlacement = false )
    {
        return array( eZTemplate::TYPE_VARIABLE,
                      array( $namespaceName, $namespaceScope, $variableName ), $variablePlacement );
    }

    /*!
     \static
     Creates an element which does lookup on an attribute and returns it.
     \param $attributeValues Must be an array with elements that result in scalar value or string.
    */
    static function createAttributeLookupElement( $attributeValues = array(), $variablePlacement = false )
    {
        if ( is_numeric( $attributeValues ) )
            $attributeValues = array( eZTemplateNodeTool::createNumericElement( $attributeValues, $variablePlacement ) );
        else if ( !is_array( $attributeValues ) )
            $attributeValues = array( eZTemplateNodeTool::createStringElement( $attributeValues, $variablePlacement ) );
        return array( eZTemplate::TYPE_ATTRIBUTE,
                      $attributeValues, $variablePlacement );
    }

    /*!
     \static
     Creates an element which represents an operator and returns it.
     \param $name The name of the operator to run.
     \param $parameters An array with parameters, each parameter is an array of variable elements.
    */
    static function createOperatorElement( $name, $parameters = array(), $variablePlacement = false )
    {
        return array( eZTemplate::TYPE_ATTRIBUTE,
                      array_merge( array( $name ), $parameters ), $variablePlacement );
    }

    /*!
     \return The value of the constant element or \c null if the element is not constant.
     \note Make sure the element is checked with isConstantElement() before running this.
     \note Can also be used on PHP variable elements, it will then fetch the variable name.
    */
    static function elementConstantValue( $elements )
    {
        if ( eZTemplateNodeTool::isConstantElement( $elements ) or
             eZTemplateNodeTool::isPHPVariableElement( $elements ) )
            return $elements[0][1];
        return null;
    }

    /**
     * @deprecated Use elementConstantValue instead.
     * @see elementConstantValue().
     */
    static function elementStaticValue( $elements )
    {
        return self::elementConstantValue( $elements );
    }

    /*!
     \return the array keys of the Dynamic array
    */
    static function elementDynamicArrayKeys( $elements )
    {
        if ( !eZTemplateNodeTool::isDynamicArrayElement( $elements ) )
            return null;
        return $elements[0][1];
    }

    /*!
     \return assosiative array of parameters in Dynamic Array
    */
    static function elementDynamicArray( $elements )
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
    static function isConstantElement( $elements )
    {
        if ( !isset( $elements[0][0] ) || count( $elements ) > 1 )
        {
            return false;
        }

        switch ( $elements[0][0] )
        {
            case eZTemplate::TYPE_VOID:
            case eZTemplate::TYPE_STRING:
            case eZTemplate::TYPE_IDENTIFIER:
            case eZTemplate::TYPE_NUMERIC:
            case eZTemplate::TYPE_BOOLEAN:
            case eZTemplate::TYPE_ARRAY:
                return true;
        }

        return false;
    }

    /*!
     \deprecated Use isConstantElement instead.
    */
    static function isStaticElement( $elements )
    {
        return self::isConstantElement( $elements );
    }

    /*!
     \return \c true if the element list \a $elements is considered to be an internal code piece.
    */
    static function isInternalCodePiece( $elements )
    {
        return isset( $elements[0][0]) && $elements[0][0] == eZTemplate::TYPE_INTERNAL_CODE_PIECE;
    }

    /*!
     \return \c true if the element list \a $elements is considered to be a variable element.
    */
    static function isVariableElement( $elements )
    {
        return isset( $elements[0][0] ) && $elements[0][0] == eZTemplate::TYPE_VARIABLE;
    }

    /*!
     \return \c true if the element list \a $elements is considered to have a PHP variable element.
             The following must be true.
             - The start value is PHP variable
             - It has no operators
             - It has no attribute lookup
    */
    static function isPHPVariableElement( $elements )
    {
        return count( $elements ) === 1 && isset( $elements[0][0] ) && $elements[0][0] == eZTemplate::TYPE_PHP_VARIABLE;
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
    static function isNumericElement( $elements )
    {
        return isset( $elements[0][0] ) && $elements[0][0] == eZTemplate::TYPE_NUMERIC;
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
    static function isStringElement( $elements )
    {
        if ( !isset( $elements[0][0] ) )
        {
            return false;
        }

        switch ( $elements[0][0] )
        {
            case eZTemplate::TYPE_STRING:
            case eZTemplate::TYPE_IDENTIFIER:
                return true;
        }

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
    static function isIdentifierElement( $elements )
    {
        return isset( $elements[0][0] ) && $elements[0][0] == eZTemplate::TYPE_IDENTIFIER;
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
    static function isBooleanElement( $elements )
    {
        return isset( $elements[0][0] ) && $elements[0][0] == eZTemplate::TYPE_BOOLEAN;
    }

    /*!
      \static
      Check if element id Dynamic Array
    */
    static function isDynamicArrayElement( $elements )
    {
        return isset( $elements[0][0] ) && $elements[0][0] == eZTemplate::TYPE_DYNAMIC_ARRAY;
    }

    /*!
     \return \c true if the element list \a $elements is considered to be an array.
             It is considered constant if the following is true:
             - The start value is array
             - It has no operators
             - It has no attribute lookup
     \sa isConstantElement
    */
    static function isArrayElement( $elements )
    {
        return isset( $elements[0][0] ) && $elements[0][0] == eZTemplate::TYPE_ARRAY;
    }

    /*!
     \static
     Creates a new function node hook with name \a $hookName and optional parameters \a $hookParameters
     and function data \a $hookFunction and returns it.
    */
    static function createFunctionNodeHook( &$node, $hookName, $hookParameters = array(), $hookFunction = false )
    {
        $node[5] = array( 'name' => $hookName,
                          'parameters' => $hookParameters,
                          'function' => $hookFunction );
    }

    /*!
     \static
     Creates a new variable node and returns it.
    */
    static function createVariableNode( $originalNode = false, $variableData = false, $variablePlacement = false,
                                 $parameters = array(), $variableAssignmentName = false, $onlyExisting = false,
                                 $overWrite = true, $assignFromVariable = false, $rememberSet = false )
    {
        $node = array();
        if ( $originalNode )
            $node = $originalNode;
        else
        {
            $node[0] = eZTemplate::NODE_VARIABLE;
            $node[1] = $variableAssignmentName;
            if ( is_array( $variableData ) )
                $node[2] = $variableData;
            else if ( $assignFromVariable )
                $node[2] = array( array( eZTemplate::TYPE_PHP_VARIABLE,
                                         $variableData,
                                         false ) );
            else if ( is_bool( $variableData ) )
                $node[2] = array( array( eZTemplate::TYPE_BOOLEAN,
                                         $variableData,
                                         false ) );
            else if ( is_string( $variableData ) )
                $node[2] = array( array( eZTemplate::TYPE_STRING,
                                         $variableData,
                                         false ) );
            else if ( is_numeric( $variableData ) )
                $node[2] = array( array( eZTemplate::TYPE_NUMERIC,
                                         $variableData,
                                         false ) );
            else
                $node[2] = array( array( eZTemplate::TYPE_STRING,
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

    static function createCodePieceElement( $codePiece, $values = false, $placement = false, $tmpValues = false, $knownTypes = true )
    {
        return array( eZTemplate::TYPE_INTERNAL_CODE_PIECE, $codePiece, $placement, $values, $tmpValues, $knownTypes );
    }

    static function createTextNode( $text )
    {
        return array( eZTemplate::NODE_TEXT, false, $text, false );
    }

    static function createWarningNode( $text, $label, $placement = false, $parameters = array() )
    {
        return array( eZTemplate::NODE_INTERNAL_WARNING, $text, $label, $parameters, $placement );
    }

    static function createErrorNode( $text, $label, $placement = false, $parameters = array() )
    {
        return array( eZTemplate::NODE_INTERNAL_ERROR, $text, $label, $parameters, $placement );
    }

    static function createCodePieceNode( $codePiece, $parameters = array() )
    {
        return array( eZTemplate::NODE_INTERNAL_CODE_PIECE, $codePiece, $parameters );
    }

    static function createVariableUnsetNode( $variableName, $parameters = array() )
    {
        return array( eZTemplate::NODE_INTERNAL_VARIABLE_UNSET, $variableName, $parameters );
    }

    /*!
     Creates a new template node that will assign the content of the current output variable
     to the variable named \a $variableName.

     The assignment type is by default text concat (.) and can be changed using \a $assignmentType.
     \param $parameters An array with optional parameters, can contain the followin:
            - spacing - The number of spaces to added for each line this expression creates.
    */
    static function createWriteToOutputVariableNode( $variableName, $parameters = array(), $assignmentType = eZPHPCreator::VARIABLE_APPEND_TEXT )
    {
        return array( eZTemplate::NODE_INTERNAL_OUTPUT_ASSIGN, $variableName, $parameters, $assignmentType );
    }

    /*!
     Creates a new template node that will assign the content of the current output variable
     to the variable named \a $variableName.

     The assignment type is by default variable assignment (=) and can be changed using \a $assignmentType.
     \param $parameters An array with optional parameters, can contain the followin:
            - spacing - The number of spaces to added for each line this expression creates.
    */
    static function createAssignFromOutputVariableNode( $variableName, $parameters = array(), $assignmentType = eZPHPCreator::VARIABLE_ASSIGNMENT )
    {
        return array( eZTemplate::NODE_INTERNAL_OUTPUT_READ, $variableName, $parameters, $assignmentType );
    }

    static function createOutputVariableIncreaseNode( $parameters = array() )
    {
        return array( eZTemplate::NODE_INTERNAL_OUTPUT_INCREASE, $parameters );
    }

    static function createOutputVariableDecreaseNode( $parameters = array() )
    {
        return array( eZTemplate::NODE_INTERNAL_OUTPUT_DECREASE, $parameters );
    }

    static function createSpacingIncreaseNode( $spacing = 4, $parameters = array() )
    {
        return array( eZTemplate::NODE_INTERNAL_OUTPUT_SPACING_INCREASE, $spacing, $parameters );
    }

    static function createSpacingDecreaseNode( $spacing = 4, $parameters = array() )
    {
        return array( eZTemplate::NODE_INTERNAL_SPACING_DECREASE, $spacing, $parameters );
    }

    static function createNamespaceChangeNode( $variableData, $parameters = array() )
    {
        if ( is_string( $variableData ) )
            $variableData = array( eZTemplateNodeTool::createStringElement( $variableData ) );
        else if ( is_numeric( $variableData ) )
            $variableData = array( eZTemplateNodeTool::createNumericElement( $variableData ) );
        return array( eZTemplate::NODE_INTERNAL_NAMESPACE_CHANGE, $variableData, $parameters );
    }

    static function createNamespaceRestoreNode( $parameters = array() )
    {
        return array( eZTemplate::NODE_INTERNAL_NAMESPACE_RESTORE, $parameters );
    }

    static function createResourceAcquisitionNode( $resourceName, $templateName, $fileName,
                                            $method, $extraParameters, $placement = false,
                                            $parameters = array(), $newRootNamespace = false, $resourceVariableName = false )
    {
        $node = array( eZTemplate::NODE_INTERNAL_RESOURCE_ACQUISITION,
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

    static function extractNodes( $nodeList, $parameters = array() )
    {
        $match = false;
        if ( isset( $parameters['match'] ) )
            $match = $parameters['match'];
        $newNodes = array();
        $skipNode = false;
        if ( $match['type'] == 'after' )
            $skipNode = true;

        if ( !is_array( $nodeList ) )
        {
            return $newNodes;
        }
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
    static function extractFunctionNodePlacement( &$node )
    {
        return $node[4];
    }

    /*!
     \static
     \return the children of the function node \a $node.
    */
    static function extractFunctionNodeChildren( &$node )
    {
        return $node[1];
    }

    /*!
     \static
     \return the parameters of the function node \a $node.
    */
    static function extractFunctionNodeParameters( &$node )
    {
        return $node[3];
    }

    /*!
     \static
     \return the parameters of the function node \a $node.
    */
    static function extractFunctionNodeParameterNames( &$node )
    {
        return array_keys( $node[3] );
    }

    /*!
     \static
     \return the variable data from the variable node \a $node.
    */
    static function extractVariableNodeData( &$node )
    {
        return $node[1];
    }

    /*!
     \static
     \return the name of the function for the function node \a $node.
    */
    static function extractFunctionNodeName( &$node )
    {
        return $node[2];
    }

    /*!
     \static
     \return the variable placement from the variable node \a $node.
    */
    static function extractVariableNodePlacement( &$node )
    {
        return $node[2];
    }

    /*!
     \static
     \return the parameters for the operator node \a $node.
    */
    static function extractOperatorNodeParameters( &$node )
    {
        return array_slice( $node[1], 1 );
    }

    /*!
     \static
     Creates a pre and post hook for the function node \a $node
     with the children in between the nodes. This means that a nested
     function node will be deflated to a pre/children/post list.
    */
    static function deflateFunctionNode( &$node, $preHook, $postHook )
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

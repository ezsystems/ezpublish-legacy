<?php
//
// Definition of eZTemplateLogicOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
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

/*!
  \class eZTemplateLogicOperator eztemplatelogicoperator.php
  \ingroup eZTemplateOperators
  \brief Logical operators for creating and manipulating booleans

  This class adds powerful template handling by enabling logical operators
  which alter the output of templates from input values.

  How counts are interpreted:
  -# If the data is an array the array count is used
  -# If the data is an object the object attribute count is used
  -# If the data is a numeric the value is used
  -# If the data is a boolean false is 0 and true is 1
  -# For all other data 0 is used

  Data is considered null (or false) if the data count is 0 (see above) or
  the data is really null (is_null). Data is considered true if it is not null.

  The supported operators are:
  - lt\n
    Returns true if the input count is less than the parameter data count. See how
    count is interpreted above.
  - le\n
    Same as lt but use less than or equal to.
  - gt\n
    Same as lt but returns true for input greater than data.
  - ge\n
    Same as gt but use greater than or equal to.
  - eq\n
    Returns true if all the input parameters match. Matching is casual meaning
    that an integer of value 0 will match a boolean of type false.
  - ne\n
    Returns true if one or more of the input parameters does not match.
    Matching is casual meaning that an integer of value 0 will match a boolean
    of type false.
  - null\n
    Returns true if the data is null, false otherwise
  - not\n
    Returns true if the data is false or false if data is true
  - true
  - false\n
    Creates a true/false boolean
  - or\n
    Evaluates all parameter values until one is found to be true (see above), then
    returns that value. The remaining parameters are not evaluated at all.
    If there are no parameter or all elements were false it returns false.
  - and\n
    Evaluates all parameter values until one is found to be false (see above), then
    returns that false. The remaining parameters are not evaluated at all.
    If there are no parameter it returns false, if no elements were false it returns the last parameter value.
  - choose\n
    Uses the input count to pick one of the parameter elements. The input count equals
    the parameter index.

*/

class eZTemplateLogicOperator
{
    /*!
     Initializes the operator class with the various operator names.
    */
    function eZTemplateLogicOperator()
    {
        $this->Operators = array( 'lt', 'gt', 'le', 'ge', 'eq', 'ne',
                                  'null', 'not',
                                  'or', 'and',
                                  'true', 'false', 'choose' );
        foreach ( $this->Operators as $operator )
        {
            $name = $operator . 'Name';
            $name[0] = $name[0] & "\xdf";
            $this->$name = $operator;
        }
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    function operatorTemplateHints()
    {
        return array( $this->LtName => array( 'input' => true,
                                              'output' => true,
                                              'parameters' => 1,
                                              'element-transformation' => true,
                                              'transform-parameters' => true,
                                              'input-as-parameter' => true,
                                              'element-transformation-func' => 'logicalComparisonTransformation'),
                      $this->GtName => array( 'input' => true,
                                              'output' => true,
                                              'parameters' => 1,
                                              'element-transformation' => true,
                                              'transform-parameters' => true,
                                              'input-as-parameter' => true,
                                              'element-transformation-func' => 'logicalComparisonTransformation'),
                      $this->LeName => array( 'input' => true,
                                              'output' => true,
                                              'parameters' => 1,
                                              'element-transformation' => true,
                                              'transform-parameters' => true,
                                              'input-as-parameter' => true,
                                              'element-transformation-func' => 'logicalComparisonTransformation'),
                      $this->GeName => array( 'input' => true,
                                              'output' => true,
                                              'parameters' => 1,
                                              'element-transformation' => true,
                                              'transform-parameters' => true,
                                              'input-as-parameter' => true,
                                              'element-transformation-func' => 'logicalComparisonTransformation'),

                      $this->EqName => array( 'input' => true,
                                              'output' => true,
                                              'parameters' => true,
                                              'element-transformation' => true,
                                              'transform-parameters' => true,
                                              'input-as-parameter' => true,
                                              'element-transformation-func' => 'logicalComparisonTransformation'),
                      $this->NeName => array( 'input' => true,
                                              'output' => true,
                                              'parameters' => true,
                                              'element-transformation' => true,
                                              'transform-parameters' => true,
                                              'input-as-parameter' => true,
                                              'element-transformation-func' => 'logicalComparisonTransformation'),

                      $this->NullName  => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => false ),

                      $this->OrName => array( 'input' => true,
                                              'output' => true,
                                              'parameters' => true,
                                              'element-transformation' => true,
                                              'transform-parameters' => true,
                                              'input-as-parameter' => true,
                                              'element-transformation-func' => 'logicalComparisonTransformation'),
                      $this->AndName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'logicalComparisonTransformation'),

                      $this->NotName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'negateTransformation'),

                      $this->ChooseName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => true,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => true,
                                                  'element-transformation-func' => 'chooseTransformation'),
                      $this->TrueName => array( 'input' => false,
                                                'output' => true,
                                                'parameters' => false,
                                                'static' => true,
                                                'element-transformation' => true,
                                                'transform-parameters' => true,
                                                'input-as-parameter' => true,
                                                'element-transformation-func' => 'trueFalseTransformation'),
                      $this->FalseName => array( 'input' => false,
                                                 'output' => true,
                                                 'parameters' => false,
                                                 'static' => true,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => true,
                                                 'element-transformation-func' => 'trueFalseTransformation') );
    }

//     function operatorTemplateStatistics( $name, &$variableItem, $variablePlacement, &$tpl, &$resourceData, $namespace, &$stats )
//     {
//         if ( $name == $this->EQName )
//         {
//             print( $name . "\n" );
//             $parameters = eZTemplateNodeTool::extractOperatorNodeParameters( $variableItem );
//             for ( $i = 0; $i < count( $parameters ); ++$i )
//             {
//                 $parameter =& $parameters[$i];
//                 $parameterData = eZTemplateNodeTool::extractVariableNodeData( $parameter );
//                 $parameterPlacement = eZTemplateNodeTool::extractVariableNodePlacement( $parameter );
//                 eZTemplateCompiler::calculateVariableNodeStatistics( $tpl, $parameter, $parameterPlacement,
//                                                                      $resourceData, $namespace, $stats );
//             }
//             return true;
//         }
//     }

    function operatorCompiledStaticData( $operatorName )
    {
        switch( $operatorName )
        {
            case $this->TrueName:
            {
                return true;
            } break;
            case $this->FalseName:
            {
                return false;
            } break;
        }
        return false;
    }

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( $this->LtName => array( "threshold" => array( "type" => "mixed",
                                                                    "required" => true,
                                                                    "default" => false ) ),
                      $this->GtName => array( "threshold" => array( "type" => "mixed",
                                                                    "required" => true,
                                                                    "default" => false ) ),
                      $this->LeName => array( "threshold" => array( "type" => "mixed",
                                                                    "required" => true,
                                                                    "default" => false ) ),
                      $this->GeName => array( "threshold" => array( "type" => "mixed",
                                                                    "required" => true,
                                                                    "default" => false ) ) );
    }


    function logicalComparisonTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                               &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;
        $minParameterCount = $maxParameterCount = 2;

        switch ( $operatorName )
        {
        case 'lt':
            $operator = '<';
            break;
        case 'le':
            $operator = '<=';
            break;
        case 'gt':
            $operator = '>';
            break;
        case 'ge':
            $operator = '>=';
            break;
        case 'eq':
            $operator = '==';
            break;
        case 'ne':
            $operator = '!=';
            break;
        case 'and':
            $operator = 'and';
            $maxParameterCount = false;
            break;
        case 'or':
            $operator = 'or';
            $maxParameterCount = false;
            break;
        }
        if ( ( count( $parameters ) < 2 ) ||
             ( $maxParameterCount && ( count( $parameters ) > $maxParameterCount ) ) )
        {
            return false;
        }
        $newElements = array();

        /* Check if all variables are integers. This is for optimization */
        {
            $code = '%output% = (';
            $counter = 0;
            foreach ( $parameters as $parameter )
            {
                if ( $counter++ )
                {
                    $code .= " $operator";
                }
                $code .= " ( %$counter% )";
                $values[] = $parameter;
            }
            $code .= " );\n";
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function negateTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                   &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( ( count( $parameters ) != 1) )
        {
            return false;
        }
        $newElements = array();

        $values[] = $parameters[0];
        $code = "%output% = !( %1% );\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function trueFalseTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                      &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        if ( ( count( $parameters ) != 0 ) )
        {
            return false;
        }
        $newElements = array();

        /* Check if all variables are integers. This is for optimization */
        $code = "%output% = $operatorName;\n";
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function chooseTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                   &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( ( count( $parameters ) < 2) )
        {
            return false;
        }

        $tmpValues = false;
        $newElements = array();
        /* This is an optimization step, but a worthwhile one if you need to
         * pick from a large array */
        if ( $parameters[0][0][0] == EZ_TEMPLATE_TYPE_NUMERIC )
        {
            $selected = $parameters[0][0][1];

            if ( $selected > ( count( $parameters ) - 1 ) )
            {
                return false;
            }

            $values[] = $parameters[$selected + 1];
            $code = "%output% = %1%;\n";
        }
        else
        {
            $values[] = $parameters[0];
            $array = $parameters;
            unset( $array[0] );

            $code = "%tmp1% = array( ";
            $counter = 2;
            foreach ($array as $element)
            {
                $values[] = $element;
                $code .= "%$counter%, ";
                $counter++;
            }
            $code .= ");\n";
            $code .= "%output% = %tmp1%[%1%];\n";
            $tmpValues = 1;
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values, false, $tmpValues );
        return $newElements;
    }

    /*!
     * Returns the 'count' value as described in the introduction or 'false' in
     * case of an unsupported type
     */
    function getValueCount( $val )
    {
        $val_cnt = false;

        if ( is_array( $val ) )
        {
            $val_cnt = count( $val );
        }
        else if ( is_null( $val ) )
        {
            $cnt = 0;
        }
        else if ( is_object( $val ) and
                  method_exists( $val, "attributes" ) )
        {
            $val_cnt = count( $val->attributes() );
        }
        else if ( is_numeric( $val ) )
        {
            $val_cnt = $val;
        }
        else if ( is_string( $val ) )
        {
            $cnt = strlen( $val );
        }
        return $val_cnt;
    }

    /*!
     Examines the input value and outputs a boolean value. See class documentation for more information.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$value, &$namedParameters )
    {
        if ( $operatorName == $this->LtName or $operatorName == $this->GtName or
             $operatorName == $this->LeName or $operatorName == $this->GeName )
        {
            $val = $namedParameters["threshold"];

            if ( ( $val_cnt = $this->getValueCount( $val ) ) === false )
            {
                $tpl->warning( $operatorName, "Unsupported input type: " . gettype( $val ) . "( $val ), must be either array, attribute object or numerical" );
                return;
            }
        }
        switch ( $operatorName )
        {
            case $this->TrueName:
            case $this->FalseName:
            {
                $value = ( $operatorName == $this->TrueName );
            } break;
            case $this->NeName:
            {
                if ( count( $operatorParameters ) >= 1 )
                {
                    if ( count( $operatorParameters ) == 1 )
                    {
                        $lastOperand =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                        $value = ( $lastOperand != $value );
                    }
                    else
                    {
                        $similar = false;
                        $value = false;
                        $lastOperand =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                        for ( $i = 1; $i < count( $operatorParameters ); ++$i )
                        {
                            $operand =& $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace );
                            if ( $operand != $lastOperand )
                            {
                                $value = true;
                                break;
                            }
                            unset( $lastOperand );
                            $lastOperand =& $operand;
                        }
                    }
                }
                else
                {
                    $value = false;
                    $tpl->warning( $operatorName, "Requires one parameter for input checking or two or more for parameter checking" );
                }
            } break;
            case $this->EqName:
            {
                if ( count( $operatorParameters ) >= 1 )
                {
                    if ( count( $operatorParameters ) == 1 )
                    {
                        $lastOperand =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                        $value = ( $lastOperand == $value );
                    }
                    else
                    {
                        $similar = false;
                        $value = true;
                        $lastOperand =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                        for ( $i = 1; $i < count( $operatorParameters ); ++$i )
                        {
                            $operand =& $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace );
                            if ( $operand != $lastOperand )
                            {
                                $value = false;
                                break;
                            }
                            unset( $lastOperand );
                            $lastOperand =& $operand;
                        }
                    }
                }
                else
                {
                    $value = false;
                    $tpl->warning( $operatorName, "Requires one parameter for input checking or two or more for parameter checking" );
                }
            } break;
            case $this->OrName:
            {
                for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                {
                    $operand = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace );
                    $operand_logic = false;
                    if ( is_array( $operand ) )
                        $operand_logic = count( $operand ) > 0;
                    else if ( is_numeric( $operand ) )
                        $operand_logic = $operand != 0;
                    else if ( is_null( $operand ) )
                        $operand_logic = false;
                    else if ( is_object( $operand ) )
                        $operand_logic = ( method_exists( $operand, "attributes" ) and
                                           method_exists( $operand, "attribute" ) );
                    else if ( is_bool( $operand ) )
                        $operand_logic = $operand;
                    if ( $operand_logic )
                    {
                        $value = $operand;
                        return;
                    }
                }
                $value = false;
            } break;
            case $this->AndName:
            {
                $operand = null;
                for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                {
                    $operand = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace );
                    $operand_logic = false;
                    if ( is_array( $operand ) )
                        $operand_logic = count( $operand ) > 0;
                    else if ( is_numeric( $operand ) )
                        $operand_logic = $operand != 0;
                    else if ( is_null( $operand ) )
                        $operand_logic = false;
                    else if ( is_object( $operand ) )
                        $operand_logic = ( method_exists( $operand, "attributes" ) and
                                           method_exists( $operand, "attribute" ) );
                    else if ( is_bool( $operand ) )
                        $operand_logic = $operand;
                    if ( !$operand_logic )
                    {
                        $value = false;
                        return;
                    }
                }
                $value = $operand;
            } break;
            case $this->ChooseName:
            {
                if ( is_array( $value ) or
                     ( is_object( $value ) and
                       method_exists( $value, "attributes" ) ) )
                {
                    $tpl->error( $operatorName, "Only supports numeric and boolean values" );
                    return;
                }
                else if ( is_numeric( $value ) )
                    $index = $value;
                else if ( is_null( $value ) )
                    $index = 0;
                else
                    $index = $value ? 1 : 0;
                if ( $index < 0 or
                     $index > count( $operatorParameters ) )
                {
                    $tpl->error( $operatorName, "Index $index out of range" );
                    return;
                }
                $value = $tpl->elementValue( $operatorParameters[$index], $rootNamespace, $currentNamespace );
            } break;
            case $this->LtName:
            case $this->GtName:
            case $this->LeName:
            case $this->GeName:
            {
                if ( ( $cnt = $this->getValueCount( $value ) ) === false )
                {
                    $tpl->warning( $operatorName, "Unsupported input type: " . gettype( $value ) . "( $value ), must be either array, attribute object or numerical" );
                    return;
                }
                if ( $operatorName == $this->LtName )
                    $value = ( $cnt < $val_cnt );
                else if ( $operatorName == $this->GtName )
                    $value = ( $cnt > $val_cnt );
                else if ( $operatorName == $this->LeName )
                    $value = ( $cnt <= $val_cnt );
                else if ( $operatorName == $this->GeName )
                    $value = ( $cnt >= $val_cnt );
            } break;
            case $this->NullName:
            {
                $value = is_null( $value );
            } break;
            case $this->NotName:
            {
                if ( is_array( $value ) )
                    $value = ( count( $value ) == 0 );
                else if ( is_null( $value ) )
                    $value = true;
                else if ( is_object( $value ) and
                          method_exists( $value, "attributes" ) )
                    $value = ( count( $value->attributes() ) == 0 );
                else if ( is_numeric( $value ) )
                    $value = ( $value == 0 );
                else if ( is_string( $value ) )
                    $value = ( strlen( $value ) == 0 );
                else
                    $value = !$value;
            } break;
        }
    }

    /// The array of operators
    var $Operators;
    /// The "less than" name
    var $LtName;
    /// The "greater than" name
    var $GtName;
    /// The "less than or equal" name
    var $LeName;
    /// The "greater than or equal" name
    var $GeName;
    /// The "equal" name
    var $EqName;
    /// The "not equal" name
    var $NeName;
    /// The "null" name
    var $NullName;
    /// The "not" name
    var $NotName;
    /// The "or" name
    var $OrName;
    /// The "and" name
    var $AndName;
    /// The "true" name
    var $TrueName;
    /// The "false" name
    var $FalseName;
    /// The "choose" name
    var $ChooseName;
};

?>

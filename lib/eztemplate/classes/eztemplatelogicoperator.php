<?php
//
// Definition of eZTemplateLogicOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
    function operatorList()
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
                                                  'input-as-parameter' => 'always',
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


    function logicalComparisonTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                               $element, $lastElement, $elementList, $elementTree, &$parameters )
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

        if ( $operatorName == 'or' )
        {
            $staticResult = false;
            $staticValue = null;
            $dynamicParameters = array();
            $addDynamic = false;
            $lastValue = null;
            foreach ( $parameters as $parameter )
            {
                if ( $addDynamic )
                {
                    $dynamicParameters[] = $parameter;
                    continue;
                }
                if ( eZTemplateNodeTool::isStaticElement( $parameter ) )
                {
                    $parameterValue = eZTemplateNodeTool::elementStaticValue( $parameter );
                    if ( $staticValue === null )
                    {
                        $staticValue = $parameterValue;
                    }
                    else
                    {
                        $staticValue = ( $staticValue or $parameterValue );
                    }
                    $lastValue = $parameterValue;
                    if ( $parameterValue )
                    {
                        $staticResult = true;
                        break;
                    }
                    continue;
                }
                $addDynamic = true;
                $dynamicParameters[] = $parameter;
                $staticValue = null;
            }
            if ( count( $dynamicParameters ) == 0 )
            {
                if ( !$staticResult )
                    $lastValue = false;
                $newElements[] = eZTemplateNodeTool::createStaticElement( $lastValue );
                return $newElements;
            }

            $code = '';
            $counter = 0;
            foreach ( $dynamicParameters as $parameter )
            {
                if ( $counter++ )
                {
                    $code .= "else ";
                }
                $code .= ( "if ( %$counter% )\n" .
                           "    %output% = %$counter%;\n" );
                $values[] = $parameter;
            }
            $code .= ( "else\n" .
                       "    %output% = false;\n" );
        }
        else if ( $operatorName == 'and' )
        {
            $staticResult = false;
            $staticValue = null;
            $dynamicParameters = array();
            $addDynamic = false;
            $lastValue = null;
            foreach ( $parameters as $parameter )
            {
                if ( $addDynamic )
                {
                    $dynamicParameters[] = $parameter;
                    continue;
                }
                if ( eZTemplateNodeTool::isStaticElement( $parameter ) )
                {
                    $parameterValue = eZTemplateNodeTool::elementStaticValue( $parameter );
                    if ( $staticValue === null )
                    {
                        $staticValue = $parameterValue;
                    }
                    else
                    {
                        $staticValue = ( $staticValue and $parameterValue );
                    }
                    $lastValue = $parameterValue;
                    if ( !$parameterValue )
                    {
                        $lastValue = false;
                        $staticResult = true;
                        break;
                    }
                    continue;
                }
                $addDynamic = true;
                $dynamicParameters[] = $parameter;
                $staticValue = null;
            }
            if ( count( $dynamicParameters ) == 0 )
            {
                $newElements[] = eZTemplateNodeTool::createStaticElement( $lastValue );
                return $newElements;
            }

            $code = '';
            $counter = 0;
            foreach ( $dynamicParameters as $parameter )
            {
                if ( $counter++ )
                {
                    $code .= "else ";
                }
                $code .= ( "if ( !%$counter% )\n" .
                           "    %output% = false;\n" );
                $values[] = $parameter;
            }
            $code .= ( "else\n" .
                       "    %output% = %$counter%;\n" );
        }
        else
        {
            $code = '%output% = (';
            $counter = 0;
            $allStatic = true;
            foreach ( $parameters as $parameter )
            {
                if ( !eZTemplateNodeTool::isStaticElement( $parameter ) )
                    $allStatic = false;
            }
            if ( $allStatic )
            {
                switch ( $operatorName )
                {
                    case 'lt':
                    {
                        $evalStatus = ( eZTemplateNodeTool::elementStaticValue( $parameters[0] ) <
                                        eZTemplateNodeTool::elementStaticValue( $parameters[1] ) );
                    } break;

                    case 'le':
                    {
                        $evalStatus = ( eZTemplateNodeTool::elementStaticValue( $parameters[0] ) <=
                                        eZTemplateNodeTool::elementStaticValue( $parameters[1] ) );
                    } break;

                    case 'gt':
                    {
                        $evalStatus = ( eZTemplateNodeTool::elementStaticValue( $parameters[0] ) >
                                        eZTemplateNodeTool::elementStaticValue( $parameters[1] ) );
                    } break;

                    case 'ge':
                    {
                        $evalStatus = ( eZTemplateNodeTool::elementStaticValue( $parameters[0] ) >=
                                        eZTemplateNodeTool::elementStaticValue( $parameters[1] ) );
                    } break;

                    case 'eq':
                    {
                        $staticParameters = array();
                        foreach ( $parameters as $parameter )
                        {
                            $staticParameters[] = eZPHPCreator::variableText( eZTemplateNodeTool::elementStaticValue( $parameter ),
                                                                              0, 0, false );
                        }
                        eval( '$evalStatus = ( ' . implode( ' == ', $staticParameters ) . ' );' );
                    } break;

                    case 'ne':
                    {
                        $staticParameters = array();
                        foreach ( $parameters as $parameter )
                        {
                            $staticParameters[] = eZPHPCreator::variableText( eZTemplateNodeTool::elementStaticValue( $parameter ),
                                                                              0, 0, false );
                        }
                        eval( '$evalStatus = ( ' . implode( ' != ', $staticParameters ) . ' );' );
                    } break;
                    break;
                }
                $newElements[] = eZTemplateNodeTool::createBooleanElement( $evalStatus );
                return $newElements;
            }

            foreach ( $parameters as $parameter )
            {
                if ( !eZTemplateNodeTool::isStaticElement( $parameter ) )
                    $allStatic = false;
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

    function negateTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                   $element, $lastElement, $elementList, $elementTree, &$parameters )
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

    function trueFalseTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                      $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        if ( ( count( $parameters ) != 0 ) )
        {
            return false;
        }
        $newElements = array();

        $value = false;
        if ( $operatorName == $this->TrueName )
            $value = true;
        $newElements[] = eZTemplateNodeTool::createBooleanElement( $value );
        return $newElements;
    }

    function chooseTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                   $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( ( count( $parameters ) < 2) )
        {
            return false;
        }

        $tmpValues = false;
        $newElements = array();
        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
        {
            $selected = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

            if ( $selected < 0 or $selected > ( count( $parameters ) - 1 ) )
            {
                return false;
            }

            return $parameters[$selected + 1];
        }
        else
        {
            $values[] = $parameters[0];
            $array = $parameters;
            unset( $array[0] );

            $count = count( $parameters ) - 1;
            $operatorNameText = eZPHPCreator::variableText( $operatorName );

            if ( count( $parameters ) == ( 2 + 1 ) )
            {
                $code = "%output% = %1% ? %3% : %2%;\n";
                $values[] = $parameters[1];
                $values[] = $parameters[2];
            }
            else
            {
                $code = ( "if ( %1% < 0 and\n" .
                          "     %1% >= $count )\n" .
                          "{\n" .
                          "    \$tpl->error( $operatorNameText, \"Index \" . %1% . \" out of range\" );\n" .
                          "     %output% = false;\n" .
                          "}\n" );
                $code .= "else switch ( %1% )\n{\n";
                $valueNumber = 2;
                for ( $i = 0; $i < $count; ++$i )
                {
                    $parameterNumber = $i + 1;
                    $code .= "    case $i:";
                    if ( eZTemplateNodeTool::isStaticElement( $parameters[$parameterNumber] ) )
                    {
                        $value = eZTemplateNodeTool::elementStaticValue( $parameters[$parameterNumber] );
                        $valueText = eZPHPCreator::variableText( $value, 0, 0, false );
                        $code .= " %output% = $valueText; break;\n";
                    }
                    else
                    {
                        $code .= "\n    {\n";
                        $code .= "%code$valueNumber%\n";
                        $code .= "%output% = %$valueNumber%;\n";
                        $code .= "    } break;\n";
                        $values[] = $parameters[$parameterNumber];
                        ++$valueNumber;
                    }
                }
                $code .= "}\n";
            }
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values, eZTemplateNodeTool::extractVariableNodePlacement( $node ), false );
        return $newElements;
    }

    /*!
     * Returns the 'count' value as described in the introduction or 'false' in
     * case of an unsupported type
     */
    function getValueCount( $val )
    {
        $val_cnt = false;

        if ( $val === null )
        {
            $val_cnt = 0;
        }
        else if ( $val === true || $val === false )
        {
            $val_cnt = (int)$val;
        }
        else if ( is_array( $val ) )
        {
            $val_cnt = count( $val );
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
            $val_cnt = strlen( $val );
        }
        return $val_cnt;
    }

    /*!
     Examines the input value and outputs a boolean value. See class documentation for more information.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters,
                     $placement )
    {
        if ( $operatorName == $this->LtName or $operatorName == $this->GtName or
             $operatorName == $this->LeName or $operatorName == $this->GeName )
        {
            $val = $namedParameters["threshold"];

            if ( ( $val_cnt = $this->getValueCount( $val ) ) === false )
            {
                $tpl->warning( $operatorName, "Unsupported input type: " . gettype( $val ) . "( $val ), must be either array, attribute object or numerical", $placement );
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
                        $lastOperand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                        $value = ( $lastOperand != $value );
                    }
                    else
                    {
                        $similar = false;
                        $value = false;
                        $lastOperand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                        for ( $i = 1; $i < count( $operatorParameters ); ++$i )
                        {
                            $operand = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
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
                    $tpl->warning( $operatorName, "Requires one parameter for input checking or two or more for parameter checking", $placement );
                }
            } break;
            case $this->EqName:
            {
                if ( count( $operatorParameters ) >= 1 )
                {
                    if ( count( $operatorParameters ) == 1 )
                    {
                        $lastOperand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                        $value = ( $lastOperand == $value );
                    }
                    else
                    {
                        $similar = false;
                        $value = true;
                        $lastOperand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                        for ( $i = 1; $i < count( $operatorParameters ); ++$i )
                        {
                            $operand = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
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
                    $tpl->warning( $operatorName, "Requires one parameter for input checking or two or more for parameter checking", $placement );
                }
            } break;
            case $this->OrName:
            {
                for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                {
                    $operand = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
                    $operand_logic = false;
                    if ( $operand === null )
                        $operand_logic = false;
                    else if ( $operand === true || $operand === false )
                        $operand_logic = $operand;
                    else if ( is_array( $operand ) )
                        $operand_logic = count( $operand ) > 0;
                    else if ( is_numeric( $operand ) )
                        $operand_logic = $operand != 0;
                    else if ( is_object( $operand ) )
                        $operand_logic = ( method_exists( $operand, "attributes" ) and
                                           method_exists( $operand, "attribute" ) );
                    else if ( is_string( $operand ) )
                        $operand_logic =  strlen(trim($operand)) > 0;
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
                    $operand = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
                    $operand_logic = false;
                    if ( $operand === null || $operand === '' )
                        $operand_logic = false;
                    else if ( $operand === true || $operand === false )
                        $operand_logic = $operand;
                    else if ( is_array( $operand ) )
                        $operand_logic = count( $operand ) > 0;
                    else if ( is_numeric( $operand ) )
                        $operand_logic = $operand != 0;
                    else if ( is_object( $operand ) )
                        $operand_logic = ( method_exists( $operand, "attributes" ) and
                                           method_exists( $operand, "attribute" ) );
                    else if ( is_string( $operand ) )
                        $operand_logic =  strlen(trim($operand)) > 0;
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
                if ( $value === null )
                    $index = 0;
            	else if ( is_array( $value ) or
                     ( is_object( $value ) and
                       method_exists( $value, "attributes" ) ) )
                {
                    $tpl->error( $operatorName, "Only supports numeric and boolean values", $placement );
                    return;
                }
                else if ( is_numeric( $value ) )
                    $index = $value;
                else
                    $index = $value ? 1 : 0;
                if ( $index < 0 or
                     $index > count( $operatorParameters ) - 1 )
                {
                    $tpl->error( $operatorName, "Index $index out of range 0 => " . ( count( $operatorParameters ) - 1 ),
                                 $placement );
                    $value = false;
                    return;
                }
                $value = $tpl->elementValue( $operatorParameters[$index], $rootNamespace, $currentNamespace, $placement );
            } break;
            case $this->LtName:
            case $this->GtName:
            case $this->LeName:
            case $this->GeName:
            {
                if ( $value !== null )
                {
                    $operandA = $value;
                    $operandB = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                }
                else
                {
                    $operandA = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                    $operandB = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace, $placement );
                }

                if ( ( $cnt = $this->getValueCount( $operandA ) ) === false )
                {
                    $tpl->warning( $operatorName, "Unsupported input type: " . gettype( $operandA ) . "( $operandA ), must be either array, attribute object or numerical", $placement );
                    return;
                }
                if ( ( $val_cnt = $this->getValueCount( $operandB ) ) === false )
                {
                    $tpl->warning( $operatorName, "Unsupported input type: " . gettype( $operandB ) . "( $operandB ), must be either array, attribute object or numerical", $placement );
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
                $value = $value === null;
            } break;
            case $this->NotName:
            {
                if ( $value === null and isset( $operatorParameters[0] ) )
                {
                    $operand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                }
                else
                {
                    $operand = $value;
                }
                if ( $operand === null )
                    $operand = true;
                else if ( is_array( $operand ) )
                    $operand = ( count( $operand ) == 0 );
                else if ( is_object( $operand ) and
                          method_exists( $operand, "attributes" ) )
                    $operand = ( count( $operand->attributes() ) == 0 );
                else if ( is_numeric( $operand ) )
                    $operand = ( $operand == 0 );
                else if ( is_string( $operand ) )
                    $operand = ( strlen( $operand ) == 0 );
                else
                    $operand = !$operand;
                $value = $operand;
            } break;
        }
    }

    /// \privatesection
    /// The array of operators
    public $Operators;
    /// The "less than" name
    public $LtName;
    /// The "greater than" name
    public $GtName;
    /// The "less than or equal" name
    public $LeName;
    /// The "greater than or equal" name
    public $GeName;
    /// The "equal" name
    public $EqName;
    /// The "not equal" name
    public $NeName;
    /// The "null" name
    public $NullName;
    /// The "not" name
    public $NotName;
    /// The "or" name
    public $OrName;
    /// The "and" name
    public $AndName;
    /// The "true" name
    public $TrueName;
    /// The "false" name
    public $FalseName;
    /// The "choose" name
    public $ChooseName;
};

?>

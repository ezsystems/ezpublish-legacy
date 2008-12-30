<?php
//
// Definition of eZTemplateArithmeticOperator class
//
// Created on: <06-Oct-2002 18:47:48 amos>
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

/*! \file
*/

/*!
  \class eZTemplateArithmeticOperator eztemplatearithmeticoperator.php
  \brief The class eZTemplateArithmeticOperator does

  sum
  sub
  inc
  dec

  div
  mod
  mul

  max
  min

  abs
  ceil
  floor
  round

  int
  float

  count

*/

class eZTemplateArithmeticOperator
{
    /*!
     Constructor
    */
    function eZTemplateArithmeticOperator()
    {
        $this->Operators = array( 'sum', 'sub', 'inc', 'dec',
                                  'div', 'mod', 'mul',
                                  'max', 'min',
                                  'abs', 'ceil', 'floor', 'round',
                                  'int', 'float',
                                  'count',
                                  'roman',
                                  'rand' );
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

    function operatorTemplateHints()
    {
        return array( $this->SumName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'basicTransformation'),
                      $this->SubName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'basicTransformation'),
                      $this->MulName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'basicTransformation'),
                      $this->DivName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'basicTransformation'),

                      $this->IncName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'decIncTransformation'),
                      $this->DecName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'decIncTransformation'),

                      $this->ModName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => 2,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'modTransformation'),

                      $this->MaxName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'minMaxTransformation'),
                      $this->MinName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'minMaxTransformation'),

                      $this->AbsName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'roundTransformation'),
                      $this->CeilName => array( 'input' => true,
                                                'output' => true,
                                                'parameters' => 1,
                                                'element-transformation' => true,
                                                'transform-parameters' => true,
                                                'input-as-parameter' => true,
                                                'element-transformation-func' => 'roundTransformation'),
                      $this->FloorName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => true,
                                                 'element-transformation-func' => 'roundTransformation'),
                      $this->RoundName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => true,
                                                 'element-transformation-func' => 'roundTransformation'),

                      $this->IntName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'castTransformation'),
                      $this->FloatName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => true,
                                                 'element-transformation-func' => 'castTransformation'),

                      $this->RomanName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => true,
                                                 'element-transformation-func' => 'romanTransformation'),

                      $this->CountName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1 ),

                      $this->RandName => array( 'input' => true,
                                                'output' => true,
                                                'parameters' => true,
                                                'element-transformation' => true,
                                                'transform-parameters' => true,
                                                'input-as-parameter' => true,
                                                'element-transformation-func' => 'randTransformation') );
    }

    function basicTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                  $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;
        $divOperation = false;
        if ( $function == $this->SumName )
        {
            $operator = '+';
        }
        else if ( $function == $this->SubName )
        {
            $operator = '-';
        }
        else if ( $function == $this->MulName )
        {
            $operator = '*';
        }
        else
        {
            $divOperation = true;
            $operator = '/';
        }

        if ( count( $parameters ) == 0 )
            return false;
        $newElements = array();

        // Reorder parameters, dynamic elements first then static ones
        // Also combine multiple static ones into a single element
        $notInitialised = true;
        $staticResult = 0;
        $isStaticFirst = false;
        $allNumeric = true;
        $newParameters = array();
        $endParameters = array();
        $parameterIndex = 0;
        foreach ( $parameters as $parameter )
        {
            if ( !eZTemplateNodeTool::isStaticElement( $parameter ) )
            {
                $allNumeric = false;
                $endParameters[] = $parameter;
            }
            else
            {
                $staticValue = eZTemplateNodeTool::elementStaticValue( $parameter );
                if ( $notInitialised )
                {
                    $staticResult = $staticValue;
                    if ( $parameterIndex == 0 )
                        $isStaticFirst = true;
                    $notInitialised = false;
                }
                else
                {
                    if ( $function == 'sum' )
                    {
                        $staticResult += $staticValue;
                    }
                    else if ( $function == 'sub' )
                    {
                        if ( $isStaticFirst )
                            $staticResult -= $staticValue;
                        else
                            $staticResult += $staticValue;
                    }
                    else if ( $function == 'mul' )
                    {
                        $staticResult *= $staticValue;
                    }
                    else
                    {
                        if ( $isStaticFirst )
                            $staticResult /= $staticValue;
                        else
                            $staticResult *= $staticValue;
                    }
                }
                $isPreviousStatic = true;
            }
            ++$parameterIndex;
        }

        if ( $allNumeric )
        {
            $newElements[] = eZTemplateNodeTool::createNumericElement( $staticResult );
            return $newElements;
        }
        else
        {
            if ( !$notInitialised )
            {
                if ( $isStaticFirst )
                    $newParameters[] = array( eZTemplateNodeTool::createNumericElement( $staticResult ) );
                else
                    $endParameters[] = array( eZTemplateNodeTool::createNumericElement( $staticResult ) );
            }
            $newParameters = array_merge( $newParameters, $endParameters );

            $code = '';
            if ( $divOperation )
            {
                $code .= '@';
            }
            $code .= '%output% =';
            $counter = 1;
            $index = 0;

            foreach ( $newParameters as $parameter )
            {
                if ( $index > 0 )
                {
                    $code .= " $operator";
                }
                if ( eZTemplateNodeTool::isStaticElement( $parameter ) )
                {
                    $staticValue = eZTemplateNodeTool::elementStaticValue( $parameter );
                    if ( !is_numeric( $staticValue ) )
                        $staticValue = (int)$staticValue;
                    $code .= sprintf(" %F", $staticValue);
                }
                else
                {
                    $code .= " %$counter%";
                    $values[] = $parameter;
                    ++$counter;
                }
                ++$index;
            }
            $code .= ";\n";
        }
        $knownType = 'integer';
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values, false, false, $knownType );
        return $newElements;
    }

    function minMaxTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                   $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( count( $parameters ) == 0 )
            return false;
        $newElements = array();

        /* Check if all variables are integers. This is for optimization */
        $staticResult = array();
        $allNumeric = true;
        foreach ( $parameters as $parameter )
        {
            if ( !eZTemplateNodeTool::isStaticElement( $parameter ) )
            {
                $allNumeric = false;
            }
            else
            {
                $staticResult[] = eZTemplateNodeTool::elementStaticValue( $parameter );
            }
        }

        if ( $allNumeric )
        {
            $staticResult = $function( $staticResult );
            return array( eZTemplateNodeTool::createNumericElement( $staticResult ) );
        }
        else
        {
            $code = "%output% = $function(";
            $counter = 1;
            foreach ( $parameters as $parameter )
            {
                if ( $counter > 1 )
                {
                    $code .= ', ';
                }
                $code .= " %$counter%";
                $values[] = $parameter;
                ++$counter;
            }
            $code .= ");\n";
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function modTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                  $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        if ( count( $parameters ) != 2 )
            return false;
        $newElements = array();

        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) && eZTemplateNodeTool::isStaticElement( $parameters[1] ) )
        {
            $staticResult = eZTemplateNodeTool::elementStaticValue( $parameters[0] ) % eZTemplateNodeTool::elementStaticValue( $parameters[1] );
            return array( eZTemplateNodeTool::createNumericElement( $staticResult ) );
        }
        else
        {
            $code = "%output% = %1% % %2%;\n";
            $values[] = $parameters[0];
            $values[] = $parameters[1];
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function roundTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                  $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( count( $parameters ) != 1 )
            return false;
        $newElements = array();

        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
        {
            $staticResult = $function( eZTemplateNodeTool::elementStaticValue( $parameters[0] ) );
            return array( eZTemplateNodeTool::createNumericElement( $staticResult ) );
        }
        else
        {
            $code = "%output% = $function( %1% );\n";
            $values[] = $parameters[0];
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function decIncTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                  $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;
        $direction = $this->DecName == $function ? -1 : 1;

        if ( count( $parameters ) < 1 )
            return false;
        $newElements = array();

        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
        {
            return array( eZTemplateNodeTool::createNumericElement( eZTemplateNodeTool::elementStaticValue( $parameters[0] ) + $direction ) );
        }
        else
        {
            $code = "%output% = %1% + $direction;\n";
            $values[] = $parameters[0];
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function castTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                 $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        if ( count( $parameters ) != 1 )
            return false;
        $newElements = array();

        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
        {
            $staticResult = ( $operatorName == $this->IntName ) ? (int) eZTemplateNodeTool::elementStaticValue( $parameters[0] ) : (float) eZTemplateNodeTool::elementStaticValue( $parameters[0] );
            return array( eZTemplateNodeTool::createNumericElement( $staticResult ) );
        }
        else
        {
            $code = "%output% = ($operatorName)%1%;\n";
            $values[] = $parameters[0];
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function randTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                 $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $paramCount = count( $parameters );
        if ( $paramCount != 0 ||
             $paramCount != 2 )
        {
            return false;
        }
        $values = array();
        $newElements = array();

        if ( $paramCount == 2 )
        {
            $code = "%output% = mt_rand( %1%, %2% );\n";
            $values[] = $parameters[0];
            $values[] = $parameters[1];
        }
        else
        {
            $code = "%output% = mt_rand();\n";
        }

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function romanTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                  $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        if ( count( $parameters ) != 1 )
            return false;
        $newElements = array();

        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
        {
            $staticResult = $this->buildRoman( eZTemplateNodeTool::elementStaticValue( $parameters[0] ) );
            return array( eZTemplateNodeTool::createNumericElement( $staticResult ) );
        }
        else
        {
            return false;
        }
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( $this->IncName => array( 'value' => array( 'type' => 'mixed',
                                                                 'required' => false,
                                                                 'default' => false ) ),
                      $this->DecName => array( 'value' => array( 'type' => 'mixed',
                                                                 'required' => false,
                                                                 'default' => false ) ),
                      $this->RomanName => array( 'value' => array( 'type' => 'mixed',
                                                                   'required' => false,
                                                                   'default' => false ) ) );
    }

    /*!
     \private
     \obsolete This function adds too much complexity, don't use it anymore
    */
    function numericalValue( $mixedValue )
    {
        if ( is_array( $mixedValue ) )
        {
            return count( $mixedValue );
        }
        else if ( is_object( $mixedValue ) )
        {
            if ( method_exists( $mixedValue, 'attributes' ) )
                return count( $mixedValue->attributes() );
            else if ( method_exists( $mixedValue, 'numericalValue' ) )
                return $mixedValue->numericalValue();
        }
        else if ( is_numeric( $mixedValue ) )
            return $mixedValue;
        else
            return 0;
    }

    /*!
     Examines the input value and outputs a boolean value. See class documentation for more information.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters,
                     $placement )
    {
        switch ( $operatorName )
        {
            case $this->RomanName:
            {
                if ( $namedParameters['value'] !== false )
                    $value = $namedParameters['value'];
                else
                    $value = $operatorValue;

                $operatorValue = $this->buildRoman( $value );
            } break;
            case $this->CountName:
            {
                if ( count( $operatorParameters ) == 0 )
                    $mixedValue =& $operatorValue;
                else
                    $mixedValue = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                if ( count( $operatorParameters ) > 1 )
                    $tpl->extraParameters( $operatorName, count( $operatorParameters ), 1 );
                if ( is_array( $mixedValue ) )
                    $operatorValue = count( $mixedValue );
                else if ( is_object( $mixedValue ) and
                          method_exists( $mixedValue, 'attributes' ) )
                    $operatorValue = count( $mixedValue->attributes() );
                else if ( is_string( $mixedValue ) )
                    $operatorValue = strlen( $mixedValue );
                else
                    $operatorValue = 0;
            } break;
            case $this->SumName:
            {
                $value = 0;
                if ( $operatorValue !== null )
                    $value = $operatorValue;
                for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
                    $value += $tmpValue;
                }
                $operatorValue = $value;
            } break;
            case $this->SubName:
            {
                $values = array();
                if ( $operatorValue !== null )
                    $values[] = $operatorValue;
                for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                {
                    $values[] = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
                }
                $value = 0;
                if ( count( $values ) > 0 )
                {
                    $value = $values[0];
                    for ( $i = 1; $i < count( $values ); ++$i )
                    {
                        $value -= $values[$i];
                    }
                }
                $operatorValue = $value;
            } break;
            case $this->IncName:
            case $this->DecName:
            {
                if ( $operatorValue !== null )
                    $value = $operatorValue;
                else
                    $value = $namedParameters['value'];
                if ( $operatorName == $this->DecName )
                    --$value;
                else
                    ++$value;
                $operatorValue = $value;
            } break;
            case $this->DivName:
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->warning( $operatorName, 'Requires at least 1 parameter value', $placement );
                    return;
                }
                $i = 0;
                if ( $operatorValue !== null )
                    $value = $operatorValue;
                else
                    $value = $tpl->elementValue( $operatorParameters[$i++], $rootNamespace, $currentNamespace, $placement );
                for ( ; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
                    @$value /= $tmpValue;
                }
                $operatorValue = $value;
            } break;
            case $this->ModName:
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->warning( $operatorName, 'Missing dividend and divisor', $placement );
                    return;
                }
                if ( count( $operatorParameters ) == 1 )
                {
                    $dividend = $operatorValue;
                    $divisor = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                }
                else
                {
                    $dividend = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                    $divisor = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace, $placement );
                }
                $operatorValue = $dividend % $divisor;
            } break;
            case $this->MulName:
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->warning( $operatorName, 'Requires at least 1 parameter value', $placement );
                    return;
                }
                $i = 0;
                if ( $operatorValue !== null )
                    $value = $operatorValue;
                else
                    $value = $tpl->elementValue( $operatorParameters[$i++], $rootNamespace, $currentNamespace, $placement );
                for ( ; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
                    $value *= $tmpValue;
                }
                $operatorValue = $value;
            } break;
            case $this->MaxName:
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->warning( $operatorName, 'Requires at least 1 parameter value', $placement );
                    return;
                }
                $i = 0;
                if ( $operatorValue !== null )
                {
                    $value = $operatorValue;
                }
                else
                {
                    $value = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                    ++$i;
                }
                for ( ; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
                    if ( $tmpValue > $value )
                        $value = $tmpValue;
                }
                $operatorValue = $value;
            } break;
            case $this->MinName:
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->warning( $operatorName, 'Requires at least 1 parameter value', $placement );
                    return;
                }
                $value = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                for ( $i = 1; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
                    if ( $tmpValue < $value )
                        $value = $tmpValue;
                }
                $operatorValue = $value;
            } break;
            case $this->AbsName:
            case $this->CeilName:
            case $this->FloorName:
            case $this->RoundName:
            {
                if ( count( $operatorParameters ) < 1 )
                    $value = $operatorValue;
                else
                    $value = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                switch ( $operatorName )
                {
                    case $this->AbsName:
                    {
                        $operatorValue = abs( $value );
                    } break;
                    case $this->CeilName:
                    {
                        $operatorValue = ceil( $value );
                    } break;
                    case $this->FloorName:
                    {
                        $operatorValue = floor( $value );
                    } break;
                    case $this->RoundName:
                    {
                        $operatorValue = round( $value );
                    } break;
                }
            } break;
            case $this->IntName:
            {
                if ( count( $operatorParameters ) > 0 )
                    $value = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                else
                    $value = $operatorValue;
                $operatorValue = (int)$value;
            } break;
            case $this->FloatName:
            {
                if ( count( $operatorParameters ) > 0 )
                    $value = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                else
                    $value = $operatorValue;
                $operatorValue = (float)$value;
            } break;
            case $this->RandName:
            {
                if ( count( $operatorParameters ) == 2 )
                {
                    $operatorValue = mt_rand( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement ),
                                              $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace, $placement ) );
                }
                else
                {
                    $operatorValue = mt_rand();
                }
            } break;
        }
    }

    /// \privatesection

    /*!
     \private

     Recursive function for calculating roman numeral from integer

     \param integer value
     \return next chars for for current value
    */
    function buildRoman( $value )
    {
        if ( $value >= 1000 )
            return 'M'.$this->buildRoman( $value - 1000 );
        if ( $value >= 500 )
        {
            if ( $value >= 900 )
                return 'CM'.$this->buildRoman( $value - 900 );
            else
                return 'D'.$this->buildRoman( $value - 500 );
        }
        if ( $value >= 100 )
        {
            if( $value >= 400 )
                return 'CD'.$this->buildRoman( $value - 400 );
            else
                return 'C'.$this->buildRoman( $value - 100 );
        }
        if ( $value >= 50 )
        {
            if( $value >= 90 )
                return 'XC'.$this->buildRoman( $value - 90 );
            else
                return 'L'.$this->buildRoman( $value - 50 );
        }
        if ( $value >= 10 )
        {
            if( $value >= 40 )
                return 'XL'.$this->buildRoman( $value - 40 );
            else
                return 'X'.$this->buildRoman( $value - 10 );
        }
        if ( $value >= 5 )
        {
            if( $value == 9 )
                return 'IX'.$this->buildRoman( $value - 9 );
            else
                return 'V'.$this->buildRoman( $value - 5 );
        }
        if ( $value >= 1 )
        {
            if( $value == 4 )
                return 'IV'.$this->buildRoman( $value - 4 );
            else
                return 'I'.$this->buildRoman( $value - 1 );
        }
        return '';
    }

    public $Operators;
    public $SumName;
    public $SubName;
    public $IncName;
    public $DecName;

    public $DivName;
    public $ModName;
    public $MulName;

    public $MaxName;
    public $MinName;

    public $AbsName;
    public $CeilName;
    public $FloorName;
    public $RoundName;

    public $IntName;
    public $FloatName;

    public $CountName;

    public $RomanName;

    public $RandName;
}

?>

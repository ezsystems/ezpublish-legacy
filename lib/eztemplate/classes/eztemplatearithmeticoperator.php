<?php
//
// Definition of eZTemplateArithmeticOperator class
//
// Created on: <06-Oct-2002 18:47:48 amos>
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

/*! \file eztemplatearithmeticoperator.php
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
                                  'roman' );
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

    function operatorTemplateHints()
    {
        return array( $this->SumName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'basicTransformation'),
                      $this->SubName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'basicTransformation'),
                      $this->MulName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'basicTransformation'),
                      $this->DivName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'basicTransformation'),

                      $this->IncName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'decIncTransformation'),
                      $this->DecName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'decIncTransformation'),

                      $this->ModName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'modTransformation'),

                      $this->MaxName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'minMaxTransformation'),
                      $this->MinName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'minMaxTransformation'),

                      $this->AbsName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'roundTransformation'),
                      $this->CeilName => array( 'input' => true,
                                                'output' => true,
                                                'parameters' => 1,
                                                'element-transformation' => true,
                                                'transform-parameters' => true,
                                                'element-transformation-func' => 'roundTransformation'),
                      $this->FloorName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'element-transformation-func' => 'roundTransformation'),
                      $this->RoundName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'element-transformation-func' => 'roundTransformation'),

                      $this->IntName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'element-transformation-func' => 'castTransformation'),
                      $this->FloatName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'element-transformation-func' => 'castTransformation'),

                      $this->RomanName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'element-transformation-func' => 'romanTransformation'),

                      $this->CountName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => 1 ) );
    }

    function basicTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                  &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;
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
            $operator = '/';
        }

        if ( count( $parameters ) == 0 )
            return false;
        $newElements = array();

        /* Check if all variables are integers. This is for optimization */
        $staticResult = 0;
        $allNumeric = true;
        $notInitialised = true;
        foreach ( $parameters as $parameter )
        {
            if ( $parameter[0][0] != EZ_TEMPLATE_TYPE_NUMERIC )
            {
                $allNumeric = false;
            }
            else
            {
                if ( $notInitialised )
                {
                    $staticResult = $parameter[0][1];
                    $notInitialised = false;
                }
                else
                {
                    if ( $function == 'sum' )
                    {
                        $staticResult += $parameter[0][1];
                    }
                    else if ( $function == 'sub' )
                    {
                        $staticResult -= $parameter[0][1];
                    }
                    else if ( $function == 'mul' )
                    {
                        $staticResult *= $parameter[0][1];
                    }
                    else
                    {
                        $staticResult /= $parameter[0][1];
                    }
                }
            }
        }
 
        if ( $allNumeric )
        {
            $code = "%output% = $staticResult;\n";
        }
        else
        {
            $code = '%output% =';
            $counter = 1;
            foreach ( $parameters as $parameter )
            {
                if ( $counter > 1 )
                {
                    $code .= " $operator";
                }
                $code .= " %$counter%";
                $values[] = $parameter;
                ++$counter;
            }
            $code .= ";\n";
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function minMaxTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                   &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
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
            if ( $parameter[0][0] != EZ_TEMPLATE_TYPE_NUMERIC )
            {
                $allNumeric = false;
            }
            else
            {
                $staticResult[] = $parameter[0][1];
            }
        }
 
        if ( $allNumeric )
        {
            $staticResult = $function( $staticResult );
            $code = "%output% = $staticResult;\n";
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

    function modTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                  &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        if ( count( $parameters ) != 2 )
            return false;
        $newElements = array();

        if ( $parameters[0][0][0] == EZ_TEMPLATE_TYPE_NUMERIC && $parameters[1][0][0] == EZ_TEMPLATE_TYPE_NUMERIC )
        {
            $staticResult = $parameters[0][0][1] & $parameters[1][0][1];
            $code = "%output% = $staticResult;\n";
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

    function roundTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                  &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( count( $parameters ) != 1 )
            return false;
        $newElements = array();

        if ( $parameters[0][0][0] == EZ_TEMPLATE_TYPE_NUMERIC )
        {
            $staticResult = $function( $parameters[0][0][1] );
            $code = "%output% = $staticResult;\n";
        }
        else
        {
            $code = "%output% = $function( %1% );\n";
            $values[] = $parameters[0];
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function decIncTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                  &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;
        $direction = $this->DecName == $function ? -1 : 1;

        if ( count( $parameters ) != 1 )
            return false;
        $newElements = array();

        if ( $parameters[0][0][0] == EZ_TEMPLATE_TYPE_NUMERIC )
        {
            $staticResult = $parameters[0][0][1] + $direction;
            $code = "%output% = $staticResult;\n";
        }
        else
        {
            $code = "%output% = %1% + $direction;\n";
            $values[] = $parameters[0];
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function castTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                 &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        if ( count( $parameters ) != 1 )
            return false;
        $newElements = array();

        if ( $parameters[0][0][0] == EZ_TEMPLATE_TYPE_NUMERIC )
        {
            $staticResult = ( $operatorName == $this->IntName ) ? (int) $parameters[0][0][1] : (float) $parameters[0][0][1];
            $code = "%output% = $staticResult;\n";
        }
        else
        {
            $code = "%output% = ($operatorName)%1%;\n";
            $values[] = $parameters[0];
        }
        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    function romanTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                  &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        if ( count( $parameters ) != 1 )
            return false;
        $newElements = array();

        if ( $parameters[0][0][0] == EZ_TEMPLATE_TYPE_NUMERIC )
        {
            $staticResult = $this->buildRoman( $parameters[0][0][1] );
            $code = "%output% = '$staticResult';\n";
            $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
            return $newElements;
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
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case $this->RomanName:
            {
                if ( $namedParameters['value'] !== false )
                    $value = $this->numericalValue( $namedParameters['value'] );
                else
                    $value = $this->numericalValue( $operatorValue );

                $operatorValue = $this->buildRoman( $value );
            } break;
            case $this->CountName;
            {
                if ( count( $operatorParameters ) == 0 )
                    $mixedValue =& $operatorValue;
                else
                    $mixedValue =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
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
                if ( is_array( $operatorValue ) )
                {
                    $array = $operatorValue;
                    $value = array_sum( $array );
                }
                for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue =& $this->numericalValue( $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace ) );
                    $value += $tmpValue;
                }
                $operatorValue = $value;
            } break;
            case $this->SubName:
            {
                $values = array();
                if ( is_array( $operatorValue ) )
                    $values = array_values( $operatorValue );
                for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                {
                    $values[] = $this->numericalValue( $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace ) );
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
                if ( $namedParameters['value'] === false )
                    $value = $this->numericalValue( $operatorValue );
                else
                    $value = $this->numericalValue( $namedParameters['value'] );
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
                    $tpl->warning( $operatorName, 'Requires at least 1 parameter value' );
                    return;
                }
                $i = 0;
                if ( count( $operatorParameters ) == 1 )
                    $value = $operatorValue;
                else
                    $value = $this->numericalValue( $tpl->elementValue( $operatorParameters[$i++], $rootNamespace, $currentNamespace ) );
                for ( ; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue =& $this->numericalValue( $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace ) );
                    $value /= $tmpValue;
                }
                $operatorValue = $value;
            } break;
            case $this->ModName:
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->warning( $operatorName, 'Missing dividend and divisor' );
                    return;
                }
                if ( count( $operatorParameters ) == 1 )
                {
                    $dividend = $operatorValue;
                    $divisor = $this->numericalValue( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace ) );
                }
                else
                {
                    $dividend = $this->numericalValue( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace ) );
                    $divisor = $this->numericalValue( $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace ) );
                }
                $operatorValue = $dividend % $divisor;
            } break;
            case $this->MulName:
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->warning( $operatorName, 'Requires at least 1 parameter value' );
                    return;
                }
                $value = $this->numericalValue( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace ) );
                for ( $i = 1; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue =& $this->numericalValue( $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace ) );
                    $value *= $tmpValue;
                }
                $operatorValue = $value;
            } break;
            case $this->MaxName:
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->warning( $operatorName, 'Requires at least 1 parameter value' );
                    return;
                }
                $value = $this->numericalValue( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace ) );
                for ( $i = 1; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue =& $this->numericalValue( $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace ) );
                    if ( $tmpValue > $value )
                        $value = $tmpValue;
                }
                $operatorValue = $value;
            } break;
            case $this->MinName:
            {
                if ( count( $operatorParameters ) < 1 )
                {
                    $tpl->warning( $operatorName, 'Requires at least 1 parameter value' );
                    return;
                }
                $value = $this->numericalValue( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace ) );
                for ( $i = 1; $i < count( $operatorParameters ); ++$i )
                {
                    $tmpValue =& $this->numericalValue( $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace ) );
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
                    $value = $this->numericalValue( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace ) );
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
                    $value = $this->numericalValue( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace ) );
                else
                    $value = $operatorValue;
                $operatorValue = (int)$value;
            } break;
            case $this->FloatName:
            {
                if ( count( $operatorParameters ) > 0 )
                    $value = $this->numericalValue( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace ) );
                else
                    $value = $operatorValue;
                $operatorValue = (float)$value;
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
        if ( $value > 1000 )
            return 'M'.$this->buildRoman( $value - 1000 );
        if ( $value > 500 )
        {
            if ( $value >= 900 )
                return 'CM'.$this->buildRoman( $value - 900 );
            else
                return 'D'.$this->buildRoman( $value - 500 );
        }
        if ( $value > 100 )
        {
            if( $value >= 400 )
                return 'CD'.$this->buildRoman( $value - 400 );
            else
                return 'C'.$this->buildRoman( $value - 100 );
        }
        if ( $value > 50 )
        {
            if( $value >= 90 )
                return 'XC'.$this->buildRoman( $value - 90 );
            else
                return 'L'.$this->buildRoman( $value - 50 );
        }
        if ( $value > 10 )
        {
            if( $value >= 40 )
                return 'XL'.$this->buildRoman( $value - 40 );
            else
                return 'X'.$this->buildRoman( $value - 10 );
        }
        if ( $value > 5 )
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

    var $Operators;
    var $SumName;
    var $SubName;
    var $IncName;
    var $DecName;

    var $DivName;
    var $ModName;
    var $MulName;

    var $MaxName;
    var $MinName;

    var $AbsName;
    var $CeilName;
    var $FloorName;
    var $RoundName;

    var $IntName;
    var $FloatName;

    var $CountName;

    var $RomanName;
}

?>

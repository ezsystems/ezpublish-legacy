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
    function eZTemplateArithmeticOperator( $sumName = 'sum', $subName = 'sub', $incName = 'inc', $decName = 'dec',
                                           $divName = 'div', $modName = 'mod', $mulName = 'mul',
                                           $maxName = 'max', $minName = 'min',
                                           $absName = 'abs', $ceilName = 'ceil', $floorName = 'floor', $roundName = 'round',
                                           $intName = 'int', $floatName = 'float',
                                           $countName = 'count' )
    {
        $this->SumName = $sumName;
        $this->SubName = $subName;
        $this->IncName = $incName;
        $this->DecName = $decName;

        $this->DivName = $divName;
        $this->ModName = $modName;
        $this->MulName = $mulName;

        $this->MaxName = $maxName;
        $this->MinName = $minName;

        $this->AbsName = $absName;
        $this->CeilName = $ceilName;
        $this->FloorName = $floorName;
        $this->RoundName = $roundName;

        $this->IntName = $intName;
        $this->FloatName = $floatName;

        $this->CountName = $countName;

        $this->Operators = array( $sumName, $subName, $incName, $decName,
                                  $divName, $modName, $mulName,
                                  $maxName, $minName,
                                  $absName, $ceilName, $floorName, $roundName,
                                  $intName, $floatName,
                                  $countName );
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

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( $this->IncName => array( "value" => array( "type" => "mixed",
                                                                 "required" => false,
                                                                 "default" => false ) ),
                      $this->DecName => array( "value" => array( "type" => "mixed",
                                                                 "required" => false,
                                                                 "default" => false ) ) );
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
                    $tpl->warning( $operatorName, "Requires at least 1 parameter value" );
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
                    $tpl->warning( $operatorName, "Missing dividend and divisor" );
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
                    $tpl->warning( $operatorName, "Requires at least 1 parameter value" );
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
                    $tpl->warning( $operatorName, "Requires at least 1 parameter value" );
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
                    $tpl->warning( $operatorName, "Requires at least 1 parameter value" );
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
}

?>

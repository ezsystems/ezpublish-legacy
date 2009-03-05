<?php
//
// Definition of eZTemplateTypeOperator class
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
  \class eZTemplateTypeOperator eztemplatetypeoperator.php
  \ingroup eZTemplateOperators
  \brief Operators for checking variable type

Usage:
$var|is_array or is_array( $var )
$var|is_boolean or is_boolean( $var )
$var|is_integer or is_integer( $var )
$var|is_float or is_float( $var )
$var|is_numeric or is_numeric( $var )
$var|is_string or is_string( $var )
$var|is_object or is_object( $var )
$var|is_class('my_class') or is_class( 'my_class', $var )
$var|is_null or is_null( $var )
is_set( $var )
is_unset( $var )
$var|get_type or get_type( $var )
$var|get_class or get_class( $var )


*/

class eZTemplateTypeOperator
{
    /*!
     Initializes the operator class with the various operator names.
    */
    function eZTemplateTypeOperator(  /*! The name array */
                                      $isArrayName = "is_array",
                                      $isBooleanName = "is_boolean",
                                      $isIntegerName = "is_integer",
                                      $isFloatName = "is_float",
                                      $isNumericName = "is_numeric",
                                      $isStringName = "is_string",
                                      $isObjectName = "is_object",
                                      $isClassName = "is_class",
                                      $isNullName = "is_null",
                                      $isSetName = "is_set",
                                      $isUnsetName = "is_unset",
                                      $getTypeName = "get_type",
                                      $getClassName = "get_class" )
    {
        $this->Operators = array( $isArrayName,
                                  $isBooleanName,
                                  $isIntegerName,
                                  $isFloatName,
                                  $isNumericName,
                                  $isStringName,
                                  $isObjectName,
                                  $isClassName,
                                  $isNullName,
                                  $isSetName,
                                  $isUnsetName,
                                  $getTypeName,
                                  $getClassName );
        $this->IsArrayName = $isArrayName;
        $this->IsBooleanName = $isBooleanName;
        $this->IsIntegerName = $isIntegerName;
        $this->IsFloatName = $isFloatName;
        $this->IsNumericName = $isNumericName;
        $this->IsStringName = $isStringName;
        $this->IsObjectName = $isObjectName;
        $this->IsClassName = $isClassName;
        $this->IsNullName = $isNullName;
        $this->IsSetName = $isSetName;
        $this->IsUnsetName = $isUnsetName;
        $this->GetTypeName = $getTypeName;
        $this->GetClassName = $getClassName;
        $this->PHPNameMap = array( $isArrayName => 'is_array',
                                   $isBooleanName => 'is_bool',
                                   $isIntegerName => 'is_integer',
                                   $isFloatName => 'is_float',
                                   $isNumericName => 'is_numeric',
                                   $isStringName => 'is_string',
                                   $isObjectName => 'is_object',
                                   $isNullName => 'is_null' );
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
        return array( $this->IsArrayName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => true,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => true,
                                                   'element-transformation-func' => 'isTransform'),
                      $this->IsBooleanName => array( 'input' => true,
                                                     'output' => true,
                                                     'parameters' => true,
                                                     'element-transformation' => true,
                                                     'transform-parameters' => true,
                                                     'input-as-parameter' => true,
                                                     'element-transformation-func' => 'isTransform' ),
                      $this->IsIntegerName => array( 'input' => true,
                                                     'output' => true,
                                                     'parameters' => true,
                                                     'element-transformation' => true,
                                                     'transform-parameters' => true,
                                                     'input-as-parameter' => true,
                                                     'element-transformation-func' => 'isTransform' ),
                      $this->IsFloatName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => true,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => true,
                                                   'element-transformation-func' => 'isTransform' ),
                      $this->IsNumericName => array( 'input' => true,
                                                     'output' => true,
                                                     'parameters' => true,
                                                     'element-transformation' => true,
                                                     'transform-parameters' => true,
                                                     'input-as-parameter' => true,
                                                     'element-transformation-func' => 'isTransform' ),
                      $this->IsStringName => array( 'input' => true,
                                                    'output' => true,
                                                    'parameters' => true,
                                                    'element-transformation' => true,
                                                    'transform-parameters' => true,
                                                    'input-as-parameter' => true,
                                                    'element-transformation-func' => 'isTransform' ),
                      $this->IsObjectName => array( 'input' => true,
                                                    'output' => true,
                                                    'parameters' => true,
                                                    'element-transformation' => true,
                                                    'transform-parameters' => true,
                                                    'input-as-parameter' => true,
                                                    'element-transformation-func' => 'isTransform' ),
                      $this->IsClassName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => true,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => true,
                                                   'element-transformation-func' => 'isTransform' ),
                      $this->IsNullName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => true,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => true,
                                                  'element-transformation-func' => 'isTransform' ),
                      $this->IsSetName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => true,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => true,
                                                 'element-transformation-func' => 'isTransform' ),
                      $this->IsUnsetName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => true,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => true,
                                                   'element-transformation-func' => 'isTransform' ),
                      $this->GetTypeName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => true,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => true,
                                                   'element-transformation-func' => 'isTransform' ),
                      $this->GetClassName => array( 'input' => true,
                                                    'output' => true,
                                                    'parameters' => true,
                                                    'element-transformation' => true,
                                                    'transform-parameters' => true,
                                                    'input-as-parameter' => true,
                                                    'element-transformation-func' => 'isTransform' ) );
    }

    function isTransform( $operatorName, &$node, $tpl, &$resourceData,
                          $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $values[] = $parameters[0];
        $code = '%output% = ';

        switch( $operatorName )
        {
            case $this->IsArrayName:
            {
                $code .= 'is_array( %1% );';
            } break;

            case $this->IsBooleanName:
            {
                $code .= 'is_bool( %1% );';
            } break;

            case $this->IsIntegerName:
            {
                $code .= 'is_int( %1% );';
            } break;

            case $this->IsFloatName:
            {
                $code .= 'is_float( %1% );';
            } break;

            case $this->IsNumericName:
            {
                $code .= 'is_numeric( %1% );';
            } break;

            case $this->IsStringName:
            {
                $code .= 'is_string( %1% );';
            } break;

            case $this->IsObjectName:
            {
                $code .= 'is_object( %1% );';
            } break;

            case $this->IsClassName:
            {
                $code .= '( strtolower( get_class( %1% ) ) == strtolower( %2% ) );';
                $values[] = $parameters[1];
            } break;

            case $this->IsNullName:
            {
                $code .= 'is_null( %1% );';
            } break;

            case $this->IsSetName:
            {
                $code .= 'isset( %1% );';
            } break;

            case $this->IsUnsetName:
            {
                $code .= '!isset( %1% );';
            } break;

            case $this->GetTypeName:
            {
                return false;
            } break;

            case $this->GetClassName:
            {
                $code .= 'strtolower( get_class( %1% ) );';
            } break;
        }

        return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
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
        return array();
    }

    /*!
     Examines the input value and outputs a boolean value. See class documentation for more information.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters, $placement )
    {
        if ( isset( $this->PHPNameMap[$operatorName] ) )
        {
            $typeFunction = $this->PHPNameMap[$operatorName];
            $this->checkType( $typeFunction, $tpl, $value, $operatorParameters, $rootNamespace, $currentNamespace, $placement );
            return;
        }
        switch ( $operatorName )
        {
            case $this->IsClassName:
            {
                if ( count( $operatorParameters ) == 1 )
                {
                    $className = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                    $value = strtolower( get_class( $value ) ) == strtolower( $className );
                }
                else
                {
                    $className = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                    $value = strtolower( get_class( $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace, $placement ) ) ) == strtolower( $className );
                }
            } break;
            case $this->IsSetName:
            {
                if ( count( $operatorParameters ) > 0 )
                {
                    if ( count( $operatorParameters ) > 1 )
                    {
                        $tpl->extraParameters( $operatorName, count( $operatorParameters ), 1 );
                    }

                    $operand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement, true );
                    $value = $operand !== null;


                }
                else
                    $tpl->missingParameter( $operatorName, 'input' );

            } break;
            case $this->IsUnsetName:
            {
                if ( count( $operatorParameters ) > 0 )
                {
                    if ( count( $operatorParameters ) > 1 )
                        $tpl->extraParameters( $operatorName,
                                               count( $operatorParameters ),
                                               1 );
                    $operand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement, true );
                    $value = $operand === null;
                }
                else
                    $tpl->missingParameter( $operatorName, 'input' );
            } break;
            case $this->GetTypeName:
            {
                if ( count( $operatorParameters ) > 0 )
                {
                    if ( count( $operatorParameters ) > 1 )
                        $tpl->extraParameters( $operatorName,
                                               count( $operatorParameters ),
                                               1 );
                    $operand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                }
                else
                    $operand =& $value;
                if ( $operand === null )
                    $value = 'null';
                else if ( is_bool( $operand ) )
                    $value = 'boolean[' . ( $operand ? 'true' : 'false' ) . ']';
                else if ( is_object( $operand ) )
                    $value = 'object[' . get_class( $operand ) . ']';
                else if ( is_array( $operand ) )
                    $value = 'array[' . count( $operand ) . ']';
                else if ( is_string( $operand ) )
                    $value = 'string[' . strlen( $operand ) . ']';
                else
                    $value = gettype( $operand );
            } break;
            case $this->GetClassName:
            {
                if ( count( $operatorParameters ) > 0 )
                {
                    if ( count( $operatorParameters ) > 1 )
                        $tpl->extraParameters( $operatorName,
                                               count( $operatorParameters ),
                                               1 );
                    $operand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                    $value = strtolower( get_class( $operand ) );
                }
                else
                {
                    $value = strtolower( get_class( $value ) );
                }
            } break;
        }
    }

    function checkType( $typeFunction, $tpl, &$value, $operatorParameters, $rootNamespace, $currentNamespace, $placement )
    {
        if ( count( $operatorParameters ) > 0 )
        {
            $value = true;
            for ( $i = 0; $i < count( $operatorParameters ); ++$i )
            {
                $operand = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement );
                if ( !$typeFunction( $operand) )
                    $value = false;
            }
        }
        else
        {
            $value = $typeFunction( $value );
        }
    }

    /// The array of operators
    public $Operators;
    /// The "less than" name
    public $IsArrayName;
};

?>

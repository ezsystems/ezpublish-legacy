<?php
//
// Definition of eZTemplateTypeOperator class
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
        return array();
    }

    /*!
     Examines the input value and outputs a boolean value. See class documentation for more information.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$value, &$namedParameters )
    {
        if ( isset( $this->PHPNameMap[$operatorName] ) )
        {
            $typeFunction = $this->PHPNameMap[$operatorName];
            $this->checkType( $typeFunction, $tpl, $value, $operatorParameters, $rootNamespace, $currentNamespace );
            return;
        }
        switch ( $operatorName )
        {
            case $this->IsClassName:
            {
                if ( count( $operatorParameters ) == 1 )
                {
                    $className =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                    $value = get_class( $value ) == strtolower( $className );
                }
                else
                {
                    $className =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                    $value = get_class( $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace ) ) == strtolower( $className );
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

                    $operand =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, false, true );
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
                    $operand =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, false, true );
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
                    $operand =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                }
                else
                    $operand =& $value;
                if ( is_object( $operand ) )
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
                    $operand =& $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                    $value = get_class( $operand );
                }
                else
                {
                    $value = get_class( $value );
                }
            } break;
        }
    }

    function checkType( $typeFunction, &$tpl, &$value, &$operatorParameters, &$rootNamespace, &$currentNamespace )
    {
        if ( count( $operatorParameters ) > 0 )
        {
            $value = true;
            for ( $i = 0; $i < count( $operatorParameters ); ++$i )
            {
                $operand =& $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace );
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
    var $Operators;
    /// The "less than" name
    var $IsArrayName;
};

?>

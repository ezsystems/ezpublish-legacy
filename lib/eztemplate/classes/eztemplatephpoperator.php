<?php
//
// Definition of eZTemplatePHPOperator class
//
// Created on: <01-Mar-2002 13:50:09 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  \class eZTemplatePHPOperator eztemplatephpoperator.php
  \ingroup eZTemplateOperators
  \brief Makes it easy to add php functions as operators

  This class makes it easy to add existing PHP functions as template operators.
  It maps a template operator to a PHP function, the function must take one
  parameter and return the result.
  The redirection is done by supplying an associative array to the class,
  each key is the operatorname and the value is the PHP function name.

  Example:
\code
$tpl->registerOperators( new eZTemplatePHPOperator( array( "upcase" => "strtoupper",
                                                           "reverse" => "strrev" ) ) );
\endcode
*/

class eZTemplatePHPOperator
{
    /*!
     Initializes the object with the redirection array.
    */
    function eZTemplatePHPOperator( $php_names )
    {
        if ( !is_array( $php_names ) )
            $php_names = array( $php_names );
        $this->PHPNames = $php_names;
        reset( $php_names );
        while ( list( $key, $val ) = each( $php_names ) )
        {
            $this->Operators[] = $key;
        }
    }

    /*!
     Returns the template operators.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    function operatorTemplateHints()
    {
        $hints = array();
        foreach ( array_keys( $this->PHPNames ) as $name )
        {
            $hints[$name] = array( 'input' => true,
                                   'output' => true,
                                   'parameters' => false,
                                   'element-transformation' => true,
                                   'transform-parameters' => true,
                                   'input-as-parameter' => 'always',
                                   'element-transformation-func' => 'phpOperatorTransformation');
        }
        return $hints;
    }

    function phpOperatorTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                        $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( ( count( $parameters ) != 1) )
        {
            return false;
        }
        $newElements = array();
        $phpname = $this->PHPNames[$operatorName];

        $values[] = $parameters[0];
        $code = "%output% = $phpname( %1% );\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    /*!
     Executes the PHP function for the operator $op_name.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters, $placement )
    {
        $phpname = $this->PHPNames[$operatorName];
        if ( $value !== null )
            $operand = $value;
        else
            $operand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
        $value = $phpname( $operand );
    }

    /// The array of operators, used for registering operators
    public $Operators;
    /// The associative array of operator/php function redirection
    public $PHPNames;
}

?>

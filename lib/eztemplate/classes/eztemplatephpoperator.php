<?php
//
// Definition of eZTemplatePHPOperator class
//
// Created on: <01-Mar-2002 13:50:09 amos>
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
    function &operatorList()
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

    function phpOperatorTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                        &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
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
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$value, $namedParameters, $placement )
    {
        $phpname = $this->PHPNames[$operatorName];
        if ( $value !== null )
            $operand = $value;
        else
            $operand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
        $value = $phpname( $operand );
    }

    /// The array of operators, used for registering operators
    var $Operators;
    /// The associative array of operator/php function redirection
    var $PHPNames;
}

?>

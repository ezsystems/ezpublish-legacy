<?php
//
// Definition of eZTemplateNl2BrOperator class
//
// Created on: <10-Mar-2003 11:22:29 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
  \class eZTemplateNl2BrOperator eztemplatenl2broperator.php
  \ingroup eZTemplateOperators
\code

\endcode

*/

class eZTemplateNl2BrOperator
{
    /*!
     Initializes the object with the name $name, default is "nl2br".
    */
    function eZTemplateNl2BrOperator()
    {
        $this->Operators = array( 'nl2br' );
        $this->Nl2brName = 'nl2br';
    }

    /*!
     Returns the template operators.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( );
    }

    function operatorTemplateHints()
    {
        return array( $this->Nl2brName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => true,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => true,
                                                 'element-transformation-func' => 'nl2brTransformation') );
    }

    function nl2brTransformation( $operatorName, &$node, $tpl, &$resourceData,
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
        $code = "%output% = nl2br( %1% );\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    /*!
     Display the variable.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $operatorValue = str_replace( "\n",
                                      "<br />",
                                      $operatorValue );
    }

    /// The array of operators, used for registering operators
    public $Operators;
}

?>

<?php
//
// Definition of eZTemplateNl2BrOperator class
//
// Created on: <10-Mar-2003 11:22:29 bf>
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
    function &operatorList()
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

    function nl2brTransformation( $operatorName, &$node, &$tpl, &$resourceData,
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
        $code = "%output% = nl2br( %1% );\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    /*!
     Display the variable.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $operatorValue = str_replace( "\n",
                                      "<br />",
                                      $operatorValue );
    }

    /// The array of operators, used for registering operators
    var $Operators;
}

?>

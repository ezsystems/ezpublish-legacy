<?php
//
// Definition of eZTemplateWashOperator class
//
// Created on: <17-Dec-2002 13:20:18 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
  \class eZTemplateWashOperator eztemplatewashoperator.php
  \ingroup eZTemplateOperators
\code

\endcode

*/

class eZTemplateWashOperator
{
    /*!
     Initializes the object with the name $name, default is "wash".
    */
    function eZTemplateWashOperator( $name = "wash" )
    {
        $this->Operators = array( $name );
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
        return array( "type" => array( "type" => "string",
                                       "required" => false,
                                       "default" => "xhtml" ) );
    }

    /*!
     Display the variable.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $type = $namedParameters["type"];
        switch ( $type )
        {
            case "xhtml":
            {
                $operatorValue = htmlspecialchars( $operatorValue );
            }break;
        }
    }

    /// The array of operators, used for registering operators
    var $Operators;
}

?>

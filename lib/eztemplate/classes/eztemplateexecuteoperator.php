<?php
//
// Definition of eZTemplateExecuteOperator class
//
// Created on: <06-Oct-2002 17:53:19 amos>
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

/*! \file eztemplateexecuteoperator.php
*/

/*!
  \class eZTemplateExecuteOperator eztemplateexecuteoperator.php
  \brief The class eZTemplateExecuteOperator does

*/

include_once( "lib/eztemplate/classes/eztemplate.php" );

class eZTemplateExecuteOperator
{
    /*!
     Constructor
    */
    function eZTemplateExecuteOperator( $fetchName = "fetch" )
    {
        $this->Operators = array( $fetchName );
    }

    /*!
     Returns the operators in this class.
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
        return array( "module_name" => array( "type" => "string",
                                              "required" => true,
                                              "default" => false ),
                      "function_name" => array( "type" => "string",
                                                "required" => true,
                                                "default" => false ),
                      "function_parameters" => array( "type" => "array",
                                                      "required" => false,
                                                      "default" => array() ) );
    }

    /*!
     Calls a specified module function and returns the result.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters,
                     &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $moduleName = $namedParameters['module_name'];
        $functionName = $namedParameters['function_name'];
        $functionParameters = $namedParameters['function_parameters'];

        include_once( 'lib/ezutils/classes/ezfunctionhandler.php' );
        $result =& eZFunctionHandler::execute( $moduleName, $functionName, $functionParameters );

        $operatorValue = $result;
    }

    /// \privatesection
    var $Operators;
}

?>

<?php
//
// Definition of eZTemplateTypeOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
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
  \class eZTemplateTypeOperator eztemplatetypeoperator.php
  \ingroup eZTemplateOperators
  \brief Operators for checking variable type


*/

class eZTemplateTypeOperator
{
    /*!
     Initializes the operator class with the various operator names.
    */
    function eZTemplateTypeOperator(  /*! The name array */
                                      $is_array_name = "is_array" )
    {
        $this->Operators = array( $is_array_name );
        $this->IsArrayName = $is_array_name;
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
        switch ( $operatorName )
        {
            case $this->IsArrayName:
            case $this->NEName:
            {
                if ( count( $operatorParameters ) > 0 )
                {
                    $value = true;
                    for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                    {
                        $operand =& $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace );
                        if ( !is_array( $operand) )
                            $value = false;
                    }
                }
                else
                {
                    $value = is_array( $value );
                }
            } break;
        }
    }

    /// The array of operators
    var $Operators;
    /// The "less than" name
    var $IsArrayName;
};

?>

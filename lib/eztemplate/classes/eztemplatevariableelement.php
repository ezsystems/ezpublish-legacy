<?php
//
// Definition of eZTemplateVariableElement class
//
// Created on: <01-Mar-2002 13:50:58 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZTemplateVariableElement eztemplatevariableelement.php
  \ingroup eZTemplateElements
  \brief Represents a variable element in the template tree.

  The element contains the variable and all it's operators.
*/

class eZTemplateVariableElement
{
    /*!
     Initializes the object with the value array and operators.
    */
    function eZTemplateVariableElement( $data )
    {
        $this->Variable = $data;
    }

    /*!
     Returns #variable.
    */
    function name()
    {
        return "#variable";
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateVariableElement',
                      'parameters' => array( 'data' ),
                      'variables' => array( 'data' => 'Variable' ) );
    }

    /*!
     Process the variable with it's operators and appends the result to $text.
    */
    function process( $tpl, &$text, $nspace, $current_nspace )
    {
        $value = $tpl->elementValue( $this->Variable, $nspace );
        $tpl->appendElement( $text, $value, $nspace, $current_nspace );
    }

    /*!
     Returns the variable array.
    */
    function variable()
    {
        return $this->Variable;
    }

    /// The variable array
    public $Variable;
}

?>

<?php
//
// Definition of eZTemplateTextOperator class
//
// Created on: <01-Aug-2002 11:38:40 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*! \file eztemplatetextoperator.php
*/

/*!
  \class eZTemplateTextOperator eztemplatetextoperator.php
  \brief The class eZTemplateTextOperator does

*/

include_once( "lib/ezutils/classes/eztexttool.php" );

class eZTemplateTextOperator
{
    /*!
     Constructor
    */
    function eZTemplateTextOperator( $concatName = "concat" )
    {
        $this->Operators = array( $concatName );
        $this->ConcatName = $concatName;
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     Examines the input value and outputs a boolean value. See class documentation for more information.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$value )
    {
        switch ( $operatorName )
        {
            case $this->ConcatName:
            {
                $operands = array();
                for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                {
                    $operand = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace );
                    if ( !is_object( $operand ) )
                        $operands[] = $operand;
                }
                $value = eZTextTool::concat( $operands );
            } break;
        }
    }

    /// \privatesection
    var $ConcatName;
    var $Operators;
}

?>

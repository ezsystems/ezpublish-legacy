<?php
//
// Definition of eZTemplateTextOperator class
//
// Created on: <01-Aug-2002 11:38:40 amos>
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
    function eZTemplateTextOperator()
    {
        $this->Operators= array( 'concat', 'indent' );

        foreach ( $this->Operators as $operator )
        {
            $name = $operator . 'Name';
            $name[0] = $name[0] & "\xdf";
            $this->$name = $operator;
        }
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    function operatorTemplateHints()
    {
        return array( $this->ConcatName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => true,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => true,
                                                  'element-transformation-func' => 'concatTransformation'),
                      $this->IndentName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => 3,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => true,
                                                  'element-transformation-func' => 'indentTransformation') ) ;
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
        return array( $this->IndentName => array( 'indent_count' => array( 'type' => 'integer',
                                                                           'required' => true,
                                                                           'default' => false ),
                                                  'indent_type' => array( 'type' => 'identifier',
                                                                          'required' => false,
                                                                          'default' => 'space' ),
                                                  'indent_filler' => array( 'type' => 'string',
                                                                            'required' => false,
                                                                            'default' => false ) ) );
    }

    function concatTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                   &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( ( count( $parameters ) < 2) )
        {
            return false;
        }
        $newElements = array();

        $counter = 1;
        $code = "%output% = ( ";
        foreach ( $parameters as $parameter )
        {
            $values[] = $parameter;
            $code .= "%$counter%";
            if ( $counter == 1 )
            {
                $code .= '. ';
            }
            $counter++;
        }
        $code .= " );\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    /*!
     Handles concat and indent operators.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
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
                $operatorValue = eZTextTool::concat( $operands );
            } break;
            case $this->IndentName:
            {
                $indentCount = $namedParameters['indent_count'];
                $indentType = $namedParameters['indent_type'];
                $filler = false;
                switch ( $indentType )
                {
                    case 'space':
                    default:
                    {
                        $filler = ' ';
                    } break;
                    case 'tab':
                    {
                        $filler = "\t";
                    } break;
                    case 'custom':
                    {
                        $filler = $namedParameters['indent_filler'];
                    } break;
                }
                $text = '';
                $lines = explode( "\n", $operatorValue );
                $indentedLines = array();
                foreach ( $lines as $line )
                {
                    $indentedLines[] = str_repeat( $filler, $indentCount ) . $line;
                }
                $operatorValue = implode( "\n", $indentedLines );
            } break;
        }
    }

    /// \privatesection
    var $ConcatName;
    var $Operators;
}

?>

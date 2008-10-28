<?php
//
// Definition of eZKernelOperator class
//
// Created on: <11-Aug-2003 14:04:59 bf>
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

/*! \file ezkerneloperator.php
*/

/*!
  \class eZKerneloperator ezkerneloperator.php
  \brief The class eZKernelOperator does handles eZ Publish preferences

*/
class eZKernelOperator
{
    /*!
     Initializes the object with the name $name
    */
    function eZKernelOperator( $name = "ezpreference" )
    {
        $this->Operators = array( $name );
    }

    /*!
      Returns the template operators.
    */
    function operatorList()
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
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'ezpreference' => array( 'name' => array( 'type' => 'string',
                                                                'required' => true,
                                                                'default' => false ) ) );
    }

    function operatorTemplateHints()
    {
        return array( 'ezpreference' => array( 'input' => false,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => false,
                                               'element-transformation-func' => 'preferencesTransformation') );
    }

    function preferencesTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                        $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        if ( count( $parameters[0] ) == 0 )
            return false;
        $values = array();
        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
        {
            $name = eZTemplateNodeTool::elementStaticValue( $parameters[0] );
            $nameText = eZPHPCreator::variableText( $name, 0, 0, false );
        }
        else
        {
            $nameText = '%1%';
            $values[] = $parameters[0];
        }
        return array( eZTemplateNodeTool::createCodePieceElement( "//include_once( 'kernel/classes/ezpreferences.php' );\n" .
                                                                  "%output% = eZPreferences::value( $nameText );\n",
                                                                  $values ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'ezpreference':
            {
                $name = $namedParameters['name'];
                $value = eZPreferences::value( $name );
                $operatorValue = $value;
            }break;

            default:
            {
                eZDebug::writeError( "Unknown kernel operator: $operatorName" );
            }break;
        }
    }
    public $Operators;
}
?>

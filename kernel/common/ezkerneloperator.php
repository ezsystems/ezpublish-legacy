<?php
//
// Definition of eZKernelOperator class
//
// Created on: <11-Aug-2003 14:04:59 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezkerneloperator.php
*/

/*!
  \class eZKerneloperator ezkerneloperator.php
  \brief The class eZKerneloperator does handles eZ publish preferences

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

    function preferencesTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                        &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
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
        return array( eZTemplateNodeTool::createCodePieceElement( "include_once( 'kernel/classes/ezpreferences.php' );\n" .
                                                                  "%output% = eZPreferences::value( $nameText );\n",
                                                                  $values ) );
    }

    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'ezpreference':
            {
                include_once( 'kernel/classes/ezpreferences.php' );
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
    var $Operators;
}
?>

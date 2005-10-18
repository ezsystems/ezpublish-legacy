<?php
//
// Definition of eZModuleParamsOperator class
//
// Created on: <29-juli-2003 18:42:38 admin>
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

/*
If you want to have autoloading of this operator you should create
a eztemplateautoload.php file and add the following code to it.
The autoload file must be placed somewhere specified in AutoloadPath
under the group TemplateSettings in settings/site.ini

$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array( 'script' => 'templatemoduleparamsoperator.php',
                                    'class' => '$full_class_name',
                                    'operator_names' => array( 'module_params' ) );

By using module_params you can ...

  Example:
\code
{module_params|wash}
\endcode

*/


class eZModuleParamsOperator
{
    /*!
      Constructor, does nothing by default.
    */
    function eZModuleParamsOperator()
    {
    }

    /*!
     \return an array with the template operator name.
    */
    function operatorList()
    {
        return array( 'module_params' );
    }
    /*!
     \return true to tell the template engine that the parameter list exists per operator type,
             this is needed for operator classes that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( 'module_params' => array( 'first_param' => array( 'type' => 'string',
                                                                        'required' => false,
                                                                        'default' => 'default text' ),
                                                'second_param' => array( 'type' => 'integer',
                                                                         'required' => false,
                                                                         'default' => 0 ) ) );
    }
    /*!
     Executes the PHP function for the operator cleanup and modifies \a $operatorValue.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $firstParam = $namedParameters['first_param'];
        $secondParam = $namedParameters['second_param'];
        // Example code, this code must be modified to do what the operator should do, currently it only trims text.
        switch ( $operatorName )
        {
            case 'module_params':
            {
                trim( $operatorValue );
                $operatorValue = $GLOBALS['eZRequestedModuleParams'];
                eZDebug::writeDebug( $operatorValue, "ezmoduleparams operator" );
            } break;
        }
    }
}
?>

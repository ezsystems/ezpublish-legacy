<?php
//
// Created on: <19-Aug-2002 16:38:41 sp>
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

/*! \file edit.php
*/

include_once( "kernel/classes/ezmodulemanager.php" );
include_once( "kernel/classes/ezrole.php" );

include_once( "kernel/classes/ezsearch.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );

include_once( "kernel/common/template.php" );

$tpl =& templateInit();
$Module =& $Params["Module"];
$roleID =& $Params["RoleID"];

$modules = eZModuleManager::aviableModules();
eZDebug::writeNotice( $modules, 'modules' );


$role = eZRole::fetch( 0, $roleID );
eZDebug::writeNotice( $role, "temporary role" );
if ( is_null( $role ) )
{
    $role = eZRole::fetch( $roleID );
    if ( $role->attribute( 'version' ) == '0' )
    {
        $temporaryRole = $role->createTmporaryVersion();
        unset( $role );
        $role = $temporaryRole;
        eZDebug::writeNotice( $role, "new temporary role" );
    }
}

$http =& eZHTTPTool::instance();


if ($http->hasPostVariable( 'NewName' ) && $role->attribute( 'name' ) != $http->postVariable( 'NewName' )  )
{
    $role->setAttribute( 'name' , $http->postVariable( 'NewName' ) );
    $role->store();
}

$showModules = true;
$showFunctions = false;
$showLimitations = false;

if ( $http->hasPostVariable( 'Apply' )  )
{
    $originalRole = eZRole::fetch( $role->attribute( 'version' ) );
    $originalRole->revertFromTemporaryVersion();

    $Module->redirectTo( $Module->functionURI( "view" ) . "/" . $originalRole->attribute( 'id' ) . '/');
}

if ( $http->hasPostVariable( 'Discard' )  )
{
    $originalRole = eZRole::fetch( $roleID ) ;
    $originalRole->remove();
    $Module->redirectTo( $Module->functionURI( "list" ) . "/" );

}

if ( $http->hasPostVariable( 'ChangeRoleName' )  )
{
    $role->setAttribute( 'name', $http->postVariable( 'NewName' ) );
}
if ( $http->hasPostVariable( "AddModule" )  )
{
    $currentModule = $http->postVariable( 'Modules' );
    $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                    'FunctionName' => '*',
                                                    'Limitation' => '*') );

}
if ( $http->hasPostVariable( "AddFunction" ) )
{
    $currentModule = $http->postVariable( 'CurrentModule' );
    $currentFunction = $http->postVariable( 'ModuleFunction' );
    eZDebug::writeNotice( $currentModule, "currentModule");
    $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                    'FunctionName' => $currentFunction,
                                                    'Limitation' => '*') );

}

if ( $http->hasPostVariable( "AddLimitation" ) )
{
    $currentModule = $http->postVariable( 'CurrentModule' );
    $currentFunction = $http->postVariable( 'CurrentFunction' );
    $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                    'FunctionName' => $currentFunction,
                                                    'Limitation' => '') );

    $mod = & eZModule::exists( $currentModule );
    $functions =& $mod->attribute( 'aviable_functions' );
    $currentFunctionLimitations = $functions[ $currentFunction ];
    eZDebug::writeNotice($currentFunctionLimitations, 'currentFunctionLimitations');
    foreach ( $currentFunctionLimitations as $functionLimitation )
    {
        if ( $http->hasPostVariable( $functionLimitation['name'] ))
        {
            $limitationValues = $http->postVariable( $functionLimitation['name'] );
            eZDebug::writeNotice( $limitationValues, 'limitationValues');

            if ( !in_array('-1', $limitationValues ) )
            {
                $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), $functionLimitation['name'] );
                foreach ( $limitationValues as $limitationValue )
                {
                    eZPolicyLimitationValue::createNew( $policyLimitation->attribute( 'id' ), $limitationValue );
                }
            }
        }
    }
}

if ( $http->hasPostVariable( "RemovePolicy" ) )
{
    $policyID = $http->postVariable( "RolePolicy" ) ;
    eZDebug::writeNotice( $policyID, 'trying to remove policy' );
    eZPolicy::remove( $policyID );

}

if ( $http->hasPostVariable( "CustomFunction" )  )
{
    $currentModule = $http->postVariable( 'Modules' );
    $mod = & eZModule::exists( $currentModule );
//    var_dump( $currentModule );
    flush();
    $functions =& $mod->attribute( 'aviable_functions' );
    $functionNames = array_keys( $functions );

    $showModules = false;
    $showFunctions = true;
//    eZDebug::writeNotice( $functions, 'Functions' );
    $tpl->setVariable( "current_module", $currentModule );
    $tpl->setVariable( "functions", $functionNames );

}

if ( $http->hasPostVariable( "DiscardFunction" )  )
{
    $showModules = true;
    $showFunctions = false;
}

if ( $http->hasPostVariable( "Limitation" )  )
{
    $currentModule = $http->postVariable( 'CurrentModule' );
    $mod = & eZModule::exists( $currentModule );
    $functions =& $mod->attribute( 'aviable_functions' );
    $functionNames = array_keys( $functions );

    $showModules = false;
    $showFunctions = false;
    $showLimitations = true;

    $currentFunction = $http->postVariable( 'ModuleFunction' );
    $currentFunctionLimitations = array();
    foreach( $functions[ $currentFunction ] as $limitation )
    {
        if( count( $limitation[ 'values' ] == 0 ) && array_key_exists( 'class', $limitation ) )
        {
            include_once( 'kernel/' . $limitation['path'] . $limitation['file']  );
            $obj = new $limitation['class']( array() );
            $limitationValueList = call_user_func_array ( array( &$obj , $limitation['function']) , $limitation['parameter'] );
            $limitationValueArray =  array();
            foreach( $limitationValueList as $limitationValue )
            {
                $limitationValuePair = array();
                $limitationValuePair['Name'] = $limitationValue[ 'name' ];
                $limitationValuePair['value'] = $limitationValue[ 'id' ];
                $limitationValueArray[] = $limitationValuePair;
            }
            $limitation[ 'values' ] = $limitationValueArray;
        }
        $currentFunctionLimitations[] = $limitation;
    }

//    eZDebug::writeNotice( $functions, 'Functions' );
    $tpl->setVariable( "current_function", $currentFunction );
    $tpl->setVariable( "function_limitations", $currentFunctionLimitations );

    $tpl->setVariable( "current_module", $currentModule );
    $tpl->setVariable( "functions", $functionNames );

}

if ( $http->hasPostVariable( "DiscardLimitation" )  )
{
    $currentModule = $http->postVariable( 'CurrentModule' );
    $mod = & eZModule::exists( $currentModule );
    $functions =& $mod->attribute( 'aviable_functions' );
    $functionNames = array_keys( $functions );

    $showModules = false;
    $showFunctions = true;
//    eZDebug::writeNotice( $functions, 'Functions' );
    $tpl->setVariable( "current_module", $currentModule );
    $tpl->setVariable( "functions", $functionNames );
}

$policies = $role->attribute( 'policies' );

$tpl->setVariable( "show_modules", $showModules );
$tpl->setVariable( "show_limitations", $showLimitations );
$tpl->setVariable( "show_functions", $showFunctions );

$tpl->setVariable( "policies", $policies );
$tpl->setVariable( "modules", $modules );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "role", $role );

$Module->setTitle( "Edit " . $role->attribute( "name" ) );

$Result =& $tpl->fetch( 'design:role/edit.tpl' );

?>

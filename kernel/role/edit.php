<?php
//
// Created on: <19-Aug-2002 16:38:41 sp>
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
sort( $modules );


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

$tpl->setVariable( "module", $Module );

$role->turnOffCaching();

$tpl->setVariable( "role", $role );
$Module->setTitle( "Edit " . $role->attribute( "name" ) );

if ($http->hasPostVariable( 'NewName' ) && $role->attribute( 'name' ) != $http->postVariable( 'NewName' )  )
{
    $role->setAttribute( 'name' , $http->postVariable( 'NewName' ) );
    $role->store();
}

$showModules = true;
$showFunctions = false;
$showLimitations = false;
$noFunctions = false;
$noLimitations = false;

if ( $http->hasPostVariable( 'Apply' )  )
{
    $originalRole = eZRole::fetch( $role->attribute( 'version' ) );
    $originalRole->revertFromTemporaryVersion();
    include_once( 'kernel/classes/ezcontentobject.php' );
    eZContentObject::expireAllCache();
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
                $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), $functionLimitation['name'], $currentModule, $currentFunction);
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
if ( $http->hasPostVariable( "RemovePolicies" )  )
{
    foreach( $http->postVariable( 'DeleteIDArray' ) as $deleteID)
    {
        eZDebug::writeNotice( $deleteID, 'trying to remove policy' );
        eZPolicy::remove( $deleteID );
    }
}


if ( $http->hasPostVariable( "CustomFunction" ) )
{
    $currentModule = $http->postVariable( 'Modules' );
    if ( $currentModule != '*' )
    {
        $mod = & eZModule::exists( $currentModule );
//    var_dump( $currentModule );
        flush();
        $functions =& $mod->attribute( 'aviable_functions' );
        $functionNames = array_keys( $functions );
    }
    $showModules = false;
    $showFunctions = true;
    if ( count( $functionNames ) < 1 )
    {
        $showModules = true;
        $showFunctions = false;
        $showLimitations = false;
        $noFunctions = true;
    }

//    eZDebug::writeNotice( $functions, 'Functions' );
    $tpl->setVariable( "current_module", $currentModule );
    $tpl->setVariable( "functions", $functionNames );
    $tpl->setVariable( "no_functions", $noFunctions );

    $Module->setTitle( "Edit " . $role->attribute( "name" ) );
    $Result = array();

    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezi18n( 'kernel/role',
                                                      'Create policy - step 2 - Specify function' ) ) );

    $Result['content'] =& $tpl->fetch( 'design:role/createpolicystep2.tpl' );
    return;


}

if ( $http->hasPostVariable( "DiscardFunction" ) )
{
    $showModules = true;
    $showFunctions = false;

}

if ( $http->hasPostVariable( "Limitation" ) )
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
    if ( count( $currentFunctionLimitations ) < 1 )
    {
        $showModules = false;
        $showFunctions = true;
        $showLimitations = false;
        $noLimitations = true;
    }
//    eZDebug::writeNotice( $functions, 'Functions' );
    $tpl->setVariable( "current_function", $currentFunction );
    $tpl->setVariable( "function_limitations", $currentFunctionLimitations );
    $tpl->setVariable( "no_limitations", $noLimitations );

    $tpl->setVariable( "current_module", $currentModule );
    $tpl->setVariable( "functions", $functionNames );

    $Result = array();
    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezi18n( 'kernel/role',
                                                      'Create policy - step 3 - Specify limitations' ) ) );

    $Result['content'] =& $tpl->fetch( 'design:role/createpolicystep3.tpl' );
    return;
}

if ( $http->hasPostVariable( "DiscardLimitation" )  || $http->hasPostVariable( "Step2")  )
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
    $tpl->setVariable( "no_functions", false );

    $Result = array();
    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezi18n( 'kernel/role',
                                                      'Create policy - step 2 - Specify function' ) ) );

    $Result['content'] =& $tpl->fetch( 'design:role/createpolicystep2.tpl' );
    return;

}

if ( $http->hasPostVariable( 'CreatePolicy' ) || $http->hasPostVariable( "Step1") )
{
    $Module->setTitle( "Edit " . $role->attribute( "name" ) );
    $tpl->setVariable( "modules", $modules );
    $tpl->setVariable( "role", $role );
    $tpl->setVariable( "module", $Module );

    $Result = array();
    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezi18n( 'kernel/role',
                                                      'Create policy - step 1 - Specify module' ) ) );

    $Result['content'] =& $tpl->fetch( 'design:role/createpolicystep1.tpl' );
    return;

}


$policies = $role->attribute( 'policies' );
$tpl->setVariable( "no_functions", $noFunctions );
$tpl->setVariable( "no_limitations", $noLimitations );

$tpl->setVariable( "show_modules", $showModules );
$tpl->setVariable( "show_limitations", $showLimitations );
$tpl->setVariable( "show_functions", $showFunctions );

$tpl->setVariable( "policies", $policies );
$tpl->setVariable( "modules", $modules );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "role", $role );

$tpl->setVariable( "step", 0 );

$Module->setTitle( "Edit " . $role->attribute( "name" ) );

$Result = array();
$Result['path'] = array( array( 'url' => '/role/edit/' . $roleID . '/' ,
                                'text' => ezi18n( 'kernel/role', 'Role edit' ) ) );

$Result['content'] =& $tpl->fetch( 'design:role/edit.tpl' );

?>

<?php
//
// Created on: <15-Aug-2002 14:37:29 bf>
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
function &makeTriggerArray( &$triggerList )
{
    $triggerArray = array();
    foreach ( array_keys( $triggerList ) as $key )
    {
        $trigger =& $triggerList[$key];
        $newKey = $trigger->attribute( 'module_name' ) . '_' . $trigger->attribute( 'function_name' ) . '_' . $trigger->attribute( 'connect_type' );
        $triggerArray[$newKey] =& $trigger;
    }
    return $triggerArray;
}

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/eztrigger.php' );
include_once( "kernel/classes/ezmodulemanager.php" );

$http =& eZHTTPTool::instance();

$Module =& $Params['Module'];

$moduleName= & $Params['ModuleName1'];
$functionName= & $Params['FunctionName1'];

$wfINI =& eZINI::instance( 'workflow.ini' );
$operations = $wfINI->variableArray( 'OperationSettings', 'AvailableOperations' );
$possibleTriggers = array();

$triggers =& makeTriggerArray( eZTrigger::fetchList() );

foreach ( $operations as $operation )
{
    list( $module_name, $operation ) = explode( '_', $operation );
    $trigger=array();
    $trigger['module'] = $module_name;
    $trigger['operation'] = $operation;
    $trigger['connect_type'] = 'before';
    $trigger['key'] = $module_name . '_' . $operation . '_b';
    $trigger['workflow_id'] = 0;
    $trigger['allowed_workflows'] = eZWorkflow::fetchLimited( $trigger['module'], $trigger['operation'], $trigger['connect_type'] );

    foreach ( array_keys ( $triggers ) as $key )
    {
        $existendTrigger =& $triggers[$key];

        if ( $existendTrigger->attribute( 'module_name' ) == $trigger['module'] &&
             $existendTrigger->attribute( 'function_name' ) == $trigger['operation'] &&
             $existendTrigger->attribute( 'connect_type' ) == 'b' )
        {
                $trigger['workflow_id'] = $existendTrigger->attribute( 'workflow_id' );
        }

    }
    $possibleTriggers[] = $trigger;
    $trigger['workflow_id'] = 0;
    foreach ( array_keys ( $triggers ) as $key )
    {
        $existendTrigger =& $triggers[$key];
        if ( $existendTrigger->attribute( 'module_name' ) == $trigger['module'] &&
             $existendTrigger->attribute( 'function_name' ) == $trigger['operation'] &&
             $existendTrigger->attribute( 'connect_type' ) == 'a' )
        {
                $trigger['workflow_id'] = $existendTrigger->attribute( 'workflow_id' );
        }

    }
    $trigger['key'] = $module_name . '_' . $operation . '_a';

    $trigger['connect_type'] = 'after';
    $trigger['allowed_workflows'] = eZWorkflow::fetchLimited( $trigger['module'], $trigger['operation'], $trigger['connect_type'] );
    $possibleTriggers[] = $trigger;
}

if ( $http->hasPostVariable( 'StoreButton' )  )
{
    foreach ( array_keys( $possibleTriggers ) as $key )
    {
        $trigger =& $possibleTriggers[$key];

        eZDebug::writeDebug( $trigger, "check trigger" );

        if ( $http->hasPostVariable( 'WorkflowID_' . $trigger['key'] ) )
        {
            $workflowID = $http->postVariable( 'WorkflowID_' . $trigger['key'] );
            if( $workflowID != -1 )
            {
                if ( !array_key_exists( $trigger['key'], $triggers ) )
                {
                    //create trigger
                    if ( $trigger['connect_type'] == 'before' )
                    {
                        $connectType = 'b';
                    }
                    else
                    {
                        $connectType = 'a';
                    }
                    $newTrigger =& eZTrigger::createNew( $trigger['module'], $trigger['operation'], $connectType, $workflowID );
                }
                else
                {
                    $existendTrigger =& $triggers[$trigger['key']];
                    if ( $existendTrigger->attribute( 'workflow_id' ) != $workflowID )
                    {
                        $existendTrigger =& $triggers[$trigger['key']];
                        $existendTrigger->setAttribute( 'workflow_id', $workflowID );
                        $existendTrigger->store();
                    }
                    // update trigger
                }
            }
            else if ( array_key_exists( $trigger['key'], $triggers ) )
            {
                $existendTrigger =& $triggers[$trigger['key']];
                $existendTrigger->remove();
                //remove trigger
            }
        }
    }
    $Module->redirectToView( 'list' );

}


if ( $moduleName == '' )
{
    $moduleName='*';
}
if ( $functionName == '' )
{
    $functionName='*';
}

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        foreach ( $deleteIDArray as $deleteID )
        {
            eZTrigger::remove( $deleteID );
        }
    }
}

if ( $http->hasPostVariable( 'NewButton' )  )
{
    $trigger =& eZTrigger::createNew( );
}


$tpl =& templateInit();

$triggers =& eZTrigger::fetchList( array(
                                       'module' => $moduleName,
                                       'function' => $functionName
                                       ) );
$showModuleList = false;
$showFunctionList = false;
$functionList = array();
$moduleList = array();
if ( $moduleName == '*' )
{
    $showModuleList = true;
    $moduleList =& eZModuleManager::availableModules();
}
elseif( $functionName == '*' )
{
    $mod =& eZModule::exists( $moduleName );
    $functionList =& array_keys( $mod->attribute( 'available_functions' ) );
    eZDebug::writeNotice( $functionList, "functions" );
    $showFunctionList = true;
}

$tpl->setVariable( 'current_module', $moduleName );
$tpl->setVariable( 'current_function', $functionName );
$tpl->setVariable( 'show_functions', $showFunctionList );
$tpl->setVariable( 'show_modules', $showModuleList );

$tpl->setVariable( 'possible_triggers', $possibleTriggers );

$tpl->setVariable( 'modules', $moduleList );
$tpl->setVariable( 'functions', $functionList );

$tpl->setVariable( 'triggers', $triggers );
$tpl->setVariable( 'module', $Module );

$Result['content'] =& $tpl->fetch( 'design:trigger/list.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/trigger', 'Trigger' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/trigger', 'List' ),
                                'url' => false ) );


?>

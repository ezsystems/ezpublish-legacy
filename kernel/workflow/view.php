<?php
//
// Created on: <20-Sep-2004 15:11:32 jk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( "kernel/classes/ezworkflow.php" );
//include_once( "kernel/classes/ezworkflowgrouplink.php" );
require_once( "kernel/common/template.php" );

$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$validation = array( 'processed' => false,
                     'groups' => array() );

$WorkflowID = $Params["WorkflowID"];
$WorkflowID = (int) $WorkflowID;
if ( !is_int( $WorkflowID ) )
    $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );

$workflow = eZWorkflow::fetch( $WorkflowID );
if ( !$workflow )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( $http->hasPostVariable( "AddGroupButton" ) && $http->hasPostVariable( "Workflow_group") )
{
    //include_once( "kernel/workflow/ezworkflowfunctions.php" );

    $selectedGroup = $http->postVariable( "Workflow_group" );
    eZWorkflowFunctions::addGroup( $WorkflowID, 0, $selectedGroup );
}
if ( $http->hasPostVariable( "DeleteGroupButton" ) && $http->hasPostVariable( "group_id_checked" ) )
{
    //include_once( "kernel/workflow/ezworkflowfunctions.php" );

    $selectedGroup = $http->postVariable( "group_id_checked" );
    if ( !eZWorkflowFunctions::removeGroup( $WorkflowID, 0, $selectedGroup ) )
    {
        $validation['groups'][] = array( 'text' => ezi18n( 'kernel/workflow', 'You have to have at least one group that the workflow belongs to!' ) );
        $validation['processed'] = true;
    }
}

$event_list = $workflow->fetchEvents();

$tpl = templateInit();
$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( "workflow", $workflow->attribute( "id" ) ) ) );

$tpl->setVariable( "workflow", $workflow );
$tpl->setVariable( "event_list", $event_list );
$tpl->setVariable( 'validation', $validation );

$Result = array();
$Result['content'] = $tpl->fetch( "design:workflow/view.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/workflow', 'View' ),
                                'url' => false ) );

?>

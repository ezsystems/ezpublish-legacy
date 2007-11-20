<?php
//
// Created on: <01-Jul-2002 17:06:14 amos>
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

$Module = $Params['Module'];

$WorkflowProcessID = null;
if ( !isset( $Params["WorkflowProcessID"] ) )
{
    $Module->redirectTo( $Module->functionURI( "list" ) );
    return;
}

$WorkflowProcessID = $Params["WorkflowProcessID"];

//include_once( "kernel/classes/ezworkflowprocess.php" );

$process = eZWorkflowProcess::fetch( $WorkflowProcessID );
if ( $process === null )
{
    $Module->redirectTo( $Module->functionURI( "list" ) );
    return;
}

//include_once( "lib/ezutils/classes/ezhttptool.php" );
$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( "Reset" ) )
{
    $process->reset();
    $process->setAttribute( "modified", time() );
    $process->store();
}

// Template handling
require_once( "kernel/common/template.php" );
$tpl = templateInit();

//include_once( "kernel/classes/ezworkflow.php" );
$workflow = eZWorkflow::fetch( $process->attribute( "workflow_id" ) );
$workflowEvent = false;
if ( $process->attribute( "event_id" ) != 0 )
    $workflowEvent = eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );

$lastEventStatus = $process->attribute( "last_event_status" );

if ( $http->hasPostVariable( "RunProcess" ) )
{
//     $Module->redirectTo( $Module->functionURI( "process" ) . "/" . $WorkflowProcessID );
//     return;
    if ( $workflowEvent instanceof eZWorkflowEvent )
    {
        $eventType = $workflowEvent->eventType();
        $lastEventStatus = $eventType->execute( $process, $workflowEvent );
    }
    $event_pos = $process->attribute( "event_position" );
    $next_event_pos = $event_pos + 1;
    $next_event_id = $workflow->fetchEventIndexed( $next_event_pos );
    if ( $next_event_id !== null )
    {
        $process->advance( $next_event_id, $next_event_pos, $lastEventStatus  );
        $workflowEvent = eZWorkflowEvent::fetch( $next_event_id );
    }
    else
    {
        unset( $workflowEvent );
        $workflowEvent = false;
        $process->advance();
    }
    $process->setAttribute( "modified", time() );
    $process->store();
}
$tpl->setVariable( "event_status", eZWorkflowType::statusName( $lastEventStatus ) );
$tpl->setVariable( "current_workflow", $workflow );
$tpl->setVariable( "current_event", $workflowEvent );

$Module->setTitle( "Workflow process" );

$tpl->setVariable( "process", $process );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );

$Result = array();
$Result['content'] = $tpl->fetch( "design:workflow/process.tpl" );


?>

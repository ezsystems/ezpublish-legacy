<?php
//
// Created on: <01-Jul-2002 17:06:14 amos>
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

$Module =& $Params["Module"];

$WorkflowProcessID = null;
if ( !isset( $Params["WorkflowProcessID"] ) )
{
    $Module->redirectTo( $Module->functionURI( "list" ) );
    return;
}

$WorkflowProcessID = $Params["WorkflowProcessID"];

include_once( "kernel/classes/ezworkflowprocess.php" );

$process =& eZWorkflowProcess::fetch( $WorkflowProcessID );
if ( $process === null )
{
    $Module->redirectTo( $Module->functionURI( "list" ) );
    return;
}

include_once( "lib/ezutils/classes/ezhttptool.php" );
$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( "Reset" ) )
{
    $process->reset();
    include_once( "lib/ezlocale/classes/ezdatetime.php" );
    $process->setAttribute( "modified", eZDateTime::currentTimeStamp() );
    $process->store();
}

// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

include_once( "kernel/classes/ezworkflow.php" );
$workflow =& eZWorkflow::fetch( $process->attribute( "workflow_id" ) );
$workflowEvent = false;
if ( $process->attribute( "event_id" ) != 0 )
    $workflowEvent =& eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );

$lastEventStatus = $process->attribute( "last_event_status" );

if ( $http->hasPostVariable( "RunProcess" ) )
{
//     $Module->redirectTo( $Module->functionURI( "process" ) . "/" . $WorkflowProcessID );
//     return;
    if ( get_class( $workflowEvent ) == "ezworkflowevent" )
    {
        $eventType =& $workflowEvent->eventType();
        $lastEventStatus = $eventType->execute( $process, $workflowEvent );
    }
    $event_pos = $process->attribute( "event_position" );
    $next_event_pos = $event_pos + 1;
    $next_event_id = $workflow->fetchEventIndexed( $next_event_pos );
    if ( $next_event_id !== null )
    {
        $process->advance( $next_event_id, $next_event_pos, $lastEventStatus  );
        $workflowEvent =& eZWorkflowEvent::fetch( $next_event_id );
    }
    else
    {
        unset( $workflowEvent );
        $workflowEvent = false;
        $process->advance();
    }
    include_once( "lib/ezlocale/classes/ezdatetime.php" );
    $process->setAttribute( "modified", eZDateTime::currentTimeStamp() );
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
$Result['content'] =& $tpl->fetch( "design:workflow/process.tpl" );


?>

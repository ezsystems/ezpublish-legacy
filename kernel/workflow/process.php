<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];

$WorkflowProcessID = null;
if ( !isset( $Params["WorkflowProcessID"] ) )
{
    $Module->redirectTo( $Module->functionURI( "list" ) );
    return;
}

$WorkflowProcessID = $Params["WorkflowProcessID"];

$process = eZWorkflowProcess::fetch( $WorkflowProcessID );
if ( $process === null )
{
    $Module->redirectTo( $Module->functionURI( "list" ) );
    return;
}

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( "Reset" ) )
{
    $process->reset();
    $process->setAttribute( "modified", time() );
    $process->store();
}

// Template handling

$tpl = eZTemplate::factory();

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

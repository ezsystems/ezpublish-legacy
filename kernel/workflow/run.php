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

// include_once( "lib/ezutils/classes/ezexecutionstack.php" );
// $execStack =& eZExecutionStack::instance();
// $execStack->addEntry( $Module->functionURI( "run" ) . "/" . $WorkflowProcessID,
//                       $Module->attribute( "name" ), "run" );

// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

include_once( "kernel/classes/ezworkflow.php" );
$workflow =& eZWorkflow::fetch( $process->attribute( "workflow_id" ) );

$workflowEvent = null;
if ( $process->attribute( "event_id" ) != 0 )
    $workflowEvent =& eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );

$process->run( $workflow, $workflowEvent, $eventLog );
// Store changes to process
if ( $process->attribute( 'status' ) != EZ_WORKFLOW_STATUS_DONE )
{
    $process->store();
}
if ( $process->attribute( 'status' ) == EZ_WORKFLOW_STATUS_DONE )
{
//    list ( $module, $function, $parameters ) = $process->getModuleInfo();
}
$tpl->setVariable( "event_log", $eventLog );
$tpl->setVariable( "current_workflow", $workflow );

$Module->setTitle( "Workflow run" );

$tpl->setVariable( "process", $process );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:workflow/run.tpl" );

?>

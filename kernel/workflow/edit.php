<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
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

include_once( "kernel/classes/ezworkflow.php" );
include_once( "kernel/classes/ezworkflowgrouplink.php" );
include_once( "kernel/classes/ezworkflowevent.php" );
include_once( "kernel/classes/ezworkflowtype.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$Module =& $Params["Module"];
if ( isset( $Params["WorkflowID"] ) )
    $WorkflowID = $Params["WorkflowID"];
else
    $WorkflowID = false;

if ( isset( $Params["GroupID"] ) )
{
    eZDebug::writeDebug( $GroupID, "GroupID" );
    $GroupID = $Params["GroupID"];
}
else
{
    eZDebug::writeDebug( $GroupID, "unknown GroupID" );
}
if ( isset( $Params["GroupName"] ) )
    $GroupName = $Params["GroupName"];

switch ( $Params["FunctionName"] )
{
    case "up":
    case "down":
    {
        $event =& eZWorkflowEvent::fetch( $Params["EventID"], true, 1,
                                          array( "workflow_id", "version", "placement" ) );
        $event->move( $Params["FunctionName"] == "up" ? false : true );
        $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $WorkflowID );
        return;
    } break;
    case "edit":
    {
    } break;
    default:
    {
        eZDebug::writeError( "Undefined function: " . $params["Function"] );
        $Module->setExitStatus( EZ_MODULE_STATUS_FAILED );
        return;
    }
}

// include_once( "lib/ezutils/classes/ezexecutionstack.php" );
// $execStack =& eZExecutionStack::instance();
// $execStack->addEntry( $Module->functionURI( "edit" ) . "/" . $WorkflowID,
//                       $Module->attribute( "name" ), "edit" );

if ( is_numeric( $WorkflowID ) )
{
    $workflow =& eZWorkflow::fetch( $WorkflowID, true, 1 );

    // If temporary version does not exist fetch the current
    if ( is_null( $workflow ) )
    {
        $workflow =& eZWorkflow::fetch( $WorkflowID, true, 0 );
        $workflowGroups=& eZWorkflowGroupLink::fetchGroupList( $WorkflowID, 0, true );
        foreach ( $workflowGroups as $workflowGroup )
        {
            $groupID = $workflowGroup->attribute( "group_id" );
            $groupName = $workflowGroup->attribute( "group_name" );
            $ingroup =& eZWorkflowGroupLink::create( $WorkflowID, 1, $groupID, $groupName );
            $ingroup->store();
        }
    }
}
else
{
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $workflow =& eZWorkflow::create( $user_id );
    $workflowCount = eZWorkflow::fetchListCount();
    ++$workflowCount;
    $workflow->setAttribute( "name", "New Workflow$workflowCount" );
    $workflow->store();
    $WorkflowID = $workflow->attribute( "id" );
    $WorkflowVersion = $workflow->attribute( "version" );
    $ingroup =& eZWorkflowGroupLink::create( $WorkflowID, $WorkflowVersion, $GroupID, $GroupName );
    $ingroup->store();
    return $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $WorkflowID );
}

$http =& eZHttpTool::instance();
$WorkflowVersion = $workflow->attribute( "version" );
if ( $http->hasPostVariable( "DiscardButton" ) )
{
    $workflow->setVersion( 1 );
    $workflow->remove( true );
    eZWorkflowGroupLink::removeWorkflowMembers( $WorkflowID, $WorkflowVersion );

    $workflowGroups=& eZWorkflowGroupLink::fetchGroupList( $WorkflowID, 0, true );
    $groupID = false;
    if ( count( $workflowGroups ) > 0 )
        $groupID = $workflowGroups[0]->attribute( 'group_id' );
    if ( $groupID )
        return $Module->redirectToView( 'workflowlist', array( $groupID ) );
    else
        return $Module->redirectToView( 'grouplist' );
}
if ( $http->hasPostVariable( "AddGroupButton" ) )
{
    if ( $http->hasPostVariable( "Workflow_group") )
    {
        $selectedGroup = $http->postVariable( "Workflow_group" );
        list ( $groupID, $groupName ) = split( "/", $selectedGroup );
        $ingroup =& eZWorkflowGroupLink::create( $WorkflowID, $WorkflowVersion, $groupID, $groupName );
        $ingroup->store();
    }
}

if ( $http->hasPostVariable( "DeleteGroupButton" ) )
{
    if ( $http->hasPostVariable( "group_id_checked") )
    {
        $selectedGroup = $http->postVariable( "group_id_checked" );
        foreach(  $selectedGroup as $group_id )
        {
            eZWorkflowGroupLink::remove( $WorkflowID, $WorkflowVersion , $group_id );
        }
    }
}

// Fetch events and types
$event_list =& $workflow->fetchEvents();
$type_list =& eZWorkflowType::fetchRegisteredTypes();

// Validate input
include_once( "lib/ezutils/classes/ezinputvalidator.php" );
$canStore = true;
$requireFixup = false;
reset( $event_list );
while( ( $key = key( $event_list ) ) !== null )
{
    $event =& $event_list[$key];
//    var_dump( $event  );
    $eventType =& $event->eventType();
    $status = $eventType->validateHTTPInput( $http, "WorkflowEvent", $event );
    if ( $status == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
        $requireFixup = true;
    else if ( $status == EZ_INPUT_VALIDATOR_STATE_INVALID )
        $canStore = false;
    next( $event_list );
}

// Fixup input
if ( $requireFixup )
{
    reset( $event_list );
    while( ( $key = key( $event_list ) ) !== null )
    {
        $event =& $event_list[$key];
        $eventType =& $event->eventType();
        $status = $eventType->fixupHTTPInput( $http, "WorkflowEvent", $event );
        next( $event_list );
    }
}

$cur_type = 0;
// Apply HTTP POST variables
eZHttpPersistence::fetch( "WorkflowEvent", eZWorkflowEvent::definition(),
                          $event_list, $http, true );
eZHttpPersistence::fetch( "Workflow", eZWorkflow::definition(),
                          $workflow, $http, false );
if ( $http->hasPostVariable( "WorkflowTypeString" ) )
    $cur_type = $http->postVariable( "WorkflowTypeString" );
$workflow->setVersion( 1, $event_list );

// Set new modification date
$date_time = time();
$workflow->setAttribute( "modified", $date_time );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
$user =& eZUser::currentUser();
$user_id = $user->attribute( "contentobject_id" );
$workflow->setAttribute( "modifier_id", $user_id );




/********** Custom Action Code Start ***************/
$customAction = false;
$customActionAttributeID = null;
// Check for custom actions
if ( $http->hasPostVariable( "CustomActionButton" ) )
{
    $customActionArray = $http->postVariable( "CustomActionButton" );
    $customActionString = key( $customActionArray );

    $customActionAttributeID = preg_match( "#^([0-9]+)_(.*)$#", $customActionString, $matchArray );
    $customActionAttributeID = $matchArray[1];
    $customAction = $matchArray[2];
}
/********** Custom Action Code End ***************/


// Fetch HTTP input
reset( $event_list );
while( ( $key = key( $event_list ) ) !== null )
{
    $event =& $event_list[$key];
    $eventType =& $event->eventType();
    $eventType->fetchHTTPInput( $http, "WorkflowEvent", $event );
    if ( $customActionAttributeID == $event->attribute( "id" ) )
    {
        $event->customHTTPAction( $http, $customAction );
    }
    next( $event_list );
}

// Store changes
if ( $canStore )
{
    $workflow->store( $event_list );
}

// Discard existing events, workflow version 1 and store version 0
if ( $http->hasPostVariable( "StoreButton" ) and $canStore )
{
    // Remove old version 0 first
    eZWorkflowGroupLink::removeWorkflowMembers( $WorkflowID, 0 );

    $workflowgroups =& eZWorkflowGroupLink::fetchGroupList( $WorkflowID, 1 );
	for ( $i=0;$i<count(  $workflowgroups );$i++ )
    {
        $workflowgroup =& $workflowgroups[$i];
        $workflowgroup->setAttribute("workflow_version", 0 );
        $workflowgroup->store();
    }
    // Remove version 1
    eZWorkflowGroupLink::removeWorkflowMembers( $WorkflowID, 1 );

    eZWorkflow::removeEvents( false, $WorkflowID, 0 );
    $workflow->remove( true );
    $workflow->setVersion( 0, $event_list );
    $workflow->adjustEventPlacements( $event_list );
    $workflow->store( $event_list );

    $workflowGroups=& eZWorkflowGroupLink::fetchGroupList( $WorkflowID, 0, true );
    $groupID = false;
    if ( count( $workflowGroups ) > 0 )
        $groupID = $workflowGroups[0]->attribute( 'group_id' );
    if ( $groupID )
        return $Module->redirectToView( 'workflowlist', array( $groupID ) );
    else
        return $Module->redirectToView( 'grouplist' );
}

// Remove events which are to be deleted
if ( $http->hasPostVariable( "DeleteButton" ) )
{
//     $http->setSessionVariable( "ExecutionStack", $executions );
    if ( eZHttpPersistence::splitSelected( "WorkflowEvent", $event_list,
                                           $http, "id",
                                           $keepers, $rejects ) )
    {
        $event_list = $keepers;
        foreach ( $rejects as $reject )
        {
            $reject->remove();
        }
    }
}


if ( $http->hasPostVariable( "NewButton" ) )
{
    $new_event =& eZWorkflowEvent::create( $WorkflowID, $cur_type );
    $new_event_type =& $new_event->eventType();
    $new_event_type->initializeEvent( $new_event );
    $new_event->store();
    $event_list[] =& $new_event;
}

$Module->setTitle( ezi18n( 'kernel/workflow', 'Edit workflow' ) . ' ' . $workflow->attribute( "name" ) );

// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( "workflow", $workflow->attribute( "id" ) ), // Workflow ID
                      array( "workflow_type", $workflow->attribute( "type" ) ) ) ); // Workflow Type

$tpl->setVariable( "http", $http );
$tpl->setVariable( "can_store", $canStore );
$tpl->setVariable( "require_fixup", $requireFixup );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "workflow", $workflow );
$tpl->setVariable( "event_list", $event_list );
$tpl->setVariable( "workflow_type_list", $type_list );
$tpl->setVariable( "workflow_type", $cur_type );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:workflow/edit.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/workflow', 'Edit' ),
                                'url' => false ) );

?>

<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

$WorkflowID = ( isset( $Params["WorkflowID"] ) ) ? $Params["WorkflowID"] : false;

switch ( $Params["FunctionName"] )
{
case "up":
case "down":
    {
        if ( $WorkflowID !== false )
        {
            if ( isset( $Params["EventID"] ) )
            {
                $event = eZWorkflowEvent::fetch( $Params["EventID"], true, 1,
                                                 array( "workflow_id", "version", "placement" ) );
                $event->move( $Params["FunctionName"] == "up" ? false : true );
                $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $WorkflowID );
                return;
            }
            else
            {
                eZDebug::writeError( "Missing parameter EventID for function: " . $params["Function"] );
                $Module->setExitStatus( eZModule::STATUS_FAILED );
                return;
            }
        }
        else
        {
            eZDebug::writeError( "Missing parameter WorkfowID for function: " . $params["Function"] );
            $Module->setExitStatus( eZModule::STATUS_FAILED );
            return;
        }
    } break;
case "edit":
    {
    } break;
default:
    {
        eZDebug::writeError( "Undefined function: " . $params["Function"] );
        $Module->setExitStatus( eZModule::STATUS_FAILED );
        return;
    }
}

$GroupID = ( isset( $Params["GroupID"] ) ) ? $Params["GroupID"] : false;
$GroupName = ( isset( $Params["GroupName"] ) ) ? $Params["GroupName"] : false;

if ( is_numeric( $WorkflowID ) )
{
    // try to fetch temporary version of workflow
    $workflow = eZWorkflow::fetch( $WorkflowID, true, 1 );

    // If temporary version does not exist fetch the current
    if ( !is_object( $workflow ) )
    {
        $workflow = eZWorkflow::fetch( $WorkflowID, true, 0 );
        if ( is_object( $workflow ) )
        {
            $workflowGroups = eZWorkflowGroupLink::fetchGroupList( $WorkflowID, 0, true );

            $db = eZDB::instance();
            $db->begin();
            foreach ( $workflowGroups as $workflowGroup )
            {
                $groupID = $workflowGroup->attribute( "group_id" );
                $groupName = $workflowGroup->attribute( "group_name" );
                $ingroup = eZWorkflowGroupLink::create( $WorkflowID, 1, $groupID, $groupName );
                $ingroup->store();
            }
            $db->commit();
        }
        else
        {
            eZDebug::writeError( "Cannot fetch workflow with WorkfowID = " . $WorkflowID );
            $Module->setExitStatus( eZModule::STATUS_FAILED );
            return;
        }
    }
}
else
{
    // if WorkflowID was not given then create new workflow
    $user = eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $workflow = eZWorkflow::create( $user_id );
    $workflowCount = eZWorkflow::fetchListCount();
    ++$workflowCount;
    $workflow->setAttribute( "name", ezpI18n::translate( 'kernel/workflow/edit', "New Workflow" ) . "$workflowCount" );

    $db = eZDB::instance();
    $db->begin();
    $workflow->store();
    $WorkflowID = $workflow->attribute( "id" );
    $WorkflowVersion = $workflow->attribute( "version" );
    $ingroup = eZWorkflowGroupLink::create( $WorkflowID, $WorkflowVersion, $GroupID, $GroupName );
    $ingroup->store();
    $db->commit();
    return $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $WorkflowID . '/' . $GroupID );
}

$http = eZHTTPTool::instance();
$WorkflowVersion = $workflow->attribute( "version" );

if ( $http->hasPostVariable( "DiscardButton" ) )
{
    $workflow->setVersion( 1 );

    $db = eZDB::instance();
    $db->begin();
    $workflow->removeThis( true );
    eZWorkflowGroupLink::removeWorkflowMembers( $WorkflowID, $WorkflowVersion );
    $db->commit();

    $workflowGroups= eZWorkflowGroupLink::fetchGroupList( $WorkflowID, 0, true );
    $groupID = false;
    if ( count( $workflowGroups ) > 0 )
        $groupID = $workflowGroups[0]->attribute( 'group_id' );
    if ( !$groupID )
        $groupID = $GroupID;
    if ( $groupID )
        return $Module->redirectToView( 'workflowlist', array( $groupID ) );
    else
        return $Module->redirectToView( 'grouplist' );
}

$validation = array( 'processed' => false,
                     'groups' => array(),
                     'attributes' => array(),
                     'events' => array() );

if ( $http->hasPostVariable( "AddGroupButton" ) && $http->hasPostVariable( "Workflow_group") )
{
    $selectedGroup = $http->postVariable( "Workflow_group" );
    eZWorkflowFunctions::addGroup( $WorkflowID, $WorkflowVersion, $selectedGroup );
}

if ( $http->hasPostVariable( "DeleteGroupButton" ) && $http->hasPostVariable( "group_id_checked" ) )
{
    $selectedGroup = $http->postVariable( "group_id_checked" );
    if ( !eZWorkflowFunctions::removeGroup( $WorkflowID, $WorkflowVersion, $selectedGroup ) )
    {
        $validation['groups'][] = array( 'text' => ezpI18n::translate( 'kernel/workflow', 'You have to have at least one group that the workflow belongs to!' ) );
        $validation['processed'] = true;
    }
}

// Fetch events and types
$event_list = $workflow->fetchEvents();
$type_list = eZWorkflowType::fetchRegisteredTypes();

if ( $http->hasPostVariable( "DeleteButton" ) )
{
    $db = eZDB::instance();
    $db->begin();

    if ( eZHTTPPersistence::splitSelected( "WorkflowEvent", $event_list,
                                           $http, "id",
                                           $keepers, $rejects ) )
    {
        $event_list = $keepers;

        foreach ( $rejects as $reject )
        {
            $reject->remove();
        }
    }
    $db->commit();

    $event_list = $workflow->fetchEvents();
}

// Validate input
$canStore = true;
$requireFixup = false;
foreach( $event_list as $event )
{
    $eventType = $event->eventType();
    $status = $eventType->validateHTTPInput( $http, "WorkflowEvent", $event, $validation );

    if ( $status == eZInputValidator::STATE_INTERMEDIATE )
        $requireFixup = true;
    else if ( $status == eZInputValidator::STATE_INVALID )
        $canStore = false;
}

// Fixup input
if ( $requireFixup )
{
    foreach( $event_list as $event )
    {
        $eventType = $event->eventType();
        $status = $eventType->fixupHTTPInput( $http, "WorkflowEvent", $event );
    }
}

$cur_type = 0;
// Apply HTTP POST variables
eZHTTPPersistence::fetch( "WorkflowEvent", eZWorkflowEvent::definition(),
                          $event_list, $http, true );
eZHTTPPersistence::fetch( "Workflow", eZWorkflow::definition(),
                          $workflow, $http, false );
if ( $http->hasPostVariable( "WorkflowTypeString" ) )
    $cur_type = $http->postVariable( "WorkflowTypeString" );

// set temporary version to edited workflow
$workflow->setVersion( 1, $event_list );

// Set new modification date
$date_time = time();
$workflow->setAttribute( "modified", $date_time );
$user = eZUser::currentUser();
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
foreach( $event_list as $event )
{
    $eventType = $event->eventType();
    $eventType->fetchHTTPInput( $http, "WorkflowEvent", $event );
    if ( $customActionAttributeID == $event->attribute( "id" ) )
    {
        $event->customHTTPAction( $http, $customAction );
    }
}

if ( $http->hasPostVariable( "StoreButton" ) and $canStore )
{
    // Discard existing events, workflow version 1 and store version 0
    $db = eZDB::instance();
    $db->begin();

    $workflow->store( $event_list ); // store changes.

    // Remove old version 0 first
    eZWorkflowGroupLink::removeWorkflowMembers( $WorkflowID, 0 );

    $workflowgroups = eZWorkflowGroupLink::fetchGroupList( $WorkflowID, 1 );
    foreach( $workflowgroups as $workflowgroup )
    {
        $workflowgroup->setAttribute("workflow_version", 0 );
        $workflowgroup->store();
    }
    // Remove version 1
    eZWorkflowGroupLink::removeWorkflowMembers( $WorkflowID, 1 );

    eZWorkflow::removeEvents( false, $WorkflowID, 0 );
    $workflow->removeThis( true );
    $workflow->setVersion( 0, $event_list );
    $workflow->adjustEventPlacements( $event_list );
//    $workflow->store( $event_list );
    $workflow->storeDefined( $event_list );
    $workflow->cleanupWorkFlowProcess();

    $db->commit();

    $workflowGroups= eZWorkflowGroupLink::fetchGroupList( $WorkflowID, 0, true );
    $groupID = false;
    if ( count( $workflowGroups ) > 0 )
        $groupID = $workflowGroups[0]->attribute( 'group_id' );
    if ( $groupID )
        return $Module->redirectToView( 'workflowlist', array( $groupID ) );
    else
        return $Module->redirectToView( 'grouplist' );
}
// Remove events which are to be deleted
else if ( $http->hasPostVariable( "DeleteButton" ) )
{
    if ( $canStore )
        $workflow->store( $event_list );
}
// Add new workflow event
else if ( $http->hasPostVariable( "NewButton" ) )
{
    $new_event = eZWorkflowEvent::create( $WorkflowID, $cur_type );
    $new_event_type = $new_event->eventType();
    $db = eZDB::instance();
    $db->begin();

    if ($canStore)
        $workflow->store( $event_list );

    $new_event_type->initializeEvent( $new_event );
    $new_event->store();

    $db->commit();
    $event_list[] = $new_event;
}
else if ( $canStore )
{
    $workflow->store( $event_list );
}

$Module->setTitle( ezpI18n::translate( 'kernel/workflow', 'Edit workflow' ) . ' ' . $workflow->attribute( "name" ) );

// Template handling

$tpl = eZTemplate::factory();

$res = eZTemplateDesignResource::instance();

$res->setKeys( array( array( "workflow", $workflow->attribute( "id" ) ) ) );

if ( isset( $GLOBALS['eZWaitUntilDateSelectedClass'] ) )
    $tpl->setVariable( "selectedClass", $GLOBALS['eZWaitUntilDateSelectedClass'] );

$tpl->setVariable( "http", $http );
$tpl->setVariable( "can_store", $canStore );
$tpl->setVariable( "require_fixup", $requireFixup );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "workflow", $workflow );
$tpl->setVariable( "event_list", $event_list );
$tpl->setVariable( "workflow_type_list", $type_list );
$tpl->setVariable( "workflow_type", $cur_type );
$tpl->setVariable( 'validation', $validation );
if ( isset( $GroupID ) )
{
    $tpl->setVariable( "group_id", $GroupID );
}

$Result = array();
$Result['content'] = $tpl->fetch( "design:workflow/edit.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::translate( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezpI18n::translate( 'kernel/workflow', 'Edit' ),
                                'url' => false ) );

?>

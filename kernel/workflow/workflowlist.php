<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
$WorkflowGroupID = null;
if ( isset( $Params["GroupID"] ) )
    $WorkflowGroupID = $Params["GroupID"];

// //include_once( 'lib/ezutils/classes/ezexecutionstack.php' );
// $execStack = eZExecutionStack::instance();
// $execStack->clear();
// $execStack->addEntry( $Module->functionURI( 'list' ),
//                       $Module->attribute( 'name' ), 'list' );

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'NewWorkflowButton' ) )
{
    if ( $http->hasPostVariable( "CurrentGroupID" ) )
        $GroupID = $http->postVariable( "CurrentGroupID" );
    if ( $http->hasPostVariable( "CurrentGroupName" ) )
        $GroupName = $http->postVariable( "CurrentGroupName" );
    $params = array( null, $GroupID, $GroupName );
    $Module->run( 'edit', $params );
    return;
}

if ( $http->hasPostVariable( 'DeleteButton' ) and
     $http->hasPostVariable( 'Workflow_id_checked' ) )
{
    if ( $http->hasPostVariable( 'CurrentGroupID' ) )
    {
        // If CurrentGroupID variable exist, delete in that group only:
        $groupID = $http->postVariable( 'CurrentGroupID' );
        $workflowIDs = $http->postVariable( 'Workflow_id_checked' );
        foreach ( $workflowIDs as $workflowID )
        {
            // for all workflows which are tagged for deleting:
            $workflow = eZWorkflow::fetch( $workflowID );
            if ( $workflow )
            {
                $workflowInGroups = $workflow->attribute( 'ingroup_list' );
                if ( count( $workflowInGroups ) == 1 )
                {
                    //remove entry from eztrigger table also, if it exists there.
                    eZTrigger::removeTriggerForWorkflow( $workflowID );

                    // if there is only one group which the workflow belongs to, delete (=disable) it:
                    eZWorkflow::setIsEnabled( false, $workflowID );
                }
                else
                {
                    // if there is more than 1 group, remove only from the group:
                    eZWorkflowFunctions::removeGroup( $workflowID, 0, array( $groupID ) );
                }

            }
            else
            {
                // just for sure :-)
                eZWorkflow::setIsEnabled( false, $workflowID );
            }
        }
    }
    else
    {
        // if there is no CurrentGroupID variable, disable every group in variable Workflow_id_checked:
        eZWorkflow::setIsEnabled( false, $http->postVariable( 'Workflow_id_checked' ) );
    }
}

if ( $http->hasPostVariable( 'DeleteButton' ) and
     $http->hasPostVariable( 'Temp_Workflow_id_checked' ) )
{
    $checkedIDs = $http->postVariable( 'Temp_Workflow_id_checked' );
    foreach ( $checkedIDs as $checkedID )
    {
        eZWorkflow::removeWorkflow( $checkedID, 1 );
        eZWorkflowGroupLink::removeWorkflowMembers( $checkedID, 1 );
    }
}

/*$workflows = eZWorkflow::fetchList();
$workflowList = array();
foreach( array_keys( $workflows ) as $workflowID )
{
    $workflow = $workflows[$workflowID];
    $workflowList[$workflow->attribute( 'id' )] = $workflow;
}
*/
$user = eZUser::currentUser();

$list_in_group = eZWorkflowGroupLink::fetchWorkflowList( 0, $WorkflowGroupID, $asObject = true);

$workflow_list = eZWorkflow::fetchList( );

$list = array();
foreach( $workflow_list as $workflow )
{
    foreach( $list_in_group as $inGroup )
    {
        if ( $workflow->attribute( 'id' ) === $inGroup->attribute( 'workflow_id' ) )
        {
            $list[] = $workflow;
        }
    }
}

$templist_in_group = eZWorkflowGroupLink::fetchWorkflowList( 1, $WorkflowGroupID, $asObject = true);
$tempworkflow_list = eZWorkflow::fetchList( 1 );

$temp_list =array();
foreach( $tempworkflow_list as $tmpWorkflow )
{
    foreach ( $templist_in_group as $tmpInGroup )
    {
        $id =  $tmpWorkflow->attribute("id");
        $workflow_id =  $tmpInGroup->attribute("workflow_id");
        if ( $tmpWorkflow->attribute( 'id' ) === $tmpWorkflow->attribute( 'workflow_id' ) )
        {
            $temp_list[] = $tmpWorkflow;
        }
    }
}

$Module->setTitle( ezi18n( 'kernel/workflow', 'Workflow list of group' ) . ' ' . $WorkflowGroupID );

$WorkflowgroupInfo =  eZWorkflowGroup::fetch( $WorkflowGroupID );
if ( !$WorkflowgroupInfo )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

require_once( 'kernel/common/template.php' );
$tpl = templateInit();
$tpl->setVariable( "temp_workflow_list", $temp_list );
$tpl->setVariable( "group_id", $WorkflowGroupID );
$WorkflowGroupName = $WorkflowgroupInfo->attribute("name");
$tpl->setVariable( "group", $WorkflowgroupInfo );
$tpl->setVariable( "group_name", $WorkflowGroupName );
$tpl->setVariable( 'workflow_list', $list );
$tpl->setVariable( 'module', $Module );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:workflow/workflowlist.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/workflow', 'List' ),
                                'url' => false ) );
?>

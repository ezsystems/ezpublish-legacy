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


include_once( 'kernel/classes/ezworkflow.php' );
include_once( 'kernel/classes/ezworkflowgroup.php' );
include_once( "kernel/classes/ezworkflowgrouplink.php" );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

$Module =& $Params['Module'];
$WorkflowGroupID = null;
if ( isset( $Params["GroupID"] ) )
    $WorkflowGroupID =& $Params["GroupID"];

// include_once( 'lib/ezutils/classes/ezexecutionstack.php' );
// $execStack =& eZExecutionStack::instance();
// $execStack->clear();
// $execStack->addEntry( $Module->functionURI( 'list' ),
//                       $Module->attribute( 'name' ), 'list' );

$http =& eZHTTPTool::instance();

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
    eZWorkflow::setIsEnabled( false, $http->postVariable( 'Workflow_id_checked' ) );
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

/*$workflows =& eZWorkflow::fetchList();
$workflowList = array();
foreach( array_keys( $workflows ) as $workflowID )
{
    $workflow =& $workflows[$workflowID];
    $workflowList[$workflow->attribute( 'id' )] =& $workflow;
}
*/
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
$user =& eZUser::currentUser();

$list_in_group = & eZWorkflowGroupLink::fetchWorkflowList( 0, $WorkflowGroupID, $asObject = true);
$workflow_list = & eZWorkflow::fetchList( );

$list = array();
for ( $i=0;$i<count( $workflow_list );$i++ )
{
    for ( $j=0;$j<count( $list_in_group );$j++ )
    {
        $id =  $workflow_list[$i]->attribute("id");
        $workflow_id =  $list_in_group[$j]->attribute("workflow_id");
        if ( $id === $workflow_id )
        {
            $list[] =& $workflow_list[$i];
        }
    }
}

$templist_in_group = & eZWorkflowGroupLink::fetchWorkflowList( 1, $WorkflowGroupID, $asObject = true);
$tempworkflow_list = & eZWorkflow::fetchList( 1 );

$temp_list =array();
for ( $i=0;$i<count( $tempworkflow_list );$i++ )
{
    for ( $j=0;$j<count( $templist_in_group );$j++ )
    {
        $id =  $tempworkflow_list[$i]->attribute("id");
        $workflow_id =  $templist_in_group[$j]->attribute("workflow_id");
        if ( $id === $workflow_id )
        {
            $temp_list[] =& $tempworkflow_list[$i];
        }
    }
}

$Module->setTitle( ezi18n( 'kernel/workflow', 'Workflow list of group' ) . ' ' . $WorkflowGroupID );

include_once( 'kernel/common/template.php' );
$tpl =& templateInit();
$tpl->setVariable( "temp_workflow_list", $temp_list );
$tpl->setVariable( "group_id", $WorkflowGroupID );
$WorkflowgroupInfo = & eZWorkflowGroup::fetch( $WorkflowGroupID );
$WorkflowGroupName = $WorkflowgroupInfo->attribute("name");
$tpl->setVariable( "group", $WorkflowgroupInfo );
$tpl->setVariable( "group_name", $WorkflowGroupName );
$tpl->setVariable( 'workflow_list', $list );
$tpl->setVariable( 'module', $Module );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:workflow/workflowlist.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/workflow', 'List' ),
                                'url' => false ) );
?>

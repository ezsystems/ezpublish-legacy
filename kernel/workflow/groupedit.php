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

include_once( "kernel/classes/ezworkflowgroup.php" );
include_once( "kernel/classes/ezworkflowgrouplink.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$Module =& $Params["Module"];
if ( isset( $Params["WorkflowGroupID"] ) )
    $WorkflowGroupID = $Params["WorkflowGroupID"];
else
    $WorkflowGroupID = false;

// include_once( "lib/ezutils/classes/ezexecutionstack.php" );
// $execStack =& eZExecutionStack::instance();
// $execStack->addEntry( $Module->functionURI( "groupedit" ) . "/" . $WorkflowGroupID,
//                       $Module->attribute( "name" ), "groupedit" );

if ( is_numeric( $WorkflowGroupID ) )
{
    $workflowGroup =& eZWorkflowGroup::fetch( $WorkflowGroupID, true );
}
else
{
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $workflowGroup =& eZWorkflowGroup::create( $user_id );
    $workflowGroup->setAttribute( "name", "New WorkflowGroup" );
    $workflowGroup->store();
    $WorkflowGroupID = $workflowGroup->attribute( "id" );
    $Module->redirectTo( $Module->functionURI( "groupedit" ) . "/" . $WorkflowGroupID );
    return;
}

//$assignedWorkflows =& $workflowGroup->fetchWorkflowList();
//$isRemoveTried = false;

$http =& eZHttpTool::instance();
if ( $http->hasPostVariable( "DiscardButton" ) )
{
    $Module->redirectTo( $Module->functionURI( "grouplist" ) );
    return;
    // $isRemoveTried = true;
//     $workflowGroup->remove();
//     include_once( "lib/ezutils/classes/ezexecutionstack.php" );
//     $execStack =& eZExecutionStack::instance();
//     $execStack->pop();
//     $uri = $execStack->peek( "uri" );
//     $Module->redirectTo( $uri == "" ? $Module->functionURI( "grouplist" ) : $uri );
//     return;
}

// Validate input
include_once( "lib/ezutils/classes/ezinputvalidator.php" );
$requireFixup = false;

// Apply HTTP POST variables
eZHttpPersistence::fetch( "WorkflowGroup", eZWorkflowGroup::definition(),
                          $workflowGroup, $http, false );

// Set new modification date
include_once( "lib/ezlocale/classes/ezdatetime.php" );
$date_time = eZDateTime::currentTimeStamp();
$workflowGroup->setAttribute( "modified", $date_time );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
$user =& eZUser::currentUser();
$user_id = $user->attribute( "contentobject_id" );
$workflowGroup->setAttribute( "modifier_id", $user_id );

// Discard existing events, workflow version 1 and store version 0
if ( $http->hasPostVariable( "StoreButton" ) )
{
    if ( $http->hasPostVariable( "WorkflowGroup_name" ) )
    {
        $name = $http->postVariable( "WorkflowGroup_name" );
    }
    $workflowGroup->setAttribute( "name", $name );
    // Set new modification date
    include_once( "lib/ezlocale/classes/ezdatetime.php" );
    $date_time = eZDateTime::currentTimeStamp();
    $workflowGroup->setAttribute( "modified", $date_time );
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $workflowGroup->setAttribute( "modifier_id", $user_id );
    $workflowGroup->store();
    $Module->redirectTo( $Module->functionURI( 'grouplist' ) );
    return;
    /*
    include_once( "lib/ezutils/classes/ezexecutionstack.php" );
    $execStack =& eZExecutionStack::instance();
    $execStack->pop();
    $uri = $execStack->peek( "uri" );
    $Module->redirectTo( $uri == "" ? $Module->functionURI( "grouplist" ) : $uri );
    return;*/
}

$Module->setTitle( ezi18n( 'kernel/workflow', 'Edit workflow group' ) . ' ' .
                   $workflowGroup->attribute( "name" ) );

// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( "workflow_group", $workflowGroup->attribute( "id" ) ) ) ); // WorkflowGroup ID

$tpl->setVariable( "http", $http );
$tpl->setVariable( "can_store", $canStore );
$tpl->setVariable( "require_fixup", $requireFixup );
$tpl->setVariable( "is_remove_tried", $isRemoveTried );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "workflow_group", $workflowGroup );
//$tpl->setVariable( "assigned_workflow_list", $assignedWorkflows );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:workflow/groupedit.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/workflow', 'Group edit' ),
                                'url' => false ) );


?>

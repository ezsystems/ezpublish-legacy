<?php
//
// Created on: <05-Sep-2002 09:46:31 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$Module =& $Params["Module"];

$http =& eZHTTPTool::instance();

include_once( "kernel/classes/eztask.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

$currentUser =& eZUser::currentUser();
$currentUserID =& $currentUser->attribute( 'contentobject_id' );

$TaskID = 0;
if ( isset( $Params['TaskID'] ) and
     is_numeric( $Params['TaskID'] ) )
    $TaskID =& $Params['TaskID'];

if ( $Module->isCurrentAction( 'CancelTask' ) and
     ( $Module->hasActionParameter( 'SelectedIDList' ) or
       $TaskID != 0 ) )
{
    $selectedTasks = $TaskID;
    if ( $Module->hasActionParameter( 'SelectedIDList' ) )
        $selectedTasks = $Module->actionParameter( 'SelectedIDList' );
    eZTask::updateTaskStatus( $selectedTasks, EZ_TASK_STATUS_CANCELLED, $currentUserID );
}
if ( $Module->isCurrentAction( 'CloseTask' ) and
     ( $Module->hasActionParameter( 'SelectedIDList' ) or
       $TaskID != 0 ) )
{
    $selectedTasks = $TaskID;
    if ( $Module->hasActionParameter( 'SelectedIDList' ) )
        $selectedTasks = $Module->actionParameter( 'SelectedIDList' );
    eZTask::updateTaskStatus( $selectedTasks, EZ_TASK_STATUS_CLOSED, $currentUserID );
}

if ( $Module->isCurrentAction( 'NewMessage' ) and
     ( $Module->hasActionParameter( 'SelectedIDList' ) or
       $TaskID != 0 ) and
     $Module->hasActionParameter( 'ClassID' ) )
{
    $classID = $Module->actionParameter( 'ClassID' );
    $selectedTasks = $Module->actionParameter( 'SelectedIDList' );
    $taskID = $TaskID;
    if ( $Module->hasActionParameter( 'SelectedIDList' ) )
    {
        $selectedTasks = $Module->actionParameter( 'SelectedIDList' );
        $taskID = $selectedTasks[0];
    }
    return $Module->run( 'message', array( $taskID ),
                         array( 'ClassID' => $classID ) );
}

if ( $Module->isCurrentAction( 'NewTask' ) )
{
    return $Module->run( 'edit', array( 'TaskType' => EZ_TASK_TYPE_TASK ) );
}

if ( $Module->isCurrentAction( 'NewAssignment' ) )
{
    return $Module->run( 'edit', array( 'TaskType' => EZ_TASK_TYPE_ASSIGNMENT ) );
}

$incomingTaskList =& eZTask::fetchList( $currentUserID, $TaskID, true );
$outgoingTaskList =& eZTask::fetchList( $currentUserID, $TaskID, false );

$task = null;
if ( is_numeric( $TaskID ) and $TaskID > 0 )
    $task = eZTask::fetch( $TaskID );

include_once( 'lib/ezutils/classes/ezini.php' );
$ini =& eZINI::instance();
$classGroups = $ini->variableArray( 'TaskSettings', 'ContentClassGroups' );
include_once( 'kernel/classes/ezcontentclassclassgroup.php' );
$classList =& eZContentClassClassGroup::fetchClassListByGroups( 0, $classGroups );

// include_once( 'kernel/classes/ezcontentclass.php' );
// $classList =& eZContentClass::fetchList();

include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$Module->setTitle( 'Task view' );

$ini =& eZINI::instance();

$taskType = 0;
$connectionType = 0;
if ( get_class( $task ) == 'eztask' )
{
    $taskType = $task->attribute( 'task_type' );
    $connectionType = $task->attribute( 'connection_type' );
}

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'task', $TaskID ),
                      array( 'task_type', $taskType ),
                      array( 'connection_type', $connectionType ) ) );

$tpl->setVariable( "incoming_task_list", $incomingTaskList );
$tpl->setVariable( "outgoing_task_list", $outgoingTaskList );
$tpl->setVariable( 'task', $task );
$tpl->setVariable( 'class_list', $classList );
$tpl->setVariable( 'task_id', $TaskID );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'view_type', $ini->variable( 'TaskSettings', 'MessageViewMode' ) );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:task/view.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/task', 'Task list' ) ) );

?>

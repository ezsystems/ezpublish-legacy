<?php
//
// Created on: <05-Sep-2002 09:46:31 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

$Module =& $Params['Module'];

$http =& eZHTTPTool::instance();

include_once( 'kernel/classes/eztask.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

$currentUser =& eZUser::currentUser();
$currentUserID =& $currentUser->attribute( 'contentobject_id' );

if ( isset( $Params['TaskID'] ) and
     is_numeric( $Params['TaskID'] ) )
{
    $TaskID =& $Params['TaskID'];
    $task =& eZTask::fetch( $TaskID );
    if ( $task->attribute( 'status' ) != EZ_TASK_STATUS_TEMPORARY )
    {
        $errorModule =& eZModule::exists( "error" );
        return $errorModule->run( "403", array() );
    }
}
else
{
    $Parameters = $Params['Parameters'];
    if ( isset( $Parameters['TaskType'] ) and
         $Parameters['TaskType'] == EZ_TASK_TYPE_ASSIGNMENT )
        $task = eZTask::createAssignment( $currentUserID );
    else
        $task = eZTask::createTask( $currentUserID );
    if ( $http->hasPostVariable( 'Task_id_checked' ) )
    {
        $selectedTasks = $http->postVariable( 'Task_id_checked' );
        if ( count( $selectedTasks ) > 0 )
        {
            $task->setAttribute( 'parent_task_type', EZ_TASK_PARENT_TYPE_TASK );
            $task->setAttribute( 'parent_task_id', $selectedTasks[0] );
        }
    }
    $task->store();
    $TaskID =& $task->attribute( 'id' );
//     return $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $TaskID );
    $http->setSessionVariable( "BrowseFromPage", $Module->functionURI( 'edit' ) . "/$TaskID" );
    $http->removeSessionVariable( "CustomBrowseActionAttributeID" );

    $http->setSessionVariable( "BrowseActionName", "SelectTaskReceiver" );
    $http->setSessionVariable( "BrowseReturnType", "ContentObjectID" );

    $NodeID = 5;
    $contentModule =& eZModule::exists( "content" );
    return $Module->redirectTo( $contentModule->functionURI( "browse" ) . "/" . $NodeID . "/" . $ObjectID . "/" . $EditVersion );
}

if ( $http->hasPostVariable( 'SendTaskButton' ) )
{
    if ( $task->attribute( 'receiver_id' ) > 0 and
         ( $task->attribute( 'task_type' ) != EZ_TASK_TYPE_ASSIGNMENT or
           $task->attribute( 'object_id' ) > 0 ) )
    {
        $task->setAttribute( 'status', EZ_TASK_STATUS_OPEN );
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $task->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
        $task->store();
        return $Module->redirectTo( $Module->functionURI( 'view' ) );
    }
}

if ( $http->hasPostVariable( 'DiscardTaskButton' ) )
{
    $task->remove();
    return $Module->redirectTo( $Module->functionURI( 'view' ) );
}

$storeTask = false;

if ( $http->hasPostVariable( 'Task_access_type' ) )
{
    $task->setAttribute( 'access_type', $http->postVariable( 'Task_access_type' ) );
    $storeTask = true;
}

if ( $http->hasPostVariable( 'Task_object_type' ) )
{
    $task->setAttribute( 'object_type', $http->postVariable( 'Task_object_type' ) );
    $storeTask = true;
}

if ( $http->hasPostVariable( 'SetAssignmentButton' ) )
{
    $task->setAttribute( 'task_type', EZ_TASK_TYPE_ASSIGNMENT );
    $storeTask = true;
}
if ( $http->hasPostVariable( 'SetTaskButton' ) )
{
    $task->setAttribute( 'task_type', EZ_TASK_TYPE_TASK );
    $storeTask = true;
}

if ( $http->hasPostVariable( 'BrowseActionName' ) and
     $http->postVariable( 'BrowseActionName' ) == 'SelectTaskReceiver' and
     $http->hasPostVariable( 'SelectedObjectIDArray' ) )
{
    $objectArray = $http->postVariable( 'SelectedObjectIDArray' );
    if ( count( $objectArray ) > 0 )
    {
        $objectID = $objectArray[0];
        $task->setAttribute( 'receiver_id', $objectID );
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $task->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
        $storeTask = true;
    }
}

if ( $http->hasPostVariable( 'BrowseActionName' ) and
     $http->postVariable( 'BrowseActionName' ) == 'SelectAssignmentObject' and
     $http->hasPostVariable( 'SelectedObjectIDArray' ) )
{
    $objectArray = $http->postVariable( 'SelectedObjectIDArray' );
    if ( count( $objectArray ) > 0 )
    {
        $objectID = $objectArray[0];
        $task->setAttribute( 'object_id', $objectID );
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $task->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
        $storeTask = true;
    }
}

if ( $storeTask )
    $task->store();


if ( ( $task->attribute( 'status' ) == EZ_TASK_STATUS_TEMPORARY and
       $task->attribute( 'receiver_id' ) == 0 ) or
     $http->hasPostVariable( 'SelectReceiverButton' ) )
{
    $http->setSessionVariable( "BrowseFromPage", $Module->functionURI( 'edit' ) . "/$TaskID" );
    $http->removeSessionVariable( "CustomBrowseActionAttributeID" );

    $http->setSessionVariable( "BrowseActionName", "SelectTaskReceiver" );
    $http->setSessionVariable( "BrowseReturnType", "ContentObjectID" );

    $NodeID = 5;
    $contentModule =& eZModule::exists( "content" );
    return $Module->redirectTo( $contentModule->functionURI( "browse" ) . "/" . $NodeID . "/" . $ObjectID . "/" . $EditVersion );
}

if ( $http->hasPostVariable( 'SelectObjectButton' ) )
{
    $http->setSessionVariable( "BrowseFromPage", $Module->functionURI( 'edit' ) . "/$TaskID" );
    $http->removeSessionVariable( "CustomBrowseActionAttributeID" );

    $http->setSessionVariable( "BrowseActionName", "SelectAssignmentObject" );
    $http->setSessionVariable( "BrowseReturnType", "ContentObjectID" );

    $NodeID = 2;
    $contentModule =& eZModule::exists( "content" );
    return $Module->redirectTo( $contentModule->functionURI( "browse" ) . "/" . $NodeID . "/" . $ObjectID . "/" . $EditVersion );
}

$Module->setTitle( 'Creating new task' );

include_once( 'kernel/common/template.php' );
$tpl =& templateInit();

$tpl->setVariable( 'task', $task );
$tpl->setVariable( 'module', $Module );

$Result =& $tpl->fetch( 'design:task/edit.tpl' );

?>

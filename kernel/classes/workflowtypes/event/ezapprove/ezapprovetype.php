<?php
//
// Definition of eZApproveType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

/*!
  \class eZApproveType ezapprovetype.php
  \brief Event type for user approvals

*/

include_once( "kernel/classes/ezworkflowtype.php" );
include_once( 'kernel/classes/eztask.php' );

define( "EZ_WORKFLOW_TYPE_APPROVE_ID", "ezapprove" );

define( "EZ_APPROVE_TYPE_TASK_NOT_CREATED", 0 );
define( "EZ_APPROVE_TYPE_TASK_CREATED", 1 );

class eZApproveType extends eZWorkflowEventType
{
    function eZApproveType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_APPROVE_ID, "Approve" );
    }

    function execute( &$process, &$event )
    {
        eZDebug::writeNotice( $process, 'process');

        if( $process->attribute( 'event_state') == EZ_APPROVE_TYPE_TASK_NOT_CREATED )
        {
            $this->createTask( $process, $event );
            $this->setInformation( "We are going to create task" );
            $process->setAttribute( 'event_state', EZ_APPROVE_TYPE_TASK_CREATED );
            $process->store();
            eZDebug::writeNotice( $this, 'aprove execute');
            return EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT;

        }
        elseif ( $process->attribute( 'event_state') == EZ_APPROVE_TYPE_TASK_CREATED )
        {
            $this->setInformation( "we are checking task now" );
            eZDebug::writeNotice( $event, 'check task' );
            return $this->checkTask(  $process, $event );
        }
    }

    function initializeEvent( &$event )
    {
    }

    function fetchHTTPInput( &$http, $base, &$event )
    {
    }

    function createTask( &$process, &$event )
    {
        $user =& eZUser::currentUser();
        $task =& eZTask::createAssignment( $user->id() );
        $task->setAttribute( 'object_id', $process->attribute( 'content_id' ));
        if ( $event->attribute( 'data_int1' ) != null && $event->attribute( 'data_int1' ) != 0 )
        {
            $task->setAttribute( 'receiver_id', $event->attribute( 'data_int1' ) );
        }
        else
        {
            $task->setAttribute( 'receiver_id', 8 );
        }
        $task->setAttribute( 'status',  EZ_TASK_STATUS_OPEN  );
        $task->store();
        $db = & eZDb::instance();
        $db->query( 'insert into ezapprovetasks( workflow_process_id,
                                                      task_id )
                                              values(' . $process->attribute( 'id' ) .','. $task->attribute( 'id' ) .' ) '
                    );
    }
    function checkTask( &$process, &$event )
    {
        $db = & eZDb::instance();
        $taskResult = $db->arrayQuery( 'select workflow_process_id, task_id from ezapprovetasks where workflow_process_id = ' . $process->attribute( 'id' )  );
        $taskID = $taskResult[0]['task_id'];
        $task =& eZTask::fetch( $taskID );
        if ( $task->attribute( 'status' ) == EZ_TASK_STATUS_OPEN )
        {
            eZDebug::writeNotice( $event, 'task opened ' );

            return EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT;
        }
        else if (  $task->attribute( 'status' ) == EZ_TASK_STATUS_CLOSED )
        {
            eZDebug::writeNotice( $event, 'task ACCEPTED' );
            return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
        }
        else if ( $task->attribute( 'status' ) == EZ_TASK_STATUS_CANCELLED )
        {
            eZDebug::writeNotice( $event, 'task CANCELED' );
            return EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_CANCELLED;
        }else
        {
            eZDebug::writeNotice( $event, 'task CANCELED no status ' );
            return EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_CANCELLED;
        }
    }
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_APPROVE_ID, "ezapprovetype" );

?>

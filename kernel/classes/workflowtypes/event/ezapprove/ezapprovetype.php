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

    function &attributeDecoder( &$event, $attr )
    {
        switch ( $attr )
        {
            case 'selected_sections':
            {
                $sections = explode( ',', $event->attribute( 'data_text1' ) );
                var_dump( $sections );
                return $sections;
            }break;
            case 'selected_users':
            {
                $users =  array( $event->attribute( 'data_int1' ) );
                return $users;
            }break;
            case 'selected_usergroups':
            {
                $groups = explode( ',', $event->attribute( 'data_text2' ) );
                return $groups;
            }
        }
        return null;
    }

    function typeFunctionalAttributes( )
    {
        return array( 'selected_sections',
                      'selected_users',
                      'selected_usergroups' );

    }

    
    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'sections':
            {
                include_once( 'kernel/classes/ezsection.php' );
                $sections =& eZSection::fetchList( false );
                foreach ( array_keys( $sections ) as $key )
                {
                    $section =& $sections[$key];
                    $section['Name'] = $section['name'];
                    $section['value'] = $section['id'];
                }
                return $sections;
            }break;
            case 'users':
            {
                include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                $users =& eZPersistentObject::fetchObjectList( eZUser::definition(), array( 'contentobject_id', 'login' ), null,null,null,false );
                eZDebug::writeDebug( $users, "attr" );
                foreach ( array_keys( $users ) as $key )
                {
                    $user =& $users[$key];
                    $user['Name'] = $user['login'];
                    $user['value'] = $user['contentobject_id'];
                }
                return $users;
                
            }break;
            case 'usergroups':
            {
                $groups =& eZPersistentObject::fetchObjectList( eZContentObject::definition(), array( 'id', 'name' ), array( 'contentclass_id' => 3 ),null,null,false );
                foreach ( array_keys( $groups ) as $key )
                {
                    $group =& $groups[$key];
                    $group['Name'] = $group['name'];
                    $group['value'] = $group['id'];
                }
                return $groups;
            }
        }
        return eZWorkflowEventType::attribute( $attr );
    }
    function hasAttribute( $attr )
    {
        return in_array( $attr, array( 'sections',
                                       'users',
                                       'usergroups' ) ) || eZWorkflowEventType::hasAttribute( $attr );
    }

    function execute( &$process, &$event )
    {
        $parameters = $process->attribute( 'parameter_list' );
//        var_dump( $parameters );
        $object =& eZContentObject::fetch( $parameters['object_id'] );
        $user =& eZUser::currentUser(); //fetch( $parameters['user_id'] );
        $userGroups = $user->attribute( 'groups' );

        $workflowSections = explode( ',', $event->attribute( 'data_text1' ) );
        $workflowGroups = explode( ',', $event->attribute( 'data_text2' ) );
        $editor = $event->attribute( 'data_int1' );

        if ( $user->id() != $editor &&
             count( array_intersect( $userGroups, $workflowGroups ) ) == 0  &&
             in_array( $object->attribute( 'section_id'), $workflowSections ) )
        {

            if( $process->attribute( 'event_state') == EZ_APPROVE_TYPE_TASK_NOT_CREATED )
            {
                $this->createTask( $process, $event, $user->id(), $object->attribute( 'id' ), $editor );
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
        else
        {
            eZDebug::writeDebug( $workflowSections , "we are not going to create task " . $object->attribute( 'section_id') );
            eZDebug::writeDebug( $userGroups, "we are not going to create task" );
            eZDebug::writeDebug( $workflowGroups,  "we are not going to create task" );
            eZDebug::writeDebug( $user->id(), "we are not going to create task $editor "  );
            return EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_DONE;
        }
    }

    function initializeEvent( &$event )
    {
    }

    function fetchHTTPInput( &$http, $base, &$event )
    {
        $editorVar = $base . "_event_ezapprove_editor_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $editorVar ) )
        {
            $editorID = $http->postVariable( $editorVar );
            $editorID = $editorID[0];
            $event->setAttribute( "data_int1", $editorID );
        }
        $userGroupsVar = $base . "_event_ezapprove_groups_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $userGroupsVar ) )
        {
            $userGroupsArray = $http->postVariable( $userGroupsVar );
            if ( in_array( '-1', $userGroupsArray ) )
            {
                $userGroupsArray = array( -1 );
            }


            $userGroupsString = implode( ',', $userGroupsArray );
            $event->setAttribute( "data_text2", $userGroupsString );
        }

        $sectionsVar = $base . "_event_ezapprove_section_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $sectionsVar ) )
        {
            $sectionsArray = $http->postVariable( $sectionsVar );
            if ( in_array( '-1', $sectionsArray ) )
            {
                $sectionsArray = array( -1 );
            }
            
            $sectionsString = implode( ',', $sectionsArray );
//            $userGroupList = explode( ',', $userGroupsString );
            $event->setAttribute( "data_text1", $sectionsString );
        }

    }

    function createTask( &$process, &$event, $userID, $contentobjectID, $editor )
    {
        $task =& eZTask::createAssignment( $userID );
        $task->setAttribute( 'object_id',  $contentobjectID );
        if ( $editor != null )
        {
            $task->setAttribute( 'receiver_id', $editor );
        }
        else
        {
            $task->setAttribute( 'receiver_id', 14 );
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

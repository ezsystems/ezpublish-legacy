<?php
//
// Definition of eZTask class
//
// Created on: <05-Sep-2002 08:26:49 amos>
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

/*! \file eztask.php
*/

/*!
  \class eZTask eztask.php
  \brief The class eZTask does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

define( 'EZ_TASK_TYPE_TASK', 1 );
define( 'EZ_TASK_TYPE_ASSIGNMENT', 2 );

define( 'EZ_TASK_STATUS_TEMPORARY', 1 );
define( 'EZ_TASK_STATUS_OPEN', 2 );
define( 'EZ_TASK_STATUS_CLOSED', 3 );
define( 'EZ_TASK_STATUS_CANCELLED', 4 );

define( 'EZ_TASK_CONNECTION_TYPE_USER', 1 );
define( 'EZ_TASK_CONNECTION_TYPE_SESSION', 2 );

define( 'EZ_TASK_PARENT_TYPE_NONE', 0 );
define( 'EZ_TASK_PARENT_TYPE_TASK', 1 );
define( 'EZ_TASK_PARENT_TYPE_WORKFLOW_PROCESS', 2 );

define( 'EZ_TASK_ACCESS_TYPE_NONE', 0 );
define( 'EZ_TASK_ACCESS_TYPE_READ', 1 );
define( 'EZ_TASK_ACCESS_TYPE_READ_WRITE', 2 );

define( 'EZ_TASK_OBJECT_TYPE_NONE', 0 );
define( 'EZ_TASK_OBJECT_TYPE_CONTENTOBJECT', 1 );
define( 'EZ_TASK_OBJECT_TYPE_CONTENTCLASS', 2 );
define( 'EZ_TASK_OBJECT_TYPE_WORKFLOW', 3 );
define( 'EZ_TASK_OBJECT_TYPE_ROLE', 4 );

define( 'EZ_TASK_MESSAGE_UNFETCHED', 0 );

class eZTask extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZTask( $row )
    {
        $this->eZPersistentObject( $row );
        $this->Messages = EZ_TASK_MESSAGE_UNFETCHED;
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => 'ID',
                                         'task_type' => 'TaskType',
                                         'status' => 'Status',
                                         'connection_type' => 'ConnectionType',
                                         'session_hash' => 'SessionHash',
                                         'creator_id' => 'CreatorID',
                                         'receiver_id' => 'ReceiverID',
                                         'parent_task_type' => 'ParentTaskType',
                                         'parent_task_id' => 'ParentTaskID',
                                         'access_type' => 'AccessType',
                                         'object_type' => 'ObjectType',
                                         'object_id' => 'ObjectID',
                                         'created' => 'Created',
                                         'modified' => 'Modified' ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZTask',
                      'sort' => array( 'connection_type' => 'asc',
                                       'task_type' => 'asc',
                                       'modified' => 'desc' ),
                      'name' => 'eztask' );
    }

    function &createTask( $creatorID, $receiver = 0, $connectionType = EZ_TASK_CONNECTION_TYPE_USER )
    {
        return eZTask::create( EZ_TASK_TYPE_TASK, $creatorID, $receiver, $connectionType );
    }

    function &createAssignment( $creatorID, $receiver = 0, $connectionType = EZ_TASK_CONNECTION_TYPE_USER )
    {
        return eZTask::create( EZ_TASK_TYPE_ASSIGNMENT, $creatorID, $receiver, $connectionType );
    }

    function &create( $taskType, $creatorID, $receiver = 0, $connectionType = EZ_TASK_CONNECTION_TYPE_USER )
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $date_time = eZDateTime::currentTimeStamp();
        $session_hash = '';
        $receiver_id = 0;
        if ( $connectionType == EZ_TASK_CONNECTION_TYPE_SESSION )
            $session_hash = $receiver;
        else
            $receiver_id = $receiver;
        $row = array(
            'id' => null,
            'task_type' => $taskType,
            'status' => EZ_TASK_STATUS_TEMPORARY,
            'connection_type' => $connectionType,
            'session_hash' => $session_hash,
            'creator_id' => $creatorID,
            'receiver_id' => $receiver_id,
            'parent_task_type' => EZ_TASK_PARENT_TYPE_NONE,
            'parent_task_id' => 0,
            'access_type' => EZ_TASK_ACCESS_TYPE_NONE,
            'object_type' => EZ_TASK_OBJECT_TYPE_NONE,
            'object_id' => 0,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZTask( $row );
    }

    function &fetch( $id, $as_object = true )
    {
        return eZPersistentObject::fetchObject( eZTask::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $as_object );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'creator' or $attr == 'receiver' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function attribute( $attr )
    {
        switch( $attr )
        {
            case 'creator':
            {
                $userID = $this->CreatorID;
            } break;
            case 'receiver':
            {
                $userID = $this->ReceiverID;
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $user =& eZUser::fetch( $userID );
        return $user;
    }

    function &fetchList( $userID, $parent = 0, $incoming = true, $as_object = true )
    {
        $conds = array();
        if ( $incoming )
        {
            $conds['connection_type'] = EZ_TASK_CONNECTION_TYPE_USER;
            $conds['receiver_id'] = $userID;
        }
        else
        {
            $conds['creator_id'] = $userID;
        }
        $conds['parent_task_id'] = $parent;
        return eZPersistentObject::fetchObjectList( eZTask::definition(),
                                                    null, $conds, null, null,
                                                    $as_object );
    }

    function updateTaskStatus( $taskList, $status, $userID )
    {
        eZPersistentObject::updateObjectList( array(
                                                  "definition" => eZTask::definition(),
                                                  "update_fields" => array( "status" => $status ),
                                                  "conditions" => array( "id" => $taskList )
                                                  )
                                              );
    }

    /// \privatesection
    var $ID;
    var $TaskType;
    var $Status;
    var $ConnectionType;
    var $SessionHash;
    var $CreatorID;
    var $ReceiverID;
    var $ParentTaskType;
    var $AccessType;
    var $ObjectType;
    var $ObjectID;
    var $Created;
    var $Modified;
    var $Messages;
}

?>

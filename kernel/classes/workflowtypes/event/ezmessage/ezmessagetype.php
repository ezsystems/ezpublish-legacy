<?php
//
// Definition of eZMessageType class
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
  \class eZMessageType ezmessagetype.php
  \brief Event type for sending messages

*/

include_once( "kernel/classes/ezworkflowtype.php" );
include_once( 'kernel/classes/eztask.php' );
include_once( 'kernel/classes/eztaskmessage.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

define( "EZ_WORKFLOW_TYPE_MESSAGE_ID", "ezmessage" );

class eZMessageType extends eZWorkflowEventType
{
    function eZMessageType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_MESSAGE_ID, "Message" );
    }

    function execute( &$process, &$event )
    {
        $this->setInformation( $event->attribute( "data_text1" ) );
        $user =& eZUser::currentUser();
        $this->sendMessage( $process, $event );

        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
    }
    function sendMessage( &$process, &$event )
    {
        $db = & eZDb::instance();
        $taskResult = $db->arrayQuery( 'select workflow_process_id, task_id from ezapprovetasks where workflow_process_id = ' . $process->attribute( 'id' )  );
        $taskID = $taskResult[0]['task_id'];
        $task =& eZTask::fetch( $taskID );

        $class =& eZContentClass::fetch( 1 );
        $contentObject =& $class->instantiate( $userID, $sectionID );
//        $contentObject =& eZContentObject::create( 1 );
        $creatorType = EZ_TASK_MESSAGE_CREATOR_RECEIVER;
        if ( $event->attribute( 'data_int1' ) != null && $event->attribute( 'data_int1' ) != 0 )
        {
            $recieverID = $event->attribute( 'data_int1' );
        }
        else
        {
            $recieverID = 14;
        }
        $message =& eZTaskMessage::create( $taskID, $creatorType, $recieverID, $contentObject->attribute( 'id' ) );
        $message->store();
        eZDebug::writeNotice( $message,  'message object in message event' );
        $approvedObjectID = $process->attribute( 'content_id' );
        $contentObject->setName( "object $approvedObjectID  approved" );
        $contentObject->store();


    }

    function initializeEvent( &$event )
    {
        $event->setAttribute( "data_text1", "" );
    }

    function fetchHTTPInput( &$http, $base, &$event )
    {
        $message_var = $base . "_event_ezmessage_text_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $message_var ) )
        {
            $message = $http->postVariable( $message_var );
            $event->setAttribute( "data_text1", $message );
        }
    }

}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_MESSAGE_ID, "ezmessagetype" );

?>

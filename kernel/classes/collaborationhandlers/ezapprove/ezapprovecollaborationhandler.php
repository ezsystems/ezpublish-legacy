<?php
//
// Definition of eZApproveCollaborationHandler class
//
// Created on: <23-Jan-2003 11:57:11 amos>
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

/*! \file ezapprovecollaborationhandler.php
*/

/*!
  \class eZApproveCollaborationHandler ezapprovecollaborationhandler.php
  \brief Handles approval communication using the collaboration system

  The handler uses the fields data_int1, data_int2 and data_int3 to store
  information on the contentobject and the approval status.

  - data_int1 - The content object ID
  - data_int2 - The content object version
  - data_int3 - The status of the approval, see defines.

*/

include_once( 'kernel/classes/ezcollaborationitemhandler.php' );
include_once( 'kernel/classes/ezcollaborationitem.php' );
include_once( 'kernel/classes/ezcollaborationitemmessagelink.php' );
include_once( 'kernel/classes/ezcollaborationitemparticipantlink.php' );
include_once( 'kernel/classes/ezcollaborationitemgrouplink.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'kernel/classes/ezcollaborationprofile.php' );
include_once( 'kernel/classes/ezcollaborationsimplemessage.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/common/i18n.php' );

/// Approval message type
define( "EZ_COLLABORATION_MESSAGE_TYPE_APPROVE", 1 );

/// Default status, no approval decision has been made
define( "EZ_COLLABORATION_APPROVE_STATUS_WAITING", 0 );
/// The contentobject was approved and will be published.
define( "EZ_COLLABORATION_APPROVE_STATUS_ACCEPTED", 1 );
/// The contentobject was denied and will be archived.
define( "EZ_COLLABORATION_APPROVE_STATUS_DENIED", 2 );
/// The contentobject was deferred and will be a draft again for reediting.
define( "EZ_COLLABORATION_APPROVE_STATUS_DEFERRED", 3 );

class eZApproveCollaborationHandler extends eZCollaborationItemHandler
{
    /*!
     Initializes the handler
    */
    function eZApproveCollaborationHandler()
    {
        $this->eZCollaborationItemHandler( 'ezapprove',
                                           ezi18n( 'kernel/classes', 'Approval' ),
                                           array( 'use-messages' => true,
                                                  'notification-types' => true,
                                                  'notification-collection-handling' => EZ_COLLABORATION_NOTIFICATION_COLLECTION_PER_PARTICIPATION_ROLE ) );
    }

    /*!
     \reimp
    */
    function title( &$collaborationItem )
    {
        return ezi18n( 'kernel/classes', 'Approval' );
    }

    /*!
     \reimp
    */
    function content( &$collaborationItem )
    {
        return array( "content_object_id" => $collaborationItem->attribute( "data_int1" ),
                      "content_object_version" => $collaborationItem->attribute( "data_int2" ),
                      "approval_status" => $collaborationItem->attribute( "data_int3" ) );
    }

    function notificationParticipantTemplate( $participantRole )
    {
        if ( $participantRole == EZ_COLLABORATION_PARTICIPANT_ROLE_APPROVER )
        {
            return 'approve.tpl';
        }
        else if ( $participantRole == EZ_COLLABORATION_PARTICIPANT_ROLE_AUTHOR )
        {
            return 'author.tpl';
        }
        else
            return false;
    }

    /*!
     \return the content object version object for the collaboration item \a $collaborationItem
    */
    function &contentObjectVersion( &$collaborationItem )
    {
        $contentObjectID = $collaborationItem->contentAttribute( 'content_object_id' );
        $contentObjectVersion = $collaborationItem->contentAttribute( 'content_object_version' );
        return eZContentObjectVersion::fetchVersion( $contentObjectVersion, $contentObjectID );
    }

    /*!
     \reimp
     Updates the last_read for the participant link.
    */
    function readItem( &$collaborationItem )
    {
        $collaborationItem->setLastRead();
    }

    /*!
     \reimp
     \return the number of messages for the approve item.
    */
    function messageCount( &$collaborationItem )
    {
        return eZCollaborationItemMessageLink::fetchItemCount( array( 'item_id' => $collaborationItem->attribute( 'id' ) ) );
    }

    /*!
     \reimp
     \return the number of unread messages for the approve item.
    */
    function unreadMessageCount( &$collaborationItem )
    {
//         $participantID =& eZUser::currentUserID();
//         $participant =& eZCollaborationItemParticipantLink::fetch( $collaborationItem->attribute( 'id' ), $participantID );
        $lastRead = 0;
        $status =& $collaborationItem->attribute( 'user_status' );
        if ( $status )
            $lastRead = $status->attribute( 'last_read' );
        return eZCollaborationItemMessageLink::fetchItemCount( array( 'item_id' => $collaborationItem->attribute( 'id' ),
                                                                      'conditions' => array( 'modified' => array( '>', $lastRead ) ) ) );
    }

    /*!
     \static
     \return the status of the approval collaboration item \a $approvalID.
    */
    function checkApproval( $approvalID )
    {
        $collaborationItem =& eZCollaborationItem::fetch( $approvalID );
        if ( $collaborationItem !== null )
        {
            return $collaborationItem->attribute( 'data_int3' );
        }
        return false;
    }

    /*!
     \static
     \return makes sure the approval item is activated for all participants \a $approvalID.
    */
    function activateApproval( $approvalID )
    {
        $collaborationItem =& eZCollaborationItem::fetch( $approvalID );
        if ( $collaborationItem !== null )
        {
//             eZDebug::writeDebug( $collaborationItem, "reactivating approval $approvalID" );
            $collaborationItem->setAttribute( 'data_int3', EZ_COLLABORATION_APPROVE_STATUS_WAITING );
            $collaborationItem->setAttribute( 'status', EZ_COLLABORATION_STATUS_ACTIVE );
            $timestamp = time();
            $collaborationItem->setAttribute( 'modified', $timestamp );
            $collaborationItem->store();
            $participantList =& eZCollaborationItemParticipantLink::fetchParticipantList( array( 'item_id' => $approvalID ) );
            for ( $i = 0; $i < count( $participantList ); ++$i )
            {
                $participantLink =& $participantList[$i];
                $collaborationItem->setIsActive( true, $participantLink->attribute( 'participant_id' ) );
            }
            return true;
        }
        return false;
    }

    /*!
     Creates a new approval collaboration item which will approve the content object \a $contentObjectID
     with version \a $contentObjectVersion.
     The item will be added to the author \a $authorID and the approver \a $approverID.
     \return the collaboration item.
    */
    function createApproval( $contentObjectID, $contentObjectVersion, $authorID, $approverID )
    {
        $collaborationItem =& eZCollaborationItem::create( 'ezapprove', $authorID );
        $collaborationItem->setAttribute( 'data_int1', $contentObjectID );
        $collaborationItem->setAttribute( 'data_int2', $contentObjectVersion );
        $collaborationItem->setAttribute( 'data_int3', false );
        $collaborationItem->store();
        $collaborationID = $collaborationItem->attribute( 'id' );

        $participantList = array( array( 'id' => $authorID,
                                         'role' => EZ_COLLABORATION_PARTICIPANT_ROLE_AUTHOR ),
                                  array( 'id' => $approverID,
                                         'role' => EZ_COLLABORATION_PARTICIPANT_ROLE_APPROVER ) );
        foreach ( $participantList as $participantItem )
        {
            $participantID = $participantItem['id'];
            $participantRole = $participantItem['role'];
            $link =& eZCollaborationItemParticipantLink::create( $collaborationID, $participantID,
                                                                 $participantRole, EZ_COLLABORATION_PARTICIPANT_TYPE_USER );
            $link->store();

            $profile =& eZCollaborationProfile::instance( $participantID );
            $groupID =& $profile->attribute( 'main_group' );
//             eZDebug::writeDebug( 'Adding item group link' );
            eZCollaborationItemGroupLink::addItem( $groupID, $collaborationID, $participantID );
        }

        // Create the notification
        $collaborationItem->createNotificationEvent();
        return $collaborationItem;
    }

    /*!
     \reimp
     Adds a new comment, approves the item or denies the item.
    */
    function handleCustomAction( &$module, &$collaborationItem )
    {
        $redirectView = 'item';
        $redirectParameters = array( 'full', $collaborationItem->attribute( 'id' ) );
        $addComment = false;

        if ( $this->isCustomAction( 'Comment' ) )
        {
            $addComment = true;
        }
        else if ( $this->isCustomAction( 'Accept' ) or
                  $this->isCustomAction( 'Deny' ) or
                  $this->isCustomAction( 'Defer' ) )
        {
            $contentObjectVersion =& $this->contentObjectVersion( $collaborationItem );
            $status = EZ_COLLABORATION_APPROVE_STATUS_DENIED;
            if ( $this->isCustomAction( 'Accept' ) )
                $status = EZ_COLLABORATION_APPROVE_STATUS_ACCEPTED;
//             else if ( $this->isCustomAction( 'Defer' ) )
//                 $status = EZ_COLLABORATION_APPROVE_STATUS_DEFERRED;
//             else if ( $this->isCustomAction( 'Deny' ) )
//                 $status = EZ_COLLABORATION_APPROVE_STATUS_DENIED;
            else if ( $this->isCustomAction( 'Defer' ) or
                      $this->isCustomAction( 'Deny' ) )
                $status = EZ_COLLABORATION_APPROVE_STATUS_DENIED;
            $collaborationItem->setAttribute( 'data_int3', $status );
            $collaborationItem->setAttribute( 'status', EZ_COLLABORATION_STATUS_INACTIVE );
            $timestamp = time();
            $collaborationItem->setAttribute( 'modified', $timestamp );
            $collaborationItem->setIsActive( false );
            $redirectView = 'view';
            $redirectParameters = array( 'summary' );
            $addComment = true;
        }
        if ( $addComment )
        {
            $messageText = $this->customInput( 'ApproveComment' );
            if ( trim( $messageText ) != '' )
            {
                $message =& eZCollaborationSimpleMessage::create( 'ezapprove_comment', $messageText );
                $message->store();
//                 eZDebug::writeDebug( $message );
                eZCollaborationItemMessageLink::addMessage( $collaborationItem, $message, EZ_COLLABORATION_MESSAGE_TYPE_APPROVE );
            }
        }
        $collaborationItem->sync();
        return $module->redirectToView( $redirectView, $redirectParameters );
    }

}

?>

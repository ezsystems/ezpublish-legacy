<?php
//
// Definition of eZApproveCollaborationHandler class
//
// Created on: <23-Jan-2003 11:57:11 amos>
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
include_once( 'kernel/common/i18n.php' );

/// Approval message type
define( "EZ_COLLABORATION_MESSAGE_TYPE_APPROVE", 1 );

/// Default status, no approval decision has been made
define( "EZ_COLLABORATION_APPROVE_STATUS_WAITING", 0 );
/// The contentobject was approved and will be published.
define( "EZ_COLLABORATION_APPROVE_STATUS_ACCEPTED", 1 );
/// The contentobject was denined and will be archived.
define( "EZ_COLLABORATION_APPROVE_STATUS_DENIED", 2 );

class eZApproveCollaborationHandler extends eZCollaborationItemHandler
{
    /*!
     Initializes the handler
    */
    function eZApproveCollaborationHandler()
    {
        $this->eZCollaborationItemHandler( 'ezapprove', ezi18n( 'design/standard/collaboration/approval', 'Approval' ) );
    }

    /*!
     \reimp
    */
    function title( &$collaborationItem )
    {
        return ezi18n( 'design/standard/collaboration/approval', 'Approval' );
    }

    /*!
     \reimp
    */
    function content( &$collaborationItem )
    {
        return array( "content_object_id" => $collaborationItem->attribute( "data_int1" ),
                      "content_object_version" => $collaborationItem->attribute( "data_int2" ),
                      "is_approved" => $collaborationItem->attribute( "data_int3" ) );
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

            include_once( 'kernel/classes/ezcollaborationprofile.php' );
            $profile =& eZCollaborationProfile::instance();
            $groupID =& $profile->attribute( 'main_group' );
            $groupLink =& eZCollaborationItemGroupLink::create( $collaborationID, $groupID, $participantID );
            $groupLink->store();
        }
        return $collaborationItem;
    }

    /*!
     \reimp
     Adds a new comment, approves the item or denies the item.
    */
    function handleCustomAction( &$module, &$collaborationItem )
    {
        if ( $this->isCustomAction( 'Comment' ) )
        {
            $messageText = $this->customInput( 'ApproveComment' );
            if ( trim( $messageText ) != '' )
            {
                include_once( 'kernel/classes/ezcollaborationsimplemessage.php' );
                $message =& eZCollaborationSimpleMessage::create( 'ezapprove_comment', $messageText );
                $message->store();
                eZDebug::writeDebug( $message );
                include_once( 'kernel/classes/ezcollaborationitemmessagelink.php' );
                eZCollaborationItemMessageLink::addMessage( $collaborationItem, $message, EZ_COLLABORATION_MESSAGE_TYPE_APPROVE );
            }
            return $module->redirectToView( 'item', array( 'full', $collaborationItem->attribute( 'id' ) ) );
        }
        return $module->redirectToView( 'item', array( 'full', $collaborationItem->attribute( 'id' ) ) );
    }

}

?>

<?php
//
// Definition of eZCollaborationNotificationHandler class
//
// Created on: <09-Jul-2003 16:37:01 amos>
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

/*! \file ezcollaborationnotificationhandler.php
*/

/*!
  \class eZCollaborationNotificationHandler ezcollaborationnotificationhandler.php
  \brief The class eZCollaborationNotificationHandler does

*/

define( 'EZ_COLLABORATION_NOTIFICATION_HANDLER_ID', 'ezcollaboration' );
define( 'EZ_COLLABORATION_NOTIFICATION_HANDLER_TRANSPORT', 'ezmail' );

include_once( 'kernel/classes/notification/eznotificationeventhandler.php' );
include_once( 'kernel/classes/ezcollaborationitemhandler.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'kernel/classes/notification/eznotificationcollection.php' );
include_once( 'kernel/classes/notification/eznotificationschedule.php' );
include_once( 'kernel/classes/notification/handler/ezcollaborationnotification/ezcollaborationnotificationrule.php' );

class eZCollaborationNotificationHandler extends eZNotificationEventHandler
{
    /*!
     Constructor
    */
    function eZCollaborationNotificationHandler()
    {
        $this->eZNotificationEventHandler( EZ_COLLABORATION_NOTIFICATION_HANDLER_ID, "Collaboration Handler" );
    }


    function hasAttribute( $attr )
    {
        if ( $attr == 'collaboration_handlers' or
             $attr == 'collaboration_selections' )
            return true;
        return eZNotificationEventHandler::hasAttribute( $attr );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'collaboration_handlers' )
        {
            return $this->collaborationHandlers();
        }
        else if ( $attr == 'collaboration_selections' )
        {
            return $this->collaborationSelections();
        }
        return eZNotificationEventHandler::attribute( $attr );
    }

    /*!
     Returns the available collaboration handlers.
    */
    function &collaborationHandlers()
    {
        return eZCollaborationItemHandler::fetchList();
    }

    /*!
    */
    function collaborationSelections()
    {
        $rules =& eZCollaborationNotificationRule::fetchList();
        $selection = array();
        for ( $i = 0; $i < count( $rules ); ++$i )
        {
            $rule =& $rules[$i];
            $selection[] = $rule->attribute( 'collab_identifier' );
        }
        return $selection;
    }

    function handle( &$event )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $event, "trying to handle event" );
        if ( $event->attribute( 'event_type_string' ) == 'ezcollaboration' )
        {
            $parameters = array();
            $status = $this->handleCollaborationEvent( $event, $parameters );
            if ( $status == EZ_NOTIFICATIONEVENTHANDLER_EVENT_HANDLED )
                $this->sendMessage( $event, $parameters );
            else
                return false;
        }
        return true;
    }

    function handleCollaborationEvent( &$event, &$parameters )
    {
        $collaborationItem =& $event->attribute( 'content' );
        if ( !$collaborationItem )
            return EZ_NOTIFICATIONEVENTHANDLER_EVENT_SKIPPED;
        $collaborationHandler =& $collaborationItem->attribute( 'handler' );
        return $collaborationHandler->handleCollaborationEvent( $event, $collaborationItem, $parameters );
    }

    function sendMessage( &$event, $parameters )
    {
        $collections =& eZNotificationCollection::fetchListForHandler( EZ_COLLABORATION_NOTIFICATION_HANDLER_ID,
                                                                       $event->attribute( 'id' ),
                                                                       EZ_COLLABORATION_NOTIFICATION_HANDLER_TRANSPORT );
        foreach ( array_keys( $collections ) as $collectionKey )
        {
            $collection =& $collections[$collectionKey];
            $items =& $collection->attribute( 'items_to_send' );
            $addressList = array();
            foreach ( array_keys( $items ) as $key )
            {
                $addressList[] = $items[$key]->attribute( 'address' );
                $items[$key]->remove();
            }
            $transport =& eZNotificationTransport::instance( 'ezmail' );
            $transport->send( $addressList, $collection->attribute( 'data_subject' ), $collection->attribute( 'data_text' ), null,
                              $parameters );
            if ( $collection->attribute( 'item_count' ) == 0 )
            {
                $collection->remove();
            }
        }
    }

    function &subscribedNodes( $user = false )
    {
        if ( $user === false )
        {
            $user =& eZUser::currentUser();
        }
        $email = $user->attribute( 'email' );

        $nodeList =& eZCollaborationNotificationRule::fetchNodesForAddress( $email );
        return $nodeList;
    }

    function &rules( $user = false )
    {
        if ( $user === false )
        {
            $user =& eZUser::currentUser();
        }
        $email = $user->attribute( 'email' );

        $ruleList =& eZCollaborationNotificationRule::fetchList( $email );
        return $ruleList;
    }

    function fetchHttpInput( &$http, &$module )
    {
        if ( $http->hasPostVariable( 'CollaborationHandlerSelection'  ) )
        {
            $oldSelection = $this->collaborationSelections();
            $selection = array();
            if ( $http->hasPostVariable( 'CollaborationHandlerSelection_' . EZ_COLLABORATION_NOTIFICATION_HANDLER_ID  ) )
                $selection = $http->postVariable( 'CollaborationHandlerSelection_' . EZ_COLLABORATION_NOTIFICATION_HANDLER_ID );
            $createRules = array_diff( $selection, $oldSelection );
            $removeRules = array_diff( $oldSelection, $selection );
            if ( count( $removeRules ) > 0 )
                eZCollaborationNotificationRule::removeByIdentifier( array( $removeRules ) );
            foreach ( $createRules as $createRule )
            {
                $rule =& eZCollaborationNotificationRule::create( $createRule );
                $rule->store();
            }
        }

    }

    /*!
     \reimp
    */
    function cleanup()
    {
        eZCollaborationNotificationRule::cleanup();
    }
}

?>

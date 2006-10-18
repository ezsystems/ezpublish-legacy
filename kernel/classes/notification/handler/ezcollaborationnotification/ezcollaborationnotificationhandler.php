<?php
//
// Definition of eZCollaborationNotificationHandler class
//
// Created on: <09-Jul-2003 16:37:01 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
// 
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
// 
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
// 
// 
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
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

    function attributes()
    {
        return array_merge( array( 'collaboration_handlers',
                                   'collaboration_selections' ),
                            eZNotificationEventHandler::attributes() );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'collaboration_handlers' )
        {
            return $this->collaborationHandlers();
        }
        else if ( $attr == 'collaboration_selections' )
        {
            $selections = $this->collaborationSelections();
            return $selections;
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
        $rules = eZCollaborationNotificationRule::fetchList();
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
        $collections = eZNotificationCollection::fetchListForHandler( EZ_COLLABORATION_NOTIFICATION_HANDLER_ID,
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

    function &rules( $user = false )
    {
        if ( $user === false )
        {
            $user =& eZUser::currentUser();
        }
        $email = $user->attribute( 'email' );

        $ruleList = eZCollaborationNotificationRule::fetchList( $email );
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
                $rule = eZCollaborationNotificationRule::create( $createRule );
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

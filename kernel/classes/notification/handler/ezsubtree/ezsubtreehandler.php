<?php
//
// Definition of eZSubTreeHandler class
//
// Created on: <12-May-2003 16:35:47 sp>
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezsubtreehandler.php
*/

/*!
  \class eZSubTreeHandler ezsubtreehandler.php
  \brief The class eZSubTreeHandler does

*/
define( 'EZ_SUBTREE_NOTIFICATION_HANDLER_ID', 'ezsubtree' );
define( 'EZ_SUBTREE_NOTIFICATION_HANDLER_TRANSPORT', 'ezmail' );
include_once( 'kernel/classes/notification/eznotificationeventhandler.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'kernel/classes/notification/eznotificationcollection.php' );
include_once( 'kernel/classes/notification/eznotificationschedule.php' );
include_once( 'kernel/classes/notification/handler/ezsubtree/ezsubtreenotificationrule.php' );

class eZSubTreeHandler extends eZNotificationEventHandler
{
    /*!
     Constructor
    */
    function eZSubTreeHandler()
    {
        $this->eZNotificationEventHandler( EZ_SUBTREE_NOTIFICATION_HANDLER_ID, "Subtree Handler" );
    }


    function hasAttribute( $attr )
    {
        if ( $attr == 'subscribed_nodes' )
        {
            return true;
        }
        else if ( $attr == 'rules' )
        {
            return true;
        }
        return eZNotificationEventHandler::hasAttribute( $attr );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'subscribed_nodes' )
        {
            $user =& eZUser::currentUser();
            return $this->subscribedNodes( $user );
        }
        else if ( $attr == 'rules' )
        {
            $user =& eZUser::currentUser();
            return $this->rules( $user );
        }
        return eZNotificationEventHandler::attribute( $attr );
    }

    function handle( &$event )
    {
        eZDebug::writeDebug( $event, "trying to handle event" );
        if ( $event->attribute( 'event_type_string' ) == 'ezpublish' )
        {
            $this->handlePublishEvent( $event );
            $this->sendMessage( $event );
        }
        return true;
    }

    function handlePublishEvent( &$event )
    {
        $versionObject =& $event->attribute( 'content' );
        $contentObject = $versionObject->attribute( 'contentobject' );
        if ( // $versionObject->attribute( 'version' ) != 1 ||
             $versionObject->attribute( 'version' ) != $contentObject->attribute( 'current_version' ) )
        {
            return EZ_NOTIFICATIONEVENTHANDLER_EVENT_SKIPPED;
        }
        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();
        $tpl->setVariable( 'object', $contentObject );
        $result = $tpl->fetch( 'design:notification/handler/ezsubtree/view/plain.tpl' );
        $subject = $tpl->variable( 'subject' );

        $collection = eZNotificationCollection::create( $event->attribute( 'id' ),
                                                        EZ_SUBTREE_NOTIFICATION_HANDLER_ID,
                                                        EZ_SUBTREE_NOTIFICATION_HANDLER_TRANSPORT );

        $collection->setAttribute( 'data_subject', $subject );
        $collection->setAttribute( 'data_text', $result );
        $collection->store();

        $assignedNodes =& $contentObject->parentNodes( true );
        $nodeIDList = array();
        foreach( array_keys( $assignedNodes ) as $key )
        {
            $node =& $assignedNodes[$key];
            $pathString = $node->attribute( 'path_string' );
            $pathString = ltrim( rtrim( $pathString, '/' ), '/' );
            $nodeIDListPart = explode( '/', $pathString );
            $nodeIDList = array_merge( $nodeIDList, $nodeIDListPart );
        }
        $nodeIDList = array_unique( $nodeIDList );

        $userList =& eZSubtreeNotificationRule::fetchUserList( $nodeIDList );

//// needs to be rebuilt
        $locale =& eZLocale::instance();
        $weekDayNames = $locale->attribute( 'weekday_list' );
        $weekDays = $locale->attribute( 'weekday_name_list' );
        $weekDaysByName = array();
        foreach ( $weekDays as $weekDay )
        {
            $weekDaysByName[$weekDayNames[$weekDay]] = $weekDay;
        }
///////////
        foreach( $userList  as $subscriber )
        {
            $item =& $collection->addItem( $subscriber['address'] );
            if ( $userList['use_digest'] == 0 )
            {
                $settings =& eZGeneralDigestUserSettings::fetchForUser( $subscriber['address'] );
                if ( !is_null( $settings ) && $settings->attribute( 'receive_digest' ) == 1 )
                {
                    $hours = $settings->attribute( 'time' );
                    $hoursArray = explode( ':', $hours );
                    $hours = $hoursArray[0];
                    $weekday = $weekDaysByName[ $settings->attribute( 'day' ) ];
                    eZNotificationSchedule::setDateForItem( $item, array( 'frequency' => 'week', 'day' => $weekday, 'time' => $hours ) );
                    $item->store();
                }
            }
        }
        return EZ_NOTIFICATIONEVENTHANDLER_EVENT_HANDLED;
    }

    function sendMessage( &$event )
    {
        $collection =& eZNotificationCollection::fetchForHandler( EZ_SUBTREE_NOTIFICATION_HANDLER_ID,
                                                                  $event->attribute( 'id' ),
                                                                  EZ_SUBTREE_NOTIFICATION_HANDLER_TRANSPORT );
        $items =& $collection->attribute( 'items_to_send' );
        $addressList = array();
        foreach ( array_keys( $items ) as $key )
        {
            $addressList[] = $items[$key]->attribute( 'address' );
            $items[$key]->remove();
        }
        $transport =& eZNotificationTransport::instance( 'ezmail' );
        $transport->send( $addressList, $collection->attribute( 'data_subject' ), $collection->attribute( 'data_text' ) );
        if ( $collection->attribute( 'item_count' ) == 0 )
        {
            $collection->remove();
        }

    }

    function &subscribedNodes( $user = false )
    {
        if ( $user === false )
        {
            $user =& eZUser::currentUser();
        }
        $email = $user->attribute( 'email' );

        $nodeList =& eZSubtreeNotificationRule::fetchNodesForAddress( $email );
        return $nodeList;
    }

    function &rules( $user = false )
    {
        if ( $user === false )
        {
            $user =& eZUser::currentUser();
        }
        $email = $user->attribute( 'email' );

        $ruleList =& eZSubtreeNotificationRule::fetchList( $email );
        return $ruleList;
    }

    function fetchHttpInput( &$http, &$module )
    {
        if ( $http->hasPostVariable( 'NewRule_' . EZ_SUBTREE_NOTIFICATION_HANDLER_ID  ) )
        {
            include_once( "kernel/classes/ezcontentbrowse.php" );
            eZContentBrowse::browse( array( 'action_name' => 'AddSubtreeSubscribingNode',
                                            'from_page' => '/notification/settings/' ),
                                     $module );

        }
        else if ( $http->hasPostVariable( 'RemoveRule_' . EZ_SUBTREE_NOTIFICATION_HANDLER_ID  ) )
        {
            $ruleIDList = $http->postVariable( 'SelectedRuleIDArray_' . EZ_SUBTREE_NOTIFICATION_HANDLER_ID  );
            foreach ( $ruleIDList as $ruleID )
            {
                eZPersistentObject::removeObject( eZSubtreeNotificationRule::definition(), array( 'id' => $ruleID ) );
            }
            $existingNodes =& eZSubtreeNotificationRule::fetchNodesForAddress( $email, false );
        }
        else if ( $http->hasPostVariable( "BrowseActionName" ) and
             $http->postVariable( "BrowseActionName" ) == "AddSubtreeSubscribingNode" )
        {
            $selectedNodeIDArray = $http->postVariable( "SelectedNodeIDArray" );
            $user =& eZUser::currentUser();
            $email = $user->attribute( 'email' );

            $existingNodes =& eZSubtreeNotificationRule::fetchNodesForAddress( $email, false );

            foreach ( $selectedNodeIDArray as $nodeID )
            {
                if ( ! in_array( $nodeID, $existingNodes ) )
                {
                    $rule =& eZSubtreeNotificationRule::create(  $nodeID, $email );
                    $rule->store();
                }
            }
//            $Module->redirectTo( "//list/" );
        }

    }


    var $SubscriberList = array( 'sp@ez.no', 'spspam@ez.no' );
}

?>

<?php
//
// Definition of eZSubTreeHandler class
//
// Created on: <12-May-2003 16:35:47 sp>
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
        eZDebugSetting::writeDebug( 'kernel-notification', $event, "trying to handle event" );
        if ( $event->attribute( 'event_type_string' ) == 'ezpublish' )
        {
            $parameters = array();
            $status = $this->handlePublishEvent( $event, $parameters );
            if ( $status == EZ_NOTIFICATIONEVENTHANDLER_EVENT_HANDLED )
                $this->sendMessage( $event, $parameters );
            else
                return false;
        }
        return true;
    }

    function handlePublishEvent( &$event, &$parameters )
    {
        $versionObject =& $event->attribute( 'content' );
        if ( !$versionObject )
            return EZ_NOTIFICATIONEVENTHANDLER_EVENT_SKIPPED;
        $contentObject = $versionObject->attribute( 'contentobject' );
        if ( !$contentObject )
            return EZ_NOTIFICATIONEVENTHANDLER_EVENT_SKIPPED;
        $contentNode =& $contentObject->attribute( 'main_node' );
        if ( !$contentNode )
            return EZ_NOTIFICATIONEVENTHANDLER_EVENT_SKIPPED;
        $contentClass =& $contentObject->attribute( 'content_class' );
        if ( !$contentClass )
            return EZ_NOTIFICATIONEVENTHANDLER_EVENT_SKIPPED;
        if ( // $versionObject->attribute( 'version' ) != 1 ||
             $versionObject->attribute( 'version' ) != $contentObject->attribute( 'current_version' ) )
        {
            return EZ_NOTIFICATIONEVENTHANDLER_EVENT_SKIPPED;
        }
        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();
        $tpl->resetVariables();

        $parentNode =& $contentNode->attribute( 'parent' );
        $parentContentObject =& $parentNode->attribute( 'object' );
        $parentContentClass =& $parentContentObject->attribute( 'content_class' );

        $res =& eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object', $contentObject->attribute( 'id' ) ),
                              array( 'node', $contentNode->attribute( 'node_id' ) ),
                              array( 'class', $contentObject->attribute( 'contentclass_id' ) ),
                              array( 'class_identifier', $contentClass->attribute( 'identifier' ) ),
                              array( 'parent_node', $contentNode->attribute( 'parent_node_id' ) ),
                              array( 'parent_class', $parentContentObject->attribute( 'contentclass_id' ) ),
                              array( 'parent_class_identifier', ( $parentContentClass != null ? $parentContentClass->attribute( 'identifier' ) : 0 ) ),
                              array( 'depth', $contentNode->attribute( 'depth' ) ),
                              array( 'url_alias', $contentNode->attribute( 'url_alias' ) )
                              ) );

        $tpl->setVariable( 'object', $contentObject );

        $notificationINI =& eZINI::instance( 'notification.ini' );
        $emailSender = $notificationINI->variable( 'MailSettings', 'EmailSender' );
        $ini =& eZINI::instance();
        if ( !$emailSender )
            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( "MailSettings", "AdminEmail" );
        $tpl->setVariable( 'sender', $emailSender );

        $result = $tpl->fetch( 'design:notification/handler/ezsubtree/view/plain.tpl' );
        $subject = $tpl->variable( 'subject' );
        if ( $tpl->hasVariable( 'message_id' ) )
            $parameters['message_id'] = $tpl->variable( 'message_id' );
        if ( $tpl->hasVariable( 'references' ) )
            $parameters['references'] = $tpl->variable( 'references' );
        if ( $tpl->hasVariable( 'reply_to' ) )
            $parameters['reply_to'] = $tpl->variable( 'reply_to' );
        if ( $tpl->hasVariable( 'from' ) )
            $parameters['from'] = $tpl->variable( 'from' );

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
            if ( $node )
            {
                $pathString = $node->attribute( 'path_string' );
                $pathString = ltrim( rtrim( $pathString, '/' ), '/' );
                $nodeIDListPart = explode( '/', $pathString );
                $nodeIDList = array_merge( $nodeIDList, $nodeIDListPart );
            }
        }
        $nodeIDList[] = $contentNode->attribute( 'node_id' );
        $nodeIDList = array_unique( $nodeIDList );

        $userList =& eZSubtreeNotificationRule::fetchUserList( $nodeIDList, $contentObject );

        $locale =& eZLocale::instance();
        $weekDayNames = $locale->attribute( 'weekday_name_list' );
        $weekDaysByName = array_flip( $weekDayNames );

        foreach( $userList as $subscriber )
        {
            $item =& $collection->addItem( $subscriber['address'] );
            if ( $subscriber['use_digest'] == 0 )
            {
                $settings =& eZGeneralDigestUserSettings::fetchForUser( $subscriber['address'] );
                if ( !is_null( $settings ) && $settings->attribute( 'receive_digest' ) == 1 )
                {
                    $time = $settings->attribute( 'time' );
                    $timeArray = explode( ':', $time );
                    $hour = $timeArray[0];

                    if ( $settings->attribute( 'digest_type' ) == EZ_DIGEST_SETTINGS_TYPE_DAILY )
                    {
                        eZNotificationSchedule::setDateForItem( $item, array( 'frequency' => 'day',
                                                                              'hour' => $hour ) );
                    }
                    else if ( $settings->attribute( 'digest_type' ) == EZ_DIGEST_SETTINGS_TYPE_WEEKLY )
                    {
                        $weekday = $weekDaysByName[ $settings->attribute( 'day' ) ];
                        eZNotificationSchedule::setDateForItem( $item, array( 'frequency' => 'week',
                                                                              'day' => $weekday,
                                                                              'hour' => $hour ) );
                    }
                    else if ( $settings->attribute( 'digest_type' ) == EZ_DIGEST_SETTINGS_TYPE_MONTHLY )
                    {
                        eZNotificationSchedule::setDateForItem( $item,
                                                                array( 'frequency' => 'month',
                                                                       'day' => $settings->attribute( 'day' ),
                                                                       'hour' => $hour ) );
                    }
                    $item->store();
                }
            }
        }
        return EZ_NOTIFICATIONEVENTHANDLER_EVENT_HANDLED;
    }

    function sendMessage( &$event, $parameters )
    {
        $collection =& eZNotificationCollection::fetchForHandler( EZ_SUBTREE_NOTIFICATION_HANDLER_ID,
                                                                  $event->attribute( 'id' ),
                                                                  EZ_SUBTREE_NOTIFICATION_HANDLER_TRANSPORT );
        if ( !$collection )
            return;
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

    function &subscribedNodes( $user = false )
    {
        if ( $user === false )
        {
            $user =& eZUser::currentUser();
        }
        $userID = $user->attribute( 'contentobject_id' );

        $nodeList =& eZSubtreeNotificationRule::fetchNodesForUserID( $userID );
        return $nodeList;
    }

    function &rules( $user = false, $offset = false, $limit = false )
    {
        if ( $user === false )
        {
            $user =& eZUser::currentUser();
        }
        $userID = $user->attribute( 'contentobject_id' );

        $ruleList =& eZSubtreeNotificationRule::fetchList( $userID, true, $offset, $limit );
        return $ruleList;
    }
    
    function &rulesCount( $user = false )
    {
        if ( $user === false )
        {
            $user =& eZUser::currentUser();
        }
        $userID = $user->attribute( 'contentobject_id' );

        return eZSubtreeNotificationRule::fetchListCount( $userID );
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
            $existingNodes =& eZSubtreeNotificationRule::fetchNodesForUserID( $email, false );
        }
        else if ( $http->hasPostVariable( "BrowseActionName" ) and
                  $http->postVariable( "BrowseActionName" ) == "AddSubtreeSubscribingNode" and
                  !$http->hasPostVariable( 'BrowseCancelButton' ) )
        {
            $selectedNodeIDArray = $http->postVariable( "SelectedNodeIDArray" );
            $user =& eZUser::currentUser();

            $existingNodes =& eZSubtreeNotificationRule::fetchNodesForUserID( $user->attribute( 'contentobject_id' ), false );

            foreach ( $selectedNodeIDArray as $nodeID )
            {
                if ( ! in_array( $nodeID, $existingNodes ) )
                {
                    $rule =& eZSubtreeNotificationRule::create( $nodeID, $user->attribute( 'contentobject_id' ) );
                    $rule->store();
                }
            }
//            $Module->redirectTo( "//list/" );
        }

    }

    /*!
     \reimp
    */
    function cleanup()
    {
        eZSubtreeNotificationRule::cleanup();
    }
}

?>

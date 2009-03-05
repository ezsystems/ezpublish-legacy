<?php
//
// Definition of eZGeneralDigestHandler class
//
// Created on: <16-May-2003 10:55:24 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZGeneralDigestHandler ezgeneraldigesthandler.php
  \brief The class eZGeneralDigestHandler does

*/
class eZGeneralDigestHandler extends eZNotificationEventHandler
{
    const NOTIFICATION_HANDLER_ID = 'ezgeneraldigest';

    /*!
     Constructor
    */
    function eZGeneralDigestHandler()
    {
        $this->eZNotificationEventHandler( self::NOTIFICATION_HANDLER_ID, "General Digest Handler" );

    }

    function attributes()
    {
        return array_merge( array( 'settings',
                                   'all_week_days',
                                   'all_month_days',
                                   'available_hours' ),
                            eZNotificationEventHandler::attributes() );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        if ( $attr == 'settings' )
        {
            return $this->settings( eZUser::currentUser() );
        }
        else if ( $attr == 'all_week_days' )
        {
            return eZLocale::instance()->attribute( 'weekday_name_list' );
        }
        else if ( $attr == 'all_month_days' )
        {
            return range( 1, 31 );
        }
        else if ( $attr == 'available_hours' )
        {
            return array( '0:00',
                          '1:00',
                          '2:00',
                          '3:00',
                          '4:00',
                          '5:00',
                          '6:00',
                          '7:00',
                          '8:00',
                          '9:00',
                          '10:00',
                          '11:00',
                          '12:00',
                          '13:00',
                          '14:00',
                          '15:00',
                          '16:00',
                          '17:00',
                          '18:00',
                          '19:00',
                          '20:00',
                          '21:00',
                          '22:00',
                          '23:00' );
        }
        return eZNotificationEventHandler::attribute( $attr );
    }

    function settings( $user = false )
    {
        if ( $user === false )
        {
            $user = eZUser::currentUser();
        }
        $address = $user->attribute( 'email' );
        $settings = eZGeneralDigestUserSettings::fetchForUser( $address );
        if ( $settings == null )
        {
            $settings = eZGeneralDigestUserSettings::create( $address );
            $settings->store();
        }
        return $settings;
    }

    function handle( $event )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $event, "trying to handle event" );
        if ( $event->attribute( 'event_type_string' ) == 'ezcurrenttime' )
        {
            $date = $event->content();
            $timestamp = $date->attribute( 'timestamp' );

            $addressArray = $this->fetchUsersForDigest( $timestamp );

            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();

            foreach ( $addressArray as $address )
            {
                $tpl->setVariable( 'date', $date );
                $tpl->setVariable( 'address', $address['address'] );
                $result = $tpl->fetch( 'design:notification/handler/ezgeneraldigest/view/plain.tpl' );
                $subject = $tpl->variable( 'subject' );
                $transport = eZNotificationTransport::instance( 'ezmail' );
                $transport->send( $address, $subject, $result);
                eZDebugSetting::writeDebug( 'kernel-notification', $result, "digest result" );
            }

            $collectionItemIDList = $tpl->variable( 'collection_item_id_list' );
            eZDebugSetting::writeDebug( 'kernel-notification', $collectionItemIDList, "handled items" );

            if ( is_array( $collectionItemIDList ) && count( $collectionItemIDList ) > 0 )
            {
                $ini = eZINI::instance( 'notification.ini' );
                $countElements = $ini->variable( 'RuleSettings', 'LimitDeleteElements' );
                if ( !$countElements )
                {
                    $countElements = 50;
                }
                $splited = array_chunk( $collectionItemIDList, $countElements );
                foreach ( $splited as $key => $value )
                {
                    eZPersistentObject::removeObject( eZNotificationCollectionItem::definition(), array( 'id' => array( $value, '' ) ) );
                }
            }

        }
        return true;
    }


    function fetchUsersForDigest( $timestamp )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationCollectionItem::definition(),
                                                    array(), array( 'send_date' => array( '', array( 1, $timestamp ) ) ),
                                                    array( 'address' => 'asc' ),null,
                                                    false,false,array( array( 'operation' => 'distinct address' ) ) );

    }

    function fetchHandlersForUser( $time, $address )
    {
        $db = eZDB::instance();

        $time = (int)$time;
        $address = $db->escapeString( $address );

        $query = "select distinct handler
                  from eznotificationcollection,
                       eznotificationcollection_item
                  where eznotificationcollection_item.collection_id = eznotificationcollection.id and
                        address='$address' and
                        send_date != 0 and
                        send_date < $time";
        $handlerResult = $db->arrayQuery( $query );
        $handlers = array();
        $availableHandlers = eZNotificationEventFilter::availableHandlers();
        foreach ( $handlerResult as $handlerName )
        {
            $handlers[$handlerName['handler']] = $availableHandlers[$handlerName['handler']];
        }
        return $handlers;
    }

    function fetchItemsForUser( $time, $address, $handler )
    {
        $db = eZDB::instance();

        $time = (int)$time;
        $address = $db->escapeString( $address );
        $handler = $db->escapeString( $handler );

        $query = "select eznotificationcollection_item.*
                  from eznotificationcollection,
                       eznotificationcollection_item
                  where eznotificationcollection_item.collection_id = eznotificationcollection.id and
                        address='$address' and
                        send_date != 0 and
                        send_date < $time and
                        handler = '$handler'
                        order by eznotificationcollection_item.event_id";
        $itemResult = $db->arrayQuery( $query );
        $items = array();
        foreach ( $itemResult as $itemRow )
        {
            $items[] = new eZNotificationCollectionItem( $itemRow );
        }
        return $items;
    }

    function storeSettings( $http, $module )
    {
        $user = eZUser::currentUser();
        $address = $user->attribute( 'email' );
        $settings = eZGeneralDigestUserSettings::fetchForUser( $address );

        if ( $http->hasPostVariable( 'ReceiveDigest_' . self::NOTIFICATION_HANDLER_ID ) &&
             $http->hasPostVariable( 'ReceiveDigest_' . self::NOTIFICATION_HANDLER_ID ) == '1' )
        {
            $settings->setAttribute( 'receive_digest', 1 );
            $digestType = $http->postVariable( 'DigestType_' . self::NOTIFICATION_HANDLER_ID );
            $settings->setAttribute( 'digest_type', $digestType );
            if ( $digestType == 1 )
            {
                $settings->setAttribute( 'day', $http->postVariable( 'Weekday_' . self::NOTIFICATION_HANDLER_ID ) );
            }
            else if ( $digestType == 2 )
            {
                $settings->setAttribute( 'day', $http->postVariable( 'Monthday_' . self::NOTIFICATION_HANDLER_ID ) );
            }
            $settings->setAttribute( 'time', $http->postVariable( 'Time_' . self::NOTIFICATION_HANDLER_ID ) );
            $settings->store();
        }
        else
        {
            $settings->setAttribute( 'receive_digest', 0 );
            $settings->store();
        }
    }

    function cleanup()
    {
        eZGeneralDigestUserSettings::cleanup();
    }

}

?>

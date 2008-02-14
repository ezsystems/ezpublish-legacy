<?php
//
// Definition of eZNotificationEventFilter class
//
// Created on: <09-May-2003 16:05:40 sp>
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

/*! \file eznotificationeventfilter.php
*/

/*!
  \class eZNotificationEventFilter eznotificationeventfilter.php
  \brief The class eZNotificationEventFilter does

*/
include_once( 'kernel/classes/notification/eznotificationevent.php' );
class eZNotificationEventFilter
{
    /*!
     Constructor
    */
    function eZNotificationEventFilter()
    {
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function process()
    {
        $eventList = eZNotificationEvent::fetchUnhandledList();
        $availableHandlers =& eZNotificationEventFilter::availableHandlers();
        foreach( array_keys( $eventList ) as $key )
        {
            $event =& $eventList[$key];
            foreach( array_keys( $availableHandlers ) as $handlerKey )
            {
                $handler =& $availableHandlers[$handlerKey];
                if ( $handler === false )
                {
                    eZDebug::writeError( "Notification handler does not exist: $handlerKey", 'eZNotificationEventFilter::process()' );
                }
                else
                {
                    $handler->handle( $event );
                }
            }
            $itemCountLeft = eZNotificationCollectionItem::fetchCountForEvent( $event->attribute( 'id' ) );
            if ( $itemCountLeft == 0 )
            {
                $event->remove();
            }
            else
            {
                $event->setAttribute( 'status', EZ_NOTIFICATIONEVENT_STATUS_HANDLED );
                $event->store();
            }
        }
        eZNotificationCollection::removeEmpty();
    }

    function &availableHandlers()
    {
        include_once( 'lib/ezutils/classes/ezextension.php' );
        $baseDirectory = eZExtension::baseDirectory();
        $notificationINI =& eZINI::instance( 'notification.ini' );
        $availableHandlers = $notificationINI->variable( 'NotificationEventHandlerSettings', 'AvailableNotificationEventTypes' );
        $repositoryDirectories = array();
        $extensionDirectories = $notificationINI->variable( 'NotificationEventHandlerSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/notification/handler';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }
        $handlers = array();
        foreach( $availableHandlers as $handlerString )
        {
            $eventHandler = eZNotificationEventFilter::loadHandler( $repositoryDirectories, $handlerString );
            if ( is_object( $eventHandler ) )
                $handlers[$handlerString] = $eventHandler;
        }
        return $handlers;
    }

    function loadHandler( $directories, $handlerString )
    {
        $foundHandler = false;
        $includeFile = '';


        include_once( 'lib/ezutils/classes/ezextension.php' );
        $baseDirectory = eZExtension::baseDirectory();
        $notificationINI =& eZINI::instance( 'notification.ini' );
        $repositoryDirectories = $notificationINI->variable( 'NotificationEventHandlerSettings', 'RepositoryDirectories' );
        $extensionDirectories = $notificationINI->variable( 'NotificationEventHandlerSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = "{$baseDirectory}/{$extensionDirectory}/notification/handler/";
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }

        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $repositoryDirectory = trim( $repositoryDirectory, '/' );
            $includeFile = "{$repositoryDirectory}/{$handlerString}/{$handlerString}handler.php";
            if ( file_exists( $includeFile ) )
            {
                $foundHandler = true;
                break;
            }
        }
        if ( !$foundHandler  )
        {
            eZDebug::writeError( "Notification handler does not exist: $handlerString", 'eZNotificationEventFilter::loadHandler()' );
            return false;
        }
        include_once( $includeFile );
        $className = $handlerString . "handler";
        return new $className();
    }

    /*!
     \static
     Goes through all event handlers and tells them to cleanup.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function cleanup()
    {
        $availableHandlers =& eZNotificationEventFilter::availableHandlers();

        $db =& eZDB::instance();
        $db->begin();
        foreach( array_keys( $availableHandlers ) as $handlerKey )
        {
            $handler =& $availableHandlers[$handlerKey];
            if ( $handler !== false )
            {
                $handler->cleanup();
            }
        }
        $db->commit();
    }
}

?>

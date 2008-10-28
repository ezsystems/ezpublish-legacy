<?php
//
// Definition of eZNotificationFunctionCollection class
//
// Created on: <14-May-2003 16:41:20 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file eznotificationfunctioncollection.php
*/

/*!
  \class eZNotificationFunctionCollection eznotificationfunctioncollection.php
  \brief The class eZNotificationFunctionCollection does

*/

class eZNotificationFunctionCollection
{
    /*!
     Constructor
    */
    function eZNotificationFunctionCollection()
    {
    }

    function handlerList()
    {
        $availableHandlers = eZNotificationEventFilter::availableHandlers();
        return array( 'result' => $availableHandlers );
    }

    function digestHandlerList( $time, $address )
    {
        $handlers = eZGeneralDigestHandler::fetchHandlersForUser( $time, $address );
        return array( 'result' => $handlers );
    }

    function digestItems( $time, $address, $handler )
    {
        $items = eZGeneralDigestHandler::fetchItemsForUser( $time, $address, $handler );
        return array( 'result' => $items );
    }

    function eventContent( $eventID )
    {
        $event = eZNotificationEvent::fetch( $eventID );
        return array( 'result' => $event->content() );
    }

    function subscribedNodesCount()
    {
        $count = eZSubTreeHandler::rulesCount();
        return array( 'result' => $count );
    }

    function subscribedNodes( $offset = false, $limit = false )
    {
        $nodes = eZSubTreeHandler::rules( false, $offset, $limit );
        return array( 'result' => $nodes );
    }
}

?>

<?php
/**
 * File containing the eZNotificationFunctionCollection class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZNotificationFunctionCollection eznotificationfunctioncollection.php
  \brief The class eZNotificationFunctionCollection does

*/

class eZNotificationFunctionCollection
{
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

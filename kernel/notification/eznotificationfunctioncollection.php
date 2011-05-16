<?php
/**
 * File containing the eZNotificationFunctionCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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

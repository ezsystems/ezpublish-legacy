<?php
//
// Definition of eZNotificationFunctionCollection class
//
// Created on: <14-May-2003 16:41:20 sp>
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

    function &handlerList()
    {
        include_once( 'kernel/classes/notification/eznotificationeventfilter.php' );
        $availableHandlers =& eZNotificationEventFilter::availableHandlers();
        return array( 'result' => $availableHandlers );
    }

    function &digestHandlerList( $time, $address )
    {
        $handlers =& eZGeneralDigestHandler::fetchHandlersForUser( $time, $address );
        return array( 'result' => $handlers );
    }

    function &digestItems( $time, $address, $handler )
    {
        $items =& eZGeneralDigestHandler::fetchItemsForUser( $time, $address, $handler );
        return array( 'result' => $items );
    }

    function &eventContent( $eventID )
    {
        $event =& eZNotificationEvent::fetch( $eventID );
        return array( 'result' => $event->content() );
    }

}

?>

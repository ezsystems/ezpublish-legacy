<?php
//
// Definition of eZNotificationEventHandler class
//
// Created on: <09-May-2003 16:06:26 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file eznotificationeventhandler.php
*/

/*!
  \class eZNotificationEventHandler eznotificationeventhandler.php
  \brief The class eZNotificationEventHandler does

*/

define( 'EZ_NOTIFICATIONEVENTHANDLER_EVENT_HANDLED', 0 );
define( 'EZ_NOTIFICATIONEVENTHANDLER_EVENT_SKIPPED', 1 );
define( 'EZ_NOTIFICATIONEVENTHANDLER_EVENT_UNKNOWN', 2 );
define( 'EZ_NOTIFICATIONEVENTHANDLER_EVENT_ERROR', 3 );

include_once( 'kernel/classes/notification/eznotificationtransport.php' );

class eZNotificationEventHandler
{
    /*!
     Constructor
    */
    function eZNotificationEventHandler( $idString, $name )
    {
        $this->IDString = $idString;
        $this->Name = $name;
    }

    function hasAttribute( $attr )
    {
        if ( $attr == 'id_string' || $attr == 'name' )
            return true;
        return false;
    }

    function &attribute( $attr )
    {
        if ( $attr == 'id_string' )
        {
            return $this->IDString;
        }
        else if ( $attr == 'name' )
        {
            return $this->Name;
        }
        return false;
    }

    function handle( $event )
    {
        return true;
    }

    function fetchHttpInput( &$http, &$module )
    {
        return true;
    }

    function storeSettings( &$http, &$module )
    {
        return true;
    }

    var $IDString = false;
    var $Name = false;
}

?>

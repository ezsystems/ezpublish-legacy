<?php
//
// Definition of eZCurrentTimeType class
//
// Created on: <16-May-2003 10:11:48 sp>
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

/*! \file ezcurrenttimetype.php
*/

/*!
  \class eZCurrentTimeType ezcurrenttimetype.php
  \brief The class eZCurrentTimeType does

*/
include_once( 'kernel/classes/notification/eznotificationeventtype.php' );
include_once( "lib/ezlocale/classes/ezdate.php" );

define( 'EZ_NOTIFICATIONTYPESTRING_CURRENTTIME', 'ezcurrenttime' );

class eZCurrentTimeType extends eZNotificationEventType
{
    /*!
     Constructor
    */
    function eZCurrentTimeType()
    {
        $this->eZNotificationEventType( EZ_NOTIFICATIONTYPESTRING_CURRENTTIME );
    }

    function initializeEvent( &$event, $params )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $params, 'params for type' );
        $time = 0;
        if ( array_key_exists( 'time', $params ) )
        {
            $time = $params['time'];
        }
        else
        {
            $time = mktime();
        }
        $event->setAttribute( 'data_int1', $time );
    }

    function &eventContent( &$event )
    {
        $date = new eZDate( );
        $stamp = $event->attribute( 'data_int1' );
        $date->setTimeStamp( $stamp );
        return $date;
    }

}

eZNotificationEventType::register( EZ_NOTIFICATIONTYPESTRING_CURRENTTIME, 'ezcurrenttimetype' );


?>

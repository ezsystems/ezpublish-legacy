<?php
//
// Definition of eZCurrentTimeType class
//
// Created on: <16-May-2003 10:11:48 sp>
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

/*! \file ezcurrenttimetype.php
*/

/*!
  \class eZCurrentTimeType ezcurrenttimetype.php
  \brief The class eZCurrentTimeType does

*/
class eZCurrentTimeType extends eZNotificationEventType
{
    const NOTIFICATION_TYPE_STRING = 'ezcurrenttime';

    /*!
     Constructor
    */
    function eZCurrentTimeType()
    {
        $this->eZNotificationEventType( self::NOTIFICATION_TYPE_STRING );
    }

    function initializeEvent( $event, $params )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $params, 'params for type' );
        $time = 0;
        if ( array_key_exists( 'time', $params ) )
        {
            $time = $params['time'];
        }
        else
        {
            $time = time();
        }
        $event->setAttribute( 'data_int1', $time );
    }

    function eventContent( $event )
    {
        $date = new eZDate( );
        $stamp = $event->attribute( 'data_int1' );
        $date->setTimeStamp( $stamp );
        return $date;
    }

}

eZNotificationEventType::register( eZCurrentTimeType::NOTIFICATION_TYPE_STRING, 'eZCurrentTimeType' );


?>

<?php
//
// Definition of eZNotificationEvent class
//
// Created on: <09-May-2003 16:03:28 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  \class eZNotificationEvent eznotificationevent.php
  \brief The class eZNotificationEvent does

*/
class eZNotificationEvent extends eZPersistentObject
{
    const STATUS_CREATED = 0;
    const STATUS_HANDLED = 1;

    /*!
     Constructor
    */
    function eZNotificationEvent( $row = array() )
    {
        $this->eZPersistentObject( $row );
        $this->TypeString = $this->attribute( 'event_type_string' );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "status" => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "event_type_string" => array( 'name' => "EventTypeString",
                                                                       'datatype' => 'string',
                                                                       'default' => '',
                                                                       'required' => true ),
                                         "data_int1" => array( 'name' => "DataInt1",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_int2" => array( 'name' => "DataInt2",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_int3" => array( 'name' => "DataInt3",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_int4" => array( 'name' => "DataInt4",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_text1" => array( 'name' => "DataText1",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text2" => array( 'name' => "DataText2",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text3" => array( 'name' => "DataText3",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text4" => array( 'name' => "DataText4",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'content' => 'content' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZNotificationEvent",
                      "name" => "eznotificationevent" );
    }

    static function create( $type, $params = array() )
    {
        $row = array(
            "id" => null,
            'event_type_string' => $type,
            'data_int1' => 0,
            'data_int2' => 0,
            'data_int3' => 0,
            'data_int4' => 0,
            'data_text1' => '',
            'data_text2' => '',
            'data_text3' => '',
            'data_text4' => '' );
        $event = new eZNotificationEvent( $row );
        eZDebugSetting::writeDebug( 'kernel-notification', $event, "event" );
        $event->initializeEventType( $params );
        return $event;
    }

    function initializeEventType( $params = array() )
    {
        $eventType = $this->eventType();
        $eventType->initializeEvent( $this, $params );
        eZDebugSetting::writeDebug( 'kernel-notification', $this, 'event after initialization' );
    }

    function eventType()
    {
        if ( ! isset ( $this->EventType ) )
        {
            $this->EventType = eZNotificationEventType::create( $this->TypeString );
        }
        return $this->EventType;
    }


    /*!
     Returns the content for this event.
    */
    function content()
    {
        if ( $this->Content === null )
        {
            $eventType = $this->eventType();
            $this->Content = $eventType->eventContent( $this );
        }
        return $this->Content;
    }

    /*!
     Sets the content for the current event
    */
    function setContent( $content )
    {
        $this->Content = $content;
    }

    static function fetchList()
    {
        return eZPersistentObject::fetchObjectList( eZNotificationEvent::definition(),
                                                    null,  null, null,null,
                                                    true );
    }

    static function fetch( $eventID )
    {
        return eZPersistentObject::fetchObject( eZNotificationEvent::definition(),
                                                null,
                                                array( 'id' => $eventID ) );
    }

    static function fetchUnhandledList()
    {
        return eZPersistentObject::fetchObjectList( eZNotificationEvent::definition(),
                                                    null, array( 'status' => self::STATUS_CREATED ), null,null,
                                                    true );
    }

    /*!
     \static
     Removes all notification events.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM eznotificationevent" );
    }

    public $Content = null;
}

?>

<?php
//
// Definition of eZNotificationEvent class
//
// Created on: <09-May-2003 16:03:28 sp>
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

/*! \file eznotificationevent.php
*/

/*!
  \class eZNotificationEvent eznotificationevent.php
  \brief The class eZNotificationEvent does

*/
include_once( 'kernel/classes/notification/eznotificationeventtype.php' );
include_once( 'kernel/classes/ezpersistentobject.php' );

define( 'EZ_NOTIFICATIONEVENT_STATUS_CREATED', 0 );
define( 'EZ_NOTIFICATIONEVENT_STATUS_HANDLED', 1 );

class eZNotificationEvent extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZNotificationEvent( $row = array() )
    {
        $this->eZPersistentObject( $row );
        $this->TypeString = $this->attribute( 'event_type_string' );
    }

    function &definition()
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

    function hasAttribute( $attr )
    {
        if ( $attr == 'content' )
        {
            return true;
        }
        return eZPersistentObject::hasAttribute( $attr );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'content' )
        {
            return $this->content();
        }
        return eZPersistentObject::attribute( $attr );

    }

    function &create( $type, $params = array() )
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
        $eventType =& $this->eventType();
        $eventType->initializeEvent( $this, $params );
        eZDebugSetting::writeDebug( 'kernel-notification', $this, 'event after initialization' );
    }

    function &eventType()
    {
        if ( ! isset (  $this->EventType ) )
        {
            $this->EventType =& eZNotificationEventType::create( $this->TypeString );
        }
        return $this->EventType;
    }


    /*!
     Returns the content for this event.
    */
    function &content()
    {
        if ( $this->Content === null )
        {
            $eventType =& $this->eventType();
            $this->Content =& $eventType->eventContent( $this );
        }
        return $this->Content;
    }

    /*!
     Sets the content for the current event
    */
    function setContent( $content )
    {
        $this->Content =& $content;
    }

    function &fetchList()
    {
        return eZPersistentObject::fetchObjectList( eZNotificationEvent::definition(),
                                                    null,  null, null,null,
                                                    true );
    }

    function &fetch( $eventID )
    {
        $events =& eZPersistentObject::fetchObjectList( eZNotificationEvent::definition(),
                                                        null,  array( 'id' => $eventID ), null,null,
                                                        true );
        return $events[0];
    }

    function &fetchUnhandledList()
    {
        return eZPersistentObject::fetchObjectList( eZNotificationEvent::definition(),
                                                    null, array( 'status' => EZ_NOTIFICATIONEVENT_STATUS_CREATED ), null,null,
                                                    true );
    }

    /*!
     \static
     Removes all notification events.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM eznotificationevent" );
    }

    var $Content = null;
}

?>

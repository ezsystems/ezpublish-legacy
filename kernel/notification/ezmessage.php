<?php
//
// Definition of eZMessage class
//
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

//!! eZNotification
//! The class eZMessage
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );

class eZMessage extends eZPersistentObject
{
    function eZMessage( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "send_method" => "SendMethod",
                                         "send_weekday" => "SendWeekday",
                                         "send_time" => "SendTime",
                                         "destination_address" => "DestinationAddress",
                                         "title" => "Title",
                                         "body" => "Body",
                                         "is_sent" => "IsSent"),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZMessage",
                      "name" => "ezmessage" );
    }

    function &create( $sendMethod, $sendWeekday, $sendTime, $destinationAddress, $title, $body )
    {
        $row = array( "id" => null,
                      "send_method" => $sendMethod,
                      "send_weekday" => $sendWeekday,
                      "send_time" => $sendTime,
                      "destination_address" => $destinationAddress,
                      "title" => $title,
                      "body" => $body,
                      "is_sent" => false );
        return new eZMessage( $row );
    }

    /*!
     \return true if the attribute \a $attr exists in this object.
    */
    function hasAttribute( $attr )
    {
        return eZPersistentObject::hasAttribute( $attr ) ;
    }

    function &attribute( $attr )
    {
        return eZPersistentObject::attribute( $attr );
    }

    /*!
     Remove the message with \a $id
    */
    function &remove( $id )
    {
        eZPersistentObject::removeObject( eZMessage::definition(),
                                          array( "id" => $id ) );
    }

    /*!
     Fetch message object with \a $id
    */
    function &fetch( $id,  $asObject = true  )
    {
        return eZPersistentObject::fetchObject( eZMessage::definition(),
                                                null,
                                                array("id" => $id ),
                                                $asObject );
    }

    /*!
     Fetch all messages from message database
    */
    function &fetchAll( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZMessage::definition(),
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /*!
     Fetch all messages which are of type SMS message
    */
    function &fetchSMSMessages( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZMessage::definition(),
                                                    null,
                                                    array( "send_method" => "sms",
                                                           "is_sent" => 0 ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /*!
     Fetch all messages which are of type Email message
    */
    function &fetchEmailMessages( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZMessage::definition(),
                                                    null,
                                                    array( "send_method" => "email",
                                                           "is_sent" => 0 ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /// \privatesection
    var $ID;
    var $SendMethod;
    var $SendWeekday;
    var $SendTime;
    var $DestinationAddress;
    var $Title;
    var $Body;
    var $IsSent;
}

?>

<?php
//
// Definition of eZNotificationCollection class
//
// Created on: <09-May-2003 16:07:24 sp>
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

/*! \file eznotificationcollection.php
*/

/*!
  \class eZNotificationCollection eznotificationcollection.php
  \brief The class eZNotificationCollection does

*/
include_once( 'kernel/classes/notification/eznotificationcollectionitem.php' );

class eZNotificationCollection extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZNotificationCollection( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "event_id" => array( 'name' => "EventID",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "handler" => array( 'name' => "Handler",
                                                             'datatype' => 'string',
                                                             'default' => '',
                                                             'required' => true ),
                                         "transport" => array( 'name' => "Transport",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         "data_subject" => array( 'name' => "DataText1",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text" => array( 'name' => "DataText2",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'items' => 'items',
                                                      'items_to_send' => 'itemsToSend',
                                                      'item_count' => 'itemCount' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZNotificationCollection",
                      "name" => "eznotificationcollection" );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == 'items' ||
             $attr == 'items_to_send' ||
             $attr == 'item_count' )
        {
            return true;
        }
        return eZPersistentObject::hasAttribute( $attr );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'items' )
        {
            return $this->items();
        }
        if ( $attr == 'items_to_send' )
        {
            return $this->itemsToSend();
        }
        if ( $attr == 'item_count' )
        {
            return $this->itemCount();
        }
        return eZPersistentObject::attribute( $attr );

    }


    function &create( $eventID, $handler, $transport )
    {
        return new eZNotificationCollection( array( 'event_id' => $eventID,
                                                    'handler' => $handler,
                                                    'transport' => $transport ) );
    }

    function &addItem( $address, $sendDate = 0 )
    {
        $item =& eZNotificationCollectionItem::create( $this->attribute( 'id' ), $this->attribute( 'event_id' ), $address, $sendDate = 0  );
        $item->store();
        return $item;
    }

    function &items()
    {
        return eZPersistentObject::fetchObjectList( eZNotificationCollectionItem::definition(),
                                                    null, array( 'collection_id' => $this->attribute( 'id' ) ), null,null,
                                                    true );
    }

    function &itemCount()
    {
        $result =& eZPersistentObject::fetchObjectList( eZNotificationCollectionItem::definition(),
                                                        array(), array( 'collection_id' => $this->attribute( 'id' ) ), array(),null,
                                                        false,false, array( array( 'operation' => 'count(*)',
                                                                                   'name' => 'count' ) ) );
        return $result[0]['count'];
    }

    function &itemsToSend()
    {
        return eZPersistentObject::fetchObjectList( eZNotificationCollectionItem::definition(),
                                                    null, array( 'collection_id' => $this->attribute( 'id' ),
                                                                 'send_date' => 0 ),
                                                    null, null, true );

    }

    function &fetchForHandler( $handler, $eventID, $transport )
    {
        return eZPersistentObject::fetchObject( eZNotificationCollection::definition(), null,
                                                array( 'event_id' => $eventID,
                                                       'handler'=> $handler,
                                                       'transport' => $transport ) );
    }

    function &fetchListForHandler( $handler, $eventID, $transport )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationCollection::definition(), null,
                                                    array( 'event_id' => $eventID,
                                                           'handler'=> $handler,
                                                           'transport' => $transport ) );
    }

    function removeEmpty()
    {
        $query = "SELECT eznotificationcollection.id from eznotificationcollection
                  LEFT JOIN eznotificationcollection_item on eznotificationcollection.id=eznotificationcollection_item.collection_id
                  WHERE eznotificationcollection_item.collection_id IS NULL";
        $db =& eZDB::instance();
        $idArray =& $db->arrayQuery( $query );

        foreach ( $idArray as $id )
        {
            eZPersistentObject::removeObject( eZNotificationCollection::definition(), array( 'id' => $id['id'] ) );
        }
    }

    /*!
     \static
     Removes all notification collections.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        eZNotificationCollectionItem::cleanup();
        $db->query( "DELETE FROM eznotificationcollection" );
    }
}

?>

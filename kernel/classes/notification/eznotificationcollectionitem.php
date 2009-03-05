<?php
//
// Definition of eZNotificationCollectionItem class
//
// Created on: <09-May-2003 16:08:18 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  \class eZNotificationCollectionItem eznotificationcollectionitem.php
  \brief The class eZNotificationCollectionItem does

*/

class eZNotificationCollectionItem extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZNotificationCollectionItem( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "collection_id" => array( 'name' => "CollectionID",
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true,
                                                                   'foreign_class' => 'eZNotificationCollection',
                                                                   'foreign_attribute' => 'id',
                                                                   'multiplicity' => '1..*' ),
                                         "event_id" => array( 'name' => "EventID",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true,
                                                              'foreign_class' => 'eZNotificationEvent',
                                                              'foreign_attribute' => 'id',
                                                              'multiplicity' => '1..*' ),
                                         "address" => array( 'name' => "Address",
                                                             'datatype' => 'string',
                                                             'default' => '',
                                                             'required' => true ),
                                         "send_date" => array( 'name' => "SendDate",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true )  ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZNotificationCollectionItem",
                      "name" => "eznotificationcollection_item" );
    }

    static function create( $collectionID, $eventID, $address, $sendDate = 0 )
    {
        return new eZNotificationCollectionItem( array( 'collection_id' => $collectionID,
                                                        'event_id' => $eventID,
                                                        'address' => $address,
                                                        'send_date' => $sendDate ) );
    }

    static function fetchByDate( $date )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationCollectionItem::definition(),
                                                    null, array( 'send_date' => array( '<', $date ),
                                                                 'send_date' => array( '!=', 0 ) ) , null, null,
                                                    true );
    }

    static function fetchCountForEvent( $eventID )
    {
        $result = eZPersistentObject::fetchObjectList( eZNotificationCollectionItem::definition(),
                                                       array(),
                                                       array( 'event_id' => $eventID ),
                                                       false,
                                                       null,
                                                       false,
                                                       false,
                                                       array( array( 'operation' => 'count( * )',
                                                                     'name' => 'count' ) ) );
        return $result[0]['count'];
    }

    /*!
     \static
     Removes all notification collection items.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM eznotificationcollection_item" );
    }
}

?>

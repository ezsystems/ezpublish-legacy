<?php
//
// Definition of eZNotificationCollectionItem class
//
// Created on: <09-May-2003 16:08:18 sp>
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

/*! \file eznotificationcollectionitem.php
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

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "collection_id" => array( 'name' => "CollectionID",
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                         "event_id" => array( 'name' => "EventID",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "address" => array( 'name' => "Address",
                                                             'datatype' => 'string',
                                                             'default' => '',
                                                             'required' => true ),
                                         "send_date" => array( 'name' => "SendDate",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true )  ),
                      "keys" => array( "id" ),
//                      "function_attributes" => array( ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZNotificationCollectionItem",
                      "name" => "eznotificationcollection_item" );

    }

    function &create( $collectionID, $eventID, $address, $sendDate = 0 )
    {
        return new eZNotificationCollectionItem( array( 'collection_id' => $collectionID,
                                                        'event_id' => $eventID,
                                                        'address' => $address,
                                                        'send_date' => $sendDate ) );
    }

    function fetchByDate( $date )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationCollectionItem::definition(),
                                                    null, array( 'send_date' => array( '<', $date ),
                                                                 'send_date' => array( '!=', 0 ) ) , null,null,
                                                    true );
    }

    function fetchCountForEvent( $eventID )
    {
        $result =& eZPersistentObject::fetchObjectList( eZNotificationCollectionItem::definition(),
                                                        array(), array( 'event_id' => $eventID ), array(),null,
                                                        false,false, array( array( 'operation' => 'count(*)',
                                                                                   'name' => 'count' ) ) );
        return $result[0]['count'];
    }

    /*!
     \static
     Removes all notification collection items.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM eznotificationcollection_item" );
    }
}

?>

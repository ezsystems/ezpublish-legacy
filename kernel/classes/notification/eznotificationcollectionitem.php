<?php
/**
 * File containing the eZNotificationCollectionItem class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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

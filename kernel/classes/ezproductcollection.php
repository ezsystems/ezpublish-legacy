<?php
//
// Definition of eZProductCollection class
//
// Created on: <04-Jul-2002 13:40:41 bf>
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

/**
 * eZProductCollection is a container class which handles groups of products
 **/

class eZProductCollection extends eZPersistentObject
{
    function eZProductCollection( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "created" => array( 'name' => "Created",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'currency_code' => array( 'name' => 'CurrencyCode',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZProductCollection",
                      "name" => "ezproductcollection" );
    }

    /**
     * Creates a new empty collection and returns it.
     *
     * @return eZProductCollection
     **/
    static function create( )
    {
        $row = array( "created" => time() );
        return new eZProductCollection( $row );
    }

    /**
     * Clones the collection object and returns it.
     * The ID of the clone is erased.
     **/
    function __clone()
    {
        $this->setAttribute( 'id', null );
    }

    /**
     * Copies the collection object, the collection items and options.
     *
     * @note The new collection will already be present in the database.
     *
     * @return eZProductCollection The new collection object.
     **/
    function copy()
    {
        $collection = clone $this;

        $db = eZDB::instance();
        $db->begin();
        $collection->store();

        $oldItems = $this->itemList();
        foreach ( $oldItems as $oldItem )
        {
            $item = $oldItem->copy( $collection->attribute( 'id' ) );
        }
        $db->commit();
        return $collection;
    }

    /**
     * Fetches an eZProductCollection based on its ID
     *
     * @param int $productCollectionID
     * @param bool $asObject
     *        If true, return an object. if false, returns an array
     *
     * @return array|eZProductCollection
     **/
    static function fetch( $productCollectionID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZProductCollection::definition(),
                                                null,
                                                array( 'id' => $productCollectionID ),
                                                $asObject );
    }

    /**
     * Returns all production collection items as an array.
     *
     * @param bool $asObject
     *        If true, return an object. if false, returns an array
     *
     * @return array(eZProductCollection|array)
     **/
    function itemList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                    null, array( "productcollection_id" => $this->ID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function verify( $id )
    {
        $invalidItemArray = array();
        $collection = eZProductCollection::fetch( $id );
        if ( !is_object( $collection ) )
             return $invalidItemArray;

        $currency = $collection->attribute( 'currency_code' );
        $productItemList = eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                                 null, array( "productcollection_id" => $id ),
                                                                 null,
                                                                 null,
                                                                 true );
        $isValid = true;

        foreach ( $productItemList as $productItem )
        {
            if ( !$productItem->verify( $currency ) )
            {
                $invalidItemArray[] = $productItem;
                $isValid = false;
            }
        }
        if ( !$isValid )
        {
            return $invalidItemArray;
        }
        return $isValid;
    }

    /**
     * Removes all product collections based on a product collection ID list
     * Will also remove the product collection items.
     *
     * @param array $productCollectionIDList array of eZProductCollection IDs
     *
     * @return void
     **/
    static function cleanupList( $productCollectionIDList )
    {
        $db = eZDB::instance();
        $db->begin();

        // Purge shipping information associated with product collections being removed.
        foreach ( $productCollectionIDList as $productCollectionID )
            eZShippingManager::purgeShippingInfo( $productCollectionID );

        eZProductCollectionItem::cleanupList( $productCollectionIDList );
        $where = $db->generateSQLINStatement( $productCollectionIDList, 'id', false, false, 'int' );
        $db->query( "DELETE FROM ezproductcollection WHERE {$where}" );
        $db->commit();
    }
}

?>
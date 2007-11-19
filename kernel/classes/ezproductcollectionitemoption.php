<?php
//
// Definition of eZProductCollectionItemOption class
//
// Created on: <10-æÅ×-2003 16:04:18 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezproductcollectionitemoption.php
*/

/*!
  \class eZProductCollectionItemOption ezproductcollectionitemoption.php
  \brief The class eZProductCollectionItemOption does

*/

class eZProductCollectionItemOption extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZProductCollectionItemOption( $row )
    {
        $this->eZPersistentObject( $row );

    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'item_id' => array( 'name' => 'ItemID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZProductCollectionItem',
                                                             'foreign_attribute' => 'id',
                                                             'multiplicity' => '1..*' ),
                                         'option_item_id' => array( 'name' => 'OptionItemID',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true,
                                                                    'foreign_class' => 'eZProductCollectionItemOption',
                                                                    'foreign_attribute' => 'id',
                                                                    'multiplicity' => '1..*' ),
                                         'object_attribute_id' => array( 'name' => 'ObjectAttributeID',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true,
                                                                         'foreign_class' => 'eZContentObjectAttribute',
                                                                         'foreign_attribute' => 'id',
                                                                         'multiplicity' => '1..*' ),
                                         'name' => array( 'name' => 'Name',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         'value' => array( 'name' => 'Value',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'price' => array( 'name' => 'Price',
                                                           'datatype' => 'float',
                                                           'default' => 0,
                                                           'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZProductCollectionItemOption",
                      "name" => "ezproductcollection_item_opt" );
    }

    static function create( $productCollectionItemID, $optionItemID, $optionName, $optionValue, $optionPrice, $attributeID )
    {
        $row = array( 'item_id' => $productCollectionItemID,
                      'option_item_id' => $optionItemID,
                      'name' => $optionName,
                      'value' => $optionValue,
                      'price' => $optionPrice,
                      'object_attribute_id' => $attributeID );
        return new eZProductCollectionItemOption( $row );
    }

    /*!
     Clones the collection item option object and returns it. The ID of the clone is erased.
    */
    function __clone()
    {
        $this->setAttribute( 'id', null );
    }

    /*!
     Copies the collection object item option,
     the new copy will point to the collection item \a $collectionItemID.
     \return the new collection item option object.
     \note The new collection item option will already be present in the database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function copy( $collectionItemID )
    {
        $item = clone $this;
        $item->setAttribute( 'item_id', $collectionItemID );
        $item->store();
        return $item;
    }

    static function fetchList( $productCollectionItemID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZProductCollectionItemOption::definition(),
                                                    null, array( "item_id" => $productCollectionItemID ),
                                                    array( "id" => "ASC"  ),
                                                    null,
                                                    $asObject );
    }

    /*!
     \static
     Removes all product collections options which are related to the collection items specified in the array \a $itemIDList.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function cleanupList( $itemIDList )
    {
        $db = eZDB::instance();
        $idText = $db->implodeWithTypeCast( ', ', $itemIDList, 'int' );
        $db->query( "DELETE FROM ezproductcollection_item_opt WHERE item_id IN ( $idText )" );
    }

}

?>

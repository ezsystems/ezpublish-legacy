<?php
//
// Definition of eZProductCollectionItem class
//
// Created on: <04-Jul-2002 13:45:10 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*!
  \class eZProductCollectionItem ezproductcollection.php
  \brief eZProductCollectionItem handles one product item
  \ingroup eZKernel

*/

//include_once( "kernel/classes/ezpersistentobject.php" );
//include_once( "kernel/classes/ezcontentobject.php" );
//include_once( "kernel/classes/ezproductcollectionitemoption.php" );


class eZProductCollectionItem extends eZPersistentObject
{
    function eZProductCollectionItem( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "productcollection_id" => array( 'name' => "ProductCollectionID",
                                                                          'datatype' => 'integer',
                                                                          'default' => 0,
                                                                          'required' => true,
                                                                          'foreign_class' => 'eZProductCollection',
                                                                          'foreign_attribute' => 'id',
                                                                          'multiplicity' => '1..*' ),
                                         "contentobject_id" => array( 'name' => "ContentObjectID",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZContentObject',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ),
                                         "item_count" => array( 'name' => "ItemCount",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "price" => array( 'name' => "Price",
                                                           'datatype' => 'float',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'is_vat_inc' => array( 'name' => "IsVATIncluded",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'vat_value' => array( 'name' => "VATValue",
                                                               'datatype' => 'float',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'discount' => array( 'name' => "DiscountValue",
                                                              'datatype' => 'float',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'name' => array( 'name' => 'Name',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ) ),
                      'function_attributes' => array( 'contentobject' => 'contentObject',
                                                      'option_list' => 'optionList' ),
                      "keys" => array( "id" ),
                      'sort' => array( 'id' => 'asc' ),
                      "increment_key" => "id",
                      "relations" => array( "contentobject_id" => array( "class" => "ezcontentobject",
                                                                         "field" => "id" ),
                                            "productcollection_id" => array( "class" => "ezproductcollection",
                                                                             "field" => "id" ) ),
                      "class_name" => "eZProductCollectionItem",
                      "name" => "ezproductcollection_item" );
    }

    /*!
     Creates a new empty collection item which belongs to
     collection \a $collectionID and returns it.
    */
    static function create( $productCollectionID )
    {
        return new eZProductCollectionItem( array( "productcollection_id" => $productCollectionID ) );
    }

    /*!
     Clones the collection item object and returns it. The ID of the clone is erased.
    */
    function __clone()
    {
        $this->setAttribute( 'id', null );
    }

    /*!
     Copies the collection object item and the option,
     the new copy will point to the collection \a $collectionID.
     \return the new collection item object.
     \note The new collection item will already be present in the database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function copy( $collectionID )
    {
        $item = clone $this;
        $item->setAttribute( 'productcollection_id', $collectionID );
        $item->store();
        $oldItemOptionList = $this->optionList();
        foreach ( $oldItemOptionList as $oldItemOption )
        {
            $itemOption = $oldItemOption->copy( $item->attribute( 'id' ) );
        }
        return $item;
    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZProductCollectionItem::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    static function fetchList( $conditions = null, $asObjects = true, $offset = false, $limit = false )
    {
        $limitation = null;
        if ( $offset !== false or $limit !== false )
            $limitation = array( 'offset' => $offset, 'length' => $limit );

        return eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                    null,
                                                    $conditions,
                                                    null,
                                                    $limitation,
                                                    $asObjects );
    }

    /*!
     \return the discount percent for the current item
    */
    function discountPercent()
    {
        $discount = false;
        return $discount;
    }

    /*!
     \return Returns the content object defining the product.
    */
    function contentObject()
    {
        if ( $this->ContentObject === null )
        {
            if ( $this->ContentObjectID == 0 )
            {
                return null;
            }
            $this->ContentObject = eZContentObject::fetch( $this->ContentObjectID );
        }
        return $this->ContentObject;
    }

    function optionList()
    {
        return eZProductCollectionItemOption::fetchList( $this->attribute( 'id' ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeThis()
    {
        $itemOptionList = eZProductCollectionItemOption::fetchList( $this->attribute( 'id' ) );

        $db = eZDB::instance();
        $db->begin();
        foreach( $itemOptionList as $itemOption )
        {
            $itemOption->remove();
        }
        eZPersistentObject::remove();
        $db->commit();
    }

    /*!
     Goes trough all options and finds the attribute they points to and calls productOptionInformation()
     to fetch the option data.
     \return The total price of all options.
    */
    function calculatePriceWithOptions( $currency = false )
    {
        //include_once( 'kernel/shop/classes/ezshopfunctions.php' );

        $optionList = eZProductCollectionItemOption::fetchList( $this->attribute( 'id' ) );
        $contentObject = $this->contentObject();
        $contentObjectVersion = $contentObject->attribute( 'current_version' );
        $optionsPrice = 0.0;
        if ( count( $optionList ) > 0 )
        {
            $db = eZDB::instance();
            $db->begin();
            foreach( $optionList as $option )
            {
                $objectAttribute = eZContentObjectAttribute::fetch( $option->attribute( 'object_attribute_id' ), $contentObjectVersion );
                if ( $objectAttribute == null )
                {
                    $optionsPrice += 0.0;
                    continue;
                }

                $dataType = $objectAttribute->dataType();
                $optionData = $dataType->productOptionInformation( $objectAttribute, $option->attribute( 'option_item_id' ), $this );

                if ( $optionData )
                {
                    $optionData['additional_price'] = eZShopFunctions::convertAdditionalPrice( $currency, $optionData['additional_price'] );
                    $option->setAttribute( 'price', $optionData['additional_price'] );
                    $option->store();

                    $optionsPrice += $optionData['additional_price'];
                }

            }
            $db->commit();
        }
        return $optionsPrice;
    }

    function verify( $currency = false )
    {
        $contentObject = $this->attribute( 'contentobject' );
        if ( $contentObject != null && $contentObject->attribute( 'main_node_id' ) > 0 )
        {
            //include_once( 'kernel/shop/classes/ezshopfunctions.php' );

            $attributes = $contentObject->contentObjectAttributes();
            $optionsPrice = $this->calculatePriceWithOptions( $currency );

            foreach ( $attributes as $attribute )
            {
                $dataType = $attribute->dataType();
                if ( eZShopFunctions::isProductDatatype( $dataType->isA() ) )
                {
                    $priceObj = $attribute->content();

                    $price = $priceObj->attribute( 'price' );
                    $priceWithOptions = $price + $optionsPrice;
                    if ( $priceWithOptions != $this->attribute( 'price' ) )
                    {
                        return false;
                    }
                    if ( $priceObj->attribute( 'is_vat_included' ) != ( $this->attribute( 'is_vat_inc' ) > 0 ) )
                    {
                        return false;
                    }
                    if ( $priceObj->attribute( 'vat_percent' ) != $this->attribute( 'vat_value' ) )
                    {
                        return false;
                    }
                    if ( $priceObj->discount() != $this->attribute( 'discount' ) )
                    {
                        return false;
                    }
                    return true;
                }
            }

        }
        return false;
    }

    /*!
     \static
     Removes all product collection items which related to the product collections specified in the array \a $productCollectionIDList.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanupList( $productCollectionIDList )
    {
        $db = eZDB::instance();
        $db->begin();
        $idText = $db->implodeWithTypeCast( ', ', $productCollectionIDList, 'int' );
        $rows = $db->arrayQuery( "SELECT id FROM ezproductcollection_item WHERE productcollection_id IN ( $idText )" );
        if ( count( $rows ) > 0 )
        {
            $itemIDList = array();
            foreach ( $rows as $row )
            {
                $itemIDList[] = $row['id'];
            }
            //include_once( 'kernel/classes/ezproductcollectionitemoption.php' );
            eZProductCollectionItemOption::cleanupList( $itemIDList );
        }
        $db->query( "DELETE FROM ezproductcollection_item WHERE productcollection_id IN ( $idText )" );
        $db->commit();

    }

    /// Stores the content object
    public $ContentObject = null;
}

?>

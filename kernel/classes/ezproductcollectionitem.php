<?php
//
// Definition of eZProductCollectionItem class
//
// Created on: <04-Jul-2002 13:45:10 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.6.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezproductcollectionitemoption.php" );


class eZProductCollectionItem extends eZPersistentObject
{
    function eZProductCollectionItem( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "productcollection_id" => array( 'name' => "ProductCollectionID",
                                                                          'datatype' => 'integer',
                                                                          'default' => 0,
                                                                          'required' => true ),
                                         "contentobject_id" => array( 'name' => "ContentObjectID",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
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
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'name' => array( 'name' => 'Name',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ) ),
                      'function_attributes' => array( 'contentobject' => 'contentObject',
                                                      'option_list' => 'optionList' ),
                      "keys" => array( "id" ),
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
    function &create( $productCollectionID )
    {
        $row = array( "productcollection_id" => $productCollectionID );
        return new eZProductCollectionItem( $row );
    }

    /*!
     Clones the collection item object and returns it. The ID of the clone is erased.
    */
    function &clone()
    {
        $item = $this;
        $item->setAttribute( 'id', null );
        return $item;
    }

    /*!
     Copies the collection object item and the option,
     the new copy will point to the collection \a $collectionID.
     \return the new collection item object.
     \note The new collection item will already be present in the database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function &copy( $collectionID )
    {
        $item =& $this->clone();
        $item->setAttribute( 'productcollection_id', $collectionID );
        $item->store();
        $oldItemOptionList =& $this->optionList();
        foreach ( array_keys( $oldItemOptionList ) as $oldItemOptionKey )
        {
            $oldItemOption =& $oldItemOptionList[$oldItemOptionKey];
            $itemOption =& $oldItemOption->copy( $item->attribute( 'id' ) );
        }
        return $item;
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZProductCollectionItem::definition(),
                                                null,
                                                array( "id" => $id
                                                       ),
                                                $asObject );
    }

    function &attribute( $attr )
    {
        switch ( $attr )
        {
            case "contentobject" :
            {
                return $this->contentObject(  );
            }break;
            case "option_list" :
            {
                return $this->optionList(  );
            }break;

            default :
            {
                return eZPersistentObject::attribute( $attr );
            }break;
        }
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "contentobject" || $attr == 'option_list' )
            return true;
        else
            return eZPersistentObject::hasAttribute( $attr );
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
    function &contentObject()
    {
        if ( $this->ContentObject === null )
        {
            if ( $this->ContentObjectID == 0 )
            {
                $retValue = null;
                return $retValue;
            }
            $this->ContentObject =& eZContentObject::fetch( $this->ContentObjectID );
        }
        return $this->ContentObject;
    }

    function &optionList()
    {
        return eZProductCollectionItemOption::fetchList( $this->attribute( 'id' ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function remove()
    {
        $itemOptionList =& eZProductCollectionItemOption::fetchList( $this->attribute( 'id' ) );

        $db =& eZDB::instance();
        $db->begin();
        foreach( array_keys( $itemOptionList ) as $key )
        {
            $itemOption =& $itemOptionList[$key];
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
    function calculatePriceWithOptions()
    {
        $optionList =& eZProductCollectionItemOption::fetchList( $this->attribute( 'id' ) );
        $contentObject =& $this->contentObject();
        $contentObjectVersion =& $contentObject->attribute( 'current_version' );
        $optionsPrice = 0.0;
        foreach( $optionList as $option )
        {
            $objectAttribute =& eZContentObjectAttribute::fetch( $option->attribute( 'object_attribute_id' ), $contentObjectVersion );
            if ( $objectAttribute == null )
            {
                $optionsPrice += 0.0;
                continue;
            }

            $dataType =& $objectAttribute->dataType();
            $optionData = $dataType->productOptionInformation( $objectAttribute, $option->attribute( 'option_item_id' ), $this, $option );

            if ( $optionData )
            {
                $optionsPrice += $optionData['additional_price'];
            }

        }
        return $optionsPrice;
    }

    function verify()
    {
        $contentObject =& $this->attribute( 'contentobject' );
        if ( $contentObject != null && $contentObject->attribute( 'main_node_id' ) > 0 )
        {
            $attributes = $contentObject->contentObjectAttributes();
            $optionsPrice = $this->calculatePriceWithOptions();
/*            if (  $optionsPrice === false )
            {
                eZDebug::writeDebug( $optionPrice , "Option price is not the same" );

                return false;
            } */
            foreach ( $attributes as $attribute )
            {
                $dataType =& $attribute->dataType();
                if ( $dataType->isA() == "ezprice" )
                {
                    $priceObj =& $attribute->content();

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
    function cleanupList( $productCollectionIDList )
    {
        $db =& eZDB::instance();
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
            include_once( 'kernel/classes/ezproductcollectionitemoption.php' );
            eZProductCollectionItemOption::cleanupList( $itemIDList );
        }
        $db->query( "DELETE FROM ezproductcollection_item WHERE productcollection_id IN ( $idText )" );
        $db->commit();

    }

    /// Stores the content object
    var $ContentObject = null;
}

?>

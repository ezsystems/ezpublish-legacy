<?php
//
// Definition of eZOrder class
//
// Created on: <31-Jul-2002 14:00:03 bf>
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

/*!
  \class eZOrder ezorder.php
  \brief eZOrder handles orders
  \ingroup eZKernel

  \sa eZProductCollection eZBasket
*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezproductcollection.php" );
include_once( "kernel/classes/ezproductcollectionitem.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/classes/ezuserdiscountrule.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "kernel/classes/ezorderitem.php" );

class eZOrder extends eZPersistentObject
{
    /*!
    */
    function eZOrder( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "is_temporary" => "IsTemporary",
                                         "user_id" => "UserID",
                                         "productcollection_id" => "ProductCollectionID",
                                         "created" => "Created"
                                         ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZOrder",
                      "name" => "ezorder" );
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZOrder::definition(),
                                                null,
                                                array( "id" => $id
                                                      ),
                                                $asObject );
    }

    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZOrder::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    function attribute( $attr )
    {
        if ( $attr == "product_items" )
            return $this->productItems();
        if ( $attr == "order_items" )
            return $this->orderItems();
        if ( $attr == "product_total_inc_vat" )
            return $this->productTotalIncVAT();
        if ( $attr == "product_total_ex_vat" )
            return $this->productTotalExVAT();
        if ( $attr == "total_inc_vat" )
            return $this->totalIncVAT();
        if ( $attr == "total_ex_vat" )
            return $this->totalExVAT();
        else if ( $attr == "user" )
            return $this->user();
        else
            return eZPersistentObject::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "order_items" )
            return true;
        else if ( $attr == "product_items" )
            return true;
        else if ( $attr == "product_total_inc_vat" )
            return true;
        else if ( $attr == "product_total_ex_vat" )
            return true;
        else if ( $attr == "total_inc_vat" )
            return true;
        else if ( $attr == "total_ex_vat" )
            return true;
        else if ( $attr == "user" )
            return true;
        else
            return eZPersistentObject::hasAttribute( $attr );
    }

    function &productItems( $asObject=true )
    {
        $productItems =& eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                       null, array( "productcollection_id" => $this->ProductCollectionID
                                                                    ),
                                                       null,
                                                       null,
                                                       $asObject );

        $addedProducts = array();
        foreach ( $productItems as  $productItem )
        {
            $discountPercent = 0.0;
            $isVATIncluded = true;
            $id = $productItem->attribute( 'id' );
            $contentObject = $productItem->attribute( 'contentobject' );

            if ( $contentObject !== null )
            {
                $attributes = $contentObject->contentObjectAttributes();
                foreach ( $attributes as $attribute )
                {
                    $dataType =& $attribute->dataType();
                    if ( $dataType->isA() == "ezprice" )
                    {
                        $classAttribute =& $attribute->attribute( 'contentclass_attribute' );
                        $VATID =  $classAttribute->attribute( EZ_DATATYPESTRING_VAT_ID_FIELD );
                        $VATIncludeValue = $classAttribute->attribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD );
                        if ( $VATIncludeValue==0 or $VATIncludeValue==1 )
                            $isVATIncluded = true;
                        else
                            $isVATIncluded = false;
                        $vatType =& eZVatType::fetch( $VATID );
                        if ( get_class( $vatType ) == 'ezvattype' )
                            $VATValue = $vatType->attribute( 'percentage' );
                        else
                            $VATValue = 0.0;

                        $priceObj =& $attribute->content();
                        $discountPercent = $priceObj->discount();
                    }
                }

                $nodeID = $contentObject->attribute( 'main_node_id' );
                $objectName = $contentObject->attribute( 'name' );
                $count = $productItem->attribute( 'item_count' );
                $price = $productItem->attribute( 'price' );
                if ( $isVATIncluded )
                {
                    $priceExVAT = $price / ( 100 + $VATValue ) * 100;
                    $priceIncVAT = $price;
                    $totalPriceExVAT = $count * $priceExVAT * ( 100 - $discountPercent ) / 100;
                    $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                }
                else
                {
                    $priceExVAT = $price;
                    $priceIncVAT = $price * ( 100 + $VATValue ) / 100;
                    $totalPriceExVAT = $count * $priceExVAT  * ( 100 - $discountPercent ) / 100;
                    $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                }
                $addedProduct = array( "id" => $id,
                                       "vat_value" => $VATValue,
                                       "item_count" => $count,
                                       "node_id" => $nodeID,
                                       "object_name" => $objectName,
                                       "price_ex_vat" => $priceExVAT,
                                       "price_inc_vat" => $priceIncVAT,
                                       "discount_percent" => $discountPercent,
                                       "total_price_ex_vat" => $totalPriceExVAT,
                                       "total_price_inc_vat" => $totalPriceIncVAT );
                $addedProducts[] = $addedProduct;
            }
        }
        return $addedProducts;
    }

    function &productTotalIncVAT()
    {
        $items =& $this->productItems();

        $total = 0.0;
        foreach ( $items as $item )
        {
            $total += $item['total_price_inc_vat'];
        }
        return $total;
    }

    function &productTotalExVAT()
    {
        $items =& $this->productItems();

        $total = 0.0;
        foreach ( $items as $item )
        {
            $total += $item['total_price_ex_vat'];
        }
        return $total;
    }

    function &orderTotalIncVAT()
    {
        $items =& $this->orderItems();

        $total = 0.0;
        foreach ( $items as $item )
        {
            $total += $item->attribute( 'price_inc_vat' );
        }
        return $total;
    }

    function &orderTotalExVAT()
    {
        $items =& $this->orderItems();

        $total = 0.0;
        foreach ( $items as $item )
        {
            $total += $item->attribute( 'price_ex_vat' );
        }
        return $total;
    }

    function &totalIncVAT()
    {
        return $this->productTotalIncVAT() + $this->orderTotalIncVAT();
    }

    function &totalExVAT()
    {
        return $this->productTotalExVAT() + $this->orderTotalExVAT();
    }

    function removeItem( $itemID )
    {
        $item = eZProductCollectionItem::fetch( $itemID );
        $item->remove();
    }

    /*!
     \return the total VAT value of the order
    */
    function &totalVAT()
    {

    }

    function &orderItems()
    {
        $items =& eZOrderItem::fetchList( $this->ID );

        return $items;
    }

    /*!
     \return the user who has created the order.
    */
    function &user()
    {
        return eZUser::fetch( $this->UserID );
    }
}

?>

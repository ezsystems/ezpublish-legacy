<?php
//
// Definition of eZWishList class
//
// Created on: <01-Aug-2002 10:22:02 bf>
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


/*!
  \class eZWishList ezwishlist.php
  \brief eZWishList handles shopping wish lists
  \ingroup eZKernel

  \sa eZProductCollection
*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezproductcollection.php" );
include_once( "kernel/classes/ezproductcollectionitem.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/classes/ezuserdiscountrule.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );

class eZWishList extends eZPersistentObject
{
    /*!
    */
    function eZWishList( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZCard class.
    */
    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "user_id" => array( 'name' => "UserID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "productcollection_id" => array( 'name' => "ProductCollectionID",
                                                                          'datatype' => 'integer',
                                                                          'default' => 0,
                                                                          'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZWishList",
                      "name" => "ezwishlist" );
    }

    function &attribute( $attr )
    {
        if ( $attr == "items" )
            return $this->items();
        else
            return eZPersistentObject::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "items" )
            return true;
        else
            return eZPersistentObject::hasAttribute( $attr );
    }

    function discountPercent()
    {
        $discountPercent = 0;
        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $nodes =& eZContentObjectTreeNode::fetchByContentObjectID( $userID );
        $idArray = array();
        $idArray[] = $userID;
        foreach ( $nodes as $node )
        {
            $parentNodeID = $node->attribute( 'parent_node_id' );
            $idArray[] = $parentNodeID;
        }
        $rules =& eZUserDiscountRule::fetchByUserIDArray( $idArray );
        foreach ( $rules as $rule )
        {
            $percent = $rule->attribute( 'discount_percent' );
            if ( $discountPercent < $percent )
                $discountPercent = $percent;
        }
        return $discountPercent;
    }

    function &items( $asObject = true )
    {
        $productItems =& eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                       null, array( "productcollection_id" => $this->ProductCollectionID
                                                                    ),
                                                       null,
                                                       null,
                                                       $asObject );
//        $discountPercent = $this->discountPercent();
        $addedProducts = array();
        foreach ( $productItems as  $productItem )
        {
            $discountPercent = 0.0;
            $isVATIncluded = true;
            $id = $productItem->attribute( 'id' );
            $contentObject = $productItem->attribute( 'contentobject' );

            if ( $contentObject !== null )
            {
                $vatValue = $productItem->attribute( 'vat_value' );
                $count = $productItem->attribute( 'item_count' );
                $discountPercent = $productItem->attribute( 'discount' );
                $nodeID = $contentObject->attribute( 'main_node_id' );
                $objectName = $contentObject->attribute( 'name' );

                $isVATIncluded = $productItem->attribute( 'is_vat_inc' );
                $price = $productItem->attribute( 'price' );

                if ( $isVATIncluded )
                {
                    $priceExVAT = $price / ( 100 + $vatValue ) * 100;
                    $priceIncVAT = $price;
                    $totalPriceExVAT = $count * $priceExVAT * ( 100 - $discountPercent ) / 100;
                    $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                }
                else
                {
                    $priceExVAT = $price;
                    $priceIncVAT = $price * ( 100 + $vatValue ) / 100;
                    $totalPriceExVAT = $count * $priceExVAT  * ( 100 - $discountPercent ) / 100;
                    $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                }
                $addedProduct = array( "id" => $id,
                                       "vat_value" => $vatValue,
                                       "item_count" => $count,
                                       "node_id" => $nodeID,
                                       "object_name" => $objectName,
                                       "price_ex_vat" => $priceExVAT,
                                       "price_inc_vat" => $priceIncVAT,
                                       "discount_percent" => $discountPercent,
                                       "total_price_ex_vat" => $totalPriceExVAT,
                                       "total_price_inc_vat" => $totalPriceIncVAT,
                                       'item_object' =>$productItem );
                $addedProducts[] = $addedProduct;
            }

        }
        return $addedProducts;
    }

    function removeItem( $itemID )
    {
        $item = eZProductCollectionItem::fetch( $itemID );
        $item->remove();
    }

    /*!
     Will return the wish list for the current user. If a wish list does not exist one will be created.
     \return current eZWishList object
    */
    function &currentWishList( $asObject=true )
    {
        $http =& eZHTTPTool::instance();

        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $WishListArray =& eZPersistentObject::fetchObjectList( eZWishList::definition(),
                                                          null, array( "user_id" => $userID
                                                                       ),
                                                          null, null,
                                                          $asObject );

        $currentWishList = false;
        if ( count( $WishListArray ) == 0 )
        {
            $collection =& eZProductCollection::create();
            $collection->store();

            $currentWishList = new eZWishList( array( "user_id" => $userID,
                                              "productcollection_id" => $collection->attribute( "id" ) ) );
            $currentWishList->store();
        }
        else
        {
            $currentWishList =& $WishListArray[0];
        }
        return $currentWishList;
    }

    /*!
     \static
     Removes all wishlists from the database.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $rows = $db->arrayQuery( "SELECT productcollection_id FROM ezwishlist" );
        if ( count( $rows ) > 0 )
        {
            $productCollectionIDList = array();
            foreach ( $rows as $row )
            {
                $productCollectionIDList[] = $row['productcollection_id'];
            }
            eZProductCollection::cleanupList( $productCollectionIDList );
        }
        $db->query( "DELETE FROM ezwishlist" );
    }
}

?>

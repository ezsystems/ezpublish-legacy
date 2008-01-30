<?php
//
// Definition of eZWishList class
//
// Created on: <01-Aug-2002 10:22:02 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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
    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "user_id" => array( 'name' => "UserID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '1..*' ),
                                         "productcollection_id" => array( 'name' => "ProductCollectionID",
                                                                          'datatype' => 'integer',
                                                                          'default' => 0,
                                                                          'required' => true,
                                                                          'foreign_class' => 'eZProductCollection',
                                                                         'foreign_attribute' => 'id',
                                                                         'multiplicity' => '1..*' ) ),
                      "keys" => array( "id" ),
                      'function_attributes' => array( 'items' => 'items' ),
                      "increment_key" => "id",
                      "class_name" => "eZWishList",
                      "name" => "ezwishlist" );
    }


    function discountPercent()
    {
        $discountPercent = 0;
        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $nodes = eZContentObjectTreeNode::fetchByContentObjectID( $userID );
        $idArray = array();
        $idArray[] = $userID;
        foreach ( $nodes as $node )
        {
            $parentNodeID = $node->attribute( 'parent_node_id' );
            $idArray[] = $parentNodeID;
        }
        $rules = eZUserDiscountRule::fetchByUserIDArray( $idArray );
        foreach ( $rules as $rule )
        {
            $percent = $rule->attribute( 'discount_percent' );
            if ( $discountPercent < $percent )
                $discountPercent = $percent;
        }
        return $discountPercent;
    }

    function itemCount( $alternativeProductionID = false )
    {
        $countRes = eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                         array(),
                                                         array( "productcollection_id" => ( $alternativeProductionID === false ) ? $this->ProductCollectionID : $alternativeProductionID ),
                                                         false,
                                                         null,
                                                         false,
                                                         false,
                                                         array( array( 'operation' => 'count( id )',
                                                                       'name' => 'count' ) ) );
        return $countRes[0]['count'];
    }

    function &items( $asObject = true, $alternativeProductionID = false, $offset = false, $limit = false )
    {
        $productItems = eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                       null,
                                                       array( 'productcollection_id' => ( $alternativeProductionID === false ) ? $this->ProductCollectionID : $alternativeProductionID ),
                                                       null,
                                                       array( 'offset' => $offset,
                                                              'length' => $limit ),
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
                }
                else
                {
                    $priceExVAT = $price;
                    $priceIncVAT = $price * ( 100 + $vatValue ) / 100;
                }

                $totalPriceExVAT = $count * $priceExVAT  * ( 100 - $discountPercent ) / 100;
                $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;

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

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeItem( $itemID )
    {
        $item = eZProductCollectionItem::fetch( $itemID );
        $item->remove();
    }

    /*!
     Will return the wish list for the current user. If a wish list does not exist one will be created.
     \return current eZWishList object
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function &currentWishList( $asObject=true )
    {
        $http =& eZHTTPTool::instance();

        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $WishListArray = eZPersistentObject::fetchObjectList( eZWishList::definition(),
                                                          null, array( "user_id" => $userID
                                                                       ),
                                                          null, null,
                                                          $asObject );

        $currentWishList = false;
        if ( count( $WishListArray ) == 0 )
        {
            $collection = eZProductCollection::create();
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
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->begin();
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
        $db->commit();
    }

    /*!
     \static
     Remove wish list entries belonging to user \a $userID
    */
    function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZWishList::definition(),
                                          array( 'user_id' => $userID ) );
    }
}

?>

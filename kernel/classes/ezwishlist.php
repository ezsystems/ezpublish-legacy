<?php
/**
 * File containing the eZWishList class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZWishList ezwishlist.php
  \brief eZWishList handles shopping wish lists
  \ingroup eZKernel

  \sa eZProductCollection
*/

class eZWishList extends eZPersistentObject
{
    function eZWishList( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZCard class.
    */
    static function definition()
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
        $user = eZUser::currentUser();
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
                                                                       'name' => 'count' ) ),
                                                         array( 'ezcontentobject_tree' ),
                                                         ' AND ezproductcollection_item.contentobject_id = ezcontentobject_tree.contentobject_id' );
        return $countRes[0]['count'];
    }

    function items( $asObject = true, $alternativeProductionID = false, $offset = false, $limit = false )
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

            //if the node is put into trash/delete, don't fetch it
            if ( $contentObject !== null && $contentObject->attribute( 'main_node_id' ) !== null )
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
    static function removeItem( $itemID )
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
    static function currentWishList( $asObject=true )
    {
        $http = eZHTTPTool::instance();

        $user = eZUser::currentUser();
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
            $currentWishList = $WishListArray[0];
        }
        return $currentWishList;
    }

    /*!
     \static
     Removes all wishlists from the database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
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
     Remove wish list entries belonging to user \a $userID
    */
    static function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZWishList::definition(),
                                          array( 'user_id' => $userID ) );
    }
}

?>

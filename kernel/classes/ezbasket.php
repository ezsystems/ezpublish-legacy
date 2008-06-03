<?php
//
// Definition of eZBasket class
//
// Created on: <04-Jul-2002 15:28:58 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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
  \class eZBasket ezbasket.php
  \brief eZBasket handles shopping baskets
  \ingroup eZKernel

  \sa eZProductCollection
*/

//include_once( "kernel/classes/ezpersistentobject.php" );
//include_once( "kernel/classes/ezproductcollection.php" );
//include_once( "kernel/classes/ezproductcollectionitem.php" );
//include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
//include_once( "kernel/classes/ezuserdiscountrule.php" );
//include_once( "kernel/classes/ezcontentobjecttreenode.php" );
//include_once( "kernel/classes/ezshippingmanager.php" );
//include_once( "kernel/classes/ezorder.php" );

class eZBasket extends eZPersistentObject
{
    /*!
     Controls the default value for how many items are cleaned in one batch operation.
    */
    const ITEM_LIMIT = 3000;

    /*!
    */
    function eZBasket( $row )
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
                                         "session_id" => array( 'name' => "SessionID",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "productcollection_id" => array( 'name' => "ProductCollectionID",
                                                                          'datatype' => 'integer',
                                                                          'default' => '0',
                                                                          'required' => true,
                                                                          'foreign_class' => 'eZProductCollection',
                                                                          'foreign_attribute' => 'id',
                                                                          'multiplicity' => '1..*' ),
                                         "order_id" => array( 'name'     => "OrderID",
                                                              'datatype' => 'integer',
                                                              'default'  => 0,
                                                              'required' => false,
                                                              'foreign_class' => 'eZOrder',
                                                              'foreign_attribute' => 'id',
                                                              'multiplicity' => '1..*') ),
                      'function_attributes' => array( 'items' => 'items',
                                                      'total_ex_vat' => 'totalExVAT',
                                                      'total_inc_vat' => 'totalIncVAT',
                                                      'is_empty' => 'isEmpty',
                                                      'productcollection' => 'productCollection',
                                                      'items_info' => 'itemsInfo' ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZBasket",
                      "name" => "ezbasket" );
    }

    function items( $asObject = true )
    {
        $productItems = eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                             null,
                                                             array( 'productcollection_id' => $this->ProductCollectionID ),
                                                             array( 'contentobject_id' => 'desc' ),
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
                $vatValue = $productItem->attribute( 'vat_value' );

                // If VAT is unknown yet then we use zero VAT percentage for price calculation.
                $realVatValue = $vatValue;
                if ( $vatValue == -1 )
                    $vatValue = 0;

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
                                       "vat_value" => $realVatValue,
                                       "item_count" => $count,
                                       "node_id" => $nodeID,
                                       "object_name" => $objectName,
                                       "price_ex_vat" => $priceExVAT,
                                       "price_inc_vat" => $priceIncVAT,
                                       "discount_percent" => $discountPercent,
                                       "total_price_ex_vat" => $totalPriceExVAT,
                                       "total_price_inc_vat" => $totalPriceIncVAT,
                                       'item_object' => $productItem );
                $addedProducts[] = $addedProduct;
            }
        }
        return $addedProducts;
    }
    /*!
     Fetching calculated information about the product items.
    */
    function itemsInfo()
    {
        $basketInfo = array();
        // Build a price summary for the items based on VAT.
        foreach ( $this->items() as $item )
        {
            $vatValue = $item['vat_value'];
            $itemCount = abs( $item['item_count'] );
            $priceExVAT = $item['price_ex_vat'];
            $priceIncVAT = $item['price_inc_vat'];
            $totalPriceExVAT = $item['total_price_ex_vat'];
            $totalPriceIncVAT = $item['total_price_inc_vat'];

            if ( !isset( $basketInfo['price_info'][$vatValue]['price_ex_vat'] ) )
            {
                $basketInfo['price_info'][$vatValue]['price_ex_vat'] = ( $priceExVAT * $itemCount );
                $basketInfo['price_info'][$vatValue]['price_inc_vat'] = ( $priceIncVAT * $itemCount );
                $basketInfo['price_info'][$vatValue]['price_vat'] = ( $priceIncVAT - $priceExVAT ) * $itemCount;

                $basketInfo['price_info'][$vatValue]['total_price_ex_vat'] = $totalPriceExVAT;
                $basketInfo['price_info'][$vatValue]['total_price_inc_vat'] = $totalPriceIncVAT;
                $basketInfo['price_info'][$vatValue]['total_price_vat'] = $totalPriceIncVAT - $totalPriceExVAT;
            }
            else
            {
                $basketInfo['price_info'][$vatValue]['price_ex_vat'] += ( $priceExVAT * $itemCount );
                $basketInfo['price_info'][$vatValue]['price_inc_vat'] += ( $priceIncVAT * $itemCount );
                $basketInfo['price_info'][$vatValue]['price_vat'] += ( $priceIncVAT - $priceExVAT ) * $itemCount;

                $basketInfo['price_info'][$vatValue]['total_price_ex_vat'] += $totalPriceExVAT;
                $basketInfo['price_info'][$vatValue]['total_price_inc_vat'] += $totalPriceIncVAT;
                $basketInfo['price_info'][$vatValue]['total_price_vat'] += ( $totalPriceIncVAT - $totalPriceExVAT );
            }

            if ( !isset( $basketInfo['total_price_info']['total_price_ex_vat'] ) )
            {
                $basketInfo['total_price_info']['price_ex_vat'] = ( $priceExVAT * $itemCount );
                $basketInfo['total_price_info']['price_inc_vat'] = ( $priceIncVAT * $itemCount );
                $basketInfo['total_price_info']['price_vat'] = ( ( $priceIncVAT - $priceExVAT ) * $itemCount );

                $basketInfo['total_price_info']['total_price_ex_vat'] = $totalPriceExVAT;
                $basketInfo['total_price_info']['total_price_inc_vat'] = $totalPriceIncVAT;
                $basketInfo['total_price_info']['total_price_vat'] = ( $totalPriceIncVAT - $totalPriceExVAT );
            }
            else
            {
                $basketInfo['total_price_info']['price_ex_vat'] += ( $priceExVAT * $itemCount );
                $basketInfo['total_price_info']['price_inc_vat'] += ( $priceIncVAT * $itemCount );
                $basketInfo['total_price_info']['price_vat'] += ( ( $priceIncVAT - $priceExVAT ) * $itemCount );

                $basketInfo['total_price_info']['total_price_ex_vat'] += $totalPriceExVAT;
                $basketInfo['total_price_info']['total_price_inc_vat'] += $totalPriceIncVAT;
                $basketInfo['total_price_info']['total_price_vat'] += ( $totalPriceIncVAT - $totalPriceExVAT );
            }
        }

        // Add shipping cost to the total items price and add / update additional price information.
        $productCollectionID = $this->attribute( 'productcollection_id' );

        // Add additional calculated information to the basketInfo array, that can be used in the template.
        $shippingUpdateStatus = eZShippingManager::updatePriceInfo( $productCollectionID, $basketInfo );

        ksort( $basketInfo['price_info'] );
        return $basketInfo;
    }

    /*!
     Returns true if VAT percentage is known for all basket items.

     \public
     */
    function isVATKnown()
    {
        $result = true;
        foreach ( $this->items() as $item )
        {
            if ( $item['vat_value'] == -1 )
                return false;
        }

        return true;
    }

    function totalIncVAT()
    {
        $items = $this->items();

        $total = 0.0;
        foreach ( $items as $item )
        {
            $total += $item['total_price_inc_vat'];
        }
        return $total;
    }

    function totalExVAT()
    {
        $items = $this->items();

        $total = 0.0;
        foreach ( $items as $item )
        {
            $total += $item['total_price_ex_vat'];
        }
        return $total;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeItem( $itemID )
    {
        $item = eZProductCollectionItem::fetch( $itemID );
        if ( is_object( $item ) && $this->attribute( 'productcollection_id' ) == $item->attribute( 'productcollection_id' ) )
        {
            $item->remove();
        }
    }

    function isEmpty()
    {
        $items = $this->items();
        return count( $items ) < 1;
    }

    /*!
     Fetches the basket which belongs to session \a $sessionKey.
     \param $sessionKey A string containing the session key.
     \return An eZSessionBasket object or \c false if none was found.
    */
    static function fetch( $sessionKey )
    {
        return eZPersistentObject::fetchObject( eZBasket::definition(),
                                                null, array( "session_id" => $sessionKey ),
                                                true );
    }

    /*!
     Will return the basket for the current session. If a basket does not exist one will be created.
     \return current eZBasket object
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function currentBasket( $asObject=true, $byOrderID=-1 )
    {
        $basketList = array();

        if( $byOrderID != -1 )
        {
            $basketList = eZPersistentObject::fetchObjectList( eZBasket::definition(),
                                                                null, array( "order_id" => $byOrderID ),
                                                                null, null,
                                                                $asObject );
        }
        else
        {
            $http = eZHTTPTool::instance();
            $sessionID = $http->sessionID();

            $basketList = eZPersistentObject::fetchObjectList( eZBasket::definition(),
                                                                null, array( "session_id" => $sessionID ),
                                                                null, null,
                                                                $asObject );
        }

        $currentBasket = false;
        if ( count( $basketList ) == 0 )
        {
            $db = eZDB::instance();
            $db->begin();
            $collection = eZProductCollection::create();
            $collection->store();

            $currentBasket = new eZBasket( array( "session_id" => $sessionID,
                                                  "productcollection_id" => $collection->attribute( "id" ) ) );
            $currentBasket->store();
            $db->commit();
        }
        else
        {
            $currentBasket = $basketList[0];
        }
        return $currentBasket;
    }

    /*!
     Creates a temporary order for the current basket.
     The order object is returned.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function createOrder()
    {
        // Make order
        $productCollectionID = $this->attribute( 'productcollection_id' );

        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        //include_once( 'kernel/classes/ezorderstatus.php' );
        $time = time();
        $order = new eZOrder( array( 'productcollection_id' => $productCollectionID,
                                     'user_id' => $userID,
                                     'is_temporary' => 1,
                                     'created' => $time,
                                     'status_id' => eZOrderStatus::PENDING,
                                     'status_modified' => $time,
                                     'status_modifier_id' => $userID
                                     ) );

        $db = eZDB::instance();
        $db->begin();
        $order->store();

        $orderID = $order->attribute( 'id' );
        $this->setAttribute( 'order_id', $orderID );
        $this->store();
        $db->commit();

        return $order;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function updatePrices()
    {
        $productCollection = $this->attribute( 'productcollection' );
        if ( $productCollection )
        {
            //include_once( 'kernel/shop/classes/ezshopfunctions.php' );

            $currencyCode = '';
            $items = $this->items();

            $db = eZDB::instance();
            $db->begin();
            foreach( $items as $itemArray )
            {
                $item = $itemArray['item_object'];
                $productContentObject = $item->attribute( 'contentobject' );
                $priceObj = null;
                $price = 0.0;
                $attributes = $productContentObject->contentObjectAttributes();
                foreach ( $attributes as $attribute )
                {
                    $dataType = $attribute->dataType();
                    if ( eZShopFunctions::isProductDatatype( $dataType->isA() ) )
                    {
                        $priceObj = $attribute->content();
                        $price += $priceObj->attribute( 'price' );
                        break;
                    }
                }
                if ( !is_object( $priceObj ) )
                    break;

                $currency = $priceObj->attribute( 'currency' );
                $optionsPrice = $item->calculatePriceWithOptions( $currency );

                $price += $optionsPrice;
                $item->setAttribute( "price", $price );
                if ( $priceObj->attribute( 'is_vat_included' ) )
                {
                    $item->setAttribute( "is_vat_inc", '1' );
                }
                else
                {
                    $item->setAttribute( "is_vat_inc", '0' );
                }
                $item->setAttribute( "vat_value", $priceObj->VATPercent( $productContentObject ) );
                $item->setAttribute( "discount", $priceObj->attribute( 'discount_percent' ) );
                $item->store();

                $currencyCode = $priceObj->attribute( 'currency' );
            }

            $productCollection->setAttribute( 'currency_code', $currencyCode );
            $productCollection->store();
            $db->commit();

            // update prices that are related to shipping values.
            eZShippingManager::updateShippingInfo( $productCollection->attribute( 'id' ) );
        }
    }

    /*!
     \static
     Removes all baskets which are considered expired (due to session expiration).
     \note This will also remove the product collection the basket is using.
    */
    static function cleanupExpired( $time )
    {
        $db = eZDB::instance();

        if ( $db->hasRequiredServerVersion( '4.0', 'mysql' ) )
        {
            // If we have the required MySQL version we use a DELETE statement
            // with multiple tables
            $sql = "DELETE FROM ezbasket, ezproductcollection, ezproductcollection_item, ezproductcollection_item_opt
USING ezsession
      JOIN ezbasket
        ON ezsession.session_key = ezbasket.session_id
      LEFT JOIN ezproductcollection
        ON ezbasket.productcollection_id = ezproductcollection.id
      LEFT JOIN ezproductcollection_item
        ON ezproductcollection.id = ezproductcollection_item.productcollection_id
      LEFT JOIN ezproductcollection_item_opt
        ON ezproductcollection_item.id = ezproductcollection_item_opt.item_id";
            if ( $time !== false )
                $sql .= "\nWHERE ezsession.expiration_time < " . (int)$time;
            $db->query( $sql );
            return;
        }

        // Find all baskets whos session will expire
        $sql = "SELECT id, productcollection_id
FROM ezbasket, ezsession
WHERE ezbasket.session_id = ezsession.session_key AND
      ezsession.expiration_time < " . (int)$time;
        $limit = self::ITEM_LIMIT;

        do
        {
            $rows = $db->arrayQuery( $sql, array( 'offset' => 0, 'limit' => $limit ) );
            if ( count( $rows ) == 0 )
                break;

            $productCollectionIDList = array();
            $idList = array();
            foreach ( $rows as $row )
            {
                $idList[] = (int)$row['id'];
                $productCollectionIDList[] = (int)$row['productcollection_id'];
            }
            eZProductCollection::cleanupList( $productCollectionIDList );

            $ids = implode( ', ', $idList );
            $db->query( "DELETE FROM ezbasket WHERE id IN ( $ids )" );

        } while ( true );
    }

    /*!
     \static
     Removes all baskets for all users.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $sql = "SELECT productcollection_id FROM ezbasket";
        $limit = self::ITEM_LIMIT;

        $db->begin();
        do
        {
            $rows = $db->arrayQuery( $sql, array( 'offset' => 0, 'limit' => $limit ) );

            if ( count( $rows ) == 0 )
                break;

            $productCollectionIDList = array();
            foreach ( $rows as $row )
            {
                $productCollectionIDList[] = (int)$row['productcollection_id'];
            }
            eZProductCollection::cleanupList( $productCollectionIDList );

            $ids = implode( ', ', $productCollectionIDList );
            $db->query( "DELETE FROM ezbasket WHERE productcollection_id IN ( $ids )" );
        }
        while ( true );
        $db->commit();
    }

    /*!
     \returns the type of basket. In other words: what type of products the basket contains.
    */
    function type()
    {
        $type = false;

        // get first product
        $productCollectionItemList = eZProductCollectionItem::fetchList( array( 'productcollection_id' => $this->attribute( 'productcollection_id' ) ),
                                                                         true,
                                                                         0,
                                                                         1 );

        if ( is_array( $productCollectionItemList ) && count( $productCollectionItemList ) === 1 )
        {
            $product = $productCollectionItemList[0]->attribute( 'contentobject' );
            if ( is_object( $product ) )
            {
                //include_once( 'kernel/shop/classes/ezshopfunctions.php' );
                $type = eZShopFunctions::productTypeByObject( $product );
            }
        }

        return $type;
    }

    function canAddProduct( $contentObject )
    {
        //include_once( 'kernel/shop/classes/ezshopfunctions.php' );
        //include_once( 'kernel/shop/errors.php' );

        $error = eZError::SHOP_OK;

        $productType = eZShopFunctions::productTypeByObject( $contentObject );
        if ( $productType === false )
        {
            $error = eZError::SHOP_NOT_A_PRODUCT;
        }
        else
        {
            if ( !eZShopFunctions::isSimplePriceProductType( $productType ) )
                $error = eZShopFunctions::isPreferredCurrencyValid();

            if ( $error === eZError::SHOP_OK )
            {
                $basketType = $this->type();
                if ( $basketType !== false && $basketType !== $productType )
                    $error = eZError::SHOP_BASKET_INCOMPATIBLE_PRODUCT_TYPE;
            }
        }

        return $error;
    }

    function productCollection()
    {
        return eZProductCollection::fetch( $this->attribute( 'productcollection_id' ) );
    }

    /*!
     \static
     Removes current basket.
     \param $useSetting - if "true" use ini setting in site.ini [ShopSettings].ClearBasketOnLogout,
                          or just clear current basket otherwise.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.

    */
    static function cleanupCurrentBasket( $useSetting = true )
    {
        $ini = eZINI::instance();
        $removeBasket = true;
        if ( $useSetting )
            $removeBasket = $ini->hasVariable( 'ShopSettings', 'ClearBasketOnLogout' ) ? $ini->variable( 'ShopSettings', 'ClearBasketOnLogout' ) == 'enabled' : false;

        if ( $removeBasket )
        {
            $basket = eZBasket::currentBasket();
            if ( !is_object( $basket ) )
                return false;
            $db = eZDB::instance();
            $db->begin();
            $productCollectionID = $basket->attribute( 'productcollection_id' );
            eZProductCollection::cleanupList( array( $productCollectionID ) );
            $basket->remove();
            $db->commit();
        }

        return true;
    }
}

?>

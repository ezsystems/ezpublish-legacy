<?php
//
// Definition of eZShopOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZShopOperationCollection ezcontentoperationcollection.php
  \brief The class eZShopOperationCollection does

*/
class eZShopOperationCollection
{
    /*!
     Constructor
    */
    function eZShopOperationCollection()
    {
    }

    function fetchOrder( $orderID )
    {
        return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
    }

    /*!
     Operation entry: Extracts user country from order account info and recalculates VAT with country given.
     */
    function handleUserCountry( $orderID )
    {
        // If user country is not required to calculate VAT then do nothing.
        if ( !eZVATManager::isDynamicVatChargingEnabled() || !eZVATManager::isUserCountryRequired() )
            return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );

        $user = eZUser::currentUser();

        // Get order's account information and extract user country from it.
        $order = eZOrder::fetch( $orderID );

        if ( !$order )
        {
            eZDebug::writeError( "No such order: $orderID" );
            return array( 'status' => eZModuleOperationInfo::STATUS_CANCELLED );
        }

        if ( $user->attribute( 'is_logged_in' ) )
            $userCountry = eZVATManager::getUserCountry( $user, false );

        $acctInfo = $order->attribute( 'account_information' );
        if ( isset( $acctInfo['country'] ) )
        {
            $country = $acctInfo['country'];

            // If user is registered and logged in
            // and country is not yet specified for the user
            // then save entered country to the user information.
            if ( !isset( $userCountry ) || !$userCountry )
                eZVATManager::setUserCountry( $user, $country );
        }
        elseif ( isset( $userCountry ) && $userCountry )
        {
            // If country is not set in shop account handler, we get it from logged user's information.
            $country = $userCountry;
        }
        else
        {
            $header = ezpI18n::tr( 'kernel/shop', 'Error checking out' );
            $msg = ezpI18n::tr( 'kernel/shop',
                           'Unable to calculate VAT percentage because your country is unknown. ' .
                           'You can either fill country manually in your account information (if you are a registered user) ' .
                           'or contact site administrator.' );

            $tpl = eZTemplate::factory();
            $tpl->setVariable( "error_header",  $header );
            $tpl->setVariable( "error_list", array( $msg ) );

            $operationResult = array(
                'status' => eZModuleOperationInfo::STATUS_CANCELLED,
                'result' => array( 'content' => $tpl->fetch( "design:shop/cancelconfirmorder.tpl" ) )
                );
            return $operationResult;
        }

        // Recalculate VAT for order's product collection items
        // according to the specified user country.

        $productCollection = $order->attribute( 'productcollection' );
        if ( !$productCollection )
        {
            eZDebug::writeError( "Cannot find product collection for order " . $order->attribute( 'id' ),
                                 "eZShopOperationCollection::handleUserCountry" );
            return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
        }

        $items = eZProductCollectionItem::fetchList( array( 'productcollection_id' => $productCollection->attribute( 'id' ) ) );
        $vatIsKnown = true;
        $db = eZDB::instance();
        $db->begin();
        foreach( $items as $item )
        {
            $productContentObject = $item->attribute( 'contentobject' );

            // Look up price object.
            $priceObj = null;
            $attributes =  $productContentObject->contentObjectAttributes();
            foreach ( $attributes as $attribute )
            {
                $dataType = $attribute->dataType();
                if ( eZShopFunctions::isProductDatatype( $dataType->isA() ) )
                {
                    $priceObj = $attribute->content();
                    break;
                }
            }
            if ( !is_object( $priceObj ) )
                continue;

            // If the product is assigned a fixed VAT type then skip the product.
            $vatType = $priceObj->VATType();
            if ( !$vatType->attribute( 'is_dynamic' ) )
                continue;

            // Update item's VAT percentage.
            $vatValue = $priceObj->VATPercent( $productContentObject, $country );
            eZDebug::writeNotice( "Updating product item collection item ('" .
                                  $productContentObject->attribute( 'name' ) . "'): " .
                                  "setting VAT $vatValue% according to order's country '$country'." );
            $item->setAttribute( "vat_value", $vatValue );

            $item->store();
        }
        $db->commit();

        return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
    }

    /*!
     Operation entry: Adds order item: shipping.
     \params $orderID contains the order id for the shipping handler.

     The function handleShipping() are runn in the process of confirmorder and
     is the final function for creating an order_item in the order confirmation.

     An example for an array that should be returned by the function
     eZShippingManager::getShippingInfo( $productCollectionID ):
     \code
     array( 'shipping_items' => array( array( 'description' => 'Shipping vat: 12%',
                                              'cost'        => 50.25,
                                              'vat_value'   => 12,
                                              'is_vat_inc'  => 0 ),
                                       array( 'description' => 'Shipping vat: 25%',
                                              'cost'        => 100.75,
                                              'vat_value'   => 25,
                                              'is_vat_inc'  => 0 ) ),
            'description' => 'Total Shipping',
            'cost'        => 182.22,
            'vat_value'   => false,
            'is_vat_inc'  => 1 );
     \endcode

     An example for the shippingvalues with only one shippingitem, old standard.
     \code
     array( 'description' => 'Total Shipping vat: 16%',
            'cost'        => 10.25,
            'vat_value'   => 16,
            'is_vat_inc'  => 1 );
     \endcode

     The returned array for each shipping item should consist of these keys:
     - order_id - The order id for the current order.
     - description - An own description of the shipping item.
     - cost - A float value of the cost for the shipping.
     - vat_value - The vat value that should be added to the shipping item.
     - is_vat_inc - Either 0, 1 or false. 0: The cost is excluded VAT.
                                          1: the cost is included VAT.
                                      false: The cost is combined by several other VAT prices.

     This function may also send additional parameters to be used in other templates, like
     in the basket.
    */
    function handleShipping( $orderID )
    {
        do // we prevent high nesting levels by using breaks
        {
            $order = eZOrder::fetch( $orderID );
            if ( !$order )
                break;
            $productCollectionID = $order->attribute( 'productcollection_id' );

            $shippingInfo = eZShippingManager::getShippingInfo( $productCollectionID );
            if ( !isset( $shippingInfo ) )
                break;

            // check if the order item has been added before.
            $orderItems = $order->orderItemsByType( 'ezcustomshipping' );

            // If orderitems allready exists, remove them first.
            if ( $orderItems )
            {
                foreach ( $orderItems as $orderItem )
                {
                    $orderItem->remove();
                }
                $purgeStatus = eZShippingManager::purgeShippingInfo( $productCollectionID );
            }

            if ( isset( $shippingInfo['shipping_items'] ) and
                 is_array( $shippingInfo['shipping_items'] ) )
            {
                // Add a new order item for each shipping.
                foreach ( $shippingInfo['shipping_items'] as $orderItemShippingInfo )
                {
                    $orderItem = new eZOrderItem( array( 'order_id' => $orderID,
                                                         'description' => $orderItemShippingInfo['description'],
                                                         'price' => $orderItemShippingInfo['cost'],
                                                         'vat_value' => $orderItemShippingInfo['vat_value'],
                                                         'is_vat_inc' => $orderItemShippingInfo['is_vat_inc'],
                                                         'type' => 'ezcustomshipping' ) );
                    $orderItem->store();
                }
            }
            else
            {
                // Made for backwards compability, if the array order_items are not supplied.
                if ( !isset( $shippingInfo['vat_value'] ) )
                {
                    $shippingInfo['vat_value'] = 0;
                }

                if ( !isset( $shippingInfo['is_vat_inc'] ) )
                {
                    $shippingInfo['is_vat_inc'] = 1;
                }

                $orderItem = new eZOrderItem( array( 'order_id' => $orderID,
                                                     'description' => $shippingInfo['description'],
                                                     'price' => $shippingInfo['cost'],
                                                     'vat' => $shippingInfo['vat_value'],
                                                     'is_vat_inc' => $shippingInfo['is_vat_inc'],
                                                     'type' => 'ezcustomshipping' ) );
                $orderItem->store();
            }

        } while ( false );

        return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
    }

    /*!
     Operation entry: Updates shipping info for items in the current basket.
    */
    function updateShippingInfo( $objectID, $optionList )
    {
        $basket = eZBasket::currentBasket();
        $shippingInfo = eZShippingManager::updateShippingInfo( $basket->attribute( 'productcollection_id' ) );
        return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
    }

    function updateBasket( $itemCountList, $itemIDList )
    {
        if ( is_array( $itemCountList ) && is_array( $itemIDList ) && count( $itemCountList ) == count ( $itemIDList ) )
        {
            $basket = eZBasket::currentBasket();
            if ( is_object( $basket ) )
            {
                $productCollectionID = $basket->attribute( 'productcollection_id' );

                $i = 0;
                foreach ( $itemIDList as $id )
                {
                    $item = eZProductCollectionItem::fetch( $id );
                    if ( is_object( $item ) && $item->attribute( 'productcollection_id' ) == $productCollectionID )
                    {
                        if ( is_numeric( $itemCountList[$i] ) and $itemCountList[$i] == 0 )
                        {
                            $item->remove();
                        }
                        else
                        {
                            $item->setAttribute( 'item_count', $itemCountList[$i] );
                            $item->store();
                        }
                    }
                    ++$i;
                }
            }
        }
    }

    /*!
     Operation entry: Adds the object \a $objectID with options \a $optionList to the current basket.
    */
    function addToBasket( $objectID, $optionList, $quantity )
    {
        $object = eZContentObject::fetch( $objectID );
        $nodeID = $object->attribute( 'main_node_id' );
        $price = 0.0;
        $isVATIncluded = true;
        $attributes = $object->contentObjectAttributes();

        $priceFound = false;

        foreach ( $attributes as $attribute )
        {
            $dataType = $attribute->dataType();
            if ( eZShopFunctions::isProductDatatype( $dataType->isA() ) )
            {
                $priceObj = $attribute->content();
                $price += $priceObj->attribute( 'price' );
                $priceFound = true;
            }
        }

        if ( !$priceFound )
        {
            eZDebug::writeError( 'Attempted to add object without price to basket.' );
            return array( 'status' => eZModuleOperationInfo::STATUS_CANCELLED );
        }

        $currency = $priceObj->attribute( 'currency' );

        // Check for 'option sets' in option list.
        // If found each 'option set' will be added as a separate product purchase.
        $hasOptionSet = false;
        foreach ( array_keys( $optionList ) as $optionKey )
        {
            if ( substr( $optionKey, 0, 4 ) == 'set_' )
            {
                $returnStatus = eZShopOperationCollection::addToBasket( $objectID, $optionList[$optionKey] );
                // If adding one 'option set' fails we should stop immediately
                if ( $returnStatus['status'] == eZModuleOperationInfo::STATUS_CANCELLED )
                    return $returnStatus;
                $hasOptionSet = true;
            }
        }
        if ( $hasOptionSet )
            return $returnStatus;


        $unvalidatedAttributes = array();
        foreach ( $attributes as $attribute )
        {
            $dataType = $attribute->dataType();

            if ( $dataType->isAddToBasketValidationRequired() )
            {
                $errors = array();
                if ( $attribute->validateAddToBasket( $optionList[$attribute->attribute('id')], $errors ) !== eZInputValidator::STATE_ACCEPTED )
                {
                    $description = $errors;
                    $contentClassAttribute = $attribute->contentClassAttribute();
                    $attributeName = $contentClassAttribute->attribute( 'name' );
                    $unvalidatedAttributes[] = array( "name" => $attributeName,
                                                      "description" => $description );
                }
            }
        }
        if ( count( $unvalidatedAttributes ) > 0 )
        {
            return array( 'status' => eZModuleOperationInfo::STATUS_CANCELLED,
                          'reason' => 'validation',
                          'error_data' => $unvalidatedAttributes );
        }

        $basket = eZBasket::currentBasket();

        /* Check if the item with the same options is not already in the basket: */
        $itemID = false;
        $collection = $basket->attribute( 'productcollection' );
        if ( !$collection )
        {
            eZDebug::writeError( 'Unable to find product collection.' );
            return array( 'status' => eZModuleOperationInfo::STATUS_CANCELLED );
        }
        else
        {
            $collection->setAttribute( 'currency_code', $currency );
            $collection->store();

            $count = 0;
            /* Calculate number of options passed via the HTTP variable: */
            foreach ( array_keys( $optionList ) as $key )
            {
                if ( is_array( $optionList[$key] ) )
                    $count += count( $optionList[$key] );
                else
                    $count++;
            }
            $collectionItems = $collection->itemList( false );
            foreach ( $collectionItems as $item )
            {
                /* For all items in the basket which have the same object_id: */
                if ( $item['contentobject_id'] == $objectID )
                {
                    $options = eZProductCollectionItemOption::fetchList( $item['id'], false );
                    /* If the number of option for this item is not the same as in the HTTP variable: */
                    if ( count( $options ) != $count )
                    {
                        break;
                    }
                    $theSame = true;
                    foreach ( $options as $option )
                    {
                        /* If any option differs, go away: */
                        if ( ( is_array( $optionList[$option['object_attribute_id']] ) &&
                               !in_array( $option['option_item_id'], $optionList[$option['object_attribute_id']] ) )
                             || ( !is_array( $optionList[$option['object_attribute_id']] ) &&
                                  $option['option_item_id'] != $optionList[$option['object_attribute_id']] ) )
                        {
                            $theSame = false;
                            break;
                        }
                    }
                    if ( $theSame )
                    {
                        $itemID = $item['id'];
                        break;
                    }
                }
            }

            if ( $itemID )
            {
                /* If found in the basket, just increment number of that items: */
                $item = eZProductCollectionItem::fetch( $itemID );
                $item->setAttribute( 'item_count', $quantity + $item->attribute( 'item_count' ) );
                $item->store();
            }
            else
            {
                $item = eZProductCollectionItem::create( $basket->attribute( "productcollection_id" ) );

                $item->setAttribute( 'name', $object->attribute( 'name' ) );
                $item->setAttribute( "contentobject_id", $objectID );
                $item->setAttribute( "item_count", $quantity );
                $item->setAttribute( "price", $price );
                if ( $priceObj->attribute( 'is_vat_included' ) )
                {
                    $item->setAttribute( "is_vat_inc", '1' );
                }
                else
                {
                    $item->setAttribute( "is_vat_inc", '0' );
                }
                $item->setAttribute( "vat_value", $priceObj->attribute( 'vat_percent' ) );
                $item->setAttribute( "discount", $priceObj->attribute( 'discount_percent' ) );
                $item->store();
                $priceWithoutOptions = $price;

                $optionIDList = array();
                foreach ( array_keys( $optionList ) as $key )
                {
                    $attributeID = $key;
                    $optionString = $optionList[$key];
                    if ( is_array( $optionString ) )
                    {
                        foreach ( $optionString as $optionID )
                        {
                            $optionIDList[] = array( 'attribute_id' => $attributeID,
                                                     'option_string' => $optionID );
                        }
                    }
                    else
                    {
                        $optionIDList[] = array( 'attribute_id' => $attributeID,
                                                 'option_string' => $optionString );
                    }
                }

                $db = eZDB::instance();
                $db->begin();
                foreach ( $optionIDList as $optionIDItem )
                {
                    $attributeID = $optionIDItem['attribute_id'];
                    $optionString = $optionIDItem['option_string'];

                    $attribute = eZContentObjectAttribute::fetch( $attributeID, $object->attribute( 'current_version' ) );
                    $dataType = $attribute->dataType();
                    $optionData = $dataType->productOptionInformation( $attribute, $optionString, $item );
                    if ( $optionData )
                    {
                        $optionData['additional_price'] = eZShopFunctions::convertAdditionalPrice( $currency, $optionData['additional_price'] );
                        $optionItem = eZProductCollectionItemOption::create( $item->attribute( 'id' ), $optionData['id'], $optionData['name'],
                                                                             $optionData['value'], $optionData['additional_price'], $attributeID );
                        $optionItem->store();
                        $price += $optionData['additional_price'];
                    }
                }

                if ( $price != $priceWithoutOptions )
                {
                    $item->setAttribute( "price", $price );
                    $item->store();
                }
                $db->commit();
            }
        }

        return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
    }

    function activateOrder( $orderID )
    {
        $order = eZOrder::fetch( $orderID );

        $db = eZDB::instance();
        $db->begin();
        $order->activate();

        $basket = eZBasket::currentBasket( true, $orderID);
        $basket->remove();
        $db->commit();

        eZHTTPTool::instance()->setSessionVariable( "UserOrderID", $orderID );
        return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
    }

    function sendOrderEmails( $orderID )
    {
        $order = eZOrder::fetch( $orderID );

        // Fetch the shop account handler
        $accountHandler = eZShopAccountHandler::instance();
        $email = $accountHandler->email( $order );

        // Fetch the confirm order handler
        $confirmOrderHandler = eZConfirmOrderHandler::instance();
        $params = array( 'email' => $email,
                         'order' => $order );
        $confirmOrderStatus = $confirmOrderHandler->execute( $params );

        return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
    }

    /*!
     Verify that we have a valid currency before the the order can continue.
    */
    function checkCurrency( $orderID )
    {
        $returnStatus = array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
        $order = eZOrder::fetch( $orderID );
        $productCollection = $order->attribute( 'productcollection' );
        $currencyCode = $productCollection->attribute( 'currency_code' );
        $currencyCode = trim( $currencyCode );
        if ( $currencyCode == '' )
        {
            $returnStatus = array( 'status' => eZModuleOperationInfo::STATUS_CANCELLED );
        }

        $locale = eZLocale::instance();
        $localeCurrencyCode = $locale->currencyShortName();

        // Reverse logic to avoid calling eZCurrencyData::currencyExists() if the first expression is true.
        if ( !( $currencyCode == $localeCurrencyCode or
                eZCurrencyData::currencyExists( $currencyCode ) ) )
        {
            $returnStatus = array( 'status' => eZModuleOperationInfo::STATUS_CANCELLED );
        }
        return $returnStatus;
    }
}

?>

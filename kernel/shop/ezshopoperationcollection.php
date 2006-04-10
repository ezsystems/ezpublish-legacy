<?php
//
// Definition of eZShopOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

/*! \file ezcontentoperationcollection.php
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
        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
    }

    /*!
     Operation entry: Extracts user country from order account info and recalculates VAT with country given.
     */
    function handleUserCountry( $orderID )
    {
        // If user country is not required to calculate VAT then do nothing.
        require_once( 'kernel/classes/ezvatmanager.php' );
        if ( !eZVATManager::isDynamicVatChargingEnabled() || !eZVATManager::isUserCountryRequired() )
            return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );

        $user =& eZUser::currentUser();

        // Get order's account information and extract user country from it.
        $order = eZOrder::fetch( $orderID );

        if ( !$order )
        {
            eZDebug::writeError( "No such order: $orderID" );
            return array( 'status' => EZ_MODULE_OPERATION_CANCELED );
        }

        $acctInfo = $order->attribute( 'account_information' );
        if ( !isset( $acctInfo['country'] ) )
        {
            //eZDebug::writeError( $acctInfo, "User country was not found in the following account information" );

            $header = ezi18n( 'kernel/shop', 'Error checking out' );
            $msg = ezi18n( 'kernel/shop',
                           'Unable to calculate VAT percentage because your country is unknown. ' .
                           'You can either fill country manually in your account information (if you are a registered user) ' .
                           'or contact site administrator.' );

            include_once( "kernel/common/template.php" );
            $tpl =& templateInit();
            $tpl->setVariable( "error_header",  $header );
            $tpl->setVariable( "error_list", array( $msg ) );

            $operationResult = array(
                'status' => EZ_MODULE_OPERATION_CANCELED,
                'result' => array( 'content' => $tpl->fetch( "design:shop/cancelconfirmorder.tpl" ) )
                );
            return $operationResult;
        }

        $country = $acctInfo['country'];

        // If user is registered and logged in
        // and country is not yet specified for the user
        // then save entered country to the user information.
        if ( $user->attribute( 'is_logged_in' ) )
        {
            $userCountry = eZVATManager::getUserCountry( $user );
            if ( !$userCountry )
                eZVATManager::setUserCountry( $user, $country );
        }

        // Recalculate VAT for order's product collection items
        // according to the specified user country.

        $productCollection =& $order->attribute( 'productcollection' );
        if ( !$productCollection )
        {
            eZDebug::writeError( "Cannot find product collection for order " . $order->attribute( 'id' ),
                                   "ezshopoperationcollection::handleUserCountry" );
            return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
        }

        require_once( 'kernel/classes/ezproductcollectionitem.php' );
        $items = eZProductCollectionItem::fetchList( array( 'productcollection_id' => $productCollection->attribute( 'id' ) ) );
        $vatIsKnown = true;
        $db =& eZDB::instance();
        $db->begin();
        include_once( 'kernel/shop/classes/ezshopfunctions.php' );
        foreach( array_keys( $items ) as $key )
        {
            $item =& $items[$key];
            $productContentObject =& $item->attribute( 'contentobject' );

            // Look up price object.
            $priceObj = null;
            $attributes =&  $productContentObject->contentObjectAttributes();
            foreach ( $attributes as $attribute )
            {
                $dataType = $attribute->dataType();
                if ( eZShopFunctions::isProductDatatype( $dataType->isA() ) )
                {
                    $priceObj =& $attribute->content();
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

        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
    }

    /*!
     Operation entry: Adds order item: shipping.
     */
    function handleShipping( $orderID )
    {
        do // we prevent high nesting levels by using breaks
        {
            $order = eZOrder::fetch( $orderID );
            if ( !$order )
                break;

            require_once( 'kernel/classes/ezshippingmanager.php' );
            $shippingInfo = eZShippingManager::getShippingInfo( $order->attribute( 'productcollection_id' ) );
            if ( !isset( $shippingInfo ) )
                break;

            // check if the order item has been added before.
            $orderItems = $order->orderItemsByType( 'ezcustomshipping' );

            // if it has then do nothing.
            if ( $orderItems )
                break;

            $shippingDescription = ezi18n( 'kernel/shop', 'Shipping' );
            if ( $shippingInfo['description'] )
                $shippingDescription .= ' (' . $shippingInfo['description'] . ')';

            // create order item: shipping

            $orderItem = new eZOrderItem( array( 'order_id' => $orderID,
                                                 'description' => $shippingDescription,
                                                 'price' => $shippingInfo['cost'],
                                                 'type' => 'ezcustomshipping' ) );
            $orderItem->store();

        } while ( false );

        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
    }

    /*!
     Operation entry: Updates shipping info for items in the current basket.
    */
    function updateShippingInfo( $objectID, $optionList )
    {
        $basket =& eZBasket::currentBasket();
        require_once( 'kernel/classes/ezshippingmanager.php' );
        $shippingInfo = eZShippingManager::updateShippingInfo( $basket->attribute( 'productcollection_id' ) );
        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
    }

    function updateBasket( $itemCountList, $itemIDList )
    {
        $i = 0;
        foreach ( $itemIDList as $id )
        {
            $item = eZProductCollectionItem::fetch( $id );
            if ( $itemCountList[$i] == 0 )
            {
                $item->remove();
            }
            else
            {
                $item->setAttribute( 'item_count', $itemCountList[$i] );
                $item->store();
            }
            ++$i;
        }
    }

    /*!
     Operation entry: Adds the object \a $objectID with options \a $optionList to the current basket.
    */
    function addToBasket( $objectID, $optionList )
    {
        include_once( 'kernel/shop/classes/ezshopfunctions.php' );

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
                $priceObj =& $attribute->content();
                $price += $priceObj->attribute( 'price' );
                $priceFound = true;
            }
        }

        if ( !$priceFound )
        {
            eZDebug::writeError( 'Attempted to add object without price to basket.' );
            return array( 'status' => EZ_MODULE_OPERATION_CANCELED );
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
                if ( $returnStatus['status'] == EZ_MODULE_OPERATION_CANCELED )
                    return $returnStatus;
                $hasOptionSet = true;
            }
        }
        if ( $hasOptionSet )
            return $returnStatus;

        $basket =& eZBasket::currentBasket();

        /* Check if the item with the same options is not already in the basket: */
        $itemID = false;
        $collection =& $basket->attribute( 'productcollection' );
        if ( !$collection )
        {
            eZDebug::writeError( 'Unable to find product collection.' );
            return array( 'status' => EZ_MODULE_OPERATION_CANCELED );
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
            $collectionItems =& $collection->itemList( false );
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
                $item->setAttribute( 'item_count', 1 + $item->attribute( 'item_count' ) );
                $item->store();
            }
            else
            {
                $item = eZProductCollectionItem::create( $basket->attribute( "productcollection_id" ) );

                $item->setAttribute( 'name', $object->attribute( 'name' ) );
                $item->setAttribute( "contentobject_id", $objectID );
                $item->setAttribute( "item_count", 1 );
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

                $db =& eZDB::instance();
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

        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
    }

    function activateOrder( $orderID )
    {
        include_once( "kernel/classes/ezbasket.php" );
        include_once( 'kernel/classes/ezorder.php' );

        $order = eZOrder::fetch( $orderID );

        $db =& eZDB::instance();
        $db->begin();
        $order->activate();

        $basket =& eZBasket::currentBasket( true, $orderID);
        $basket->remove();
        $db->commit();

        include_once( "lib/ezutils/classes/ezhttptool.php" );
        eZHTTPTool::setSessionVariable( "UserOrderID", $orderID );
        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
    }

    function sendOrderEmails( $orderID )
    {
        include_once( "kernel/classes/ezbasket.php" );
        include_once( 'kernel/classes/ezorder.php' );
        $order = eZOrder::fetch( $orderID );

        // Fetch the shop account handler
        include_once( 'kernel/classes/ezshopaccounthandler.php' );
        $accountHandler =& eZShopAccountHandler::instance();
        $email = $accountHandler->email( $order );

        include_once( "kernel/common/template.php" );
        $tpl =& templateInit();
        $tpl->setVariable( 'order', $order );
        $templateResult =& $tpl->fetch( 'design:shop/orderemail.tpl' );

        $subject = $tpl->variable( 'subject' );

        $receiver = $email;

        include_once( 'lib/ezutils/classes/ezmail.php' );
        include_once( 'lib/ezutils/classes/ezmailtransport.php' );
        $ini =& eZINI::instance();
        $mail = new eZMail();

        if ( !$mail->validate( $receiver ) )
        {
        }
        $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( "MailSettings", "AdminEmail" );

        $mail->setReceiver( $email );
        $mail->setSender( $emailSender );
        $mail->setSubject( $subject );
        $mail->setBody( $templateResult );
        $mailResult = eZMailTransport::send( $mail );


        $email = $ini->variable( 'MailSettings', 'AdminEmail' );

        $mail = new eZMail();

        if ( !$mail->validate( $receiver ) )
        {
        }

        $mail->setReceiver( $email );
        $mail->setSender( $emailSender );
        $mail->setSubject( $subject );
        $mail->setBody( $templateResult );
        $mailResult = eZMailTransport::send( $mail );

        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
    }
}

?>

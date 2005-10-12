<?php
//
// Definition of eZShopOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
     Operation entry: Adds the object \a $objectID with options \a $optionList to the current basket.
    */
    function addToBasket( $objectID, $optionList )
    {
        $object = eZContentObject::fetch( $objectID );
        $nodeID = $object->attribute( 'main_node_id' );
        $price = 0.0;
        $isVATIncluded = true;
        $attributes = $object->contentObjectAttributes();

        $priceFound = false;

        foreach ( $attributes as $attribute )
        {
            $dataType =& $attribute->dataType();
            if ( $dataType->isA() == "ezprice" )
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
        $basket =& eZBasket::currentBasket();

        /* Check if the item with the same options is not already in the basket: */
        $itemID = false;
        $collection = eZProductCollection::fetch( $basket->attribute( 'productcollection_id' ) );
        if ( $collection )
        {
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
                $dataType =& $attribute->dataType();
                $optionData = $dataType->productOptionInformation( $attribute, $optionString, $item );
                if ( $optionData )
                {
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

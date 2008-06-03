<?php
//
// Created on: <04-Jul-2002 13:19:43 bf>
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];

//include_once( "kernel/classes/ezcontentobject.php" );
//include_once( "kernel/classes/ezbasket.php" );
//include_once( "kernel/classes/ezvattype.php" );
//include_once( "kernel/classes/ezorder.php" );
//include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

//include_once( "kernel/classes/ezproductcollection.php" );
//include_once( "kernel/classes/ezproductcollectionitem.php" );
//include_once( "kernel/classes/ezproductcollectionitemoption.php" );
require_once( "kernel/common/template.php" );
//include_once( 'lib/ezutils/classes/ezhttptool.php' );

$basket = eZBasket::currentBasket();
$basket->updatePrices(); // Update the prices. Transaction not necessary.


if ( $http->hasPostVariable( "ActionAddToBasket" ) )
{
    $objectID = $http->postVariable( "ContentObjectID" );

    if ( $http->hasPostVariable( 'eZOption' ) )
        $optionList = $http->postVariable( 'eZOption' );
    else
        $optionList = array();

    $http->setSessionVariable( "FromPage", $_SERVER['HTTP_REFERER'] );
    $http->setSessionVariable( "AddToBasket_OptionList_" . $objectID, $optionList );

    $module->redirectTo( "/shop/add/" . $objectID );
    return;
}

if ( $http->hasPostVariable( "RemoveProductItemButton" ) )
{
    $itemCountList = $http->postVariable( "ProductItemCountList" );
    $itemIDList = $http->postVariable( "ProductItemIDList" );

    if ( is_array( $itemCountList ) && is_array( $itemIDList ) && count( $itemCountList ) == count( $itemIDList ) && is_object( $basket ) )
    {
        $productCollectionID = $basket->attribute( 'productcollection_id' );
        $item = $http->postVariable( "RemoveProductItemButton" );
        if ( $http->hasPostVariable( "RemoveProductItemDeleteList" ) )
            $itemList = $http->postVariable( "RemoveProductItemDeleteList" );
        else
            $itemList = array();

        $i = 0;

        $db = eZDB::instance();
        $db->begin();
        $itemCountError = false;
        foreach ( $itemIDList as $id )
        {
            $item = eZProductCollectionItem::fetch( $id );
            if ( is_object( $item ) && $item->attribute( 'productcollection_id' ) == $productCollectionID )
            {
                if ( is_numeric( $itemCountList[$i] ) and $itemCountList[$i] > 0 )
                {
                    $item->setAttribute( "item_count", $itemCountList[$i] );
                    $item->store();
                }
                else
                {
                    if ( ( is_numeric( $item ) and $id != $item ) or ( is_array( $itemList ) and !in_array( $id, $itemList ) ) )
                        $itemCountError = true;
                }
            }
            $i++;
        }
        if ( is_numeric( $item )  )
        {
            $basket->removeItem( $item );
        }
        else
        {
            foreach ( $itemList as $item )
            {
                $basket->removeItem( $item );
            }
        }

        // Update shipping info after removing an item from the basket.
        require_once( 'kernel/classes/ezshippingmanager.php' );
        eZShippingManager::updateShippingInfo( $basket->attribute( 'productcollection_id' ) );

        $db->commit();

        if ( $itemCountError )
        {
            $module->redirectTo( $module->functionURI( "basket" ) . "/(error)/invaliditemcount" );
            return;
        }

        $module->redirectTo( $module->functionURI( "basket" ) . "/" );
        return;
    }
}

if ( $http->hasPostVariable( "StoreChangesButton" ) )
{
    $itemCountList = $http->postVariable( "ProductItemCountList" );
    $itemIDList = $http->postVariable( "ProductItemIDList" );

    // We should check item count, all itemcounts must be greater than 0
    foreach ( $itemCountList as $itemCount )
    {
        // If item count of product <= 0 we should show the error
        if ( !is_numeric( $itemCount ) or $itemCount < 0 )
        {
            // Redirect to basket
            $module->redirectTo( $module->functionURI( "basket" ) . "/(error)/invaliditemcount" );
            return;
        }
    }

    $http->setSessionVariable( 'ProductItemCountList', $itemCountList );
    $http->setSessionVariable( 'ProductItemIDList', $itemIDList );

    $module->redirectTo( '/shop/updatebasket/' );
    return;
}

if ( $http->hasPostVariable( "ContinueShoppingButton" ) )
{
    $itemCountList = $http->hasPostVariable( "ProductItemCountList" ) ? $http->postVariable( "ProductItemCountList" ) : false;
    $itemIDList = $http->hasPostVariable( "ProductItemIDList" ) ? $http->postVariable( "ProductItemIDList" ) : false;
    if ( is_array( $itemCountList ) && is_array( $itemIDList ) && count( $itemCountList ) == count( $itemIDList ) && is_object( $basket ) )
    {
        $productCollectionID = $basket->attribute( 'productcollection_id' );

        $i = 0;

        $db = eZDB::instance();
        $db->begin();
        $itemCountError = false;
        foreach ( $itemIDList as $id )
        {
            if ( !is_numeric( $itemCountList[$i] ) or $itemCountList[$i] <= 0 )
            {
                $itemCountError = true;
            }
            else
            {
                $item = eZProductCollectionItem::fetch( $id );
                if ( is_object( $item ) && $item->attribute( 'productcollection_id' ) == $productCollectionID )
                {
                    $item->setAttribute( "item_count", $itemCountList[$i] );
                    $item->store();
                }
            }
            $i++;
        }
        $db->commit();
        if ( $itemCountError )
        {
            // Redirect to basket
            $module->redirectTo( $module->functionURI( "basket" ) . "/(error)/invaliditemcount" );
            return;
        }

        $fromURL = $http->sessionVariable( "FromPage" );
        $module->redirectTo( $fromURL );
    }
}

$doCheckout = false;
if ( $http->hasSessionVariable( 'DoCheckoutAutomatically' ) )
{
    if ( $http->sessionVariable( 'DoCheckoutAutomatically' ) === true )
    {
        $doCheckout = true;
        $http->setSessionVariable( 'DoCheckoutAutomatically', false );
    }
}

$removedItems = array();

if ( $http->hasPostVariable( "CheckoutButton" ) or ( $doCheckout === true ) )
{
    if ( $http->hasPostVariable( "ProductItemIDList" ) )
    {
        $itemCountList = $http->postVariable( "ProductItemCountList" );
        $itemIDList = $http->postVariable( "ProductItemIDList" );

        if ( is_array( $itemCountList ) && is_array( $itemIDList ) && count( $itemCountList ) == count( $itemIDList ) && is_object( $basket ) )
        {
            $productCollectionID = $basket->attribute( 'productcollection_id' );
            $db = eZDB::instance();
            $db->begin();

            for ( $i = 0, $itemCountError = false; $i < count( $itemIDList ); ++$i )
            {
                // If item count of product <= 0 we should show the error
                if ( !is_numeric( $itemCountList[$i] ) or $itemCountList[$i] <= 0 )
                {
                    $itemCountError = true;
                    continue;
                }
                $item = eZProductCollectionItem::fetch( $itemIDList[$i] );
                if ( is_object( $item ) && $item->attribute( 'productcollection_id' ) == $productCollectionID )
                {
                    $item->setAttribute( "item_count", $itemCountList[$i] );
                    $item->store();
                }
            }
            $db->commit();
            if ( $itemCountError )
            {
                // Redirect to basket
                $module->redirectTo( $module->functionURI( "basket" ) . "/(error)/invaliditemcount" );
                return;
            }
        }
    }

    // Fetch the shop account handler
    //include_once( 'kernel/classes/ezshopaccounthandler.php' );
    $accountHandler = eZShopAccountHandler::instance();

    // Do we have all the information we need to start the checkout
    if ( !$accountHandler->verifyAccountInformation() )
    {
        // Fetches the account information, normally done with a redirect
        $accountHandler->fetchAccountInformation( $module );
        return;
    }
    else
    {
        // Creates an order and redirects
        $basket = eZBasket::currentBasket();
        $productCollectionID = $basket->attribute( 'productcollection_id' );

        $verifyResult = eZProductCollection::verify( $productCollectionID  );

        $db = eZDB::instance();
        $db->begin();
        $basket->updatePrices();

        if ( $verifyResult === true )
        {
            $order = $basket->createOrder();
            $order->setAttribute( 'account_identifier', "default" );
            $order->store();

            $http->setSessionVariable( 'MyTemporaryOrderID', $order->attribute( 'id' ) );

            $db->commit();
            $module->redirectTo( '/shop/confirmorder/' );
            return;
        }
        else
        {
            $basket = eZBasket::currentBasket();
            $removedItems = array();
            foreach ( $itemList as $item )
            {
                $removedItems[] = $item;
                $basket->removeItem( $item->attribute( 'id' ) );
            }
        }
        $db->commit();
    }
}
$basket = eZBasket::currentBasket();

$tpl = templateInit();
if ( isset( $Params['Error'] ) )
{
    $tpl->setVariable( 'error', $Params['Error'] );
    if ( $Params['Error'] == 'options' )
    {
        $tpl->setVariable( 'error_data', $http->sessionVariable( 'BasketError') );
        $http->removeSessionVariable( 'BasketError');
    }
}
$tpl->setVariable( "removed_items", $removedItems);
$tpl->setVariable( "basket", $basket );
$tpl->setVariable( "module_name", 'shop' );
$tpl->setVariable( "vat_is_known", $basket->isVATKnown() );


// Add shipping cost to the total items price and store the sum to corresponding template vars.
require_once( 'kernel/classes/ezshippingmanager.php' );
$shippingInfo = eZShippingManager::getShippingInfo( $basket->attribute( 'productcollection_id' ) );
if ( $shippingInfo !== null )
{
    // to make backwards compability with old version, allways set the cost inclusive vat.
    if ( ( isset( $shippingInfo['is_vat_inc'] ) and $shippingInfo['is_vat_inc'] == 0 ) or
         !isset( $shippingInfo['is_vat_inc'] ) )
    {
        $additionalShippingValues = eZShippingManager::vatPriceInfo( $shippingInfo );
        $shippingInfo['cost'] = $additionalShippingValues['total_shipping_inc_vat'];
        $shippingInfo['is_vat_inc'] = 1;
    }

    $totalIncShippingExVat  = $basket->attribute( 'total_ex_vat'  ) + $shippingInfo['cost'];
    $totalIncShippingIncVat = $basket->attribute( 'total_inc_vat' ) + $shippingInfo['cost'];

    $tpl->setVariable( 'shipping_info', $shippingInfo );
    $tpl->setVariable( 'total_inc_shipping_ex_vat', $totalIncShippingExVat );
    $tpl->setVariable( 'total_inc_shipping_inc_vat', $totalIncShippingIncVat );
}

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/basket.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Basket' ) ) );
?>

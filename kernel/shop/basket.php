<?php
//
// Created on: <04-Jul-2002 13:19:43 bf>
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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezbasket.php" );
include_once( "kernel/classes/ezvattype.php" );
include_once( "kernel/classes/ezorder.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

include_once( "kernel/classes/ezproductcollection.php" );
include_once( "kernel/classes/ezproductcollectionitem.php" );
include_once( "kernel/common/template.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

if ( $http->hasPostVariable( "ActionAddToBasket" ) )
{
    $objectID = $http->postVariable( "ContentObjectID" );
    $optionList =& $http->postVariable( "eZOption" );

    $object = eZContentObject::fetch( $objectID );
    $nodeID = $object->attribute( 'main_node_id' );
    $http->setSessionVariable( "FromPage", "/content/view/full/" . $nodeID . "/" );
    $price = 0.0;
    $isVATIncluded = true;
    $attributes = $object->contentObjectAttributes();
    foreach ( $attributes as $attribute )
    {
        $dataType =& $attribute->dataType();

        if ( $dataType->isA() == "ezprice" )
        {
            $content =& $attribute->content();
            $price += $content->attribute( 'price' );
        }
    }

    $basket =& eZBasket::currentBasket();
    $sessionID = $http->sessionID();

    $item =& eZProductCollectionItem::create( $basket->attribute( "productcollection_id" ) );

    $item->setAttribute( "contentobject_id", $objectID );
    $item->setAttribute( "item_count", 1 );
    $item->setAttribute( "price", $price );
    $item->store();
    $module->redirectTo( "/shop/basket/" );
    return;
}

if ( $http->hasPostVariable( "RemoveProductItemButton" ) )
{
    $itemList = $http->postVariable( "RemoveProductItemDeleteList" );

    $basket =& eZBasket::currentBasket();

    foreach ( $itemList as $item )
    {
        $basket->removeItem( $item );
    }
    $module->redirectTo( $module->functionURI( "basket" ) . "/" );
    return;
}

if ( $http->hasPostVariable( "StoreChangesButton" ) )
{
    $itemCountList = $http->postVariable( "ProductItemCountList" );
    $itemIDList = $http->postVariable( "ProductItemIDList" );

    $i = 0;
    foreach ( $itemIDList as $id )
    {
        $item = eZProductCollectionItem::fetch( $id );
        $item->setAttribute( "item_count", $itemCountList[$i] );
        $item->store();

        $i++;
    }

    $module->redirectTo( $module->functionURI( "basket" ) . "/" );
    return;
}

if ( $http->hasPostVariable( "ContinueShoppingButton" ) )
{
    $fromURL = $http->sessionVariable( "FromPage" );
    $module->redirectTo( $fromURL );
}

if ( $http->hasPostVariable( "CheckoutButton" ) )
{
    // Check login
    $user =& eZUser::currentUser();

    if ( !$user->isLoggedIn() )
    {
        eZHTTPTool::setSessionVariable( 'RedirectAfterUserRegister', '/shop/basket/' );
        $module->redirectTo( '/user/register/' );
        return;
    }

    //
    $basket =& eZBasket::currentBasket();
    $productCollectionID = $basket->attribute( 'productcollection_id' );

    $user =& eZUser::currentUser();
    $userID = $user->attribute( 'contentobject_id' );

    $order = new eZOrder( array( 'productcollection_id' => $productCollectionID,
                                 'user_id' => $userID,
                                 'is_temporary' => 1,
                                 'created' => mktime() ) );
    $order->store();

    eZHTTPTool::setSessionVariable( 'MyTemporaryOrderID', $order->attribute( 'id' ) );

    $module->redirectTo( '/shop/confirmorder/' );
    return;
}

$basket = eZBasket::currentBasket();
$tpl =& templateInit();
$tpl->setVariable( "basket", $basket );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/basket.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'Basket' ) );
?>

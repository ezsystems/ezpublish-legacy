<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
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

include_once( "kernel/classes/ezwishlist.php" );

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];
$offset = $Params['Offset'];

$user =& eZUser::currentUser();
if ( !$user->isLoggedIn() )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( $http->hasPostVariable( "ActionAddToWishList" ) )
{

    $objectID = $http->postVariable( "ContentObjectID" );
    $object = eZContentObject::fetch( $objectID );
    $optionList =& $http->postVariable( "eZOption" );

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
            $priceObj =& $content;
        }
    }

    $wishList =& eZWishList::currentWishList();

    $item =& eZProductCollectionItem::create( $wishList->attribute( "productcollection_id" ) );

    $item->setAttribute( "contentobject_id", $objectID );
    $item->setAttribute( "item_count", 1 );
    $item->setAttribute( "price", $price );
    $item->store();

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

    foreach ( $optionIDList as $optionIDItem )
    {
        $attributeID = $optionIDItem['attribute_id'];
        $optionString = $optionIDItem['option_string'];

        $attribute =& eZContentObjectAttribute::fetch( $attributeID, $object->attribute( 'current_version' ) );
        $dataType =& $attribute->dataType();
        $optionData = $dataType->productOptionInformation( $attribute, $optionString, $item );
        if ( $optionData )
        {
            $optionItem =& eZProductCollectionItemOption::create( $item->attribute( 'id' ), $optionData['id'], $optionData['name'],
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

    $module->redirectTo( $module->functionURI( "wishlist" ) . "/" );
    return;
}

if ( $http->hasPostVariable( "RemoveProductItemButton" ) )
{
    $itemList = $http->postVariable( "RemoveProductItemDeleteList" );

    $wishList =& eZWishList::currentWishList();

    foreach ( $itemList as $item )
    {
        $wishList->removeItem( $item );
    }
    $module->redirectTo( $module->functionURI( "wishlist" ) . "/" );
    return;
}

include_once( "kernel/common/template.php" );

$tpl =& templateInit();

$wishList =& eZWishList::currentWishList();

$tpl->setVariable( "wish_list", $wishList );

$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/wishlist.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Basket' ) ) );

?>

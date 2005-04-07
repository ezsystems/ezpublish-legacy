<?php
//
// Created on: <04-Mar-2005 13:45:19 jhe>
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

include_once( "kernel/classes/ezbasket.php" );
include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

$http = eZHttpTool::instance();
$basket = eZBasket::currentBasket();
$module =& $Params["Module"];

// Verify the ObjectID input
if ( !is_numeric( $ObjectID ) )
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

// Check if the object exists on disc
if ( !eZContentObject::exists( $ObjectID ) )
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

// Check if the user can read the object
$object =& eZContentObject::fetch( $ObjectID );
if ( !$object->canRead() )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel', array( 'AccessList' => $object->accessList( 'read' ) ) );

// Check if the object has a price datatype, if not it cannot be used in the basket
$attributes = $object->contentObjectAttributes();

$priceFound = false;
foreach ( $attributes as $attribute )
{
    $dataType =& $attribute->dataType();
    if ( $dataType->isA() == "ezprice" )
    {
        $priceFound = true;
    }
}

if ( !$priceFound )
{
    include_once( 'kernel/shop/errors.php' );
    return $Module->handleError( EZ_ERROR_SHOP_NOT_A_PRODUCT, 'shop' );
}


$OptionList = $http->sessionVariable( "AddToBasket_OptionList_" . $ObjectID );

$operationResult = eZOperationHandler::execute( 'shop', 'addtobasket', array( 'basket_id' => $basket->attribute( 'id' ),
                                                                              'object_id' => $ObjectID,
                                                                              'option_list' => $OptionList ) );

switch( $operationResult['status'] )
{
    case EZ_MODULE_OPERATION_HALTED:
    {
        if ( isset( $operationResult['redirect_url'] ) )
        {
            $module->redirectTo( $operationResult['redirect_url'] );
            return;
        }
        else if ( isset( $operationResult['result'] ) )
        {
            $result =& $operationResult['result'];
            $resultContent = false;
            if ( is_array( $result ) )
            {
                if ( isset( $result['content'] ) )
                    $resultContent = $result['content'];
                if ( isset( $result['path'] ) )
                    $Result['path'] = $result['path'];
            }
            else
                $resultContent =& $result;
            $Result['content'] =& $resultContent;
            return $Result;
       }
    }break;
}


$ini = eZINI::instance();
if ( $ini->variable( 'ShopSettings', 'RedirectAfterAddToBasket' ) == 'reload' )
    $module->redirectTo( $http->sessionVariable( "FromPage" ) );
else
    $module->redirectTo( "/shop/basket/" );

?>

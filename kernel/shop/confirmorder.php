<?php
//
// Created on: <04-Dec-2002 16:15:49 bf>
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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( "kernel/classes/ezbasket.php" );
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezorder.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$tpl =& templateInit();
$tpl->setVariable( "module_name", 'shop' );

$orderID = eZHTTPTool::sessionVariable( 'MyTemporaryOrderID' );

$order = eZOrder::fetch( $orderID );
if ( !is_object( $order ) )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

$basket =& eZBasket::currentBasket();
$basket->updatePrices();

if ( get_class( $order ) == 'ezorder' )
{

    if ( $http->hasPostVariable( "ConfirmOrderButton" ) )
    {
        $order->detachProductCollection();
        $ini =& eZINI::instance();
        if ( $ini->variable( 'ShopSettings', 'ClearBasketOnCheckout' ) == 'enabled' )
        {
            include_once( "kernel/classes/ezbasket.php" );
            $basket =& eZBasket::currentBasket();
            $basket->remove();
        }
        $module->redirectTo( '/shop/checkout/' );
        return;
    }

    if ( $http->hasPostVariable( "CancelButton" ) )
    {
        $module->redirectTo( '/shop/basket/' );
        return;
    }

    $tpl->setVariable( "order", $order );
}



$operationResult = eZOperationHandler::execute( 'shop', 'confirmorder', array( 'order_id' => $order->attribute( 'id' ) ) );

switch( $operationResult['status'] )
{
    case EZ_MODULE_OPERATION_CONTINUE:
    {
        if ( $operationResult != null &&
             !isset( $operationResult['result'] ) &&
             ( !isset( $operationResult['redirect_url'] ) || $operationResult['redirect_url'] == null ) )
        {

            $Result = array();
            $Result['content'] =& $tpl->fetch( "design:shop/confirmorder.tpl" );
            $Result['path'] = array( array( 'url' => false,
                                            'text' => ezi18n( 'kernel/shop', 'Confirm order' ) ) );
        }
    }break;
    case EZ_MODULE_OPERATION_HALTED:
    {
        if (  isset( $operationResult['redirect_url'] ) )
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
        }
    }break;
    case EZ_MODULE_OPERATION_CANCELED:
    {
        $Result = array();
        $Result['content'] = "- I think you are not able to view that object :) <br/>
                              - Why?<br/>
                              - Because I think so :)";
    }

}

/*
$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/confirmorder.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Confirm order' ) ) );
*/
?>

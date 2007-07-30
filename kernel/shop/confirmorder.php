<?php
//
// Created on: <04-Dec-2002 16:15:49 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

$http = eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( "kernel/classes/ezbasket.php" );
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezorder.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$tpl = templateInit();
$tpl->setVariable( "module_name", 'shop' );

$orderID = eZHTTPTool::sessionVariable( 'MyTemporaryOrderID' );

$order = eZOrder::fetch( $orderID );
if ( !is_object( $order ) )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

if ( strtolower( get_class( $order ) ) == 'ezorder' )
{
    if ( $http->hasPostVariable( "ConfirmOrderButton" ) )
    {
        $order->detachProductCollection();
        $ini = eZINI::instance();
        if ( $ini->variable( 'ShopSettings', 'ClearBasketOnCheckout' ) == 'enabled' )
        {
            include_once( "kernel/classes/ezbasket.php" );
            $basket = eZBasket::currentBasket();
            $basket->remove();
        }
        $module->redirectTo( '/shop/checkout/' );
        return;
    }

    if ( $http->hasPostVariable( "CancelButton" ) )
    {
        $order->purge( /*$removeCollection = */ false );
        $module->redirectTo( '/shop/basket/' );
        return;
    }

    $tpl->setVariable( "order", $order );
}

$basket = eZBasket::currentBasket();
$basket->updatePrices();

$operationResult = eZOperationHandler::execute( 'shop', 'confirmorder', array( 'order_id' => $order->attribute( 'id' ) ) );

switch( $operationResult['status'] )
{
    case EZ_MODULE_OPERATION_CONTINUE:
    {
        if ( $operationResult != null &&
             !isset( $operationResult['result'] ) &&
             ( !isset( $operationResult['redirect_url'] ) || $operationResult['redirect_url'] == null ) )
        {
            $order = eZOrder::fetch( $order->attribute( 'id' ) );
            $tpl->setVariable( "order", $order );

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
        if ( isset( $operationResult['result']['content'] ) )
            $Result['content'] = $operationResult['result']['content'];
        else
            $Result['content'] = ezi18n( 'kernel/shop', "The confirm order operation was canceled. Try to checkout again." );

        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezi18n( 'kernel/shop', 'Confirm order' ) ) );
    }

}

/*
$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/confirmorder.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Confirm order' ) ) );
*/
?>

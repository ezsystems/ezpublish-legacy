<?php
//
// Created on: <07-æÅ×-2003 14:21:36 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file checkout.php
*/
//include_once( 'kernel/classes/ezorder.php' );
//include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

//include_once( 'kernel/shop/classes/ezpaymentobject.php' );

$http = eZHTTPTool::instance();
$module = $Params['Module'];

$orderID = $http->sessionVariable( 'MyTemporaryOrderID' );
$order = eZOrder::fetch( $orderID );

if ( $order instanceof eZOrder )
{
    if (  $order->attribute( 'is_temporary' ) )
    {
        $paymentObj = eZPaymentObject::fetchByOrderID( $orderID );
        if ( $paymentObj != null )
        {
            $startTime = time();
            while( ( time() - $startTime ) < 25 )
            {
                eZDebug::writeDebug( "next iteration", "checkout" );
                $order = eZOrder::fetch( $orderID );
                if ( $order->attribute( 'is_temporary' ) == 0 )
                {
                    break;
                }
                else
                {
                    sleep ( 2 );
                }
            }
        }
        $order = eZOrder::fetch( $orderID );
        if (  $order->attribute( 'is_temporary' ) == 1 && $paymentObj == null  )
        {
            $email = $order->accountEmail();
            $order->setAttribute( 'email', $email );
            $order->store();

            //include_once( "lib/ezutils/classes/ezhttptool.php" );
            $http->setSessionVariable( "UserOrderID", $order->attribute( 'id' ) );

            $operationResult = eZOperationHandler::execute( 'shop', 'checkout', array( 'order_id' => $order->attribute( 'id' ) ) );
            switch( $operationResult['status'] )
            {
                case eZModuleOperationInfo::STATUS_HALTED:
                {
                    if (  isset( $operationResult['redirect_url'] ) )
                    {
                        $module->redirectTo( $operationResult['redirect_url'] );
                        return;
                    }
                    else if ( isset( $operationResult['result'] ) )
                    {
                        $result = $operationResult['result'];
                        $resultContent = false;
                        if ( is_array( $result ) )
                        {
                            if ( isset( $result['content'] ) )
                            {
                                $resultContent = $result['content'];
                            }
                            if ( isset( $result['path'] ) )
                            {
                                $Result['path'] = $result['path'];
                            }
                        }
                        else
                            $resultContent = $result;
                        $Result['content'] = $resultContent;
                        return;
                    }
                }break;
                case eZModuleOperationInfo::STATUS_CANCELLED:
                {
                    $Result = array();
                    require_once( "kernel/common/template.php" );
                    $tpl = templateInit();

                    $tpl->setVariable( 'operation_result', $operationResult );

                    $Result['content'] = $tpl->fetch( "design:shop/cancelcheckout.tpl" ) ;
                    $Result['path'] = array( array( 'url' => false,
                                                    'text' => ezi18n( 'kernel/shop', 'Checkout' ) ) );

                    return;
                }

            }
        }
        else
        {
            if ( $order->attribute( 'is_temporary' ) == 0 )
            {
                $http->removeSessionVariable( "CheckoutAttempt" );
                $module->redirectTo( '/shop/orderview/' . $orderID );
                return;
            }
            else
            {
                // Get the attempt number and the order.
                $attempt = ($http->hasSessionVariable("CheckoutAttempt") ? $http->sessionVariable("CheckoutAttempt") : 0 );
                $attemptOrderID = ($http->hasSessionVariable("CheckoutAttemptOrderID") ? $http->sessionVariable("CheckoutAttemptOrderID") : 0 );

                // This attempt is for another order. So reset the attempt.
                if ($attempt != 0 && $attemptOrderID != $orderID) $attempt = 0;

                $http->setSessionVariable("CheckoutAttempt", ++$attempt);
                $http->setSessionVariable("CheckoutAttemptOrderID", $orderID);

                if ( $attempt < 4)
                {
                    $Result = array();
                    require_once( "kernel/common/template.php" );
                    $tpl = templateInit();
                    $tpl->setVariable( 'attempt', $attempt );
                    $tpl->setVariable( 'orderID', $orderID );
                    $Result['content'] = $tpl->fetch( "design:shop/checkoutagain.tpl" ) ;
                    $Result['path'] = array( array( 'url' => false,
                                                    'text' => ezi18n( 'kernel/shop', 'Checkout' ) ) );
                    return;
                }
                else
                {
                    // Got no receipt or callback from the payment server.
                    $http->removeSessionVariable( "CheckoutAttempt" );

                    $Result = array();
                    require_once( "kernel/common/template.php" );
                    $tpl = templateInit();
                    $tpl->setVariable ("ErrorCode", "NO_CALLBACK");
                    $tpl->setVariable ("OrderID", $orderID);

                    $Result['content'] = $tpl->fetch( "design:shop/cancelcheckout.tpl" ) ;
                    $Result['path'] = array( array( 'url' => false,
                                                    'text' => ezi18n( 'kernel/shop', 'Checkout' ) ) );
                    return;
                }
            }
        }
   }
   $module->redirectTo( '/shop/orderview/' . $orderID );
   return;

}

?>

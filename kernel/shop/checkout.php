<?php
//
// Created on: <07-æÅ×-2003 14:21:36 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file checkout.php
*/
include_once( 'kernel/classes/ezorder.php' );
include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

$orderID = eZHTTPTool::sessionVariable( 'MyTemporaryOrderID' );
$order = eZOrder::fetch( $orderID );

if ( get_class( $order ) == 'ezorder' )
{
   if (  $order->attribute( 'is_temporary' ) )
   {
        $operationResult = eZOperationHandler::execute( 'shop', 'checkout', array( 'order_id' => $order->attribute( 'id' ) ) );
        switch( $operationResult['status'] )
        {
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
                    return;
                }
            }break;
            case EZ_MODULE_OPERATION_CANCELED:
            {
                $Result = array();
                include_once( "kernel/common/template.php" );
                $tpl =& templateInit();
                $Result['content'] =& $tpl->fetch( "design:shop/cancelcheckout.tpl" ) ;
                $Result['path'] = array( array( 'url' => false,
                                                'text' => ezi18n( 'kernel/shop', 'Checkout' ) ) );

                return;
            }

        }
   }
   $module->redirectTo( '/shop/orderview/' . $orderID );
   return;

}


?>

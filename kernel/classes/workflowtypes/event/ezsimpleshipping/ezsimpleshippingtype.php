<?php
//
// Definition of eZSimpleShippingType class
//
// Created on: <09-äÅË-2002 14:42:23 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZSimpleShippingType ezsimpleshippingtype.php
  \brief The class eZSimpleShippingType handles adding shipping cost to an order

*/
class eZSimpleShippingType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = 'ezsimpleshipping';

    /*!
     Constructor
    */
    function eZSimpleShippingType()
    {
        $this->eZWorkflowEventType( eZSimpleShippingType::WORKFLOW_TYPE_STRING, ezi18n( 'kernel/workflow/event', "Simple shipping" ) );
        $this->setTriggerTypes( array( 'shop' => array( 'confirmorder' => array ( 'before' ) ) ) );
    }

    function execute( $process, $event )
    {
        $ini = eZINI::instance( 'workflow.ini' );

        $cost = $ini->variable( "SimpleShippingWorkflow", "ShippingCost" );
        $description = $ini->variable( "SimpleShippingWorkflow", "ShippingDescription" );

        $parameters = $process->attribute( 'parameter_list' );

        if ( isset( $parameters['order_id'] ) )
        {
            $orderID = $parameters['order_id'];

            $order = eZOrder::fetch( $orderID );
            $orderItems = $order->attribute( 'order_items' );
            $addShipping = true;
            foreach ( $orderItems as $orderItem )
            {
                if ( $orderItem->attribute( 'type' ) == 'ezsimpleshipping' )
                {
                    $addShipping = false;
                    break;
                }
            }
            if ( $addShipping )
            {
                $productCollection = $order->attribute( 'productcollection' );
                $orderCurrency = $productCollection->attribute( 'currency_code' );

                $cost = eZShopFunctions::convertAdditionalPrice( $orderCurrency, $cost );

                $orderItem = new eZOrderItem( array( 'order_id' => $orderID,
                                                     'description' => $description,
                                                     'price' => $cost,
                                                     'type' => 'ezsimpleshipping' )
                                              );
                $orderItem->store();
            }
        }
        return eZWorkflowType::STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerEventType( eZSimpleShippingType::WORKFLOW_TYPE_STRING, "eZSimpleShippingType" );

?>

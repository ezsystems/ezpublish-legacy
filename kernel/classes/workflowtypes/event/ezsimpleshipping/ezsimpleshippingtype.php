<?php
//
// Definition of eZSimpleShippingType class
//
// Created on: <09-äÅË-2002 14:42:23 sp>
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

/*! \file ezsimpleshippingtype.php
*/

/*!
  \class eZSimpleShippingType ezsimpleshippingtype.php
  \brief The class eZSimpleshippingType handles adding shipping cost to an order

*/
include_once( 'kernel/classes/ezorder.php' );


define( 'EZ_WORKFLOW_TYPE_SIMPLESHIPPING_ID', 'ezsimpleshipping' );

class eZSimpleShippingType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function eZSimpleShippingType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_SIMPLESHIPPING_ID, ezi18n( 'kernel/workflow/event', "Simple shipping" ) );
    }

    function execute( &$process, &$event )
    {
        $ini =& eZINI::instance( 'workflow.ini' );

        $cost = $ini->variable( "SimpleShippingWorkflow", "ShippingCost" );
        $description = $ini->variable( "SimpleShippingWorkflow", "ShippingDescription" );

        $parameters = $process->attribute( 'parameter_list' );
        $orderID = $parameters['order_id'];

        $order =& eZOrder::fetch( $orderID );
        $orderItems = $order->attribute( 'order_items' );
        $addShipping = true;
        foreach ( array_keys( $orderItems ) as $key )
        {
            $orderItem =& $orderItems[$key];
            if ( $orderItem->attribute( 'description' ) == $description )
            {
                $addShipping = false;
                break;
            }
        }
        if ( $addShipping )
        {
            $orderItem = new eZOrderItem( array( 'order_id' => $orderID,
                                                 'description' => $description,
                                                 'price' => $cost,
                                                 'vat_is_included' => true,
                                                 'vat_type_id' => 1 )
                                          );
            $orderItem->store();
        }
        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_SIMPLESHIPPING_ID, "ezsimpleshippingtype" );

?>

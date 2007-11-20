<?php
//
// Definition of eZPaymentGateway class
//
// Created on: <18-May-2004 14:18:58 dl>
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

/*! \file ezpaymentgateway.php
*/

/*!
  \class eZPaymentGateway ezpaymentgateway.php
  \brief Abstract class for all payment gateways.
*/

//include_once( 'kernel/classes/workflowtypes/event/ezpaymentgateway/ezpaymentlogger.php' );

class eZPaymentGateway
{
    /*!
	Constructor.
    */
    function eZPaymentGateway()
    {
        $this->logger = eZPaymentLogger::CreateForAdd( "var/log/eZPaymentGateway.log" );
    }

    function execute( $process, $event )
    {
        $this->logger->writeTimedString( 'You must override this function.', 'execute' );
        return eZWorkflowType::STATUS_REJECTED;
    }

    function needCleanup()
    {
        return false;
    }

    function cleanup( $process, $event )
    {
    }

    /*!
	Creates short description of order. Usually this string is
	passed to payment site as describtion of payment.
    */
    function createShortDescription( $order, $maxDescLen )
    {
        //__DEBUG__
	    $this->logger->writeTimedString("createShortDescription");
        //___end____

        $descText       = '';
        $productItems   = $order->productItems();

        foreach( $productItems as $item )
        {
            $descText .= $item['object_name'] . ',';
        }
        $descText   = rtrim( $descText, "," );

        $descLen    = strlen( $descText );
        if( ($maxDescLen > 0) && ($descLen > $maxDescLen) )
        {
            $descText = substr($descText, 0, $maxDescLen - 3) ;
            $descText .= '...';
        }

        //__DEBUG__
	    $this->logger->writeTimedString("descText=$descText");
        //___end____

        return $descText;
    }

    public $logger;
}
?>

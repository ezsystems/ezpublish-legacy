<?php
//
// Definition of eZPaypalGateway class
//
// Created on: <18-Jul-2004 14:18:58 dl>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*! \file ezpaypalgateway.php
*/

/*!
  \class eZPaypalGateway ezpaypalgateway.php
  \brief The class eZPaypalGateway implements
  functions to perform redirection to the PayPal
  payment server.
*/

include_once( 'kernel/shop/classes/ezpaymentobject.php' );
include_once( 'kernel/shop/classes/ezredirectgateway.php' );

//__DEBUG__
include_once( 'kernel/classes/workflowtypes/event/ezpaymentgateway/ezpaymentlogger.php' );
//___end____

define( "EZ_PAYMENT_GATEWAY_TYPE_PAYPAL", "ezpaypal" );

class eZPaypalGateway extends eZRedirectGateway
{
    /*!
        Constructor.
    */
    function eZPaypalGateway()
    {
        //__DEBUG__
            $this->logger   = eZPaymentLogger::CreateForAdd( "var/log/eZPaypalType.log" );
            $this->logger->writeTimedString( 'eZPaypalGateway::eZPaypalGateway()' );
        //___end____
    }

    /*!
        Creates new eZPaypalGateway object.
    */
    function &createPaymentObject( &$processID, &$orderID )
    {
        //__DEBUG__
            $this->logger->writeTimedString("createPaymentObject");
        //___end____

        return eZPaymentObject::createNew( $processID, $orderID, 'Paypal' );
    }
    
    /*!
        Creates redirectional url to paypal server.
    */
    function &createRedirectionUrl( &$process )
    {
        //__DEBUG__
            $this->logger->writeTimedString("createRedirectionUrl");
        //___end____
        
        $paypalINI      =& eZINI::instance( 'paypal.ini' );
    
        $paypalServer   = $paypalINI->variable( 'ServerSettings', 'ServerName');
        $requestURI     = $paypalINI->variable( 'ServerSettings', 'RequestURI');
        $business       = urlencode( $paypalINI->variable( 'PaypalSettings', 'Business' ) );
        
        $processParams  =& $process->attribute( 'parameter_list' );
        $orderID        = $processParams['order_id'];

        $indexDir       = eZSys::indexDir();
        $localHost      = eZSys::hostname();
        $localURI       = eZSys::serverVariable( 'REQUEST_URI' );
        
        $order          =& eZOrder::fetch( $orderID );
        $amount         = urlencode( $order->attribute( 'total_inc_vat' ) );
        
        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $locale         =& eZLocale::instance();
        $currency       = urlencode( $locale->currencyShortName() );

        $maxDescLen     = $paypalINI->variable( 'PaypalSettings', 'MaxDescriptionLength');
        $itemName       = urlencode( $this->createShortDescription( $order, $maxDescLen ) );
                
        $accountInfo    = $order->attribute( 'account_information' );
        $first_name     = urlencode( $accountInfo['first_name'] );
        $last_name      = urlencode( $accountInfo['last_name'] );
        $street         = urlencode( $accountInfo['street2'] );
        $zip            = urlencode( $accountInfo['zip'] );
        $state          = urlencode( $accountInfo['state'] );
        $place          = urlencode( $accountInfo['place'] );
        $image_url      = "http://$localHost" . urlencode( $paypalINI->variable( 'PaypalSettings', 'LogoURI' ) );
        $background     = urlencode( $paypalINI->variable( 'PaypalSettings', 'BackgroundColor' ) );
        $pageStyle      = urlencode( $paypalINI->variable( 'PaypalSettings', 'PageStyle' ) );
        $noNote         = urlencode( $paypalINI->variable( 'PaypalSettings', 'NoNote' ) );
        $noteLabel      = ($noNote == 1) ? '' : urlencode( $paypalINI->variable( 'PaypalSettings', 'NoteLabel' ) );
        $noShipping     = 1;

        $url =  $paypalServer  . $requestURI    .
                "?cmd=_ext-enter"               .
                "&redirect_cmd=_xclick"         .
                "&business=$business"           .
                "&item_name=$itemName"          .
                "&custom=$orderID"              .
                "&amount=$amount"               .
                "&currency_code=$currency"      .
                "&first_name=$first_name"       .
                "&last_name=$last_name"         .
                "&address1=$street"             .
                "&zip=$zip"                     .
                "&state=$state"                 .
                "&city=$place"                  .
                "&image_url=$image_url"         .
                "&cs=$background"               .
                "&page_style=$pageStyle"        .
                "&no_shipping=$noShipping"      .
                "&cn=$noteLabel"                .
                "&no_note=$noNote"              .
//                "&notify_url=http://$localHost" . $indexDir . "/paypal/notify_url/".
//                "&return=http://$localHost"     . $indexDir . "/shop/checkout/" .
//                "&cancel_return=http://$localHost" . $indexDir. "/shop/basket/";
                "&notify_url=http://195.138.86.178:8088" . $indexDir . "/paypal/notify_url/".
                "&return=http://195.138.86.178:8088"     . $indexDir . "/shop/checkout/" .
                "&cancel_return=http://195.138.86.178:8088" . $indexDir. "/shop/basket/";

                
        //__DEBUG__
            $this->logger->writeTimedString("business       = $business");
            $this->logger->writeTimedString("item_name      = $itemName");
            $this->logger->writeTimedString("custom         = $orderID");
            $this->logger->writeTimedString("no_shipping    = $noShipping");
            $this->logger->writeTimedString("localHost      = $localHost");
            $this->logger->writeTimedString("amount         = $amount");
            $this->logger->writeTimedString("currency_code  = $currency");
            $this->logger->writeTimedString("notify_url     = http://$localHost"    . $indexDir . "/paypal/notify_url/");
            $this->logger->writeTimedString("return         = http://$localHost"    . $indexDir . "/shop/checkout/");
            $this->logger->writeTimedString("cancel_return  = http://$localHost"    . "/shop/basket/");
        //___end____
        
        return $url;
    }
}

eZPaymentGatewayType::registerGateway( EZ_PAYMENT_GATEWAY_TYPE_PAYPAL, "ezpaypalgateway", "Paypal" );

?>

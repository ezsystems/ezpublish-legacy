<?php
//
// Definition of eZPaymentCallbackChecker class
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

/*! \file ezpaymentcallbackchecker.php
*/

/*!
  \class eZPaymentCallbackChecker ezpaymentcallbackchecker.php
  \brief Routines for support callback(postbacks) in redirectional
  payment gateways.
*/

include_once( 'kernel/classes/workflowtypes/event/ezpaymentgateway/ezpaymentlogger.php' );
include_once( 'kernel/shop/classes/ezpaymentobject.php' );
include_once( 'kernel/classes/ezorder.php' );


class eZPaymentCallbackChecker
{
    /*! 
        Constructor.
    */
    function eZPaymentCallbackChecker( $iniFile )
    {
        $this->logger   =& eZPaymentLogger::CreateForAdd( 'var/log/eZPaymentChecker.log' );
        $this->ini      =& eZINI::instance( $iniFile );
    }

    /*! 
        Parses 'POST' response and create array with received data.
    */
    function createDataFromPOST()
    {
        $this->logger->writeTimedString( 'createDataFromPOST' );
        $this->callbackData = array();

        eZSys::removeMagicQuotes();
        foreach( $_POST as $key => $value )
        {
            $this->callbackData[$key] = $value;
            $this->logger->writeTimedString( "$key = $value" );
        }

        return ( count( $this->callbackData ) > 0 );
    }

    /*! 
        Parses 'GET' response and create array with received data.
    */
    function createDataFromGET()
    {
        $this->logger->writeTimedString( 'createDataFromGET' );
        $this->callbackData = array();

        $query_string = eZSys::serverVariable( 'QUERY_STRING' );
        if( $query_string )
        {
            $key_value_pairs = explode( '&', $query_string );

            foreach( $key_value_pairs as $key_value )
            {
                $data = explode( '=', $key_value );
                $this->callbackData[$data[0]] = $data[1];
                $this->logger->writeTimedString( "$data[0] = $data[1]" );
            }
        }

        return ( count( $this->callbackData ) > 0 );
    }


    /*! 
        Sends POST request.
    */
    function sendPOSTRequest( $server, $port, $serverMethod, $request, $timeout=30)
    {
        $pos = strpos($server, '://');
        if( $pos !== false )
            $server = substr($server, $pos+3);

        $fp = fsockopen( $server, $port, $errno, $errstr, $timeout );
      
        if( $fp )
        {
            $theCall    =   "POST $serverMethod HTTP/1.0\r\n"                       .
                            "Host: $server\r\n"                                   .
                            "Content-Type: application/x-www-form-urlencoded\r\n"   .
                            "Content-Length: ".strlen( $request )."\r\n\r\n"     .
                            $request."\r\n\r\n";
            
            if ( !fputs( $fp, "$theCall", strlen( $theCall ) ) )
            {
                $this->logger->writeTimedString( "Could not write to socket to server: $server:$port", 'sendPOSTRequest faild' );
                return null;
            }

            return $this->handleResponse( $fp );
        }
      
        $this->logger->writeTimedString( "Unable to open socket on $server:$port. errno = $errno, errstr = $errstr", 'sendPOSTRequest faild' );
        return null;
    }

    /*!
        Asks paypal's server to validate callback.
    */
    function requestValidation()
    {  
        return false;
    }

    /*! 
        Creates order and payment objects by orderID.
        After this 'checkAmount', 'checkCurrency' can be called.
    */
    function setupOrderAndPaymentObject( $orderID )
    {
        if ( isset( $orderID ) && $orderID > 0 )
        {
            $this->paymentObject =& eZPaymentObject::fetchByOrderID( $orderID );
            if ( isset( $this->paymentObject ) )
            {
                $this->order =& eZOrder::fetch( $orderID );
                if ( isset( $this->order ) )
                {
                    return true;
                }
                $this->logger->writeTimedString( "Unable to fetch order object by orderID=$orderID", 'setupOrderAndPaymentObject faild' );
                return false;    
            }
            $this->logger->writeTimedString( "Unable to fetch payment object by orderID=$orderID", 'setupOrderAndPaymentObject faild' );
            return false;    
        }
        $this->logger->writeTimedString( "Invalid orderID=$orderID", 'setupOrderAndPaymentObject faild' );
        return false;
    }
  
    /*! 
        Approves payment and continues workflow.
    */
    function approvePayment( $continueWorkflow=true )
    {
        if( $this->paymentObject )
        {
            $this->paymentObject->approve();
            $this->paymentObject->store();

            $this->logger->writeTimedString( 'payment was approved' );

            return ( $continueWorkflow ? $this->continueWorkflow() : null );
        }
    
        $this->logger->writeTimedString( "payment object is not set", 'approvePayment faild' );
        return null;
    }

    /*! 
        Continues workflow.
    */
    function continueWorkflow()
    {
        if( $this->paymentObject )
        {
            $workflowID = $this->paymentObject->attribute( 'workflowprocess_id' );
            if( $workflowID )
            {
                return eZPaymentObject::continueWorkflow( $workflowID );
            }

            $this->logger->writeTimedString( "workflowID is not set", 'continueWorkflow faild' );
            return null;
        }

        $this->logger->writeTimedString( "payment object is not set", 'continueWorkflow faild' );
        return null;
    }
  
    /*! 
        Returns value of specified field.
    */
    function getFieldValue( $field )
    {
        if ( isset( $this->callbackData[$field] ) )
        {
            return $this->callbackData[ $field ];
        }

        $this->logger->writeTimedString( "field $field doesn't exists.", 'getFieldValue faild' );
        return null;
    }

    /*! 
        Reads ip list from ini file and searches in it
        server's ip.
    */
    function checkServerIP()
    {
        $remoteHostIP   = eZSys::serverVariable( 'REMOTE_ADDR' );
        $serverIPList   = $this->ini->variable( 'ServerSettings', 'ServerIP');

        if ( is_array( $serverIPList ) && in_array( $remoteHostIP, $serverIPList ) )
        {
            return true;
        }

        $this->logger->writeTimedString( "server with ip = $remoteHostIP doesn't exists.", 'checkServerIP faild' );
        $this->logger->writeTimedString( $serverIPList, 'serverIPList from ini file is' );

        return false;
    }

    /*! 
        Simple amount checking.
    */
    function checkAmount( $amount )
    {
        $orderAmount = $this->order->attribute( 'total_inc_vat' );
        if ( $orderAmount == $amount )
        {
            return true;
        }

        $this->logger->writeTimedString( "orderAmount = $orderAmount. Expected amount = $amount", 'checkAmount faild' );
        return false;
    }
    
    /*! 
        Simple currency checking.
    */
    function checkCurrency( $currency )
    {
        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $locale         =& eZLocale::instance();
        $orderCurrency  =  $locale->currencyShortName();

        if ( $orderCurrency == $currency )
        {
            return true;
        }

        $this->logger->writeTimedString( "orderCurrency = $orderCurrency. Expected currency = $curency", 'checkCurrency faild' );
        return false;
    }

    function checkDataField( $field, $value )
    {
        $isValid = false;
        
        if( isset( $this->callbackData[$field] ) )
        {
            $isValid = ( $this->callbackData[$field] == $value );
        }

        //__DEBUG__
            if( !$isValid )
            {
                $this->logger->writeTimedString('check Field ----');
                $this->logger->writeTimedString("ERROR - receiving value doesn't match!!!");
                $this->logger->writeTimedString("Field          :".$field);
                $this->logger->writeTimedString("Value          :".$this->callbackData[$field]);
                $this->logger->writeTimedString("Expected value :".$value);
                $this->logger->writeTimedString('----');
            }
        //___end____

        return $isValid;
    }

    // you must override below
    /*! 
        Postback request which will be sent to payment server.
    */
    function &buildRequestString()
    {
        $this->logger->writeTimedString( 'You must override this function.', 'buildRequestString faild' );
        return null;
    }

    /*! 
        Handles server response.
    */
    function &handleResponse( &$socket )
    {
        $this->logger->writeTimedString( 'You must override this function.', 'handlePOSTResponse faild' );
        return null;
    }

    var $logger;
    var $ini;
    var $callbackData;
    var $paymentObject;
    var $order;
}

?>
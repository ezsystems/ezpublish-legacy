<?php
//
// Definition of eZPaypalChecker class
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezpaypalchecker.php
*/

/*!
  \class eZPaypalChecker ezpaypalchecker.php
  \brief The class eZPaypalChecker implements
  functions to perform verification of the
  paypal's callback.
*/

include_once( 'kernel/shop/classes/ezpaymentcallbackchecker.php' );

class eZPaypalChecker extends eZPaymentCallbackChecker
{
    /*!
        Constructor.
    */
    function eZPaypalChecker( $iniFile )
    {
        $this->eZPaymentCallbackChecker( $iniFile );
        $this->logger =& eZPaymentLogger::CreateForAdd( 'var/log/eZPaypalChecker.log' );    
    }

    /*!
        Asks paypal's server to validate callback.
    */
    function requestValidation()
    {
        $server     = $this->ini->variable( 'ServerSettings', 'ServerName');
        $serverPort = 80;
        $requestURI = $this->ini->variable( 'ServerSettings', 'RequestURI');
        $request    = $this->buildRequestString();
        $response   = $this->sendPOSTRequest( $server, $serverPort, $requestURI, $request);

        $this->logger->writeTimedString( $response, 'requestValidation. response from server is' );
       
        if( $response && strcasecmp( $response, 'VERIFIED' ) == 0 )
        {
            return true;
        }
      
        $this->logger->writeTimedString( 'invalid response' );
        return false;
    }

    /*!
        Convinces of completion of the payment.
    */
    function checkPaymentStatus()
    {
        if( $this->checkDataField( 'payment_status', 'Completed' ) )
        {
            return true;
        }

        $this->logger->writeTimedString( 'checkPaymentStatus faild' );
        return false;
    }

    // overrides
    /*!
        Creates resquest string which is used to 
        confirm paypal's callback.
    */
    function &buildRequestString()
    {
        $request = "cmd=_notify-validate";
        foreach( $this->callbackData as $key => $value )
        {
            $request .= "&$key=".urlencode( $value );
        }
        return $request;
    }
    
    function &handleResponse( &$socket )
    {
        if( $socket )
        {
            while ( !feof( $socket ) )
            {
                $response = fgets ( $socket, 1024 );
            }
      
            fclose( $socket );
            return $response;
        }

        $this->logger->writeTimedString( "socket = $socket is invalid.", 'handlePOSTResponse faild' );
        return null;
    }
}

?>
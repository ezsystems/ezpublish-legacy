<?php
//
// Definition of eZRedirectGateway class
//
// Created on: <11-Jun-2004 14:18:58 dl>
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

/*! \file ezredirectgateway.php
*/

/*!
  \class eZRedirectGateway ezredirectgateway.php
  \brief The class eZRedirectGateway is a
  base class for payment gateways which
  support payment through redirection to
  the payment site and payment notifications
  throught callbacks(postbacks).
*/

define( "EZ_REDIRECT_GATEWAY_OBJECT_NOT_CREATED", 1 );
define( "EZ_REDIRECT_GATEWAY_OBJECT_CREATED"    , 2 );

include_once( 'kernel/shop/classes/ezpaymentgateway.php' );

class eZRedirectGateway extends eZPaymentGateway
{
    /*!
        Constructor.
    */
    function eZRedirectGateway()
    {                     
        //__DEBUG__
            $this->logger   = eZPaymentLogger::CreateForAdd( "var/log/eZRedirectGateway.log" );
            $this->logger->writeTimedString( 'eZRedirectGateway::eZRedirectGateway()' );
        //___end____
    }

    function execute( &$process, &$event )
    {
        //__DEBUG__
            $this->logger->writeTimedString("execute");
        //___end____
        
        $processParameters =& $process->attribute( 'parameter_list' );
        $processID         =  $process->attribute( 'id' );

        switch ( $process->attribute( 'event_state' ) )
        {
            case EZ_REDIRECT_GATEWAY_OBJECT_CREATED:
                {
                    //__DEBUG__
                        $this->logger->writeTimedString("case EZ_REDIRECT_GATEWAY_OBJECT_CREATED");
                    //___end____
                                                    
                    $thePayment =& eZPaymentObject::fetchByProcessID( $processID );
                    if ( is_object( $thePayment ) && $thePayment->approved() )
                    {
                        //__DEBUG__
                            $this->logger->writeTimedString("Payment accepted.");
                        //___end____
                        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
                    }
                    //__DEBUG__
                        else
                        {
                            $this->logger->writeTimedString("Error. Payment rejected: unable to fetch 'eZPaymentObject' or payment status 'not approved'");
                        }
                     //___end____
                   
                    return EZ_WORKFLOW_TYPE_STATUS_REJECTED;
                }
                break;
    
            case EZ_REDIRECT_GATEWAY_OBJECT_NOT_CREATED:
                //__DEBUG__
                    $this->logger->writeTimedString("case EZ_REDIRECT_GATEWAY_OBJECT_NOT_CREATED");
                //___end____
        
            default:
                {
                    $orderID        = $processParameters['order_id'];
                    $paymentObject  =& $this->createPaymentObject( $processID, $orderID );

                    if( is_object( $paymentObject ) )
                    {
                        $paymentObject->store();
                        $process->setAttribute( 'event_state', EZ_REDIRECT_GATEWAY_OBJECT_CREATED );

                        $process->RedirectUrl = $this->createRedirectionUrl( $process );
                    }
                    else
                    {
                        //__DEBUG__
                            $this->logger->writeTimedString("Unable to create 'eZPaymentObject'. Payment rejected.");
                        //___end____
                        return EZ_WORKFLOW_TYPE_STATUS_REJECTED;
                    }
                }
                break;
        };

        //__DEBUG__
            $this->logger->writeTimedString("return EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT");
        //___end____
        return EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT;
    }
    
    function needCleanup()
    {
        return true;
    }
    
    /*!
        Removes temporary eZPaymentObject from database.
    */
    function cleanup( &$process, &$event )
    {
        //__DEBUG__
            $this->logger->writeTimedString("cleanup");
        //___end____

        $paymentObj =& eZPaymentObject::fetchByProcessID( $process->attribute( 'id' ) );
        
        if ( is_object( $paymentObj ) )
        {
            $paymentObj->remove();
        }
    }
    
    /*!
        Creates instance of subclass of eZPaymentObject which stores
        information about payment processing(orderID, workflowID, ...).
        Must be overridden in subclass. 
    */
    function &createPaymentObject( &$processID, &$orderID )
    {
        //__DEBUG__
            $this->logger->writeTimedString("createPaymentObject. You have to override this");
        //___end____
        $theObject = null;
        return $theObject;
    }

    /*!
        Creates redirection url to payment site.
        Must be overridden in subclass. 
    */
    function &createRedirectionUrl( &$process )
    {
        //__DEBUG__
            $this->logger->writeTimedString("createRedirectionUrl. You have to override this");
        //___end____
        $theObject = null;
        return $theObject;
    }
}

?>
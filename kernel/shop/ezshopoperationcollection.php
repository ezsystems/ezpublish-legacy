<?php
//
// Definition of eZShopOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
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

/*! \file ezcontentoperationcollection.php
*/

/*!
  \class eZShopOperationCollection ezcontentoperationcollection.php
  \brief The class eZShopOperationCollection does

*/

class eZShopOperationCollection
{
    /*!
     Constructor
    */
    function eZShopOperationCollection()
    {
    }

    function fetchOrder( $orderID )
    {
        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );

    }

    function activateOrder( $orderID )
    {
        include_once( "kernel/classes/ezbasket.php" );
        include_once( 'kernel/classes/ezorder.php' );

        $order =& eZOrder::fetch( $orderID );
        $order->activate();

        $basket =& eZBasket::currentBasket( true, $orderID);
        $basket->remove();

        include_once( "lib/ezutils/classes/ezhttptool.php" );
        eZHTTPTool::setSessionVariable( "UserOrderID", $orderID );
        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
    }

    function sendOrderEmails( $orderID )
    {
        include_once( "kernel/classes/ezbasket.php" );
        include_once( 'kernel/classes/ezorder.php' );
        $order =& eZOrder::fetch( $orderID );

        // Fetch the shop account handler
        include_once( 'kernel/classes/ezshopaccounthandler.php' );
        $accountHandler =& eZShopAccountHandler::instance();
        $email = $accountHandler->email( $order );
        eZDebug::writeDebug( $email,  'email' );

        include_once( "kernel/common/template.php" );
        $tpl =& templateInit();
        $tpl->setVariable( 'order', $order );
//        $tpl->setVariable( 'object', $object );
        $templateResult =& $tpl->fetch( 'design:shop/orderemail.tpl' );

//        $subject = "Order number " . $order->attribute( 'id' );
        $subject =& $tpl->variable( 'subject' );

        $receiver = $email;

        include_once( 'lib/ezutils/classes/ezmail.php' );
        include_once( 'lib/ezutils/classes/ezmailtransport.php' );
        $ini =& eZINI::instance();
        $mail = new eZMail();

        if ( !$mail->validate( $receiver ) )
        {
        }
        $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( "MailSettings", "AdminEmail" );

        $mail->setReceiver( $email );
        $mail->setSender( $emailSender );
        $mail->setSubject( $subject );
        $mail->setBody( $templateResult );
        $mailResult = eZMailTransport::send( $mail );


        $email = $ini->variable( 'MailSettings', 'AdminEmail' );

        $mail = new eZMail();

        if ( !$mail->validate( $receiver ) )
        {
        }

        $mail->setReceiver( $email );
        $mail->setSender( $emailSender );
        $mail->setSubject( $subject );
        $mail->setBody( $templateResult );
        $mailResult = eZMailTransport::send( $mail );

        return array( 'status' => EZ_MODULE_OPERATION_CONTINUE );
    }


}

?>

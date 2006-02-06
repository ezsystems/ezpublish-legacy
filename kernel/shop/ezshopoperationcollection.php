<?php
//
// Definition of eZShopOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.5.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
        $templateResult =& $tpl->fetch( 'design:shop/orderemail.tpl' );

        $subject = $tpl->variable( 'subject' );

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

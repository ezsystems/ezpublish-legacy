<?php
//
// Created on: <08-Aug-2006 15:23:10 bjorn>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZDefaultConfirmOrderHandler ezdefaultconfirmorderhandler.php
  \brief The class eZDefaultConfirmOrderHandler does

*/

class eZDefaultConfirmOrderHandler
{
    /*!
     Constructor
    */
    function eZDefaultConfirmOrderHandler()
    {
    }

    function execute( $params = array() )
    {
        $ini = eZINI::instance();
        $sendOrderEmail = $ini->variable( 'ShopSettings', 'SendOrderEmail' );
        if ( $sendOrderEmail == 'enabled' )
        {
            $this->sendOrderEmail( $params );
        }
    }

    function sendOrderEmail( $params )
    {
        $ini = eZINI::instance();
        if ( isset( $params['order'] ) and
             isset( $params['email'] ) )
        {
            $order = $params['order'];
            $email = $params['email'];

            require_once( "kernel/common/template.php" );
            $tpl = templateInit();
            $tpl->setVariable( 'order', $order );
            $templateResult = $tpl->fetch( 'design:shop/orderemail.tpl' );

            $subject = $tpl->variable( 'subject' );

            $mail = new eZMail();

            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            if ( !$emailSender )
                $emailSender = $ini->variable( "MailSettings", "AdminEmail" );

            if ( $tpl->hasVariable( 'content_type' ) )
                $mail->setContentType( $tpl->variable( 'content_type' ) );

            $mail->setReceiver( $email );
            $mail->setSender( $emailSender );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );

            $email = $ini->variable( 'MailSettings', 'AdminEmail' );

            $mail = new eZMail();

            if ( $tpl->hasVariable( 'content_type' ) )
                $mail->setContentType( $tpl->variable( 'content_type' ) );

            $mail->setReceiver( $email );
            $mail->setSender( $emailSender );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );
        }
    }
}

?>

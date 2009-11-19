<?php
//
// Definition of eZSMTPTransport class
//
// Created on: <10-Dec-2002 15:20:20 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  \class eZSMTPTransport ezsmtptransport.php
  \brief The class eZSMTPTransport does

*/

class eZSMTPTransport extends eZMailTransport
{
    /*!
     Constructor
    */
    function eZSMTPTransport()
    {
    }

    function sendMail( eZMail $mail )
    {
        $ini = eZINI::instance();
        $parameters = array();
        $parameters['host'] = $ini->variable( 'MailSettings', 'TransportServer' );
        $parameters['helo'] = $ini->variable( 'MailSettings', 'SenderHost' );
        $parameters['port'] = $ini->variable( 'MailSettings', 'TransportPort' );
        $user = $ini->variable( 'MailSettings', 'TransportUser' );
        $password = $ini->variable( 'MailSettings', 'TransportPassword' );
        if ( $user and
             $password )
        {
            $parameters['auth'] = true;
            $parameters['user'] = $user;
            $parameters['pass'] = $password;
        }

        /* If email sender hasn't been specified or is empty
         * we substitute it with either MailSettings.EmailSender or AdminEmail.
         */
        if ( !$mail->senderText() )
        {
            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            if ( !$emailSender )
                $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );

            eZMail::extractEmail( $emailSender, $emailSenderAddress, $emailSenderName );

            if ( !eZMail::validate( $emailSenderAddress ) )
                $emailSender = false;

            if ( $emailSender )
                $mail->setSenderText( $emailSender );
        }

        $sendData = array();
//        $sendData['from'] = $mail->senderText();
        $from = $mail->sender();
        $sendData['from'] = isset( $from['email'] ) ? $from['email'] : '';
        $sendData["recipients"] = $mail->receiverTextList();
        $sendData['CcRecipients'] = $mail->ccReceiverTextList();
        $sendData['BccRecipients'] = $mail->bccReceiverTextList();

        $excludeHeaders = $ini->variable( 'MailSettings', 'ExcludeHeaders' );
        if ( count( $excludeHeaders ) > 0 )
            $sendData['headers'] = $mail->headerTextList( array( 'exclude-headers' => $excludeHeaders ) );
        else
            $sendData['headers'] = $mail->headerTextList();
        $sendData['body'] = $mail->body();

        $smtp = new smtp( $parameters );
        $smtpConnected = $smtp->connect();
        if ( $smtpConnected )
        {
            $result = $smtp->send( $sendData );
            $mailSent = true;
            if ( isset( $smtp->errors ) and is_array( $smtp->errors ) and count( $smtp->errors ) > 0 )
            {
                $mailSent = false;
                foreach ( $smtp->errors as $error )
                {
                    eZDebug::writeError( "Error sending SMTP mail: " . $error, "eZSMTPTransport::sendMail()" );
                }
            }
            $smtp->quit();
        }
        else
        {
            eZDebug::writeError( 'Unable to connect to SMTP server ' . $parameters['host'], 'error.log', __METHOD__ );
            $mailSent = false;
        }
        return $mailSent;
    }
}

?>

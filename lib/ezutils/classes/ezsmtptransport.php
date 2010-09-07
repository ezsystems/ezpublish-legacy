<?php
//
// Definition of eZSMTPTransport class
//
// Created on: <10-Dec-2002 15:20:20 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
        $parameters['connectionType'] = $ini->variable( 'MailSettings', 'TransportConnectionType' );
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

        $excludeHeaders = $ini->variable( 'MailSettings', 'ExcludeHeaders' );
        if ( count( $excludeHeaders ) > 0 )
            $mail->Mail->appendExcludeHeaders( $excludeHeaders );

        $options = new ezcMailSmtpTransportOptions();
        if( $parameters['connectionType'] )
        {
            $options->connectionType = $parameters['connectionType'];
        }
        $smtp = new ezcMailSmtpTransport( $parameters['host'], $user, $password, 
        $parameters['port'], $options );

        // If in debug mode, send to debug email address and nothing else
        if ( $ini->variable( 'MailSettings', 'DebugSending' ) == 'enabled' )
        {
            $mail->Mail->to = array( new ezcMailAddress( $ini->variable( 'MailSettings', 'DebugReceiverEmail' ) ) );
            $mail->Mail->cc = array();
            $mail->Mail->bcc = array();
        }

        // send() from ezcMailSmtpTransport doesn't return anything (it uses exceptions in case
        // something goes bad)
        try
        {
            $smtp->send( $mail->Mail );
        }
        catch ( ezcMailException $e )
        {
            eZDebug::writeError( $e->getMessage(), 'eZSMTPTransport::sendMail' );
            return false;
        }

        // return true in case of no exceptions
        return true;
    }
}

?>

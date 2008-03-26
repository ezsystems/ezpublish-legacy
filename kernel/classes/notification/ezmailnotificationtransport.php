<?php
//
// Definition of eZMailNotificationTransport class
//
// Created on: <13-May-2003 13:22:20 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

/*! \file ezmailnotificationtransport.php
*/

/*!
  \class eZMailNotificationTransport ezmailnotificationtransport.php
  \brief The class eZMailNotificationTransport does

*/

class eZMailNotificationTransport extends eZNotificationTransport
{
    /*!
     Constructor
    */
    function eZMailNotificationTransport()
    {
        $this->eZNotificationTransport();
    }

    function send( $addressList = array(), $subject, $body, $transportData = null, $parameters = array() )
    {
        include_once( 'lib/ezutils/classes/ezmail.php' );
        include_once( 'lib/ezutils/classes/ezmailtransport.php' );
        $ini =& eZINI::instance();
        $mail = new eZMail();
        $addressList = $this->prepareAddressString( $addressList, $mail );

        if ( $addressList == false )
        {
            eZDebug::writeError( 'Error with receiver', 'eZMailNotificationTransport::send()' );
            return false;
        }

        $notificationINI =& eZINI::instance( 'notification.ini' );
        $emailSender = $notificationINI->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( "MailSettings", "AdminEmail" );

        foreach ( $addressList as $addressItem )
        {
            $mail->extractEmail( $addressItem, $email, $name );
            $mail->addBcc( $email, $name );
        }
        $mail->setSender( $emailSender );
        $mail->setSubject( $subject );
        $mail->setBody( $body );
        if ( isset( $parameters['message_id'] ) )
            $mail->addExtraHeader( 'Message-ID', $parameters['message_id'] );
        if ( isset( $parameters['references'] ) )
            $mail->addExtraHeader( 'References', $parameters['references'] );
        if ( isset( $parameters['reply_to'] ) )
            $mail->addExtraHeader( 'In-Reply-To', $parameters['reply_to'] );
        if ( isset( $parameters['from'] ) )
            $mail->setSenderText( $parameters['from'] );
        $mailResult = eZMailTransport::send( $mail );
        return $mailResult;
    }


    function prepareAddressString( $addressList, &$mail )
    {
        if ( is_array( $addressList ) )
        {
            $validatedAddressList = array();
            foreach ( $addressList as $address )
            {
                if ( $mail->validate( $address ) )
                {
                    $validatedAddressList[] = $address;
                }
            }
//             $addressString = '';
//             if ( count( $validatedAddressList ) > 0 )
//             {
//                 $addressString = implode( ',', $validatedAddressList );
//                 return $addressString;
//             }
            return $validatedAddressList;
        }
        else if ( strlen( $addressList ) > 0 )
        {
            if ( $mail->validate( $addressList ) )
            {
                return $addressList;
            }
        }
        return false;
    }
}

?>

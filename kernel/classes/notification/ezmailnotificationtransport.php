<?php
//
// Definition of eZMailNotificationTransport class
//
// Created on: <13-May-2003 13:22:20 sp>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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

    function send( $addressList = array(), $subject, $body, $transportData = null )
    {
        include_once( 'lib/ezutils/classes/ezmail.php' );
        include_once( 'lib/ezutils/classes/ezmailtransport.php' );
        $ini =& eZINI::instance();
        $mail = new eZMail();
        $addressList = $this->prepareAddressString( $addressList, $mail );

        if ( $addressList == false )
        {
            eZDebug::writeError( 'Error with reciever', 'eZMailNotificationTransport::send()' );
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
        $mail->setReceiver( '' );
        $mail->setSender( $emailSender );
        $mail->setSubject( $subject );
        $mail->setBody( $body );
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
            if ( $mail->validate( $address ) )
            {
                return array( $address );
            }
        }
        return false;
    }
}

?>

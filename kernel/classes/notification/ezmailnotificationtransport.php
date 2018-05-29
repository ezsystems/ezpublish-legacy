<?php
/**
 * File containing the eZMailNotificationTransport class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZMailNotificationTransport ezmailnotificationtransport.php
  \brief The class eZMailNotificationTransport does

*/

class eZMailNotificationTransport extends eZNotificationTransport
{
    function send( $addressList = array(), $subject, $body, $transportData = null, $parameters = array() )
    {
        $ini = eZINI::instance();
        $mail = new eZMail();
        $addressList = $this->prepareAddressString( $addressList, $mail );

        if ( $addressList == false )
        {
            eZDebug::writeError( 'Error with receiver', __METHOD__ );
            return false;
        }

        $notificationINI = eZINI::instance( 'notification.ini' );
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
        if ( isset( $parameters['content_type'] ) )
            $mail->setContentType( $parameters['content_type'] );
        $mailResult = eZMailTransport::send( $mail );
        return $mailResult;
    }


    function prepareAddressString( $addressList, $mail )
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

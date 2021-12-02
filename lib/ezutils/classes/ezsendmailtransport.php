<?php
/**
 * File containing the eZSendmailTransport class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZSendmailTransport ezsendmailtransport.php
  \brief Sends the email message to sendmail which takes care of sending the actual message.

  Uses the mail() function in PHP to pass the email to the sendmail system.

*/

class eZSendmailTransport extends eZMailTransport
{
    function sendMail( eZMail $mail )
    {
        $ini = eZINI::instance();
        $sendmailOptions = '';
        $emailFrom = $mail->sender();
        $emailSender = isset( $emailFrom['email'] ) ? $emailFrom['email'] : false;
        if ( !$emailSender || ( is_countable( $emailSender ) && count( $emailSender) <= 0 ) )
            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );
        if ( !eZMail::validate( $emailSender ) )
            $emailSender = false;

        $sendmailOptionsArray = $ini->variable( 'MailSettings', 'SendmailOptions' );
        if( is_array($sendmailOptionsArray) )
            $sendmailOptions = implode( ' ', $sendmailOptionsArray );
        elseif( !is_string($sendmailOptionsArray) )
            $sendmailOptions = $sendmailOptionsArray;
        if ( $emailSender )
            $sendmailOptions .= ' -f'. $emailSender;

        if( function_exists( 'mail' ) )
        {
            $message = $mail->body();
            $sys = eZSys::instance();
            $excludeHeaders = $ini->variable( 'MailSettings', 'ExcludeHeaders' );
            $excludeHeaders[] = 'Subject';
            // If not Windows PHP mail() implementation, we can not specify a To: header in the $additional_headers parameter,
            // because then there will be 2 To: headers in the resulting e-mail.
            // However, we can use "undisclosed-recipients:;" in $to.
            if ( $sys->osType() != 'win32' )
            {
                $excludeHeaders[] = 'To';
                $insertUndisclosedRecipient = $ini->variable( 'MailSettings', 'SendmailInsertUndisclosedRecipient' );
                $recipientText = $insertUndisclosedRecipient == 'disabled' ? '' : 'undisclosed-recipients:;';
                $receiverEmailText = count( $mail->ReceiverElements ) > 0 ? $mail->receiverEmailText() : $recipientText;
            }
            // If Windows PHP mail() implementation, we can specify a To: header in the $additional_headers parameter,
            // it will be used as the only To: header.
            // We can not use "undisclosed-recipients:;" in $to, it will result in a SMTP server response: 501 5.1.3 Bad recipient address syntax
            else
            {
                $receiverEmailText = $mail->receiverEmailText();
            }

            // If in debug mode, send to debug email address and nothing else
            if ( $ini->variable( 'MailSettings', 'DebugSending' ) == 'enabled' )
            {
                $receiverEmailText = $ini->variable( 'MailSettings', 'DebugReceiverEmail' );
                $excludeHeaders[] = 'To';
                $excludeHeaders[] = 'Cc';
                $excludeHeaders[] = 'Bcc';
            }

            $extraHeaders = $mail->headerText( array( 'exclude-headers' => $excludeHeaders ) );

            $returnedValue = mail( $receiverEmailText, $mail->subject(), $message, $extraHeaders, $sendmailOptions );
            if ( $returnedValue === false )
            {
                eZDebug::writeError( 'An error occurred while sending e-mail. Check the Sendmail error message for further information (usually in /var/log/messages)',
                                     __METHOD__ );
            }

            return $returnedValue;
        }
        else
        {
            eZDebug::writeWarning( "Unable to send mail: 'mail' function is not compiled into PHP.", __METHOD__ );
        }

        return false;
    }
}

?>

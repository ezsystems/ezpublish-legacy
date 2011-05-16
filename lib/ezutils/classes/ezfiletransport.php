<?php
/**
 * File containing the eZFileTransport class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZFileTransport ezfiletransport.php
  \brief Sends the email message to a file.

*/

class eZFileTransport extends eZMailTransport
{
    /*!
     Constructor
    */
    function eZFileTransport()
    {
    }

    function sendMail( eZMail $mail )
    {
        $ini = eZINI::instance();
        $sendmailOptions = '';
        $emailFrom = $mail->sender();
        $emailSender = $emailFrom['email'];
        if ( !$emailSender || count( $emailSender) <= 0 )
            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );
        if ( !eZMail::validate( $emailSender ) )
            $emailSender = false;

        $isSafeMode = ini_get( 'safe_mode' );
        if ( $isSafeMode and
             $emailSender and
             $mail->sender() == false )
            $mail->setSenderText( $emailSender );

        $filename = time() . '-' . mt_rand() . '.mail';

        $data = preg_replace('/(\r\n|\r|\n)/', "\r\n", $mail->headerText() . "\n" . $mail->body() );
        $returnedValue = eZFile::create( $filename, 'var/log/mail', $data );
        if ( $returnedValue === false )
        {
            eZDebug::writeError( 'An error occurred writing the e-mail file in var/log/mail', __METHOD__ );
        }

        return $returnedValue;
    }
}

?>

<?php
/**
 * File containing the eZMailTransport class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZMailTransport ezmailtransport.php
  \brief Interface for mail transport handling

*/

class eZMailTransport
{
    /*!
     Tries to send the contents of the email object \a $mail and
     returns \c true if succesful.
    */
    function sendMail( eZMail $mail )
    {
        return false;
    }

    /*!
     \static
     Sends the contents of the email object \a $mail using the default transport.
    */
    static function send( eZMail $mail )
    {
        $ini = eZINI::instance();

        $transportType = trim( $ini->variable( 'MailSettings', 'Transport' ) );

        $optionArray = array( 'iniFile'      => 'site.ini',
                              'iniSection'   => 'MailSettings',
                              'iniVariable'  => 'TransportAlias',
                              'handlerIndex' => strtolower( $transportType ) );
        $options = new ezpExtensionOptions( $optionArray );
        $transportClass = eZExtension::getHandlerClass( $options );

        if ( !is_object( $transportClass ) )
        {
            eZDebug::writeError( "No class available for mail transport type '$transportType', cannot send mail", __METHOD__ );
        }
        return $transportClass->sendMail( $mail );
    }
}

?>

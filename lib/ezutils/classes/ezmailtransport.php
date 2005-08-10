<?php
//
// Definition of eZMailTransport class
//
// Created on: <10-Dec-2002 14:31:35 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezmailtransport.php
*/

/*!
  \class eZMailTransport ezmailtransport.php
  \brief Interface for mail transport handling

*/

class eZMailTransport
{
    /*!
     Constructor
    */
    function eZMailTransport()
    {
    }

    /*!
     \virtual
     Tries to send the contents of the email object \a $mail and
     returns \c true if succesful.
    */
    function sendMail( &$mail )
    {
        if ( get_class( $mail ) != 'ezmail' )
        {
            eZDebug::writeError( 'Can only handle objects of type eZMail.', 'eZMailTransport::sendMail' );
            return false;
        }
        return false;
    }

    /*!
     \static
     Sends the contents of the email object \a $mail using the default transport.
    */
    function send( &$mail )
    {
        if ( get_class( $mail ) != 'ezmail' )
        {
            eZDebug::writeError( 'Can only handle objects of type eZMail.', 'eZMailTransport::send' );
            return false;
        }
        $ini =& eZINI::instance();

        $transportType = trim( $ini->variable( 'MailSettings', 'Transport' ) );
        $transportObject =& $GLOBALS['eZMailTransportHandler_' . strtolower( $transportType )];
        if ( !isset( $transportObject ) or
             !is_object( $transportObject ) )
        {
            $transportClassFile = 'ez' . strtolower( $transportType ) . 'transport.php';
            $transportClassPath = 'lib/ezutils/classes/' . $transportClassFile;
            $transportClass = 'eZ' . $transportType . 'Transport';
            if ( !file_exists( $transportClassPath ) )
            {
                eZDebug::writeError( "Unknown mail transport type '$transportType', cannot send mail",
                                     'eZMailTransport::send' );
                return false;
            }
            include_once( $transportClassPath );
            if ( !class_exists( $transportClass ) )
            {
                eZDebug::writeError( "No class available for mail transport type '$transportType', cannot send mail",
                                     'eZMailTransport::send' );
                return false;
            }
            $transportObject = new $transportClass();
        }
        return $transportObject->sendMail( $mail );
    }
}

?>

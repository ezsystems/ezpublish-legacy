<?php
//
// Created on: <30-Jul-2003 14:46:19 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

include_once( "kernel/common/template.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezmail.php" );
include_once( 'lib/ezutils/classes/ezmailtransport.php' );

$Module =& $Params['Module'];

$ini =& eZINI::instance();
$isEnabled = $ini->variable( 'FormProcessSettings', 'Module' ) == 'enabled';
if ( !$isEnabled )
{
    return $Module->handleError( EZ_ERROR_KERNEL_MODULE_DISABLED, 'kernel',
                                 array( 'check' => array( 'view_checked' => false,
                                                          'module' => 'form' ) ) );
}

$tpl =& templateInit();

// Parse HTTP POST variables and generate Mail message
$formProcessed = false;

$http =& eZHTTPTool::instance();
$postVariables =& $http->attribute( 'post' );

if ( count( $postVariables ) > 0 )
{
    $mail = new eZMail();
    $receiver = false;
    $mailBody = "";
    $mailSubject = "eZ publish form data";
    $emailSender = "";
    $redirectURL = false;
    foreach ( array_keys( $postVariables ) as $key )
    {
        $value = $postVariables[$key];

        // Check for special keys
        // Note: the duplicate checks are because of eZ publish 2.2 compatibility
        switch ( $key )
        {
            case "redirectTo":
            case "RedirectTo":
            {
                $redirectURL = trim( $value );
            }break;

            case "mailSendTo":
            case "MailSendTo":
            {
                $receiver = trim( $value );
            }
            break;

            case "mailSendFrom":
            case "MailSendFrom":
            {
                $emailSender = trim( $value );
            }
            break;

            case "mailSubject":
            case "MailSubject":
            {
                $mailSubject = trim( $value );
            }
            break;

            default:
            {
                $mailBody .= "$key:\n$value\n\n";
            }break;
        }
    }

    if ( !$mail->validate( $receiver ) )
    {
        // receiver does not contain a valid email address, get the default one
        $receiver = $ini->variable( "InformationCollectionSettings", "EmailReceiver" );
        if ( !$receiver )
            $receiver = $ini->variable( "MailSettings", "AdminEmail" );
    }

    if ( !$mail->validate( $emailSender ) )
    {
        // receiver does not contain a valid email address, get the default one
        $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( "MailSettings", "AdminEmail" );
    }

    $mail->setReceiver( $receiver );
    $mail->setSender( $emailSender );
    $mail->setSubject( $mailSubject );
    $mail->setBody( $mailBody );
    $mailResult = eZMailTransport::send( $mail );

    $formProcessed = true;

    if ( $redirectURL != false )
    {
        $Module->redirectTo( $redirectURL );
    }
}

$tpl->setVariable( 'form_processed', $formProcessed );
$Result = array();
$Result['content'] =& $tpl->fetch( "design:form/process.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/form', 'Form processing' ),
                                'url' => false ) );
?>

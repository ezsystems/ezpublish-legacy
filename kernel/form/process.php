<?php
//
// Created on: <30-Jul-2003 14:46:19 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

require_once( "kernel/common/template.php" );
//include_once( "lib/ezutils/classes/ezhttptool.php" );
//include_once( "lib/ezutils/classes/ezmail.php" );
//include_once( 'lib/ezutils/classes/ezmailtransport.php' );

$Module = $Params['Module'];

$ini = eZINI::instance();
$isEnabled = $ini->variable( 'FormProcessSettings', 'Module' ) == 'enabled';
if ( !$isEnabled )
{
    return $Module->handleError( eZError::KERNEL_MODULE_DISABLED, 'kernel',
                                 array( 'check' => array( 'view_checked' => false,
                                                          'module' => 'form' ) ) );
}

$tpl = templateInit();

// Parse HTTP POST variables and generate Mail message
$formProcessed = false;

$http = eZHTTPTool::instance();
$postVariables = $http->attribute( 'post' );

if ( count( $postVariables ) > 0 )
{
    $mail = new eZMail();
    $receiver = false;
    $mailBody = "";
    $mailSubject = "eZ Publish form data";
    $emailSender = "";
    $redirectURL = false;
    foreach ( array_keys( $postVariables ) as $key )
    {
        $value = $postVariables[$key];

        // Check for special keys
        // Note: the duplicate checks are because of eZ Publish 2.2 compatibility
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
$Result['content'] = $tpl->fetch( "design:form/process.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/form', 'Form processing' ),
                                'url' => false ) );
?>

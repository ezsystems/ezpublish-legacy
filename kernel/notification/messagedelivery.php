<?php
//
// Created on:<2-Oct-2001 12:40:06 wy>
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "kernel/notification/ezmessage.php" );
include_once( "lib/ezutils/classes/ezmail.php" );
include_once( 'lib/ezutils/classes/ezmailtransport.php' );

$current = getdate();
$weekday = $current['wday'];
$hour = $current['hours'];

$i18nINI = eZINI::instance( "i18n.ini" );
$charset = $i18nINI->variable( "CharacterSettings", "Charset" );
$codec =& eZTextCodec::instance( $charset, "ISO-8859-1" );  // This should be more general

$ini =& eZINI::instance();

$emailMessages = eZMessage::fetchEmailMessages();
$freeMessages = array();
$messageBulks = array();
foreach ( $emailMessages as $emailMessage )
{
    $valueText = $emailMessage->attribute( 'title' );
    $values = explode( ',', $valueText );
    if ( count( $values ) < 3 )
    {
        $emailMessage->remove();
        continue;
    }
    $userID = $values[0];
    $ruleID = $values[1];
    $contentObjectID = $values[2];

    $send = false;
    $sendNow = false;
    $sendWeekday = $emailMessage->attribute( "send_weekday" );
    $sendHour =  $emailMessage->attribute( "send_time" );
    $sendWeekdayArray = explode( ',', $sendWeekday );
    if ( $sendWeekday == -1 )
    {
        $send = true;
        $sendNow = true;
    }
    else if ( ( $sendWeekday == $weekday ) and ( $sendHour == -1 or $sendHour <= $hour ) )
        $send = true;
//     else if ( $sendWeekday == ( $weekday - 1 ) )
//         $send = true;
//     else if ( $sendWeekday == 7 and $weekday == 1 )
//         $send = true;
//     else
//         $send = false;

    if ( $send and $sendNow )
    {
        $freeMessages[] = array( 'user_id' => $userID,
                                 'rule_id' => $ruleID,
                                 'message' => array( 'object_id' => $contentObjectID,
                                                     'message' => $emailMessage ) );
        $emailMessage->setAttribute( "is_sent", true );
        $emailMessage->store();
        continue;
    }
    else if ( $send )
    {
        if ( !isset( $messageBulks["$userID-$ruleID"] ) )
        {
            $messageBulks["$userID-$ruleID"] = array( 'user_id' => $userID,
                                                      'rule_id' => $ruleID,
                                                      'email' => $emailMessage->attribute( 'destination_address' ),
                                                      'messages' => array() );
        }
        $messageBulks["$userID-$ruleID"]['messages'][] = array( 'object_id' => $contentObjectID,
                                                                'message' => $emailMessage );
        $emailMessage->setAttribute( "is_sent", true );
        $emailMessage->store();
    }
}

function sendMessages( $destinationAddress, $userID, $ruleID, $messageList )
{
//     $destinationAddress = $emailMessage->attribute( "destination_address" );
//     $title = $emailMessage->attribute( "title" );
//     $body = $emailMessage->attribute( "body" );
//     $title = $codec->convertString( $title );
//     $body = $codec->convertString( $body );

    include_once( 'kernel/common/template.php' );
    $tpl =& templateInit();

    $tpl->setVariable( 'user_id', $userID );
    $tpl->setVariable( 'rule_id', $ruleID );
    $tpl->setVariable( 'message_list', $messageList );

    $body = $tpl->fetch( 'design:notification/email.tpl' );
    $subject = $tpl->variable( 'subject' );

    $ini =& eZINI::instance();
    $email = new eZMail();
    $email->setReceiverText( $destinationAddress );
    $email->setSenderText( $ini->variable( 'MailSettings', 'AdminEmail' ) );
    $email->setSubject( $subject );
    $email->setBody( $body );

//     print( 'headers:<pre>' . htmlspecialchars( $email->headerText() ) . "</pre>" );
//     print( 'body:<pre>' . htmlspecialchars( $email->body() ) . "</pre>" );

    $mailResult = eZMailTransport::send( $email );
}

foreach ( $freeMessages as $freeMessage )
{
    sendMessages( $freeMessage['message']['message']->attribute( 'destination_address' ),
                  $freeMessage['user_id'], $freeMessage['rule_id'],
                  array( $freeMessage['message'] ) );
}

foreach ( $messageBulks as $messageBulk )
{
    sendMessages( $messageBulk['email'],
                  $messageBulk['user_id'], $messageBulk['rule_id'],
                  $messageBulk['messages'] );
}

?>

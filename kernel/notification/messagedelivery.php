<?php
//
// Created on:<2-Oct-2001 12:40:06 wy>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

$current = getdate();
$weekday = $current['wday'];
$hour = $current['hours'];

$emailMessages = eZMessage::fetchEmailMessages();
 eZDebug::writeError("fsfsfdf1");
foreach ( $emailMessages as $emailMessage )
{
     eZDebug::writeError("fsfsfdf2");
    $send = false;
    $sendWeekday = $emailMessage->attribute( "send_weekday" );
    $sendHour =  $emailMessage->attribute( "send_time" );
    if ( $sendWeekday == -1 )
        $send = true;
    elseif( ( $sendWeekday == $weekday ) and ( $sendHour == -1 or $sendHour <= $hour ) )
        $send = true;
    elseif( $sendWeekday == ( $weekday-1 ) )
        $send = true;
    elseif( $sendWeekday == 7 and $weekday == 1 )
        $send = true;
    else
        $send = false;
    if( $send == true )
    {
        eZDebug::writeError("fsfsfdf");
        $destinationAddress = $emailMessage->attribute( "destination_address" );
        $title = $emailMessage->attribute( "title" );
        $body = $emailMessage->attribute( "body" );
        $emailMessage->setAttribute( "is_sent", true );
        $emailMessage->store();
        $email = new eZMail();
        $email->setReceiver( "wy@ez.no" );
        $email->setSender( "admin@ez.no" );
        $email->setFromName( "Administrator" );
        $email->setSubject( $title );
        $email->setBody( $body );
        $email->send();
    }
}
?>

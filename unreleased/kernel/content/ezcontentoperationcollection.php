<?php
//
// Definition of eZContentOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
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

/*! \file ezcontentoperationcollection.php
*/

/*!
  \class eZContentOperationCollection ezcontentoperationcollection.php
  \brief The class eZContentOperationCollection does

*/

class eZContentOperationCollection
{
    /*!
     Constructor
    */
    function eZContentOperationCollection()
    {
    }

    function checkNotifications( $objectID, $versionNum )
    {
        include_once( "kernel/notification/eznotificationrule.php" );
        include_once( "kernel/notification/eznotificationruletype.php" );
        include_once( "kernel/notification/eznotificationuserlink.php" );
        include_once( "kernel/notification/ezmessage.php" );
        $object =& eZContentObject::fetch( $objectID );
        $allrules =& eZNotificationRule::fetchList( null );
//         print( "running notifications<br/>" );
        foreach ( $allrules as $rule )
        {
            $ruleID = $rule->attribute( "id" );
            $ruleClass = $rule->attribute("rule_type");

//             print( "rule class=" . get_class( $ruleClass ) . ", rule id=$ruleID" . "<br/>" );

            if ( is_object( $ruleClass ) && $ruleClass->match( $object, $rule ) )
            {
                $users =& eZNotificationUserLink::fetchUserList( $ruleID );
                foreach ( $users as $user )
                {
                    $userID = $user->attribute( "user_id" );
                    $sendMethod = $user->attribute( "send_method" );
                    $sendWeekday = $user->attribute( "send_weekday" );
                    $sendTime = $user->attribute( "send_time" );
                    $destinationAddress = $user->attribute( "destination_address" );

                    // get user domain
                    $ini = eZINI::instance( "site.ini" );
                    $domain = $ini->variable( "SiteSettings", "SiteURL" );

// BEGIN HiO specific code
//                     if ( get_class( $ruleClass ) == "ezhiorule" )
//                     {
//                         $userObject = eZUser::fetch( $userID );
//                         ob_start();
//                         print_r( $userObject );
//                         $userString = ob_get_contents();
//                         ob_end_clean();
//                         $userHash = md5( "$ruleID\n$userID\n$userString" );

//                         switch ( $sendWeekday )
//                         {
//                             case 1:
//                                 $weekday = "Mandag";
//                             break;
//                             case 2:
//                                 $weekday = "Tirsdag";
//                             break;
//                             case 3:
//                                 $weekday = "Onsdag";
//                             break;
//                             case 4:
//                                 $weekday = "Torsdag";
//                             break;
//                             case 5:
//                                 $weekday = "Fredag";
//                             break;
//                             case 6:
//                                 $weekday = "Lørdag";
//                             break;
//                             case 7:
//                                 $weekday = "Søndag";
//                             break;
//                             default:
//                                 $weekday = "Med en gang";
//                             break;
//                         }

//                         if ( $sendTime == -1 )
//                             $time = "Hver time";
//                         else
//                             $time = $sendTime . ":00";

//                         $charset = $ini->variable( "CharacterSettings", "Charset" );
//                         $codec =& eZTextCodec::instance( "ISO-8859-1", $charset );

//                         $title = "Oppdatering på " . $domain;
//                         $codec->convertString( $title );

//                         $body1 = "Oppdatering på " . $domain;
//                         $body1 .= "\n\nDenne siden er oppdatert:\n";
//                         $codec->convertString( $body1 );
//                         $body1 .= $object->attribute( "name" );
//                         $body1 .= "\nhttp://" . $domain . "/content/view/full/";
//                         $body1 .= $object->attribute( "main_node_id" );

//                         $body2 = "\n\nDette er en automatisk generert melding. Den ble sendt til deg";
//                         $body2 .= "\nfordi du har startet et abonnement med følgende regler:";
//                         $body2 .= "\nSøkeord: ";
//                         $codec->convertString( $body2 );
//                         $body2 .= $rule->attribute( "keyword" );

//                         $body3 = "\nUkedag: " . $weekday;
//                         $body3 .= "\nTime: " . $time;
//                         $body3 .= "\n\nHvis du vil avslutte abonnementet, følg denne lenken:\n";
//                         $body3 .= "http://" . $domain . "/notification/remove/" . $ruleID;
//                         $body3 .= "/" . $userID . "/" . $userHash;
//                         $body3 .= "\n\n\nAdministrator";
//                         $codec->convertString( $body3 );

//                         $body = $body1 . $body2 . $body3;
//                     }
//                     else
//                     {
// // END HiO specific code
//                         $title = "New publishing notification";
//                         $body = $object->attribute( "name" );
//                         $body .= "\nhttp://" .  $domain . "/content/view/full/";
//                         $body .=  $object->attribute( "main_node_id" );
//                         $body .= "\n\n\nAdministrator";
// // BEGIN HiO specific code
//                     }
// // END HiO specific code

                    $valueText = implode( ',', array( $userID, $ruleID, $object->attribute( 'id' ) ) );
//                     $message =& eZMessage::create( $sendMethod, $sendWeekday, $sendTime,
//                                                    $destinationAddress, $title, $body );
                    $message =& eZMessage::create( $sendMethod, $sendWeekday, $sendTime,
                                                   $destinationAddress, $valueText, false );
                    $message->store();
                    $msgid = $message->attribute( "id" );
                    $mymsg = eZMessage::fetch( $msgid );
                }
            }
        }
    }

}

?>

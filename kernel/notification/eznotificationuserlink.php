<?php
//
// Definition of eZUserNotificationLink class
//
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

//!! eZNotification
//! The class eZUserNotificationLink
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );

class eZNotificationUserLink extends eZPersistentObject
{
    function eZNotificationUserLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "rule_id" => "RuleID",
                                         "user_id" => "UserID",
                                         "send_method" => "SendMethod",
                                         "send_weekday" => "SendWeekday",
                                         "send_time" => "SendTime",
                                         "destination_address" => "DestinationAddress"),
                      "keys" => array( "rule_id", "user_id" ),
                      "class_name" => "eZNotificationUserLink",
                      "name" => "eznotification_user_link" );
    }

    function &create( $ruleID, $userID, $sendMethod, $sendWeekday, $sendTime, $destinationAddress )
    {
        $row = array( "rule_id" => $ruleID,
                      "user_id" => $userID,
                      "send_method" => $sendMethod,
                      "send_weekday" => $sendWeekday,
                      "send_time" => $sendTime,
                      "destination_address" => $destinationAddress );
        return new eZNotificationUserLink( $row );
    }

    /*!
     Remove the user link with \a $ruleID and \a $userID
    */
    function &remove( $ruleID, $userID )
    {
        eZPersistentObject::removeObject( eZNotificationUserLink::definition(),
                                          array( "rule_id" => $ruleID,
                                                 "user_id" => $userID ) );
    }

    /*!
     Remove all user links which has the same \a $ruleID
    */
    function &removeRuleList( $ruleID )
    {
        eZPersistentObject::removeObject( eZNotificationUserLink::definition(),
                                          array( "rule_id" => $ruleID ) );
    }

    /*!
     Remove all user links of the user with \a $userID
    */
    function &removeUserList( $userID )
    {
        eZPersistentObject::removeObject( eZNotificationUserLink::definition(),
                                          array( "user_id" => $userID ) );
    }

    /*!
     Fetch user link with \a $ruleID and \a $userID
    */
    function &fetch( $ruleID, $userID, $asObject = true  )
    {
        return eZPersistentObject::fetchObject( eZNotificationUserLink::definition(),
                                                null,
                                                array( "rule_id" => $ruleID,
                                                       "user_id" => $userID ),
                                                $asObject );
    }

    /*!
     Fetch user links of a user with \a $userID
    */
    function &fetchRuleList( $userID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationUserLink::definition(),
                                                    null,
                                                    array( "user_id" => $userID ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    /*!
     Fetch all user links with \a $ruleID
    */
    function &fetchUserList( $ruleID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationUserLink::definition(),
                                                    null,
                                                    array( "rule_id" => $ruleID ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    /*!
     Fetch all user links
    */
    function &fetchAll( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationUserLink::definition(),
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    $asObject);
    }

    /// \privatesection
    var $RuleID;
    var $UserID;
    var $SendMethod;
    var $SendTime;
    var $SendWeekday;
    var $DestinationAddress;
}

?>

<?php
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

// BEGIN HiO specific code

// Purpose: Display a success or failure message when hio-users try to
// unsubscribe from a notification message.

include_once( "kernel/notification/eznotificationruletype.php" );
include_once( "kernel/notification/eznotificationrule.php" );
include_once( "kernel/notification/eznotificationuserlink.php" );
include_once( "kernel/common/template.php" );

$Module =& $Params["Module"];
if ( isset( $Params["RuleID"] ) )
    $RuleID = $Params["RuleID"];
if ( isset( $Params["UserID"] ) )
    $UserID = $Params["UserID"];
if ( isset( $Params["Hash"] ) )
    $Hash = $Params["Hash"];

$whatHappened = "nothing";
if ( isset( $RuleID ) && isset( $UserID ) && isset( $Hash ) )
{
    $userObject = eZUser::fetch( $UserID );
    ob_start();
    print_r( $userObject );
    $userString = ob_get_contents();
    ob_end_clean();
    $userHash = md5( "$RuleID\n$UserID\n$userString" );

    if ( $userHash == $Hash )
    {
        eZNotificationUserLink::remove( $RuleID, $UserID );
        $users =& eZNotificationUserLink::fetchUserList( $RuleID );
        if ( count( $users ) == 0 )
            eZNotificationRule::remove( $RuleID );
        $whatHappened = "removed";
    }
}

$Module->setTitle( "Remove rule " );

$tpl =& templateInit();

$tpl->setVariable( "what_happened", $whatHappened );
$tpl->setVariable( "rule_id", $RuleID );
$tpl->setVariable( "user_id", $UserID );
$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:notification/remove.tpl" );
$Result['path'] = array( array( 'url' => '/notification/remove/',
                                'text' => 'Notification rule removed' ) );
// END HiO specific code

?>

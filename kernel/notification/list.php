<?php
//
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

include_once( "kernel/notification/eznotificationruletype.php" );
include_once( "kernel/notification/eznotificationrule.php" );
include_once( "kernel/notification/eznotificationuserlink.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/common/template.php" );

$Module =& $Params["Module"];

$user =& eZUser::currentUser();
$UserID = $user->attribute( "contentobject_id" );

$http =& eZHttpTool::instance();
if ( $http->hasPostVariable( "NewRuleButton" ) )
{
    if ( $http->hasPostVariable( "notification_rule_type" ) )
    {
        $ruleType = $http->postVariable( "notification_rule_type" );
    }
    $Module->redirectTo( $Module->functionURI( "edit" ) . '/' . $ruleType  );
    return;
}

if ( $http->hasPostVariable( "DeleteRuleButton" ) )
{
    if ( $http->hasPostVariable( "Rule_id_checked") )
    {
        $delete_array = $http->postVariable( "Rule_id_checked" );
        for ( $i=0;$i<count( $delete_array );$i++ )
        {
            eZNotificationUserLink::remove( $delete_array[$i], $UserID );
            $users =& eZNotificationUserLink::fetchUserList( $delete_array[$i] );
            if ( count( $users ) == 0 )
                eZNotificationRule::remove( $delete_array[$i] );
        }
    }
}

if ( $http->hasPostVariable( "SendButton" ) )
{
   $Module->redirectTo( $Module->functionURI( "send" ) );
}

include_once( "kernel/notification/eznotificationrule.php" );
$ruleTypes =& eZNotificationRuleType::registeredRules();

$Module->setTitle( "Notification rule list " );

$tpl =& templateInit();

$list = array();

$userRules =& eZNotificationUserLink::fetchRuleList( $UserID );

foreach ( $userRules as $userRule )
{
    $rule_id = $userRule->attribute( "rule_id");
    $rule = & eZNotificationRule::fetch( $rule_id );
    $list[] = $rule;
}

$tpl->setVariable( "rule_list", $list );
$tpl->setVariable( "rule_type", $ruleTypes );
$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:notification/list.tpl" );
$Result['path'] = array( array( 'url' => '/notification/list/',
                                'text' => ezi18n( 'kernel/content', 'Notification rule list' ) ) );

?>

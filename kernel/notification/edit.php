<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
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
include_once( "kernel/notification/eznotificationrule.php" );
include_once( "kernel/notification/eznotificationuserlink.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

$user =& eZUser::currentUser();
$user_id = $user->attribute( "contentobject_id" );
$http =& eZHTTPTool::instance();
$Module =& $Params["Module"];
$RuleID = null;
$ClassName = null;
$ClassAttributeID = null;
$Path = null;
$has_stored = false;
$has_constraint = false;
$rule_exist = false;
$rule = array();
$userlink_list = array();
if ( isset( $Params["RuleID"] ) )
    $RuleID = $Params["RuleID"];
if ( isset( $Params["RuleType"] ) )
    $RuleType = $Params["RuleType"];

if ( is_numeric( $RuleID ) )
{
    $has_stored = true;
    $rule =& eZNotificationRule::fetch( $RuleID );
}
else
{
    $rule = array( "id" => null,
                   "contentclass_name" => "",
                   "path" => "",
                   "keyword" => "",
                   "has_constraint" => 0 );
}

$class_list =& eZContentClass::fetchList();

$userlink_list =& eZNotificationUserLink::fetch( $RuleID, $user_id );

if ( $http->hasPostVariable( "DiscardRuleButton" ) )
{
    $Module->redirectTo( $Module->functionURI( "list" ) );
    return;
}

if ( $http->hasPostVariable( "StoreRuleButton" ) )
{
    $idChanged = false;
    $email = $user->attribute( "email" );
    $smsnr = $user->attribute( "smsnr" );
    if ( $http->hasPostVariable( "contentClassName" ) )
    {
        $contentClassName = $http->postVariable( "contentClassName" );
    }
    if ( $http->hasPostVariable( "path" ) )
    {
        $path = $http->postVariable( "path" );
    }
    if ( $http->hasPostVariable( "keyword" ) )
    {
        $keyword = $http->postVariable( "keyword" );
    }
    $sendMethod = $http->postVariable( "sendMethod" );
    $sendTime_week = $http->postVariable( "sendTime_week" );
    $sendTime_hour = $http->postVariable( "sendTime_hour" );
    $condition = array( "contentclass_name" => $contentClassName,
                        "path" => $path,
                        "keyword" => $keyword,
                        "has_constraint" => '0' );

    $existRule =& eZNotificationRule::fetchOne( $condition );
    if ( $existRule != null )
    {
        $RuleID = $existRule->attribute( "id" );
        $rule_exist = true;
    }

    if ( $http->hasPostVariable( "CurrentRuleID" ) )
    {
        $currentID = $http->postVariable( "CurrentRuleID" );
        if ( $currentID != $RuleID )
            $idChanged = true;
    }

    if ( !$rule_exist )
    {
          $newRule =& eZNotificationRule::create( $contentClassName, $RuleType, $path, $keyword, $has_constraint );
          $newRule->store();
          $RuleID = $newRule->attribute( "id" );
    }

    if ( $http->hasPostVariable( "CurrentRuleID" ) )
    {
        $currentID = $http->postVariable( "CurrentRuleID" );
        if ( $currentID != $RuleID and $currentID != "" )
            $idChanged = true;
        if ( $idChanged and !$rule_exist )
        {
            eZNotificationUserLink::remove( $currentID, $user_id );
            $users =& eZNotificationUserLink::fetchUserList( $currentID );
            if ( count( $users ) == 0 )
                eZNotificationRule::remove( $currentID );

            if ( $sendMethod == "email" )
                $userLink =& eZNotificationUserLink::create( $RuleID, $user_id, $sendMethod, $sendTime_week, $sendTime_hour, $email );
            else if ( $sendMethod == "sms" )
                $userLink =& eZNotificationUserLink::create( $RuleID, $user_id, $sendMethod, $sendTime_week, $sendTime_hour, $smsnr );
            else if ( $sendMethod == "internal message" )
                $userLink =& eZNotificationUserLink::create( $RuleID, $user_id, $sendMethod, $sendTime_week, $sendTime_hour, $user_id );
            $userLink->store();
        }
    }

    /*  if ( $has_stored and  $keyword !== null )
    {
        $rule->setAttribute( "keyword", $keyword );
        $rule->setAttribute( "path", $path );
        $rule->store();
    }*/

    if ( $userlink_list == null )
    {
        if ( $sendMethod == "email" )
            $userLink =& eZNotificationUserLink::create( $RuleID, $user_id, $sendMethod, $sendTime_week, $sendTime_hour, $email );
        else if ( $sendMethod == "sms" )
            $userLink =& eZNotificationUserLink::create( $RuleID, $user_id, $sendMethod, $sendTime_week, $sendTime_hour, $smsnr );
        else if ( $sendMethod == "internal message" )
            $userLink =& eZNotificationUserLink::create( $RuleID, $user_id, $sendMethod, $sendTime_week, $sendTime_hour, $user_id );
        $userLink->store();
    }

    if ( $userlink_list != null and !$idChanged )
    {
        $userlink_list->setAttribute( "send_method", $sendMethod );
        $userlink_list->setAttribute( "send_weekday", $sendTime_week );
        $userlink_list->setAttribute( "send_time", $sendTime_hour );
        if ( $sendMethod == "email" )
            $userlink_list->setAttribute( "destination_address", $email );
        if ( $sendMethod == "sms" )
            $userlink_list->setAttribute( "destination_address", $smsnr );
        if ( $sendMethod == "internal message" )
            $userlink_list->setAttribute( "destination_address", $user_id );
        $userlink_list->store();
    }
    $Module->redirectTo( $Module->functionURI( "list" ) );
}

$Module->setTitle( "Edit rule " );
// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "rule_id", $RuleID );
$tpl->setVariable( "rule_type", $RuleType );
$tpl->setVariable( "rule_list", $rule );
$tpl->setVariable( "class_list", $class_list );
$tpl->setVariable( "userlink_list", $userlink_list );
$tpl->setVariable( "ClassAttributeID", $ClassAttributeID );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:notification/edit.tpl" );
$Result['path'] = array( array( 'url' => '/notification/edit/',
                                'text' => 'Notification edit' ) );

?>

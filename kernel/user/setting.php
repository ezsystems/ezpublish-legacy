<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

//include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
//include_once( "lib/ezutils/classes/ezhttptool.php" );
//include_once( "kernel/classes/datatypes/ezuser/ezusersetting.php" );

$Module = $Params['Module'];
if ( isset( $Params["UserID"] ) )
    $UserID = $Params["UserID"];

$http = eZHTTPTool::instance();

$user = eZUser::fetch( $UserID );
if ( !$user )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
$userObject = $user->attribute( 'contentobject' );
if ( !$userObject )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
$userSetting = eZUserSetting::fetch( $UserID );

if ( $http->hasPostVariable( "UpdateSettingButton" ) )
{
    $isEnabled = 0;
    if ( $http->hasPostVariable( "max_login" ) )
    {
        $maxLogin = $http->postVariable( "max_login" );
        $userSetting->setAttribute( "max_login", $maxLogin );
    }

    if ( $http->hasPostVariable( "is_enabled" ) )
    {
        $isEnabled = true;
    }

    if ( $userSetting->attribute( 'is_enabled' ) != $isEnabled )
    {
        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearContentCacheIfNeeded( $UserID );
        eZContentCacheManager::generateObjectViewCache( $UserID );
    }

    $userSetting->setAttribute( "is_enabled", $isEnabled );
    $userSetting->store();
    if ( !$isEnabled )
    {
        eZUser::removeSessionData( $UserID );
    }
    $Module->redirectTo( '/content/view/full/' . $userObject->attribute( 'main_node_id' ) );
    return;
}

if ( $http->hasPostVariable( "CancelSettingButton" ) )
{
    $Module->redirectTo( '/content/view/full/' . $userObject->attribute( 'main_node_id' ) );
    return;
}

if ( $http->hasPostVariable( "ResetFailedLoginButton" ) )
{
    // Reset number of failed login attempts
    eZUser::setFailedLoginAttempts( $UserID, 0, true );
}

$failedLoginAttempts = $user->failedLoginAttempts();
$maxFailedLoginAttempts = eZUser::maxNumberOfFailedLogin();

$Module->setTitle( "Edit user settings" );
// Template handling
require_once( "kernel/common/template.php" );
$tpl = templateInit();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userID", $UserID );
$tpl->setVariable( "user", $user );
$tpl->setVariable( "userSetting", $userSetting );
$tpl->setVariable( "failed_login_attempts", $failedLoginAttempts );
$tpl->setVariable( "max_failed_login_attempts", $maxFailedLoginAttempts );

$Result = array();
$Result['content'] = $tpl->fetch( "design:user/setting.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/user', 'User' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/user', 'Setting' ),
                                'url' => false ) );

?>

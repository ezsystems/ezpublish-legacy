<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
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

include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezusersetting.php" );

$Module =& $Params["Module"];
if ( isset( $Params["UserID"] ) )
    $UserID = $Params["UserID"];

$http =& eZHTTPTool::instance();

$user =& eZUser::fetch( $UserID );
if ( !$user )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
$userObject =& $user->attribute( 'contentobject' );
if ( !$userObject )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
if ( !$userObject->canEdit() )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
$userSetting =& eZUserSetting::fetch( $UserID );

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

$Module->setTitle( "Edit user settings" );
// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userID", $UserID );
$tpl->setVariable( "user", $user );
$tpl->setVariable( "userSetting", $userSetting );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:user/setting.tpl" );

?>

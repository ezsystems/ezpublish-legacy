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

$currentUser =& eZUser::currentUser();
$currentUserID = $currentUser->attribute( "contentobject_id" );
$http =& eZHTTPTool::instance();
$Module =& $Params["Module"];
$message = 0;
$oldPasswordNotValid = 0;
$newPasswordNotMatch = 0;
$newPasswordTooShort = 0;

if ( is_numeric( $Params["UserID"] ) )
    $UserID = $Params["UserID"];
else
    $UserID = $currentUserID;

$user =& eZUser::fetch( $UserID );
if ( !$user )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
$currentUser =& eZUser::currentUser();
if ( $currentUser->attribute( 'contentobject_id' ) != $user->attribute( 'contentobject_id' ) or
     !$currentUser->isLoggedIn() )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( $http->hasPostVariable( "OKButton" ) )
{
    if ( $http->hasPostVariable( "oldPassword" ) )
    {
        $oldPassword = $http->postVariable( "oldPassword" );
    }
    if ( $http->hasPostVariable( "newPassword" ) )
    {
        $newPassword = $http->postVariable( "newPassword" );
    }
    if ( $http->hasPostVariable( "confirmPassword" ) )
    {
        $confirmPassword = $http->postVariable( "confirmPassword" );
    }

    $login = $user->attribute( "login" );
    $type = $user->attribute( "password_hash_type" );
    $hash = $user->attribute( "password_hash" );
    $site = $user->site();
    if ( $user->authenticateHash( $login, $oldPassword, $site, $type, $hash ) )
    {
        if (  $newPassword ==  $confirmPassword )
        {
            if ( strlen( $newPassword ) < 3 )
            {
                $newPasswordTooShort = 1;
            }
            else
            {
                $newHash = $user->createHash( $login, $newPassword, $site, $type );
                $user->setAttribute( "password_hash", $newHash );
                $user->store();
            }
            $message = true;
            $newPassword = '';
            $oldPassword = '';
            $confirmPassword = '';

        }
        else
        {
            $newPassword = "";
            $confirmPassword = "";
            $newPasswordNotMatch = 1;
            $message = true;
        }
    }
    else
    {
        $oldPassword = "";
        $oldPasswordNotValid = 1;
        $message = true;
    }
}

if ( $http->hasPostVariable( "CancelButton" ) )
{
    if ( $http->hasPostVariable( "RedirectOnCancel" ) )
    {
        $Module->redirectTo( $http->postVariable( "RedirectOnCancel" ) );
    }
    else if( $http->hasSessionVariable( "LastAccessesURI" ) )
    {
        $Module->redirectTo( $http->sessionVariable( "LastAccessesURI" ) );
    }
    else
    {
        $Module->redirectTo( '/content/view/sitemap/2/' );
    }
    return;
}

$Module->setTitle( "Edit user information" );
// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userID", $UserID );
$tpl->setVariable( "userAccount", $user );
$tpl->setVariable( "oldPassword", $oldPassword );
$tpl->setVariable( "newPassword", $newPassword );
$tpl->setVariable( "confirmPassword", $confirmPassword );
$tpl->setVariable( "oldPasswordNotValid", $oldPasswordNotValid );
$tpl->setVariable( "newPasswordNotMatch", $newPasswordNotMatch );
$tpl->setVariable( "newPasswordTooShort", $newPasswordTooShort );
$tpl->setVariable( "message", $message );

$Result = array();
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/user', 'User' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/user', 'Change password' ),
                                'url' => false ) );
$Result['content'] =& $tpl->fetch( "design:user/password.tpl" );

?>

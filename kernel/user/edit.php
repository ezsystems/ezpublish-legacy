<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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


$currentUser = eZUser::currentUser();
$currentUserID = $currentUser->attribute( "contentobject_id" );
$http = eZHTTPTool::instance();
$Module = $Params['Module'];

if ( isset( $Params["UserID"] ) )
    $UserID = $Params["UserID"];

if ( !$currentUser->isAnonymous() and !isset( $UserID ) )
    $UserID = $currentUserID;

if ( $Module->isCurrentAction( "ChangePassword" ) )
{
    $Module->redirectTo( $Module->functionURI( "password" ) . "/" . $UserID  );
    return;
}

if ( $Module->isCurrentAction( "ChangeSetting" ) )
{
    $Module->redirectTo( $Module->functionURI( "setting" ) . "/" . $UserID );
    return;
}

if ( $Module->isCurrentAction( "Cancel" ) )
{
    $Module->redirectTo( '/content/view/sitemap/5/' );
    return;
}

if ( $Module->isCurrentAction( "Edit" ) )
{
    $selectedVersion = $http->hasPostVariable( 'SelectedVersion' ) ? $http->postVariable( 'SelectedVersion' ) : 'f';
    $editLanguage = $http->hasPostVariable( 'ContentObjectLanguageCode' ) ? $http->postVariable( 'ContentObjectLanguageCode' ) : '';
    $Module->redirectTo( '/content/edit/' . $UserID . '/' . $selectedVersion . '/' . $editLanguage );
    return;
}

$userAccount = eZUser::fetch( $UserID );
if ( !$userAccount )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
$userObject = $userAccount->attribute( 'contentobject' );
if ( !$userObject )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
if ( !$userObject->canEdit( ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

require_once( "kernel/common/template.php" );
$tpl = templateInit();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userID", $UserID );
$tpl->setVariable( "userAccount", $userAccount );

$Result = array();
$Result['content'] = $tpl->fetch( "design:user/edit.tpl" );
$Result['path'] = array( array( 'text' =>  ezi18n( 'kernel/user', 'User profile' ),
                                'url' => false ) );


?>

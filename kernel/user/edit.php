<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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


$Module = $Params['Module'];

if ( isset( $Params['UserID'] ) && is_numeric( $Params['UserID'] ) )
{
    $UserID = $Params['UserID'];
}
else
{
    $currentUser = eZUser::currentUser();
    $UserID      = $currentUser->attribute( 'contentobject_id' );
    if ( $currentUser->isAnonymous() )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}

if ( $Module->isCurrentAction( "ChangePassword" ) )
{
    return $Module->redirectTo( "user/password/" . $UserID  );
}

if ( $Module->isCurrentAction( "ChangeSetting" ) )
{
    return $Module->redirectTo( "user/setting/" . $UserID );
}

if ( $Module->isCurrentAction( "Cancel" ) )
{
    return $Module->redirectTo( '/content/view/sitemap/5/' );
}

$http = eZHTTPTool::instance();

if ( $Module->isCurrentAction( "Edit" ) || ( isset( $UserParameters['action'] ) && $UserParameters['action'] === 'edit' ) )
{
    $selectedVersion = $http->hasPostVariable( 'SelectedVersion' ) ? $http->postVariable( 'SelectedVersion' ) : 'f';
    $editLanguage = $http->hasPostVariable( 'ContentObjectLanguageCode' ) ? $http->postVariable( 'ContentObjectLanguageCode' ) : '';
    return $Module->redirectTo( '/content/edit/' . $UserID . '/' . $selectedVersion . '/' . $editLanguage );
}

$userAccount = eZUser::fetch( $UserID );
if ( !$userAccount )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
    
$userObject = $userAccount->attribute( 'contentobject' );
if ( !$userObject instanceof eZContentObject  )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( !$userObject->canEdit( ) )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}


$tpl = eZTemplate::factory();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userID", $UserID );
$tpl->setVariable( "userAccount", $userAccount );
$tpl->setVariable( 'view_parameters', $UserParameters );
$tpl->setVariable( 'site_access', $GLOBALS['eZCurrentAccess'] );

$Result = array();
$Result['content'] = $tpl->fetch( "design:user/edit.tpl" );
$Result['path'] = array( array( 'text' =>  ezpI18n::translate( 'kernel/user', 'User profile' ),
                                'url' => false ) );


?>

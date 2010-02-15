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
$http = eZHTTPTool::instance();

$hash = trim( $http->hasPostVariable( 'Hash' ) ? $http->postVariable( 'Hash' ) : $Params['Hash'] );
$mainNodeID = (int) $http->hasPostVariable( 'MainNodeID' ) ? $http->postVariable( 'MainNodeID' ) : $Params['MainNodeID'];

// Prepend or append the hash string with a salt, and md5 the resulting hash
// Example: use is login name as salt, and a 'secret password' as hash sent to the user
if ( $http->hasPostVariable( 'HashSaltPrepend' ) )
    $hash =  md5( trim( $http->postVariable( 'HashSaltPrepend' ) ) . $hash );
else if ( $http->hasPostVariable( 'HashSaltAppend' ) )
    $hash =  md5( $hash . trim( $http->postVariable( 'HashSaltAppend' ) ) );


// Check if key exists
$accountActivated = false;
$alreadyActive = false;
$accountKey = $hash ? eZUserAccountKey::fetchByKey( $hash ) : false;

if ( $accountKey )
{
    $accountActivated = true;
    $userID = $accountKey->attribute( 'user_id' );
    
    $userContentObject = eZContentObject::fetch( $userID );
    if ( !$userContentObject instanceof eZContentObject )
    {
        return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
    }

    if ( $userContentObject->attribute('main_node_id') != $mainNodeID )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    // Enable user account
    if ( eZOperationHandler::operationIsAvailable( 'user_activation' ) )
    {
        $operationResult = eZOperationHandler::execute( 'user',
                                                        'activation', array( 'user_id'    => $userID,
                                                                             'user_hash'  => $hash,
                                                                             'is_enabled' => true ) );
    }
    else
    {
        eZUserOperationCollection::activation( $userID, $hash, true );
    }

    // Log in user
    $user = eZUser::fetch( $userID );

    if ( $user === null )
        return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );

    $user->loginCurrent();
}
elseif( $mainNodeID )
{
    $userContentObject = eZContentObject::fetchByNodeID( $mainNodeID );
    if ( $userContentObject instanceof eZContentObject )
    {
        $userSetting = eZUserSetting::fetch( $userContentObject->attribute( 'id' ) );
    
        if ( $userSetting !== null && $userSetting->attribute( 'is_enabled' ) )
        {
            $alreadyActive = true;
        }
    }
}

// Template handling
require_once( 'kernel/common/template.php' );
$tpl = templateInit();

$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'account_activated', $accountActivated );
$tpl->setVariable( 'already_active', $alreadyActive );

// This line is deprecated, the correct name of the variable should
// be 'account_activated' as shown above.
// However it is kept for backwards compatibility.
$tpl->setVariable( 'account_avtivated', $accountActivated );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:user/activate.tpl' );
$Result['path'] = array( array( 'text' => eZi18n::translate( 'kernel/user', 'User' ),
                                'url' => false ),
                         array( 'text' => eZi18n::translate( 'kernel/user', 'Activate' ),
                                'url' => false ) );
$ini = eZINI::instance();
if ( $ini->variable( 'SiteSettings', 'LoginPage' ) == 'custom' )
    $Result['pagelayout'] = 'loginpagelayout.tpl';

?>

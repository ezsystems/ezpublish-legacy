<?php
//
// Created on: <02-May-2002 16:24:15 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.7.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'kernel/common/template.php' );
include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuserloginhandler.php' );

//$Module->setExitStatus( EZ_MODULE_STATUS_SHOW_LOGIN_PAGE );

$Module =& $Params['Module'];

$ini =& eZINI::instance();
$http =& eZHTTPTool::instance();

$userLogin = '';
$userPassword = '';
$userRedirectURI = '';

$loginWarning = false;

$siteAccessAllowed = true;
$siteAccessName = false;

if ( isset( $Params['SiteAccessAllowed'] ) )
    $siteAccessAllowed = $Params['SiteAccessAllowed'];
if ( isset( $Params['SiteAccessName'] ) )
    $siteAccessName = $Params['SiteAccessName'];

$postData = ''; // Will contain post data from previous page.
if ( $http->hasSessionVariable( '$_POST_BeforeLogin' ) )
{
    $postData = $http->sessionVariable( '$_POST_BeforeLogin' );
    $http->removeSessionVariable( '$_POST_BeforeLogin' );
}

if ( $Module->isCurrentAction( 'Login' ) and
     $Module->hasActionParameter( 'UserLogin' ) and
     $Module->hasActionParameter( 'UserPassword' ) and
     !$http->hasPostVariable( "RegisterButton" )
     )
{
    $userLogin = $Module->actionParameter( 'UserLogin' );
    $userPassword = $Module->actionParameter( 'UserPassword' );
    $userRedirectURI = $Module->actionParameter( 'UserRedirectURI' );

    if ( trim( $userRedirectURI ) == "" )
    {
        // Only use redirection if RequireUserLogin is disabled
        $requireUserLogin = ( $ini->variable( "SiteAccessSettings", "RequireUserLogin" ) == "true" );
        if ( !$requireUserLogin )
        {
            if ( $http->hasSessionVariable( "LastAccessesURI" ) )
                $userRedirectURI = $http->sessionVariable( "LastAccessesURI" );
        }

        if ( $http->hasSessionVariable( "RedirectAfterLogin" ) )
        {
            $userRedirectURI = $http->sessionVariable( "RedirectAfterLogin" );
        }
    }
    // Save array of previous post variables in session variable
    $post = $http->attribute( 'post' );
    $lastPostVars = array();
    foreach ( array_keys( $post ) as $postKey )
    {
        if ( substr( $postKey, 0, 5 ) == 'Last_' )
            $lastPostVars[ substr( $postKey, 5, strlen( $postKey ) )] = $post[ $postKey ];
    }
    if ( count( $lastPostVars ) > 0 )
    {
        $postData = $lastPostVars;
        $http->setSessionVariable( 'LastPostVars', $lastPostVars );
    }

    $user = false;
    if ( $userLogin != '' )
    {
        $http->removeSessionVariable( 'RedirectAfterLogin' );

        $ini =& eZINI::instance();
        if ( $ini->hasVariable( 'UserSettings', 'LoginHandler' ) )
        {
            $loginHandlers = $ini->variable( 'UserSettings', 'LoginHandler' );
        }
        else
        {
            $loginHandlers = array( 'standard' );
        }
        $hasAccessToSite = true;
        foreach ( array_keys ( $loginHandlers ) as $key )
        {
            $loginHandler = $loginHandlers[$key];
            $userClass =& eZUserLoginHandler::instance( $loginHandler );
            $user = $userClass->loginUser( $userLogin, $userPassword );
            if ( get_class( $user ) == 'ezuser' )
            {
                $uri =& eZURI::instance( eZSys::requestURI() );
                $access = accessType( $uri,
                                      eZSys::hostname(),
                                      eZSys::serverPort(),
                                      eZSys::indexFile() );
                $siteAccessResult = $user->hasAccessTo( 'user', 'login' );
                $hasAccessToSite = false;
                // A check that the user has rights to access current siteaccess.
                if ( $siteAccessResult[ 'accessWord' ] == 'limited' )
                {
                    include_once( 'lib/ezutils/classes/ezsys.php' );

                    $policyChecked = false;
                    foreach ( array_keys( $siteAccessResult['policies'] ) as $key )
                    {
                        $policy =& $siteAccessResult['policies'][$key];
                        if ( isset( $policy['SiteAccess'] ) )
                        {
                            $policyChecked = true;
                            if ( in_array( eZSys::ezcrc32( $access[ 'name' ] ), $policy['SiteAccess'] ) )
                            {
                                $hasAccessToSite = true;
                                break;
                            }
                        }
                        if ( $hasAccessToSite )
                            break;
                    }
                    if ( !$policyChecked )
                        $hasAccessToSite = true;
                }
                else if ( $siteAccessResult[ 'accessWord' ] == 'yes' )
                {
                    $hasAccessToSite = true;
                }
                // If the user doesn't have the rights.
                if ( !$hasAccessToSite )
                {
                    $user->logoutCurrent();
                    $user = null;
                    $siteAccessName = $access['name'];
                    $siteAccessAllowed = false;
                }
                break;
            }
        }
        if ( ( get_class( $user ) != 'ezuser' ) and $hasAccessToSite )
            $loginWarning = true;
    }
    else
    {
        $loginWarning = true;
    }

    $redirectionURI = $userRedirectURI;
    if ( $redirectionURI == '' )
        $redirectionURI = $ini->variable( 'SiteSettings', 'DefaultPage' );

//     eZDebug::writeDebug( $user, 'user');
    $userID = 0;
    if ( get_class( $user ) == 'ezuser' )
        $userID = $user->id();
    if ( $userID > 0 )
    {
        $http->removeSessionVariable( 'eZUserLoggedInID' );
        $http->setSessionVariable( 'eZUserLoggedInID', $userID );
        return $Module->redirectTo( $redirectionURI );
    }
}
else
{
    $loginPage = $ini->variable( 'SiteSettings', 'LoginPage' );

    if ( $loginPage == 'embedded' ||
         $loginPage == 'custom' )
    {
        $requestedURI =& $GLOBALS['eZRequestedURI'];
        if ( get_class( $requestedURI ) == 'ezuri' )
        {
            $requestedModule = $requestedURI->element( 0, false );
            $requestedView = $requestedURI->element( 1, false );
            if ( $requestedModule != 'user' or
                 $requestedView != 'login' )
                $userRedirectURI = $requestedURI->uriString( true );
        }
    }
    else
    {
        eZUserLoginHandler::forceLogin();
        return $Module->redirectTo( '/' );
    }
}

if( $http->hasPostVariable( "RegisterButton" ) )
{
    $Module->redirectToView( 'register' );
}
$tpl =& templateInit();

$tpl->setVariable( 'login', $userLogin, 'User' );
$tpl->setVariable( 'post_data', $postData, 'User' );
$tpl->setVariable( 'password', $userPassword, 'User' );
$tpl->setVariable( 'redirect_uri', $userRedirectURI, 'User' );
$tpl->setVariable( 'warning', array( 'bad_login' => $loginWarning ), 'User' );

$tpl->setVariable( 'site_access', array( 'allowed' => $siteAccessAllowed,
                                         'name' => $siteAccessName ) );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:user/login.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/user', 'User' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/user', 'Login' ),
                                'url' => false ) );
if ( $ini->variable( 'SiteSettings', 'LoginPage' ) == 'custom' )
    $Result['pagelayout'] = 'loginpagelayout.tpl';

?>

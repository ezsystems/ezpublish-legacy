<?php
//
// Created on: <02-May-2002 16:24:15 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'lib/ezutils/classes/ezhttptool.php' );
//include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
require_once( 'kernel/common/template.php' );
//include_once( 'lib/ezutils/classes/ezini.php' );
//include_once( 'kernel/classes/datatypes/ezuser/ezuserloginhandler.php' );

//$Module->setExitStatus( EZ_MODULE_STATUS_SHOW_LOGIN_PAGE );

$Module = $Params['Module'];

$ini = eZINI::instance();
$http = eZHTTPTool::instance();

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

        $ini = eZINI::instance();
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
            $userClass = eZUserLoginHandler::instance( $loginHandler );
            $user = $userClass->loginUser( $userLogin, $userPassword );
            if ( $user instanceof eZUser )
            {
                $uri = eZURI::instance( eZSys::requestURI() );
                $access = accessType( $uri,
                                      eZSys::hostname(),
                                      eZSys::serverPort(),
                                      eZSys::indexFile() );
                $siteAccessResult = $user->hasAccessTo( 'user', 'login' );
                $hasAccessToSite = false;
                // A check that the user has rights to access current siteaccess.
                if ( $siteAccessResult[ 'accessWord' ] == 'limited' )
                {
                    //include_once( 'lib/ezutils/classes/ezsys.php' );

                    $policyChecked = false;
                    foreach ( $siteAccessResult['policies'] as $policy )
                    {
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
        if ( !( $user instanceof eZUser ) and $hasAccessToSite )
            $loginWarning = true;
    }
    else
    {
        $loginWarning = true;
    }

    $redirectionURI = $userRedirectURI;

    // Determine if we already know redirection URI.
    $haveRedirectionURI = ( $redirectionURI != '' && $redirectionURI != '/' );

    if ( !$haveRedirectionURI )
        $redirectionURI = $ini->variable( 'SiteSettings', 'DefaultPage' );

    /* If the user has successfully passed authorization
     * and we don't know redirection URI yet.
     */
    if ( is_object( $user ) && !$haveRedirectionURI )
    {
        /*
         * Choose where to redirect the user to after successful login.
         * The checks are done in the following order:
         * 1. Per-user.
         * 2. Per-group.
         *    If the user object is published under several groups, main node is chosen
         *    (it its URI non-empty; otherwise first non-empty URI is chosen from the group list -- if any).
         *
         * See doc/features/3.8/advanced_redirection_after_user_login.txt for more information.
         */

        // First, let's determine which attributes we should search redirection URI in.
        $userUriAttrName  = '';
        $groupUriAttrName = '';
        $ini = eZINI::instance();
        if ( $ini->hasVariable( 'UserSettings', 'LoginRedirectionUriAttribute' ) )
        {
            $uriAttrNames = $ini->variable( 'UserSettings', 'LoginRedirectionUriAttribute' );
            if ( is_array( $uriAttrNames ) )
            {
                if ( isset( $uriAttrNames['user'] ) )
                    $userUriAttrName = $uriAttrNames['user'];

                if ( isset( $uriAttrNames['group'] ) )
                    $groupUriAttrName = $uriAttrNames['group'];
            }
        }

        $userObject = $user->attribute( 'contentobject' );

        // 1. Check if redirection URI is specified for the user
        $userUriSpecified = false;
        if ( $userUriAttrName )
        {
            $userDataMap = $userObject->attribute( 'data_map' );
            if ( !isset( $userDataMap[$userUriAttrName] ) )
            {
                eZDebug::writeWarning( "Cannot find redirection URI: there is no attribute '$userUriAttrName' in object '" .
                                       $userObject->attribute( 'name' ) .
                                       "' of class '" .
                                       $userObject->attribute( 'class_name' ) . "'." );
            }
            elseif ( ( $uriAttribute = $userDataMap[$userUriAttrName] ) &&
                     ( $uri = $uriAttribute->attribute( 'content' ) ) )
            {
                $redirectionURI = $uri;
                $userUriSpecified = true;
            }
        }

        // 2.Check if redirection URI is specified for at least one of the user's groups (preferring main parent group).
        if ( !$userUriSpecified && $groupUriAttrName && $user->hasAttribute( 'groups' ) )
        {
            $groups = $user->attribute( 'groups' );

            if ( isset( $groups ) && is_array( $groups ) )
            {
                $chosenGroupURI = '';
                foreach ( $groups as $groupID )
                {
                    $group = eZContentObject::fetch( $groupID );
                    $groupDataMap = $group->attribute( 'data_map' );
                    $isMainParent = ( $group->attribute( 'main_node_id' ) == $userObject->attribute( 'main_parent_node_id' ) );

                    if ( !isset( $groupDataMap[$groupUriAttrName] ) )
                    {
                        eZDebug::writeWarning( "Cannot find redirection URI: there is no attribute '$groupUriAttrName' in object '" .
                                               $group->attribute( 'name' ) .
                                               "' of class '" .
                                               $group->attribute( 'class_name' ) . "'." );
                        continue;
                    }
                    $uri = $groupDataMap[$groupUriAttrName]->attribute( 'content' );
                    if ( $uri )
                    {
                        if ( $isMainParent )
                        {
                            $chosenGroupURI = $uri;
                            break;
                        }
                        elseif ( !$chosenGroupURI )
                            $chosenGroupURI = $uri;
                    }
                }

                if ( $chosenGroupURI ) // if we've chose an URI from one of the user's groups.
                    $redirectionURI = $chosenGroupURI;
            }
        }
    }

    $userID = 0;
    if ( $user instanceof eZUser )
        $userID = $user->id();
    if ( $userID > 0 )
    {
        if ( $http->hasPostVariable( 'Cookie' ) )
        {
            $ini = eZINI::instance();
            $rememberMeTimeout = $ini->hasVariable( 'Session', 'RememberMeTimeout' )
                                 ? $ini->variable( 'Session', 'RememberMeTimeout' )
                                 : false;
            if ( $rememberMeTimeout )
            {
                $GLOBALS['RememberMeTimeout'] = $rememberMeTimeout;
                eZSessionStop();
                eZSessionStart();
                unset( $GLOBALS['RememberMeTimeout'] );
            }

        }
        $http->removeSessionVariable( 'eZUserLoggedInID' );
        $http->setSessionVariable( 'eZUserLoggedInID', $userID );

        // Remove all temporary drafts
        //include_once( 'kernel/classes/ezcontentobject.php' );
        eZContentObject::cleanupAllInternalDrafts( $userID );
        return $Module->redirectTo( $redirectionURI );
    }
}
else
{
    // called from outside of a template (?)
    $requestedURI = $GLOBALS['eZRequestedURI'];
    if ( $requestedURI instanceof eZURI )
    {
        $requestedModule = $requestedURI->element( 0, false );
        $requestedView = $requestedURI->element( 1, false );
        if ( $requestedModule != 'user' or
             $requestedView != 'login' )
            $userRedirectURI = $requestedURI->originalURIString( false );
    }
}

if ( $http->hasPostVariable( "RegisterButton" ) )
{
    $Module->redirectToView( 'register' );
}

$userIsNotAllowedToLogin = false;
$failedLoginAttempts = false;
$maxNumOfFailedLogin = !eZUser::isTrusted() ? eZUser::maxNumberOfFailedLogin() : false;

// Should we show message about failed login attempt and max number of failed login
if ( $loginWarning and isset( $GLOBALS['eZFailedLoginAttemptUserID'] ) )
{
    $ini = eZINI::instance();
    $showMessageIfExceeded = $ini->hasVariable( 'UserSettings', 'ShowMessageIfExceeded' ) ? $ini->variable( 'UserSettings', 'ShowMessageIfExceeded' ) == 'true' : false;

    $failedUserID = $GLOBALS['eZFailedLoginAttemptUserID'];
    $failedLoginAttempts = eZUser::failedLoginAttemptsByUserID( $failedUserID );

    $canLogin = eZUser::isEnabledAfterFailedLogin( $failedUserID );
    if ( $showMessageIfExceeded and !$canLogin )
        $userIsNotAllowedToLogin = true;
}

$tpl = templateInit();

$tpl->setVariable( 'login', $userLogin, 'User' );
$tpl->setVariable( 'post_data', $postData, 'User' );
$tpl->setVariable( 'password', $userPassword, 'User' );
$tpl->setVariable( 'redirect_uri', $userRedirectURI, 'User' );
$tpl->setVariable( 'warning', array( 'bad_login' => $loginWarning ), 'User' );

$tpl->setVariable( 'site_access', array( 'allowed' => $siteAccessAllowed,
                                         'name' => $siteAccessName ) );
$tpl->setVariable( 'user_is_not_allowed_to_login', $userIsNotAllowedToLogin, 'User' );
$tpl->setVariable( 'failed_login_attempts', $failedLoginAttempts, 'User' );
$tpl->setVariable( 'max_num_of_failed_login', $maxNumOfFailedLogin, 'User' );


$Result = array();
$Result['content'] = $tpl->fetch( 'design:user/login.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/user', 'User' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/user', 'Login' ),
                                'url' => false ) );
if ( $ini->variable( 'SiteSettings', 'LoginPage' ) == 'custom' )
    $Result['pagelayout'] = 'loginpagelayout.tpl';

?>

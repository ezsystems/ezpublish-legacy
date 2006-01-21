<?php
//
// Created on: <02-May-2002 16:24:15 bf>
//
// Copyright (C) 1999-2006 eZ systems as. All rights reserved.
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
     $Module->hasActionParameter( 'UserPassword' )
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

    /* If the user has successfully passed authorization
     * and we don't know redirection URI yet.
     */
    if ( is_object( $user ) && ( !$redirectionURI || $redirectionURI == '/' ) )
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
        $ini =& eZINI::instance();
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
            $uriAttribute =& $userDataMap[$userUriAttrName];

            if ( !isset( $userDataMap[$userUriAttrName] ) )
            {
                eZDebug::writeWarning( "Cannot find redirection URI: there is no attribute '$userUriAttrName' in object '" .
                                       $userObject->attribute( 'name' ) .
                                       "' of class '" .
                                       $userObject->attribute( 'class_name' ) . "'." );
            }
            elseif ( ( $uriAttribute =& $userDataMap[$userUriAttrName] ) &&
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
                    $uriAttribute =& $groupDataMap[$groupUriAttrName];
                    $uri = $uriAttribute->attribute( 'content' );
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

//     eZDebug::writeDebug( $user, 'user');
    $userID = 0;
    if ( get_class( $user ) == 'ezuser' )
        $userID = $user->id();
    if ( $userID > 0 )
    {
        $http->removeSessionVariable( 'eZUserLoggedInID' );
        $http->setSessionVariable( 'eZUserLoggedInID', $userID );
//        eZDebug::writeDebug( $http->sessionVariable( 'eZUserLoggedInID' ), 'userid' );
        return $Module->redirectTo( $redirectionURI );
    }
}
else
{
    // called from outside of a template (?)
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

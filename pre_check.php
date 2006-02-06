<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.5.x
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

include_once( "lib/ezutils/classes/ezhttptool.php" );

/*!
 Checks if the installation is valid and returns a module redirect if required.
 If CheckValidity in SiteAccessSettings is false then no check is done.
*/

function eZCheckValidity( &$siteBasics, &$uri )
{
//     eZDebug::writeDebug( "Checking validity" );
    $ini =& eZINI::instance();
    $checkValidity = ( $ini->variable( "SiteAccessSettings", "CheckValidity" ) == "true" );
    $check = null;
    if ( $checkValidity )
    {
//         eZDebug::writeDebug( "Setup required" );
        $check = array( "module" => "setup",
                        'function' => 'init' );
        // Turn off some features that won't bee needed yet
//        $siteBasics['policy-check-required'] = false;
        $siteBasics['policy-check-omit-list'][] = 'setup';
        $siteBasics['url-translator-allowed'] = false;
        $siteBasics['show-page-layout'] = $ini->variable( 'SetupSettings', 'PageLayout' );
        $siteBasics['validity-check-required'] = true;
        $siteBasics['user-object-required'] = false;
        $siteBasics['session-required'] = false;
        $siteBasics['db-required'] = false;
        $siteBasics['no-cache-adviced'] = true;
        $siteBasics['site-design-override'] = $ini->variable( 'SetupSettings', 'OverrideSiteDesign' );
        $access = array( 'name' => 'setup',
                         'type' => EZ_ACCESS_TYPE_URI );
        $access = changeAccess( $access );
        $GLOBALS['eZCurrentAccess'] = $access;
    }
    return $check;
}

/*!
 Checks if user is logged in, if not and the site requires user login for access
 a module redirect is returned.
*/
function eZCheckUser( &$siteBasics, &$uri )
{
//     eZDebug::writeDebug( "Checking user" );
    if ( !$siteBasics['user-object-required'] )
    {
//         eZDebug::writeDebug( "Skipping user requirements" );
        return null;
    }
    $ini =& eZINI::instance();
    $requireUserLogin = ( $ini->variable( "SiteAccessSettings", "RequireUserLogin" ) == "true" );
    $check = null;
    $http =& eZHTTPTool::instance();
    if ( !$requireUserLogin )
        return null;
//     $uri =& $GLOBALS['eZRequestedURI'];
    $check = array( "module" => "user",
                    "function" => "login" );
    if ( $http->hasSessionVariable( "eZUserLoggedInID" ) and
         $http->sessionVariable( "eZUserLoggedInID" ) != '' and
         $http->sessionVariable( "eZUserLoggedInID" ) != $ini->variable( 'UserSettings', 'AnonymousUserID' ) )
    {
        if( include_once( "kernel/classes/datatypes/ezuser/ezuser.php" ) )
            $currentUser =& eZUser::currentUser();

        if ( is_object( $currentUser ) && !$currentUser->isEnabled() )
        {
            eZUser::logoutCurrent();
            $currentUser =& eZUser::currentUser();
        }
        else
            return null;
    }
    $moduleName = $uri->element();
    $viewName = $uri->element( 1 );
    $anonymousAccessList = $ini->variable( "SiteAccessSettings", "AnonymousAccessList" );
    $anonymousAccessList[] = 'ezinfo';
    foreach ( $anonymousAccessList as $anonymousAccess )
    {
        $elements = explode( '/', $anonymousAccess );
        if ( count( $elements ) == 1 )
        {
            if ( $moduleName == $elements[0] )
                return null;
        }
        else
        {
            if ( $moduleName == $elements[0] and
                 $viewName == $elements[1] )
                return null;
        }
    }
    return $check;
}

/*!
 \return an array with items to run a check on, each items
 is an associative array. The item must contain:
 - function - name of the function to run
*/
function eZCheckList()
{
    $checks = array();
    $checks["validity"] = array( "function" => "eZCheckValidity" );
    $checks["user"] = array( "function" => "eZCheckUser" );
    return $checks;
}

/*!
 \return an array with check items in the order they should be checked.
*/
function eZCheckOrder()
{
    $checkOrder = array( 'validity', 'user' );
    return $checkOrder;
}

/*!
 Does pre checks and returns a structure with redirection information,
 returns null if nothing should be done.
*/
function eZHandlePreChecks( &$siteBasics, &$uri )
{
    $checks = eZCheckList();
    precheckAllowed( $checks );
    $checkOrder = eZCheckOrder();
    foreach( $checkOrder as $checkItem )
    {
        if ( !isset( $checks[$checkItem] ) )
            continue;
        $check = $checks[$checkItem];
        if ( !isset( $check["allow"] ) or $check["allow"] )
        {
            $func = $check["function"];
            $check = $func( $siteBasics, $uri );
            if ( $check !== null )
                return $check;
        }
    }
    return null;
}


?>

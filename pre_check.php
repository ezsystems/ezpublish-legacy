<?php
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

//include_once( "lib/ezutils/classes/ezhttptool.php" );

/*!
 Checks if the installation is valid and returns a module redirect if required.
 If CheckValidity in SiteAccessSettings is false then no check is done.
*/

function eZCheckValidity( &$siteBasics, &$uri )
{
    $ini = eZINI::instance();
    $checkValidity = ( $ini->variable( "SiteAccessSettings", "CheckValidity" ) == "true" );
    $check = null;
    if ( $checkValidity )
    {
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
        $siteBasics['no-cache-adviced'] = false;
        $siteBasics['site-design-override'] = $ini->variable( 'SetupSettings', 'OverrideSiteDesign' );
        $access = array( 'name' => 'setup',
                         'type' => EZ_ACCESS_TYPE_URI );
        $access = changeAccess( $access );

        //include_once( 'lib/ezi18n/classes/eztranslatormanager.php' );
        eZTranslatorManager::enableDynamicTranslations();
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
 Check if user login is required. If so, use login handler to redirect user.
*/
function eZCheckUser( &$siteBasics, &$uri )
{
    if ( !$siteBasics['user-object-required'] )
    {
        return null;
    }

    // if( !include_once( 'kernel/classes/datatypes/ezuser/ezuserloginhandler.php' ) )
    //     return null;

    $http = eZHTTPTool::instance();
    $ini = eZINI::instance();
    $requireUserLogin = ( $ini->variable( "SiteAccessSettings", "RequireUserLogin" ) == "true" );
    $forceLogin = $http->hasSessionVariable( eZUserLoginHandler::FORCE_LOGIN );
    if ( !$requireUserLogin &&
         !$forceLogin )
    {
        return null;
    }

    return eZUserLoginHandler::checkUser( $siteBasics, $uri );
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
    $checks = precheckAllowed( $checks );
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

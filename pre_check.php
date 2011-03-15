<?php
/**
 * File containing pre check functions as used to validate request
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package index
 */

/**
 * Checks if the installation is valid and returns a module redirect if required.
 * If CheckValidity in SiteAccessSettings is false then no check is done.
 *
 * @deprecated As of 4.4, moved to index.php for now
 * @param array $siteBasics
 * @param eZURI $uri
 */
function eZCheckValidity( array &$siteBasics, eZURI $uri )
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
                         'type' => eZSiteAccess::TYPE_URI );
        $access = eZSiteAccess::change( $access );

        eZTranslatorManager::enableDynamicTranslations();
    }
    return $check;
}

/**
 * List of functions that should be checked by key identifier
 *
 * @deprecated As of 4.4, since SitePrecheckRules setting is not used or documented anywhere
 *             (documentation above was added when it was deprecated)
 *             Also validity checks needs to be done before session init and user check after..
 * @return array An associative array with items to run a check on, each items
 * is an associative array. The item must contain: function -> name of the function
 */
function eZCheckList()
{
    $checks = array();
    $checks['validity'] = array( 'function' => 'eZCheckValidity' );
    $checks['user'] = array( 'function' => 'eZCheckUser' );
    return $checks;
}

/**
 * Check if user login is required. If so, use login handler to redirect user.
 *
 * @deprecated As of 4.4, moved to {@link eZUserLoginHandler::preCheck()}
 * @param array $siteBasics
 * @param eZURI $uri
 * @return array|true|false|null An associative array on redirect with 'module' and 'function' keys, true on successful
 *                               and false/null on #fail.
 */
function eZCheckUser( array &$siteBasics, eZURI $uri )
{
    if ( !$siteBasics['user-object-required'] )
    {
        return null;
    }

    $ini = eZINI::instance();
    $requireUserLogin = ( $ini->variable( 'SiteAccessSettings', 'RequireUserLogin' ) == 'true' );
    $forceLogin = false;
    if ( eZSession::hasStarted() )
    {
        $http = eZHTTPTool::instance();
        $forceLogin = $http->hasSessionVariable( eZUserLoginHandler::FORCE_LOGIN );
    }
    if ( !$requireUserLogin && !$forceLogin )
    {
        return null;
    }

    return eZUserLoginHandler::checkUser( $siteBasics, $uri );
}

/**
 * Return the order that prechecks should be checked
 *
 * @deprecated As of 4.4, since SitePrecheckRules setting is not used or documented anywhere
 *             (documentation above was added when it was deprecated)
 *             Also validity checks needs to be done before session init and user check after..
 * @return array
 */
function eZCheckOrder()
{
    return array( 'validity', 'user' );
}

/**
 * Executes pre checks
 *
 * @deprecated As of 4.4, since SitePrecheckRules setting is not used or documented anywhere
 *             (documentation above was added when it was deprecated)
 *             Also validity checks needs to be done before session init and user check after..
 * @param array $siteBasics
 * @param eZURI $uri
 * @return array|null A structure with redirection information or null if nothing should be done.
 */
function eZHandlePreChecks( array &$siteBasics, eZURI $uri )
{
    $checks = eZCheckList();
    $checks = precheckAllowed( $checks );
    $checkOrder = eZCheckOrder();
    foreach( $checkOrder as $checkItem )
    {
        if ( !isset( $checks[$checkItem] ) )
            continue;
        $check = $checks[$checkItem];
        if ( !isset( $check['allow'] ) || $check['allow'] )
        {
            $func = $check['function'];
            $check = $func( $siteBasics, $uri );
            if ( $check !== null )
                return $check;
        }
    }
    return null;
}

/**
 * Uses [SitePrecheckRules] to check if a precheck is allowed or not.
 * Setting seems to be able to be defined like this (site.ini):
 *  [SitePrecheckRules]
 *  Rules[]
 *  # access can be enabled or disabled, and will affect the later
 *  Rules[]=access;enabled
 *  # precheckall can be true (makes prior access rule affect all prechecks)
 *  Rules[]=precheckall;true
 *  # precheck needs to be set to the same key as the precheck you want to allow / disallow
 *  Rules[]=precheck;validity
 *
 * @deprecated As of 4.4, since SitePrecheckRules setting is not used or documented anywhere
 *             (documentation above was added when it was deprecated)
 * @param array $prechecks
 * @return array The same $prechecks array but adjusted according to the SitePrecheckRules rules
 */
function precheckAllowed( array $prechecks )
{
    $ini = eZINI::instance();

    if ( !$ini->hasGroup( 'SitePrecheckRules' ) )
        return $prechecks;

    $tmp_allow = true;
    $items     = $ini->variableArray( 'SitePrecheckRules', 'Rules' );
    foreach( $items as $item )
    {
        $name  = strtolower( $item[0] );
        $value = $item[1];
        switch( $name )
        {
            case 'access':
            {
                $tmp_allow = ($value === 'enable');
            } break;
            case 'precheckall':
            {
                if ( $value === 'true' )
                {
                    foreach( $prechecks as $key => $value )
                    {
                        $prechecks[$key]['allow'] = $tmp_allow;
                    }
                }
            } break;
            case 'precheck':
            {
                if ( isset( $prechecks[$value] ) )
                    $prechecks[$value]['allow'] = $tmp_allow;
            } break;
            default:
            {
                eZDebug::writeError( "Unknown precheck rule: $name=$value", 'Access' );
            } break;
        }
    }
    return $prechecks;
}

?>

<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

// Set a default time zone if none is given. The time zone can be overriden
// in config.php or php.ini.
if ( !ini_get( 'date.timezone' ) )
{
    date_default_timezone_set( 'UTC' );
}

require 'autoload.php';
include_once( 'lib/ezutils/classes/ezsession.php' );
include_once( 'kernel/common/ezincludefunctions.php' );

// Tweaks ini filetime checks if not defined!
// This makes ini system not check modified time so
// that index_ajax.php can assume that index.php does
// this regular enough, set in config.php to override.
if ( !defined('EZP_INI_FILEMTIME_CHECK') )
{
    define( 'EZP_INI_FILEMTIME_CHECK', false );
}

function ezupdatedebugsettings()
{
}

function eZFatalError()
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error' );
}

function exitWithInternalError( $errorText )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error' );
    include_once( 'extension/ezjscore/classes/ezjscajaxcontent.php' );
    $contentType = ezjscAjaxContent::getHttpAccept();

    // set headers
    if ( $contentType === 'xml' )
        header('Content-Type: text/xml; charset=utf-8');
    else if ( $contentType === 'json' )
        header('Content-Type: text/javascript; charset=utf-8');

    eZDB::checkTransactionCounter();
    echo ezjscAjaxContent::autoEncode( array( 'error_text' => $errorText, 'content' => '' ), $contentType );
    eZExecution::cleanExit();
}

function hasAccessToBySetting( $moduleName, $view = false, $policyAccessList = false )
{
    if ( $policyAccessList !== false )
    {
        if ( in_array( $moduleName, $policyAccessList) )
            return true;
        if ( $view && in_array( $moduleName . '/' . $view, $policyAccessList) )
            return true;
    }
    return false;
}

function hasAccessToLogin( eZUser $user, $crc32AccessName = false )
{
    $policyChecked = false;
    $siteAccessResult = $user->hasAccessTo( 'user', 'login' );
    if ( $crc32AccessName && $siteAccessResult[ 'accessWord' ] === 'limited' )
    {
        foreach ( $siteAccessResult['policies'] as $policy )
        {
            if ( isset( $policy['SiteAccess'] ) )
            {
                $policyChecked = true;
                if ( in_array( $crc32AccessName, $policy['SiteAccess'] ) )
                    return true;
            }
        }
    }
    if ( $siteAccessResult[ 'accessWord' ] === 'yes' || !$policyChecked )
    {
        return true;
    }
    return false;
}

ignore_user_abort( true );
ob_start();
error_reporting ( E_ALL );


/*
    see:
    - http://www.php.net/manual/en/function.session-set-save-handler.php
    - http://bugs.php.net/bug.php?id=33635
    - http://bugs.php.net/bug.php?id=33772
*/
register_shutdown_function( 'session_write_close' );

// register fatal error & debug handler
eZExecution::addFatalErrorHandler( 'eZFatalError' );
eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

// Trick to get eZSys working with a script other than index.php (while index.php still used in generated URLs):
$_SERVER['SCRIPT_FILENAME'] = str_replace( '/index_ajax.php', '/index.php', $_SERVER['SCRIPT_FILENAME'] );
$_SERVER['PHP_SELF'] = str_replace( '/index_ajax.php', '/index.php', $_SERVER['PHP_SELF'] );
$_SERVER['REQUEST_URI'] = str_replace( '/index_ajax.php', '/index.php', $_SERVER['REQUEST_URI'] );
$_SERVER['SCRIPT_NAME'] = str_replace( '/index_ajax.php', '/index.php', $_SERVER['SCRIPT_NAME'] );

// set timezone to avoid strict errors
$ini = eZINI::instance();
$timezone = $ini->variable( 'TimeZoneSettings', 'TimeZone' );
if ( $timezone )
{
    putenv( "TZ=$timezone" );
}

// init uri code
$GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );
eZSys::init( 'index.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) === 'true' );
$uri = eZURI::instance( eZSys::requestURI() );
$GLOBALS['eZRequestedURI'] = $uri;

require 'pre_check.php';

// Check for extension
eZExtension::activateExtensions( 'default' );

// load siteaccess
require 'access.php';
$access = accessType( $uri,
                      eZSys::hostname(),
                      eZSys::serverPort(),
                      eZSys::indexFile() );
$access = changeAccess( $access );

// Check for new extension loaded by siteaccess ( disabled for performance reasons )
//eZExtension::activateExtensions( 'access' );

// check module name
$moduleName = $uri->element();
if ( strpos( $moduleName, 'index.php' ) !== false  )
{
    $uri->increase();
    $moduleName = $uri->element();
}

if ( $moduleName === '' )
{
    exitWithInternalError( 'Did not find module info in url. (165)' );
}

// check db connection
$db = eZDB::instance();
if ( $db->isConnected() )
{
    if ( class_exists( 'eZSession' ) )
        eZSession::start();
    else
        eZSessionStart();
}
else
{
    exitWithInternalError( 'Could not connect to database.' );
}


// Initialize with locale settings
$locale       = eZLocale::instance();
$languageCode = $locale->httpLocaleCode();
$httpCharset  = eZTextCodec::httpCharset();
$phpLocale    = trim( $ini->variable( 'RegionalSettings', 'SystemLocale' ) );
if ( $phpLocale !== '' )
{
    setlocale( LC_ALL, explode( ',', $phpLocale ) );
}

// send header information
$headerList = array( 'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
                     'Last-Modified' => gmdate( 'D, d M Y H:i:s' ) . ' GMT',
                     'Cache-Control' => 'no-cache, must-revalidate',
                     'Pragma' => 'no-cache',
                     'X-Powered-By' => 'eZ Publish',
                     'Content-Type' => 'text/html; charset=' . $httpCharset,
                     'Served-by' => $_SERVER['SERVER_NAME'],
                     'Content-language' => $languageCode );
$headerOverrideArray = eZHTTPHeader::headerOverrideArray( $uri );
$headerList = array_merge( $headerList, $headerOverrideArray );
foreach( $headerList as $key => $value )
{
    header( $key . ': ' . $value );
}

// set default section id
eZSection::initGlobalID();

// Get and set module repositories
$moduleINI = eZINI::instance( 'module.ini' );
$globalModuleRepositories = array( );
$extensionRepositories = $moduleINI->variable( 'ModuleSettings', 'ExtensionRepositories' );
foreach ( $extensionRepositories as $repo )
{
    $globalModuleRepositories[] = 'extension/' . $repo . '/modules';
}
eZModule::setGlobalPathList( $globalModuleRepositories );

$siteBasics = array();
$userObjectRequired = false;
$siteBasics['user-object-required'] =& $userObjectRequired;
$check = eZCheckUser( $siteBasics, $uri );
if ( $check !== null )
{
    exitWithInternalError( "'eZCheckUser' returned something else then null: " . var_export( $check, true ) );
}

// find module
$module = eZModule::findModule( $moduleName );
if ( !$module instanceof eZModule )
{
    exitWithInternalError( "'$moduleName' module does not exist, or is not a valid module." );
}

// find module view
$viewName = $uri->element( 1 );
if ( !$viewName )
{
    exitWithInternalError( 'Did not find view info in url.' );
}

// Check if module / view is disabled
$moduleCheck = accessAllowed( $uri );
if ( !$moduleCheck['result'] )
{
    exitWithInternalError( '$moduleName/$viewName is disabled.' );
}

// verify view name
$moduleViews = $module->attribute('views');
if ( !isset( $moduleViews[$viewName] ) )
{
    exitWithInternalError( "'$viewName' view does not exist on the current module." );
}

// check access
if ( !hasAccessToBySetting( $moduleName, $viewName, $ini->variable( 'RoleSettings', 'PolicyOmitList' ) ) )
{
    // check user/login access
    $currentUser = eZUser::currentUser();
    if ( !hasAccessToLogin( $currentUser, eZSys::ezcrc32( $access[ 'name' ] ) ) )
    {
        exitWithInternalError( 'User does not have access to the current siteaccess.' );
    }
    
    // check access to view
    if ( !$currentUser->hasAccessToView( $module, $viewName, $params ) )
    {
        exitWithInternalError( "User does not have access to the $moduleName/$viewName policy." );
    }
}

// run module
$uri->increase();
$uri->increase();
$GLOBALS['eZRequestedModule'] = $module;
$moduleResult = $module->run( $viewName, $uri->elements( false ), false, $uri->userParameters() );

// run output filter
if ( $ini->hasVariable( 'OutputSettings', 'OutputFilterName' ) )
{
    $classname = $ini->variable( 'OutputSettings', 'OutputFilterName' );
    if( class_exists( $classname ) )
    {
        $moduleResult['content'] = call_user_func( array ( $classname, 'filter' ), $moduleResult['content'] );
    }
}

// output content
$out = ob_get_clean();
echo trim( $out );
eZDB::checkTransactionCounter();
echo $moduleResult['content'];
eZExecution::cleanExit();

?>
<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

// Set a default time zone if none is given. The time zone can be overriden
// in config.php or php.ini.
if ( !ini_get( 'date.timezone' ) )
{
    date_default_timezone_set( 'UTC' );
}

define( 'MAX_AGE', 86400 );
header( 'X-Powered-By: eZ Publish (index_treemenu)' );

if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified' );
    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
    header( 'Cache-Control: max-age=' . MAX_AGE );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) . ' GMT' );
    header( 'Pragma: ' );
    exit();
}

require 'autoload.php';

// Tweaks ini filetime checks if not defined!
// This makes ini system not check modified time so
// that index_treemenu.php can assume that index.php does
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

function exitWithInternalError()
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error' );
    eZExecution::cleanup();
    eZExecution::setCleanExit();
}

ignore_user_abort( true );
ob_start();
error_reporting ( E_ALL );

eZExecution::addFatalErrorHandler( 'eZFatalError' );
eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

// Trick to get eZSys working with a script other than index.php (while index.php still used in generated URLs):
$_SERVER['SCRIPT_FILENAME'] = str_replace( '/index_treemenu.php', '/index.php', $_SERVER['SCRIPT_FILENAME'] );
$_SERVER['PHP_SELF'] = str_replace( '/index_treemenu.php', '/index.php', $_SERVER['PHP_SELF'] );

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

// Check for extension
eZExtension::activateExtensions( 'default' );

// load siteaccess
$access = eZSiteAccess::match( $uri,
                      eZSys::hostname(),
                      eZSys::serverPort(),
                      eZSys::indexFile() );
$access = eZSiteAccess::change( $access );
$GLOBALS['eZCurrentAccess'] = $access;

// Check for new extension loaded by siteaccess
eZExtension::activateExtensions( 'access' );

$db = eZDB::instance();
if ( $db->isConnected() )
{
    eZSession::start();
}
else
{
    exitWithInternalError();
    return;
}

$moduleINI = eZINI::instance( 'module.ini' );
$globalModuleRepositories = $moduleINI->variable( 'ModuleSettings', 'ModuleRepositories' );
eZModule::setGlobalPathList( $globalModuleRepositories );

$module = eZModule::exists( 'content' );
if ( !$module )
{
    exitWithInternalError();
    return;
}

$function_name = 'treemenu';
$uri->increase();
$uri->increase();

$currentUser = eZUser::currentUser();
$siteAccessResult = $currentUser->hasAccessTo( 'user', 'login' );
$hasAccessToSite = false;
if ( $siteAccessResult[ 'accessWord' ] == 'limited' )
{
    $policyChecked = false;
    foreach ( $siteAccessResult['policies'] as $policy )
    {
        if ( isset( $policy['SiteAccess'] ) )
        {
            $policyChecked = true;
            $crc32AccessName = eZSys::ezcrc32( $access[ 'name' ] );
            if ( in_array( $crc32AccessName, $policy['SiteAccess'] ) )
            {
                $hasAccessToSite = true;
                break;
            }
        }
        if ( $hasAccessToSite )
        {
            break;
        }
    }
    if ( !$policyChecked )
    {
        $hasAccessToSite = true;
    }
}
else if ( $siteAccessResult[ 'accessWord' ] == 'yes' )
{
    $hasAccessToSite = true;
}

if ( !$hasAccessToSite )
{
    exitWithInternalError();
    return;
}

$GLOBALS['eZRequestedModule'] = $module;
$moduleResult = $module->run( $function_name, $uri->elements( false ) );

eZExecution::cleanup();
eZExecution::setCleanExit();

?>

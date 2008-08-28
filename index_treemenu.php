<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
if ( !ini_get( "date.timezone" ) )
{
    date_default_timezone_set( "UTC" );
}

define( 'MAX_AGE', 86400 );

if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified' );
    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
    header( 'Cache-Control: max-age=' . MAX_AGE );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) . ' GMT' );
    exit();
}

//require_once( 'lib/ezutils/classes/ezexecution.php' );
////include_once( 'lib/ezutils/classes/ezsys.php' );
//require_once( 'lib/ezutils/classes/ezdebug.php' );
////include_once( 'lib/ezutils/classes/ezini.php' );
////include_once( 'lib/ezutils/classes/ezuri.php' );
require_once( 'lib/ezutils/classes/ezsession.php' );
////include_once( 'lib/ezutils/classes/ezextension.php' );
require_once( 'kernel/common/ezincludefunctions.php' );
////include_once( 'lib/ezutils/classes/ezmodule.php' );
////include_once( 'lib/ezdb/classes/ezdb.php' );
////include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
require 'autoload.php';

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

$GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );

eZSys::init( 'index.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) == 'true' );

$uri = eZURI::instance( eZSys::requestURI() );

$GLOBALS['eZRequestedURI'] = $uri;

require_once 'pre_check.php';

// Check for extension
//include_once( 'lib/ezutils/classes/ezextension.php' );
require_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions( 'default' );
// Extension check end

require_once 'access.php';

$access = accessType( $uri,
                      eZSys::hostname(),
                      eZSys::serverPort(),
                      eZSys::indexFile() );
$access = changeAccess( $access );
$GLOBALS['eZCurrentAccess'] = $access;

$db = eZDB::instance();
if ( $db->isConnected() )
{
    eZSessionStart();
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

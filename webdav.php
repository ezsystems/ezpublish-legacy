<?php
//
// This is the index_webdav.php file. Manages WebDAV sessions.
//
// Created on: <15-Aug-2003 15:15:15 bh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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
if ( !ini_get( "date.timezone" ) )
{
    date_default_timezone_set( "UTC" );
}

require 'autoload.php';

ignore_user_abort( true );
ob_start();

ini_set( "display_errors" , "0" );

error_reporting ( E_ALL );

// Turn off session stuff, isn't needed for WebDAV operations.
$GLOBALS['eZSiteBasics']['session-required'] = false;

/*! Reads settings from site.ini and passes them to eZDebug.
 */
function eZUpdateDebugSettings()
{
    $ini = eZINI::instance();
    $debugSettings = array();
    $debugSettings['debug-enabled'] = $ini->variable( 'DebugSettings', 'DebugOutput' ) == 'enabled';
    $debugSettings['debug-by-ip']   = $ini->variable( 'DebugSettings', 'DebugByIP' )   == 'enabled';
    $debugSettings['debug-ip-list'] = $ini->variable( 'DebugSettings', 'DebugIPList' );
    eZDebug::updateSettings( $debugSettings );
}

/*!
 Reads settings from i18n.ini and passes them to eZTextCodec.
*/
function eZUpdateTextCodecSettings()
{
    $ini = eZINI::instance( 'i18n.ini' );

    list( $i18nSettings['internal-charset'], $i18nSettings['http-charset'], $i18nSettings['mbstring-extension'] ) =
        $ini->variableMulti( 'CharacterSettings', array( 'Charset', 'HTTPCharset', 'MBStringExtension' ), array( false, false, 'enabled' ) );

    eZTextCodec::updateSettings( $i18nSettings );
}

// Initialize text codec settings
eZUpdateTextCodecSettings();

// Check for extension
require_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions( 'default' );
// Extension check end

// Make sure site.ini and template.ini reloads its cache incase
// extensions override it
$ini = eZINI::instance( 'site.ini' );
$ini->loadCache();
$tplINI = eZINI::instance( 'template.ini' );
$tplINI->loadCache();

// Grab the main WebDAV setting (enable/disable) from the WebDAV ini file.
$webDavIni = eZINI::instance( 'webdav.ini' );
$enable = $webDavIni->variable( 'GeneralSettings', 'EnableWebDAV' );

function eZDBCleanup()
{
    if ( class_exists( 'ezdb' )
         and eZDB::hasInstance() )
    {
        $db = eZDB::instance();
        $db->setIsSQLOutputEnabled( false );
    }
}

function eZFatalError()
{
    eZDebug::setHandleType( eZDebug::HANDLE_NONE );
    eZWebDAVContentBackend::appendLogEntry( "****************************************" );
    eZWebDAVContentBackend::appendLogEntry( "Fatal error: eZ Publish did not finish its request" );
    eZWebDAVContentBackend::appendLogEntry( "The execution of eZ Publish was abruptly ended, the debug output is present below." );
    eZWebDAVContentBackend::appendLogEntry( "****************************************" );
    // $templateResult = null;
    // eZDisplayResult( $templateResult, eZDisplayDebug() );
}

// Check and proceed only if WebDAV functionality is enabled:
if ( $enable === 'true' )
{
    eZExecution::addCleanupHandler( 'eZDBCleanup' );
    eZExecution::addFatalErrorHandler( 'eZFatalError' );
    eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

    if ( !isset( $_SERVER['REQUEST_URI'] ) or
         !isset( $_SERVER['REQUEST_METHOD'] ) )
    {
        // We stop the script if these are missing
        // e.g. if run from the shell
        eZExecution::cleanExit();
    }
    require_once( "access.php" );
    require_once( "kernel/common/i18n.php" );

    eZModule::setGlobalPathList( array( "kernel" ) );
    eZWebDAVContentBackend::appendLogEntry( "========================================" );
    eZWebDAVContentBackend::appendLogEntry( "Requested URI is: " . $_SERVER['REQUEST_URI'], 'webdav.php' );

    // Initialize/set the index file.
    eZSys::init( 'webdav.php' );

    // @as 2009-01-05 - added cleaning up of the REQUEST_URI - not used
    // $_SERVER['REQUEST_URI'] = urldecode( $_SERVER['REQUEST_URI'] );

    // The top/root folder is publicly available (without auth):
    if ( $_SERVER['REQUEST_URI'] == ''  or
         $_SERVER['REQUEST_URI'] == '/' or
         $_SERVER['REQUEST_URI'] == '/webdav.php/' or
         $_SERVER['REQUEST_URI'] == '/webdav.php' )
    {
        // $requestUri = $_SERVER['REQUEST_URI'];
        // if ( $requestUri == '' )
        // {
        //     $requestUri = '/';
        // }
        // if ( $requestUri == '/webdav.php' )
        // {
        //     $requestUri = '/webdav.php/';
        // }

        $server = ezcWebdavServer::getInstance();

        $backend = new eZWebDAVContentBackend();
        $server->handle( $backend );
    }
    // Else: need to login with username/password:
    else
    {
        // Create & initialize a new instance of the content server.
        $server = ezcWebdavServer::getInstance();
        $server->pluginRegistry->registerPlugin(
            new ezcWebdavLockPluginConfiguration()
        );
        $backend = new eZWebDAVContentBackend();
        $server->auth = new eZWebDAVContentBackendAuth();

        // Get the name of the site that is being browsed.
        $currentSite = $backend->currentSiteFromPath( $_SERVER['REQUEST_URI'] );

        // Proceed only if the current site is valid:
        if ( $currentSite )
        {
            $backend->setCurrentSite( $currentSite );

            // Process the request.
            $server->handle( $backend );
        }
        // Else: site-name is invalid (was not among available sites).
        else
        {
            header( "HTTP/1.1 404 Not Found" );
        }
    }

    eZExecution::cleanExit();
}
// Else: WebDAV functionality is disabled, do nothing...
else
{
    header( "HTTP/1.1 404 Not Found" );
    print ( "WebDAV functionality is disabled!" );
}

?>

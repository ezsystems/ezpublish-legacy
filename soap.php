<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \brief The SOAP file will handle all eZ Publish soap requests.

  SOAP functions are
*/

ob_start();

ini_set( "display_errors" , "0" );

// Set a default time zone if none is given. The time zone can be overriden
// in config.php or php.ini.
if ( !ini_get( "date.timezone" ) )
{
    date_default_timezone_set( "UTC" );
}

require 'autoload.php';

/*!
 Reads settings from site.ini and passes them to eZDebug.
*/
function eZUpdateDebugSettings()
{
    $ini = eZINI::instance();

    list( $debugSettings['debug-enabled'], $debugSettings['debug-by-ip'], $debugSettings['debug-by-user'], $debugSettings['debug-ip-list'], $debugSettings['debug-user-list'] ) =
        $ini->variableMulti( 'DebugSettings', array( 'DebugOutput', 'DebugByIP', 'DebugByUser', 'DebugIPList', 'DebugUserIDList' ), array ( 'enabled', 'enabled', 'enabled' ) );
    eZDebug::updateSettings( $debugSettings );
}

$ini = eZINI::instance();

// Initialize/set the index file.
eZSys::init( 'soap.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) === 'true' );
$uri = eZURI::instance( eZSys::requestURI() );
$GLOBALS['eZRequestedURI'] = $uri;

// Check for extension
eZExtension::activateExtensions( 'default' );
// Extension check end

// Activate correct siteaccess
$soapINI = eZINI::instance( 'soap.ini' );
if ( $soapINI->variable( 'GeneralSettings', 'UseDefaultAccess' ) === 'enabled' )
{
    $access = array( 'name' => $ini->variable( 'SiteSettings', 'DefaultAccess' ),
                     'type' => eZSiteAccess::TYPE_DEFAULT );
}
else
{
    $access = eZSiteAccess::match( $uri,
                          eZSys::hostname(),
                          eZSys::serverPort(),
                          eZSys::indexFile() );
}
$access = eZSiteAccess::change( $access );
// Siteaccess activation end

// Check for activating Debug by user ID (Final checking. The first was in eZDebug::updateSettings())
eZDebug::checkDebugByUser();

// Check for siteaccess extension
eZExtension::activateExtensions( 'access' );
// Siteaccess extension check end

// Now that all extensions are activated and siteaccess has been changed, reset
// all eZINI instances as they may not take into account siteaccess specific settings.
eZINI::resetAllInstances( false );

/*!
 Reads settings from i18n.ini and passes them to eZTextCodec.
*/
function eZUpdateTextCodecSettings()
{
    $ini = eZINI::instance( 'i18n.ini' );

    list( $i18nSettings['internal-charset'], $i18nSettings['http-charset'] ) =
        $ini->variableMulti( 'CharacterSettings', array( 'Charset', 'HTTPCharset' ), array( false, false ) );

    eZTextCodec::updateSettings( $i18nSettings );
}

// Initialize text codec settings
eZUpdateTextCodecSettings();

// Initialize module loading
$moduleRepositories = eZModule::activeModuleRepositories();
eZModule::setGlobalPathList( $moduleRepositories );

// Load soap extensions
$enableSOAP = $soapINI->variable( 'GeneralSettings', 'EnableSOAP' );

if ( $enableSOAP == 'true' )
{
    // Login if we have username and password.
    if ( eZHTTPTool::username() and eZHTTPTool::password() )
        eZUser::loginUser( eZHTTPTool::username(), eZHTTPTool::password() );

    $server = new eZSOAPServer();

    foreach( $soapINI->variable( 'ExtensionSettings', 'SOAPExtensions' ) as $extension )
    {
        include_once( eZExtension::baseDirectory() . '/' . $extension . '/soap/initialize.php' );
    }

    $server->processRequest();
}

ob_end_flush();

eZExecution::cleanExit();

?>

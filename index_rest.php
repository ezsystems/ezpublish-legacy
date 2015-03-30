<?php
/**
 * File containing the rest bootstrap
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

require __DIR__ . '/autoload.php';
require __DIR__ . '/kernel/private/rest/classes/lazy.php';

// Below we are setting up a minimal eZ Publish environment from the old index.php
// This is a temporary measure.

eZDebug::setHandleType( eZDebug::HANDLE_NONE );
$GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );
$ini = eZINI::instance();
eZSys::init( 'index_rest.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) == 'true' );
$uri = eZURI::instance( eZSys::requestURI() );
$GLOBALS['eZRequestedURI'] = $uri;

// load extensions
eZExtension::activateExtensions( 'default' );

// setup for eZSiteAccess:change() needs some functions defined in old index.php
function eZUpdateDebugSettings()
{
    $ini = eZINI::instance();
    $debugSettings = array( 'debug-enabled' => false );
    $logList = $ini->variable( 'DebugSettings', 'AlwaysLog' );
    $logMap = array(
        'notice' => eZDebug::LEVEL_NOTICE,
        'warning' => eZDebug::LEVEL_WARNING,
        'error' => eZDebug::LEVEL_ERROR,
        'debug' => eZDebug::LEVEL_DEBUG,
        'strict' => eZDebug::LEVEL_STRICT
    );
    $debugSettings['always-log'] = array();
    foreach ( $logMap as $name => $level )
    {
        $debugSettings['always-log'][$level] = in_array( $name, $logList );
    }

    eZDebug::updateSettings( $debugSettings );
}

// set siteaccess from X-Siteaccess header if given and exists
if ( isset( $_SERVER['HTTP_X_SITEACCESS'] ) && eZSiteAccess::exists( $_SERVER['HTTP_X_SITEACCESS'] ) )
{
    $access = array( 'name' => $_SERVER['HTTP_X_SITEACCESS'], 'type' => eZSiteAccess::TYPE_STATIC );
}
else
{
    $access = eZSiteAccess::match( $uri, eZSys::hostname(), eZSys::serverPort(), eZSys::indexFile() );
}

$access = eZSiteAccess::change( $access );

// load siteaccess extensions
eZExtension::activateExtensions( 'access' );

// Now that all extensions are activated and siteaccess has been changed, reset
// all eZINI instances as they may not take into account siteaccess specific settings.
eZINI::resetAllInstances( false );

if( ezpRestDebug::isDebugEnabled() )
{
    $debug = ezpRestDebug::getInstance();
    $debug->updateDebugSettings();
}

$mvcConfig = new ezpMvcConfiguration();

$frontController = new ezpMvcConfigurableDispatcher( $mvcConfig );
$frontController->run();
?>

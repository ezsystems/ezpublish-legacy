<?php
/**
 * File containing the rest bootstrap
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

require 'autoload.php';
require 'rest/rest/classes/lazy.php';

// Below we are setting up a minimal eZ Publish environment from the old index.php
// This is a temporary measure.

// We want PHP to deal with all errors here.
eZDebug::setHandleType( eZDebug::HANDLE_TO_PHP );
$GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );
$ini = eZINI::instance();
eZSys::init( 'index.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) == 'true' );
$uri = eZURI::instance( eZSys::requestURI() );
$GLOBALS['eZRequestedURI'] = $uri;
require_once 'kernel/common/ezincludefunctions.php';

eZExtension::activateExtensions( 'default' );

// setup for changeAccess() needs some methods defined in old index.php
// We disable it, since we dont' want any override settings to change the
// debug settings here
function eZUpdateDebugSettings() {}


require_once "access.php";

$access = eZSiteAccess::match( $uri,
                      eZSys::hostname(),
                      eZSys::serverPort(),
                      eZSys::indexFile() );
$access = eZSiteAccess::change( $access );

// Adding the compat layer for i18n methods, as this is used in some of the
// datatypes in extensions
require_once 'kernel/common/i18n.php';


$mvcConfig = new ezpMvcConfiguration();

$frontController = new ezcMvcConfigurableDispatcher( $mvcConfig );
$frontController->run();
?>

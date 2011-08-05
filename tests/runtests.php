#!/usr/bin/env php
<?php
/**
 * File containing the runtests CLI script
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

set_time_limit( 0 );

require_once 'autoload.php';
require_once 'PHPUnit/Autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Test Runner\n\n" .
                                                         "sets up an eZ Publish testing environment" .
                                                         "\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

// Override INI override folder from settings/override to
// tests/settings to not read local override settings
$ini = eZINI::instance();
$ini->setOverrideDirs( array( array( 'tests/settings', true ) ), 'override' );
$ini->loadCache();

// Be sure to have clean content language data
eZContentLanguage::expireCache();

$script->startup();
// $options = $script->getOptions();
$script->initialize();

// Avoids Fatal error: eZ Publish did not finish its request if die() is used.
eZExecution::setCleanExit();

ezpTestRunner::main();

$script->shutdown();
?>

#!/usr/bin/env php
<?php
/**
 * File containing the runtests CLI script
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

set_time_limit( 0 );

require_once 'autoload.php';

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'tests/toolkit/ezptestrunner.php';

// Exclude the test system from code coverage reports
PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist( getcwd() . '/tests' );

// Whitelist all eZ Publish kernel files
$baseDir = getcwd();
$autoloadArray = include 'autoload/ezp_kernel.php';
foreach ( $autoloadArray as $class => $file )
{
    // Exclude files from the tests directory
    if ( strpos( $file, 'tests' ) !== 0 )
    {
        PHP_CodeCoverage_Filter::getInstance()->addFileToBlacklist( "{$baseDir}/{$file}" );
    }
}

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

$script->startup();
// $options = $script->getOptions();
$script->initialize();

// Avoids Fatal error: eZ Publish did not finish its request if die() is used.
eZExecution::setCleanExit();

ezpTestRunner::main();

$script->shutdown();
?>

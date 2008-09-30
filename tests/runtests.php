#!/usr/bin/env php
<?php
/**
 * File containing the runtests CLI script
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

set_time_limit( 0 );

require_once 'autoload.php';

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'tests/toolkit/ezptestrunner.php';

// Exclude the test system from code coverage reports
PHPUnit_Util_Filter::addDirectoryToFilter( getcwd() . '/tests' );

// Whitelist all eZ Publish kernel files
$baseDir = getcwd();
$autoloadArray = include 'autoload/ezp_kernel.php';
foreach ( $autoloadArray as $class => $file )
{
    // Exclude files from the tests directory
    if ( strpos( $file, 'tests' ) !== 0 )
    {
        PHPUnit_Util_Filter::addFileToWhitelist( "{$baseDir}/{$file}" );
    }
}

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Test Runner\n\n" .
                                                         "sets up an eZ Publish testing environment" .
                                                         "\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();
// $options = $script->getOptions();
$script->initialize();

// Avoids Fatal error: eZ Publish did not finish its request if die() is used.
eZExecution::setCleanExit();

ezpTestRunner::main();

$script->shutdown();
?>
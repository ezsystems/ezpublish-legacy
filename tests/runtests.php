#!/usr/bin/env php
<?php
/**
 * File containing the runtests CLI script
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

set_time_limit( 0 );

require_once 'autoload.php';

if ( !class_exists( 'ezpTestRunner', true ) )
{
    echo "The ezpTestRunner class isn't defined. Are the tests autoloads generated ?\n"
        . "You can generate them using php bin/php/ezpgenerateautoloads.php -s\n";
    exit(1);
}

$version = PHPUnit_Runner_Version::id();

if ( version_compare( $version, '3.7.0' ) == -1 && $version !== '@package_version@' )
{
    echo "PHPUnit 3.7.0 (or later) is required to run this test suite.\n";
    exit(1);
}

try
{
    $runner = ezpTestRunner::instance();
    $runner->run($_SERVER['argv']);
}
catch ( Exception $e )
{
    echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}

?>

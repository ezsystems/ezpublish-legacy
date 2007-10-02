#!/usr/bin/env php
<?php
//
// Created on: <18-Mar-2003 17:06:45 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once( "lib/ezutils/classes/ezextension.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'kernel/classes/ezscript.php' );
include_once( 'lib/compat.php' );

$ini =& eZINI::instance();
$ini->appendOverrideDir( 'tests/settings', true );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Unit Tester\n" .
                                                         "Runs selected unit tests.\n" .
                                                         "\n" .
                                                         "The syntax of SUITE can be one of:\n" .
                                                         "SUITENAME - Run all tests in suite SUITENAME\n" .
                                                         "SUITENAME:TESTNAME - Run only test TESTNAME in suite SUITENAME" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();
$options = $script->getOptions( "",
                                "[suite*]",
                                array( 'compile-directory' => "Where to place compiled files,\ndefault is template/compiled in current cache directory" ) );

/* Use the 'test' site access if none is set,
   this ensures that only ini settings which are good for the
   test is used (e.g override.ini) */
if ( !$script->usedSiteAccess() )
{
    $script->setUseSiteAccess( 'test' );
}

$suiteList = array();
foreach ( $options['arguments'] as $suiteName )
{
    $suiteTestName = false;
    $suiteTestEntryName = false;
    if ( preg_match( "/^([a-zA-Z0-9_]+):([a-zA-Z0-9_]+):([a-zA-Z0-9_]+)/", $suiteName, $matches ) )
    {
        $suiteName = $matches[1];
        $suiteTestName = $matches[2];
        $suiteTestEntryName = strtolower( $suiteTestName . '::' . $matches[3] );
    }
    else if ( preg_match( "/^([a-zA-Z0-9_]+):([a-zA-Z0-9_]+)/", $suiteName, $matches ) )
    {
        $suiteName = $matches[1];
        $suiteTestName = $matches[2];
    }

    if ( !in_array( $suiteName, $suiteList ) )
        $suiteList[] = $suiteName;

    if ( $suiteTestName )
    {
        if ( !isset( $suiteTestMap[$suiteName] ) )
            $suiteTestMap[$suiteName] = array();
        if ( $suiteTestEntryName )
        {
            if ( !isset( $suiteTestMap[$suiteName][$suiteTestName] ) or
                 !is_array( $suiteTestMap[$suiteName][$suiteTestName] ) )
                $suiteTestMap[$suiteName][$suiteTestName] = array();
            $suiteTestMap[$suiteName][$suiteTestName][] = $suiteTestEntryName;
        }
        else
        {
            $suiteTestMap[$suiteName][$suiteTestName] = true;
        }
    }
}

$script->initialize();

if ( count( $suiteList ) == 0 )
{
    $script->showHelp();
    $script->shutdown( 1 );
}

include_once( 'tests/classes/eztestcase.php' );
include_once( 'tests/classes/eztestsuite.php' );
include_once( 'tests/classes/eztestclirunner.php' );

$success = true;

foreach ( $suiteList as $suiteName )
{
    $suitePath = 'tests/' . $suiteName;
    $suiteDefinitionPath = $suitePath . '/testsuite.php';
    if ( file_exists( $suiteDefinitionPath ) )
    {
        unset( $SuiteDefinition );
        include( $suiteDefinitionPath );
        if ( isset( $SuiteDefinition ) )
        {
            $suite = new eZTestSuite( $SuiteDefinition['name'] );
            $testsToRun = array();
            foreach ( $SuiteDefinition['tests'] as $testDefinition )
            {
                $testUnitName = $testDefinition['name'];
                if ( isset( $suiteTestMap[$suiteName] ) )
                {
                    if ( !isset( $suiteTestMap[$suiteName][$testUnitName] ) )
                        continue;
                    $testsToRun = array_merge( $testsToRun, $suiteTestMap[$suiteName][$testUnitName] );
                }
                $testUnitFile = $testDefinition['file'];
                $testUnitPath = $suitePath . '/' . $testUnitFile;
                if ( file_exists( $testUnitPath ) )
                {
                    include_once( $testUnitPath );
                    $testUnitClass = $testDefinition['class'];
                    if ( class_exists( $testUnitClass ) )
                    {
                        $testUnit = new $testUnitClass( $testUnitName );
                        $suite->addUnit( $testUnit );
                    }
                    else
                    {
                        $cli->warning( "Could not find test unit class '" . $cli->stylize( 'emphasize', $testUnitClass ) . "' for test suite " .
                                       $cli->stylize( 'emphasize', $suiteName ) );
                    }
                }
                else
                {
                    $cli->warning( "Could not find a test unit file '" . $cli->stylize( 'emphasize', $testUnitFile ) . "' for test suite " .
                                   $cli->stylize( 'emphasize', $suiteName ) );
                }
            }
            $cli->output( "Test results from suite " . $cli->stylize( 'emphasize', $suiteName ) );
            $runner = new eZTestCLIRunner();
            if ( count( $testsToRun ) == 0 )
                $testsToRun = true;
            $runner->run( $suite, true, $testsToRun );

            if ( !$runner->isSuccessful() )
                $success = false;
        }
        else
        {
            $cli->warning( "Could not find a suite definition for test suite " .
                           $cli->stylize( 'emphasize', $suiteName ) . "\n" .
                           $cli->stylize( 'emphasize', "\$SuiteDefinition" ) . " is missing" );
        }
    }
    else
    {
        $cli->warning( "Could not find a suite definition for test suite " . $cli->stylize( 'emphasize', $suiteName ) . "\nTried $suiteDefinitionPath" );
    }
}

$exitStatus = 0;
if ( !$success )
{
    $cli->output();
    $cli->output( $cli->stylize( 'failure', "Some tests failed" ) );
    $exitStatus = 1;
}

$script->shutdown( $exitStatus );

?>

#!/usr/bin/env php
<?php
//
// Created on: <18-Mar-2003 17:06:45 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "lib/ezutils/classes/ezextension.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'kernel/classes/ezscript.php' );

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

$script->shutdown();

exit( $exitStatus );

?>

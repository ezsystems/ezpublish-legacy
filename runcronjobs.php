#!/usr/bin/env php
<?php
//
// Created on: <18-Mar-2003 17:06:45 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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


// No more than one instance of a cronjob script can be run at any given time. If a script uses more
// time than eZRunCronjobs_MaxScriptExecutionTime, the next instance of it will try to gracefully steal
// the cronjob script mutex. If the process has been running for more than
// two times the eZRunCronjobs_MaxScriptExecutionTime, the original process will be killed.

// Define maximum script execution time to 12 hours
define( 'eZRunCronjobs_MaxScriptExecutionTime', 12*3600 );

// Set a default time zone if none is given. The time zone can be overriden
// in config.php or php.ini.
if ( !ini_get( "date.timezone" ) )
{
    date_default_timezone_set( "UTC" );
}

require 'autoload.php';

eZContentLanguage::setCronjobMode();

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'debug-message' => '',
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$endl = $cli->endlineString();
$webOutput = $cli->isWebOutput();

function help()
{
    $argv = $_SERVER['argv'];
    $cli = eZCLI::instance();
    $cli->output( "Usage: " . $argv[0] . " [OPTION]... [PART]\n" .
                  "Executes eZ Publish cronjobs.\n" .
                  "\n" .
                  "General options:\n" .
                  "  -h,--help          display this help and exit \n" .
                  "  -q,--quiet         do not give any output except when errors occur\n" .
                  "  -s,--siteaccess    selected siteaccess for operations, if not specified default siteaccess is used\n" .
                  "  -d,--debug         display debug output at end of execution\n" .
                  "  -c,--colors        display output using ANSI colors\n" .
                  "  --sql              display sql queries\n" .
                  "  --logfiles         create log files\n" .
                  "  --no-logfiles      do not create log files (default)\n" .
                  "  --no-colors        do not use ANSI coloring (default)\n" );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    global $cronPart;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for cronjob" );
    }
    elseif ( isExtensionSiteaccess( $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using extension siteaccess $siteaccess for cronjob" );

        eZExtension::prependExtensionSiteAccesses( $siteaccess );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

/*
    Look in the ActiveExtensions for $siteaccessName
    We only need to look in ActiveExtensions and not ActiveAccessExtensions
    Return true if siteaccessName exists in an extension, false if not.
*/
function isExtensionSiteaccess( $siteaccessName )
{
    $ini = eZINI::instance();
    $extensionDirectory = $ini->variable( 'ExtensionSettings', 'ExtensionDirectory' );
    $activeExtensions = $ini->variable( 'ExtensionSettings', 'ActiveExtensions' );

    foreach ( $activeExtensions as $extensionName )
    {
        $possibleExtensionPath = $extensionDirectory . '/' . $extensionName . '/settings/siteaccess/' . $siteaccessName;
        if ( file_exists( $possibleExtensionPath ) )
            return true;
    }
    return false;
}

$siteaccess = false;
$debugOutput = false;
$allowedDebugLevels = false;
$useDebugAccumulators = false;
$useDebugTimingpoints = false;
$useIncludeFiles = false;
$useColors = false;
$isQuiet = false;
$useLogFiles = false;
$showSQL = false;
$cronPart = false;

$optionsWithData = array( 's' );
$longOptionsWithData = array( 'siteaccess' );

$readOptions = true;

for ( $i = 1; $i < count( $argv ); ++$i )
{
    $arg = $argv[$i];
    if ( $readOptions and
         strlen( $arg ) > 0 and
         $arg[0] == '-' )
    {
        if ( strlen( $arg ) > 1 and
             $arg[1] == '-' )
        {
            $flag = substr( $arg, 2 );
            if ( in_array( $flag, $longOptionsWithData ) )
            {
                $optionData = $argv[$i+1];
                ++$i;
            }
            if ( $flag == 'help' )
            {
                help();
                exit();
            }
            else if ( $flag == 'siteaccess' )
            {
                changeSiteAccessSetting( $siteaccess, $optionData );
            }
            else if ( $flag == 'debug' )
            {
                $debugOutput = true;
            }
            else if ( $flag == 'quiet' )
            {
                $isQuiet = true;
            }
            else if ( $flag == 'colors' )
            {
                $useColors = true;
            }
            else if ( $flag == 'no-colors' )
            {
                $useColors = false;
            }
            else if ( $flag == 'no-logfiles' )
            {
                $useLogFiles = false;
            }
            else if ( $flag == 'logfiles' )
            {
                $useLogFiles = true;
            }
            else if ( $flag == 'sql' )
            {
                $showSQL = true;
            }
        }
        else
        {
            $flag = substr( $arg, 1, 1 );
            $optionData = false;
            if ( in_array( $flag, $optionsWithData ) )
            {
                if ( strlen( $arg ) > 2 )
                {
                    $optionData = substr( $arg, 2 );
                }
                else
                {
                    $optionData = $argv[$i+1];
                    ++$i;
                }
            }
            if ( $flag == 'h' )
            {
                help();
                exit();
            }
            else if ( $flag == 'q' )
            {
                $isQuiet = true;
            }
            else if ( $flag == 'c' )
            {
                $useColors = true;
            }
            else if ( $flag == 'd' )
            {
                $debugOutput = true;
                if ( strlen( $arg ) > 2 )
                {
                    $levels = explode( ',', substr( $arg, 2 ) );
                    $allowedDebugLevels = array();
                    foreach ( $levels as $level )
                    {
                        if ( $level == 'all' )
                        {
                            $useDebugAccumulators = true;
                            $allowedDebugLevels = false;
                            $useDebugTimingpoints = true;
                            break;
                        }
                        if ( $level == 'accumulator' )
                        {
                            $useDebugAccumulators = true;
                            continue;
                        }
                        if ( $level == 'timing' )
                        {
                            $useDebugTimingpoints = true;
                            continue;
                        }
                        if ( $level == 'include' )
                        {
                            $useIncludeFiles = true;
                        }
                        if ( $level == 'error' )
                            $level = eZDebug::LEVEL_ERROR;
                        else if ( $level == 'warning' )
                            $level = eZDebug::LEVEL_WARNING;
                        else if ( $level == 'debug' )
                            $level = eZDebug::LEVEL_DEBUG;
                        else if ( $level == 'notice' )
                            $level = eZDebug::LEVEL_NOTICE;
                        else if ( $level == 'timing' )
                            $level = eZDebug::EZ_LEVEL_TIMING;
                        $allowedDebugLevels[] = $level;
                    }
                }
            }
            else if ( $flag == 's' )
            {
                changeSiteAccessSetting( $siteaccess, $optionData );
            }
        }
    }
    else
    {
        if ( $cronPart === false )
        {
            $readOptions = false;
            $cronPart = $arg;
        }
    }
}
$script->setUseDebugOutput( $debugOutput );
$script->setAllowedDebugLevels( $allowedDebugLevels );
$script->setUseDebugAccumulators( $useDebugAccumulators );
$script->setUseDebugTimingPoints( $useDebugTimingpoints );
$script->setUseIncludeFiles( $useIncludeFiles );

if ( $webOutput )
    $useColors = true;

$cli->setUseStyles( $useColors );
$script->setDebugMessage( "\n\n" . str_repeat( '#', 36 ) . $cli->style( 'emphasize' ) . " DEBUG " . $cli->style( 'emphasize-end' )  . str_repeat( '#', 36 ) . "\n" );

$script->setUseSiteAccess( $siteaccess );

$script->initialize();
if ( !$script->isInitialized() )
{
    $cli->error( 'Error initializing script: ' . $script->initializationError() . '.' );
    $script->shutdown( 0 );
}

if ( $cronPart )
{
    if ( !$isQuiet )
        print( "Running cronjob part '$cronPart'$endl" );
}


$ini = eZINI::instance( 'cronjob.ini' );
$scriptDirectories = $ini->variable( 'CronjobSettings', 'ScriptDirectories' );

/* Include extension directories */
$extensionDirectories = $ini->variable( 'CronjobSettings', 'ExtensionDirectories' );
$scriptDirectories = array_merge( $scriptDirectories, eZExtension::expandedPathList( $extensionDirectories, 'cronjobs' ) );

$scriptGroup = 'CronjobSettings';
if ( $cronPart !== false )
    $scriptGroup = "CronjobPart-$cronPart";
$scripts = $ini->variable( $scriptGroup, 'Scripts' );

if ( !is_array( $scripts ) or count( $scripts ) == 0 and !$isQuiet )
{
    $cli->notice( 'Notice: No scripts found for execution.' );
    $script->shutdown( 0 );
}

$index = 0;

foreach ( $scripts as $cronScript )
{
    foreach ( $scriptDirectories as $scriptDirectory )
    {
        $scriptFile = $scriptDirectory . '/' . $cronScript;
        if ( file_exists( $scriptFile ) )
            break;
    }
    if ( file_exists( $scriptFile ) )
    {
        if ( !$isQuiet &&
             $index > 0 )
        {
            print( $endl );
        }
        if ( !$isQuiet )
            $cli->output( "Running " . $cli->stylize( 'emphasize', $scriptFile ) );

        eZDebug::addTimingPoint( "Script $scriptFile starting" );
        eZRunCronjobs::runScript( $cli, $scriptFile );
        eZDebug::addTimingPoint( "Script $scriptFile done" );
        ++$index;
        // The transaction check
        $transactionCounterCheck = eZDB::checkTransactionCounter();
        if ( isset( $transactionCounterCheck['error'] ) )
            $cli->error( $transactionCounterCheck['error'] );
    }
}

$script->shutdown();

?>

#!/usr/bin/env php
<?php
//
// Definition of Updateniceurls class
//
// Created on: <03-ñÎ×-2003 16:05:43 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file updateniceurls.php
*/

set_time_limit ( 0 );
// chdir ( '../../../' );

include_once( "lib/ezutils/classes/ezextension.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'debug-message' => '',
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$endl = $cli->endlineString();
$webOutput = $cli->isWebOutput();

function help()
{
    $argv = $_SERVER['argv'];
    $cli =& eZCLI::instance();
    $cli->output( "Usage: " . $argv[0] . " [OPTION]...\n" .
                  "eZ publish nice url updater.\n" .
                  "Will go trough and remake all nice urls\n" .
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
    $cli =& eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for nice url update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
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
                            $level = EZ_LEVEL_ERROR;
                        else if ( $level == 'warning' )
                            $level = EZ_LEVEL_WARNING;
                        else if ( $level == 'debug' )
                            $level = EZ_LEVEL_DEBUG;
                        else if ( $level == 'notice' )
                            $level = EZ_LEVEL_NOTICE;
                        else if ( $level == 'timing' )
                            $level = EZ_LEVEL_TIMING;
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

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$db =& eZDb::instance();
$db->setIsSQLOutputEnabled( $showSQL );

$fetchLimit = 30;
$percentLength = 6;
$timeLength = 12;
$maxColumn = 72 - $percentLength - $timeLength;
$totalChangedNodes = 0;
$totalNodeCount = 0;

$topLevelNodesArray = $db->arrayQuery( 'SELECT node_id FROM ezcontentobject_tree WHERE depth = 1 ORDER BY node_id' );
foreach ( array_keys( $topLevelNodesArray ) as $key )
{
    $topLevelNodeID = $topLevelNodesArray[$key]['node_id'];
    $rootNode =& eZContentObjectTreeNode::fetch( $topLevelNodeID );
    if ( $rootNode->updateURLAlias() )
        ++$totalChangedNodes;
    $done = false;
    $offset = 0;
    $counter = 0;
    $column = 0;
    $changedNodes = 0;
    $nodeCount = $rootNode->subTreeCount( array( 'Limitation' => array() ) );
    $totalNodeCount += $nodeCount + 1;
    $cli->output( "Starting updates for " . $cli->stylize( 'mark', $rootNode->attribute( 'name' ) ) . ", $nodeCount nodes" );
    $mtime = microtime();
    $tTime = explode( " ", $mtime );
    ereg( "0\.([0-9]+)", "" . $tTime[0], $t1 );
    $nodeStartTime = $tTime[1] . "." . $t1[1];
    while ( !$done )
    {
        $nodes =& $rootNode->subTree( array( 'Offset' => $offset,
                                             'Limit' => $fetchLimit,
                                             'Limitation' => array() ) );
        foreach ( array_keys( $nodes ) as $key )
        {
            $node =& $nodes[ $key ];
            $hasChanged = $node->updateURLAlias();
            if ( $hasChanged )
            {
                ++$changedNodes;
                ++$totalChangedNodes;
            }
            $changeCharacters = array( '.', '+', '*' );
            $changeCharacter = '.';
            if ( isset( $changeCharacters[$hasChanged] ) )
                $changeCharacter = $changeCharacters[$hasChanged];
            $cli->output( $changeCharacter, false );
            if ( $column > $maxColumn )
            {
                $mtime = microtime();
                $tTime = explode( " ", $mtime );
                ereg( "0\.([0-9]+)", "" . $tTime[0], $t1 );
                $endTime = $tTime[1] . "." . $t1[1];
                $relTime = ( $endTime - $nodeStartTime ) / $counter;
                $totalTime = ( $relTime * (float)$nodeCount ) - ( $endTime - $nodeStartTime );

                $percent = number_format( ( $counter * 100.0 ) / ( $nodeCount ), 2 );

                $timeLeft = '';
//                 $usedTime = $endTime - $nodeStartTime;
//                 $timeSeconds = (int)( $usedTime % 60 );
//                 $timeMinutes = (int)( ( $usedTime / 60.0 ) % 60 );
//                 $timeHours = (int)( $usedTime / ( 60.0 * 60.0 ) );
//                 $timeLeftArray = array();
//                 if ( $timeHours > 0 )
//                     $timeLeftArray[] = $timeHours . "h";
//                 if ( $timeMinutes > 0 )
//                     $timeLeftArray[] = $timeMinutes . "m";
//                 $timeLeftArray[] = $timeSeconds . "s";
//                 $timeLeft .= implode( " ", $timeLeftArray );

                $timeSeconds = (int)( $totalTime % 60 );
                $timeMinutes = (int)( ( $totalTime / 60.0 ) % 60 );
                $timeHours = (int)( $totalTime / ( 60.0 * 60.0 ) );
                $timeLeftArray = array();
                if ( $timeHours > 0 )
                    $timeLeftArray[] = $timeHours . "h";
                if ( $timeMinutes > 0 )
                    $timeLeftArray[] = $timeMinutes . "m";
                $timeLeftArray[] = $timeSeconds . "s";
                $timeLeft .= implode( " ", $timeLeftArray );

                $cli->output( " " . $percent . "% " . $timeLeft );

                $column = 0;
            }
            else
            {
                ++$column;
            }
            ++$counter;
            flush();
        }
        if ( count( $nodes ) == 0 )
            $done = true;
        unset( $nodes );
        $offset += $fetchLimit;
    }
    if ( $column > 0 )
        $cli->output();
    $cli->output( "Updated " . $cli->stylize( 'emphasize', "$changedNodes/$nodeCount" ) . " for " . $cli->stylize( 'mark', $rootNode->attribute( 'name' ) ) );
    $cli->output();
}

eZURLAlias::expireWildcards();

$cli->output();
$cli->output( "Total update " . $cli->stylize( 'emphasize', "$totalChangedNodes/$totalNodeCount" ) );

$script->shutdown();

?>

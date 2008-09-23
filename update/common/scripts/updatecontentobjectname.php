#!/usr/bin/env php
<?php
//
// Created on: <28-Nov-2002 12:45:40 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

require 'autoload.php';

set_time_limit( 0 );

//include_once( "lib/ezutils/classes/ezextension.php" );
//include_once( "lib/ezutils/classes/ezmodule.php" );
//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

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
    $cli->output( "Usage: " . $argv[0] . " [OPTION]...\n" .
                  "eZ Publish content object name update.\n" .
                  "Goes trough all objects and updates all content object names\n" .
                  "\n" .
                  "General options:\n" .
                  "  -h,--help          display this help and exit \n" .
                  "  -q,--quiet         do not give any output except when errors occur\n" .
                  "  -s,--siteaccess    selected siteaccess for operations, if not specified default siteaccess is used\n" .
                  "  -d,--debug         display debug output at end of execution\n" .
                  "  --db-host=HOST     Use database host HOST\n" .
                  "  --db-user=USER     Use database user USER\n" .
                  "  --db-password=PWD  Use database password PWD\n" .
                  "  --db-database=DB   Use database named DB\n" .
                  "  --db-driver=DRIVER Use database driver DRIVER\n" .
                  "  -c,--colors        display output using ANSI colors\n" .
                  "  --sql              display sql queries\n" .
                  "  --logfiles         create log files\n" .
                  "  --no-logfiles      do not create log files (default)\n" .
                  "  --no-colors        do not use ANSI coloring (default)\n" );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for content object name update" );
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

$dbUser = false;
$dbPassword = false;
$dbHost = false;
$dbName = false;
$dbImpl = false;

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
                $script->shutdown( 0 );
            }
            else if ( $flag == 'siteaccess' )
            {
                changeSiteAccessSetting( $siteaccess, $optionData );
            }
            else if ( preg_match( "/^db-host=(.*)$/", $flag, $matches ) )
            {
                $dbHost = $matches[1];
            }
            else if ( preg_match( "/^db-user=(.*)$/", $flag, $matches ) )
            {
                $dbUser = $matches[1];
            }
            else if ( preg_match( "/^db-password=(.*)$/", $flag, $matches ) )
            {
                $dbPassword = $matches[1];
            }
            else if ( preg_match( "/^db-database=(.*)$/", $flag, $matches ) )
            {
                $dbName = $matches[1];
            }
            else if ( preg_match( "/^db-driver=(.*)$/", $flag, $matches ) )
            {
                $dbImpl = $matches[1];
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
                $script->shutdown( 0 );
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

print( "Updating content object names\n" );

//eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

//include_once( "lib/ezutils/classes/ezmodule.php" );
// eZModule::setGlobalPathList( array( "kernel" ) );
require_once( 'lib/ezutils/classes/ezexecution.php' );
require_once( "lib/ezutils/classes/ezdebug.php" );

//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$db = eZDB::instance();

if ( $dbHost or $dbName or $dbUser or $dbImpl )
{
    $params = array();
    if ( $dbHost !== false )
        $params['server'] = $dbHost;
    if ( $dbUser !== false )
    {
        $params['user'] = $dbUser;
        $params['password'] = '';
    }
    if ( $dbPassword !== false )
        $params['password'] = $dbPassword;
    if ( $dbName !== false )
        $params['database'] = $dbName;
    $db = eZDB::instance( $dbImpl, $params, true );
    eZDB::setInstance( $db );
}

$db->setIsSQLOutputEnabled( $showSQL );

// Get top node
$topNodeArray = eZPersistentObject::fetchObjectList( eZContentObjectTreeNode::definition(),
                                                     null,
                                                     array( 'parent_node_id' => 1,
                                                            'depth' => 1 ) );
$subTreeCount = 0;
foreach ( array_keys ( $topNodeArray ) as $key  )
{
    $subTreeCount += $topNodeArray[$key]->subTreeCount( array( 'Limitation' => false ) );
}

print( "Number of objects to update: $subTreeCount $endl" );

$i = 0;
$dotMax = 70;
$dotCount = 0;
$limit = 50;

foreach ( array_keys ( $topNodeArray ) as $key  )
{
    $node =& $topNodeArray[$key];
    $offset = 0;
    $subTree =& $node->subTree( array( 'Offset' => $offset, 'Limit' => $limit,
                                       'Limitation' => array() ) );
    while ( $subTree != null )
    {
        foreach ( $subTree as $innerNode )
        {
            $object = $innerNode->attribute( 'object' );
            $class = $object->contentClass();
            $object->setName( $class->contentObjectName( $object ) );
            $object->store();
            unset( $object );
            unset( $class );

            // show progress bar
            ++$i;
            ++$dotCount;
            print( "." );
            if ( $dotCount >= $dotMax or $i >= $subTreeCount )
            {
                $dotCount = 0;
                $percent = (float)( ($i*100.0) / $subTreeCount );
                print( " " . $percent . "%" . $endl );
            }
        }
        $offset += $limit;
        unset( $subTree );
        $subTree =& $node->subTree( array( 'Offset' => $offset, 'Limit' => $limit,
                                           'Limitation' => array() ) );
    }
}

print( $endl . "done" . $endl );

$script->shutdown();

?>

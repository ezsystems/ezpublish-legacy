#!/usr/bin/env php
<?php
//
// Definition of Updateniceurls class
//
// Created on: <03-Apr-2003 16:05:43 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*! \file updateniceurls.php
*/

set_time_limit ( 0 );

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish nice url updater.\n\n" .
                                                         "Will go trough and remake all nice urls" .
                                                         "\n" .
                                                         "updateniceurls.php" ),
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-user:][db-password:][db-database:][db-type:|db-driver:][sql]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-driver' => "Database driver",
                                       'db-type' => "Database driver, alias for --db-driver",
                                       'sql' => "Display sql queries"
                                       ) );
$script->initialize();

$dbUser = $options['db-user'] ? $options['db-user'] : false;
$dbPassword = $options['db-password'] ? $options['db-password'] : false;
$dbHost = isset( $options['db-host'] ) && $options['db-host'] ? $options['db-host'] : false;
$dbName = $options['db-database'] ? $options['db-database'] : false;
$dbImpl = $options['db-driver'] ? $options['db-driver'] : false;
$showSQL = $options['sql'] ? true : false;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;
if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
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

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$db =& eZDb::instance();

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
    $db =& eZDB::instance( $dbImpl, $params, true );
    eZDB::setInstance( $db );
}

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
    $rootNode = eZContentObjectTreeNode::fetch( $topLevelNodeID );
    if ( $rootNode->updateURLAlias() )
        ++$totalChangedNodes;
    $done = false;
    $offset = 0;
    $counter = 0;
    $column = 0;
    $changedNodes = 0;
    $nodeCount = $rootNode->subTreeCount( array( 'Limitation' => array(), 'IgnoreVisibility' => true ) );
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
                                             'IgnoreVisibility' => true,
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

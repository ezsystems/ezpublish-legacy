#!/usr/bin/env php
<?php
//
// Definition of Updateniceurls class
//
// Created on: <03-Apr-2003 16:05:43 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish nice url updater.\n\n" .
                                                        "Will go trough and remake all nice urls" .
                                                        "\n" .
                                                        "updateniceurls.php" ),
                                     'use-session' => true,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-user:][db-password:][db-database:][db-type:|db-driver:][sql][fetch-limit:]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-driver' => "Database driver",
                                       'db-type' => "Database driver, alias for --db-driver",
                                       'sql' => "Display sql queries",
                                       'fetch-limit' => "The fetch limit to use when fetching url aliases from the database"
                                       ) );
$script->initialize();

$dbUser = $options['db-user'] ? $options['db-user'] : false;
$dbPassword = $options['db-password'] ? $options['db-password'] : false;
$dbHost = isset( $options['db-host'] ) && $options['db-host'] ? $options['db-host'] : false;
$dbName = $options['db-database'] ? $options['db-database'] : false;
$dbImpl = $options['db-driver'] ? $options['db-driver'] : false;
$showSQL = $options['sql'] ? true : false;
$fetchLimitOption = $options['fetch-limit'] ? $options['fetch-limit'] : 200;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;
if ( $siteAccess )
{
    changeSiteAccessSetting( $siteAccess );
}

function changeSiteAccessSetting( $siteAccess )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $siteAccess) )
    {
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteAccess for nice url update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $siteAccess does not exist, using default siteaccess" );
    }
}

//include_once( 'lib/ezdb/classes/ezdb.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$db = eZDb::instance();

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

//include_once( 'kernel/classes/ezcontentlanguage.php' );
eZContentLanguage::setCronjobMode( true );

$fetchLimit = $fetchLimitOption;
$cli->notice( "Using fetch limit: $fetchLimit" );
$percentLength = 6;
$timeLength = 12;
$maxColumn = 72 - $percentLength - $timeLength;
$totalChangedNodes = 0;
$totalNodeCount = 0;

function displayProgress( $statusCharacter, $startTime, $currentCount, $totalCount, $currentColumn )
{
    global $maxColumn;
    global $cli;

    if ( $statusCharacter !== false )
        $cli->output( $statusCharacter, false );

    if ( $currentColumn > $maxColumn )
    {
        $endTime = microtime( true );
        $relTime = ( $endTime - $startTime ) / $currentCount;
#        $totalTime = ( $relTime * (float)$totalCount ) - ( $endTime - $startTime );
        $totalTime = ( $relTime * (float)($totalCount - $currentCount) );
        $percent = number_format( ( $currentCount * 100.0 ) / ( $totalCount ), 2 );

        $timeSeconds = (int)( $totalTime % 60 );
        $timeMinutes = (int)( ( $totalTime / 60.0 ) % 60 );
        $timeHours = (int)( $totalTime / ( 60.0 * 60.0 ) );
        $timeLeftArray = array();
        if ( $timeHours > 0 )
            $timeLeftArray[] = $timeHours . "h";
        if ( $timeMinutes > 0 )
            $timeLeftArray[] = $timeMinutes . "m";
        $timeLeftArray[] = $timeSeconds . "s";
        $timeLeft = implode( " ", $timeLeftArray );

        $cli->output( " " . $percent . "% " . $timeLeft );

        $currentColumn = 0;
    }
    else
    {
        ++$currentColumn;
    }
    ++$currentCount;
    flush();
    return array( $currentColumn, $currentCount );
}

function fetchMaskByNodeID( $nodeID )
{
    $db = eZDB::instance();
    $sql = "SELECT language_mask FROM ezcontentobject, ezcontentobject_tree
            WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id
            AND   ezcontentobject_tree.node_id = " . (int)$nodeID;
    $rows = $db->arrayQuery( $sql );
    if ( count( $rows ) > 0 )
    {
        return $rows[0]['language_mask'];
    }
    return false;
}

function isAlwaysAvailable( $nodeID )
{
    $mask = fetchMaskByNodeID( $nodeID );
    if ( ($mask & 1) > 0 )
        return true;
    return false;
}

function decodeAction( $destination )
{
    $alwaysAvailable = false;
    if ( preg_match( "#^content/view/full/([0-9]+)$#", $destination, $matches ) )
    {
        $nodeID = $matches[1];
        $action = 'eznode:' . $nodeID;
        $alwaysAvailable = isAlwaysAvailable( $nodeID );
    }
    else
    {
        $action = 'module:' . $destination;
    }
    return array( $action, $alwaysAvailable );
}

function fetchHistoricURLCount()
{
    $db = eZDB::instance();
    $sql = 'SELECT count(*) AS count FROM ezurlalias
            WHERE is_imported = 0 AND is_wildcard = 0 AND forward_to_id = 0';
    $rows = $db->arrayQuery( $sql );
    return $rows[0]['count'];
}

function fetchHistoricRedirectionCount()
{
    $db = eZDB::instance();
    $sql = 'SELECT count(*) AS count FROM ezurlalias
            WHERE is_imported = 0 AND is_wildcard = 0 AND forward_to_id != 0';
    $rows = $db->arrayQuery( $sql );
    return $rows[0]['count'];
}

function fetchHistoricWildcardCount()
{
    $db = eZDB::instance();
    $sql = 'SELECT count(*) AS count FROM ezurlalias
            WHERE is_imported = 0 AND is_wildcard = 1';
    $rows = $db->arrayQuery( $sql );
    return $rows[0]['count'];
}

function fetchHistoricURLChunk( $offset, $fetchLimit )
{
    $db = eZDB::instance();
    $sql = 'SELECT id, source_url, destination_url FROM ezurlalias
            WHERE is_imported = 0 AND is_wildcard = 0 AND forward_to_id = 0';
    $rows = $db->arrayQuery( $sql,
                             array( 'offset' => $offset,
                                    'limit' => $fetchLimit ) );
    return array( $rows, $offset + count( $rows ) );
}

function fetchHistoricRedirectionChunk( $offset, $fetchLimit )
{
    $db = eZDB::instance();
    $sql = 'SELECT id, forward_to_id, source_url, destination_url FROM ezurlalias
            WHERE is_imported = 0 AND is_wildcard = 0 AND forward_to_id != 0';
    $rows = $db->arrayQuery( $sql,
                             array( 'offset' => $offset,
                                    'limit' => $fetchLimit ) );
    return array( $rows, $offset + count( $rows ) );
}

function fetchHistoricWildcardChunk( $offset, $fetchLimit )
{
    $db = eZDB::instance();
    $sql = 'SELECT id, is_wildcard, source_url, destination_url
            FROM ezurlalias WHERE is_imported = 0 AND is_wildcard = 1';
    $rows = $db->arrayQuery( $sql,
                             array( 'offset' => $offset,
                                    'limit' => $fetchLimit ) );
    return array( $rows, $offset + count( $rows ) );
}

function createURLListCondition( $rows )
{
    if ( count( $rows ) == 0 )
        return false;
    $cond = "";
    $start = false;
    $last  = false;
    $ids  = array();
    foreach ( $rows as $row )
    {
        $ids[] = (int)$row['id'];
    }
    sort( $ids );
    $singleIDs = array();
    $betweens  = array();
    foreach ( $ids as $id )
    {
        if ( $last === false )
        {
            $start = $id;
            $last  = $id;
        }
        else if ( $last + 1 != $id )
        {
            if ( $start != $last )
            {
                $betweens[] = "(id BETWEEN $start AND $last)";
            }
            else
            {
                $singleIDs[] = $last;
            }
            $start = $id;
            $last  = $id;
        }
        else
            $last = $id;
    }
    if ( $start != $last )
    {
        $betweens[] = "(id BETWEEN $start AND $last)";
    }
    else
    {
        $singleIDs[] = $last;
    }
    $cond = join( " OR ", $betweens );
    if ( count( $singleIDs ) > 0 )
    {
        if ( $cond != "" )
             $cond .= " OR ";
        $cond .= "id IN (" . join( ",", $singleIDs ) . ")";
    }
    return $cond;
}

function removeURLList( $rows )
{
    if ( count( $rows ) == 0 )
        return;
    $db   = eZDB::instance();
    $cond =  createURLListCondition( $rows );
    $sql  =  "DELETE FROM ezurlalias WHERE $cond";
    $db->query( $sql );
}

function markAsImported( $rows )
{
    if ( count( $rows ) == 0 )
        return;
    $db   = eZDB::instance();
    $cond =  createURLListCondition( $rows );
    $sql  =  "UPDATE ezurlalias SET is_imported = 1 WHERE $cond";
    $db->query( $sql );
}

// Move old historical elements to new table
$rows = $db->arrayQuery( 'SELECT count(*) AS count FROM ezurlalias' );
$urlCount = $rows[0]['count'];
if ( $urlCount > 0 )
{
    $cli->output( "Importing old url aliases" );

    // First move standard urls
    $urlCount = fetchHistoricURLCount();
    $cli->output( "Importing {$urlCount} " . $cli->stylize( 'emphasize', "standard urls" ) );
    $column = $counter = $offset = 0;
    $urlImportStartTime = microtime( true );
    do
    {
        list( $rows, $offset ) = fetchHistoricURLChunk( 0/*$offset*/, $fetchLimit );
        $count = count( $rows );
        foreach ( $rows as $row )
        {
            $source      = $row['source_url'];
            $destination = $row['destination_url'];
            list( $action, $alwaysAvailable ) = decodeAction( $destination );
            eZURLAliasML::storePath( $source, $action,
                                     false, false, $alwaysAvailable );
            list( $column, $counter ) = displayProgress( '.', $urlImportStartTime, $counter, $urlCount, $column );
        }
        markAsImported( $rows );
    } while ( $count > 0 );
    flush();
    if ( $column > 0 )
        $cli->output();

    // Then redirect urls
    $urlCount = fetchHistoricRedirectionCount();
    $cli->output( "Importing {$urlCount} " . $cli->stylize( 'emphasize', "redirections" ) );
    $column = $counter = $offset = 0;
    $urlImportStartTime = microtime( true );
    do
    {
        list( $rows, $offset ) = fetchHistoricRedirectionChunk( 0, $fetchLimit );
        $count = count( $rows );
        foreach ( $rows as $key => $row )
        {
            $forwardToID = (int)$row['forward_to_id'];
            $rows2 = $db->arrayQuery( "SELECT source_url FROM ezurlalias WHERE id = $forwardToID" );
            if ( count( $rows2 ) == 0 )
            {
                // Forwarded item does not exist, try to find one with same destination
                $rows2 = $db->arrayQuery( "SELECT source_url FROM ezurlalias WHERE destination_url = '" . $db->escapeString( $row['destination_url'] ) . "' AND forward_to_id = 0" );
                if ( count( $rows2 ) == 0 )
                {
                    // Did not find forwarded item, mark as error
                    eZDebug::writeError( "Could not find urlalias entry with ID $forwardToID which was referenced by ID " . $row['id'] );
                    list( $column, $counter ) = displayProgress( 'F', $urlImportStartTime, $counter, $urlCount, $column );
                    continue;
                }
            }
            $redirectedSource = $rows2[0]['source_url'];
            eZDebug::writeNotice( $redirectedSource, "redirectedSource" );
            $elements = eZURLAliasML::fetchByPath( $redirectedSource );
            if ( count( $elements ) == 0 )
            {
                // Referenced url does not exist
                eZDebug::writeError( "The referenced path '$redirectedSource' can not be found among the new URL alias entries, url entry ID is " . $row['id'] );
                list( $column, $counter ) = displayProgress( 'E', $urlImportStartTime, $counter, $urlCount, $column );
                continue;
            }

            // Fetch the ID of the element to redirect to.
            $linkID      = $elements[0]->attribute( 'id' );
            $source      = $row['source_url'];
            $destination = $row['destination_url'];
            list( $action, $alwaysAvailable ) = decodeAction( $destination );
            eZURLAliasML::storePath( $source, $action,
                                     false, $linkID, $alwaysAvailable, false,
                                     true, true );
            list( $column, $counter ) = displayProgress( '.', $urlImportStartTime, $counter, $urlCount, $column );
        }
        markAsImported( $rows );
    } while ( $count > 0 );
    flush();
    if ( $column > 0 )
        $cli->output();

    // Then the wildcard changes
    $urlCount = fetchHistoricWildcardCount();
    $cli->output( "Importing {$urlCount} " . $cli->stylize( 'emphasize', "wildcards" ) );
    $column = $counter = $offset = 0;
    $urlImportStartTime = microtime( true );
    do
    {
        list( $rows, $offset ) = fetchHistoricWildcardChunk( 0, $fetchLimit );
        $count = count( $rows );
        foreach ( $rows as $key => $row )
        {
            $wildcardType        = (int)$row['is_wildcard']; // 1 is forward, 2 is direct (alias) for now they are both treated as forwarding/redirect
            $sourceWildcard      = $row['source_url'];
            $destinationWildcard = $row['destination_url'];

            // Validate the wildcards
            if ( !preg_match( "#^(.*)\*$#", $sourceWildcard, $matches ) )
            {
                eZDebug::writeError( "Invalid source wildcard '$sourceWildcard', item is skipped, URL entry ID is " . $row['id'] );
                list( $column, $counter ) = displayProgress( 'S', $urlImportStartTime, $counter, $urlCount, $column );
                continue;
            }
            $fromPath = $matches[1];
            if ( !preg_match( "#^(.*)\{1\}$#", $destinationWildcard, $matches ) )
            {
                eZDebug::writeError( "Invalid destination wildcard '$destinationWildcard', item is skipped, URL entry ID is " . $row['id'] );
                list( $column, $counter ) = displayProgress( 'D', $urlImportStartTime, $counter, $urlCount, $column );
                continue;
            }
            $toPath = $matches[1];

            $elements = eZURLAliasML::fetchByPath( $toPath );
            if ( count( $elements ) == 0 )
            {
                // Referenced url does not exist
                eZDebug::writeError( "The referenced path '$toPath' can not be found among the new URL alias entries, url entry ID is " . $row['id'] );
                list( $column, $counter ) = displayProgress( 'E', $urlImportStartTime, $counter, $urlCount, $column );
                continue;
            }
            // Fetch the ID of the element to redirect to.
            $linkID = $elements[0]->attribute( 'id' );
            $action = $elements[0]->attribute( 'action' );
            $alwaysAvailable = ($elements[0]->attribute( 'lang_mask' ) & 1);
            eZURLAliasML::storePath( $fromPath, $action,
                                     false, $linkID, $alwaysAvailable );
            list( $column, $counter ) = displayProgress( '.', $urlImportStartTime, $counter, $urlCount, $column );
        }
        markAsImported( $rows );
    } while ( $count > 0 );
    flush();
    if ( $column > 0 )
        $cli->output();

//    $cli->output( "Removing urlalias data which have been imported" );
//    $db = eZDB::instance();
//    $db->query( "DELETE FROM ezurlalias WHERE is_imported = 1" ); // Removing all aliases which have been imported

    $rows = $db->arrayQuery( "SELECT count(*) AS count FROM ezurlalias WHERE is_imported = 0" );
    $remaining = $rows[0]['count'];
    if ( $remaining > 0 )
    {
        $cli->output( "There are $remaining remaining URL aliases in the old ezurlalias table, manual cleanup is needed." );
    }

    $cli->output( "Removing old wildcard caches" );
    //include_once( 'kernel/classes/ezcache.php' );
    eZCache::clearByID( 'urlalias' );

    $cli->output( "Importing completed" );
}

// Start updating nodes
$topLevelNodesArray = $db->arrayQuery( 'SELECT node_id FROM ezcontentobject_tree WHERE depth = 1 ORDER BY node_id' );

foreach ( array_keys( $topLevelNodesArray ) as $key )
{
    $topLevelNodeID = $topLevelNodesArray[$key]['node_id'];
    $rootNode = eZContentObjectTreeNode::fetch( $topLevelNodeID );
    if ( $rootNode->updateSubTreePath() )
        ++$totalChangedNodes;
    $done = false;
    $offset = 0;
    $counter = 0;
    $column = 0;
    $changedNodes = 0;
    $nodeCount = $rootNode->subTreeCount( array( 'Limitation' => array(),
                                                 'IgnoreVisibility' => true ) );
    $totalNodeCount += $nodeCount + 1;
    $cli->output( "Starting updates for " . $cli->stylize( 'mark', $rootNode->attribute( 'name' ) ) . ", $nodeCount nodes" );
    $nodeStartTime = microtime( true );
    while ( !$done )
    {
        $nodeList = $rootNode->subTree( array( 'Offset' => $offset,
                                             'Limit' => $fetchLimit,
                                             'IgnoreVisibility' => true,
                                             'Limitation' => array() ) );
        foreach ( array_keys( $nodeList ) as $key )
        {
            $node = $nodeList[ $key ];
            $hasChanged = $node->updateSubTreePath( false );
            if ( $hasChanged )
            {
                ++$changedNodes;
                ++$totalChangedNodes;
            }
            $changeCharacters = array( '.', '+', '*' );
            $changeCharacter = '.';
            if ( isset( $changeCharacters[$hasChanged] ) )
                $changeCharacter = $changeCharacters[$hasChanged];
            list( $column, $counter ) = displayProgress( $changeCharacter, $nodeStartTime, $counter, $nodeCount, $column );
        }
        if ( count( $nodeList ) == 0 )
            $done = true;
        unset( $nodeList );
        $offset += $fetchLimit;
        eZContentObject::clearCache();
    }
    flush();
    if ( $column > 0 )
        $cli->output();
    $cli->output( "Updated " . $cli->stylize( 'emphasize', "$changedNodes/$nodeCount" ) . " for " . $cli->stylize( 'mark', $rootNode->attribute( 'name' ) ) );
    $cli->output();
}

$cli->output();
$cli->output( "Total update " . $cli->stylize( 'emphasize', "$totalChangedNodes/$totalNodeCount" ) );

$script->shutdown();

?>

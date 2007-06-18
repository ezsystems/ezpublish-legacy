#!/usr/bin/env php
<?php
//
// Created on: <19-Jul-2004 10:51:17 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Cache Handler\n" .
                                                         "Allows for easy clearing of Cache files\n" .
                                                         "\n" .
                                                         "./bin/php/ezcache.php --clear-tag=content" ),
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[clear-tag:][clear-id:][clear-all][list-tags][list-ids]",
                                "",
                                array( 'clear-tag' => 'Clears all caches related to a given tag',
                                       'clear-id' => 'Clears all caches related to a given id, separate multiple ids with a comma',
                                       'clear-all' => 'Clears all caches',
                                       'list-tags' => 'Lists all available tags',
                                       'list-ids' => 'Lists all available ids' ) );
$sys = eZSys::instance();

$script->initialize();

include_once( 'kernel/classes/ezcache.php' );

$cacheList = eZCache::fetchList();

if ( $options['list-tags'] )
{
    $tagList = eZCache::fetchTagList( $cacheList );
    if ( $script->verboseOutputLevel() > 0 )
    {
        $cli->output( "The following tags are defined:" );
        $column = 0;
        foreach ( $tagList as $tagName )
        {
            $len = strlen( $tagName );
            if ( $len > $column )
                $column = $len;
        }
        $column += 2;
        foreach ( $tagList as $tagName )
        {
            $cacheEntries = eZCache::fetchByTag( $tagName, $cacheList );
            $cli->output( $cli->stylize( 'emphasize', $tagName ) . ':', false );
            $i = 0;
            foreach ( $cacheEntries as $cacheEntry )
            {
                if ( $i > 0 )
                    $cli->output( str_repeat( ' ', $column ), false );
                else
                    $cli->output( str_repeat( ' ', $column - strlen( $tagName ) - 1 ), false );
                $cli->output( $cacheEntry['name'] );
                ++$i;
            }
        }
    }
    else
    {
        $cli->output( "The following tags are defined: (use --verbose for more details)" );
        $cli->output( $cli->stylize( 'emphasize', implode( ', ', $tagList ) ) );
    }
    return $script->shutdown();
}

if ( $options['list-ids'] )
{
    if ( $script->verboseOutputLevel() > 0 )
    {
        $cli->output( "The following ids are defined:" );
        $column = 0;
        foreach ( $cacheList as $cacheInfo )
        {
            $len = strlen( $cacheInfo['id'] );
            if ( $len > $column )
                $column = $len;
        }
        $column += 2;
        foreach ( $cacheList as $cacheInfo )
        {
            $cli->output( $cli->stylize( 'emphasize', $cacheInfo['id'] ) . ':', false );
            $cli->output( str_repeat( ' ', $column - strlen( $cacheInfo['id'] ) - 1 ), false );
            $cli->output( $cacheInfo['name'] );
        }
    }
    else
    {
        $idList = eZCache::fetchIDList( $cacheList );
        $cli->output( "The following ids are defined: (use --verbose for more details)" );
        $cli->output( $cli->stylize( 'emphasize', implode( ', ', $idList ) ) );
    }
    return $script->shutdown();
}

if ( $options['clear-all'] )
{
    $cli->output( 'Clearing : ', false );
    $i = 0;
    foreach ( $cacheList as $cacheEntry )
    {
        if ( $i > 0 )
            $cli->output( ', ', false );
        $cli->output( $cli->stylize( 'emphasize', $cacheEntry['name'] ), false );
        eZCache::clearItem( $cacheEntry );
        ++$i;
    }
    $cli->output();
    return $script->shutdown();
}

if ( $options['clear-tag'] )
{
    $tagName = $options['clear-tag'];
    $cacheEntries = eZCache::fetchByTag( $tagName, $cacheList );
    $cli->output( 'Clearing ' . $cli->stylize( 'emphasize', $tagName ) . ': ', false );
    $i = 0;
    foreach ( $cacheEntries as $cacheEntry )
    {
        if ( $i > 0 )
            $cli->output( ', ', false );
        $cli->output( $cli->stylize( 'emphasize', $cacheEntry['name'] ), false );
        eZCache::clearItem( $cacheEntry );
        ++$i;
    }
    $cli->output();
}

if ( $options['clear-id'] )
{
    $idName = $options['clear-id'];
    $idList = explode( ',', $idName );
    $missingIDList = array();
    $cacheEntries = array();
    foreach ( $idList as $id )
    {
        $cacheEntry = eZCache::fetchByID( $id, $cacheList );
        if ( $cacheEntry )
        {
            $cacheEntries[] = $cacheEntry;
        }
        else
        {
            $missingIDList[] = $id;
        }
    }
    if ( count( $missingIDList ) > 0 )
    {
        $cli->warning( 'No such cache ID: ' . $cli->stylize( 'emphasize', implode( ', ', $missingIDList ) ) );
        $script->shutdown( 1 );
    }
    $cli->output( 'Clearing ' . $cli->stylize( 'emphasize', $idName ) . ': ', false );
    $i = 0;
    foreach ( $cacheEntries as $cacheEntry )
    {
        if ( $i > 0 )
            $cli->output( ', ', false );
        $cli->output( $cli->stylize( 'emphasize', $cacheEntry['name'] ), false );
        eZCache::clearItem( $cacheEntry );
        ++$i;
    }
    $cli->output();
}

$script->shutdown();

?>

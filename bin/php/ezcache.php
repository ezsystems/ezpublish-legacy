#!/usr/bin/env php
<?php
/**
 * File containing the ezcache.php script.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Cache Handler\n" .
                                                        "Allows for easy clearing of Cache files\n" .
                                                        "\n" .
                                                        "./bin/php/ezcache.php --clear-tag=content" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[clear-tag:][clear-id:][clear-all]" . /*[purge-tag:][purge-id:][purge-all]*/ "[iteration-sleep:][iteration-max:][expiry:][list-tags][list-ids][purge]",
                                "",
                                array( 'clear-tag' => 'Clears all caches related to a given tag',
                                       'clear-id' => 'Clears all caches related to a given id, separate multiple ids with a comma',
                                       'clear-all' => 'Clears all caches',
                                       'purge' => 'Enforces purging of cache items which ensures that specified entries are physically removed (Useful for saving diskspace). Used together with the clear-* options.',
                                       'iteration-sleep' => 'Amount of seconds to sleep between each iteration when performing a purge operation, can be a float.',
                                       'iteration-max' => 'Amount of items to remove in each iteration when performing a purge operation.',
                                       'expiry' => 'Date or relative time which specifies when cache items are to be considered expired, e.g \'now\', \'-2 days\' or \'last monday\'',
                                       'list-tags' => 'Lists all available tags',
                                       'list-ids' => 'Lists all available ids' ) );
$sys = eZSys::instance();

$script->initialize();

$purgeSleep = false;
if ( $options['iteration-sleep'] )
{
    $purgeSleep = (int)($options['iteration-sleep']*1000000); // Turn into microseconds
}
$purgeMax = false;
if ( $options['iteration-max'] )
{
    $purgeMax = (int)$options['iteration-max'];
}
$purgeExpiry = false;
if ( $options['expiry'] )
{
    $expiryText = trim( $options['expiry'] );
    $purgeExpiry = strtotime( $expiryText );
    if ( $purgeExpiry == -1 || $purgeExpiry === false )
    {
        $cli->error( "Invalid date supplied to --expiry, '$expiryText'" );
        $script->shutdown( 1 );
    }
}
$purge = false;
if ( $options['purge'] )
{
    if ( !$options['expiry'] ) // If --expiry is not specified, purge everything
        $purgeExpiry = time();
    $purge = true;
}
$noAction = true;

$cacheList = eZCache::fetchList();

if ( $options['list-tags'] )
{
    $noAction = false;
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
    $script->shutdown( 0 );
}

if ( $options['list-ids'] )
{
    $noAction = false;
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
    $script->shutdown( 0 );
}

function clearItems( $cacheEntries, $cli, $name )
{
    if ( !$name )
        $name = 'All cache';
    $name = $cli->stylize( 'emphasize', $name );
    $cli->output( 'Clearing ' . $name . ': ' );

    checkPaths( $cacheEntries, false );

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

function purgeItems( $cacheEntries, $cli, $name )
{
    global $purgeSleep, $purgeMax, $purgeExpiry;
    if ( !$name )
        $name = 'All cache';
    $name = $cli->stylize( 'emphasize', $name );
    $cli->output( 'Purging ' . $name . ': ' );

    checkPaths( $cacheEntries, true );

    $i = 0;
    foreach ( $cacheEntries as $cacheEntry )
    {
        if ( $i > 0 )
            $cli->output( ', ', false );
        $cli->output( $cli->stylize( 'emphasize', $cacheEntry['name'] ), false );
        eZCache::clearItem( $cacheEntry, true, 'reportProgress', $purgeSleep, $purgeMax, $purgeExpiry );
        ++$i;
    }
    $cli->output();
}

function checkPaths( $cacheEntries, $purge )
{
    global $cli, $script;

    $warnPaths = array();
    foreach ( $cacheEntries as $cacheEntry )
    {
        $absPath = realpath( eZSys::cacheDirectory() . DIRECTORY_SEPARATOR . $cacheEntry['path'] );
        $absPathElementCount = count( explode( DIRECTORY_SEPARATOR, rtrim( $absPath, DIRECTORY_SEPARATOR ) ) );
        // Refuse to delete root directory ('/' or 'C:\')
        if ( $absPath &&
             $absPathElementCount < 2 ) // 2, since one path element ('/foo') produces two exploded elements
        {
            $cli->error( 'Refusing to delete root directory! Please check your cache settings. Path: ' . $absPath );
            $script->shutdown( 1 );
            exit();
        }

        // Warn if the cache entry is not function based, and the path is outside ezp root, and the path has less than 2 elements
        if ( $absPath &&
             ( !$purge || !isset( $cacheEntry['purge-function'] ) ) &&
             !isset( $cacheEntry['function'] ) &&
             $absPathElementCount < 3 && /* 3, since two path elements ('/foo/bar') produce three exploded elements */
             strpos( dirname( $absPath ) . DIRECTORY_SEPARATOR, realpath( eZSys::rootDir() ) . DIRECTORY_SEPARATOR ) === false )
        {
            $warnPaths[] = $absPath;
        }
    }

    if ( count( $warnPaths ) > 0 )
    {
        $cli->warning( 'The following cache paths are outside of the eZ Publish root directory, and have less than 2 path elements. ' .
                       'Are you sure you want to ' . ( $purge ? 'purge' : 'clear' ) . ' them?' );
        foreach ( $warnPaths as $warnPath )
        {
            $cli->output( $warnPath );
        }
        $input = getUserInput( ( $purge ? 'Purge' : 'Clear' ) . '? yes/no:', array( 'yes', 'no' ) );

        if ( $input == 'no' )
        {
            $script->shutdown();
            exit();
        }
    }
}

if ( !function_exists( 'readline' ) )
{
    function readline( $prompt = '' )
        {
            echo $prompt . ' ';
            return trim( fgets( STDIN ) );
        }
}

if ( !function_exists( 'getUserInput' ) )
{
    function getUserInput( $query, $acceptValues )
    {
        $validInput = false;
        while( !$validInput )
        {
            $input = readline( $query );
            if ( $acceptValues === false ||
                 in_array( $input, $acceptValues ) )
            {
                $validInput = true;
            }
        }
        return $input;
    }
}

function reportProgress( $filename, $count )
{
    global $cli;
    static $progress = array( '|', '/', '-', '\\' );
    if ( $count == 0 )
    {
        $cli->output( $cli->storePosition() . " " . $cli->restorePosition(), false );
    }
    else
    {
        $text = array_shift( $progress );
        $cli->output( $cli->storePosition() . $text . $cli->restorePosition(), false );
        $progress[] = $text;
    }
}

if ( $options['clear-all'] )
{
    $noAction = false;
    if ( $purge )
        purgeItems( $cacheList, $cli, false );
    else
        clearItems( $cacheList, $cli, false );
    $script->shutdown( 0 );
}

if ( $options['clear-tag'] )
{
    $noAction = false;
    $tagName = $options['clear-tag'];
    $cacheEntries = eZCache::fetchByTag( $tagName, $cacheList );
    if ( $purge )
        purgeItems( $cacheEntries, $cli, $tagName );
    else
        clearItems( $cacheEntries, $cli, $tagName );
}

$idName = false;
if ( $options['clear-id'] )
{
    $noAction = false;
    $idName = $options['clear-id'];
}

if ( $idName )
{
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
    if ( $options['clear-id'] )
    {
        if ( $purge )
            purgeItems( $cacheEntries, $cli, $idName );
        else
            clearItems( $cacheEntries, $cli, $idName );
    }
    else
    {
        $script->shutdown( 1, "Internal error" );
    }
}

if ( $noAction )
{
    $cli->warning( "To clear caches use one of the options --clear-id, --clear-tag or --clear-all. Use --help option for more details." );
}

$script->shutdown();

?>

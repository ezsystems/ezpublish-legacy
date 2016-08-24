<?php
/**
 * File containing the eZCacheHelper class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class eZCacheHelper
{
    /**
     * @var eZCLI
     */
    private $cli;

    /**
     * @var eZScript
     */
    private $script;

    public function __construct( eZCLI $cli, eZScript $script )
    {
        $this->cli = $cli;
        $this->script = $script;
    }

    public function clearItems( $cacheEntries, $name )
    {
        $this->internalClear( false, $cacheEntries, $name );
    }

    public function purgeItems( $cacheEntries, $name, $purgeSleep, $purgeMax, $purgeExpiry )
    {
        $this->internalClear( true, $cacheEntries, $name, $purgeSleep, $purgeMax, $purgeExpiry );
    }

    private function internalClear( $purge, $cacheEntries, $name, $purgeSleep = null, $purgeMax = null, $purgeExpiry = null )
    {
        $this->cli->output(
            ( $purge ? 'Purging ' : 'Clearing ' ) . $this->cli->stylize( 'emphasize', $name ? $name : 'All cache' ) . ': '
        );

        $warnPaths = array();
        $cachePath = eZSys::globalCachePath();
        foreach ( $cacheEntries as $cacheEntry )
        {
            if ( substr($cacheEntry['path'], 0, strlen($cachePath)) == $cachePath )
            {
                $absPath = realpath( $cacheEntry['path'] );
            }
            else
            {
                $absPath = realpath( eZSys::cacheDirectory() . DIRECTORY_SEPARATOR . $cacheEntry['path'] );
            }
            $absPathElementCount = count( explode( DIRECTORY_SEPARATOR, rtrim( $absPath, DIRECTORY_SEPARATOR ) ) );
            // Refuse to delete root directory ('/' or 'C:\')
            // 2 => since one path element ('/foo') produces two exploded elements
            if ( $absPath && $absPathElementCount < 2 )
            {
                $this->cli->error( 'Refusing to delete root directory! Please check your cache settings. Path: ' . $absPath );
                $this->script->shutdown( 1 );
                exit();
            }

            // Warn if the cache entry is not function based, and the path is outside ezp root, and the path has less than 2 elements
            if (
                $absPath
                && ( !$purge || !isset( $cacheEntry['purge-function'] ) )
                && !isset( $cacheEntry['function'] )
                // 3 => since two path elements ('/foo/bar') produce three exploded elements
                && $absPathElementCount < 3
                && strpos( dirname( $absPath ) . DIRECTORY_SEPARATOR, realpath( eZSys::rootDir() ) . DIRECTORY_SEPARATOR ) === false
            )
            {
                $warnPaths[] = $absPath;
            }
        }

        if ( !empty( $warnPaths ) )
        {
            $this->cli->warning(
                'The following cache paths are outside of the eZ Publish root directory, and have less than 2 path elements. ' .
                'Are you sure you want to ' . ( $purge ? 'purge' : 'clear' ) . ' them?'
            );

            foreach ( $warnPaths as $warnPath )
            {
                $this->cli->output( $warnPath );
            }

            if ( function_exists( "getUserInput" ) )
            {
                $input = getUserInput( ( $purge ? 'Purge' : 'Clear' ) . '? yes/no:', array( 'yes', 'no' ) );
            }
            else
            {
                $validInput = false;
                $readlineExists = function_exists( "readline" );
                while ( !$validInput )
                {
                    if ( $readlineExists )
                    {
                        $input = readline( $query );
                    }
                    else
                    {
                        echo $prompt . ' ';
                        $input = trim( fgets( STDIN ) );
                    }
                    if ( $acceptValues === false || in_array( $input, $acceptValues ) )
                    {
                        $validInput = true;
                    }
                }
            }

            if ( $input === 'no' )
            {
                $this->script->shutdown();
                exit();
            }
        }

        $firstItem = true;
        foreach ( $cacheEntries as $cacheEntry )
        {
            if ( $firstItem )
                $firstItem = false;
            else
                $this->cli->output( ', ', false );

            $this->cli->output( $this->cli->stylize( 'emphasize', $cacheEntry['name'] ), false );

            if ( $purge )
                eZCache::clearItem( $cacheEntry, true, array( $this, 'reportProgress'), $purgeSleep, $purgeMax, $purgeExpiry );
            else
                eZCache::clearItem( $cacheEntry );
        }
        $this->cli->output();
    }

    public function reportProgress( $filename, $count )
    {
        static $progress = array( '|', '/', '-', '\\' );

        if ( $count == 0 )
        {
            $this->cli->output( $this->cli->storePosition() . " " . $this->cli->restorePosition(), false );
        }
        else
        {
            $text = array_shift( $progress );
            $this->cli->output( $this->cli->storePosition() . $text . $this->cli->restorePosition(), false );
            $progress[] = $text;
        }
    }
}

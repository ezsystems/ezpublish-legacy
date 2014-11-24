#!/usr/bin/env php
<?php
/**
 * File containing the script to cleanup files in a DFS setup
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package
 */

require_once 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => "Script for checking database and DFS file consistency",
        'use-session' => false,
        'use-modules' => true,
        'use-extensions' => true
    )
);
$script->startup();
$options = $script->getOptions(
    "[S][B][D][path:][iteration-limit:]", "",
    array(
        "D" => "Delete nonexistent files",
        "S" => "Check files on DFS share against files in the database",
        "B" => "Checks files in database against files on DFS share",
        "path" => "Path to limit checks to (e.g.: var/storage/content - Default: var/)",
        "iteration-limit" => "Amount of items to remove in each iteration when performing a purge operation. Default is all in one iteration.",

    )
);

$script->initialize();



$fileHandler = eZClusterFileHandler::instance();
if ( !$fileHandler instanceof eZDFSFileHandler )
{
    $cli->error( "Your installation does not use DFS clustering." );
    $script->shutdown( 2 );
}

$delete = isset( $options['D'] );
$checkBase = isset( $options['S'] );
$checkDFS = isset( $options['B'] );

if (isset( $options['path'] ) )
{
    $checkPath = trim( $options['path'] );
}
else
{
    $checkPath = eZINI::instance()->variable( 'FileSettings', 'VarDir' );
}
$optIterationLimit =  isset( $options['iteration-limit'] ) ?  (int)$options['iteration-limit'] : false;
$pause = 1000; // microseconds, time to wait between heavy operations

if ( !$checkBase && !$checkDFS )
{
    $cli->warning( 'Specify at least one of -B or -S' );
    $script->showHelp();
    $script->shutdown( 1 );
}

if ( $delete &&  $checkDFS )
{
    $cli->warning( "This script will REMOVE files on the DFS share that are not registered in the database." );
    $cli->warning( "You can run this script without -D switch to just view which files are going to be deleted." );
    $cli->warning( "You have 10 seconds to break the script (press Ctrl-C)." );
    sleep( 10 );
    $cli->output();
}

$cli->output( 'Performing cleanup on directory <' . $checkPath . '>.' );

if ( $checkBase )
{
    if ( $delete )
    {
        $cli->output( 'Deleting database records for files that do not exist on DFS share...' );
    }
    else
    {
        $cli->output( 'Checking files registered in the database...' );
    }

    $loopRun = true;
    $limit = $optIterationLimit ? array( 0, $optIterationLimit ) : false;
    while ( $loopRun )
    {
        try
        {
            $files = $fileHandler->getFileList( false, false, $limit, $checkPath );
        }
        catch ( Exception $e )
        {
            abort( "Database error, aborting.\n" . $e->getMessage() );
        }

        foreach ( $files as $file )
        {
            $fh = eZClusterFileHandler::instance( $file );

            try
            {
                if ( !$fh->exists( true ) )
                {
                    $cli->output( '  - ' . $fh->name() );

                    // expire the file, and purge it
                    if ( $delete )
                    {
                        $fh->delete();
                        $fh->purge();
                    }
                }
            }
            catch ( eZDFSFileHandlerDFSBackendException $e )
            {
                abort( "DFS FS backend error, aborting.\n" . $e->getMessage() );
            }
            catch ( Exception $e )
            {
                abort( "DFS DB backend error, aborting.\n" . $e->getMessage() );
            }
            usleep( $pause );
        }
        if ($limit)
        {
            $limit[0] += $limit[1];
        }
        else
        {
            $loopRun = false;
        }
        unset($files);
    }
    $cli->output( 'Done' );
}

if ( $checkDFS )
{
    if ( $delete )
    {
        $cli->output( 'Deleting the files on the DFS share that are not registered in the database...' );
    }
    else
    {
        $cli->output( 'Checking files on the DFS share...' );
    }

    $dfsBackend = eZDFSFileHandlerBackendFactory::build();
    foreach ( $dfsBackend->getFilesList( $checkPath ) as $filePathName )
    {
        try
        {
            if ( !$fileHandler->fileExists( $filePathName ) )
            {
                $cli->output( '  - ' . $filePathName );
                if ( $delete )
                {
                    $dfsBackend->delete( $filePathName );
                }
            }
        }
        catch ( Exception $e )
        {
            abort( "DFS Backend error, aborting.\n" . $e->getMessage() );
        }
        usleep( $pause );
    }
    $cli->output( 'Done' );
}

$script->shutdown();

function abort( $message )
{
    eZCLI::instance()->error( $message );
    eZScript::instance()->shutdown( 2 );
}

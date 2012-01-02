#!/usr/bin/env php
<?php
/**
 * File containing the script to cleanup files in a DFS setup
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package
 */

require 'autoload.php';

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
    "[S][B][D]", "",
    array(
        "D" => "Delete nonexistent files",
        "S" => "Check files on DFS share against files in the database",
        "B" => "Checks files in database against files on DFS share"
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
$pause = 1000; // microseconds, time to wait between heavy operations

if ( !$checkBase && !$checkDFS )
{
    $cli->output( 'Nothing to do...' );
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
    $files = $fileHandler->getFileList();
    foreach ( $files as $file )
    {
        $fh = eZClusterFileHandler::instance( $file );
        if ( !$fh->exists( true ) )
        {
            $cli->output( '  - ' . $fh->name() );
            if ( $delete );
            // expire the file, and purge it
            {
                $fh->delete();
                $fh->purge();
            }
        }
        usleep( $pause );
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

    $dfsBackend = new eZDFSFileHandlerDFSBackend();
    $base = realpath( $dfsBackend->getMountPoint() );
    $cleanPregExpr = preg_quote( $base, '@' );
    foreach (
        new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator( $base )
        ) as $filename => $current )
    {
        if ( $current->isFile() )
        {
            $relativePath = trim( preg_replace( '@^' . $cleanPregExpr . '@', '', $filename ), '/' );
            if ( !$fileHandler->fileExists( $relativePath ) )
            {
                $cli->output( '  - ' . $relativePath );
                if ( $delete )
                {
                    unlink( $filename );
                }
            }
            usleep( $pause );
        }
    }
    $cli->output( 'Done' );
}

$script->shutdown();

?>

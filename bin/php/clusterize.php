#!/usr/bin/env php
<?php
/**
 * File containing the clusterize.php script.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*

NOTE:

 Please read doc/features/3.8/clustering.txt and set up clustering
 before running this script.

*/

error_reporting( E_ALL | E_NOTICE );

require_once 'autoload.php';

// This code is taken from eZBinaryFile::storedFileInfo()
function filePathForBinaryFile($fileName, $mimeType )
{
    $storageDir = eZSys::storageDirectory();
    list( $group, $type ) = explode( '/', $mimeType );
    $filePath = $storageDir . '/original/' . $group . '/' . $fileName;
    return $filePath;
}

function copyBinaryfilesToDB( $remove )
{
    $cli = eZCLI::instance();
    $fileHandler = eZClusterFileHandler::instance();

    $db = eZDB::instance();

    $cli->output( "Importing binary files to database:");
    $rows = $db->arrayQuery('select filename, mime_type from ezbinaryfile' );

    foreach( $rows as $row )
    {
        $filePath = filePathForBinaryFile( $row['filename'] , $row['mime_type'] );
        $cli->output( "- " . $filePath);
        $fileHandler->fileStore( $filePath, 'binaryfile', $remove );
    }

    $cli->output();
}

function copyMediafilesToDB( $remove )
{
    $cli = eZCLI::instance();
    $fileHandler = eZClusterFileHandler::instance();

    $db = eZDB::instance();

    $cli->output( "Importing media files to database:");
    $rows = $db->arrayQuery('select filename, mime_type from ezmedia' );
    foreach( $rows as $row )
    {
        $filePath = filePathForBinaryFile( $row['filename'] , $row['mime_type'] );
        $cli->output( "- " . $filePath);
        $fileHandler->fileStore( $filePath, 'mediafile', $remove );
    }

    $cli->output();
}

function copyImagesToDB( $remove )
{
    $cli = eZCLI::instance();
    $fileHandler = eZClusterFileHandler::instance();

    $db = eZDB::instance();

    $cli->output( "Importing images and imagealiases files to database:");
    $rows = $db->arrayQuery('select filepath from ezimagefile' );
    foreach( $rows as $row )
    {
        $filePath = $row['filepath'];
        $cli->output( "- " . $filePath);

        $mimeData = eZMimeType::findByFileContents( $filePath );
        $fileHandler->fileStore( $filePath, 'image', $remove, $mimeData['name'] );
    }
}

function copyFilesFromDB( $excludeScopes, $remove )
{
    $cli = eZCLI::instance();
    $fileHandler = eZClusterFileHandler::instance();

    $cli->output( "Exporting files from database:");
    $filePathList = $fileHandler->getFileList( $excludeScopes, true );

    foreach ( $filePathList as $filePath )
    {
        $cli->output( "- " . $filePath );
        eZDir::mkdir( dirname( $filePath ), false, true );
        $fileHandler->fileFetch( $filePath );

        if ( $remove )
            $fileHandler->fileDelete( $filePath );
    }

    $cli->output();
}

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish (un)clusterize\n" .
                                                        "Script for moving var_dir files from " .
                                                        "filesystem to database and vice versa\n" .
                                                        "\n" .
                                                        "./bin/php/clusterize.php" ),
                                     'use-session'    => false,
                                     'use-modules'    => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[u][skip-binary-files][skip-media-files][skip-images][r][n]",
                                "",
                                array( 'u'                 => 'Unclusterize',
                                       'skip-binary-files' => 'Skip copying binary files',
                                       'skip-media-files'  => 'Skip copying media files',
                                       'skip-images'       => 'Skip copying images',
                                       'r'                 => 'Remove files after copying',
                                       'n'                 => 'Do not wait' ) );

$script->initialize();

$clusterize = !isset( $options['u'] );
$remove     =  isset( $options['r'] );
$copyFiles  = !isset( $options['skip-binary-files'] );
$copyMedia  = !isset( $options['skip-media-files'] );
$copyImages = !isset( $options['skip-images'] );
$wait       = !isset( $options['n'] );

if ( $wait )
{
    $warningMsg = sprintf( "This script will now %s your files and/or images %s database.",
                           ( $remove ? "move" : "copy" ),
                           ( $clusterize ? 'to' : 'from' ) );
    $cli->warning( $warningMsg );
    $cli->warning( "You have 10 seconds to break the script (press Ctrl-C)." );
    sleep( 10 );
}

$fileHandler = eZClusterFileHandler::instance();
if ( !is_object( $fileHandler ) )
{
    $cli->error( "Clustering settings specified incorrectly or the chosen file handler is ezfs." );
    $script->shutdown( 1 );
}
// the script will only run if clusterizing is supported by the currently
// configured handler
elseif ( !$fileHandler->requiresClusterizing() )
{
    $message = "The current cluster handler (" . get_class( $fileHandler ) . ") " .
               "doesn't require/support running this script";
    $cli->output( $message );
    $script->shutdown( 0 );
}

// clusterize, from FS => cluster
if ( $clusterize )
{
    if ( $copyFiles )
        copyBinaryfilesToDB( $remove );
    if ( $copyImages )
        copyImagesToDB( $remove );
    if ( $copyMedia )
        copyMediafilesToDB( $remove );
}
// unclusterize, from cluster => FS
else
{
    $excludeScopes = array();
    if ( !$copyFiles )
        $excludeScopes[] = 'binaryfile';
    if ( !$copyImages )
        $excludeScopes[] = 'image';
    if ( !$copyMedia )
        $excludeScopes[] = 'mediafile';

    copyFilesFromDB( $excludeScopes, $remove );
}

$script->shutdown();
?>

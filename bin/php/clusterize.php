#!/usr/bin/env php
<?php
//
// Created on: <30-Mar-2006 06:30:00 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*

NOTE:

 Please read doc/features/3.8/clustering.txt and set up clustering
 before runnning this script.

*/

error_reporting( E_ALL | E_NOTICE );

// require_once( 'lib/ezdb/classes/ezdb.php' );
// require_once( 'lib/ezutils/classes/ezcli.php' );
// require_once( 'lib/ezutils/classes/ezsys.php' );
// require_once( 'kernel/classes/ezscript.php' );
// require_once( 'kernel/classes/ezclusterfilehandler.php' );

require 'autoload.php';

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
    global $cli, $dbFileHandler;

    $db = eZDB::instance();

    $cli->output( "Importing binary files to database:");
    $rows = $db->arrayQuery('select filename, mime_type from ezbinaryfile' );

    foreach( $rows as $row )
    {
        $filePath = filePathForBinaryFile( $row['filename'] , $row['mime_type'] );
        $cli->output( "- " . $filePath);
        $dbFileHandler->fileStore( $filePath, 'binaryfile', $remove );
    }

    $cli->output();
}

function copyMediafilesToDB( $remove )
{
    global $cli, $dbFileHandler;

    $db = eZDB::instance();

    $cli->output( "Importing media files to database:");
    $rows = $db->arrayQuery('select filename, mime_type from ezmedia' );
    foreach( $rows as $row )
    {
        $filePath = filePathForBinaryFile( $row['filename'] , $row['mime_type'] );
        $cli->output( "- " . $filePath);
        $dbFileHandler->fileStore( $filePath, 'mediafile', $remove );
    }

    $cli->output();
}

function copyImagesToDB( $remove )
{
    global $cli, $dbFileHandler;

    $db = eZDB::instance();

    $cli->output( "Importing images and imagealiases files to database:");
    $rows = $db->arrayQuery('select filepath from ezimagefile' );
    //include_once( 'lib/ezutils/classes/ezmimetype.php' );

    foreach( $rows as $row )
    {
        $filePath = $row['filepath'];
        $cli->output( "- " . $filePath);

        $mimeData = eZMimeType::findByFileContents( $filePath );
        $dbFileHandler->fileStore( $filePath, 'image', $remove, $mimeData['name'] );
    }
}

function copyFilesFromDB( $copyFiles, $copyImages, $remove )
{
    global $cli, $dbFileHandler;

    $cli->output( "Exporting files from database:");
    $filePathList = $dbFileHandler->getFileList( !$copyFiles, !$copyImages );

    foreach ( $filePathList as $filePath )
    {
        $cli->output( "- " . $filePath );
        eZDir::mkdir( dirname( $filePath ), false, true );
        $dbFileHandler->fileFetch( $filePath );

        if ( $remove )
            $dbFileHandler->fileDelete( $filePath );
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

$dbFileHandler = eZClusterFileHandler::instance();
if ( !is_object( $dbFileHandler ) || !( $dbFileHandler instanceof eZDBFileHandler ) )
{
    $cli->error( "Clustering settings specified incorrectly or the chosen file handler is ezfs." );
    $script->shutdown( 1 );
}

if ( $clusterize )
{
    if ( $copyFiles )
        copyBinaryfilesToDB( $remove );

    if ( $copyImages )
        copyImagesToDB( $remove );
    if ( $copyMedia )
        copyMediafilesToDB( $remove );
}
else
{
    copyFilesFromDB( $copyFiles, $copyImages, $remove );
}

$script->shutdown();
?>

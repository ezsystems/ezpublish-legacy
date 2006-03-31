#!/usr/bin/env php
<?php
//
// Created on: <30-Mar-2006 06:30:00 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

/*

NOTE:

 Please read doc/features/3.8/clustering.txt and set up clustering
 before runnning this script.

*/

error_reporting( E_ALL | E_NOTICE );

require_once( 'lib/ezdb/classes/ezdb.php' );
require_once( 'lib/ezutils/classes/ezcli.php' );
require_once( 'lib/ezutils/classes/ezsys.php' );
require_once( 'kernel/classes/ezscript.php' );
require_once( 'kernel/classes/clusterfilehandlers/ezdbmysqlfilehandler.php' );

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
    global $cli;

    $dbFileHandler = new eZDBMysqlFileHandler();
    $db =& eZDB::instance();

    $cli->output( "Importing binary files to database:");
    $rows = $db->arrayQuery('select filename, mime_type from ezbinaryfile' );
    eZDebug::writeDebug( $rows, 'files rows' );

    foreach( $rows as $row )
    {
        $filePath = filePathForBinaryFile( $row['filename'] , $row['mime_type'] );
        $cli->output( "- " . $filePath);
        $dbFileHandler->fileStore( $filePath, 'binaryfile', $remove );
    }

    $cli->output();
}

function copyImagesToDB( $remove )
{
    global $cli;

    $dbFileHandler = new eZDBMysqlFileHandler();
    $db =& eZDB::instance();

    $cli->output( "Importing images and imagealiases files to database:");
    $rows = $db->arrayQuery('select filepath from ezimagefile' );

    include_once( 'lib/ezutils/classes/ezmimetype.php' );

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
    global $cli;

    $dbFileHandler = new eZDBMysqlFileHandler();
    $db =& $dbFileHandler->db;

    $cli->output( "Exporting files from database:");
    $query = 'select name, scope from ' . TABLE_METADATA;

    // omit some file types if needed
    $filters = array();
    if ( !$copyFiles )
        $filters[] = "'binaryfile'";
    if ( !$copyImages )
        $filters[] = "'image'";
    if ( $filters )
        $query .= ' WHERE scope NOT IN (' . join( ', ', $filters ) . ')';

    $rslt = mysql_query( $query, $db );

    if ( !$rslt )
    {
        $cli->error( "Cannot fetch list of images to export." );
        eZDebug::writeError( mysql_error( $db ) );
        return;
    }

    while( $row = mysql_fetch_row( $rslt ) )
    {
        $filePath = $row[0];
        $fileScope = $row[1];

        $cli->output( "- " . $filePath);
        eZDir::mkdir( dirname( $filePath ), false, true );
        $dbFileHandler->fileFetch( $filePath );

        if ( $remove )
            $dbFileHandler->fileDelete( $filePath );
    }

    $cli->output();
}

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish (un)clusterize\n" .
                                                         "Script for moving var_dir files from " .
                                                         "filesystem to database and vice versa\n" .
                                                         "\n" .
                                                         "./bin/php/clusterize.php" ),
                                      'use-session'    => false,
                                      'use-modules'    => false,
                                      'use-extensions' => false ) );

$script->startup();

$options = $script->getOptions( "[u][skip-binary-files][skip-images][r]",
                                "",
                                array( 'u'                 => 'Unclusterize',
                                       'skip-binary-files' => 'Skip copying binary files',
                                       'skip-images'       => 'Skip copying images',
                                       'r'                 => 'Remove files after copying'  ) );

$script->initialize();

$clusterize = !isset( $options['u'] );
$remove     =  isset( $options['r'] );
$copyFiles  = !isset( $options['skip-binary-files'] );
$copyImages = !isset( $options['skip-images'] );

if ( $clusterize )
{
    if ( $copyFiles )
        copyBinaryfilesToDB( $remove );

    if ( $copyImages )
        copyImagesToDB( $remove );
}
else
{
    copyFilesFromDB( $copyFiles, $copyImages, $remove );
}

$script->shutdown();
?>

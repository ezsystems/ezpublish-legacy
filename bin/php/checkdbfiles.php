#!/usr/bin/env php
<?php
//
// Created on: <26-Mar-2004 10:31:14 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$dbTypes = array();
$dbTypes[] = 'mysql';
$dbTypes[] = 'postgresql';

$branches = array();
$branches[] = '3.0';
$branches[] = '3.1';
$branches[] = '3.2';
$branches[] = '3.3';
$branches[] = '3.4';

/********************************************************
*** NOTE: The following arrays do not follow the
***       coding standard, the reason for this is
***       to make it easy to merge any changes between
***       the various eZ publish branches.
*********************************************************/

$versions = array();
$versions30 = array( 'stable' => array( array( '2.9.7', '3.0-1' ),
                                        array( '3.0-1', '3.0-2' ) ),
                     'info_files' => true );
$versions31 = array( 'unstable' => array( array( '3.0-2', '3.1.0-1' ),
                                          array( '3.1.0-1', '3.1.0-2' ) ),
                     'stable' => array( array( '3.1.0-2', '3.1-1' ) ),
                     'info_files' => true );
$versions32 = array( 'unstable' => array( array( '3.1-1', '3.2.0-1' ),
                                          array( '3.2.0-1', '3.2.0-2' ),
                                          array( '3.2.0-2', '3.2-1' ) ),
                     'unstable_subdir' => 'unstable',
                     'stable' => array( array( '3.1-1', '3.2-1' )
                                        ,array( '3.2-1', '3.2-2' )
                                        ,array( '3.2-2', '3.2-3' )
                                        ,array( '3.2-3', '3.2-4' )
                                        ,array( '3.2-4', '3.2-5' )
                                        ,array( '3.2-5', '3.2-6' )
                                        ) );
$versions33 = array( 'unstable' => array( array( '3.2-3', '3.3.0-1' ),
                                          array( '3.3.0-1', '3.3.0-2' ),
                                          array( '3.3.0-2', '3.3-1' ) ),
                     'unstable_subdir' => 'unstable',
                     'stable' => array( array( '3.2-4', '3.3-1' )
                                        ,array( '3.3-1', '3.3-2' )
                                        ,array( '3.3-2', '3.3-3' )
                                        ,array( '3.3-3', '3.3-4' )
                                        ,array( '3.3-4', '3.3-5' )
                                        ,array( '3.3-5', '3.3-6' )
                                        ) );
$versions34 = array( 'unstable' => array( array( '3.3-3', '3.4.0alpha1' )
                                          ,array( '3.4.0alpha1', '3.4.0alpha2' )
                                          ,array( '3.4.0alpha2', '3.4.0alpha3' )
                                          ,array( '3.4.0alpha3', '3.4.0alpha4' )
                                          ,array( '3.4.0alpha4', '3.4.0beta1' )
                                          ,array( '3.4.0beta1', '3.4.0beta2' )
                                          ,array( '3.4.0beta2', '3.4.0' )
                                          ),
                     'unstable_subdir' => 'unstable',
                     'stable' => array( array( '3.3-5', '3.4.0' )
		                                ,array( '3.4.0', '3.4.1' )
		                                ,array( '3.4.1', '3.4.2' )
		                                ,array( '3.4.2', '3.4.3' )
		                                ,array( '3.4.3', '3.4.4' )
                                        ) );
$versions['3.0'] = $versions30;
$versions['3.1'] = $versions31;
$versions['3.2'] = $versions32;
$versions['3.3'] = $versions33;
$versions['3.4'] = $versions34;

$fileList = array();
$missingFileList = array();
$scannedDirs = array();

function handleVersionList( $basePath, $subdir,
                            &$fileList, &$missingFileList, &$scannedDirs,
                            $useInfoFiles, $versionList )
{
    $updatePath = $basePath . $subdir;
    if ( is_dir( $updatePath ) )
    {
        if ( !in_array( $updatePath, $scannedDirs ) )
        {
            $dh = opendir( $updatePath );
            if ( $dh )
            {
                while ( ( $file = readdir( $dh ) ) !== false )
                {
                    if ( $file == '.' or
                         $file == '..' or
                         substr( $file, strlen( $file ) - 1, 1 ) == '~' or
                         is_dir( $updatePath . '/' . $file ) )
                        continue;
                    $fileList[] = $updatePath . '/' . $file;
                }
                closedir( $dh );
            }
            $fileList = array_unique( $fileList );
            $scannedDirs[] = $updatePath;
        }
    }
    foreach ( $versionList as $versionEntry )
    {
        $from = $versionEntry[0];
        $to = $versionEntry[1];
        $dir = $updatePath;
        $file = $dir . '/dbupdate-' . $from . '-to-' . $to . '.sql';
        $fileList = array_diff( $fileList, array( $file ) );
        if ( !file_exists( $file ) )
        {
            $missingFileList[] = $file;
        }
        if ( $useInfoFiles )
        {
            $infoFile = $dir . '/dbupdate-' . $from . '-to-' . $to . '.info';
            $fileList = array_diff( $fileList, array( $infoFile ) );
            if ( !file_exists( $infoFile ) )
            {
                $missingFileList[] = $infoFile;
            }
        }
    }
}

foreach ( $dbTypes as $dbType )
{
    foreach ( $branches as $branch )
    {
        $basePath = 'update/database/' . $dbType . '/' . $branch;
        $versionList = $versions[$branch];
        $useInfoFiles = false;
        if ( isset( $versionList['info_files'] ) )
        {
            $useInfoFiles = $versionList['info_files'];
        }
        if ( isset( $versionList['unstable'] ) )
        {
            $subdir = false;
            if ( isset( $versionList['unstable_subdir'] ) )
            {
                $subdir = '/' . $versionList['unstable_subdir'];
            }
            handleVersionList( $basePath, $subdir,
                               $fileList, $missingFileList, $scannedDirs,
                               $useInfoFiles, $versionList['unstable'] );
        }
        if ( isset( $versionList['stable'] ) )
        {
            $subdir = false;
            if ( isset( $versionList['stable_subdir'] ) )
            {
                $subdir = '/' . $versionList['stable_subdir'];
            }
            handleVersionList( $basePath, $subdir,
                               $fileList, $missingFileList, $scannedDirs,
                               $useInfoFiles, $versionList['stable'] );
        }
    }
}

if ( count( $missingFileList ) > 0 or
     count( $fileList ) > 0 )
{
    if ( count( $missingFileList ) > 0 )
    {
        foreach ( $missingFileList as $file )
        {
            print( '! ' . $file . "\n" );
        }
    }

    if ( count( $fileList ) > 0 )
    {
        foreach ( $fileList as $file )
        {
            print( '? ' . $file . "\n" );
        }
    }
    exit( 1 );
}

?>

<?php
//
// Created on: <28-May-2007 17:44:41 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.5.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
// SOFTWARE LICENSE: eZ Proprietary Use License v1.0
// NOTICE: >
//   This source file is part of the eZ Publish (tm) CMS and is
//   licensed under the terms and conditions of the eZ Proprietary
//   Use License v1.0 (eZPUL).
// 
//   A copy of the eZPUL was included with the software. If the
//   license is missing, request a copy of the license via email
//   at eZPUL-v1.0@ez.no or via postal mail at
//     Attn: Licensing Dept. eZ Systems AS, Klostergata 30, N-3732 Skien, Norway
// 
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! Cronjob to remove empty directories in result of having file.ini [CleanupEmptyDirectories] DeferToCronjob = enabled
*/

if ( !$isQuiet )
    $cli->output( "Starting processing pending empty directories cleanups" );

$db = eZDB::instance();

$offset = 0;
$limit = 20;

do
{
    $deleteParams = array();

    $rows = $db->arrayQuery( "SELECT DISTINCT param FROM ezpending_actions WHERE action = 'cleanupEmptyDirectories'",
                                array( 'limit' => $limit,
                                       'offset' => $offset ) );
    if ( !$rows || ( empty( $rows ) ) )
        break;

    foreach ( $rows as $row )
    {
        $dir = $row['param'];

        $dirElements = explode( '/', $dir );
        if ( count( $dirElements ) == 0 )
            continue;
        $currentDir = $dirElements[0];
        if ( !file_exists( $currentDir ) and $currentDir != "" )
            continue;

        for ( $i = count( $dirElements ); $i > 0; --$i )
        {
            $dirpath = implode( '/', array_slice( $dirElements, 0, $i ) );
            if ( file_exists( $dirpath ) and
                 is_dir( $dirpath ) )
            {
                $rmdirStatus = @rmdir( $dirpath );
                if ( !$rmdirStatus )
                    break;
            }
        }

        $deleteParams[] = $dir;
    }

    if ( !empty( $deleteParams ) )
    {
        $db->begin();
        $db->query( "DELETE FROM ezpending_actions WHERE action='cleanupEmptyDirectories' AND param IN ( '" . implode( "','", $deleteParams ) . "' )" );
        $db->commit();
    }
    else
    {
        $offset += $limit;
    }
} while ( true );

if ( !$isQuiet )
    $cli->output( "Done" );

?>

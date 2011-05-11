<?php
//
// Created on: <28-May-2007 17:44:41 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
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

/*! \file
*/

$cli->output( "Starting processing pending static cache cleanups" );

$db = eZDB::instance();

$offset = 0;
$limit = 20;

do
{
    $deleteParams = array();
    $markInvalidParams = array();
    $fileContentCache = array();

    $rows = $db->arrayQuery( "SELECT DISTINCT param FROM ezpending_actions WHERE action = 'static_store'",
                                array( 'limit' => $limit,
                                       'offset' => $offset ) );
    if ( !$rows || ( empty( $rows ) ) )
        break;

    foreach ( $rows as $row )
    {
        $param = $row['param'];
        $paramList = explode( ',', $param );
        $source = $paramList[1];
        $destination = $paramList[0];
        $invalid = isset( $paramList[2] ) ? $paramList[2] : null;

        if ( !isset( $fileContentCache[$source] ) )
        {
            $cli->output( "Fetching URL: $source" );

            $fileContentCache[$source] = eZHTTPTool::getDataByURL( $source, false, eZStaticCache::USER_AGENT );
        }

        if ( $fileContentCache[$source] === false )
        {
            $cli->error( "Could not grab content from \"$source\", is the hostname correct and Apache running?" );

            if ( $invalid !== null )
            {
                $deleteParams[] = $param;

                continue;
            }

            $markInvalidParams[] = $param;
        }
        else
        {
            eZStaticCache::storeCachedFile( $destination, $fileContentCache[$source] );

            $deleteParams[] = $param;
        }
    }

    if ( !empty( $markInvalidParams ) )
    {
        $db->begin();
        $db->query( "UPDATE ezpending_actions SET param=( " . $db->concatString( array( "param", "',invalid'" ) ) . " ) WHERE param IN ( '" . implode( "','", $markInvalidParams ) . "' )" );
        $db->commit();
    }

    if ( !empty( $deleteParams ) )
    {
        $db->begin();
        $db->query( "DELETE FROM ezpending_actions WHERE action='static_store' AND param IN ( '" . implode( "','", $deleteParams ) . "' )" );
        $db->commit();
    }
    else
    {
        $offset += $limit;
    }
} while ( true );

$cli->output( "Done" );

?>

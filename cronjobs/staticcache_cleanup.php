<?php
//
// Created on: <28-May-2007 17:44:41 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*! \file staticcache_cleanup.php
*/

//include_once( 'lib/ezdb/classes/ezdb.php' );
//include_once( 'kernel/classes/ezstaticcache.php' );

if ( !$isQuiet )
{
    $cli->output( "Starting processing pending static cache cleanups" );
}

$db = eZDB::instance();

$offset = 0;
$limit = 20;
$doneDestList = array();
$fileContentCache = array();

while( true )
{
    $entries = $db->arrayQuery( "SELECT param FROM ezpending_actions WHERE action = 'static_store'",
                                array( 'limit' => $limit,
                                       'offset' => $offset ) );
    $inSQL = '';

    if ( is_array( $entries ) and count( $entries ) )
    {
        $db->begin();
        foreach ( $entries as $entry )
        {
            $param = $entry['param'];
            $destination = explode( ',', $param );
            $source = $destination[1];
            $destination = $destination[0];
            $success = false;

            if ( !isset( $doneDestList[$destination] ) )
            {
                if ( !isset( $fileContentCache[$source] ) )
                {
                    if ( !$isQuiet )
                    {
                        $cli->output( "\tFetching URL: $source" );
                    }
                    $fileContentCache[$source] = file_get_contents( $source );
                }
                if ( $fileContentCache[$source] === false )
                {
                    $cli->output( "\tCould not grab content, is the hostname correct and Apache running?" );
                }
                else
                {
                    eZStaticCache::storeCachedFile( $destination, $fileContentCache[$source] );
                    $doneDestList[$destination] = 1;
                    $success = true;
                }
            }
            else
            {
                $success = true;
            }

            if ( $success )
            {
                if ( $inSQL != '' )
                {
                    $inSQL .= ', ';
                }
                $inSQL .= '\'' . $param . '\'';
            }
        }

        $db->query( "DELETE FROM ezpending_actions WHERE action='static_store' AND param IN ($inSQL)" );
        $db->commit();
    }
    else
    {
        break; // No valid result from ezpending_actions
    }
}

if ( !$isQuiet )
{
    $cli->output( "Done" );
}

?>

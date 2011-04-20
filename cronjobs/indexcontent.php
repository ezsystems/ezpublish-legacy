<?php
//
// Created on: <18-Mar-2004 17:12:43 dr>
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

$cli->output( "Starting processing pending search engine modifications" );

$contentObjects = array();
$db = eZDB::instance();

$offset = 0;
$limit = 50;

$searchEngine = eZSearch::getEngine();

if ( !is_object( $searchEngine ) )
{
    $cli->error( "The configured search engine does not implement the ezpSearchEngine interface or can't be found." );
    $script->shutdown( 1 );
}

$needRemoveWithUpdate = $searchEngine->needRemoveWithUpdate();

while( true )
{
    $entries = $db->arrayQuery(
        "SELECT DISTINCT param FROM ezpending_actions WHERE action = 'index_object' ORDER BY created",
        array( 'limit' => $limit, 'offset' => $offset )
    );

    if ( is_array( $entries ) && count( $entries ) != 0 )
    {
        foreach ( $entries as $entry )
        {
            $objectID = (int)$entry['param'];

            $cli->output( "\tIndexing object ID #$objectID" );
            $db->begin();
            $object = eZContentObject::fetch( $objectID );
            $removeFromPendingActions = true;
            if ( $object )
            {
                if ( $needRemoveWithUpdate )
                {
                    $searchEngine->removeObject( $object, false );
                }
                $removeFromPendingActions = $searchEngine->addObject( $object, false );
            }

            if ( $removeFromPendingActions )
            {
                $db->query( "DELETE FROM ezpending_actions WHERE action = 'index_object' AND param = '$objectID'" );
            }
            else
            {
                $cli->warning( "\tFailed indexing object ID #$objectID, keeping it in the queue." );
                // Increase the offset to skip failing objects
                ++$offset;
            }

            $db->commit();
        }

        $searchEngine->commit();
        // clear object cache to conserve memory
        eZContentObject::clearCache();
    }
    else
    {
        break; // No valid result from ezpending_actions
    }
}

$cli->output( "Done" );

?>

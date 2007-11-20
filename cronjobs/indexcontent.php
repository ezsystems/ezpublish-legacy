<?php
//
// Created on: <18-Mar-2004 17:12:43 dr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file indexcontent.php
*/

//include_once( 'kernel/classes/ezsearch.php' );
//include_once( 'kernel/classes/ezcontentobject.php' );
//include_once( 'lib/ezdb/classes/ezdb.php' );

if ( !$isQuiet )
{
    $cli->output( "Starting processing pending search engine modifications" );
}

$contentObjects = array();
$db = eZDB::instance();

$offset = 0;
$limit = 50;

while( true )
{
    $entries = $db->arrayQuery( "SELECT param FROM ezpending_actions WHERE action = 'index_object'",
                                array( 'limit' => $limit,
                                       'offset' => $offset ) );
    $inSQL = '';

    if ( is_array( $entries ) && count( $entries ) != 0 )
    {
        $db->begin();
        foreach ( $entries as $entry )
        {
            $objectID = $entry['param'];

            if ( $inSQL != '' )
            {
                $inSQL .= ', ';
            }
            $inSQL .=(int) $objectID;

            $cli->output( "\tIndexing object ID #$objectID" );
            $object = eZContentObject::fetch( $objectID );
            if ( $object )
            {
                eZSearch::removeObject( $object );
                eZSearch::addObject( $object );
            }
        }

        $db->query( "DELETE FROM ezpending_actions WHERE action = 'index_object' AND param IN ($inSQL)" );
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

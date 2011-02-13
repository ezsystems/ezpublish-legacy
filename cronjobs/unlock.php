<?php
//
// Created on: <10-Mar-09 11:48:24 jr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

$cli->output( 'Fetching objects with status : "locked"' );

$lockedObjectIDList = fetchLockedObjects();

if( !$lockedObjectIDList )
{
    $cli->output( 'No locked objects.' );
    $cli->output( 'Done' );
    return;
}

foreach( $lockedObjectIDList as $lockedContentObjectID )
{
    $object = eZContentObject::fetch( $lockedContentObjectID );

    $cli->output( 'Removing lock of '                                       , false );
    $cli->output( $cli->stylize( 'emphasize', $object->attribute( 'name' ) ), false );
    $cli->output( ' ... '                                                   , false );

    $status = unlockObject( $lockedContentObjectID );

    $statusString = 'Failed';
    $statusColor  = 'red';

    if( $status )
    {
        $statusString = 'Success';
        $statusColor  = 'green';
    }

    $cli->output( $cli->stylize( $statusColor, $statusString ) );
}

$cli->output( 'Done' );

function fetchLockedObjects()
{
    $db = eZDB::instance();
    $sql = "SELECT ezcobj_state_link.contentobject_id
            FROM ezcobj_state_link, ezcobj_state
            WHERE ezcobj_state_link.contentobject_state_id = ezcobj_state.id
              AND ezcobj_state.identifier = 'locked'";

    $rows = $db->arrayQuery( $sql );

    if( $rows )
    {
        $contentObjectIDList = array();
        foreach( $rows as $row )
            $contentObjectIDList[] = $row['contentobject_id'];

        return $contentObjectIDList;
    }

    return false;
}

function unlockObject( $contentObjectID )
{
    $db  = eZDB::instance();
    $sql = 'UPDATE ezcobj_state_link
            SET contentobject_state_id = 1
            WHERE contentobject_id       = '. $db->escapeString( $contentObjectID ) .'
             AND  contentobject_state_id = 2';

    return $db->query( $sql );
}
?>

<?php
/**
 * File containing the unlock.php cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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

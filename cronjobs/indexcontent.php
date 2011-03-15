<?php
/**
 * File containing the indexcontent.php cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/*! \file
*/

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
    $entries = $db->arrayQuery( "SELECT DISTINCT param FROM ezpending_actions WHERE action = 'index_object'",
                                array( 'limit' => $limit,
                                       'offset' => $offset ) );

    if ( is_array( $entries ) && count( $entries ) != 0 )
    {
        foreach ( $entries as $entry )
        {
            $objectID = (int)$entry['param'];

            $cli->output( "\tIndexing object ID #$objectID" );
            $db->begin();
            $object = eZContentObject::fetch( $objectID );
            if ( $object )
            {
                eZSearch::removeObject( $object );
                eZSearch::addObject( $object );
            }
            $db->query( "DELETE FROM ezpending_actions WHERE action = 'index_object' AND param = '$objectID'" );
            $db->commit();
        }
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

<?php
/**
 * File containing the staticcache_cleanup.php cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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

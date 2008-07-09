#!/usr/bin/env php
<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 3.9.x
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

if ( !function_exists( 'readline' ) )
{
    function readline( $prompt = '' )
    {
        echo $prompt . ' ';
        return trim( fgets( STDIN ) );
    }
}

include_once( 'kernel/classes/ezscript.php' );
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'lib/ezlocale/classes/ezdatetime.php' );
include_once( 'kernel/classes/ezcontentobject.php' );

$cli =& eZCLI::instance();

$scriptSettings = array();
$scriptSettings['description'] = 'Fix non-unique usage of content object remote ID\'s';
$scriptSettings['use-session'] = false;
$scriptSettings['use-modules'] = false;
$scriptSettings['use-extensions'] = false;

$script =& eZScript::instance( $scriptSettings );
$script->startup();

$config = '';
$argumentConfig = '';
$optionHelp = false;
$arguments = false;
$useStandardOptions = true;

$options = $script->getOptions( $config, $argumentConfig, $optionHelp, $arguments, $useStandardOptions );
$script->initialize();

$db =& eZDB::instance();

$nonUniqueRemoteIDDataList = $db->arrayQuery( 'SELECT remote_id, COUNT(*) AS cnt FROM ezcontentobject GROUP BY remote_id HAVING COUNT(*) > 1' );

$nonUniqueRemoteIDDataListCount = count( $nonUniqueRemoteIDDataList );

$cli->output( '' );
$cli->output( "Found $nonUniqueRemoteIDDataListCount non-unique content object remote IDs." );
$cli->output( '' );

foreach ( $nonUniqueRemoteIDDataList as $nonUniqueRemoteIDData )
{
    $action = readline( "Remote ID '$nonUniqueRemoteIDData[remote_id]' is used for $nonUniqueRemoteIDData[cnt] different content objects. Do you want to see the details (d) or do you want this inconsistency to be fixed automatically (a) ?" );

    while ( !in_array( $action, array( 'a', 'd' ) ) )
    {
        $action = readline( 'Invalid option. Type either d for details or a to fix automatically.' );
    }

    switch ( $action )
    {
        case 'd':
        {
            $escapedRemoteID = $db->escapeString( $nonUniqueRemoteIDData['remote_id'] );

            $sql = "SELECT o.id, t.path_identification_string, o.published
                    FROM ezcontentobject_tree t, ezcontentobject o
                    WHERE o.id=t.contentobject_id
                      AND o.remote_id='$escapedRemoteID'
                      AND t.node_id=t.main_node_id
                    ORDER BY o.published asc";
            $rows = $db->arrayQuery( $sql );

            $cli->output( '' );
            $cli->output( 'Select the number of the content object that you want to keep the current remote ID. The other listed content objects will get a new one.' );
            $cli->output( '' );

            foreach ( $rows as $i => $row )
            {
                $dateTime = new eZDateTime( $row['published'] );
                $formattedDateTime = $dateTime->toString( true );
                $cli->output( "$i) $row[path_identification_string] (object ID: $row[id], published: $formattedDateTime )" );
                $cli->output( '' );
            }

            do {
                $skip = readline( 'Number of object that should keep the current remote ID: ' );
            } while ( !array_key_exists( $skip, $rows ) );
        } break;

        case 'a':
        default:
        {
            $skip = 0;
        }
    }

    $contentObjects = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                           null,
                                                           array( 'remote_id' => $nonUniqueRemoteIDData['remote_id'] ),
                                                           array( 'published' => 'asc' ) );
    foreach ( $contentObjects as $i => $contentObject )
    {
        if ( $i == $skip )
        {
            continue;
        }

        $newRemoteID = md5( (string)mt_rand() . (string)time() );
        $contentObject->setAttribute( 'remote_id', $newRemoteID );
        $contentObject->store();
    }

    $cli->output( '' );
    $cli->output( '' );
}

$script->shutdown( 0 );

?>
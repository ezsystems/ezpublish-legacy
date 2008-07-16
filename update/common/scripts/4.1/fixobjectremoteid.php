#!/usr/bin/env php
<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

require 'autoload.php';

$cli = eZCLI::instance();

$scriptSettings = array();
$scriptSettings['description'] = 'Fix non-unique usage of content object remote ID\'s';
$scriptSettings['use-session'] = false;
$scriptSettings['use-modules'] = false;
$scriptSettings['use-extensions'] = false;

$script = eZScript::instance( $scriptSettings );
$script->startup();

$config = '[mode:]';
$argumentConfig = '';
$optionHelp = array( 'mode' => "the fixing mode to use, either d (detailed) or a (automatic)" );
$arguments = false;
$useStandardOptions = true;

$options = $script->getOptions( $config, $argumentConfig, $optionHelp, $arguments, $useStandardOptions );
$script->initialize();

if ( isset( $options['mode'] ) )
{
    if ( !in_array( $options['mode'], array( 'a', 'd' ) ) )
    {
        $script->shutdown( 1, 'Invalid mode. Use either d for detailed or a for automatic.' );
    }

    $mode = $options['mode'];
}
else
{
    $mode = false;
}

$db = eZDB::instance();

$nonUniqueRemoteIDDataList = $db->arrayQuery( 'SELECT remote_id, COUNT(*) AS cnt FROM ezcontentobject GROUP BY remote_id HAVING COUNT(*) > 1' );

$nonUniqueRemoteIDDataListCount = count( $nonUniqueRemoteIDDataList );

$cli->output( '' );
$cli->output( "Found $nonUniqueRemoteIDDataListCount non-unique content object remote IDs." );
$cli->output( '' );

foreach ( $nonUniqueRemoteIDDataList as $nonUniqueRemoteIDData )
{
    if ( $mode )
    {
        $cli->output( "Remote ID '$nonUniqueRemoteIDData[remote_id]' is used for $nonUniqueRemoteIDData[cnt] different content objects." );
        $action = $mode;
    }
    else
    {
        $action = readline( "Remote ID '$nonUniqueRemoteIDData[remote_id]' is used for $nonUniqueRemoteIDData[cnt] different content objects. Do you want to see the details (d) or do you want this inconsistency to be fixed automatically (a) ?" );

        while ( !in_array( $action, array( 'a', 'd' ) ) )
        {
            $action = readline( 'Invalid option. Type either d for details or a to fix automatically.' );
        }
    }

    $contentObjects = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                           null,
                                                           array( 'remote_id' => $nonUniqueRemoteIDData['remote_id'] ),
                                                           array( 'status' => 'desc', 'published' => 'asc' ) );

    switch ( $action )
    {
        case 'd':
        {
            $cli->output( '' );
            $cli->output( 'Select the number of the content object that you want to keep the current remote ID. The other listed content objects will get a new one.' );
            $cli->output( '' );

            foreach ( $contentObjects as $i => $contentObject )
            {
                $status = $contentObject->attribute( 'status' );
                $objectID = $contentObject->attribute( 'id' );

                switch ( $status )
                {
                    case eZContentObject::STATUS_PUBLISHED:
                    {
                        $dateTime = new eZDateTime( $contentObject->attribute( 'published' ) );
                        $formattedDateTime = $dateTime->toString( true );
                        $mainNode = $contentObject->attribute( 'main_node' );
                        $pathIdentificationString = $mainNode->attribute( 'path_identification_string' );

                        $message = "$pathIdentificationString (object ID: $objectID, published: $formattedDateTime )";
                    } break;

                    case eZContentObject::STATUS_DRAFT:
                    {
                        $message = "draft content object (object ID: $objectID )";
                    } break;

                    case eZContentObject::STATUS_ARCHIVED:
                    {
                        $message = "trashed content object (object ID: $objectID)";
                    } break;

                    default:
                    {
                        $script->shutdown( 2, "Impossible object status $status for object $objectID" );
                    }
                }

                $cli->output( "$i) $message" );
                $cli->output( '' );
            }

            do {
                $skip = readline( 'Number of object that should keep the current remote ID: ' );
            } while ( !array_key_exists( $skip, $contentObjects ) );
        } break;

        case 'a':
        default:
        {
            $skip = 0;
        }
    }

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

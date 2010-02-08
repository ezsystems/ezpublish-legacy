#!/usr/bin/env php
<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
// This program is free software; you can redistribute it and/or
// modify it under the terms of version 2.0 of the GNU General
// Public License as published by the Free Software Foundation.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of version 2.0 of the GNU General
// Public License along with this program; if not, write to the Free
// Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
// MA 02110-1301, USA.
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
$scriptSettings['description'] = 'Fix non-unique usage of tree node remote ID\'s';
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

$nonUniqueRemoteIDDataList = $db->arrayQuery( 'SELECT remote_id, COUNT(*) AS cnt FROM ezcontentobject_tree GROUP BY remote_id HAVING COUNT(*) > 1' );

$nonUniqueRemoteIDDataListCount = count( $nonUniqueRemoteIDDataList );

$cli->output( '' );
$cli->output( "Found $nonUniqueRemoteIDDataListCount non-unique tree node remote IDs." );
$cli->output( '' );

$totalCount = 0;

foreach ( $nonUniqueRemoteIDDataList as $nonUniqueRemoteIDData )
{
 if ( $mode )
 {
 $cli->output( "Remote ID '$nonUniqueRemoteIDData[remote_id]' is used for $nonUniqueRemoteIDData[cnt] different tree nodes." );
 $action = $mode;
 }
 else
 {
 $action = readline( "Remote ID '$nonUniqueRemoteIDData[remote_id]' is used for $nonUniqueRemoteIDData[cnt] different tree nodes. Do you want to see the details (d) or do you want this inconsistency to be fixed automatically (a) ?" );

 while ( !in_array( $action, array( 'a', 'd' ) ) )
 {
 $action = readline( 'Invalid option. Type either d for details or a to fix automatically.' );
 }
 }

 $treeNodes = eZPersistentObject::fetchObjectList( eZContentObjectTreeNode::definition(),
 null,
 array( 'remote_id' => $nonUniqueRemoteIDData['remote_id'] ),
 array( 'modified_subnode' => 'asc' ) );

 switch ( $action )
 {
 case 'd':
 {
 $cli->output( '' );
 $cli->output( 'Select the number of the tree node that you want to keep the current remote ID. The other listed tree nodes will get a new one.' );
 $cli->output( '' );

 foreach ( $treeNodes as $i => $treeNode )
 {
 $nodeID = $treeNode->attribute( 'node_id' );

 $dateTime = new eZDateTime( $treeNode->attribute( 'modified_subnode' ) );
 $formattedDateTime = $dateTime->toString( true );
 $pathIdentificationString = $treeNode->attribute( 'path_identification_string' );

 $cli->output( "$i) $pathIdentificationString (Node ID: $nodeID, modified_subnode: $formattedDateTime )" );
 $cli->output( '' );
 }

 do {
 $skip = readline( 'Number of nodes that should keep the current remote ID: ' );
 } while ( !array_key_exists( $skip, $treeNodes ) );
 } break;

 case 'a':
 default:
 {
 $skip = 0;
 }
 }

 $cli->output( 'Fixing...' );

 foreach ( $treeNodes as $i => $treeNode )
 {
 if ( $i == $skip )
 {
 continue;
 }

 $newRemoteID = md5( (string)mt_rand() . (string)time() );
 $treeNode->setAttribute( 'remote_id', $newRemoteID );
 $treeNode->store();
 }

 $totalCount += $nonUniqueRemoteIDData['cnt'] - 1;

 $cli->output( '' );
 $cli->output( '' );
}

$nonUniqueRemoteIDDataList = $db->arrayQuery( "SELECT node_id FROM ezcontentobject_tree WHERE remote_id = ''" );

$nonUniqueRemoteIDDataListCount = count( $nonUniqueRemoteIDDataList );

$cli->output( '' );
$cli->output( "Found $nonUniqueRemoteIDDataListCount tree nodes with empty remote IDs." );
$cli->output( '' );

if ( $nonUniqueRemoteIDDataListCount )
{
    $cli->output( 'Fixing', false );

    foreach ( $nonUniqueRemoteIDDataList as $nonUniqueRemoteIDData )
    {
        // fetch nodes with eZPersistentObject to avoid object cache
        $treeNodes = eZPersistentObject::fetchObjectList( eZContentObjectTreeNode::definition(),
                                                        null,
                                                        array( 'node_id' => $nonUniqueRemoteIDData['node_id'] ),
                                                        array( 'modified_subnode' => 'asc' ) );
        foreach ( $treeNodes as $i => $treeNode )
        {
            $newRemoteID = md5( (string)mt_rand() . (string)time() );
            $treeNode->setAttribute( 'remote_id', $newRemoteID );
            $treeNode->store();
        }

        ++$totalCount;
        $cli->output( '.', false );
    }
}

$cli->output( '' );
$cli->output( "Number of tree nodes that received a new remote ID : $totalCount" );

$script->shutdown( 0 );

?>

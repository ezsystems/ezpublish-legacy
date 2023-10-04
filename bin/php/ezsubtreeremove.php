#!/usr/bin/env php
<?php
/**
 * File containing the ezsubtreeremove.php script.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

// Subtree Remove Script
// file  bin/php/ezsubtreeremove.php

// script initializing
require_once 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                         "This script will make a remove of a content object subtrees.\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[nodes-id:][ignore-trash][only-children-remove]",
                                      "",
                                      array( 'nodes-id' => "Subtree nodes ID (separated by comma ',').",
                                             'ignore-trash' => "Ignore trash ('move to trash' by default).",
                                             'only-children-remove' => "If you want only remove child of nodes-id."
                                             ),
                                      false );
$script->initialize();
$srcNodesID  = $scriptOptions[ 'nodes-id' ] ? trim( $scriptOptions[ 'nodes-id' ] ) : false;
$moveToTrash = $scriptOptions[ 'ignore-trash' ] ? false : true;
$onlyChildrenRemove = $scriptOptions[ 'only-children-remove' ] ? true : false;
$deleteIDArray = $srcNodesID ? explode( ',', $srcNodesID ) : false;

if ( !$deleteIDArray )
{
    $cli->error( "Subtree remove Error!\nCannot get subtree nodes. Please check nodes-id argument and try again." );
    $script->showHelp();
    $script->shutdown( 1 );
}

$ini = eZINI::instance();
// Get user's ID who can remove subtrees. (Admin by default with userID = 14)
$userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
$user = eZUser::fetch( $userCreatorID );
if ( !$user )
{
    $cli->error( "Subtree remove Error!\nCannot get user object by userID = '$userCreatorID'.\n(See site.ini[UserSettings].UserCreatorID)" );
    $script->shutdown( 1 );
}
eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

$deleteIDArrayResult = array();
foreach ( $deleteIDArray as $nodeID )
{
    $node = eZContentObjectTreeNode::fetch( $nodeID );
    if ( $node === null )
    {
        $cli->error( "\nSubtree remove Error!\nCannot find subtree with nodeID: '$nodeID'." );
        continue;
    }
    $deleteIDArrayResult[] = $nodeID;
}
// Get subtree removal information
$info = eZContentObjectTreeNode::subtreeRemovalInformation( $deleteIDArrayResult );

$deleteResult = $info['delete_list'];

if ( count( $deleteResult ) == 0 )
{
    $cli->output( "\nExit." );
    $script->shutdown( 1 );
}

$totalChildCount = $info['total_child_count'];
$canRemoveAll = $info['can_remove_all'];
$moveToTrashStr = $moveToTrash ? 'true' : 'false';
$onlyChildrenRemoveStr = $onlyChildrenRemove ? 'true' : 'false';
$reverseRelatedCount = $info['reverse_related_count'];

$cli->output( "\nTotal child count: $totalChildCount" );
$cli->output( "Move to trash: $moveToTrashStr" );
$cli->output( "Remove only children: $onlyChildrenRemoveStr" );
$cli->output( "Reverse related count: $reverseRelatedCount\n" );

$cli->output( "Removing subtrees:\n" );

foreach ( $deleteResult as $deleteItem )
{
    $node = $deleteItem['node'];
    $nodeName = $deleteItem['node_name'];
    if ( $node === null )
    {
        $cli->error( "\nSubtree remove Error!\nCannot find subtree '$nodeName'." );
        continue;
    }
    $nodeID = $node->attribute( 'node_id' );
    $childCount = $deleteItem['child_count'];
    $objectNodeCount = $deleteItem['object_node_count'];

    $cli->output( "Node id: $nodeID" );
    $cli->output( "Node name: $nodeName" );

    $canRemove = $deleteItem['can_remove'];
    if ( !$canRemove )
    {
        $cli->error( "\nSubtree remove Error!\nInsufficient permissions. You do not have permissions to remove the subtree with nodeID: $nodeID\n" );
        continue;
    }
    $cli->output( "Child count: $childCount" );
    $cli->output( "Object node count: $objectNodeCount" );

    // Remove subtrees
    eZContentObjectTreeNode::removeSubtrees( array( $nodeID ), $moveToTrash, false, $onlyChildrenRemove );

    // We should make sure that all subitems have been removed.
    $itemInfo = eZContentObjectTreeNode::subtreeRemovalInformation( array( $nodeID ) );
    $itemTotalChildCount = $itemInfo['total_child_count'];
    $itemDeleteList = $itemInfo['delete_list'];

    if ( $onlyChildrenRemove and count( $itemDeleteList ) == 1 )
    {
        $itemNodeID = $itemDeleteList[0]["node"]->attribute( 'node_id' );

        if ( $itemNodeID == $nodeID )
        {
            array_pop( $itemDeleteList );
        }
    }

    if ( count( $itemDeleteList ) != 0 or ( $childCount != 0 and $itemTotalChildCount != 0 ) )
        $cli->error( "\nWARNING!\nSome subitems have not been removed.\n" );
    else
        $cli->output( "Successfuly DONE.\n" );
}

$cli->output( "Done." );
$script->shutdown();

?>

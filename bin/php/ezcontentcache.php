#!/usr/bin/env php
<?php
//
// Created on: <19-Jul-2004 10:51:17 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Content Cache Handler\n" .
                                                        "Allows for easy clearing of Content Caches\n" .
                                                        "\n" .
                                                        "Clearing node for content and users tree\n" .
                                                        "./bin/ezcontentcache.php --clear-node=/,5\n" .
                                                        "Clearing subtree for content tree\n" .
                                                        "./bin/ezcontentcache.php --clear-subtree=/" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[clear-node:][clear-subtree:]",
                                "",
                                array( 'clear-node' => ( "Clears all content caches related to a given node,\n" .
                                                         "pass either node ID or nice url of node.\n" .
                                                         "Separate multiple nodes with a comma." ),
                                       'clear-subtree' => ( "Clears all content caches related to a given node subtree,\n" .
                                                            "subtree expects a nice url as input.\n" .
                                                            "Separate multiple subtrees with a comma" ) ) );
$sys = eZSys::instance();

$script->initialize();

// Max nodes to fetch at a time
$limit = 50;

if ( $options['clear-node'] )
{
    $idList = explode( ',', $options['clear-node'] );
    foreach ( $idList as $nodeID )
    {
        if ( is_numeric( $nodeID ) )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( !$node )
            {
                $cli->output( "Node with ID $nodeID does not exist, skipping" );
                continue;
            }
        }
        else
        {
            $nodeSubtree = trim( $nodeID, '/' );
            $node = eZContentObjectTreeNode::fetchByURLPath( $nodeSubtree );
            if ( !$node )
            {
                $cli->output( "Node with subtree " . $cli->stylize( 'emphasize', $nodeSubtree ) . " does not exist, skipping" );
                continue;
            }
        }
        $nodeSubtree = $node->attribute( 'path_identification_string' );
        $nodeName = false;
        $object = $node->attribute( 'object' );
        if ( $object )
        {
            $nodeName = $object->attribute( 'name' );
        }
        $objectID = $node->attribute( 'contentobject_id' );
        $cli->output( "Clearing cache for $nodeName ($nodeSubtree)" );
        eZContentCacheManager::clearContentCache( $objectID );
    }
    $script->shutdown( 0 );
}
else if ( $options['clear-subtree'] )
{
    $subtreeList = explode( ',', $options['clear-subtree'] );
    foreach ( $subtreeList as $nodeSubtree )
    {
        if ( is_numeric( $nodeSubtree ) )
        {
            $nodeID = (int)$nodeSubtree;
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( !$node )
            {
                $cli->output( "Node with ID " . $cli->stylize( 'emphasize', $nodeID ) . " does not exist, skipping" );
                continue;
            }
        }
        else
        {
            $nodeSubtree = trim( $nodeSubtree, '/' );
            $node = eZContentObjectTreeNode::fetchByURLPath( $nodeSubtree );
            if ( !$node )
            {
                $cli->output( "Node with subtree " . $cli->stylize( 'emphasize', $nodeSubtree ) . " does not exist, skipping" );
                continue;
            }
        }
        $nodeSubtree = $node->attribute( 'path_identification_string' );
        $nodeName = false;
        $object = $node->attribute( 'object' );
        if ( $object )
        {
            $nodeName = $object->attribute( 'name' );
        }
        $cli->output( "Clearing cache for subtree $nodeName ($nodeSubtree)" );
        $objectID = $node->attribute( 'contentobject_id' );
        $offset = 0;
        $params = array( 'AsObject' => false,
                         'Depth' => false,
                         'Limitation' => array() ); // Empty array means no permission checking

        $subtreeCount = $node->subTreeCount( $params );
        $script->resetIteration( $subtreeCount );
        while ( $offset < $subtreeCount )
        {
            $params['Offset'] = $offset;
            $params['Limit'] = $limit;
            $subtree =& $node->subTree( $params );
            $offset += count( $subtree );
            if ( count( $subtree ) == 0 )
            {
                break;
            }

            $objectIDList = array();
            foreach ( $subtree as $subtreeNode )
            {
                $objectIDList[] = $subtreeNode['contentobject_id'];
            }
            $objectIDList = array_unique( $objectIDList );
            unset( $subtree );

            foreach ( $objectIDList as $objectID )
            {
                $status = eZContentCacheManager::clearContentCache( $objectID );
                $script->iterate( $cli, $status, "Cleared view cache for object $objectID" );
            }
        }
    }
    $script->shutdown( 0 );
}
$cli->output( "You will need to specify what to clear, either with --clear-node or --clear-subtree" );

$script->shutdown( 1 );

?>

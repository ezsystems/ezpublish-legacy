#!/usr/bin/env php
<?php
//
// Created on: <19-Jul-2004 10:51:17 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Content Cache Handler\n" .
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
$sys =& eZSys::instance();

$script->initialize();

include_once( 'kernel/classes/ezcontentcachemanager.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

// Max nodes to fetch at a time
$limit = 50;

if ( $options['clear-node'] )
{
    $idList = explode( ',', $options['clear-node'] );
    foreach ( $idList as $nodeID )
    {
        if ( is_numeric( $nodeID ) )
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
            if ( !$node )
            {
                $cli->output( "Node with ID $nodeID does not exist, skipping" );
                continue;
            }
        }
        else
        {
            $nodeSubtree = trim( $nodeID, '/' );
            $node =& eZContentObjectTreeNode::fetchByURLPath( $nodeSubtree );
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
        eZContentCacheManager::clearViewCache( $objectID, true );
    }
    return $script->shutdown();
}
else if ( $options['clear-subtree'] )
{
    $subtreeList = explode( ',', $options['clear-subtree'] );
    foreach ( $subtreeList as $nodeSubtree )
    {
        if ( is_numeric( $nodeSubtree ) )
        {
            $nodeID = (int)$nodeSubtree;
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
            if ( !$node )
            {
                $cli->output( "Node with ID " . $cli->stylize( 'emphasize', $nodeID ) . " does not exist, skipping" );
                continue;
            }
        }
        else
        {
            $nodeSubtree = trim( $nodeSubtree, '/' );
            $node =& eZContentObjectTreeNode::fetchByURLPath( $nodeSubtree );
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
                $status = eZContentCacheManager::clearViewCache( $objectID, true );
                $script->iterate( $cli, $status, "Cleared view cache for object $objectID" );
            }
        }
    }
    return $script->shutdown();
}
$cli->output( "You will need to specify what to clear, either with --clear-node or --clear-subtree" );

$script->shutdown( 1 );

?>

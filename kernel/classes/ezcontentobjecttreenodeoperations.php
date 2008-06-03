<?php
//
// Definition of eZContentObjectTreeNodeOperations class
//
// Created on: <12-Sep-2005 12:02:22 dl>
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

/*! \file ezcontentobjecttreenodeoperations.php
*/

/*!
  \class eZContentObjectTreeNodeOperations ezcontentobjecttreenodeoperations.php
  \brief The class eZContentObjectTreeNodeOperations is a wrapper for node's
  core-operations. It takes care about interface stuff.
  Example: there is a 'move' core-operation that moves a node from one location
  to another. But, for example, before and after moving we have to clear
  view caches for old and new placements. Clearing of the cache is handled by
  this class.
*/

class eZContentObjectTreeNodeOperations
{
    /*!
     Constructor
    */
    function eZContentObjectTreeNodeOperations()
    {
    }

    /*!
     \static
     A wrapper for eZContentObjectTreeNode's 'move' operation.
     It does:
      - clears caches for old placement;
      - performs actual move( calls eZContentObjectTreeNode->move() );
      - updates subtree path;
      - updates node's section;
      - updates assignment( setting new 'parent_node' );
      - clears caches for new placement;

     \param $nodeID The id of a node to move.
     \param $newParentNodeID The id of a new parent.
     \return \c true if 'move' was done successfully, otherwise \c false;
    */
    static function move( $nodeID, $newParentNodeID )
    {
        $result = false;

        if ( !is_numeric( $nodeID ) || !is_numeric( $newParentNodeID ) )
            return false;

        //include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $node = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !$node )
            return false;

        $object = $node->object();
        if ( !$object )
            return false;

        $objectID = $object->attribute( 'id' );
        $oldParentNode = $node->fetchParent();
        $oldParentObject = $oldParentNode->object();

        // clear user policy cache if this was a user object
        //include_once( "lib/ezutils/classes/ezini.php" );
        $ini = eZINI::instance();
        $userClassID = $ini->variable( "UserSettings", "UserClassID" );
        if ( $object->attribute( 'contentclass_id' ) == $userClassID )
        {
            //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            eZUser::cleanupCache();
        }

        // clear cache for old placement.
        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        //include_once( "lib/ezdb/classes/ezdb.php" );
        $db = eZDB::instance();
        $db->begin();

        $node->move( $newParentNodeID );

        $newNode = eZContentObjectTreeNode::fetchNode( $objectID, $newParentNodeID );

        if ( $newNode )
        {
            $newNode->updateSubTreePath( true, true );
            if ( $newNode->attribute( 'main_node_id' ) == $newNode->attribute( 'node_id' ) )
            {
                // If the main node is moved we need to check if the section ID must change
                $newParentNode = $newNode->fetchParent();
                $newParentObject = $newParentNode->object();
                if ( $object->attribute( 'section_id' ) != $newParentObject->attribute( 'section_id' ) )
                {

                    eZContentObjectTreeNode::assignSectionToSubTree( $newNode->attribute( 'main_node_id' ),
                                                                     $newParentObject->attribute( 'section_id' ),
                                                                     $oldParentObject->attribute( 'section_id' ) );
                }
            }

            // modify assignment
            //include_once( "kernel/classes/eznodeassignment.php" );
            $curVersion     = $object->attribute( 'current_version' );
            $nodeAssignment = eZNodeAssignment::fetch( $objectID, $curVersion, $oldParentNode->attribute( 'node_id' ) );

            if ( $nodeAssignment )
            {
                $nodeAssignment->setAttribute( 'parent_node', $newParentNodeID );
                $nodeAssignment->setAttribute( 'op_code', eZNodeAssignment::OP_CODE_MOVE );
                $nodeAssignment->store();
            }

            $result = true;
        }
        else
        {
            eZDebug::writeError( "Node $nodeID was moved to $newParentNodeID but fetching the new node failed" );
        }

        $db->commit();

        // clear cache for new placement.
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return $result;
    }
}


?>

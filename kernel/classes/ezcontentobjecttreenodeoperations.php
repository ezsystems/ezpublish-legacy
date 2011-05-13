<?php
/**
 * File containing the eZContentObjectTreeNodeOperations class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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

        $node = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !$node )
            return false;

        $object = $node->object();
        if ( !$object )
            return false;

        $objectID = $object->attribute( 'id' );
        $oldParentNode = $node->fetchParent();
        $oldParentObject = $oldParentNode->object();

        // clear user policy cache if this is a user object
        if ( in_array( $object->attribute( 'contentclass_id' ), eZUser::contentClassIDs() ) )
        {
            eZUser::purgeUserCacheByUserId( $object->attribute( 'id' ) );
        }

        // clear cache for old placement.
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

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
            $curVersion     = $object->attribute( 'current_version' );
            $nodeAssignment = eZNodeAssignment::fetch( $objectID, $curVersion, $oldParentNode->attribute( 'node_id' ) );

            if ( $nodeAssignment )
            {
                $nodeAssignment->setAttribute( 'parent_node', $newParentNodeID );
                $nodeAssignment->setAttribute( 'op_code', eZNodeAssignment::OP_CODE_MOVE );
                $nodeAssignment->store();

                // update search index
                $nodeIDList = array( $nodeID );
                eZSearch::removeNodeAssignment( $node->attribute( 'main_node_id' ), $newNode->attribute( 'main_node_id' ), $object->attribute( 'id' ), $nodeIDList );
                eZSearch::addNodeAssignment( $newNode->attribute( 'main_node_id' ), $object->attribute( 'id' ), $nodeIDList );
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

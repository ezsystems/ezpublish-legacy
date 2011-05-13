<?php
/**
 * File containing the eZContentObjectOperations class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZContentObjectOperations ezcontentobjectoperations.php
  \brief The class eZContentObjectOperations is a place where
         content object operations are encapsulated.
  We move them out from eZContentObject because they may content code
  which is not directly related to content objects (e.g. clearing caches, etc).
*/

class eZContentObjectOperations
{
    /*!
     Removes content object and all data associated with it.
     \static
    */
    static function remove( $objectID, $removeSubtrees = true )
    {
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        $object = eZContentObject::fetch( $objectID );
        if ( !is_object( $object ) )
            return false;

        // TODO: Is content cache cleared for all objects in subtree ??

        if ( $removeSubtrees )
        {
            $assignedNodes = $object->attribute( 'assigned_nodes' );
            if ( count( $assignedNodes ) == 0 )
            {
                $object->purge();
                eZContentObject::expireAllViewCache();
                return true;
            }
            $assignedNodeIDArray = array();
            foreach( $assignedNodes as $node )
            {
                $assignedNodeIDArray[] = $node->attribute( 'node_id' );
            }
            eZContentObjectTreeNode::removeSubtrees( $assignedNodeIDArray, false );
        }
        else
            $object->purge();

        return true;
    }
}


?>

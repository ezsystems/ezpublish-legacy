<?php
//
// Definition of eZContentObjectOperations class
//
// Created on: <23-Jan-2006 14:38:57 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezcontentobjectoperations.php
*/

/*!
  \class eZContentObjectOperations ezcontentobjectoperations.php
  \brief The class eZContentObjectOperations is a place where
         content object operations are encapsulated.
  We move them out from eZContentObject because they may content code
  which is not directly related to content objects (e.g. clearing caches, etc).
*/

//include_once( 'kernel/classes/ezcontentobject.php' );

class eZContentObjectOperations
{
    /*!
     Removes content object and all data associated with it.
     \static
    */
    static function remove( $objectID, $removeSubtrees = true )
    {
        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
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

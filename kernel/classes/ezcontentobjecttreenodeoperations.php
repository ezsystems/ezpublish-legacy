<?php
//
// Definition of eZContentObjectTreeNodeOperations class
//
// Created on: <12-Sep-2005 12:02:22 dl>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
    function move( $nodeID, $newParentNodeID )
    {
        $result = false;

        if ( !is_numeric( $nodeID ) || !is_numeric( $newParentNodeID ) )
            return false;

        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $node = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !$node )
            return false;

        $object =& $node->object();
        if ( !$object )
            return false;

        $objectID = $object->attribute( 'id' );
        $oldParentNode =& $node->fetchParent();
        $oldParentObject =& $oldParentNode->object();

        // clear user policy cache if this was a user object
        include_once( "lib/ezutils/classes/ezini.php" );
        $ini =& eZINI::instance();
        $userClassID = $ini->variable( "UserSettings", "UserClassID" );
        if ( $object->attribute( 'contentclass_id' ) == $userClassID )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            eZUser::cleanupCache();
        }

        // clear cache for old placement.
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        include_once( "lib/ezdb/classes/ezdb.php" );
        $db =& eZDB::instance();
        $db->begin();

        $node->move( $newParentNodeID );

        $newNode = eZContentObjectTreeNode::fetchNode( $objectID, $newParentNodeID );

        if ( $newNode )
        {
            $newNode->updateSubTreePath();
            if ( $newNode->attribute( 'main_node_id' ) == $newNode->attribute( 'node_id' ) )
            {
                // If the main node is moved we need to check if the section ID must change
                $newParentNode =& $newNode->fetchParent();
                $newParentObject =& $newParentNode->object();
                if ( $object->attribute( 'section_id' ) != $newParentObject->attribute( 'section_id' ) )
                {

                    eZContentObjectTreeNode::assignSectionToSubTree( $newNode->attribute( 'main_node_id' ),
                                                                     $newParentObject->attribute( 'section_id' ),
                                                                     $oldParentObject->attribute( 'section_id' ) );
                }
            }

            // modify assignment
            include_once( "kernel/classes/eznodeassignment.php" );
            $curVersion     =& $object->attribute( 'current_version' );
            $nodeAssignment = eZNodeAssignment::fetch( $objectID, $curVersion, $oldParentNode->attribute( 'node_id' ) );

            if ( $nodeAssignment )
            {
                $nodeAssignment->setAttribute( 'parent_node', $newParentNodeID );
                $nodeAssignment->store();
            }

            $result = true;
        }

        $db->commit();

        // clear cache for new placement.
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return $result;
    }
}


?>
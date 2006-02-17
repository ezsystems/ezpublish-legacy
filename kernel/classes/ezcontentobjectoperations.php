<?php
//
// Definition of eZContentObjectOperations class
//
// Created on: <23-Jan-2006 14:38:57 vs>
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

/*! \file ezcontentobjectoperations.php
*/

/*!
  \class eZContentObjectOperations ezcontentobjectoperations.php
  \brief The class eZContentObjectOperations is a place where
         content object operations are encapsulated.
  We move them out from eZContentObject because they may content code
  which is not directly related to content objects (e.g. clearing caches, etc).
*/

include_once( 'kernel/classes/ezcontentobject.php' );

class eZContentObjectOperations
{
    /*!
     Removes content object and all data associated with it.
     \static
    */
    function remove( $objectID, $removeSubtrees = true )
    {
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        $object =& eZContentObject::fetch( $objectID );
        if ( !is_object( $object ) )
            return;

        // TODO: Is content cache cleared for all objects in subtree ??

        if ( $removeSubtrees )
        {
            $assignedNodes = $object->attribute( 'assigned_nodes' );
            $assignedNodeIDArray = array();
            foreach( $assignedNodes as $node )
            {
                $assignedNodeIDArray[] = $node->attribute( 'node_id' );
            }
            eZContentObjectTreeNode::removeSubtrees( $assignedNodeIDArray, false );
        }
        else
            $object->purge();
    }
}


?>
<?php
//
// Definition of eZContentCacheManager class
//
// Created on: <23-Sep-2004 12:52:38 jb>
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

/*! \file ezcontentcachemanager.php
*/

/*!
  \class eZContentCacheManager ezcontentcachemanager.php
  \brief Figures out relations between objects, nodes and classes for cache management

  This class works together with eZContentCache to manage the cache files
  for content viewing. This class takes care of finding out the relationship
  and then passes a list of nodes to eZContentCache which does the actual
  clearing.

  The manager uses special rules in 'viewcache.ini' to figure relationships.
  \sa eZContentCache
*/

class eZContentCacheManager
{
    /*!
     \note Not used, all methods are static
    */
    function eZContentCacheManager()
    {
    }

    /*!
     \static
     Appends parent nodes ids of \a $object to \a $nodeIDList array.
     \param $versionNum The version of the object to use or \c true for current version
     \param[out] $nodeIDList Array with node IDs
    */
    function appendParentNodeIDs( &$object, $versionNum, &$nodeIDList )
    {
        $parentNodes =& $object->parentNodes( $versionNum );
        foreach ( array_keys( $parentNodes ) as $parentNodeKey )
        {
            $parentNode =& $parentNodes[$parentNodeKey];
            $nodeIDList[] = $parentNode->attribute( 'node_id' );
        }
    }

    /*!
     \static
     Appends nodes ids from \a $nodeList list to \a $nodeIDList
     \param[out] $nodeIDList Array with node IDs
    */
    function appendNodeIDs( &$nodeList, &$nodeIDList )
    {
        foreach ( array_keys( $nodeList ) as $nodeKey )
        {
            $assignedNode =& $nodeList[$nodeKey];
            $nodeIDList[] = $assignedNode->attribute( 'node_id' );
        }
    }

    /*!
     \static
     Goes through all content nodes in \a $nodeList and extracts the \c 'path_string'.
     \return An array with \c 'path_string' information.
    */
    function &fetchNodePathString( &$nodeList )
    {
        $pathList = array();
        foreach ( array_keys( $nodeList ) as $nodeKey )
        {
            $node =& $nodeList[$nodeKey];
            $pathList[] = $node->attribute( 'path_string' );
        }
        return $pathList;
    }

    /*!
     \static
     Find all content objects that relates \a $object and appends
     their node IDs to \a $nodeIDList.
     \param[out] $nodeIDList Array with node IDs
    */
    function appendRelatingNodeIDs( &$object, &$nodeIDList )
    {
        $relatedObjects =& $object->contentObjectListRelatingThis();
        foreach ( array_keys( $relatedObjects ) as $relatedObjectKey )
        {
            $relatedObject =& $relatedObjects[$relatedObjectKey];
            $assignedNodes =& $relatedObject->assignedNodes( false );
            foreach ( array_keys( $assignedNodes ) as $assignedNodeKey )
            {
                $assignedNode =& $assignedNodes[$assignedNodeKey];
                $nodeIDList[] = $assignedNode['node_id'];
            }
        }
    }

    /*
     \static
     Reads 'viewcache.ini' file and determines relation between
     \a $classID and another class.

     \return An associative array with information on the class, containsL:
             - dependent_class_identifier - The class identifier of objects that depend on this class
             - max_parents - The maxium number of parent nodes to check, or \c 0 for no limit
             - clear_cache_type - Bitfield of clear types, see nodeListForObject() for more details
             - object_filter - Array with object IDs, if there are entries only these objects should be checked.
    */
    function dependencyInfo( $classID )
    {
        $ini =& eZINI::instance( 'viewcache.ini' );
        $info = false;

        if ( $ini->hasGroup( $classID ) )
        {
            $info = array();
            $info['dependent_class_identifier'] = $ini->variable( $classID, 'DependentClassIdentifier' );

            if ( $ini->hasVariable( $classID, 'MaxParents' ) )
                $info['max_parents'] = $ini->variable( $classID, 'MaxParents' );
            else
                $info['max_parents'] = 0;

            $info['clear_cache_type'] = 0;
            if ( $ini->hasVariable( $classID, 'ClearCacheMethod' ) )
            {
                $type = $ini->variable( $classID, 'ClearCacheMethod' );

                if ( $type == 'clear_all_caches' )
                {
                    $info['clear_cache_type'] = EZ_VCSC_CLEAR_ALL_CACHE;
                }
                else
                {
                    if ( $type == 'clear_object_caches_only' ||
                         $type == 'clear_object_and_parent_nodes_caches' ||
                         $type == 'clear_object_and_relating_objects_caches' )
                    {
                        $info['clear_cache_type'] |= EZ_VCSC_CLEAR_NODE_CACHE;
                    }

                    if ( $type == 'clear_object_and_parent_nodes_caches' ||
                         $type == 'clear_parent_nodes_caches_only' ||
                         $type == 'clear_parent_nodes_and_relating_caches' )
                    {
                        $info['clear_cache_type'] |= EZ_VCSC_CLEAR_PARENT_CACHE;
                    }

                    if ( $type == 'clear_object_and_relating_objects_caches' ||
                         $type == 'clear_parent_nodes_and_relating_caches' ||
                         $type == 'clear_relating_caches_only' )
                    {
                        $info['clear_cache_type'] |= EZ_VCSC_CLEAR_RELATING_CACHE;
                    }
                }
            }
            else
            {
                $info['clear_cache_type'] = EZ_VCSC_CLEAR_ALL_CACHE;
            }

            $info['object_filter'] = array();
            if ( $ini->hasVariable( $classID, 'ObjectFilter' ) )
            {
                $info['object_filter'] = $ini->variable( $classID, 'ObjectFilter' );
            }
        }
        return $info;
    }

    /*!
     \static
     Use \a $clearCacheType to include different kind of nodes( parent, relating, etc ).
     If \a $versionNum is true, then current version will be used.

     \param $contentObject Current content object that is checked.
     \param $versionNum The version of the object to use or \c true for current version
     \param $clearCacheType Bit field which controls the the extra nodes to include,
                            use bitwise or with one of these defines:
                            - EZ_VCSC_CLEAR_NODE_CACHE - Clear the nodes of the object
                            - EZ_VCSC_CLEAR_PARENT_CACHE - Clear the parent nodes of the object
                            - EZ_VCSC_CLEAR_RELATING_CACHE - Clear nodes of objects that relate this object
                            - EZ_VCSC_CLEAR_ALL_CACHE - Enables all of the above
     \param[out] $nodeList An array with node IDs that are affected by the current object change.

     \note This function is recursive.
    */
    function nodeListForObject( &$contentObject, $versionNum, $clearCacheType, &$nodeList )
    {
        $assignedNodes =& $contentObject->assignedNodes();

        if ( $clearCacheType & EZ_VCSC_CLEAR_NODE_CACHE )
        {
            eZContentCacheManager::appendNodeIDs( $assignedNodes, $nodeList );
        }

        if ( $clearCacheType & EZ_VCSC_CLEAR_PARENT_CACHE )
        {
            eZContentCacheManager::appendParentNodeIDs( $contentObject, $versionNum, $nodeList );
        }

        if ( $clearCacheType & EZ_VCSC_CLEAR_RELATING_CACHE )
        {
            eZContentCacheManager::appendRelatingNodeIDs( $contentObject, $nodeList );
        }

        // determine if $contentObject has dependent objects for which cache should be cleared too.
        $objectClassIdentifier =  $contentObject->attribute( 'class_identifier' );
        $dependentClassInfo =& eZContentCacheManager::dependencyInfo( $objectClassIdentifier );

        if ( isset( $dependentClassInfo['dependent_class_identifier'] ) )
        {
            // getting 'path_string's for all locations.
            $nodePathList =& eZContentCacheManager::fetchNodePathString( $assignedNodes );

            foreach ( $nodePathList as $nodePath )
            {
                // getting class identifier and node ID for each node in the $nodePath.
                $nodeInfoList =& eZContentObjectTreeNode::fetchClassIdentifierListByPathString( $nodePath, false );

                $step = 0;
                $maxParents = $dependentClassInfo['max_parents'];
                $dependentClassIdentifier = $dependentClassInfo['dependent_class_identifier'];
                $smartClearType = $dependentClassInfo['clear_cache_type'];

                if ( $maxParents > 0 )
                {
                    // need to reverse $nodeInfoList if $maxParents is used.
                    // if offset is zero then we will loop through all elements in $nodeInfoList. So,
                    // array_reverse don't need.

                    $nodeInfoList = array_reverse( $nodeInfoList );
                }

                // for each node in $nodeInfoList determine if this node belongs to $dependentClassIdentifier. If
                // so then clear cache for this node.
                foreach ( $nodeInfoList as $item )
                {
                    if ( $item['class_identifier'] == $dependentClassIdentifier )
                    {
                        $node =& eZContentObjectTreeNode::fetch( $item['node_id'] );
                        $object =& $node->attribute( 'object' );

                        if ( count( $dependentClassInfo['object_filter'] ) > 0 )
                        {
                            foreach ( $dependentClassInfo['object_filter'] as $objectIDFilter )
                            {
                                if ( $objectIDFilter == $object->attribute( 'id' ) )
                                {
                                    eZContentCacheManager::nodeListForObject( $object, true, $smartClearType, $nodeList );
                                    break;
                                }
                            }
                        }
                        else
                        {
                            eZContentCacheManager::nodeListForObject( $object, true, $smartClearType, $nodeList );
                        }
                    }

                    // if we reached $maxParents then break
                    if ( ++$step == $maxParents )
                    {
                        break;
                    }
                }
            }
        }
    }

    /*!
     \static
     Figures out all nodes that are affected by the change of object \a $objectID.
     This involves finding all nodes, parent nodes and nodes of objects
     that relate this object.
     The 'viewcache.ini' file is also checked to see if some special content classes
     has dependencies to the current object, if this is true extra nodes might be
     included.

     \param $versionNum The version of the object to use or \c true for current version
     \param $additionalNodeList An array with node IDs to add to clear list,
                                or \c false for no additional nodes.
     \return An array with node IDs that must have their viewcaches cleared.
    */
    function &nodeList( $objectID, $versionNum )
    {
        $nodeList = array();

        $object =& eZContentObject::fetch( $objectID );

        eZContentCacheManager::nodeListForObject( $object, $versionNum, EZ_VCSC_CLEAR_ALL_CACHE, $nodeList );

        return $nodeList;
    }

    /*!
     \static
     Clears view caches of nodes, parent nodes and relating nodes
     of content objects with id \a $objectID.
     It will use 'viewcache.ini' to determine additional nodes.

     \param $versionNum The version of the object to use or \c true for current version
     \param $additionalNodeList An array with node IDs to add to clear list,
                                or \c false for no additional nodes.
    */
    function clearViewCache( $objectID, $versionNum, $additionalNodeList = false )
    {
        $nodeList =& eZContentCacheManager::nodeList( $objectID, $versionNum );
        if ( is_array( $additionalNodeList ) )
        {
            array_splice( $nodeList, count( $nodeList ), 0, $additionalNodeList );
        }

        eZDebugSetting::writeDebug( 'kernel-content-edit', count( $nodeList ), "count in nodeList" );

        include_once( 'kernel/classes/ezcontentcache.php' );

        eZDebug::accumulatorStart( 'node_cleanup', '', 'Node cleanup' );

        eZContentObject::expireComplexViewModeCache();
        $cleanupValue = eZContentCache::calculateCleanupValue( count( $nodeList ) );

        if ( eZContentCache::inCleanupThresholdRange( $cleanupValue ) )
        {
//                     eZDebug::writeDebug( 'cache file cleanup' );
            if ( eZContentCache::cleanup( $nodeList ) )
            {
//                     eZDebug::writeDebug( 'cache cleaned up', 'content' );
            }
        }
        else
        {
//                     eZDebug::writeDebug( 'expire all cache files' );
            eZContentObject::expireAllCache();
        }
        eZDebug::accumulatorStop( 'node_cleanup' );
    }
}

?>

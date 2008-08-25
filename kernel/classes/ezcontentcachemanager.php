<?php
//
// Definition of eZContentCacheManager class
//
// Created on: <23-Sep-2004 12:52:38 jb>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

// Clear cache types
define( 'EZ_VCSC_CLEAR_NODE_CACHE'      , 1 );
define( 'EZ_VCSC_CLEAR_PARENT_CACHE'    , 2 );
define( 'EZ_VCSC_CLEAR_RELATING_CACHE'  , 4 );
define( 'EZ_VCSC_CLEAR_KEYWORD_CACHE'   , 8 );
define( 'EZ_VCSC_CLEAR_SIBLINGS_CACHE'  , 16 );
define( 'EZ_VCSC_CLEAR_ALL_CACHE'       , 31 );
define( 'EZ_VCSC_CLEAR_DEFAULT'         , 15   ); // EZ_VCSC_CLEAR_NODE_CACHE and EZ_VCSC_CLEAR_PARENT_CACHE and EZ_VCSC_CLEAR_RELATING_CACHE and EZ_VCSC_CLEAR_KEYWORD_CACHE


include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'lib/ezutils/classes/ezini.php' );

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
            if ( is_object ( $parentNode ) )
            {
                $nodeIDList[] = $parentNode->attribute( 'node_id' );
            }
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
        $viewCacheIni = eZINI::instance( 'viewcache.ini' );
        if ( $viewCacheIni->hasVariable( 'ViewCacheSettings', 'ClearRelationTypes' ) )
        {
            $relTypes = $viewCacheIni->variable( 'ViewCacheSettings', 'ClearRelationTypes' );

            if ( !count( $relTypes ) )
                return;

            $relatedObjects = array();

            $relationsMask = 0;
            if ( in_array( 'object', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_COMMON | EZ_CONTENT_OBJECT_RELATION_EMBED;

            if ( in_array( 'common', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_COMMON;

            if ( in_array( 'embedded', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_EMBED;

            if ( in_array( 'linked', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_LINK;

            if ( in_array( 'attribute', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_ATTRIBUTE;

            if ( $relationsMask )
            {
                $objects = $object->relatedContentObjectList( false, false, false, false,
                                                              array( 'AllRelations' => $relationsMask ) );
                $previousVersionObjects = array();
                $previousVersion = $object->previousVersion();
                if ( $previousVersion )
                {
                    $previousVersionObjects = $object->relatedContentObjectList( $previousVersion, false, false, false,
                                                              array( 'AllRelations' => $relationsMask ) );
                }
                $relatedObjects = array_merge( $relatedObjects, $objects, $previousVersionObjects );
            }

            $relationsMask = 0;
            if ( in_array( 'reverse_object', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_COMMON | EZ_CONTENT_OBJECT_RELATION_EMBED;

            if ( in_array( 'reverse_common', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_COMMON;

            if ( in_array( 'reverse_embedded', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_EMBED;

            if ( in_array( 'reverse_linked', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_LINK;

            if ( in_array( 'reverse_attribute', $relTypes ) )
                $relationsMask |= EZ_CONTENT_OBJECT_RELATION_ATTRIBUTE;

            if ( $relationsMask )
            {
                $objects = $object->reverseRelatedObjectList( false, false, false, false,
                                                              array( 'AllRelations' => $relationsMask ) );
                $previousVersionObjects = array();
                $previousVersion = $object->previousVersion();
                if ( $previousVersion )
                {
                    $previousVersionObjects = $object->relatedContentObjectList( $previousVersion, false, false, false,
                                                              array( 'AllRelations' => $relationsMask ) );
                }
                $relatedObjects = array_merge( $relatedObjects, $objects, $previousVersionObjects );
            }
        }
        else
        {
            $normalRelated =& $object->relatedContentObjectArray();
            $reversedRelated =& $object->contentObjectListRelatingThis();

            $relatedObjects = array_merge( $normalRelated, $reversedRelated );
        }

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
        $nodeIDList = array_unique( $nodeIDList );
    }

    /*!
     \static
     Appends node ids of objects with the same keyword(s) as \a $object to \a $nodeIDList array.
     \param $versionNum The version of the object to use or \c true for current version
     \param[out] $nodeIDList Array with node IDs
    */
    function appendKeywordNodeIDs( &$object, $versionNum, &$nodeIDList )
    {
        if ( $versionNum === true )
            $versionNum = false;
        $keywordArray = array();
        $attributes =& $object->contentObjectAttributes( true, $versionNum );
        foreach ( array_keys( $attributes ) as $key )  // Looking for ezkeyword attributes
        {
            if ( get_class( $attributes[$key] ) == 'ezcontentobjectattribute' and
                 $attributes[$key]->attribute( 'data_type_string' ) == 'ezkeyword' )  // Found one
            {
                $keywordObject =& $attributes[$key]->content();
                if ( get_class( $keywordObject ) == 'ezkeyword' )
                {
                    foreach ( $keywordObject->attribute( 'keywords' ) as $keyword )
                    {
                        $keywordArray[] = $keyword;
                    }
                }
            }
        }

        // Find all nodes that have the given keywords
        if ( count( $keywordArray ) > 0 )
        {
            $keywordString = implode( "', '", $keywordArray );
            include_once( 'lib/ezdb/classes/ezdb.php' );
            $db = eZDB::instance();
            $keywordString = "'".$db->escapeString( $keyword )."'";
            $rows = $db->arrayQuery( "SELECT DISTINCT ezcontentobject_tree.node_id
                                       FROM
                                         ezcontentobject_tree,
                                         ezcontentobject_attribute,
                                         ezkeyword_attribute_link,
                                         ezkeyword
                                       WHERE
                                         ezcontentobject_tree.contentobject_id = ezcontentobject_attribute.contentobject_id AND
                                         ezcontentobject_attribute.id = ezkeyword_attribute_link.objectattribute_id AND
                                         ezkeyword_attribute_link.keyword_id = ezkeyword.id AND
                                         ezkeyword.keyword IN ( $keywordString )" );

            foreach ( $rows as $row )
            {
                $nodeIDList[] = $row['node_id'];
            }
        }
    }

    /*
     \static
     For each node in \a $nodeList finds its sibling nodes and adds its ids to
     the \a $nodeIDList
    */
    function appendSiblingsNodeIDs( &$nodeList, &$nodeIDList )
    {
        $params = array( 'Depth' => 1,
                         'AsObject' => false );
        foreach ( array_keys( $nodeList ) as $nodeKey )
        {
            $node =& $nodeList[$nodeKey];
            $siblingNodeList =& eZContentObjectTreeNode::subTree( $params, $node->attribute( 'parent_node_id' ) );
            if ( count( $siblingNodeList ) > 0 )
            {
                foreach ( array_keys( $siblingNodeList ) as $siblingKey )
                {
                    $nodeIDList[] = $siblingNodeList[$siblingKey]['node_id'];
                }
            }
        }
    }

    /*
     \static
     Reads 'viewcache.ini' file and determines relation between
     \a $classID and another class.

     \return An associative array with information on the class, containsL:
             - dependent_class_identifier - The class identifier of objects that depend on this class
             - additional_objects - Array of additional arbitrary object ids to clear
             - max_parents - The maxium number of parent nodes to check, or \c 0 for no limit
             - clear_cache_type - Bitfield of clear types, see nodeListForObject() for more details
             - object_filter - Array with object IDs, if there are entries only these objects should be checked.
    */
    function dependencyInfo( $classID, $ignoreINISettings = false )
    {
        $ini =& eZINI::instance( 'viewcache.ini' );
        $info = false;

        if ( $ignoreINISettings || $ini->variable( 'ViewCacheSettings', 'SmartCacheClear' ) == 'enabled' )
        {
            if ( $ini->hasGroup( $classID ) )
            {
                $info = array();
                if ( $ini->hasVariable( $classID, 'DependentClassIdentifier' ) )
                    $info['dependent_class_identifier'] = $ini->variable( $classID, 'DependentClassIdentifier' );

                if ( $ini->hasVariable( $classID, 'MaxParents' ) )
                    $info['max_parents'] = $ini->variable( $classID, 'MaxParents' );
                else
                    $info['max_parents'] = 0;

                if ( $ini->hasVariable( $classID, 'AdditionalObjectIDs' ) )
                    $info['additional_objects'] = $ini->variable( $classID, 'AdditionalObjectIDs' );

                $info['clear_cache_type'] = 0;
                if ( $ini->hasVariable( $classID, 'ClearCacheMethod' ) )
                {
                    $type = $ini->variable( $classID, 'ClearCacheMethod' );

                    if ( is_array( $type ) )
                    {
                        if ( in_array( 'all', $type ) )
                        {
                            $info['clear_cache_type'] = EZ_VCSC_CLEAR_ALL_CACHE;
                        }
                        else
                        {
                            if ( in_array( 'object', $type ) )
                                $info['clear_cache_type'] |= EZ_VCSC_CLEAR_NODE_CACHE;

                            if ( in_array( 'parent', $type ) )
                                $info['clear_cache_type'] |= EZ_VCSC_CLEAR_PARENT_CACHE;

                            if ( in_array( 'relating', $type ) )
                                $info['clear_cache_type'] |= EZ_VCSC_CLEAR_RELATING_CACHE;

                            if ( in_array( 'keyword', $type ) )
                                $info['clear_cache_type'] |= EZ_VCSC_CLEAR_KEYWORD_CACHE;

                            if ( in_array( 'siblings', $type ) )
                                $info['clear_cache_type'] |= EZ_VCSC_CLEAR_SIBLINGS_CACHE;
                        }
                    }
                    else
                    {
                        // deprecated
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

                            if ( $type == 'clear_keyword_caches_only' )
                            {
                                $info['clear_cache_type'] |= EZ_VCSC_CLEAR_KEYWORD_CACHE;
                            }
                        }
                    }
                }
                else
                {
                    $info['clear_cache_type'] = EZ_VCSC_CLEAR_DEFAULT;
                }

                $info['object_filter'] = array();
                if ( $ini->hasVariable( $classID, 'ObjectFilter' ) )
                {
                    $info['object_filter'] = $ini->variable( $classID, 'ObjectFilter' );
                }
            }
        }

        return $info;
    }

    /*
     Can be used to debug the \a $handledObjectList parameter of nodeListForObject()

     \static
    */
    function writeDebugBits( $handledObjectList, $highestBit )
    {
        $bitPadLength = (int)( pow( $highestBit, 0.5 ) + 1 );
        //$bitPadLength = strlen( decbin( $highestBit ) );

        $objectIDList = array_keys( $handledObjectList );
        $maxObjectID = max( $objectIDList );
        $padLength = strlen( $maxObjectID ) + 2;

        $msg = '';
        foreach ( $handledObjectList as $objectID => $clearCacheType )
        {
            $bitString = decbin( $clearCacheType );
            $msg .= str_pad( $objectID, $padLength, ' ', STR_PAD_RIGHT ) . str_pad( $bitString, $bitPadLength, '0', STR_PAD_LEFT );
            $msg .= "\r\n";
        }

        eZDebug::writeDebug( $msg, 'eZContentCacheManager::writeDebugBits()' );
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
                            - EZ_VCSC_CLEAR_KEYWORD_CACHE - Clear nodes of objects that have the same keyword as this object
                            - EZ_VCSC_CLEAR_SIBLINGS_CACHE - Clear caches for siblings of the node.
                            - EZ_VCSC_CLEAR_ALL_CACHE - Enables all of the above
     \param[out] $nodeList An array with node IDs that are affected by the current object change.
     \param[out] $handledObjectList An associative array with object IDs and the cache types that were handled for these objects already.
                                    Used to avoid infinite recursion.

     \note This function is recursive.
    */
    function nodeListForObject( &$contentObject, $versionNum, $clearCacheType, &$nodeList, &$handledObjectList )
    {
        $contentObjectID = $contentObject->attribute( 'id' );

        if ( isset( $handledObjectList[$contentObjectID] ) )
        {
            $handledObjectList[$contentObjectID] |= $clearCacheType;
        }
        else
        {
            $handledObjectList[$contentObjectID] = $clearCacheType;
        }
        //self::writeDebugBits( $handledObjectList, EZ_VCSC_CLEAR_SIBLINGS_CACHE );

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

        if ( $clearCacheType & EZ_VCSC_CLEAR_KEYWORD_CACHE )
        {
            eZContentCacheManager::appendKeywordNodeIDs( $contentObject, $versionNum, $nodeList );
        }

        if ( $clearCacheType & EZ_VCSC_CLEAR_SIBLINGS_CACHE )
        {
            eZContentCacheManager::appendSiblingsNodeIDs( $assignedNodes, $nodeList );
        }

        // determine if $contentObject has dependent objects for which cache should be cleared too.
        $objectClassIdentifier =  $contentObject->attribute( 'class_identifier' );
        $dependentClassInfo = eZContentCacheManager::dependencyInfo( $objectClassIdentifier );

        if ( $dependentClassInfo['clear_cache_type'] & EZ_VCSC_CLEAR_SIBLINGS_CACHE )
        {
            if ( !( $clearCacheType & EZ_VCSC_CLEAR_SIBLINGS_CACHE ) )
            {
                eZContentCacheManager::appendSiblingsNodeIDs( $assignedNodes, $nodeList );
                $handledObjectList[$contentObjectID] |= EZ_VCSC_CLEAR_SIBLINGS_CACHE;
            }

            // drop 'siblings' bit and process parent nodes.
            // since 'sibling' mode is affected to the current object
            $dependentClassInfo['clear_cache_type'] &= ~EZ_VCSC_CLEAR_SIBLINGS_CACHE;
        }

        if ( isset( $dependentClassInfo['additional_objects'] ) )
        {
            foreach( $dependentClassInfo['additional_objects'] as $objectID )
            {
                // skip if cache type is already handled for this object
                if ( isset( $handledObjectList[$objectID] ) && $handledObjectList[$objectID] & EZ_VCSC_CLEAR_NODE_CACHE )
                {
                    continue;
                }

                $object =& eZContentObject::fetch( $objectID );
                if ( $object )
                {
                    //eZDebug::writeDebug( 'adding additional object ' . $objectID, 'eZContentCacheManager::nodeListForObject() for object ' . $contentObjectID );
                    eZContentCacheManager::nodeListForObject( $object, true, EZ_VCSC_CLEAR_NODE_CACHE, $nodeList, $handledObjectList );
                }
            }
        }

        if ( isset( $dependentClassInfo['dependent_class_identifier'] ) )
        {
            $maxParents = $dependentClassInfo['max_parents'];
            $dependentClassIdentifiers = $dependentClassInfo['dependent_class_identifier'];
            $smartClearType = $dependentClassInfo['clear_cache_type'];

            // getting 'path_string's for all locations.
            $nodePathList =& eZContentCacheManager::fetchNodePathString( $assignedNodes );

            foreach ( $nodePathList as $nodePath )
            {
                $step = 0;

                // getting class identifier and node ID for each node in the $nodePath, up to $maxParents
                $nodeInfoList =& eZContentObjectTreeNode::fetchClassIdentifierListByPathString( $nodePath, false, $maxParents );

                // for each node in $nodeInfoList determine if this node belongs to $dependentClassIdentifiers. If
                // so then clear cache for this node.
                foreach ( $nodeInfoList as $item )
                {
                    if ( in_array( $item['class_identifier'], $dependentClassIdentifiers ) )
                    {
                        $object =& eZContentObject::fetchByNodeID( $item['node_id'] );
                        $objectID = $object->attribute( 'id' );

                        if ( isset( $handledObjectList[$objectID] ) )
                        {
                            // remove cache types that were already handled
                            $smartClearType &= ~$handledObjectList[$objectID];

                            // if there are no cache types remaining, then skip
                            if ( $smartClearType == 0 )
                            {
                                continue;
                            }
                        }

                        if ( count( $dependentClassInfo['object_filter'] ) > 0 )
                        {
                            if ( in_array( $objectID, $dependentClassInfo['object_filter'] ) )
                            {
                                //eZDebug::writeDebug( 'adding parent ' . $objectID, 'eZContentCacheManager::nodeListForObject() for object ' . $contentObjectID );
                                eZContentCacheManager::nodeListForObject( $object, true, $smartClearType, $nodeList, $handledObjectList );
                            }
                        }
                        else
                        {
                            //eZDebug::writeDebug( 'adding parent ' . $objectID, 'eZContentCacheManager::nodeListForObject() for object ' . $contentObjectID );
                            eZContentCacheManager::nodeListForObject( $object, true, $smartClearType, $nodeList, $handledObjectList );
                        }
                    }
                }
            }
        }

        //self::writeDebugBits( $handledObjectList, EZ_VCSC_CLEAR_SIBLINGS_CACHE );
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
        if ( !$object )
        {
            $nodeList = false;
            return $nodeList;
        }

        eZContentCacheManager::nodeListForObject( $object, $versionNum, EZ_VCSC_CLEAR_DEFAULT, $nodeList, $handledObjectList );

        return $nodeList;
    }

    /*!
     \static
     Depreciated. Use 'clearObjectViewCache' instead
    */
    function clearViewCache( $objectID, $versionNum = true , $additionalNodeList = false )
    {
        eZDebug::writeWarning( "'clearViewCache' function was depreciated. Use 'clearObjectViewCache' instead", 'eZContentCacheManager::clearViewCache' );
        eZContentCacheManager::clearObjectViewCache( $objectID, $versionNum, $additionalNodeList );
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
    function clearObjectViewCache( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        eZDebug::accumulatorStart( 'node_cleanup_list', '', 'Node cleanup list' );

        $nodeList =& eZContentCacheManager::nodeList( $objectID, $versionNum );

        if ( $nodeList === false and !is_array( $additionalNodeList ) )
            return false;

        if ( $nodeList === false )
        {
            $nodeList = array();
        }

        if ( is_array( $additionalNodeList ) )
        {
            array_splice( $nodeList, count( $nodeList ), 0, $additionalNodeList );
        }

        if ( count( $nodeList ) == 0 )
        {
            return false;
        }

        $nodeList = array_unique( $nodeList );

        eZDebug::accumulatorStop( 'node_cleanup_list' );

        eZDebugSetting::writeDebug( 'kernel-content-edit', count( $nodeList ), "count in nodeList" );

        $ini =& eZINI::instance();
        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            include_once( 'kernel/classes/ezstaticcache.php' );
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $staticCache = new eZStaticCache();
            $staticCache->generateAlwaysUpdatedCache();
            $staticCache->generateNodeListCache( eZContentObjectTreeNode::fetchAliasesFromNodeList( $nodeList ) );
        }

        include_once( 'kernel/classes/ezcontentcache.php' );

        eZDebug::accumulatorStart( 'node_cleanup', '', 'Node cleanup' );

        eZContentObject::expireComplexViewModeCache();
        $cleanupValue = eZContentCache::calculateCleanupValue( count( $nodeList ) );

        if ( eZContentCache::inCleanupThresholdRange( $cleanupValue ) )
            eZContentCache::cleanup( $nodeList );
        else
            eZContentObject::expireAllViewCache();

        eZDebug::accumulatorStop( 'node_cleanup' );
        return true;
    }

    /*!
     \static
     Clears view cache for specified object.
     Checks 'ViewCaching' ini setting to determine whether cache is enabled or not.
    */
    function clearObjectViewCacheIfNeeded( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        $ini = eZINI::instance();
        if ( $ini->variable( 'ContentSettings', 'ViewCaching' ) === 'enabled' )
            eZContentCacheManager::clearObjectViewCache( $objectID, $versionNum, $additionalNodeList );
    }

    /*!
     \static
     Clears template-block cache and template-block with subtree_expiry parameter caches for specified object.
     Checks 'TemplateCache' ini setting to determine whether cache is enabled or not.
     If $objectID is \c false all template block caches will be cleared.
    */
    function clearTemplateBlockCacheIfNeeded( $objectID )
    {
        $ini = eZINI::instance();
        if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) === 'enabled' )
            eZContentCacheManager::clearTemplateBlockCache( $objectID );
    }

    /*!
     \static
     Clears template-block cache and template-block with subtree_expiry parameter caches for specified object
     without checking 'TemplateCache' ini setting. If $objectID is \c false all template block caches will be cleared.
    */
    function clearTemplateBlockCache( $objectID )
    {
        // ordinary template block cache
        eZContentObject::expireTemplateBlockCache();

        // subtree template block cache
        $nodeList = false;
        $object = false;
        if ( $objectID )
            $object = eZContentObject::fetch( $objectID );
        if ( $object )
            $nodeList =& $object->assignedNodes();

        include_once( 'kernel/classes/ezsubtreecache.php' );
        eZSubtreeCache::cleanup( $nodeList );
    }

    /*!
     \static
     Generates the related viewcaches (PreGeneration) for the content object.
     It will only do this if [ContentSettings]/PreViewCache in site.ini is enabled.

     \param $objectID The ID of the content object to generate caches for.
    */
    function generateObjectViewCache( $objectID )
    {
        // Generate the view cache
        $ini =& eZINI::instance();
        $object = eZContentObject::fetch( $objectID );
        $user =& eZUser::currentUser();

        include_once( 'kernel/classes/eznodeviewfunctions.php' );
        eZDebug::accumulatorStart( 'generate_cache', '', 'Generating view cache' );
        if ( $ini->variable( 'ContentSettings', 'PreViewCache' ) == 'enabled' )
        {
            $preCacheSiteaccessArray = $ini->variable( 'ContentSettings', 'PreCacheSiteaccessArray' );

            $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];

            // This is the default view parameters for content/view
            $viewParameters = array( 'offset' => 0,
                                     'year' => false,
                                     'month' => false,
                                     'day' => false,
                                     'namefilter' => false );

            foreach ( $preCacheSiteaccessArray as $changeToSiteAccess )
            {
                $GLOBALS['eZCurrentAccess']['name'] = $changeToSiteAccess;

                if ( $GLOBALS['eZCurrentAccess']['type'] == EZ_ACCESS_TYPE_URI )
                {
                    eZSys::clearAccessPath();
                    eZSys::addAccessPath( $changeToSiteAccess );
                }

                include_once( 'kernel/common/template.php' );
                $tpl =& templateInit();
                $res =& eZTemplateDesignResource::instance();

                // Get the sitedesign and cached view preferences for this siteaccess
                $siteini = eZINI::instance( 'site.ini', 'settings', null, null, false );
                $siteini->prependOverrideDir( "siteaccess/$changeToSiteAccess", false, 'siteaccess' );
                $siteini->loadCache();
                $designSetting = $siteini->variable( "DesignSettings", "SiteDesign" );
                $cachedViewPreferences = $siteini->variable( 'ContentSettings', 'CachedViewPreferences' );
                $res->setDesignSetting( $designSetting, 'site' );

                $res->setOverrideAccess( $changeToSiteAccess );

                $language = false; // Needs to be specified if you want to generate the cache for a specific language
                $viewMode = 'full';

                $assignedNodes =& $object->assignedNodes();
                $assignedNodes_keys = array_keys( $assignedNodes );
                foreach ( $assignedNodes_keys as $key )
                {
                    $node =& $assignedNodes[$key];

                    // We want to generate the cache for the specified user
                    $previewCacheUsers = $ini->variable( 'ContentSettings', 'PreviewCacheUsers' );
                    foreach ( $previewCacheUsers as $previewCacheUserID )
                    {
                        // If the text is 'anon' we need to fetch the Anonymous user ID.
                        if ( $previewCacheUserID === 'anonymous' )
                        {
                            $previewCacheUserID = $siteini->variable( "UserSettings", "AnonymousUserID" );
                            $previewCacheUser = eZUser::fetch( $previewCacheUserID  );
                        }
                        else if ( $previewCacheUserID === 'current' )
                        {
                            $previewCacheUser =& $user;
                        }
                        else
                        {
                            $previewCacheUser = eZUser::fetch( $previewCacheUserID  );
                        }
                        if ( !$previewCacheUser )
                            continue;

                        // Before we generate the view cache we must change the currently logged in user to $previewCacheUser
                        // If not the templates might read in wrong personalized data (preferences etc.)
                        $previewCacheUser->setCurrentlyLoggedInUser( $previewCacheUser, $previewCacheUser->attribute( 'contentobject_id' ) );

                        // Cache the current node
                        $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $previewCacheUser, $node->attribute( 'node_id' ), 0, false, $language, $viewMode, $viewParameters, $cachedViewPreferences );
                        $tmpRes =& eZNodeviewfunctions::generateNodeView( $tpl, $node, $node->attribute( 'object' ), $language, $viewMode, 0, $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], true, $viewParameters );

                        // Cache the parent node
                        $parentNode =& $node->attribute( 'parent' );
                        $objectID = $parentNode->attribute( 'contentobject_id' );
                        // if parent objectID is null or is 0 we should not create cache.
                        if ( $objectID )
                        {
                            $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $previewCacheUser, $parentNode->attribute( 'node_id' ), 0, false, $language, $viewMode, $viewParameters, $cachedViewPreferences );
                            $tmpRes =& eZNodeviewfunctions::generateNodeView( $tpl, $parentNode, $parentNode->attribute( 'object' ), $language, $viewMode, 0, $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], true, $viewParameters );
                        }
                    }
                }
            }
            // Restore the old user as the current one
            $user->setCurrentlyLoggedInUser( $user, $user->attribute( 'contentobject_id' ) );

            $GLOBALS['eZCurrentAccess']['name'] = $currentSiteAccess;
            $res->setDesignSetting( $currentSiteAccess, 'site' );
            $res->setOverrideAccess( false );
            if ( $GLOBALS['eZCurrentAccess']['type'] == EZ_ACCESS_TYPE_URI )
            {
                eZSys::clearAccessPath();
                eZSys::addAccessPath( $currentSiteAccess );
            }
        }

        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            include_once( 'kernel/classes/ezstaticcache.php' );
            include_once( 'kernel/classes/ezcontentcachemanager.php' );

            $nodes = array();
            $ini =& eZINI::instance();
            $staticCache = new eZStaticCache();
            $useURLAlias =& $GLOBALS['eZContentObjectTreeNodeUseURLAlias'];
            $pathPrefix = $ini->variable( 'SiteAccessSettings', 'PathPrefix' );

            if ( !isset( $useURLAlias ) )
            {
                $useURLAlias = $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled';
            }

            eZContentCacheManager::nodeListForObject( $object, true, EZ_VCSC_CLEAR_DEFAULT, $nodes, $handledObjectList );
            foreach ( $nodes as $nodeID )
            {
                if ( $useURLAlias )
                {
                    $aNode = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                    if ( !isset( $aNode ) )
                        continue;

                    $urlAlias = $aNode['path_identification_string'];
                    if ( $pathPrefix != '' )
                    {
                        $tempAlias = substr( $pathPrefix, strlen( $pathPrefix ) -1 ) == '/'
                                        ? $urlAlias . '/'
                                        : $urlAlias;
                        if ( strncmp( $tempAlias, $pathPrefix, strlen( $tempAlias) ) == 0 )
                            $urlAlias = substr( $tempAlias, strlen( $pathPrefix ) );
                    }
                }
                else
                {
                    $urlAlias = 'content/view/full/' . $nodeID;
                }
                $staticCache->cacheURL( '/' . $urlAlias, $nodeID );
            }
            $staticCache->generateAlwaysUpdatedCache();
        }
        eZDebug::accumulatorStop( 'generate_cache' );
    }

    /*!
     \static
     Clears content cache if needed by \a $sectionID
    */
    function clearContentCacheIfNeededBySectionID( $sectionID )
    {
        // fetch all objects of this section
        $objectList = eZContentObject::fetchList( false, array( 'section_id' => "$sectionID" ) );
        // Clear cache
        foreach ( $objectList as $object )
        {
            eZContentCacheManager::clearContentCacheIfNeeded( $object['id'] );
        }
        return true;
    }

    /*!
     \static
     Clears content cache for specified object: view cache, template-block cache, template-block with subtree_expiry parameter cache.
     Checks appropriate ini settings to determine whether caches are enabled or not.
    */
    function clearContentCacheIfNeeded( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        eZDebug::accumulatorStart( 'check_cache', '', 'Check cache' );

        eZContentCacheManager::clearObjectViewCacheIfNeeded( $objectID, $versionNum, $additionalNodeList );
        eZContentCacheManager::clearTemplateBlockCacheIfNeeded( $objectID );

        // Clear cached path strings of content SSL zones.
        include_once( 'kernel/classes/ezsslzone.php' );
        eZSSLZone::clearCacheIfNeeded();

        eZDebug::accumulatorStop( 'check_cache' );
        return true;
    }

    /*!
     \static
     Clears content cache for specified object: view cache, template-block cache, template-block with subtree_expiry parameter cache
     without checking of ini settings.
    */
    function clearContentCache( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        eZDebug::accumulatorStart( 'check_cache', '', 'Check cache' );

        eZContentCacheManager::clearObjectViewCache( $objectID, $versionNum, $additionalNodeList );
        eZContentCacheManager::clearTemplateBlockCache( $objectID );

        // Clear cached path strings of content SSL zones.
        include_once( 'kernel/classes/ezsslzone.php' );
        eZSSLZone::clearCache();

        eZDebug::accumulatorStop( 'check_cache' );
        return true;
    }

    /*!
     \static
     Clears all content cache: view cache, template-block cache, template-block with subtree_expiry parameter cache.
    */
    function clearAllContentCache( $ignoreINISettings = false )
    {
        if ( !$ignoreINISettings )
        {
            $ini = eZINI::instance();
            $viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) === 'enabled' );
            $templateCacheEnabled = ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) === 'enabled' );
        }
        else
        {
            $viewCacheEnabled = true;
            $templateCacheEnabled = true;
        }

        if ( $viewCacheEnabled || $templateCacheEnabled )
        {
            // view cache and/or ordinary template block cache
            eZContentObject::expireAllCache();

            // subtree template block caches
            if ( $templateCacheEnabled )
            {
                include_once( 'kernel/classes/ezsubtreecache.php' );
                eZSubtreeCache::cleanupAll();
            }
        }
    }
}

?>

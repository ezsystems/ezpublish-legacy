<?php
/**
 * File containing the eZContentCacheManager class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
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
    // Clear cache types
    const CLEAR_NO_CACHE       = 0;
    const CLEAR_NODE_CACHE     = 1;
    const CLEAR_PARENT_CACHE   = 2;
    const CLEAR_RELATING_CACHE = 4;
    const CLEAR_KEYWORD_CACHE  = 8;
    const CLEAR_SIBLINGS_CACHE = 16;
    const CLEAR_CHILDREN_CACHE = 32;
    const CLEAR_ALL_CACHE      = 63;
    const CLEAR_DEFAULT        = 15; // CLEAR_NODE_CACHE and CLEAR_PARENT_CACHE and CLEAR_RELATING_CACHE and CLEAR_KEYWORD_CACHE

    /**
     * Hash of additional NodeIDs to append the node list, for clearing view cache.
     * Indexed by contentObjectID.
     *
     * @var array
     */
    private static $additionalNodeIDsPerObject = array();

    /**
     * Adds an additional NodeID to be appended to the node list for clearing view cache.
     *
     * @param int $contentObjectID
     * @param int $additionalNodeID
     */
    public static function addAdditionalNodeIDPerObject( $contentObjectID, $additionalNodeID )
    {
        if ( !isset( self::$additionalNodeIDsPerObject[$contentObjectID] ) )
        {
            self::$additionalNodeIDsPerObject[$contentObjectID] = array();
        }

        self::$additionalNodeIDsPerObject[$contentObjectID][] = $additionalNodeID;
    }

    /**
     * Appends additional node IDs.
     *
     * @param eZContentObject $contentObject
     * @param array $nodeIDList
     */
    private static function appendAdditionalNodeIDs( eZContentObject $contentObject, &$nodeIDList )
    {
        $contentObjectId = $contentObject->attribute( 'id' );
        if ( !isset( self::$additionalNodeIDsPerObject[$contentObjectId] ) )
            return;

        foreach ( self::$additionalNodeIDsPerObject[$contentObjectId] as $nodeID )
        {
            $nodeIDList[] = $nodeID;
        }
    }

    /*!
     \static
     Appends parent nodes ids of \a $object to \a $nodeIDList array.
     \param $versionNum The version of the object to use or \c true for current version
     \param[out] $nodeIDList Array with node IDs
    */
    static function appendParentNodeIDs( $object, $versionNum, &$nodeIDList )
    {
        foreach ( $object->parentNodeIDArray() as $parentNodeID )
        {
            $nodeIDList[] = $parentNodeID;
        }
    }

    /*!
     \static
     Appends nodes ids from \a $nodeList list to \a $nodeIDList
     \param[out] $nodeIDList Array with node IDs
    */
    static function appendNodeIDs( $nodeList, &$nodeIDList )
    {
        foreach ( $nodeList as $node )
        {
            $nodeIDList[] = $node->attribute( 'node_id' );
        }
    }

    /*!
     \static
     Goes through all content nodes in \a $nodeList and extracts the \c 'path_string'.
     \return An array with \c 'path_string' information.
    */
    static function fetchNodePathString( $nodeList )
    {
        $pathList = array();
        foreach ( $nodeList as $node )
        {
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
    static function appendRelatingNodeIDs( $object, &$nodeIDList )
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
                $relationsMask |= eZContentObject::RELATION_COMMON | eZContentObject::RELATION_EMBED;

            if ( in_array( 'common', $relTypes ) )
                $relationsMask |= eZContentObject::RELATION_COMMON;

            if ( in_array( 'embedded', $relTypes ) )
                $relationsMask |= eZContentObject::RELATION_EMBED;

            if ( in_array( 'linked', $relTypes ) )
                $relationsMask |= eZContentObject::RELATION_LINK;

            if ( in_array( 'attribute', $relTypes ) )
                $relationsMask |= eZContentObject::RELATION_ATTRIBUTE;

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
                $relationsMask |= eZContentObject::RELATION_COMMON | eZContentObject::RELATION_EMBED;

            if ( in_array( 'reverse_common', $relTypes ) )
                $relationsMask |= eZContentObject::RELATION_COMMON;

            if ( in_array( 'reverse_embedded', $relTypes ) )
                $relationsMask |= eZContentObject::RELATION_EMBED;

            if ( in_array( 'reverse_linked', $relTypes ) )
                $relationsMask |= eZContentObject::RELATION_LINK;

            if ( in_array( 'reverse_attribute', $relTypes ) )
                $relationsMask |= eZContentObject::RELATION_ATTRIBUTE;

            if ( $relationsMask )
            {
                $objects = $object->reverseRelatedObjectList( false, false, false,
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
            $normalRelated = $object->relatedContentObjectArray();
            $reversedRelated = $object->contentObjectListRelatingThis();

            $relatedObjects = array_merge( $normalRelated, $reversedRelated );
        }

        foreach ( $relatedObjects as $relatedObject )
        {
            $assignedNodes = $relatedObject->assignedNodes( false );
            foreach ( $assignedNodes as $assignedNode )
            {
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
    static function appendKeywordNodeIDs( $object, $versionNum, &$nodeIDList, $limit = null )
    {
        if ( $limit === 0 )
            return;
        if ( $versionNum === true )
            $versionNum = false;
        $keywordArray = array();
        $attributes = $object->contentObjectAttributes( true, $versionNum );
        foreach ( array_keys( $attributes ) as $key )  // Looking for ezkeyword attributes
        {
            if ( $attributes[$key] instanceof eZContentObjectAttribute and
                 $attributes[$key]->attribute( 'data_type_string' ) == 'ezkeyword' )  // Found one
            {
                $keywordObject = $attributes[$key]->content();
                if ( $keywordObject instanceof eZKeyword )
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
            $db = eZDB::instance();
            foreach( $keywordArray as $k => $keyword )
            {
                $keywordArray[$k] = "'" . $db->escapeString( $keyword ) . "'";
            }
            $keywordString = implode( ', ', $keywordArray );
            $params = $limit ? array( 'offset' => 0, 'limit'  => $limit ) : array();
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
                                         ezkeyword.keyword IN ( $keywordString )",
                                        $params );

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
    static function appendSiblingsNodeIDs( &$nodeList, &$nodeIDList )
    {
        $params = array( 'Depth' => 1,
                         'AsObject' => false );
        foreach ( $nodeList as $node )
        {
            $siblingNodeList = eZContentObjectTreeNode::subTreeByNodeID( $params, $node->attribute( 'parent_node_id' ) );
            if ( count( $siblingNodeList ) > 0 )
            {
                foreach ( array_keys( $siblingNodeList ) as $siblingKey )
                {
                    $nodeIDList[] = $siblingNodeList[$siblingKey]['node_id'];
                }
            }
        }
    }

    /**
     * For each node in $nodeList finds its children nodes and adds its ids to
     * the $nodeIDList.
     *
     * @param array(eZContentObjectTreeNode) $nodeList
     * @param array(int) $nodeIDList
     */
    public static function appendChildrenNodeIDs( &$nodeList, &$nodeIDList )
    {
        $params = array( 'Depth' => 1,
                         'AsObject' => false );
        foreach ( $nodeList as $node )
        {
            $childNodeList = eZContentObjectTreeNode::subTreeByNodeID( $params, $node->attribute( 'node_id' ) );
            if ( !empty( $childNodeList ) )
            {
                foreach ( $childNodeList as $childNode )
                {
                    $nodeIDList[] = $childNode['node_id'];
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
    static function dependencyInfo( $classID, $ignoreINISettings = false )
    {
        $ini = eZINI::instance( 'viewcache.ini' );
        $info = false;

        if ( $ignoreINISettings || $ini->variable( 'ViewCacheSettings', 'SmartCacheClear' ) !== 'disabled' )
        {
            if ( $ini->hasGroup( $classID ) )
            {
                $info = array();
                $info['clear_cache_exclusive'] = $ini->variable( 'ViewCacheSettings', 'SmartCacheClear' ) === 'exclusive';

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
                        if ( in_array( 'none', $type ) )
                        {
                            $info['clear_cache_type'] = self::CLEAR_NO_CACHE;
                        }
                        elseif ( in_array( 'all', $type ) )
                        {
                            $info['clear_cache_type'] = self::CLEAR_ALL_CACHE;
                        }
                        else
                        {
                            if ( in_array( 'object', $type ) )
                                $info['clear_cache_type'] |= self::CLEAR_NODE_CACHE;

                            if ( in_array( 'parent', $type ) )
                                $info['clear_cache_type'] |= self::CLEAR_PARENT_CACHE;

                            if ( in_array( 'relating', $type ) )
                                $info['clear_cache_type'] |= self::CLEAR_RELATING_CACHE;

                            if ( in_array( 'keyword', $type ) )
                                $info['clear_cache_type'] |= self::CLEAR_KEYWORD_CACHE;

                            if ( in_array( 'siblings', $type ) )
                                $info['clear_cache_type'] |= self::CLEAR_SIBLINGS_CACHE;

                            if ( in_array( 'children', $type ) )
                                $info['clear_cache_type'] |= self::CLEAR_CHILDREN_CACHE;
                        }
                    }
                }
                else
                {
                    $info['clear_cache_type'] = self::CLEAR_DEFAULT;
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
    */
    static function writeDebugBits( $handledObjectList, $highestBit )
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

        eZDebug::writeDebug( $msg, __METHOD__ );
    }

    /*!
     \static
     Use \a $clearCacheType to include different kind of nodes( parent, relating, etc ).
     If \a $versionNum is true, then current version will be used.

     \param $contentObject Current content object that is checked.
     \param $versionNum The version of the object to use or \c true for current version
     \param $clearCacheType Bit field which controls the the extra nodes to include,
                            use bitwise or with one of these defines:
                            - self::CLEAR_NODE_CACHE - Clear the nodes of the object
                            - self::CLEAR_PARENT_CACHE - Clear the parent nodes of the object
                            - self::CLEAR_RELATING_CACHE - Clear nodes of objects that relate this object
                            - self::CLEAR_KEYWORD_CACHE - Clear nodes of objects that have the same keyword as this object
                            - self::CLEAR_SIBLINGS_CACHE - Clear caches for siblings of the node.
                            - self::CLEAR_ALL_CACHE - Enables all of the above
                            - self::CLEAR_NO_CACHE - Do not clear cache for current object.
     \param[out] $nodeList An array with node IDs that are affected by the current object change.
     \param[out] $handledObjectList An associative array with object IDs and the cache types that were handled for these objects already.
                                    Used to avoid infinite recursion.

     \note This function is recursive.
    */
    static function nodeListForObject( $contentObject, $versionNum, $clearCacheType, &$nodeList, &$handledObjectList )
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
        //self::writeDebugBits( $handledObjectList, self::CLEAR_SIBLINGS_CACHE );

        $assignedNodes = $contentObject->assignedNodes();

        // determine if $contentObject has dependent objects for which cache should be cleared too.
        $objectClassIdentifier = $contentObject->attribute( 'class_identifier' );
        $dependentClassInfo = eZContentCacheManager::dependencyInfo( $objectClassIdentifier );

        if ( $dependentClassInfo['clear_cache_type'] === self::CLEAR_NO_CACHE )
        {
            // BC: Allow smart cache clear setting to specify no caching setting
            $clearCacheType = self::CLEAR_NO_CACHE;
        }
        else if ( $dependentClassInfo['clear_cache_exclusive'] === true )
        {
            // use class specific smart cache rules exclusivly
            $clearCacheType = $dependentClassInfo['clear_cache_type'];
        }

        if ( $clearCacheType === self::CLEAR_NO_CACHE )
        {
            // when recursing we will never have to handle this object again for other cache types
            // because types of caches to clear will always be set to none
            $handledObjectList[$contentObjectID] = self::CLEAR_ALL_CACHE;
        }

        if ( $clearCacheType & self::CLEAR_NODE_CACHE )
        {
            eZContentCacheManager::appendNodeIDs( $assignedNodes, $nodeList );
        }

        if ( $clearCacheType & self::CLEAR_PARENT_CACHE )
        {
            eZContentCacheManager::appendParentNodeIDs( $contentObject, $versionNum, $nodeList );
        }

        if ( $clearCacheType & self::CLEAR_RELATING_CACHE )
        {
            eZContentCacheManager::appendRelatingNodeIDs( $contentObject, $nodeList );
        }

        if ( $clearCacheType & self::CLEAR_KEYWORD_CACHE )
        {
            $keywordClearLimit = null;
            $viewcacheini = eZINI::instance( 'viewcache.ini' );
            if ( is_numeric( $viewcacheini->variable( 'ViewCacheSettings', 'KeywordNodesCacheClearLimit' ) ) )
                $keywordClearLimit = (int) $viewcacheini->variable( 'ViewCacheSettings', 'KeywordNodesCacheClearLimit' );

            eZContentCacheManager::appendKeywordNodeIDs( $contentObject, $versionNum, $nodeList, $keywordClearLimit );
        }

        if ( $clearCacheType & self::CLEAR_SIBLINGS_CACHE )
        {
            eZContentCacheManager::appendSiblingsNodeIDs( $assignedNodes, $nodeList );
        }

        if ( $dependentClassInfo['clear_cache_type'] & self::CLEAR_SIBLINGS_CACHE )
        {
            if ( !( $clearCacheType & self::CLEAR_SIBLINGS_CACHE ) )
            {
                eZContentCacheManager::appendSiblingsNodeIDs( $assignedNodes, $nodeList );
                $handledObjectList[$contentObjectID] |= self::CLEAR_SIBLINGS_CACHE;
            }

            // drop 'siblings' bit and process parent nodes.
            // since 'sibling' mode is affected to the current object
            $dependentClassInfo['clear_cache_type'] &= ~self::CLEAR_SIBLINGS_CACHE;
        }

        if ( $clearCacheType & self::CLEAR_CHILDREN_CACHE )
        {
            eZContentCacheManager::appendChildrenNodeIDs( $assignedNodes, $nodeList );
        }

        if ( $dependentClassInfo['clear_cache_type'] & self::CLEAR_CHILDREN_CACHE )
        {
            if ( !( $clearCacheType & self::CLEAR_CHILDREN_CACHE ) )
            {
                eZContentCacheManager::appendChildrenNodeIDs( $assignedNodes, $nodeList );
                $handledObjectList[$contentObjectID] |= self::CLEAR_CHILDREN_CACHE;
            }
            // drop 'children' bit and process parent nodes.
            // since 'children' mode is affected to the current object
            $dependentClassInfo['clear_cache_type'] &= ~self::CLEAR_CHILDREN_CACHE;
        }

        if ( isset( $dependentClassInfo['additional_objects'] ) )
        {
            foreach( $dependentClassInfo['additional_objects'] as $objectID )
            {
                // skip if cache type is already handled for this object
                if ( isset( $handledObjectList[$objectID] ) && $handledObjectList[$objectID] & self::CLEAR_NODE_CACHE )
                {
                    continue;
                }

                $object = eZContentObject::fetch( $objectID );
                if ( $object )
                {
                    //eZDebug::writeDebug( 'adding additional object ' . $objectID, 'eZContentCacheManager::nodeListForObject() for object ' . $contentObjectID );
                    eZContentCacheManager::nodeListForObject( $object, true, self::CLEAR_NODE_CACHE, $nodeList, $handledObjectList );
                }
            }
        }

        if ( isset( $dependentClassInfo['dependent_class_identifier'] ) )
        {
            $maxParents = $dependentClassInfo['max_parents'];
            $dependentClassIdentifiers = $dependentClassInfo['dependent_class_identifier'];
            $smartClearType = $dependentClassInfo['clear_cache_type'];

            // getting 'path_string's for all locations.
            $nodePathList = eZContentCacheManager::fetchNodePathString( $assignedNodes );

            foreach ( $nodePathList as $nodePath )
            {
                $step = 0;

                // getting class identifier and node ID for each node in the $nodePath, up to $maxParents
                $nodeInfoList = eZContentObjectTreeNode::fetchClassIdentifierListByPathString( $nodePath, false, $maxParents );

                // for each node in $nodeInfoList determine if this node belongs to $dependentClassIdentifiers. If
                // so then clear cache for this node.
                foreach ( $nodeInfoList as $item )
                {
                    if ( in_array( $item['class_identifier'], $dependentClassIdentifiers ) )
                    {
                        $object = eZContentObject::fetchByNodeID( $item['node_id'] );
                        if ( !$object instanceof eZContentObject )
                        {
                            continue;
                        }
                        $objectID = $object->attribute( 'id' );

                        if ( isset( $handledObjectList[$objectID] ) )
                        {
                            // remove cache types that were already handled
                            $smartClearType &= ~$handledObjectList[$objectID];

                            // if there are no cache types remaining, then skip
                            if ( $smartClearType == self::CLEAR_NO_CACHE )
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

        self::appendAdditionalNodeIDs( $contentObject, $nodeList );

        //self::writeDebugBits( $handledObjectList, self::CLEAR_SIBLINGS_CACHE );
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
    static function nodeList( $objectID, $versionNum )
    {
        $nodeList = array();

        $object = eZContentObject::fetch( $objectID );
        if ( !$object )
        {
            return false;
        }

        eZContentCacheManager::nodeListForObject( $object, $versionNum, self::CLEAR_DEFAULT, $nodeList, $handledObjectList );

        return $nodeList;
    }

    /**
     * Clears view caches of nodes, parent nodes and relating nodes
     * of content object with $objectID.
     * It will use 'viewcache.ini' to determine additional nodes.
     *
     * @param int    $objectID           The object ID
     * @param int    $versionNum         The version of the object to use, or true for current version
     * @param array  $additionalNodeList An array with node IDs to add to clear list, or false for no additional nodes.
    */
    static function clearObjectViewCache( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        eZDebug::accumulatorStart( 'node_cleanup_list', '', 'Node cleanup list' );

        $nodeList = eZContentCacheManager::nodeList( $objectID, $versionNum );

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

        $nodeList = array_unique( $nodeList );

        eZDebug::accumulatorStop( 'node_cleanup_list' );

        return self::clearNodeViewCacheArray( $nodeList, array( $objectID ) );
    }

    /**
     * Clears view caches of nodes, parent nodes and relating nodes
     * of content objects with ids contained in $objectIDList.
     * It will use 'viewcache.ini' to determine additional nodes.
     *
     * @see clearObjectViewCache
     *
     * @param array $objectIDList List of object ID
     */
    public static function clearObjectViewCacheArray( array $objectIDList )
    {
        eZDebug::accumulatorStart( 'node_cleanup_list', '', 'Node cleanup list' );

        $nodeList = array();

        foreach ( $objectIDList as $objectID )
        {
            $tempNodeList = self::nodeList( $objectID, true );

            if ( $tempNodeList !== false )
            {
                $nodeList = array_merge( $nodeList, $tempNodeList );
            }
        }

        $nodeList = array_unique( $nodeList );

        eZDebug::accumulatorStop( 'node_cleanup_list' );

        return self::clearNodeViewCacheArray( $nodeList, $objectIDList );
    }

    /**
     * Clears view caches for an array of nodes.
     *
     * @param  array   $nodeList List of node IDs to clear
     * @param  array|null   $contentObjectList List of content object IDs to clear
     * @return boolean returns true on success
     */
    public static function clearNodeViewCacheArray( array $nodeList, array $contentObjectList = null )
    {
        if ( count( $nodeList ) == 0 )
        {
            return false;
        }

        eZDebugSetting::writeDebug( 'kernel-content-edit', count( $nodeList ), "count in nodeList" );

        if ( eZINI::instance()->variable( 'ContentSettings', 'StaticCache' ) === 'enabled' )
        {
            $staticCacheHandler = eZExtension::getHandlerClass(
                new ezpExtensionOptions(
                    array(
                        'iniFile' => 'site.ini',
                        'iniSection' => 'ContentSettings',
                        'iniVariable' => 'StaticCacheHandler',
                    )
                )
            );

            $staticCacheHandler->generateAlwaysUpdatedCache();
            $staticCacheHandler->generateNodeListCache( $nodeList );
        }

        eZDebug::accumulatorStart( 'node_cleanup', '', 'Node cleanup' );

        eZContentObject::expireComplexViewModeCache();
        $cleanupValue = eZContentCache::calculateCleanupValue( count( $nodeList ) );

        if ( eZContentCache::inCleanupThresholdRange( $cleanupValue ) )
        {
            ezpEvent::getInstance()->notify( 'content/cache', array( $nodeList, $contentObjectList ) );
            eZContentCache::cleanup( $nodeList );
        }
        else
        {
            eZDebug::writeWarning( "Expiring all view cache since list of nodes({$cleanupValue}) exceeds site.ini\[ContentSettings]\CacheThreshold", __METHOD__ );
            ezpEvent::getInstance()->notify( 'content/cache/all' );
            eZContentObject::expireAllViewCache();
        }

        eZDebug::accumulatorStop( 'node_cleanup' );
        return true;
    }

    /**
     * Clears view cache for specified object(s).
     * Checks 'ViewCaching' ini setting to determine whether cache is enabled or not.
     *
     * @param array $objectID (list of) object ID
     * @param bool|int $versionNum
     * @param bool|array $additionalNodeList
     */
    public static function clearObjectViewCacheIfNeeded( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        if ( eZINI::instance()->variable( 'ContentSettings', 'ViewCaching' ) !== 'enabled' )
            return;

        if ( is_array( $objectID ) )
        {
            if ( $versionNum !== true || $additionalNodeList !== false )
            {
                trigger_error( "This method does not support second and third parameters when operating on many objects!" );
                return false;
            }

            self::clearObjectViewCacheArray( $objectID );
        }
        else
        {
            self::clearObjectViewCache( $objectID, $versionNum, $additionalNodeList );
        }
    }

    /**
     * Clears template-block cache and template-block with subtree_expiry parameter caches for specified object(s).
     * Checks 'TemplateCache' ini setting to determine whether cache is enabled or not.
     * If $objectID is \c false all template block caches will be cleared.
     *
     * @param int|array $objectID (list of) object ID.
     */
    public static function clearTemplateBlockCacheIfNeeded( $objectID )
    {
        $ini = eZINI::instance();
        if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) === 'enabled' )
            self::clearTemplateBlockCache( $objectID, true );
    }

    /**
     * Clears template-block cache and template-block with subtree_expiry parameter caches for specified object
     * without checking 'TemplateCache' ini setting.
     *
     * @param int|array|bool $objectID (list of) object ID, if false only ordinary template block caches will be cleared
     *                                 Support for array value available {@since 5.0}.
     * @param bool $checkViewCacheClassSettings Check whether ViewCache class settings should be verified
     */
    public static function clearTemplateBlockCache( $objectID, $checkViewCacheClassSettings = false )
    {
        // ordinary template block cache
        eZContentObject::expireTemplateBlockCache();

        if ( empty( $objectID ) )
            return;

        // subtree template block cache
        $nodeList = false;

        if ( is_array( $objectID ) )
        {
            $objects = eZContentObject::fetchIDArray( $objectID );
        }
        else
        {
            $objects = array( $objectID => eZContentObject::fetch( $objectID ) );
        }

        $ini = eZINI::instance( 'viewcache.ini' );
        foreach ( $objects as $object )
        {
            if ( $object instanceof eZContentObject )
            {
                $getAssignedNodes = true;
                if ( $checkViewCacheClassSettings )
                {
                    $objectClassIdentifier = $object->attribute('class_identifier');
                    if ( $ini->hasVariable( $objectClassIdentifier, 'ClearCacheBlocks' )
                      && $ini->variable( $objectClassIdentifier, 'ClearCacheBlocks' ) === 'disabled' )
                    {
                        $getAssignedNodes = false;
                    }
                }

                if ( $getAssignedNodes )
                {
                    $nodeList = array_merge( ( $nodeList !== false ? $nodeList : array() ), $object->assignedNodes() );
                }
            }
        }

        eZSubtreeCache::cleanup( $nodeList );
    }

    /*!
     \static
     Generates the related viewcaches (PreGeneration) for the content object.
     It will only do this if [ContentSettings]/PreViewCache in site.ini is enabled.

     \param $objectID The ID of the content object to generate caches for.
    */
    static function generateObjectViewCache( $objectID )
    {
        // Generate the view cache
        $ini = eZINI::instance();
        $object = eZContentObject::fetch( $objectID );
        $user = eZUser::currentUser();

        eZDebug::accumulatorStart( 'generate_cache', '', 'Generating view cache' );
        if ( $ini->variable( 'ContentSettings', 'PreViewCache' ) == 'enabled' )
        {
            $preCacheSiteaccessArray = $ini->variable( 'ContentSettings', 'PreCacheSiteaccessArray' );

            $currentSiteAccess = $GLOBALS['eZCurrentAccess'];

            // This is the default view parameters for content/view
            $viewParameters = array( 'offset' => false,
                                     'year' => false,
                                     'month' => false,
                                     'day' => false,
                                     'namefilter' => false );
            if ( is_array( $preCacheSiteaccessArray ) && count( $preCacheSiteaccessArray ) > 0 )
            {
                foreach ( $preCacheSiteaccessArray as $changeToSiteAccess )
                {
                    $newSiteAccess = $currentSiteAccess;
                    $newSiteAccess['name'] = $changeToSiteAccess;
                    unset( $newSiteAccess['uri_part'] );//eZSiteAccess::load() will take care of setting correct one
                    eZSiteAccess::load( $newSiteAccess );

                    $tpl = eZTemplate::factory();

                    // Get the sitedesign and cached view preferences for this siteaccess
                    $siteini = eZINI::instance( 'site.ini');
                    $cachedViewPreferences = $siteini->variable( 'ContentSettings', 'CachedViewPreferences' );

                    $language = false; // Needs to be specified if you want to generate the cache for a specific language
                    $viewMode = 'full';

                    $assignedNodes = $object->assignedNodes();
                    foreach ( $assignedNodes as $node )
                    {
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
                                $previewCacheUser = $user;
                            }
                            else
                            {
                                $previewCacheUser = eZUser::fetch( $previewCacheUserID  );
                            }
                            if ( !$previewCacheUser )
                                continue;

                            // Before we generate the view cache we must change the currently logged in user to $previewCacheUser
                            // If not the templates might read in wrong personalized data (preferences etc.)
                            eZUser::setCurrentlyLoggedInUser( $previewCacheUser, $previewCacheUser->attribute( 'contentobject_id' ), eZUser::NO_SESSION_REGENERATE );

                            // Cache the current node
                            $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $previewCacheUser, $node->attribute( 'node_id' ), false, false, $language, $viewMode, $viewParameters, $cachedViewPreferences );
                            $tmpRes = eZNodeviewfunctions::generateNodeView( $tpl, $node, $node->attribute( 'object' ), $language, $viewMode, false, $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], true, $viewParameters );

                            // Cache the parent node
                            $parentNode = $node->attribute( 'parent' );
                            $objectID = $parentNode->attribute( 'contentobject_id' );
                            // if parent objectID is null or is 0 we should not create cache.
                            if ( $objectID )
                            {
                                $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $previewCacheUser, $parentNode->attribute( 'node_id' ), 0, false, $language, $viewMode, $viewParameters, $cachedViewPreferences );
                                $tmpRes = eZNodeviewfunctions::generateNodeView( $tpl, $parentNode, $parentNode->attribute( 'object' ), $language, $viewMode, 0, $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], true, $viewParameters );
                            }
                        }
                    }
                }
                // Restore the old user as the current one
                eZUser::setCurrentlyLoggedInUser( $user, $user->attribute( 'contentobject_id' ), eZUser::NO_SESSION_REGENERATE );

                // restore siteaccess
                eZSiteAccess::load( $currentSiteAccess );
            }
        }

        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            $nodes = array();
            $ini = eZINI::instance();
            $useURLAlias =& $GLOBALS['eZContentObjectTreeNodeUseURLAlias'];
            $pathPrefix = $ini->variable( 'SiteAccessSettings', 'PathPrefix' );

            // get staticCacheHandler instance
            $optionArray = array( 'iniFile'      => 'site.ini',
                                  'iniSection'   => 'ContentSettings',
                                  'iniVariable'  => 'StaticCacheHandler' );

            $options = new ezpExtensionOptions( $optionArray );
            $staticCacheHandler = eZExtension::getHandlerClass( $options );

            if ( !isset( $useURLAlias ) )
            {
                $useURLAlias = $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled';
            }

            eZContentCacheManager::nodeListForObject( $object, true, self::CLEAR_DEFAULT, $nodes, $handledObjectList );

            // If no nodes returns it means that ClearCacheMethod = self::CLEAR_NO_CACHE
            if ( count( $nodes ) )
            {
                foreach ( $nodes as $nodeID )
                {
                    if ( $useURLAlias )
                    {
                        $oNode = eZContentObjectTreeNode::fetch( $nodeID, false, true );
                        if ( !isset( $oNode ) )
                            continue;

                        $urlAlias = $oNode->urlAlias();
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
                    $staticCacheHandler->cacheURL( '/' . $urlAlias, $nodeID );
                }
                $staticCacheHandler->generateAlwaysUpdatedCache();
            }
        }
        eZDebug::accumulatorStop( 'generate_cache' );
    }

    /*!
     \static
     Clears content cache if needed by \a $sectionID
    */
    static function clearContentCacheIfNeededBySectionID( $sectionID )
    {
        // fetch all objects of this section
        $objectList = eZContentObject::fetchList( false, array( 'section_id' => "$sectionID" ) );
        // Clear cache
        $objectIDList = array();
        foreach ( $objectList as $object )
        {
            $objectIDList[] = $object['id'];
        }
        self::clearContentCacheIfNeeded( $objectIDList );
        return true;
    }

    /**
     * Clears content cache for specified (list of) object: view cache, template-block cache, template-block with subtree_expiry parameter cache.
     * Checks appropriate ini settings to determine whether caches are enabled or not.
     *
     * @param int|array $objectID (list of) object ID
     * @param bool|int $versionNum
     * @param bool|array $additionalNodeList
     */
    public static function clearContentCacheIfNeeded( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        eZDebug::accumulatorStart( 'check_cache', '', 'Check cache' );

        self::clearObjectViewCacheIfNeeded( $objectID, $versionNum, $additionalNodeList );
        self::clearTemplateBlockCacheIfNeeded( $objectID );

        // Clear cached path strings of content SSL zones.
        eZSSLZone::clearCacheIfNeeded();

        eZDebug::accumulatorStop( 'check_cache' );
        return true;
    }

    /**
     * Clears content cache for specified object: view cache, template-block cache, template-block with subtree_expiry parameter cache
     * without checking of ini settings.
     *
     * @param int|array $objectID (list of) object ID
     * @param bool|int $versionNum
     * @param bool|array $additionalNodeList
     */
    static function clearContentCache( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        eZDebug::accumulatorStart( 'check_cache', '', 'Check cache' );

        if ( is_array( $objectID ) )
        {
            if ( $versionNum !== true || $additionalNodeList !== false )
            {
                trigger_error( "This method does not support second and third parameters when operating on many objects!" );
                return false;
            }

            self::clearObjectViewCacheArray( $objectID );
        }
        else
        {
            self::clearObjectViewCache( $objectID, $versionNum, $additionalNodeList );
        }

        self::clearTemplateBlockCache( $objectID );

        // Clear cached path strings of content SSL zones.
        eZSSLZone::clearCache();

        eZDebug::accumulatorStop( 'check_cache' );
        return true;
    }

    /*!
     \static
     Clears all content cache: view cache, template-block cache, template-block with subtree_expiry parameter cache.
    */
    static function clearAllContentCache( $ignoreINISettings = false )
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

            ezpEvent::getInstance()->notify( 'content/cache/all' );

            // subtree template block caches
            if ( $templateCacheEnabled )
            {
                eZSubtreeCache::cleanupAll();
            }
        }
    }
}

?>

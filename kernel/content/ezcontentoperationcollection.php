<?php
//
// Definition of eZContentOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
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

/*! \file ezcontentoperationcollection.php
*/

/*!
  \class eZContentOperationCollection ezcontentoperationcollection.php
  \brief The class eZContentOperationCollection does

*/

// ViewCache. Smart Clear Types
define( 'EZ_VCSC_CLEAR_NODE_CACHE'      , 1 );
define( 'EZ_VCSC_CLEAR_PARENT_CACHE'    , 2 );
define( 'EZ_VCSC_CLEAR_RELATING_CACHE'  , 4 );
define( 'EZ_VCSC_CLEAR_ALL_CACHE'       , 7 );


class eZContentOperationCollection
{
    /*!
     Constructor
    */
    function eZContentOperationCollection()
    {
    }

    function readNode( $nodeID )
    {

    }

    function readObject( $nodeID, $userID, $languageCode )
    {
        if ( $languageCode != '' )
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID, $languageCode );
        }
        else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }

        if ( $node === null )
//            return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
            return false;


        $object = $node->attribute( 'object' );

        if ( $object === null )
//            return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
        {
            return false;
        }
/*
        if ( !$object->attribute( 'can_read' ) )
        {
//            return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
            return false;
        }
*/
        if ( $languageCode != '' )
        {
            $object->setCurrentLanguage( $languageCode );
        }
        return array( 'status' => true, 'object' => $object, 'node' => $node );
    }

    function loopNodes( $nodeID )
    {
        return array( 'parameters' => array( array( 'parent_node_id' => 3 ),
                                             array( 'parent_node_id' => 5 ),
                                             array( 'parent_node_id' => 12 ) ) );
    }

    function loopNodeAssignment( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );
        $nodeAssignmentList =& $version->attribute( 'node_assignments' );

        $parameters = array();
        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
            $nodeAssignment =& $nodeAssignmentList[$key];
            if ( $nodeAssignment->attribute( 'parent_node' ) > 0 )
            {
                if ( $nodeAssignment->attribute( 'is_main' ) == 1 )
                {
                    $mainNodeID = $this->publishNode( $nodeAssignment->attribute( 'parent_node' ), $objectID, $versionNum, false );
                }
                else
                {
                    $parameters[] = array( 'parent_node_id' => $nodeAssignment->attribute( 'parent_node' ) );
                }
            }
        }
        for ( $i = 0; $i < count( $parameters ); $i++ )
        {
            $parameters[$i]['main_node_id'] = $mainNodeID;
        }

        return array( 'parameters' => $parameters );
    }

    function setVersionStatus( $objectID, $versionNum, $status )
    {
        $object =& eZContentObject::fetch( $objectID );

        if ( !$versionNum )
        {
            $versionNum = $object->attribute( 'current_version' );
        }
        $version =& $object->version( $versionNum );
        if ( $version === null )
            return;
        switch ( $status )
        {
            case 1:
            {
                $statusName = 'pending';
                $version->setAttribute( 'status', EZ_VERSION_STATUS_PENDING );
            } break;
            case 2:
            {
                $statusName = 'archived';
                $version->setAttribute( 'status', EZ_VERSION_STATUS_ARCHIVED );
            } break;
            case 3:
            {
                $statusName = 'published';
                $version->setAttribute( 'status', EZ_VERSION_STATUS_PUBLISHED );
            } break;
            default:
                $statusName = 'none';
        }
        $version->store();
    }

    function setObjectStatusPublished( $objectID )
    {
        $object =& eZContentObject::fetch( $objectID );
        $object->setAttribute( 'status', EZ_CONTENT_OBJECT_STATUS_PUBLISHED );
        $object->store();
    }

    function attributePublishAction( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $nodes =& $object->assignedNodes();
//         $dataMap =& $object->attribute( 'data_map' );
        $contentObjectAttributes =& $object->contentObjectAttributes( true, $versionNum, null, false );
        foreach ( array_keys( $contentObjectAttributes ) as $contentObjectAttributeKey )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$contentObjectAttributeKey];
            $contentObjectAttribute->onPublish( $object, $nodes );
        }
    }

    /*!
        \a static
        Appends parent nodes ids of \a $object to \a $outNodesList array.
    */
    function viewCacheAppendParentNodes( &$object, $versionNum, &$outNodesList )
    {
        $parentNodes =& $object->parentNodes( $versionNum );
        foreach ( array_keys( $parentNodes ) as $parentNodeKey )
        {
            $parentNode =& $parentNodes[$parentNodeKey];
            $outNodesList[] = $parentNode->attribute( 'node_id' );
        }
    }

    /*!
        \a static
        Appends nodes ids from \a $nodes list to \a $outNodesList
    */
    function viewCacheAppendNodesIDs( &$nodes, &$outNodesList )
    {
        foreach ( array_keys( $nodes ) as $nodeKey )
        {
            $assignedNode =& $nodes[$nodeKey];
            $outNodesList[] = $assignedNode->attribute( 'node_id' );
        }
    }

    /*!
        \a static
        Returns list of 'path_strings' of \a $nodes.
    */
    function &viewCacheGetNodesPathesList( &$nodes )
    {
        $pathList = array();
        foreach ( array_keys( $nodes ) as $nodeKey )
        {
            $node =& $nodes[$nodeKey];
            $pathList[] = $node->attribute( 'path_string' );
        }
        return $pathList;
    }

    /*!
        \a static
        Appends to \a $outNodesList nodes ids of relating objects of
        \a $object
    */
    function viewCacheAppendRelatingThisNodes( &$object, &$outNodesList )
    {
        $relatedObjects =& $object->contentObjectListRelatingThis();
        foreach ( array_keys( $relatedObjects ) as $relatedObjectKey )
        {
            $relatedObject =& $relatedObjects[$relatedObjectKey];
            $assignedNodes =& $relatedObject->assignedNodes( false );
            foreach ( array_keys( $assignedNodes ) as $assignedNodeKey )
            {
                $assignedNode =& $assignedNodes[$assignedNodeKey];
                $outNodesList[] = $assignedNode['node_id'];
            }
        }
    }

    /*
        \a static
        Reads 'viewcache.ini' file and determines relation between
        \a $classID and another class.
    */
    function viewCacheGetDependentClassInfo( $classID )
    {
        $ini =& eZINI::instance( 'viewcache.ini' );
        $dependentClassInfo = false;

        if ( $ini->hasGroup( $classID ) )
        {
            $dependentClassInfo = array();
            $dependentClassInfo['dependent_class_id'] = $ini->variable( $classID, 'DependentClassIdentifier' );

            if ( $ini->hasVariable( $classID, 'MaxOffset' ) )
                $dependentClassInfo['max_offset'] = $ini->variable( $classID, 'MaxOffset' );
            else
                $dependentClassInfo['max_offset'] = 0;

            $dependentClassInfo['clear_cache_type'] = 0;
            if ( $ini->hasVariable( $classID, 'ClearCacheMethod' ) )
            {
                $type = $ini->variable( $classID, 'ClearCacheMethod' );

                if ( $type == 'clear_all_caches' )
                {
                    $dependentClassInfo['clear_cache_type'] = EZ_VCSC_CLEAR_ALL_CACHE;
                }
                else
                {
                    if ( $type == 'clear_object_cache_only' ||
                         $type == 'clear_object_and_parent_nodes_caches' ||
                         $type == 'clear_object_and_relating_objects_caches' )
                    {
                        $dependentClassInfo['clear_cache_type'] |= EZ_VCSC_CLEAR_NODE_CACHE;
                    }

                    if ( $type == 'clear_object_and_parent_nodes_caches' ||
                         $type == 'clear_parent_nodes_caches_only' ||
                         $type == 'clear_parent_nodes_and_relating_caches' )
                    {
                        $dependentClassInfo['clear_cache_type'] |= EZ_VCSC_CLEAR_PARENT_CACHE;
                    }

                    if ( $type == 'clear_object_and_relating_objects_caches' ||
                         $type == 'clear_parent_nodes_and_relating_caches' ||
                         $type == 'clear_relating_caches_only' )
                    {
                        $dependentClassInfo['clear_cache_type'] |= EZ_VCSC_CLEAR_RELATING_CACHE;
                    }
                }
            }
            else
            {
                $dependentClassInfo['clear_cache_type'] = EZ_VCSC_CLEAR_ALL_CACHE;
            }

            $dependentClassInfo['object_only_ids'] = array();
            if ( $ini->hasVariable( $classID, 'ObjectOnlyIDList' ) )
            {
                $dependentClassInfo['object_only_ids'] = $ini->variable( $classID, 'ObjectOnlyIDList' );
            }
        }
        return $dependentClassInfo;
    }

    /*!
        \a static
        Returns in \a $nodesList information about nodes of \a $contentObject for which
        viewcache should cleared. This function is recursive.
        if \a $objectVersionNum current version of object will be used.
        Use \a $clearCacheType to include different kind of nodes( parent, relating, etc ).
        If \a $versionNum is true, then current version will be used.
    */
    function viewCacheGetNodesOfObject( &$contentObject, $objectVersionNum, $clearCacheType, &$nodesList )
    {
        $assignedNodes =& $contentObject->assignedNodes();

        if ( $clearCacheType & EZ_VCSC_CLEAR_NODE_CACHE )
        {
            eZContentOperationCollection::viewCacheAppendNodesIDs( $assignedNodes, $nodesList );
        }

        if ( $clearCacheType & EZ_VCSC_CLEAR_PARENT_CACHE )
        {
            eZContentOperationCollection::viewCacheAppendParentNodes( $contentObject, $objectVersionNum, $nodesList );
        }

        if( $clearCacheType & EZ_VCSC_CLEAR_RELATING_CACHE )
        {
            eZContentOperationCollection::viewCacheAppendRelatingThisNodes( $contentObject, $nodesList );
        }

        // determine if $contentObject has dependent objects for which cache should be cleared too.
        $objectClassID =  $contentObject->attribute( 'class_identifier' );
        $dependentClassInfo =& eZContentOperationCollection::viewCacheGetDependentClassInfo( $objectClassID );

        if ( isset( $dependentClassInfo['dependent_class_id'] ) )
        {
            // getting 'path_strings' for nodes.
            $nodePathList =& eZContentOperationCollection::viewCacheGetNodesPathesList( $assignedNodes );

            foreach( $nodePathList as $nodePath )
            {
                // getting class identifiers for each node in the $nodePath.
                $classIdsInfo =& eZContentObjectTreeNode::getClassIdentifiersListByPath( $nodePath, false );

                $step = 0;
                $maxOffset = $dependentClassInfo['max_offset'];
                $dependentClassID = $dependentClassInfo['dependent_class_id'];
                $smartClearType = $dependentClassInfo['clear_cache_type'];

                if ( $maxOffset > 0 )
                {
                    // need to reverse $classIdsInfo if $maxOffset is used.
                    // if offset is zero then we will loop through all elements in $classIdsInfo. So,
                    // array_reverse don't need.

                    $classIdsInfo = array_reverse( $classIdsInfo );
                }

                // for each node in $classIdsInfo determine if this node belongs to $dependentClassID. If
                // so then clear cache for this node.
                foreach ( $classIdsInfo as $item )
                {
                    if ( $item['class_identifier'] == $dependentClassID )
                    {
                        $node =& eZContentObjectTreeNode::fetch( $item['node_id'] );
                        $object =& $node->attribute( 'object' );

                        if ( count( $dependentClassInfo['object_only_ids'] ) > 0 )
                        {
                            foreach ( $dependentClassInfo['object_only_ids'] as $dependentObjectID )
                            {
                                if ( $dependentObjectID == $object->attribute( 'id' ) )
                                {
                                    eZContentOperationCollection::viewCacheGetNodesOfObject( $object, true, $smartClearType, $nodesList );
                                    break;
                                }
                            }
                        }
                        else
                        {
                            eZContentOperationCollection::viewCacheGetNodesOfObject( $object, true, $smartClearType, $nodesList );
                        }
                    }

                    // if we reached $maxOffset then break
                    if ( ++$step == $maxOffset )
                    {
                        break;
                    }
                }
            }
        }
    }

    /*!
        \a static
        Returns in \a $nodesList information about nodes of object with \a $objectID for which
        viewcache should be cleared.
        If \a $versionNum is true, then current version will be used.
    */
    function &viewCacheGetNodes( $objectID, $versionNum )
    {
        $nodesList = array();

        $object =& eZContentObject::fetch( $objectID );

        eZContentOperationCollection::viewCacheGetNodesOfObject( $object, $versionNum, EZ_VCSC_CLEAR_ALL_CACHE, $nodesList );

        return $nodesList;
    }

    /*!
        \a static
        Clears view cache of nodes, parent nodes and relating nodes of content objects with id \a $objectID.
        To determine additional nodes use 'viewcache.ini'.
        If \a $versionNum is true, then current version will be used.
    */
    function viewCacheDoSmartCacheClear( $objectID, $versionNum, $additionalNodesIDList = false )
    {
        $nodeList =& eZContentOperationCollection::viewCacheGetNodes( $objectID, $versionNum );
        if ( is_array( $additionalNodesIDList ) )
        {
            array_splice( $nodeList, count( $nodeList ), 0, $additionalNodesIDList );
        }

        eZDebugSetting::writeDebug( 'kernel-content-edit', count( $nodeList ), "count in nodeList " );

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

    /*!
        \a static
        If \a $versionNum is true, then current version will be used.
    */
    function clearObjectViewCache( $objectID, $versionNum, $additionalNodesIDList = false )
    {
        eZDebug::accumulatorStart( 'check_cache', '', 'Check cache' );

        $ini =& eZINI::instance();
        $viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );

        if ( $viewCacheEnabled )
        {
            $viewCacheINI =& eZINI::instance( 'viewcache.ini' );
            if ( $viewCacheINI->variable( 'ViewCacheSettings', 'SmartCacheClear' ) == 'enabled' )
            {
                eZContentOperationCollection::viewCacheDoSmartCacheClear( $objectID, $versionNum, $additionalNodesIDList );
            }
            else
            {
                eZContentObject::expireAllCache();
            }
        }
        eZDebug::accumulatorStop( 'check_cache' );
    }


    /*!
    */
    function publishNode( $parentNodeID, $objectID, $versionNum, $mainNodeID )
    {
        $object         =& eZContentObject::fetch( $objectID );
        $version        =& $object->version( $versionNum );
        $nodeAssignment =& eZNodeAssignment::fetch( $objectID, $versionNum, $parentNodeID );

        $object->setAttribute( 'current_version', $versionNum );
        if ( $object->attribute( 'published' ) == 0 )
        {
            $object->setAttribute( 'published', mktime() );
        }

        $object->setAttribute( 'modified', mktime() );
        $object->store();

        $class      =& eZContentClass::fetch( $object->attribute( 'contentclass_id' ) );
        $objectName =  $class->contentObjectName( $object );

        $object->setName( $objectName, $versionNum );
//        $object->store();  // removed to reduce sql calls. restore if publish bugs occur, by kk

        $existingTranslations =& $version->translations( false );
        foreach( array_keys( $existingTranslations ) as $key )
        {
            $translation = $existingTranslations[$key];
            $translatedName = $class->contentObjectName( $object, $versionNum, $translation );
            $object->setName( $translatedName, $versionNum, $translation );
        }

        $fromNodeID       = $nodeAssignment->attribute( 'from_node_id' );
        $originalObjectID = $nodeAssignment->attribute( 'contentobject_id' );

        $nodeID           =  $nodeAssignment->attribute( 'parent_node' );
        $parentNode       =& eZContentObjectTreeNode::fetch( $nodeID );
        $parentNodeID     =  $parentNode->attribute( 'node_id' );
        $existingNode     =  null;

        if ( strlen( $nodeAssignment->attribute( 'parent_remote_id' ) ) > 0 )
        {
            $existingNode = eZContentObjectTreeNode::fetchByRemoteID( $nodeAssignment->attribute( 'parent_remote_id' ) );
        }
        if ( !$existingNode );
        {
            $existingNode =& eZContentObjectTreeNode::findNode( $nodeID , $object->attribute( 'id' ), true );
        }
        $updateSectionID = false;
        if ( $existingNode  == null )
        {
            if ( $fromNodeID == 0 || $fromNodeID == -1)
            {
                $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );

                include_once( 'kernel/classes/ezcontentbrowserecent.php' );
                $user =& eZUser::currentUser();
                eZContentBrowseRecent::createNew( $user->id(), $parentNode->attribute( 'node_id' ), $parentNode->attribute( 'name' ) );

                $existingNode =& $parentNode->addChild( $object->attribute( 'id' ), 0, true );

                if ( $fromNodeID == -1 )
                {
                    $updateSectionID = true;
                }
            }
            else
            {
                // clear cache for old placement.
                $additionalNodeIDList = array( $fromNodeID );
                eZContentOperationCollection::clearObjectViewCache( $originalObjectID, $versionNum, $additionalNodeIDList );

                $originalNode =& eZContentObjectTreeNode::fetchNode( $originalObjectID, $fromNodeID );
                if ( $originalNode->attribute( 'main_node_id' ) == $originalNode->attribute( 'node_id' ) )
                {
                    $updateSectionID = true;
                }
                $originalNode->move( $parentNodeID );
                $existingNode =& eZContentObjectTreeNode::fetchNode( $originalObjectID, $parentNodeID );
            }
        }

        if ( strlen( $nodeAssignment->attribute( 'parent_remote_id' ) ) > 0 )
        {
            $existingNode->setAttribute( 'remote_id', $nodeAssignment->attribute( 'parent_remote_id' ) );
        }
        $existingNode->setAttribute( 'sort_field', $nodeAssignment->attribute( 'sort_field' ) );
        $existingNode->setAttribute( 'sort_order', $nodeAssignment->attribute( 'sort_order' ) );
        $existingNode->setAttribute( 'contentobject_version', $version->attribute( 'version' ) );
        $existingNode->setAttribute( 'contentobject_is_published', 1 );
        $existingNode->setName( $objectName );

        eZDebug::createAccumulatorGroup( 'nice_urls_total', 'Nice urls' );

        $existingNode->updateSubTreePath();

        if ( $mainNodeID > 0 )
        {
            $existingNodeID = $existingNode->attribute( 'node_id' );
            if ( $existingNodeID != $mainNodeID )
            {
                include_once( 'kernel/classes/ezcontentbrowserecent.php' );
                eZContentBrowseRecent::updateNodeID( $existingNodeID, $mainNodeID );
            }
            $existingNode->setAttribute( 'main_node_id', $mainNodeID );
        }
        else
        {
            $existingNode->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
        }

        $version->setAttribute( 'status', EZ_VERSION_STATUS_PUBLISHED );
        $version->store();

        $object->store();
        $existingNode->store();

        if ( $updateSectionID )
        {
            eZDebug::writeDebug( "will  update section ID " );
            eZContentOperationCollection::updateSectionID( $objectID, $versionNum );
        }

        // Clear cache after publish
        $ini =& eZINI::instance();
        $templateBlockCacheEnabled = ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled' );

        if ( $templateBlockCacheEnabled )
        {
            include_once( 'kernel/classes/ezcontentobject.php' );
            eZContentObject::expireTemplateBlockCache();
        }

        if ( $mainNodeID == false )
        {
            return $existingNode->attribute( "node_id" );
        }
    }

    function updateSectionID( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );

        if ( $versionNum == 1 or
             $object->attribute( 'current_version' ) == $versionNum )
        {
            list( $newMainAssignment ) = eZNodeAssignment::fetchForObject( $objectID, $versionNum, 1 );
            $newParentObject =& $newMainAssignment->getParentObject();
            $object->setAttribute( 'section_id', $newParentObject->attribute( 'section_id' ) );
            $object->store();
            return;
        }

        list( $newMainAssignment ) = eZNodeAssignment::fetchForObject( $objectID, $versionNum, 1 );

        $currentVersion =& $object->attribute( 'current' );
        list( $oldMainAssignment ) = eZNodeAssignment::fetchForObject( $objectID, $object->attribute( 'current_version' ), 1 );

        if ( $newMainAssignment && $oldMainAssignment
             &&  $newMainAssignment->attribute( 'parent_node' ) != $oldMainAssignment->attribute( 'parent_node' ) )
        {
            $oldMainParentNode =& $oldMainAssignment->attribute( 'parent_node_obj' );
            if ( $oldMainParentNode )
            {
                $oldParentObject =& $oldMainParentNode->attribute( 'object' );
                $oldParentObjectSectionID = $oldParentObject->attribute( 'section_id' );
                if ( $oldParentObjectSectionID == $object->attribute( 'section_id' ) )
                {
                    $newParentNode =& $newMainAssignment->attribute( 'parent_node_obj' );
                    if ( !$newParentNode )
                        return;
                    $newParentObject =& $newParentNode->attribute( 'object' );
                    if ( !$newParentObject )
                        return;

                    $newSectionID = $newParentObject->attribute( 'section_id' );

                    if ( $newSectionID != $object->attribute( 'section_id' ) )
                    {
                        $oldSectionID = $object->attribute( 'section_id' );
                        $object->setAttribute( 'section_id', $newSectionID );
                        $object->store();
                        $mainNodeID = $object->attribute( 'main_node_id' );
                        if ( $mainNodeID > 0 )
                            eZContentObjectTreeNode::assignSectionToSubTree( $mainNodeID,
                                                                             $newSectionID,
                                                                             $oldSectionID );

                    }
                }
            }
        }
    }

    function removeOldNodes( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );

        $assignedExistingNodes =& $object->attribute( 'assigned_nodes' );

        $curentVersionNodeAssignments = $version->attribute( 'node_assignments' );
        $versionParentIDList = array();
        foreach ( array_keys( $curentVersionNodeAssignments ) as $key )
        {
            $nodeAssignment =& $curentVersionNodeAssignments[$key];
            $versionParentIDList[] = $nodeAssignment->attribute( 'parent_node' );
        }
        foreach ( array_keys( $assignedExistingNodes )  as $key )
        {
            $node =& $assignedExistingNodes[$key];
            if ( $node->attribute( 'contentobject_version' ) < $version->attribute( 'version' ) &&
                 !in_array( $node->attribute( 'parent_node_id' ), $versionParentIDList ) )
            {
                $node->remove();
            }
        }
    }

    function registerSearchObject( $objectID, $versionNum )
    {
        eZDebug::createAccumulatorGroup( 'search_total', 'Search Total' );

        include_once( "lib/ezutils/classes/ezini.php" );

        $ini =& eZINI::instance( 'site.ini' );
        $delayedIndexing = ( $ini->variable( 'SearchSettings', 'DelayedIndexing' ) == 'enabled' );

        if ( $delayedIndexing )
        {
            include_once( "lib/ezdb/classes/ezdb.php" );

            $db =& eZDB::instance();
            $db->query( 'INSERT INTO ezpending_actions( action, param ) VALUES ( "index_object", '. (int)$objectID. ' )' );
        }
        else
        {
            include_once( "kernel/classes/ezsearch.php" );
            $object =& eZContentObject::fetch( $objectID );
            // Register the object in the search engine.
            eZDebug::accumulatorStart( 'remove_object', 'search_total', 'remove object' );
            eZSearch::removeObject( $object );
            eZDebug::accumulatorStop( 'remove_object' );
            eZDebug::accumulatorStart( 'add_object', 'search_total', 'add object' );
            eZSearch::addObject( $object );
            eZDebug::accumulatorStop( 'add_object' );
        }
    }


    function createNotificationEvent( $objectID, $versionNum )
    {
        include_once( 'kernel/classes/notification/eznotificationevent.php' );
        $event =& eZNotificationEvent::create( 'ezpublish', array( 'object' => $objectID,
                                                                   'version' => $versionNum ) );
        $event->store();
    }
}

?>

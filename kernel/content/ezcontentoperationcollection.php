<?php
//
// Definition of eZContentOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZContentOperationCollection ezcontentoperationcollection.php
  \brief The class eZContentOperationCollection does

*/

class eZContentOperationCollection
{
    /*!
     Constructor
    */
    function eZContentOperationCollection()
    {
    }

    static public function readNode( $nodeID )
    {

    }

    static public function readObject( $nodeID, $userID, $languageCode )
    {
        if ( $languageCode != '' )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID, $languageCode );
        }
        else
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
        }

        if ( $node === null )
//            return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
            return false;


        $object = $node->attribute( 'object' );

        if ( $object === null )
//            return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
        {
            return false;
        }
/*
        if ( !$object->attribute( 'can_read' ) )
        {
//            return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
            return false;
        }
*/

        return array( 'status' => true, 'object' => $object, 'node' => $node );
    }

    static public function loopNodes( $nodeID )
    {
        return array( 'parameters' => array( array( 'parent_node_id' => 3 ),
                                             array( 'parent_node_id' => 5 ),
                                             array( 'parent_node_id' => 12 ) ) );
    }

    static public function loopNodeAssignment( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );

        $version = $object->version( $versionNum );
        $nodeAssignmentList = $version->attribute( 'node_assignments' );

        $parameters = array();
        foreach ( $nodeAssignmentList as $nodeAssignment )
        {
            if ( $nodeAssignment->attribute( 'parent_node' ) > 0 )
            {
                if ( $nodeAssignment->attribute( 'is_main' ) == 1 )
                {
                    $mainNodeID = self::publishNode( $nodeAssignment->attribute( 'parent_node' ), $objectID, $versionNum, false );
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

    function publishObjectExtensionHandler( $contentObjectID, $contentObjectVersion )
    {
        eZContentObjectEditHandler::executePublish( $contentObjectID, $contentObjectVersion );
    }

    static public function setVersionStatus( $objectID, $versionNum, $status )
    {
        $object = eZContentObject::fetch( $objectID );

        if ( !$versionNum )
        {
            $versionNum = $object->attribute( 'current_version' );
        }
        $version = $object->version( $versionNum );
        if ( !$version )
            return;

        $version->setAttribute( 'status', $status );
        $version->store();
    }

    static public function setObjectStatusPublished( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );
        $version = $object->version( $versionNum );

        $db = eZDB::instance();
        $db->begin();

        $object->publishContentObjectRelations( $versionNum );
        $object->setAttribute( 'status', eZContentObject::STATUS_PUBLISHED );
        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_PUBLISHED );
        $object->setAttribute( 'current_version', $versionNum );

        $objectIsAlwaysAvailable = $object->isAlwaysAvailable();
        $object->setAttribute( 'language_mask', eZContentLanguage::maskByLocale( $version->translationList( false, false ), $objectIsAlwaysAvailable ) );

        if ( $object->attribute( 'published' ) == 0 )
        {
            $object->setAttribute( 'published', time() );
        }
        $object->setAttribute( 'modified', time() );
        $classID = $object->attribute( 'contentclass_id' );

        $class = eZContentClass::fetch( $classID );
        $objectName = $class->contentObjectName( $object );
        $object->setName( $objectName, $versionNum );
        $existingTranslations = $version->translations( false );
        foreach( $existingTranslations as $translation )
        {
            $translatedName = $class->contentObjectName( $object, $versionNum, $translation );
            $object->setName( $translatedName, $versionNum, $translation );
        }

        if ( $objectIsAlwaysAvailable )
        {
            $initialLanguageID = $object->attribute( 'initial_language_id' );
            $object->setAlwaysAvailableLanguageID( $initialLanguageID );
        }

        $version->store();
        $object->store();

        eZContentObjectTreeNode::setVersionByObjectID( $objectID, $versionNum );

        $nodes = $object->assignedNodes();
        foreach ( $nodes as $node )
        {
            $node->setName( $object->attribute( 'name' ) );
            $node->updateSubTreePath();
        }

        $db->commit();

        /* Check if current class is the user class, and if so, clean up the user-policy cache */
        if ( in_array( $classID, eZUser::contentClassIDs() ) )
        {
            eZUser::cleanupCache();
        }
    }

    static public function attributePublishAction( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );
        $nodes = $object->assignedNodes();
        $version = $object->version( $versionNum );
        $contentObjectAttributes = $object->contentObjectAttributes( true, $versionNum, $version->initialLanguageCode(), false );
        foreach ( $contentObjectAttributes as $contentObjectAttribute )
        {
            $contentObjectAttribute->onPublish( $object, $nodes );
        }
    }

    /*!
     \static
     Generates the related viewcaches (PreGeneration) for the content object.
     It will only do this if [ContentSettings]/PreViewCache in site.ini is enabled.

     \param $objectID The ID of the content object to generate caches for.
    */
    static public function generateObjectViewCache( $objectID )
    {
        eZContentCacheManager::generateObjectViewCache( $objectID );
    }

    /*!
     \static
     Clears the related viewcaches for the content object using the smart viewcache system.

     \param $objectID The ID of the content object to clear caches for
     \param $versionNum The version of the object to use or \c true for current version
     \param $additionalNodeList An array with node IDs to add to clear list,
                                or \c false for no additional nodes.
    */
    static public function clearObjectViewCache( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID, $versionNum, $additionalNodeList );
    }


    static public function publishNode( $parentNodeID, $objectID, $versionNum, $mainNodeID )
    {
        $object         = eZContentObject::fetch( $objectID );
        $nodeAssignment = eZNodeAssignment::fetch( $objectID, $versionNum, $parentNodeID );
        $version = $object->version( $versionNum );

        $db = eZDB::instance();
        $db->begin();

        $fromNodeID       = $nodeAssignment->attribute( 'from_node_id' );
        $originalObjectID = $nodeAssignment->attribute( 'contentobject_id' );

        $nodeID           =  $nodeAssignment->attribute( 'parent_node' );
        $opCode           =  $nodeAssignment->attribute( 'op_code' );
        $parentNode       = eZContentObjectTreeNode::fetch( $nodeID );
        $parentNodeID     =  $parentNode->attribute( 'node_id' );
        $existingNode     =  null;

        if ( strlen( $nodeAssignment->attribute( 'parent_remote_id' ) ) > 0 )
        {
            $existingNode = eZContentObjectTreeNode::fetchByRemoteID( $nodeAssignment->attribute( 'parent_remote_id' ) );
        }
        if ( !$existingNode );
        {
            $existingNode = eZContentObjectTreeNode::findNode( $nodeID , $object->attribute( 'id' ), true );
        }
        $updateSectionID = false;
        // now we check the op_code to see what to do
        if ( ( $opCode & 1 ) == eZNodeAssignment::OP_CODE_NOP )
        {
            // There is nothing to do so just return
            $db->commit();
            if ( $mainNodeID == false )
            {
                return $object->attribute( 'main_node_id' );
            }

            return;
        }

        $updateFields = false;

        if ( $opCode == eZNodeAssignment::OP_CODE_MOVE ||
             $opCode == eZNodeAssignment::OP_CODE_CREATE )
        {
//            if ( $fromNodeID == 0 || $fromNodeID == -1)
            if ( $opCode == eZNodeAssignment::OP_CODE_CREATE ||
                 $opCode == eZNodeAssignment::OP_CODE_SET )
            {
                // If the node already exists it means we have a conflict (for 'CREATE').
                // We resolve this by leaving node-assignment data be.
                if ( $existingNode == null )
                {
                    $parentNode = eZContentObjectTreeNode::fetch( $nodeID );

                    $user = eZUser::currentUser();
                    eZContentBrowseRecent::createNew( $user->id(), $parentNode->attribute( 'node_id' ), $parentNode->attribute( 'name' ) );
                    $updateFields = true;

                    $existingNode = $parentNode->addChild( $object->attribute( 'id' ), true );

                    if ( $fromNodeID == -1 )
                    {
                        $updateSectionID = true;
                    }
                }
                elseif ( $opCode == eZNodeAssignment::OP_CODE_SET )
                {
                    $updateFields = true;
                }
            }
            elseif ( $opCode == eZNodeAssignment::OP_CODE_MOVE )
            {
                if ( $fromNodeID == 0 || $fromNodeID == -1 )
                {
                    eZDebug::writeError( "NodeAssignment '", $nodeAssignment->attribute( 'id' ), "' is marked with op_code='$opCode' but has no data in from_node_id. Cannot use it for moving node." );
                }
                else
                {
                    // clear cache for old placement.
                    $additionalNodeIDList = array( $fromNodeID );

                    eZContentCacheManager::clearContentCacheIfNeeded( $objectID, $versionNum, $additionalNodeIDList );

                    $originalNode = eZContentObjectTreeNode::fetchNode( $originalObjectID, $fromNodeID );
                    if ( $originalNode->attribute( 'main_node_id' ) == $originalNode->attribute( 'node_id' ) )
                    {
                        $updateSectionID = true;
                    }
                    $originalNode->move( $parentNodeID );
                    $existingNode = eZContentObjectTreeNode::fetchNode( $originalObjectID, $parentNodeID );
                    $updateFields = true;
                }
            }
        }
        elseif ( $opCode == eZNodeAssignment::OP_CODE_REMOVE )
        {
            $db->commit();
            return;
        }

        if ( $updateFields )
        {
            if ( strlen( $nodeAssignment->attribute( 'parent_remote_id' ) ) > 0 )
            {
                $existingNode->setAttribute( 'remote_id', $nodeAssignment->attribute( 'parent_remote_id' ) );
            }
            $existingNode->setAttribute( 'sort_field', $nodeAssignment->attribute( 'sort_field' ) );
            $existingNode->setAttribute( 'sort_order', $nodeAssignment->attribute( 'sort_order' ) );
        }
        $existingNode->setAttribute( 'contentobject_is_published', 1 );

        eZDebug::createAccumulatorGroup( 'nice_urls_total', 'Nice urls' );

        if ( $mainNodeID > 0 )
        {
            $existingNodeID = $existingNode->attribute( 'node_id' );
            if ( $existingNodeID != $mainNodeID )
            {
                eZContentBrowseRecent::updateNodeID( $existingNodeID, $mainNodeID );
            }
            $existingNode->setAttribute( 'main_node_id', $mainNodeID );
        }
        else
        {
            $existingNode->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
        }

        $existingNode->store();

        if ( $updateSectionID )
        {
            eZContentOperationCollection::updateSectionID( $objectID, $versionNum );
        }
        $db->commit();
        if ( $mainNodeID == false )
        {
            return $existingNode->attribute( 'node_id' );
        }
    }

    static public function updateSectionID( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );

        $version = $object->version( $versionNum );

        if ( $versionNum == 1 or
             $object->attribute( 'current_version' ) == $versionNum )
        {
            $newMainAssignment = null;
            $newMainAssignments = eZNodeAssignment::fetchForObject( $objectID, $versionNum, 1 );
            if ( isset( $newMainAssignments[0] ) )
            {
                $newMainAssignment = $newMainAssignments[0];
            }
            // we should not update section id for toplevel nodes
            if ( $newMainAssignment && $newMainAssignment->attribute( 'parent_node' ) != 1 )
            {
                // We should check if current object already has been updated for section_id
                // If yes we should not update object section_id by $parentNodeSectionID
                $sectionID = $object->attribute( 'section_id' );
                if ( $sectionID > 0 )
                    return;

                $newParentObject = $newMainAssignment->getParentObject();
                if ( !$newParentObject )
                {
                    return array( 'status' => eZModuleOperationInfo::STATUS_CANCELLED );
                }
                $parentNodeSectionID = $newParentObject->attribute( 'section_id' );
                $object->setAttribute( 'section_id', $parentNodeSectionID );
                $object->store();
            }

            return;
        }

        $newMainAssignmentList = eZNodeAssignment::fetchForObject( $objectID, $versionNum, 1 );
        $newMainAssignment = ( count( $newMainAssignmentList ) ) ? array_pop( $newMainAssignmentList ) : null;

        $currentVersion = $object->attribute( 'current' );
        // Here we need to fetch published nodes and not old node assignments.
        $oldMainNode = $object->mainNode();

        if ( $newMainAssignment && $oldMainNode
             &&  $newMainAssignment->attribute( 'parent_node' ) != $oldMainNode->attribute( 'parent_node_id' ) )
        {
            $oldMainParentNode = $oldMainNode->attribute( 'parent' );
            if ( $oldMainParentNode )
            {
                $oldParentObject = $oldMainParentNode->attribute( 'object' );
                $oldParentObjectSectionID = $oldParentObject->attribute( 'section_id' );
                if ( $oldParentObjectSectionID == $object->attribute( 'section_id' ) )
                {
                    $newParentNode = $newMainAssignment->attribute( 'parent_node_obj' );
                    if ( !$newParentNode )
                        return;
                    $newParentObject = $newParentNode->attribute( 'object' );
                    if ( !$newParentObject )
                        return;

                    $newSectionID = $newParentObject->attribute( 'section_id' );

                    if ( $newSectionID != $object->attribute( 'section_id' ) )
                    {
                        $oldSectionID = $object->attribute( 'section_id' );
                        $object->setAttribute( 'section_id', $newSectionID );

                        $db = eZDB::instance();
                        $db->begin();
                        $object->store();
                        $mainNodeID = $object->attribute( 'main_node_id' );
                        if ( $mainNodeID > 0 )
                        {
                            eZContentObjectTreeNode::assignSectionToSubTree( $mainNodeID,
                                                                             $newSectionID,
                                                                             $oldSectionID );
                        }
                        $db->commit();
                    }
                }
            }
        }
    }

    static public function removeOldNodes( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );


        $version = $object->version( $versionNum );
        $moveToTrash = true;

        $assignedExistingNodes = $object->attribute( 'assigned_nodes' );

        $curentVersionNodeAssignments = $version->attribute( 'node_assignments' );
        $removeParentNodeList = array();
        $removeAssignmentsList = array();
        foreach ( $curentVersionNodeAssignments as $nodeAssignment )
        {
            $nodeAssignmentOpcode = $nodeAssignment->attribute( 'op_code' );
            if ( $nodeAssignmentOpcode == eZNodeAssignment::OP_CODE_REMOVE ||
                 $nodeAssignmentOpcode == eZNodeAssignment::OP_CODE_REMOVE_NOP )
            {
                $removeAssignmentsList[] = $nodeAssignment->attribute( 'id' );
                if ( $nodeAssignmentOpcode == eZNodeAssignment::OP_CODE_REMOVE )
                {
                    $removeParentNodeList[] = $nodeAssignment->attribute( 'parent_node' );
                }
            }
        }

        $db = eZDB::instance();
        $db->begin();
        foreach ( $assignedExistingNodes as $node )
        {
            if ( in_array( $node->attribute( 'parent_node_id' ), $removeParentNodeList ) )
            {
                eZContentObjectTreeNode::removeSubtrees( array( $node->attribute( 'node_id' ) ), $moveToTrash );
            }
        }

        if ( count( $removeAssignmentsList ) > 0 )
        {
            eZNodeAssignment::purgeByID( $removeAssignmentsList );
        }

        $db->commit();
    }

    // New function which resets the op_code field when the object is published.
    static public function resetNodeassignmentOpcodes( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );
        $version = $object->version( $versionNum );
        $nodeAssignments = $version->attribute( 'node_assignments' );
        foreach ( $nodeAssignments as $nodeAssignment )
        {
            if ( ( $nodeAssignment->attribute( 'op_code' ) & 1 ) == eZNodeAssignment::OP_CODE_EXECUTE )
            {
                $nodeAssignment->setAttribute( 'op_code', ( $nodeAssignment->attribute( 'op_code' ) & ~1 ) );
                $nodeAssignment->store();
            }
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static public function registerSearchObject( $objectID, $versionNum )
    {
        $objectID = (int)$objectID;
        eZDebug::createAccumulatorGroup( 'search_total', 'Search Total' );

        $ini = eZINI::instance( 'site.ini' );
        $delayedIndexing = $ini->variable( 'SearchSettings', 'DelayedIndexing' );

        if ( $delayedIndexing == 'enabled' )
        {
            $db = eZDB::instance();
            $rows = $db->arrayQuery( "SELECT param FROM ezpending_actions WHERE action='index_object' AND param = '$objectID'" );
            if ( count( $rows ) == 0 )
            {
                $db->query( "INSERT INTO ezpending_actions( action, param ) VALUES ( 'index_object', '$objectID' )" );
            }
            return;
        }
        elseif ( $delayedIndexing == 'classbased' )
        {
            $classList = $ini->variable( 'SearchSettings', 'DelayedIndexingClassList' );
            $object = eZContentObject::fetch( $objectID );
            $classIdentifier = $object->attribute( 'class_identifier' );
            if ( is_array( $classList ) && in_array( $classIdentifier, $classList ) )
            {
                $db = eZDB::instance();
                $db->query( "INSERT INTO ezpending_actions( action, param ) VALUES ( 'index_object', '$objectID' )" );
                return;
            }
        }

        $object = eZContentObject::fetch( $objectID );
        // Register the object in the search engine.
        $needCommit = eZSearch::needCommit();
        $doDeleteFirst = eZSearch::needRemoveWithUpdate();
        if ($doDeleteFirst)
        {
            eZDebug::accumulatorStart( 'remove_object', 'search_total', 'remove object' );
            eZSearch::removeObject( $object, $needCommit );
            eZDebug::accumulatorStop( 'remove_object' );
        }

        eZDebug::accumulatorStart( 'add_object', 'search_total', 'add object' );
        eZSearch::addObject( $object, $needCommit );
        eZDebug::accumulatorStop( 'add_object' );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static public function createNotificationEvent( $objectID, $versionNum )
    {
        $event = eZNotificationEvent::create( 'ezpublish', array( 'object' => $objectID,
                                                                   'version' => $versionNum ) );
        $event->store();
    }

    /*!
      Start global transaction.

      \deprecated since version 4.1.0, this method will be removed in future major releases
     */
    function beginPublish()
    {
        $db = eZDB::instance();
        $db->begin();
    }

    /*!
     Stop (commit) global transaction.

     \deprecated since version 4.1.0, this method will be removed in future major releases
     */
    function endPublish()
    {
        $db = eZDB::instance();
        $db->commit();
    }

    /*!
     Copies missing translations from published version to the draft.
     */
    static public function copyTranslations( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );
        $publishedVersionNum = $object->attribute( 'current_version' );
        if ( !$publishedVersionNum )
        {
            return;
        }
        $publishedVersion = $object->version( $publishedVersionNum );
        $publishedVersionTranslations = $publishedVersion->translations();
        $publishedLanguages = eZContentLanguage::languagesByMask( $object->attribute( 'language_mask' ) );
        $publishedLanguageCodes = array_keys( $publishedLanguages );

        $version = $object->version( $versionNum );
        $versionTranslationList = $version->translationList( false, false );

        foreach ( $publishedVersionTranslations as $translation )
        {
            if ( in_array( $translation->attribute( 'language_code' ), $versionTranslationList )
              || !in_array( $translation->attribute( 'language_code' ), $publishedLanguageCodes ) )
            {
                continue;
            }

            $contentObjectAttributes = $translation->objectAttributes();
            foreach ( $contentObjectAttributes as $attribute )
            {
                $clonedAttribute = $attribute->cloneContentObjectAttribute( $versionNum, $publishedVersionNum, $objectID );
                $clonedAttribute->sync();
            }
        }

        $version->updateLanguageMask();
    }

    /*!
     Updates non-translatable attributes.
     */
    static public function updateNontranslatableAttributes( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );
        $version = $object->version( $versionNum );

        $nonTranslatableAttributes = $version->nonTranslatableAttributesToUpdate();
        if ( $nonTranslatableAttributes )
        {
            $attributes = $version->contentObjectAttributes( $version->initialLanguageCode() );
            $attributeByClassAttrID = array();
            foreach ( $attributes as $attribute )
            {
                $attributeByClassAttrID[$attribute->attribute( 'contentclassattribute_id' )] = $attribute;
            }

            foreach ( $nonTranslatableAttributes as $attributeToUpdate )
            {
                $originalAttribute =& $attributeByClassAttrID[$attributeToUpdate->attribute( 'contentclassattribute_id' )];
                if ( $originalAttribute )
                {
                    unset( $tmp );
                    $tmp = $attributeToUpdate;
                    $tmp->initialize( $attributeToUpdate->attribute( 'version' ), $originalAttribute );
                    $tmp->setAttribute( 'id', $attributeToUpdate->attribute( 'id' ) );
                    $tmp->setAttribute( 'language_code', $attributeToUpdate->attribute( 'language_code' ) );
                    $tmp->setAttribute( 'language_id', $attributeToUpdate->attribute( 'language_id' ) );
                    $tmp->setAttribute( 'attribute_original_id', $originalAttribute->attribute( 'id' ) );
                    $tmp->store();
                    $tmp->postInitialize( $attributeToUpdate->attribute( 'version' ), $originalAttribute );
                }
            }
        }
    }

    static public function removeTemporaryDrafts( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );
        $object->cleanupInternalDrafts( eZUser::currentUserID() );
    }

    /**
     * Moves a node
     *
     * @param int $nodeID
     * @param int $objectID
     * @param int $newParentNodeID
     *
     * @return array An array with operation status, always true
     */
    static public function moveNode( $nodeID, $objectID, $newParentNodeID )
    {
       if( !eZContentObjectTreeNodeOperations::move( $nodeID, $newParentNodeID ) )
       {
           eZDebug::writeError( "Failed to move node $nodeID as child of parent node $newParentNodeID",
                                'content/action' );

           return array( 'status' => false );
       }

       eZContentObject::fixReverseRelations( $objectID, 'move' );

       return array( 'status' => true );
    }

    /**
     * Adds a new nodeAssignment
     *
     * @param int $nodeID
     * @param int $objectId
     * @param array $selectedNodeIDArray
     *
     * @return array An array with operation status, always true
     */
    static public function addAssignment( $nodeID, $objectID, $selectedNodeIDArray )
    {
        $userClassIDArray = eZUser::contentClassIDs();

        $object = eZContentObject::fetch( $objectID );
        $class = $object->contentClass();

        $nodeAssignmentList = eZNodeAssignment::fetchForObject( $objectID, $object->attribute( 'current_version' ), 0, false );
        $assignedNodes = $object->assignedNodes();

        $parentNodeIDArray = array();

        foreach ( $assignedNodes as $assignedNode )
        {
            $append = false;
            foreach ( $nodeAssignmentList as $nodeAssignment )
            {
                if ( $nodeAssignment['parent_node'] == $assignedNode->attribute( 'parent_node_id' ) )
                {
                    $append = true;
                    break;
                }
            }
            if ( $append )
            {
                $parentNodeIDArray[] = $assignedNode->attribute( 'parent_node_id' );
            }
        }

        $db = eZDB::instance();
        $db->begin();
        $locationAdded = false;
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        foreach ( $selectedNodeIDArray as $selectedNodeID )
        {
            if ( !in_array( $selectedNodeID, $parentNodeIDArray ) )
            {
                $parentNode = eZContentObjectTreeNode::fetch( $selectedNodeID );
                $parentNodeObject = $parentNode->attribute( 'object' );

                $canCreate = ( ( $parentNode->checkAccess( 'create', $class->attribute( 'id' ), $parentNodeObject->attribute( 'contentclass_id' ) ) == 1 ) ||
                                ( $parentNode->canAddLocation() && $node->canRead() ) );

                if ( $canCreate )
                {
                    $insertedNode = $object->addLocation( $selectedNodeID, true );

                    // Now set is as published and fix main_node_id
                    $insertedNode->setAttribute( 'contentobject_is_published', 1 );
                    $insertedNode->setAttribute( 'main_node_id', $node->attribute( 'main_node_id' ) );
                    $insertedNode->setAttribute( 'contentobject_version', $node->attribute( 'contentobject_version' ) );
                    // Make sure the url alias is set updated.
                    $insertedNode->updateSubTreePath();
                    $insertedNode->sync();

                    $locationAdded = true;
                }
            }
        }
        if ( $locationAdded )
        {

            //call appropriate method from search engine
            eZSearch::addNodeAssignment( $nodeID, $objectID, $selectedNodeIDArray );

            // clear user policy cache if this was a user object
            if ( in_array( $object->attribute( 'contentclass_id' ), $userClassIDArray ) )
            {
                eZUser::cleanupCache();
            }


        }
        $db->commit();

        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return array( 'status' => true );
    }

    /**
     * Removes a nodeAssignment or a list of nodeAssigments
     *
     * @param int $nodeID
     * @param int $objectID
     * @param array $removeList
     * @param bool $moveToTrash
     *
     * @return array An array with operation status, always true
     */
    static public function removeAssignment( $nodeID, $objectID, $removeList, $moveToTrash )
    {
        $mainNodeChanged      = false;
        $nodeIDList           = array();
        $mainNodeID           = $nodeID;
        $userClassIDArray     = eZUser::contentClassIDs();
        $object               = eZContentObject::fetch( $objectID );
        $nodeAssignmentIDList = array();

        $db = eZDB::instance();
        $db->begin();

        foreach ( $removeList as $key => $node )
        {
            $removeObjectID = $node->attribute( 'contentobject_id' );
            $removeObject = eZContentObject::fetch( $removeObjectID );
            $nodeAssignmentList = eZNodeAssignment::fetchForObject( $removeObjectID, $removeObject->attribute( 'current_version' ), 0, false );
            foreach ( $nodeAssignmentList as $nodeAssignmentKey => $nodeAssignment )
            {
                if ( $nodeAssignment['parent_node'] == $node->attribute( 'parent_node_id' ) )
                {
                    $nodeAssignmentIDList[] = $nodeAssignment['id'];
                    unset( $nodeAssignmentList[$nodeAssignmentKey] );
                }
            }

            if ( $node->attribute( 'node_id' ) == $node->attribute( 'main_node_id' ) )
                $mainNodeChanged = true;
            $node->removeThis();

            $nodeIDList[] = $node->attribute( 'node_id' );
        }

        // Give other search engines that the default one a chance to reindex
        // when removing locations.
        // include_once( 'kernel/classes/ezsearch.php' );
        if ( !eZSearch::getEngine() instanceof eZSearchEngine )
        {
            // include_once( 'kernel/content/ezcontentoperationcollection.php' );
            eZContentOperationCollection::registerSearchObject( $objectID, $object->attribute( 'current_version' ) );
        }

        eZNodeAssignment::purgeByID( array_unique( $nodeAssignmentIDList ) );

        if ( $mainNodeChanged )
        {
            $allNodes   = $object->assignedNodes();
            $mainNode   = $allNodes[0];
            $mainNodeID = $mainNode->attribute( 'node_id' );
            eZContentObjectTreeNode::updateMainNodeID( $mainNodeID, $objectID, false, $mainNode->attribute( 'parent_node_id' ) );
        }

        $db->commit();


        //call appropriate method from search engine
        eZSearch::removeNodeAssignment( $nodeID, $mainNodeID, $objectID, $nodeIDList );

        eZContentCacheManager::clearObjectViewCacheIfNeeded( $objectID );

        // clear user policy cache if this was a user object
        if ( in_array( $object->attribute( 'contentclass_id' ), $userClassIDArray ) )
        {
            eZUser::cleanupCache();
        }

        // we don't clear template block cache here since it's cleared in eZContentObjectTreeNode::removeNode()

        return array( 'status' => true );
    }

    /**
     * Deletes a content object, or a list of content objects
     *
     * @param array $deleteIDArray
     * @param bool $moveToTrash
     *
     * @return array An array with operation status, always true
     */
    static public function deleteObject( $deleteIDArray, $moveToTrash = false )
    {
       eZContentObjectTreeNode::removeSubtrees( $deleteIDArray, $moveToTrash );
       return array( 'status' => true );
    }

    /**
     * Changes an contentobject's status
     *
     * @param int $nodeID
     *
     * @return array An array with operation status, always true
     */
    static public function changeHideStatus( $nodeID )
    {
        $action = 'hide';

        $curNode = eZContentObjectTreeNode::fetch( $nodeID );
        if ( is_object( $curNode ) )
        {
            if ( $curNode->attribute( 'is_hidden' ) )
            {
                eZContentObjectTreeNode::unhideSubTree( $curNode );
                $action = 'show';
            }
            else
                eZContentObjectTreeNode::hideSubTree( $curNode );
        }

        //call appropriate method from search engine
        eZSearch::updateNodeVisibility( $nodeID, $action );

        return array( 'status' => true );
    }

    /**
     * Swap a node with another one
     *
     * @param int $nodeID
     * @param int $selectedNodeID
     * @param array $nodeIdList
     *
     * @return array An array with operation status, always true
     */
    static public function swapNode( $nodeID, $selectedNodeID, $nodeIdList = array() )
    {
        $userClassIDArray = eZUser::contentClassIDs();

        $node             = eZContentObjectTreeNode::fetch( $nodeID );
        $selectedNode     = eZContentObjectTreeNode::fetch( $selectedNodeID );
        $object           = $node->object();
        $nodeParentNodeID = $node->attribute( 'parent_node_id' );
        $nodeParent       = $node->attribute( 'parent' );

        $objectID      = $object->attribute( 'id' );
        $objectVersion = $object->attribute( 'current_version' );

        $selectedObject           = $selectedNode->object();
        $selectedObjectID         = $selectedObject->attribute( 'id' );
        $selectedObjectVersion    = $selectedObject->attribute( 'current_version' );
        $selectedNodeParentNodeID = $selectedNode->attribute( 'parent_node_id' );
        $selectedNodeParent       = $selectedNode->attribute( 'parent' );

        $db = eZDB::instance();
        $db->begin();

        $node->setAttribute( 'contentobject_id', $selectedObjectID );
        $node->setAttribute( 'contentobject_version', $selectedObjectVersion );

        $selectedNode->setAttribute( 'contentobject_id', $objectID );
        $selectedNode->setAttribute( 'contentobject_version', $objectVersion );

        // fix main node id
        if ( $node->isMain() && !$selectedNode->isMain() )
        {
            $node->setAttribute( 'main_node_id', $selectedNode->attribute( 'main_node_id' ) );
            $selectedNode->setAttribute( 'main_node_id', $selectedNode->attribute( 'node_id' ) );
        }
        else if ( $selectedNode->isMain() && !$node->isMain() )
        {
            $selectedNode->setAttribute( 'main_node_id', $node->attribute( 'main_node_id' ) );
            $node->setAttribute( 'main_node_id', $node->attribute( 'node_id' ) );
        }

        $node->store();
        $selectedNode->store();

        // clear user policy cache if this was a user object
        if ( in_array( $object->attribute( 'contentclass_id' ), $userClassIDArray ) or
             in_array( $selectedObject->attribute( 'contentclass_id' ), $userClassIDArray ) )
        {
            eZUser::cleanupCache();
        }

        // modify path string
        $changedOriginalNode = eZContentObjectTreeNode::fetch( $nodeID );
        $changedOriginalNode->updateSubTreePath();
        $changedTargetNode = eZContentObjectTreeNode::fetch( $selectedNodeID );
        $changedTargetNode->updateSubTreePath();

        // modify section
        if ( $changedOriginalNode->isMain() )
        {
            $changedOriginalObject = $changedOriginalNode->object();
            $parentObject = $nodeParent->object();
            if ( $changedOriginalObject->attribute( 'section_id' ) != $parentObject->attribute( 'section_id' ) )
            {

                eZContentObjectTreeNode::assignSectionToSubTree( $changedOriginalNode->attribute( 'main_node_id' ),
                                                                $parentObject->attribute( 'section_id' ),
                                                                $changedOriginalObject->attribute( 'section_id' ) );
            }
        }
        if ( $changedTargetNode->isMain() )
        {
            $changedTargetObject = $changedTargetNode->object();
            $selectedParentObject = $selectedNodeParent->object();
            if ( $changedTargetObject->attribute( 'section_id' ) != $selectedParentObject->attribute( 'section_id' ) )
            {

                eZContentObjectTreeNode::assignSectionToSubTree( $changedTargetNode->attribute( 'main_node_id' ),
                                                                $selectedParentObject->attribute( 'section_id' ),
                                                                $changedTargetObject->attribute( 'section_id' ) );
            }
        }

        eZContentObject::fixReverseRelations( $objectID, 'swap' );
        eZContentObject::fixReverseRelations( $selectedObjectID, 'swap' );

        $db->commit();

        // clear cache for new placement.
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        eZSearch::swapNode( $nodeID, $selectedNodeID, $nodeIdList = array() );

        return array( 'status' => true );
    }

    /**
     * Assigns a node to a section
     *
     * @param int $nodeID
     * @param int $selectedSectionID
     *
     * @return array An array with operation status, always true
     */
    static public function updateSection( $nodeID, $selectedSectionID )
    {
        eZContentObjectTreeNode::assignSectionToSubTree( $nodeID, $selectedSectionID );

        //call appropriate method from search engine
        eZSearch::updateNodeSection( $nodeID, $selectedSectionID );
    }

    /**
     * Changes the status of a translation
     *
     * @param int $objectID
     * @param int $status
     *
     * @return array An array with operation status, always true
     */
    static public function changeTranslationAvailableStatus( $objectID, $status = false )
    {
        $object = eZContentObject::fetch( $objectID );
        if ( !$object->canEdit() )
        {
            return array( 'status' => false );
        }
        if ( $object->isAlwaysAvailable() & $status == false )
        {
            $object->setAlwaysAvailableLanguageID( false );
            eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
        }
        else if ( !$object->isAlwaysAvailable() & $status == true )
        {
            $object->setAlwaysAvailableLanguageID( $object->attribute( 'initial_language_id' ) );
            eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
        }
        return array( 'status' => true );
    }

    /**
     * Changes the sort order for a node
     *
     * @param int $nodeID
     * @param string $sortingField
     * @param bool $sortingOrder
     *
     * @return array An array with operation status, always true
     */
    static public function changeSortOrder( $nodeID, $sortingField, $sortingOrder = false )
    {
        $curNode = eZContentObjectTreeNode::fetch( $nodeID );
        if ( is_object( $curNode ) )
        {
             $db = eZDB::instance();
             $db->begin();
             $curNode->setAttribute( 'sort_field', $sortingField );
             $curNode->setAttribute( 'sort_order', $sortingOrder );
             $curNode->store();
             $db->commit();
             $object = $curNode->object();
             eZContentCacheManager::clearContentCache( $object->attribute( 'id' ) );
        }
        return array( 'status' => true );
    }

    /**
     * Updates the priority of a node
     *
     * @param int $nodeID
     * @param array $priorityArray
     * @param array $priorityArray
     *
     * @return array An array with operation status, always true
     */
    static public function updatePriority( $nodeID, $priorityArray = array(), $priorityIDArray = array() )
    {
        $curNode = eZContentObjectTreeNode::fetch( $nodeID );
        if ( is_object( $curNode ) )
        {
             $db = eZDB::instance();
             $db->begin();
             for ( $i = 0, $l = count( $priorityArray ); $i < $l; $i++ )
             {
                 $priority = (int) $priorityArray[$i];
                 $nodeID = (int) $priorityIDArray[$i];
                 $db->query( "UPDATE ezcontentobject_tree SET priority=$priority WHERE node_id=$nodeID" );
             }
             $curNode->updateAndStoreModified();
             $db->commit();
        }
        return array( 'status' => true );
    }

    /**
     * Update a node's main assignement
     *
     * @param int $mainAssignmentID
     * @param int $objectID
     * @param int $mainAssignmentParentID
     *
     * @return array An array with operation status, always true
     */
    static public function UpdateMainAssignment( $mainAssignmentID, $ObjectID, $mainAssignmentParentID )
    {
        eZContentObjectTreeNode::updateMainNodeID( $mainAssignmentID, $ObjectID, false, $mainAssignmentParentID );
        eZContentCacheManager::clearContentCacheIfNeeded( $ObjectID );
        return array( 'status' => true );
    }

    /**
     * Updates an contentobject's initial language
     *
     * @param int $objectID
     * @param int $newInitialLanguageID
     *
     * @return array An array with operation status, always true
     */
    static public function updateInitialLanguage( $objectID, $newInitialLanguageID )
    {
        $object = eZContentObject::fetch( $objectID );
        $language = eZContentLanguage::fetch( $newInitialLanguageID );
        if ( $language and !$language->attribute( 'disabled' ) )
        {
            $object->setAttribute( 'initial_language_id', $newInitialLanguageID );
            $objectName = $object->name( false, $language->attribute( 'locale' ) );
            $object->setAttribute( 'name', $objectName );
            $object->store();

            if ( $object->isAlwaysAvailable() )
            {
                $object->setAlwaysAvailableLanguageID( $newInitialLanguageID );
            }

            $nodes = $object->assignedNodes();
            foreach ( $nodes as $node )
            {
                $node->updateSubTreePath();
            }
        }

        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return array( 'status' => true );
    }

    /**
     * Set the always available flag for a content object
     *
     * @param int $objectID
     * @param int $newAlwaysAvailable
     * @return array An array with operation status, always true
     */
    static public function updateAlwaysAvailable( $objectID, $newAlwaysAvailable )
    {
        $object = eZContentObject::fetch( $objectID );

        if ( $object->isAlwaysAvailable() & $newAlwaysAvailable == false )
        {
            $object->setAlwaysAvailableLanguageID( false );
            eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
        }
        else if ( !$object->isAlwaysAvailable() & $newAlwaysAvailable == true )
        {
            $object->setAlwaysAvailableLanguageID( $object->attribute( 'initial_language_id' ) );
            eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
        }

        return array( 'status' => true );
    }

    /**
     * Removes a translation for a contentobject
     *
     * @param int $objectID
     * @param array
     * @return array An array with operation status, always true
     */
    static public function removeTranslation( $objectID, $languageIDArray )
    {
        $object = eZContentObject::fetch( $objectID );

        foreach( $languageIDArray as $languageID )
        {
            if ( !$object->removeTranslation( $languageID ) )
            {
                eZDebug::writeError( "Object with id $objectID: cannot remove the translation with language id $languageID!",
                                     'content/translation' );
            }
        }

        eZContentOperationCollection::registerSearchObject( $object->attribute( 'id' ), $object->attribute( 'current_version' ) );

        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return array( 'status' => true );
    }

    /**
     * Update a contentobject's state
     *
     * @param int $objectID
     * @param int $selectedStateIDList
     *
     * @return array An array with operation status, always true
     */
    static public function updateObjectState( $objectID, $selectedStateIDList )
    {
        $object = eZContentObject::fetch( $objectID );

        // we don't need to re-assign states the object currently already has assigned
        $currentStateIDArray = $object->attribute( 'state_id_array' );
        $selectedStateIDList = array_diff( $selectedStateIDList, $currentStateIDArray );

        // filter out any states the current user is not allowed to assign
        $canAssignStateIDList = $object->attribute( 'allowed_assign_state_id_list' );
        $selectedStateIDList = array_intersect( $selectedStateIDList, $canAssignStateIDList );

        foreach ( $selectedStateIDList as $selectedStateID )
        {
            $state = eZContentObjectState::fetchById( $selectedStateID );
            $object->assignState( $state );
        }
        //call appropriate method from search engine
        eZSearch::updateObjectState($objectID, $selectedStateIDList);

        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return array( 'status' => true );
    }
}
?>

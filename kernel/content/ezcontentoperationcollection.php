<?php
/**
 * File containing the eZContentOperationCollection class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZContentOperationCollection ezcontentoperationcollection.php
  \brief The class eZContentOperationCollection does

*/

class eZContentOperationCollection
{
    /**
     * Use by {@see beginTransaction()} and {@see commitTransaction()} to handle nested publish operations
     */
    private static $operationsStack = 0;

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

    /**
     * Starts a database transaction.
     */
    static public function beginTransaction()
    {
        // We only start a transaction if another content publish operation hasn't been started
        if ( ++self::$operationsStack === 1 )
        {
            eZDB::instance()->begin();
        }
    }

    /**
     * Commit a previously started database transaction.
     */
    static public function commitTransaction()
    {
        if ( --self::$operationsStack === 0 )
        {
            eZDB::instance()->commit();
        }
    }

    static public function setVersionStatus( $objectID, $versionNum, $status )
    {
        $db = eZDB::instance();
        $db->begin();

        if ( !$versionNum )
        {
            $objectRows = $db->arrayQuery( "SELECT * FROM ezcontentobject WHERE id = $objectID FOR UPDATE" );
            if ( empty( $objectRows ) )
            {
                $db->commit(); // We haven't made any changes, but commit here to avoid affecting any outer transactions.
                return;
            }

            $versionNum = $objectRows[0]['current_version'];
        }

        $versionRows = $db->arrayQuery( "SELECT * FROM ezcontentobject_version WHERE version = $versionNum AND contentobject_id = $objectID FOR UPDATE" );
        if ( empty( $versionRows ) )
        {
            $db->commit(); // We haven't made any changes, but commit here to avoid affecting any outer transactions.
            return;
        }

        $version = eZContentObjectVersion::fetch( $versionRows[0]['id'] );
        if ( !$version )
        {
            $db->commit(); // We haven't made any changes, but commit here to avoid affecting any outer transactions.
            return;
        }

        $version->setAttribute( 'status', $status );
        $version->store();

        $db->commit();
    }

    static public function setObjectStatusPublished( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );
        $version = $object->version( $versionNum );

        $db = eZDB::instance();
        $db->begin();

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
            eZUser::purgeUserCacheByUserId( $object->attribute( 'id' ) );
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

        $fromNodeID       = $nodeAssignment->attribute( 'from_node_id' );
        $originalObjectID = $nodeAssignment->attribute( 'contentobject_id' );

        $nodeID           =  $nodeAssignment->attribute( 'parent_node' );
        $opCode           =  $nodeAssignment->attribute( 'op_code' );
        $parentNode       = eZContentObjectTreeNode::fetch( $nodeID );

        // if parent doesn't exist, return. See issue #18320
        if ( !$parentNode instanceof eZContentObjectTreeNode )
        {
            eZDebug::writeError( "Parent node doesn't exist. object id: $objectID, node_assignment id: " . $nodeAssignment->attribute( 'id' ), __METHOD__ );
            return;
        }
        $parentNodeID     =  $parentNode->attribute( 'node_id' );
        $existingNode     =  null;

        $db = eZDB::instance();
        $db->begin();
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
                    if ( !eZSys::isShellExecution() and !$user->isAnonymous() )
                    {
                        eZContentBrowseRecent::createNew( $user->id(), $parentNode->attribute( 'node_id' ), $parentNode->attribute( 'name' ) );
                    }
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
                    eZDebug::writeError( "NodeAssignment '" . $nodeAssignment->attribute( 'id' ) . "' is marked with op_code='$opCode' but has no data in from_node_id. Cannot use it for moving node.", __METHOD__ );
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
            if ( $nodeAssignment->attribute( 'is_hidden' ) )
            {
                $existingNode->setAttribute( 'is_hidden', 1 );
                $existingNode->setAttribute( 'is_invisible', 1 );
            }
            $existingNode->setAttribute( 'priority', $nodeAssignment->attribute( 'priority' ) );
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
        if ( !$object instanceof eZContentObject )
        {
            eZDebug::writeError( 'Unable to find object #' . $objectID, __METHOD__ );
            return;
        }

        $version = $object->version( $versionNum );
        if ( !$version instanceof eZContentObjectVersion )
        {
            eZDebug::writeError( 'Unable to find version #' . $versionNum . ' for object #' . $objectID, __METHOD__ );
            return;
        }

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

    /**
     * Registers the object in search engine.
     *
     * @note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     *       the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int $objectID Id of the object.
     * @param int $version Operation collection passes this default param. Not used in the method
     * @param bool $isMoved true if node is being moved
     */
    static public function registerSearchObject( $objectID, $version = null, $isMoved = false )
    {
        $objectID = (int)$objectID;
        eZDebug::createAccumulatorGroup( 'search_total', 'Search Total' );

        $ini = eZINI::instance( 'site.ini' );
        $insertPendingAction = false;
        $object = null;

        switch ( $ini->variable( 'SearchSettings', 'DelayedIndexing' ) )
        {
            case 'enabled':
                $insertPendingAction = true;
                break;
            case 'classbased':
                $classList = $ini->variable( 'SearchSettings', 'DelayedIndexingClassList' );
                $object = eZContentObject::fetch( $objectID );
                if ( is_array( $classList ) && in_array( $object->attribute( 'class_identifier' ), $classList ) )
                {
                    $insertPendingAction = true;
                }
        }

        if ( $insertPendingAction )
        {
            $action = $isMoved ? 'index_moved_node' : 'index_object';

            eZDB::instance()->query( "INSERT INTO ezpending_actions( action, param ) VALUES ( '$action', '$objectID' )" );
            return;
        }

        if ( $object === null )
            $object = eZContentObject::fetch( $objectID );

        // Register the object in the search engine.
        $needCommit = eZSearch::needCommit();
        if ( eZSearch::needRemoveWithUpdate() )
        {
            eZDebug::accumulatorStart( 'remove_object', 'search_total', 'remove object' );
            eZSearch::removeObjectById( $objectID );
            eZDebug::accumulatorStop( 'remove_object' );
        }

        eZDebug::accumulatorStart( 'add_object', 'search_total', 'add object' );
        if ( !eZSearch::addObject( $object, $needCommit ) )
        {
            eZDebug::writeError( "Failed adding object ID {$object->attribute( 'id' )} in the search engine", __METHOD__ );
        }
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
     Copies missing translations from published version to the draft.
     */
    static public function copyTranslations( $objectID, $versionNum )
    {
        $object = eZContentObject::fetch( $objectID );
        if ( !$object instanceof eZContentObject )
        {
            return array( 'status' => eZModuleOperationInfo::STATUS_CANCELLED );
        }
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
                                __METHOD__ );

           return array( 'status' => false );
       }

       eZContentObject::fixReverseRelations( $objectID, 'move' );

        if ( !eZSearch::getEngine() instanceof eZSearchEngine )
        {
            eZContentOperationCollection::registerSearchObject( $objectID );
        }

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
            eZAudit::writeAudit( 'location-assign', array( 'Main Node ID' => $object->attribute( 'main_node_id' ),
                                                           'Content object ID' => $object->attribute( 'id' ),
                                                           'Content object name' => $object->attribute( 'name' ),
                                                           'New Locations Parent Node ID Array' => implode( ', ' , $selectedNodeIDArray ),
                                                           'Comment' => 'Assigned new locations to the current node: eZContentOperationCollection::addAssignment()' ) );
            //call appropriate method from search engine
            eZSearch::addNodeAssignment( $nodeID, $objectID, $selectedNodeIDArray );

            // clear user policy cache if this was a user object
            if ( in_array( $object->attribute( 'contentclass_id' ), $userClassIDArray ) )
            {
                eZUser::purgeUserCacheByUserId( $object->attribute( 'id' ) );
            }


        }
        $db->commit();

        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return array( 'status' => true );
    }

    /**
     * Removes nodes
     *
     * This function does not check about permissions, this is the responsibility of the caller!
     *
     * @param array $removeNodeIdList Array of Node ID to remove
     *
     * @return array An array with operation status, always true
     */
    static public function removeNodes( array $removeNodeIdList )
    {
        $mainNodeChanged      = array();
        $nodeAssignmentIdList = array();
        $objectIdList         = array();

        $db = eZDB::instance();
        $db->begin();

        foreach ( $removeNodeIdList as $nodeId )
        {
            $node     = eZContentObjectTreeNode::fetch($nodeId);
            $objectId = $node->attribute( 'contentobject_id' );
            foreach ( eZNodeAssignment::fetchForObject( $objectId,
                                                        eZContentObject::fetch( $objectId )->attribute( 'current_version' ),
                                                        0, false ) as $nodeAssignmentKey => $nodeAssignment )
            {
                if ( $nodeAssignment['parent_node'] == $node->attribute( 'parent_node_id' ) )
                {
                    $nodeAssignmentIdList[$nodeAssignment['id']] = 1;
                }
            }

            eZAudit::writeAudit( 'location-remove', array( 'Removed Node ID' => $nodeId,
                                                           'Parent Node ID' => $node->attribute( 'parent_node_id' ),
                                                           'Content object ID' => $objectId,
                                                           'Content object name' => $node->attribute( 'name' ),
                                                           'Was main node' => ( $nodeId == $node->attribute( 'main_node_id' ) ? 'Yes' : 'No' )
            ) );

            if ( $nodeId == $node->attribute( 'main_node_id' ) )
                $mainNodeChanged[$objectId] = 1;
            $node->removeThis();

            if ( !isset( $objectIdList[$objectId] ) )
                $objectIdList[$objectId] = eZContentObject::fetch( $objectId );
        }

        eZNodeAssignment::purgeByID( array_keys( $nodeAssignmentIdList ) );

        foreach ( array_keys( $mainNodeChanged ) as $objectId )
        {
            $allNodes = $objectIdList[$objectId]->assignedNodes();
            // Registering node that will be promoted as 'main'
            if ( isset( $allNodes[0] ) )
            {
                $mainNodeChanged[$objectId] = $allNodes[0];
                eZAudit::writeAudit( 'main-node-update', array( 'Content object ID' => $objectId,
                                                                'New Main Node ID' => $allNodes[0]->attribute( 'node_id' ),
                                                                'New Parent Node ID' => $allNodes[0]->attribute( 'parent_node_id' ),
                                                                'Comment' => 'Updated the main location of the current object: eZContentOperationCollection::removeNodes()' )
                );
                eZContentObjectTreeNode::updateMainNodeID( $allNodes[0]->attribute( 'node_id' ), $objectId, false, $allNodes[0]->attribute( 'parent_node_id' ) );
            }
        }

        // Give other search engines that the default one a chance to reindex
        // when removing locations.
        if ( !eZSearch::getEngine() instanceof eZSearchEngine )
        {
            foreach ( array_keys( $objectIdList ) as $objectId )
                eZContentOperationCollection::registerSearchObject( $objectId );
        }

        $db->commit();

        //call appropriate method from search engine
        eZSearch::removeNodes( $removeNodeIdList );

        $userClassIdList = eZUser::contentClassIDs();
        foreach ( $objectIdList as $objectId => $object )
        {
            eZContentCacheManager::clearObjectViewCacheIfNeeded( $objectId );

            // clear user policy cache if this was a user object
            if ( in_array( $object->attribute( 'contentclass_id' ), $userClassIdList ) )
            {
                eZUser::purgeUserCacheByUserId( $object->attribute( 'id' ) );
            }
        }

        // Triggering content/cache filter for Http cache purge
        ezpEvent::getInstance()->filter( 'content/cache', $removeNodeIdList, array_keys( $objectIdList ) );
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
        $ini = eZINI::instance();
        $aNodes = eZContentObjectTreeNode::fetch( $deleteIDArray );
        if( !is_array( $aNodes ) )
        {
            $aNodes = array( $aNodes );
        }

        $delayedIndexingValue = $ini->variable( 'SearchSettings', 'DelayedIndexing' );
        if ( $delayedIndexingValue === 'enabled' || $delayedIndexingValue === 'classbased' )
        {
            $pendingActionsToDelete = array();
            $classList = $ini->variable( 'SearchSettings', 'DelayedIndexingClassList' ); // Will be used below if DelayedIndexing is classbased
            $assignedNodesByObject = array();
            $nodesToDeleteByObject = array();

            foreach ( $aNodes as $node )
            {
                $object = $node->object();
                $objectID = $object->attribute( 'id' );
                $assignedNodes = $object->attribute( 'assigned_nodes' );
                // Only delete pending action if this is the last object's node that is requested for deletion
                // But $deleteIDArray can also contain all the object's node (mainly if this method is called programmatically)
                // So if this is not the last node, then store its id in a temp array
                // This temp array will then be compared to the whole object's assigned nodes array
                if ( count( $assignedNodes ) > 1 )
                {
                    // $assignedNodesByObject will be used as a referent to check if we want to delete all lasting nodes
                    if ( !isset( $assignedNodesByObject[$objectID] ) )
                    {
                        $assignedNodesByObject[$objectID] = array();

                        foreach ( $assignedNodes as $assignedNode )
                        {
                            $assignedNodesByObject[$objectID][] = $assignedNode->attribute( 'node_id' );
                        }
                    }

                    // Store the node assignment we want to delete
                    // Then compare the array to the referent node assignment array
                    $nodesToDeleteByObject[$objectID][] = $node->attribute( 'node_id' );
                    $diff = array_diff( $assignedNodesByObject[$objectID], $nodesToDeleteByObject[$objectID] );
                    if ( !empty( $diff ) ) // We still have more node assignments for object, pending action is not to be deleted considering this iteration
                    {
                        continue;
                    }
                }

                if ( $delayedIndexingValue !== 'classbased' ||
                     ( is_array( $classList ) && in_array( $object->attribute( 'class_identifier' ), $classList ) ) )
                {
                    $pendingActionsToDelete[] = $objectID;
                }
            }

            if ( !empty( $pendingActionsToDelete ) )
            {
                $filterConds = array( 'param' => array ( $pendingActionsToDelete ) );
                eZPendingActions::removeByAction( 'index_object', $filterConds );
            }
        }

        // Add assigned nodes to the clear cache list
        // This allows to clear assigned nodes separately (e.g. in reverse proxies)
        // as once content is removed, there is no more assigned nodes, and http cache clear is not possible any more.
        // See https://jira.ez.no/browse/EZP-22447
        foreach ( $aNodes as $node )
        {
            if ( is_object( $node ) )
                eZContentCacheManager::addAdditionalNodeIDPerObject( $node->attribute( 'contentobject_id' ), $node->attribute( 'node_id' ) );
        }
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
            {
                eZContentObjectTreeNode::hideSubTree( $curNode );
            }

            // Notify cache system about visibility change
            ezpEvent::getInstance()->notify('content/cache', [
                [(int)$nodeID],
                [(int)$curNode->attribute('contentobject_id')]
            ]);
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
        if ( in_array( $object->attribute( 'contentclass_id' ), $userClassIDArray ) )
        {
            eZUser::purgeUserCacheByUserId( $object->attribute( 'id' ) );
        }

        if ( in_array( $selectedObject->attribute( 'contentclass_id' ), $userClassIDArray ) )
        {
            eZUser::purgeUserCacheByUserId( $selectedObject->attribute( 'id' ) );
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
     * @param bool $updateSearchIndexes
     *
     * @return void
     */
    static public function updateSection( $nodeID, $selectedSectionID, $updateSearchIndexes = true )
    {
        eZContentObjectTreeNode::assignSectionToSubTree( $nodeID, $selectedSectionID, false, $updateSearchIndexes );
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
             eZContentCacheManager::clearContentCacheIfNeeded( $object->attribute( 'id' ) );
        }
        return array( 'status' => true );
    }

    /**
     * Updates the priority of a node
     *
     * @param int $parentNodeID
     * @param array $priorityArray
     * @param array $priorityIDArray
     *
     * @return array An array with operation status, always true
     */
    static public function updatePriority( $parentNodeID, $priorityArray = array(), $priorityIDArray = array() )
    {
        $curNode = eZContentObjectTreeNode::fetch( $parentNodeID );
        if ( $curNode instanceof eZContentObjectTreeNode )
        {
             $objectIDs = array();
             $db = eZDB::instance();
             $db->begin();
             for ( $i = 0, $l = count( $priorityArray ); $i < $l; $i++ )
             {
                 $priority = (int) $priorityArray[$i];
                 $nodeID = (int) $priorityIDArray[$i];
                 $node = eZContentObjectTreeNode::fetch( $nodeID );
                 if ( !$node instanceof eZContentObjectTreeNode )
                 {
                     continue;
                 }

                 $objectIDs[] = $node->attribute( 'contentobject_id' );
                 $db->query( "UPDATE
                                  ezcontentobject_tree
                              SET
                                  priority={$priority}
                              WHERE
                                  node_id={$nodeID} AND parent_node_id={$parentNodeID}" );
             }
             $curNode->updateAndStoreModified();
             $db->commit();
             if ( !eZSearch::getEngine() instanceof eZSearchEngine )
             {
                 eZContentCacheManager::clearContentCacheIfNeeded( $objectIDs );
                 foreach ( $objectIDs as $objectID )
                 {
                     eZContentOperationCollection::registerSearchObject( $objectID );
                 }
             }
        }
        return array( 'status' => true );
    }

    /**
     * Update a node's main assignment
     *
     * @param int $mainAssignmentID
     * @param int $objectID
     * @param int $mainAssignmentParentID
     *
     * @return array An array with operation status, always true
     */
    static public function updateMainAssignment( $mainAssignmentID, $ObjectID, $mainAssignmentParentID )
    {
        eZAudit::writeAudit( 'main-node-update', array( 'Content object ID' => $ObjectID,
                                                        'New Main Node ID' => $mainAssignmentID,
                                                        'New Parent Node ID' => $mainAssignmentParentID,
                                                        'Comment' => 'Updated the main location of the current object: eZContentOperationCollection::updateMainAssignment'
        ) );
        eZContentObjectTreeNode::updateMainNodeID( $mainAssignmentID, $ObjectID, false, $mainAssignmentParentID );
        eZContentCacheManager::clearContentCacheIfNeeded( $ObjectID );
        eZContentOperationCollection::registerSearchObject( $ObjectID );

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
        $change = false;

        if ( $object->isAlwaysAvailable() & $newAlwaysAvailable == false )
        {
            $object->setAlwaysAvailableLanguageID( false );
            $change = true;
        }
        else if ( !$object->isAlwaysAvailable() & $newAlwaysAvailable == true )
        {
            $object->setAlwaysAvailableLanguageID( $object->attribute( 'initial_language_id' ) );
            $change = true;
        }
        if ( $change )
        {
            eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
            if ( !eZSearch::getEngine() instanceof eZSearchEngine )
            {
                eZContentOperationCollection::registerSearchObject( $objectID );
            }
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
                                     __METHOD__ );
            }
        }

        eZContentOperationCollection::registerSearchObject( $objectID );

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
        eZAudit::writeAudit( 'state-assign', array( 'Content object ID' => $object->attribute( 'id' ),
                                                    'Content object name' => $object->attribute( 'name' ),
                                                    'Selected State ID Array' => implode( ', ' , $selectedStateIDList ),
                                                    'Comment' => 'Updated states of the current object: eZContentOperationCollection::updateObjectState()' ) );
        //call appropriate method from search engine
        eZSearch::updateObjectState($objectID, $selectedStateIDList);

        // Triggering content/state/assign event for persistence cache purge
        ezpEvent::getInstance()->notify( 'content/state/assign', array( $objectID, $selectedStateIDList ) );

        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return array( 'status' => true );
    }

    /**
     * Executes the pre-publish trigger for this object, and handles
     * specific return statuses from the workflow
     *
     * @param int $objectID Object ID
     * @param int $version Version number
     *
     * @since 4.2
     */
    static public function executePrePublishTrigger( $objectID, $version )
    {

    }

    /**
     * Creates a RSS/ATOM Feed export for a node
     *
     * @param int $nodeID Node ID
     *
     * @since 4.3
     */
    static public function createFeedForNode( $nodeID )
    {
        $hasExport = eZRSSFunctionCollection::hasExportByNode( $nodeID );
        if ( isset( $hasExport['result'] ) && $hasExport['result'] )
        {
            eZDebug::writeError( 'There is already a rss/atom export feed for this node: ' . $nodeID, __METHOD__ );
            return array( 'status' => false );
        }

        $node = eZContentObjectTreeNode::fetch( $nodeID );
        $currentClassIdentifier = $node->attribute( 'class_identifier' );

        $config = eZINI::instance( 'site.ini' );
        $feedItemClasses = $config->variable( 'RSSSettings', 'DefaultFeedItemClasses' );

        if ( !$feedItemClasses || !isset( $feedItemClasses[ $currentClassIdentifier ] ) )
        {
            eZDebug::writeError( "EnableRSS: content class $currentClassIdentifier is not defined in site.ini[RSSSettings]DefaultFeedItemClasses[<class_id>].", __METHOD__ );
            return array( 'status' => false );
        }

        $object = $node->object();
        $objectID = $object->attribute('id');
        $currentUserID = eZUser::currentUserID();
        $rssExportItems = array();

        $db = eZDB::instance();
        $db->begin();

        $rssExport = eZRSSExport::create( $currentUserID );
        $rssExport->setAttribute( 'access_url', 'rss_feed_' . $nodeID );
        $rssExport->setAttribute( 'node_id', $nodeID );
        $rssExport->setAttribute( 'main_node_only', '1' );
        $rssExport->setAttribute( 'number_of_objects', $config->variable( 'RSSSettings', 'NumberOfObjectsDefault' ) );
        $rssExport->setAttribute( 'rss_version', $config->variable( 'RSSSettings', 'DefaultVersion' ) );
        $rssExport->setAttribute( 'status', eZRSSExport::STATUS_VALID );
        $rssExport->setAttribute( 'title', $object->name() );
        $rssExport->store();

        $rssExportID = $rssExport->attribute( 'id' );

        foreach( explode( ';', $feedItemClasses[$currentClassIdentifier] ) as $classIdentifier )
        {
            $iniSection = 'RSSSettings_' . $classIdentifier;
            if ( $config->hasVariable( $iniSection, 'FeedObjectAttributeMap' ) )
            {
                $feedObjectAttributeMap = $config->variable( $iniSection, 'FeedObjectAttributeMap' );
                $subNodesMap = $config->hasVariable( $iniSection, 'Subnodes' ) ? $config->variable( $iniSection, 'Subnodes' ) : array();

                $rssExportItem = eZRSSExportItem::create( $rssExportID );
                $rssExportItem->setAttribute( 'class_id', eZContentObjectTreeNode::classIDByIdentifier( $classIdentifier ) );
                $rssExportItem->setAttribute( 'title', $feedObjectAttributeMap['title'] );
                if ( isset( $feedObjectAttributeMap['description'] ) )
                    $rssExportItem->setAttribute( 'description', $feedObjectAttributeMap['description'] );

                if ( isset( $feedObjectAttributeMap['category'] ) )
                    $rssExportItem->setAttribute( 'category', $feedObjectAttributeMap['category'] );

                if ( isset( $feedObjectAttributeMap['enclosure'] ) )
                    $rssExportItem->setAttribute( 'enclosure', $feedObjectAttributeMap['enclosure'] );

                $rssExportItem->setAttribute( 'source_node_id', $nodeID );
                $rssExportItem->setAttribute( 'status', eZRSSExport::STATUS_VALID );
                $rssExportItem->setAttribute( 'subnodes', isset( $subNodesMap[$currentClassIdentifier] ) && $subNodesMap[$currentClassIdentifier] === 'true' );
                $rssExportItem->store();
            }
            else
            {
                eZDebug::writeError( "site.ini[$iniSection]Source[] setting is not defined.", __METHOD__ );
            }
        }

        $db->commit();

        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return array( 'status' => true );
    }

    /**
     * Removes a RSS/ATOM Feed export for a node
     *
     * @param int $nodeID Node ID
     *
     * @since 4.3
     */
    static public function removeFeedForNode( $nodeID )
    {
        $rssExport = eZPersistentObject::fetchObject( eZRSSExport::definition(),
                                                null,
                                                array( 'node_id' => $nodeID,
                                                       'status' => eZRSSExport::STATUS_VALID ),
                                                true );
        if ( !$rssExport instanceof eZRSSExport )
        {
            eZDebug::writeError( 'DisableRSS: There is no rss/atom feeds left to delete for this node: '. $nodeID, __METHOD__ );
            return array( 'status' => false );
        }

        $node = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !$node instanceof eZContentObjectTreeNode )
        {
            eZDebug::writeError( 'DisableRSS: Could not fetch node: '. $nodeID, __METHOD__ );
            return array( 'status' => false );
        }

        $objectID = $node->attribute('contentobject_id');
        $rssExport->removeThis();

        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return array( 'status' => true );
    }

    /**
     * Sends the published object/version for publishing to the queue
     * Used by the content/publish operation
     * @param int $objectId
     * @param int $version
     *
     * @return array( status => int )
     * @since 4.5
     */
    public static function sendToPublishingQueue( $objectId, $version )
    {
        $behaviour = ezpContentPublishingBehaviour::getBehaviour();
        if ( $behaviour->disableAsynchronousPublishing )
            $asyncEnabled = false;
        else
            $asyncEnabled = ( eZINI::instance( 'content.ini' )->variable( 'PublishingSettings', 'AsynchronousPublishing' ) == 'enabled' );

        $accepted = true;

        if ( $asyncEnabled === true )
        {
            // Filter handlers
            $ini = eZINI::instance( 'content.ini' );
            $filterHandlerClasses = $ini->variable( 'PublishingSettings', 'AsynchronousPublishingFilters' );

            if ( count( $filterHandlerClasses ) )
            {
                $versionObject = eZContentObjectVersion::fetchVersion( $version, $objectId );
                foreach( $filterHandlerClasses as $filterHandlerClass )
                {
                    if ( !class_exists( $filterHandlerClass ) )
                    {
                        eZDebug::writeError( "Unknown asynchronous publishing filter handler class '$filterHandlerClass'", __METHOD__  );
                        continue;
                    }

                    $handler = new $filterHandlerClass( $versionObject );
                    if ( !( $handler instanceof ezpAsynchronousPublishingFilterInterface ) )
                    {
                        eZDebug::writeError( "Asynchronous publishing filter handler class '$filterHandlerClass' does not implement ezpAsynchronousPublishingFilterInterface", __METHOD__  );
                        continue;
                    }

                    $accepted = $handler->accept();

                    if ( !$accepted )
                    {
                        eZDebugSetting::writeDebug( "Object #{$objectId}/{$version} was excluded from asynchronous publishing by $filterHandlerClass", __METHOD__ );
                        break;
                    }
                }
            }
            unset( $filterHandlerClasses, $handler );
        }

        if ( $asyncEnabled && $accepted )
        {
            // if the object is already in the process queue, we move ahead
            // this test should NOT be necessary since http://issues.ez.no/17840 was fixed
            if ( ezpContentPublishingQueue::isQueued( $objectId, $version ) )
            {
                return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
            }
            // the object isn't in the process queue, this means this is the first time we execute this method
            // the object must be queued
            else
            {
                ezpContentPublishingQueue::add( $objectId, $version );
                return array( 'status' => eZModuleOperationInfo::STATUS_HALTED, 'redirect_url' => "content/queued/{$objectId}/{$version}" );
            }
        }
        else
        {
            return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
        }
    }
}
?>

<?php
//
// Definition of eZContentOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.7.x
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

/*! \file ezcontentoperationcollection.php
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

    function readNode( $nodeID )
    {

    }

    function readObject( $nodeID, $userID, $languageCode )
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
        if ( !$version )
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
     \static
     Generates the related viewcaches (PreGeneration) for the content object.
     It will only do this if [ContentSettings]/PreViewCache in site.ini is enabled.

     \param $objectID The ID of the content object to generate caches for.
    */
    function generateObjectViewCache( $objectID )
    {
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
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
    function clearObjectViewCache( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID, $versionNum, $additionalNodeList );
    }


    /*!
    */
    function publishNode( $parentNodeID, $objectID, $versionNum, $mainNodeID )
    {
        $object         =& eZContentObject::fetch( $objectID );
        $version        =& $object->version( $versionNum );
        $nodeAssignment = eZNodeAssignment::fetch( $objectID, $versionNum, $parentNodeID );

        $object->setAttribute( 'current_version', $versionNum );
        if ( $object->attribute( 'published' ) == 0 )
        {
            $object->setAttribute( 'published', mktime() );
        }

        $db =& eZDB::instance();
        $db->begin();

        $object->setAttribute( 'modified', mktime() );
        $object->store();

        $class      = eZContentClass::fetch( $object->attribute( 'contentclass_id' ) );
        $objectName =  $class->contentObjectName( $object );

        /* Check if current class is the user class, and if so, clean up the
         * user-policy cache */
        include_once( "lib/ezutils/classes/ezini.php" );
        $ini =& eZINI::instance();
        $userClassID = $ini->variable( "UserSettings", "UserClassID" );
        if ( $object->attribute( 'contentclass_id' ) == $userClassID )
        {
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            eZUser::cleanupCache();
        }

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
        if ( $existingNode  == null )
        {
            if ( $fromNodeID == 0 || $fromNodeID == -1)
            {
                $parentNode = eZContentObjectTreeNode::fetch( $nodeID );

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

                include_once( 'kernel/classes/ezcontentcachemanager.php' );
                eZContentCacheManager::clearContentCacheIfNeeded( $objectID, $versionNum, $additionalNodeIDList );

                $originalNode = eZContentObjectTreeNode::fetchNode( $originalObjectID, $fromNodeID );
                if ( $originalNode->attribute( 'main_node_id' ) == $originalNode->attribute( 'node_id' ) )
                {
                    $updateSectionID = true;
                }
                $originalNode->move( $parentNodeID );
                $existingNode = eZContentObjectTreeNode::fetchNode( $originalObjectID, $parentNodeID );
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
            eZContentOperationCollection::updateSectionID( $objectID, $versionNum );
        }
        $db->commit();

        // Clear cache after publish
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearTemplateBlockCacheIfNeeded( $object->attribute( 'id' ) );

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
            // we should not update section id for toplevel nodes
            if ( $newMainAssignment->attribute( 'parent_node' ) != 1 )
            {
                $newParentObject =& $newMainAssignment->getParentObject();
                if ( !$newParentObject )
                {
                    return array( 'status' => EZ_MODULE_OPERATION_CANCELED );
                }
                $parentNodeSectionID = $newParentObject->attribute( 'section_id' );
                $object->setAttribute( 'section_id', $parentNodeSectionID );
                $object->store();
            }

            return;
        }

        $newMainAssignmentList = eZNodeAssignment::fetchForObject( $objectID, $versionNum, 1 );
        $newMainAssignment = ( count( $newMainAssignmentList ) ) ? array_pop( $newMainAssignmentList ) : null;

        $currentVersion =& $object->attribute( 'current' );
        $nodeAssigmentTmpArray = eZNodeAssignment::fetchForObject( $objectID, $object->attribute( 'current_version' ), 1 );
        list( $oldMainAssignment ) = isset( $nodeAssigmentTmpArray[0] ) ? $nodeAssigmentTmpArray : null;

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

                        $db =& eZDB::instance();
                        $db->begin();
                        $object->store();
                        $mainNodeID = $object->attribute( 'main_node_id' );
                        if ( $mainNodeID > 0 )
                            eZContentObjectTreeNode::assignSectionToSubTree( $mainNodeID,
                                                                             $newSectionID,
                                                                             $oldSectionID );
                        $db->commit();
                    }
                }
            }
        }
    }

    function removeOldNodes( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );
        $moveToTrash = true;

        $assignedExistingNodes =& $object->attribute( 'assigned_nodes' );

        $curentVersionNodeAssignments = $version->attribute( 'node_assignments' );
        $versionParentIDList = array();
        foreach ( array_keys( $curentVersionNodeAssignments ) as $key )
        {
            $nodeAssignment =& $curentVersionNodeAssignments[$key];
            $versionParentIDList[] = $nodeAssignment->attribute( 'parent_node' );
        }

        $db =& eZDB::instance();
        $db->begin();
        foreach ( array_keys( $assignedExistingNodes )  as $key )
        {
            $node =& $assignedExistingNodes[$key];
            if ( $node->attribute( 'contentobject_version' ) < $version->attribute( 'version' ) &&
                 !in_array( $node->attribute( 'parent_node_id' ), $versionParentIDList ) )
            {
                eZContentObjectTreeNode::removeSubtrees( array( $node->attribute( 'node_id' ) ), $moveToTrash );
            }
        }
        $db->commit();
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
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
            $db->query( 'INSERT INTO ezpending_actions( action, param ) VALUES ( \'index_object\', '. (int)$objectID. ' )' );
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

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function createNotificationEvent( $objectID, $versionNum )
    {
        include_once( 'kernel/classes/notification/eznotificationevent.php' );
        $event = eZNotificationEvent::create( 'ezpublish', array( 'object' => $objectID,
                                                                   'version' => $versionNum ) );
        $event->store();
    }

    /*!
      Start global transaction.
     */
    function beginPublish()
    {
        $db =& eZDB::instance();
        $db->begin();
    }

    /*!
     Stop (commit) global transaction.
     */
    function endPublish()
    {
        $db =& eZDB::instance();
        $db->commit();
    }

}

?>

<?php
//
// Created on: <23-Mar-2005 23:23:23 rl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

$Module = $Params['Module'];
$NodeID = $Params['NodeID'];

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'BrowseCancelButton' ) )
{
    if ( $http->hasPostVariable( 'BrowseCancelURI' ) )
        return $Module->redirectTo( $http->postVariable( 'BrowseCancelURI' ) );
}

if ( $NodeID === null ) // NodeID is returned after browsing
{
    $NodeID = $http->postVariable( 'NodeID' );
}

$srcNode = eZContentObjectTreeNode::fetch( $NodeID );

if ( $srcNode === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$srcNode->attribute( 'can_read' ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $parentNodeID = $srcNode->attribute( 'parent_node_id' );
    return $Module->redirectToView( 'view', array( 'full', $parentNodeID ) );
}

///// functions START =============================================================================
function copyPublishContentObject( $sourceObject,
                                   $sourceSubtreeNodeIDList,
                                   &$syncNodeIDListSrc, &$syncNodeIDListNew,
                                   &$syncObjectIDListSrc, &$syncObjectIDListNew,
                                   $objectIDBlackList, &$nodeIDBlackList,
                                   &$notifications,
                                   $allVersions = false, $keepCreator = false, $keepTime = false )
{
    $sourceObjectID = $sourceObject->attribute( 'id' );

    $key = array_search( $sourceObjectID, $syncObjectIDListSrc );
    if ( $key !== false )
    {
        eZDebug::writeDebug( "Object (ID = $sourceObjectID) has been already copied.",
                             "Subtree copy: copyPublishContentObject()" );
        return 1; // object already copied
    }

    $srcNodeList = $sourceObject->attribute( 'assigned_nodes' );

    // if we already failed to copy that contentobject, then just skip it:
    if ( in_array( $sourceObjectID, $objectIDBlackList ) )
        return 0;
    // if we already failed to copy that node, then just skip it:
    //if ( in_array( $sourceNodeID, $nodeIDBlackList ) )
    //    return 0;

    // if cannot read contentobject then remember it and all its nodes (nodes
    // which are inside subtree being copied) in black list, and skip current node:
    if ( !$sourceObject->attribute( 'can_read' ) )
    {
        $objectIDBlackList[] = $sourceObjectID;
        $notifications['Warnings'][] = ezi18n( 'kernel/content/copysubtree',
                                               "Object (ID = %1) was not copied: you do not have permission to read the object.",
                                               null, array( $sourceObjectID ) );

        $srcNodeList = $sourceObject->attribute( 'assigned_nodes' );
        foreach( $srcNodeList as $srcNode )
        {
            $srcNodeID = $srcNode->attribute( 'node_id' );
            $sourceParentNodeID = $srcNode->attribute( 'parent_node_id' );

            $key = array_search( $sourceParentNodeID, $sourceSubtreeNodeIDList );
            if ( $key !== false )
            {
                $nodeIDBlackList[] = $srcNodeID;
                $notifications['Warnings'][] = ezi18n( 'kernel/content/copysubtree',
                                                       "Node (ID = %1) was not copied: you do not have permission to read object (ID = %2).",
                                                       null, array( $srcNodeID, $sourceObjectID ) );
            }
        }
        return 0;
    }

    // check if all possible parent nodes for given contentobject are already published:
    $isReadyToPublish = false;
    foreach ( $srcNodeList as $srcNode )
    {
        $srcNodeID = $srcNode->attribute( 'node_id' );

        if ( in_array( $srcNodeID, $nodeIDBlackList ) )
            continue;

        $srcParentNodeID = $srcNode->attribute( 'parent_node_id' );

        // if parent node for this node is outside
        // of subtree being copied, then skip this node:
        $key = array_search( $srcParentNodeID, $sourceSubtreeNodeIDList );
        if ( $key === false )
            continue;

        // if parent node for this node wasn't copied yet and is in black list
        // then add that node in black list and just skip it:
        $key = array_search( $srcParentNodeID, $nodeIDBlackList );
        if ( $key !== false )
        {
            $nodeIDBlackList[] = $srcNodeID;
            $notifications['Warnings'][] = ezi18n( 'kernel/content/copysubtree',
                                                   "Node (ID = %1) was not copied: parent node (ID = %2) was not copied.",
                                                   null, array( $srcNodeID, $srcParentNodeID ) );
            continue;
        }

        $key = array_search( $srcParentNodeID, $syncNodeIDListSrc );
        if ( $key === false )
        {
            // if parent node is not copied yet and not in black list,
            // then just skip sourceObject from copying for next time
            eZDebug::writeDebug( "Parent node (ID = $srcParentNodeID) for contentobject (ID = $sourceObjectID) is not published yet.",
                                 "Subtree copy: copyPublishContentObject()" );
            return 2;
        }
        else
        {
            $newParentNodeID = $syncNodeIDListNew[ $key ];
            $newParentNode = eZContentObjectTreeNode::fetch( $newParentNodeID );
            if ( $newParentNode === null )
            {
                eZDebug::writeError( "Cannot fetch one of parent nodes. Error are somewhere above",
                                     "Subtree copy error: copyPublishContentObject()" );
                return 3;
            }

            if ( $newParentNode->checkAccess( 'create', $sourceObject->attribute( 'contentclass_id' ) ) != 1 )
            {
                $nodeIDBlackList[] = $srcNodeID;
                $notifications['Warnings'][] = ezi18n( 'kernel/content/copysubtree',
                                                       "Node (ID = %1) was not copied: you do not have permission to create.",
                                                       null, array( $srcNodeID ) );

                continue;
            }
            else
                $isReadyToPublish = true;
        }
    }

    // if all nodes of sourceObject were skiped as black list entry or
    // as outside of subtree being copied, then sourceObject cannot be
    // copied and published in any new location. So insert sourceObject
    // in a black list and skip it.
    if ( $isReadyToPublish == false )
    {
        $objectIDBlackList[] = $sourceObjectID;
        $notifications['Warnings'][] = ezi18n( 'kernel/content/copysubtree',
                                               "Object (ID = %1) was not copied: no one nodes of object was not copied.",
                                               null, array( $sourceObjectID) );
        return 0;
    }

    // make copy of source object
    $newObject             = $sourceObject->copy( $allVersions ); // insert source and new object's ids in $syncObjectIDList
    // We should reset section that will be updated in updateSectionID().
    // If sectionID is 0 than the object has been newly created
    $newObject->setAttribute( 'section_id', 0 );
    $newObject->store();

    $syncObjectIDListSrc[] = $sourceObjectID;
    $syncObjectIDListNew[] = $newObject->attribute( 'id' );

    $curVersion        = $newObject->attribute( 'current_version' );
    $curVersionObject  = $newObject->attribute( 'current' );

    $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );

    // copy nodeassigments:
    $assignmentsForRemoving = array();
    $foundMainAssignment = false;
    foreach ( $newObjAssignments as $assignment )
    {
        $parentNodeID = $assignment->attribute( 'parent_node' );

        // if assigment is outside of subtree being copied then do not copy this assigment
        $key1 = array_search( $parentNodeID, $sourceSubtreeNodeIDList );
        $key2 = array_search( $parentNodeID, $nodeIDBlackList );
        if ( $key1 === false or $key2 !== false )
        {
            $assignmentsForRemoving[] = $assignment->attribute( 'id' );
            continue;
        }

        $key = array_search( $parentNodeID, $syncNodeIDListSrc );
        if ( $key === false )
        {
            eZDebug::writeError( "Cannot publish contentobject (ID=$sourceObjectID). Parent is not published yet.",
                                 "Subtree Copy error: copyPublishContentObject()" );
            return 4;
        }

        if ( $assignment->attribute( 'is_main' ) )
            $foundMainAssignment = true;

        $newParentNodeID = $syncNodeIDListNew[ $key ];
        $assignment->setAttribute( 'parent_node', $newParentNodeID );
        $assignment->store();
    }
    // remove assigments which are outside of subtree being copied:
    eZNodeAssignment::purgeByID( $assignmentsForRemoving );

    // if main nodeassigment was not copied then set as main first nodeassigment
    if ( $foundMainAssignment == false )
    {
        $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );
        // We need to check if it has any assignments before changing the data.
        if ( isset( $newObjAssignments[0] ) )
        {
            $newObjAssignments[0]->setAttribute( 'is_main', 1 );
            $newObjAssignments[0]->store();
        }
    }

    // publish the newly created object
    $result = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObject->attribute( 'id' ),
                                                                        'version'   => $curVersion ) );
    // Refetch the object data since it might change in the database.
    $newObjectID = $newObject->attribute( 'id' );
    $newObject = eZContentObject::fetch( $newObjectID );
    $newNodeList = $newObject->attribute( 'assigned_nodes' );
    if ( count($newNodeList) == 0 )
    {
        $newObject->purge();
        eZDebug::writeError( "Cannot publish contentobject.",
                             "Subtree Copy Error!" );
        $notifications['Warnings'][] = ezi18n( 'kernel/content/copysubtree',
                                               "Cannot publish object (ID = %1).",
                                               null, array( $sourceObjectID) );
        return -1;
    }

    $objAssignments = $curVersionObject->attribute( 'node_assignments' );
    foreach ( $newNodeList as $newNode )
    {
        $newParentNode = $newNode->fetchParent();
        $newParentNodeID = $newParentNode->attribute( 'node_id' );

        $keyA = array_search( $newParentNodeID, $syncNodeIDListNew );
        if ( $keyA === false )
        {
            eZDebug::writeError( "Algoritm ERROR! Cannot find new parent node ID in new ID's list",
                                 "Subtree Copy Error!" );
            return -2;
        }

        $srcParentNodeID = $syncNodeIDListSrc[ $keyA ];

        // Update attributes of node
        $bSrcParentFound = false;
        foreach ( $srcNodeList as $srcNode )
        {
            if ( $srcNode->attribute( 'parent_node_id' ) == $srcParentNodeID )
            {
                $newNode->setAttribute( 'priority', $srcNode->attribute( 'priority' ) );
                $newNode->setAttribute( 'is_hidden', $srcNode->attribute( 'is_hidden' ) );
                // Update node visibility
                if ( $newParentNode->attribute( 'is_invisible' ) or $newParentNode->attribute( 'is_hidden' ) )
                    $newNode->setAttribute( 'is_invisible', 1 );
                else
                    $newNode->setAttribute( 'is_invisible', $srcNode->attribute( 'is_invisible' ) );

                $syncNodeIDListSrc[] = $srcNode->attribute( 'node_id' );
                $syncNodeIDListNew[] = $newNode->attribute( 'node_id' );
                $bSrcParentFound = true;
                break;
            }
        }
        if ( $bSrcParentFound == false )
        {
            eZDebug::writeError( "Cannot find source parent node in list of nodes already copied.",
                                 "Subtree Copy Error!" );
        }
        // Create unique remote_id
        $newRemoteID = md5( (string)mt_rand() . (string)time() );
        $oldRemoteID = $newNode->attribute( 'remote_id' );
        $newNode->setAttribute( 'remote_id', $newRemoteID );
        // Change parent_remote_id for object assignments
        foreach ( $objAssignments as $assignment )
        {
            if ( $assignment->attribute( 'parent_remote_id' ) == $oldRemoteID )
            {
                 $assignment->setAttribute( 'parent_remote_id', $newRemoteID );
                 $assignment->store();
            }
        }
        $newNode->store();
    }

    // if $keepCreator == true then keep owner of contentobject being
    // copied and creator of its published version Unchaged
    $isModified = false;
    if ( $keepTime )
    {
        $srcPublished = $sourceObject->attribute( 'published' );
        $newObject->setAttribute( 'published', $srcPublished );
        $srcModified  = $sourceObject->attribute( 'modified' );
        $newObject->setAttribute( 'modified', $srcModified );
        $isModified = true;
    }
    if ( $keepCreator )
    {
        $srcOwnerID = $sourceObject->attribute( 'owner_id' );
        $newObject->setAttribute( 'owner_id', $srcOwnerID );
        $isModified = true;
    }
    if ( $isModified )
        $newObject->store();

    if ( $allVersions )
    {   // copy time of creation and midification and creator id for
        // all versions of content object being copied.
        $srcVersionsList = $sourceObject->versions();

        foreach ( $srcVersionsList as $srcVersionObject )
        {
            $newVersionObject = $newObject->version( $srcVersionObject->attribute( 'version' ) );
            if ( !is_object( $newVersionObject ) )
                continue;

            $isModified = false;
            if ( $keepTime )
            {
                $srcVersionCreated  = $srcVersionObject->attribute( 'created' );
                $newVersionObject->setAttribute( 'created', $srcVersionCreated );
                $srcVersionModified = $srcVersionObject->attribute( 'modified' );
                $newVersionObject->setAttribute( 'modified', $srcVersionModified );
                $isModified = true;
            }
            if ( $keepCreator )
            {
                $srcVersionCreatorID = $srcVersionObject->attribute( 'creator_id' );
                $newVersionObject->setAttribute( 'creator_id', $srcVersionCreatorID );

                $isModified = true;
            }
            if ( $isModified )
                $newVersionObject->store();
        }
    }
    else // if not all versions copied
    {
        $srcVersionObject = $sourceObject->attribute( 'current' );
        $newVersionObject = $newObject->attribute( 'current' );

        $isModified = false;
        if ( $keepTime )
        {
            $srcVersionCreated  = $srcVersionObject->attribute( 'created' );
            $newVersionObject->setAttribute( 'created', $srcVersionCreated );
            $srcVersionModified = $srcVersionObject->attribute( 'modified' );
            $newVersionObject->setAttribute( 'modified', $srcVersionModified );
            $isModified = true;
        }
        if ( $keepCreator )
        {
            $srcVersionCreatorID = $srcVersionObject->attribute( 'creator_id' );
            $newVersionObject->setAttribute( 'creator_id', $srcVersionCreatorID );
            $isModified = true;
        }
        if ( $isModified )
            $newVersionObject->store();
    }

    return 0; // source object was copied successfully.

} // function copyPublishContentObject END


function copySubtree( $srcNodeID, $dstNodeID, &$notifications, $allVersions, $keepCreator, $keepTime )
{
    // 1. Copy subtree and form the arrays of accordance of the old and new nodes and content objects.

    $sourceSubTreeMainNode = ( $srcNodeID ) ? eZContentObjectTreeNode::fetch( $srcNodeID ) : false;
    $destinationNode = ( $dstNodeID ) ? eZContentObjectTreeNode::fetch( $dstNodeID ) : false;

    if ( !$sourceSubTreeMainNode )
    {
        eZDebug::writeError( "Cannot get subtree main node (nodeID = $srcNodeID).",
                             "Subtree copy Error!" );
        $notifications['Errors'][] = ezi18n( 'kernel/content/copysubtree',
                                            "Fatal error: cannot get subtree main node (ID = %1).",
                                            null, array( $srcNodeID ) );
        return $notifications;
    }
    if ( !$destinationNode )
    {
        eZDebug::writeError( "Cannot get destination node (nodeID = $dstNodeID).",
                             "Subtree copy Error!" );
        $notifications['Errors'][] = ezi18n( 'kernel/content/copysubtree',
                                            "Fatal error: cannot get destination node (ID = %1).",
                                            null, array( $dstNodeID ) );
        return $notifications;
    }

    $sourceNodeList    = array();

    $syncNodeIDListSrc = array(); // arrays for synchronizing between source and new IDs of nodes
    $syncNodeIDListNew = array();
    $syncObjectIDListSrc = array(); // arrays for synchronizing between source and new IDs of contentobjects
    $syncObjectIDListNew = array();

    $sourceSubTreeMainNodeID = $sourceSubTreeMainNode->attribute( 'node_id' );
    $sourceNodeList[] = $sourceSubTreeMainNode;

    $syncNodeIDListSrc[] = $sourceSubTreeMainNode->attribute( 'parent_node_id' );
    $syncNodeIDListNew[] = (int) $dstNodeID;

    $nodeIDBlackList = array(); // array of nodes which are unable to copy
    $objectIDBlackList = array(); // array of contentobjects which are unable to copy in any location inside new subtree

    $sourceNodeList = array_merge( $sourceNodeList,
                                   eZContentObjectTreeNode::subTreeByNodeID( array( 'Limitation' => array() ), $sourceSubTreeMainNodeID ) );
    $countNodeList = count( $sourceNodeList );

    $notifications['Notifications'][] = ezi18n( 'kernel/content/copysubtree',
                                                "Number of nodes of source subtree - %1",
                                                null, array( $countNodeList ) );

    // Prepare list of source node IDs. We will need it in the future
    // for checking node is inside or outside of the subtree being copied.
    $sourceNodeIDList = array();
    foreach ( $sourceNodeList as $sourceNode )
        $sourceNodeIDList[] = $sourceNode->attribute( 'node_id' );

    eZDebug::writeDebug( "Source NodeID = $srcNodeID, destination NodeID = $dstNodeID",
                         "Subtree copy: START!" );

    // 1. copying and publishing source subtree
    $k = 0;
    while ( count( $sourceNodeList ) > 0 )
    {
        if ( $k > $countNodeList )
        {
            eZDebug::writeError( "Too many loops while copying nodes.",
                                 "Subtree Copy Error!" );
            break;
        }

        for ( $i = 0; $i < count( $sourceNodeList ); $i)
        {
            $sourceNodeID = $sourceNodeList[ $i ]->attribute( 'node_id' );

            // if node was alreaty copied
            if ( in_array( $sourceNodeID, $syncNodeIDListSrc ) )
            {
                array_splice( $sourceNodeList, $i, 1 );
                continue;
            }

            //////////// check permissions START
            // if node is already in black list, then skip current node:
            if ( in_array( $sourceNodeID, $nodeIDBlackList ) )
            {
                array_splice( $sourceNodeList, $i, 1 );
                continue;
            }

            $sourceObject = $sourceNodeList[ $i ]->object();

            $srcSubtreeNodeIDlist = ($sourceNodeID == $sourceSubTreeMainNodeID) ? $syncNodeIDListSrc : $sourceNodeIDList;
            $copyResult = copyPublishContentObject( $sourceObject,
                                                    $srcSubtreeNodeIDlist,
                                                    $syncNodeIDListSrc, $syncNodeIDListNew,
                                                    $syncObjectIDListSrc, $syncObjectIDListNew,
                                                    $objectIDBlackList, $nodeIDBlackList,
                                                    $notifications,
                                                    $allVersions, $keepCreator, $keepTime );
            if ( $copyResult === 0 )
            {   // if copying successful then remove $sourceNode from $sourceNodeList
                array_splice( $sourceNodeList, $i, 1 );
            }
            else
                $i++;
        }
        $k++;
    }

    array_shift( $syncNodeIDListSrc );
    array_shift( $syncNodeIDListNew );


    $countNewNodes = count( $syncNodeIDListNew );
    $countNewObjects = count( $syncObjectIDListNew );

    $key = array_search( $sourceSubTreeMainNodeID, $syncNodeIDListSrc );
    if ( $key === false )
    {
        eZDebug::writeDebug( "Root node of given subtree was not copied.",
                             "Subtree copy:" );
        $notifications['Notifications'][] = ezi18n( 'kernel/content/copysubtree',
                                                    "Subtree was not copied." );
        return $notifications;
    }

    $notifications['Notifications'][] = ezi18n( 'kernel/content/copysubtree',
                                                "Number of copied nodes - %1",
                                                null, array( $countNewNodes ) );
    $notifications['Notifications'][] = ezi18n( 'kernel/content/copysubtree',
                                                "Number of copied contentobjects - %1",
                                                null, array( $countNewObjects ) );

    eZDebug::writeDebug( count( $syncNodeIDListNew ), "Number of copied nodes: " );
    eZDebug::writeDebug( count( $syncObjectIDListNew ), "Number of copied contentobjects: " );

    eZDebug::writeDebug( $objectIDBlackList, "Copy subtree: Not copied object IDs list:" );
    eZDebug::writeDebug( $nodeIDBlackList, "Copy subtree: Not copied node IDs list:" );

    // 2. fetch all new subtree

    $newSubTreeMainNodeID = $syncNodeIDListSrc[ $key ];
    $newSubTreeMainNode   = eZContentObjectTreeNode::fetch( $newSubTreeMainNodeID );

    $newNodeList[] = $newSubTreeMainNode;
    $newNodeList = $sourceNodeList = array_merge( $newNodeList,
                                                  eZContentObjectTreeNode::subTreeByNodeID( false, $newSubTreeMainNodeID ) );

    // 3. fix local links (in ezcontentobject_link)
    eZDebug::writeDebug( "Fixing global and local links...",
                         "Subtree copy:" );

    $db = eZDB::instance();
    if ( !$db )
    {
        eZDebug::writeError( "Cannot create instance of eZDB for fixing local links (related objects).",
                             "Subtree Copy Error!" );
        $notifications['Errors'][] = ezi18n( 'kernel/content/copysubtree',
                                             "Cannot create instance of eZDB to fix local links (related objects)." );
        return $notifications;
    }

    $idListINString = $db->generateSQLINStatement( $syncObjectIDListNew, 'from_contentobject_id', false, false, 'int' );
    $relatedRecordsList = $db->arrayQuery( "SELECT * FROM ezcontentobject_link WHERE $idListINString" );

    foreach ( $relatedRecordsList as $relatedEntry )
    {
        $kindex = array_search( $relatedEntry[ 'to_contentobject_id' ], $syncObjectIDListSrc );
        if ( $kindex !== false )
        {
            $newToContentObjectID = (int) $syncObjectIDListNew[ $kindex ];
            $linkID = (int) $relatedEntry[ 'id' ];
            $db->query( "UPDATE ezcontentobject_link SET  to_contentobject_id=$newToContentObjectID WHERE id=$linkID" );
        }
    }

    // 4. duplicating of global links for new contentobjects (in ezurl_object_link) are automatic during copy of contentobject.
    //    it was fixed as bug patch.

    // 5. fixing node_ids and object_ids in ezxmltext attributes of copied objects
    $conditions = array( 'contentobject_id' => '', // 5
                         'data_type_string' => 'ezxmltext' );

    foreach ( $syncObjectIDListNew as $contentObjectID )
    {
        $conditions[ 'contentobject_id' ] = $contentObjectID;
        $attributeList = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(), null, $conditions );
        if ( count( $attributeList ) == 0 )
        {
            continue;
        }
        foreach ( $attributeList as $xmlAttribute )
        {
            $xmlText = $xmlAttribute->attribute( 'data_text' );
            $xmlTextLen = strlen ( $xmlText );
            $isTextModified = false;
            $curPos = 0;

            while ( $curPos < $xmlTextLen )
            {
                $literalTagBeginPos = strpos( $xmlText, "<literal", $curPos );
                if ( $literalTagBeginPos )
                {
                    $literalTagEndPos = strpos( $xmlText, "</literal>", $literalTagBeginPos );
                    if ( $literalTagEndPos === false )
                        break;
                    $curPos = $literalTagEndPos + 9;
                }

                if ( ($tagBeginPos = strpos( $xmlText, "<link", $curPos )) !== false or
                     ($tagBeginPos = strpos( $xmlText, "<a"   , $curPos )) !== false or
                     ($tagBeginPos = strpos( $xmlText, "<embed",$curPos )) !== false )
                {
                    $tagEndPos = strpos( $xmlText, ">", $tagBeginPos + 1 );
                    if ( $tagEndPos === false )
                        break;

                    $tagText = substr( $xmlText, $tagBeginPos, $tagEndPos - $tagBeginPos );

                    if ( ($nodeIDAttributePos = strpos( $tagText, " node_id=\"" )) !== false )
                    {
                        $idNumberPos = $nodeIDAttributePos + 10;
                        $quoteEndPos = strpos( $tagText, "\"", $idNumberPos );

                        if ( $quoteEndPos !== false )
                        {
                            $idNumber = substr( $tagText, $idNumberPos, $quoteEndPos - $idNumberPos );
                            $key = array_search( (int) $idNumber, $syncNodeIDListSrc );

                            if ( $key !== false )
                            {
                                $tagText = substr_replace( $tagText, (string) $syncNodeIDListNew[ $key ], $idNumberPos, $quoteEndPos - $idNumberPos );
                                $xmlText = substr_replace( $xmlText, $tagText, $tagBeginPos, $tagEndPos - $tagBeginPos );
                                $isTextModified = true;
                            }
                        }
                    }
                    else if ( ($objectIDAttributePos = strpos( $tagText, " object_id=\"" )) !== false )
                    {
                        $idNumberPos = $objectIDAttributePos + 12;
                        $quoteEndPos = strpos( $tagText, "\"", $idNumberPos );

                        if ( $quoteEndPos !== false )
                        {
                            $idNumber = substr( $tagText, $idNumberPos, $quoteEndPos - $idNumberPos );
                            $key = array_search( (int) $idNumber, $syncObjectIDListSrc );
                            if ( $key !== false )
                            {
                                $tagText = substr_replace( $tagText, (string) $syncObjectIDListNew[ $key ], $idNumberPos, $quoteEndPos - $idNumberPos );
                                $xmlText = substr_replace( $xmlText, $tagText, $tagBeginPos, $tagEndPos - $tagBeginPos );
                                $isTextModified = true;
                            }
                        }
                    }
                    $curPos = $tagEndPos;
                }
                else if ( ($tagBeginPos = strpos( $xmlText, "<object", $curPos )) !== false )
                {
                    $tagEndPos = strpos( $xmlText, ">", $tagBeginPos + 1 );
                    if ( !$tagEndPos )
                        break;

                    $tagText = substr( $xmlText, $tagBeginPos, $tagEndPos - $tagBeginPos );

                    if ( ($idAttributePos = strpos( $tagText, " id=\"" )) !== false )
                    {
                        $idNumberPos = $idAttributePos + 5;
                        $quoteEndPos = strpos( $tagText, "\"", $idNumberPos );

                        if ( $quoteEndPos !== false )
                        {
                            $idNumber = substr( $tagText, $idNumberPos, $quoteEndPos - $idNumberPos );
                            $key = array_search( (int) $idNumber, $syncObjectIDListSrc );
                            if ( $key !== false )
                            {
                                $tagText = substr_replace( $tagText, (string) $syncObjectIDListNew[ $key ], $idNumberPos, $quoteEndPos - $idNumberPos );
                                $xmlText = substr_replace( $xmlText, $tagText, $tagBeginPos, $tagEndPos - $tagBeginPos );
                                $isTextModified = true;
                            }
                        }
                    }
                    $curPos = $tagEndPos;
                }
                else
                    break;

            } // while END

            if ( $isTextModified )
            {
                $xmlAttribute->setAttribute( 'data_text', $xmlText );
                $xmlAttribute->store();
            }
        } // foreach END
    }

    // 6. fixing datatype ezobjectrelationlist
    $conditions = array( 'contentobject_id' => '',
                         'data_type_string' => 'ezobjectrelationlist' );
    foreach ( $syncObjectIDListNew as $contentObjectID )
    {
        $conditions[ 'contentobject_id' ] = $contentObjectID;
        $attributeList = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(), null, $conditions );
        if ( count( $attributeList ) == 0 )
        {
            continue;
        }
        foreach ( $attributeList as $relationListAttribute )
        {
            $relationsXmlText = $relationListAttribute->attribute( 'data_text' );
            $relationsDom = eZObjectRelationListType::parseXML( $relationsXmlText );
            $relationItems = $relationsDom->getElementsByTagName( 'relation-item' );
            $isRelationModified = false;

            foreach ( $relationItems as $relationItem )
            {
                $originalObjectID = $relationItem->getAttribute( 'contentobject-id' );

                $key = array_search( $originalObjectID, $syncObjectIDListSrc );
                if ( $key !== false )
                {
                    $newObjectID = $syncObjectIDListNew[ $key ];
                    $relationItem->setAttribute( 'contentobject-id', $newObjectID );
                    $isRelationModified = true;
                }

                $originalNodeID = $relationItem->getAttribute( 'node-id' );
                if ( $originalNodeID )
                {
                    $key = array_search( $originalNodeID, $syncNodeIDListSrc );
                    if ( $key !== false )
                    {
                        $newNodeID = $syncNodeIDListNew[ $key ];
                        $relationItem->setAttribute( 'node-id', $newNodeID );

                        $newNode = eZContentObjectTreeNode::fetch( $newNodeID );
                        $newParentNodeID = $newNode->attribute( 'parent_node_id' );
                        $relationItem->setAttribute( 'parent-node-id', $newParentNodeID );
                        $isRelationModified = true;
                    }
                }
            }
            if ( $isRelationModified )
            {
                $attributeID = $relationListAttribute->attribute( 'id' );
                $attributeVersion = $relationListAttribute->attribute( 'version' );
                $changedDomString =$db->escapeString( eZObjectRelationListType::domString( $relationsDom ) );
                $db->query( "UPDATE ezcontentobject_attribute SET data_text='$changedDomString'
                             WHERE id=$attributeID AND version=$attributeVersion" );
            }
        }
    }

    eZDebug::writeDebug( "Successfuly DONE.",
                         "Copy subtree:" );

    $notifications['Notifications'][] = ezi18n( 'kernel/content/copysubtree',
                                                "Successfully DONE." );
    return $notifications;
} // function copySubtree END

/*!
Browse for node to place the object copy into
*/
function browse( $Module, $srcNode )
{
    if ( $Module->hasActionParameter( 'LanguageCode' ) )
        $languageCode = $Module->actionParameter( 'LanguageCode' );
    else
    {
        $languageCode = false;
    }

    $nodeID          = $srcNode->attribute( 'node_id' );
    $object          = $srcNode->attribute( 'object' );
    $objectID        = $object->attribute( 'id' );
    $class           = $object->contentClass();
    $classID         = $class->attribute( 'id' );
    $srcParentNodeID = $srcNode->attribute( 'parent_node_id' );

    $ignoreNodesSelect = array();
    $ignoreNodesClick = array();
    foreach ( $object->assignedNodes( false ) as $element )
    {
        $ignoreNodesSelect[] = $element['node_id'];
        $ignoreNodesClick[]  = $element['node_id'];
    }
    $ignoreNodesSelect = array_unique( $ignoreNodesSelect );
    $ignoreNodesClick = array_unique( $ignoreNodesClick );

    $viewMode = 'full';
    if ( $Module->hasActionParameter( 'ViewMode' ) )
        $viewMode = $module->actionParameter( 'ViewMode' );

    eZContentBrowse::browse(
         array( 'action_name'          => 'CopySubtree',
                'description_template' => 'design:content/browse_copy_subtree.tpl',
                'keys'                 => array( 'class'      => $classID,
                                                 'class_id'   => $class->attribute( 'identifier' ),
                                                 'classgroup' => $class->attribute( 'ingroup_id_list' ),
                                                 'section'    => $object->attribute( 'section_id' ) ),
                'ignore_nodes_select'  => $ignoreNodesSelect,
                'ignore_nodes_click'   => $ignoreNodesClick,
                'persistent_data'      => array( 'ObjectID' => $objectID,
                                                 'NodeID'   => $nodeID ),
                'permission'           => array( 'access' => 'create', 'contentclass_id' => $classID ),
                'content'              => array( 'node_id' => $nodeID ),
                'start_node'           => $srcParentNodeID,
                'cancel_page'          => $Module->redirectionURIForModule( $Module, 'view',
                                                         array( $viewMode, $srcParentNodeID, $languageCode ) ),
                'from_page'            => "/content/copysubtree" ),
         $Module );
}

/*!
Redirect to the page that lets a user to choose which versions to copy:
either all version or the current one.
*/
function chooseOptionsToCopy( $Module, &$Result, $srcNode, $chooseVersions, $chooseCreator, $chooseTime )
{
        $selectedNodeIDArray = eZContentBrowse::result( $Module->currentAction() );

        require_once( 'kernel/common/template.php' );
        $tpl = templateInit();

        $tpl->setVariable( 'node', $srcNode );
        $tpl->setVariable( 'selected_node_id', $selectedNodeIDArray[0] );
        $tpl->setVariable( 'choose_versions', $chooseVersions );
        $tpl->setVariable( 'choose_creator', $chooseCreator );
        $tpl->setVariable( 'choose_time', $chooseTime );

        $Result['content'] = $tpl->fetch( 'design:content/copy_subtree.tpl' );
        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezi18n( 'kernel/content', 'Content' ) ),
                                 array( 'url' => false,
                                        'text' => ezi18n( 'kernel/content', 'Copy subtree' ) ) );
}

function showNotificationAfterCopying( $http, $Module, &$Result, &$Notifications, $srcNode )
{
    require_once( 'kernel/common/template.php' );
    $tpl = templateInit();

    if ( $http->hasSessionVariable( "LastAccessesURI" ) )
    {
        $tpl->setVariable( 'redirect_url', $http->sessionVariable( "LastAccessesURI" ) );
    }
    else
    {
        $parentRootNodeID = $srcNode->attribute( 'parent_node_id' );
        if ( $Module->hasActionParameter( 'LanguageCode' ) )
            $languageCode = $Module->actionParameter( 'LanguageCode' );
        else
        {
            $languageCode = false;
        }

        if ( $Module->hasActionParameter( 'ViewMode' ) )
            $viewMode = $module->actionParameter( 'ViewMode' );
        else
            $viewMode = 'full';

        $redirectURI = $Module->redirectionURIForModule( $Module, 'view', array( $viewMode, $parentRootNodeID, $languageCode ) );
        $tpl->setVariable( 'redirect_url', $redirectURI );
    }

    $tpl->setVariable( 'source_node', $srcNode );
    //$tpl->setVariable( 'subtree_nodes_count', $srcSubtreeNodesCount );
    $tpl->setVariable( 'notifications', $Notifications );

    $Result['content'] = $tpl->fetch( 'design:content/copy_subtree_notification.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'kernel/content', 'Content' ) ),
                             array( 'url' => false,
                                    'text' => ezi18n( 'kernel/content', 'Copy subtree' ) ) );
}
/////////// functions END ==================================================================

$Result = array();
$notifications = array( 'Notifications' => array(),
                        'Warnings' => array(),
                        'Errors' => array() );
$contentINI = eZINI::instance( 'content.ini' );

// check if number of nodes being copied not more then MaxNodesCopySubtree setting
$maxNodesCopySubtree = $contentINI->variable( 'CopySettings', 'MaxNodesCopySubtree' );
$srcSubtreeNodesCount = $srcNode->subTreeCount();

if ( $srcSubtreeNodesCount > $maxNodesCopySubtree )
{
    $notifications['Warnings'][] = ezi18n( 'kernel/content/copysubtree',
                                           "You are trying to copy a subtree that contains more than ".
                                           "the maximum possible nodes for subtree copying. ".
                                           "You can copy this subtree using Subtree Copy script.",
                                           null, array( $maxNodesCopySubtree ) );
    $notifications['Notifications'][] = ezi18n( 'kernel/content/copysubtree',
                                                "Subtree was not copied." );

    showNotificationAfterCopying( $http, $Module, $Result, $notifications, $srcNode );
    return;
}

$versionHandling = $contentINI->variable( 'CopySettings', 'VersionHandling' );
$creatorHandling = $contentINI->variable( 'CopySettings', 'CreatorHandling' );
$timeHandling    = $contentINI->variable( 'CopySettings', 'TimeHandling' );
$showCopySubtreeNotification = $contentINI->variable( 'CopySettings', 'ShowCopySubtreeNotification' );

$chooseVersions = ( $versionHandling == 'user-defined' );
$chooseCreator  = ( $creatorHandling == 'user-defined' );
$chooseTime     = ( $timeHandling    == 'user-defined' );
$showNotification = ( $showCopySubtreeNotification == 'enabled' );

if( $chooseVersions )
    $allVersions = ( $Module->hasActionParameter( 'VersionChoice' ) and
                     $Module->actionParameter( 'VersionChoice' ) == 1 ) ? true : false;
else
    $allVersions = ( $versionHandling == 'last-published' ) ? false : true;

if ( $chooseCreator )
    $keepCreator = ( $Module->hasActionParameter( 'CreatorChoice' ) and
                     $Module->actionParameter( 'CreatorChoice' ) == 1 ) ? true : false;
else
    $keepCreator = ( $creatorHandling == 'keep-unchanged' );

if ( $chooseTime )
    $keepTime = ( $Module->hasActionParameter( 'TimeChoice' ) and
                  $Module->actionParameter( 'TimeChoice' ) == 1 ) ? true : false;
else
    $keepTime = ( $timeHandling == 'keep-unchanged' );


$keepCreator = false;
$keepTime = false;

if ( $Module->isCurrentAction( 'Copy' ) )
{
    // actually do copying after a user has selected object versions to copy
    $newParentNodeID = $http->postVariable( 'SelectedNodeID' );
    copySubtree( $NodeID, $newParentNodeID, $notifications, $allVersions, $keepCreator, $keepTime );

    if ( $showNotification )
    {
        showNotificationAfterCopying( $http, $Module, $Result, $notifications, $srcNode );
        return;
    }
    return $Module->redirectToView( 'view', array( 'full', $newParentNodeID ) );
}
else if ( $Module->isCurrentAction( 'CopySubtree' ) )
{
    // we get here after a user selects target node to place the source object under
    if( $chooseVersions or $chooseCreator or $chooseTime )
    {
        // redirect to the page with choice of versions to copy
        chooseOptionsToCopy( $Module, $Result, $srcNode, $chooseVersions, $chooseCreator, $chooseTime );
    }
    else
    {
        // actually do copying of the pre-configured object version(s)
        $selectedNodeIDArray = eZContentBrowse::result( $Module->currentAction() );
        $newParentNodeID = $selectedNodeIDArray[0];
        copySubtree( $NodeID, $newParentNodeID, $notifications, $allVersions, $keepCreator, $keepTime );

        if ( $showNotification )
        {
            showNotificationAfterCopying( $http, $Module, $Result, $notifications, $srcNode );
            return;
        }
        return $Module->redirectToView( 'view', array( 'full', $newParentNodeID ) );
    }
}
else // default, initial action
{   //Browse for target node.
    //We get here when a user clicks "copy" button when viewing some node.
    browse( $Module, $srcNode );
}

?>
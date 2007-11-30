#!/usr/bin/env php
<?php
//
// Created on: <20-Feb-2005 15:00:00 rl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

// Subtree Copy Script
// file  bin/php/ezsubtreecopy.php

// script initializing
//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                         "This script will make a copy of a content object subtree and place it in a specified\n" .
                                                         "location.\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true,
                                      'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[src-node-id:][dst-node-id:][all-versions][keep-creator][keep-time]",
                                      "",
                                      array( 'src-node-id' => "Source subtree parent node ID.",
                                             'dst-node-id' => "Destination node ID.",
                                             'all-versions' => "Copy all versions for each contentobject being copied.",
                                             'keep-creator'=> "Do not change the creator (user) for the copied content objects.",
                                             'keep-time'   => "Do not change the creation and modification time of the copied content objects."
                                             ),
                                      false,
                                      array( 'user' => true )
                                     );
$script->initialize();

$srcNodeID   = $scriptOptions[ 'src-node-id' ] ? $scriptOptions[ 'src-node-id' ] : false;
$dstNodeID   = $scriptOptions[ 'dst-node-id' ] ? $scriptOptions[ 'dst-node-id' ] : false;
$allVersions = $scriptOptions[ 'all-versions' ];
$keepCreator = $scriptOptions[ 'keep-creator' ];
$keepTime    = $scriptOptions[ 'keep-time' ];

//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "kernel/classes/ezcontentobjecttreenode.php" );

function copyPublishContentObject( $sourceObject,
                                   $sourceSubtreeNodeIDList,
                                   &$syncNodeIDListSrc, &$syncNodeIDListNew,
                                   &$syncObjectIDListSrc, &$syncObjectIDListNew,
                                   $allVersions = false, $keepCreator = false, $keepTime = false )
{
    global $cli;

    $sourceObjectID = $sourceObject->attribute( 'id' );

    $key = array_search( $sourceObjectID, $syncObjectIDListSrc );
    if ( $key !== false )
    {
        return 1; // object already copied
    }

    $srcNodeList = $sourceObject->attribute( 'assigned_nodes' );

    // check if all parent nodes for given contentobject are already published:
    foreach ( $srcNodeList as $srcNode )
    {
        $sourceParentNodeID = $srcNode->attribute( 'parent_node_id' );

        // if parent node for this node is outside
        // of subtree being copied, then skip this node.
        $key = array_search( $sourceParentNodeID, $sourceSubtreeNodeIDList );
        if ( $key === false )
        {
            continue;
        }

        $key = array_search( $sourceParentNodeID, $syncNodeIDListSrc );
        if ( $key === false )
        {
            return 2; // one of parent nodes is not published yet - have to try to publish later.
        }
        else
        {
            $newParentNodeID = $syncNodeIDListNew[ $key ];
            if ( ( $newParentNode = eZContentObjectTreeNode::fetch( $newParentNodeID ) ) === null )
            {
                return 3; // cannot fetch one of parent nodes - must be error somewhere above.
            }
        }
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
        $key = array_search( $parentNodeID, $sourceSubtreeNodeIDList );
        if ( $key === false )
        {
            $assignmentsForRemoving[] = $assignment->attribute( 'id' );
            continue;
        }

        $key = array_search( $parentNodeID, $syncNodeIDListSrc );
        if ( $key === false )
        {
            $cli->error( "Subtree Copy Error!\nOne of parent nodes for contentobject (ID = $sourceObjectID) is not published yet." );
            return 4;
        }

        if ( $assignment->attribute( 'is_main' ) )
            $foundMainAssignment = true;

        $newParentNodeID = $syncNodeIDListNew[ $key ];
        $assignment->setAttribute( 'parent_node', $newParentNodeID );
        $assignment->store();
    }
    // remove assigments which are outside of subtree being copied:
    eZNodeAssignment::purgeByID( $assignmentsForRemoving ); // JB valid

    // if main nodeassigment was not copied then set as main first nodeassigment
    if ( $foundMainAssignment == false )
    {
        $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );
        // JB start
        // We need to check if it has any assignments before changing the data.
        if ( isset( $newObjAssignments[0] ) )
        {
            $newObjAssignments[0]->setAttribute( 'is_main', 1 );
            $newObjAssignments[0]->store();
        }
        // JB end
    }

    // publish the newly created object
    //include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    $result = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObject->attribute( 'id' ),
                                                                        'version'   => $curVersion ) );
    // JB start
    // Refetch the object data since it might change in the database.
    $newObjectID = $newObject->attribute( 'id' );
    $newObject = eZContentObject::fetch( $newObjectID );
    // JB end
    $newNodeList = $newObject->attribute( 'assigned_nodes' );
    if ( count($newNodeList) == 0 )
    {
        $newObject->purge();
        $cli->error( "Subtree Copy Error!\nCannot publish contentobject." );
        return 5;
    }

    $objAssignments = $curVersionObject->attribute( 'node_assignments' );
    foreach ( $newNodeList as $newNode )
    {
        $newParentNodeID = $newNode->attribute( 'parent_node_id' );
        $keyA = array_search( $newParentNodeID, $syncNodeIDListNew );

        if ( $keyA === false )
        {
            die( "Copy Subtree Error: Algoritm ERROR! Cannot find new parent node ID in new ID's list" );
        }

        $srcParentNodeID = $syncNodeIDListSrc[ $keyA ];

        // Update attributes of node
        $bSrcParentFound = false;
        foreach ( $srcNodeList as $srcNode )
        {
            if ( $srcNode->attribute( 'parent_node_id' ) == $srcParentNodeID )
            {
                $newNode->setAttribute( 'priority',     $srcNode->attribute( 'priority' ) );
                $newNode->setAttribute( 'is_hidden',    $srcNode->attribute( 'is_hidden' ) );
                $newNode->setAttribute( 'is_invisible', $srcNode->attribute( 'is_invisible' ) );
                $syncNodeIDListSrc[] = $srcNode->attribute( 'node_id' );
                $syncNodeIDListNew[] = $newNode->attribute( 'node_id' );
                $bSrcParentFound = true;
                break;
            }
        }
        if ( $bSrcParentFound == false )
        {
            die( "Copy Subtree Error: Algoritm ERROR! Cannot find source parent node ID in source parent node ID's list of contentobject being copied." );
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

    // Update "is_invisible" attribute for the newly created node.
    $newNode = $newObject->attribute( 'main_node' );
    eZContentObjectTreeNode::updateNodeVisibility( $newNode, $newParentNode ); // ??? do we need this here?

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
    {   // copy time of creation and modification and creator id for
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

}   //function copyPublishContentObject END



// 1. Copy subtree and form the arrays of accordance of the old and new nodes and content objects.

$sourceSubTreeMainNode = ( $srcNodeID ) ? eZContentObjectTreeNode::fetch( $srcNodeID ) : false;
$destinationNode = ( $dstNodeID ) ? eZContentObjectTreeNode::fetch( $dstNodeID ) : false;

if ( !$sourceSubTreeMainNode )
{
    $cli->error( "Subtree copy Error!\nCannot get subtree main node. Please check src-node-id argument and try again." );
    $script->showHelp();
    $script->shutdown( 1 );
}
if ( !$destinationNode )
{
    $cli->error( "Subtree copy Error!\nCannot get destination node. Please check dst-node-id argument and try again." );
    $script->showHelp();
    $script->shutdown( 1 );
}

$sourceNodeList    = array();
$syncNodeIDListSrc = array();
$syncNodeIDListNew = array();

$sourceSubTreeMainNodeID = $sourceSubTreeMainNode->attribute( 'node_id' );
$sourceNodeList[] = $sourceSubTreeMainNode;

$syncNodeIDListSrc[] = $sourceSubTreeMainNode->attribute( 'parent_node_id' );
$syncNodeIDListNew[] = (int) $dstNodeID;

$syncObjectIDListSrc = array();
$syncObjectIDListNew = array();

$sourceNodeList = array_merge( $sourceNodeList, eZContentObjectTreeNode::subTreeByNodeID( false, $sourceSubTreeMainNodeID ) );
$countNodeList = count( $sourceNodeList );

// Prepare list of source node IDs. We will need it in the future
// for checking node is inside or outside of the subtree being copied.
$sourceNodeIDList = array();
foreach ( $sourceNodeList as $sourceNode )
    $sourceNodeIDList[] = $sourceNode->attribute( 'node_id' );

$cli->output( "Copying subtree:" );

$k = 0;
while ( count( $sourceNodeList ) > 0 )
{
    if ( $k > $countNodeList )
    {
        $cli->error( "Subtree Copy Error!\nToo many loops while copying nodes." );
        $script->shutdown( 6 );
    }

    for ( $i = 0; $i < count( $sourceNodeList ); $i)
    {
        $sourceNodeID = $sourceNodeList[ $i ]->attribute( 'node_id' );

        if ( in_array( $sourceNodeID, $syncNodeIDListSrc ) )
            array_splice( $sourceNodeList, $i, 1 );
        else
        {
            $sourceObject =& $sourceNodeList[ $i ]->object();
            $srcSubtreeNodeIDlist = ($sourceNodeID == $sourceSubTreeMainNodeID) ? $syncNodeIDListSrc : $sourceNodeIDList;

            $copyResult = copyPublishContentObject( $sourceObject,
                                                    $srcSubtreeNodeIDlist,
                                                    $syncNodeIDListSrc, $syncNodeIDListNew,
                                                    $syncObjectIDListSrc, $syncObjectIDListNew,
                                                    $allVersions, $keepCreator, $keepTime );
            if ( $copyResult === 0 )
            {   // if copying successful then remove $sourceNode from $sourceNodeList
                array_splice( $sourceNodeList, $i, 1 );
                $cli->output( ".", false );
            }
            else
                $i++;
        }
    }
    $k++;
}

array_shift( $syncNodeIDListSrc );
array_shift( $syncNodeIDListNew );

$cli->output( "\nNumber of copied nodes: " . count( $syncNodeIDListNew ) );
$cli->output( "Number of copied contentobjects: " . count( $syncObjectIDListNew ) );

// 2. fetch all new subtree

$key = array_search( $sourceSubTreeMainNodeID, $syncNodeIDListSrc );
if ( $key === false )
{
    $cli->error( "Subtree copy Error!\nCannot find subtree root node in array of IDs of copied nodes." );
    $script->shutdown( 1 );
}

$newSubTreeMainNodeID = $syncNodeIDListSrc[ $key ];
$newSubTreeMainNode   = eZContentObjectTreeNode::fetch( $newSubTreeMainNodeID );

$newNodeList[] = $newSubTreeMainNode;
$newNodeList = $sourceNodeList = array_merge( $newNodeList,
                                              eZContentObjectTreeNode::subTreeByNodeID( false, $newSubTreeMainNodeID ) );

$cli->output( "Fixing global and local links..." );

// 3. fix local links (in ezcontentobject_link)

$db = eZDB::instance();

if ( !$db )
{
    $cli->error( "Subtree Copy Error!\nCannot create instance of eZDB for fixing local links (related objects)." );
    $script->shutdown( 3 );
}

$idListStr = implode( ',', $syncObjectIDListNew );
$relatedRecordsList = $db->arrayQuery( "SELECT * FROM ezcontentobject_link WHERE from_contentobject_id IN ($idListStr)" );

foreach ( $relatedRecordsList as $relatedEntry )
{
    $kindex = array_search( $relatedEntry[ 'to_contentobject_id' ], $syncObjectIDListSrc );
    if ( $kindex !== false )
    {
        $newToContentObjectID = $syncObjectIDListNew[ $kindex ];
        $linkID = $relatedEntry[ 'id' ];
        $db->query( "UPDATE ezcontentobject_link SET  to_contentobject_id=$newToContentObjectID WHERE id=$linkID" );
    }
}

// 4. duplicating of global links for new contentobjects (in ezurl_object_link) are automatic during copy of contentobject.

// 5. loop on new nodes and REPLACE node_ids and object_ids

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
            // JK: find out what is the first tag
            // This is not the optimal solution, there is better way how to do. This is just a quick fix.
            $tmpArray = array();
            if ( $literalTagBeginPos = strpos( $xmlText, "<literal", $curPos ) )
                $tmpArray[] = $literalTagBeginPos;
            if ( $linkTagBeginPos = strpos( $xmlText, "<link", $curPos ) )
                $tmpArray[] = $linkTagBeginPos;
            if ( $aTagBeginPos = strpos( $xmlText, "<a", $curPos ) )
                $tmpArray[] = $aTagBeginPos;
            if ( $embedTagBeginPos = strpos( $xmlText, "<embed", $curPos ) )
                $tmpArray[] = $embedTagBeginPos;
            if ( $objectTagBeginPos = strpos( $xmlText, "<object", $curPos ) )
                $tmpArray[] = $objectTagBeginPos;

            if ( !$tmpArray )
            {
                break;
            }

            $tagBeginPos = min( $tmpArray );

            if ( $tagBeginPos == $literalTagBeginPos )
            {
                $literalTagEndPos = strpos( $xmlText, "</literal>", $literalTagBeginPos );
                if ( $literalTagEndPos === false )
                    break;
                $curPos = $literalTagEndPos + 9;
            }

            if ( $tagBeginPos == $linkTagBeginPos || $tagBeginPos == $aTagBeginPos || $tagBeginPos == $embedTagBeginPos )
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
            else if ( $tagBeginPos == $objectTagBeginPos )
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

$cli->output( "Done." );

$script->shutdown();

?>

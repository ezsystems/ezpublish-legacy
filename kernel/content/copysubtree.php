<?php
//
// Created on: <23-Mar-2005 23:23:23 rl>
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

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( "lib/ezdb/classes/ezdb.php" );

$Module =& $Params['Module'];
$NodeID =& $Params['NodeID'];

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( 'BrowseCancelButton' ) )
{
    if ( $http->hasPostVariable( 'BrowseCancelURI' ) )
        return $Module->redirectTo( $http->postVariable( 'BrowseCancelURI' ) );
}

if ( $NodeID === null ) // NodeID is returned after browsing
{
    $NodeID =& $http->postVariable( 'NodeID' );
}

$srcNode = eZContentObjectTreeNode::fetch( $NodeID );

if ( $srcNode === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$srcNode->attribute( 'can_read' ) )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $parentNodeID = $srcNode->attribute( 'parent_node_id' );
    return $Module->redirectToView( 'view', array( 'full', $parentNodeID ) );
}

//=== functions ===================================================================================

function &copyPublishContentObject( &$sourceObject,
                                    &$sourceSubtreeNodeIDList,
                                    &$syncNodeIDListSrc, &$syncNodeIDListNew,
                                    &$syncObjectIDListSrc, &$syncObjectIDListNew,
                                    $allVersions = false, $keepCreator = false, $keepTime = false )
{
    $sourceObjectID = $sourceObject->attribute( 'id' );

    $key = array_search( $sourceObjectID, $syncObjectIDListSrc );
    if ( $key !== false )
    {
        eZDebug::writeDebug( "Object (ID = $sourceObjectID) has been Already Copied.",
                             "Subtree copy: copyPublishContentObject()" );
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
            eZDebug::writeDebug( "One of parent nodes is not published yet.",
                                 "Subtree copy: copyPublishContentObject()" );
            return 2;
        }
        else
        {
            $newParentNodeID = $syncNodeIDListNew[ $key ];
            if ( ( $newParentNode =& eZContentObjectTreeNode::fetch( $newParentNodeID ) ) === null )
            {
                eZDebug::writeError( "Cannot fetch one of parent nodes. There are error somewhere above",
                                     "Subtree copy error: copyPublishContentObject()" );
                return 3;
            }
        }
    }

    // make copy of source object
    $newObject             = $sourceObject->copy( $allVersions ); // insert source and new object's ids in $syncObjectIDList

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
    eZNodeAssignment::removeByID( $assignmentsForRemoving );

    // if main nodeassigment was not copied then set as main first nodeassigment
    if ( $foundMainAssignment == false )
    {
        $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );
        $newObjAssignments[0]->setAttribute( 'is_main', 1 );
        $newObjAssignments[0]->store();
    }

    // publish the newly created object
    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    $result = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObject->attribute( 'id' ),
                                                                        'version'   => $curVersion ) );
    $newNodeList =& $newObject->attribute( 'assigned_nodes' );
    if ( count($newNodeList) == 0 )
    {
        $newObject->purge();
        eZDebug::writeError( "Cannot publish contentobject.",
                             "Subtree Copy Error!" );
        return -1;
    }

    foreach ( $newNodeList as $newNode )
    {
        $newParentNodeID = $newNode->attribute( 'parent_node_id' );
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
            eZDebug::writeError( "Cannot find source parent node ID in source parent node ID's list of contentobject being copied.",
                                 "Subtree Copy Error!" );
        }
        $newNode->store();
    }

    // Update "is_invisible" attribute for the newly created node.
    $newNode =& $newObject->attribute( 'main_node' );
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


function copySubtree( $srcNodeID, $dstNodeID, $allVersions, $keepCreator, $keepTime )
{
    // 1. Copy subtree and form the arrays of accordance of the old and new nodes and content objects.

    $sourceSubTreeMainNode = ( $srcNodeID ) ? eZContentObjectTreeNode::fetch( $srcNodeID ) : false;
    $destinationNode = ( $dstNodeID ) ? eZContentObjectTreeNode::fetch( $dstNodeID ) : false;

    if ( !$sourceSubTreeMainNode )
    {
        eZDebug::writeError( "Cannot get subtree main node (nodeID = $srcNodeID).",
                             "Subtree copy Error! copySubtree():" );
        return 1;
    }
    if ( !$destinationNode )
    {
        eZDebug::writeError( "Cannot get destination node.",
                             "Subtree copy Error!" );
        return 1;
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

    $sourceNodeList = array_merge( $sourceNodeList,
                                   eZContentObjectTreeNode::subTree( false, $sourceSubTreeMainNodeID ) );
    $countNodeList = count( $sourceNodeList );

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
            return 6;
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
                }
                else
                    $i++;
            }
        }
        $k++;
    }

    array_shift( $syncNodeIDListSrc );
    array_shift( $syncNodeIDListNew );

    eZDebug::writeDebug( count( $syncNodeIDListNew ) ,"Number of copied nodes: " );
    eZDebug::writeDebug( count( $syncObjectIDListNew ), "Number of copied contentobjects: " );

    // 2. fetch all new subtree

    $key = array_search( $sourceSubTreeMainNodeID, $syncNodeIDListSrc );
    if ( $key === false )
    {
        eZDebug::writeError( "Cannot find subtree root node in array of IDs of copied nodes.",
                             "Subtree copy Error!" );
        return 2;
    }

    $newSubTreeMainNodeID = $syncNodeIDListSrc[ $key ];
    $newSubTreeMainNode   = eZContentObjectTreeNode::fetch( $newSubTreeMainNodeID );

    $newNodeList[] = $newSubTreeMainNode;
    $newNodeList = $sourceNodeList = array_merge( $newNodeList,
                                                  eZContentObjectTreeNode::subTree( false, $newSubTreeMainNodeID ) );

    // 3. fix local links (in ezcontentobject_link)
    eZDebug::writeDebug( "Fixing global and local links...",
                         "Subtree copy:" );

    $db =& eZDB::instance();
    if ( !$db )
    {
        eZDebug::writeError( "Cannot create instance of eZDB for fixing local links (related objects).",
                             "Subtree Copy Error!" );
        return 3;
    }

    $idListStr = implode( ',', $syncObjectIDListNew );
    $relatedRecordsList =& $db->arrayQuery( "SELECT * FROM ezcontentobject_link WHERE from_contentobject_id IN ($idListStr)" );

    foreach ( array_keys( $relatedRecordsList ) as $key )
    {
        $relatedEntry =& $relatedRecordsList[ $key ];
        $kindex = array_search( $relatedEntry[ 'to_contentobject_id' ], $syncObjectIDListSrc );
        if ( $kindex !== false )
        {
            $newToContentObjectID = $syncObjectIDListNew[ $kindex ];
            $linkID = $relatedEntry[ 'id' ];
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
        foreach ( array_keys( $attributeList ) as $key )
        {
            $xmlAttribute =& $attributeList[ $key ];
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
                    if ( $literalTagEnd === false )
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

    eZDebug::writeDebug( "Successfuly DONE.",
                         "Copy subtree:" );

} // function copySubtree END

/*!
Browse for node to place the object copy into
*/
function browse( &$Module, &$srcNode )
{
    if ( $Module->hasActionParameter( 'LanguageCode' ) )
        $languageCode = $Module->actionParameter( 'LanguageCode' );
    else
        $languageCode = eZContentObject::defaultLanguage();

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

    include_once( 'kernel/classes/ezcontentbrowse.php' );
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
                'content'              => array( 'node_id'        => $nodeID ),
                                                 //'object_id'      => $objectID,
                                                 //'object_version' => $object->attribute( 'current_version' ),
                                                 //'object_language'=> $languageCode ),
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

function chooseOptionsToCopy( &$Module, &$Result, &$srcNode, $chooseVersions, $chooseCreator, $chooseTime )
{
        include_once( 'kernel/classes/ezcontentbrowse.php' );
        $selectedNodeIDArray = eZContentBrowse::result( $Module->currentAction() );

        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();

        $tpl->setVariable( 'node', $srcNode );
        $tpl->setVariable( 'selected_node_id', $selectedNodeIDArray[0] );
        $tpl->setVariable( 'choose_versions', $chooseVersions );
        $tpl->setVariable( 'choose_creator', $chooseCreator );
        $tpl->setVariable( 'choose_time', $chooseTime );

        $Result['content'] = $tpl->fetch( 'design:content/copy_subtree.tpl' );
        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezi18n( 'kernel/content', 'Content' ) ),
                                 array( 'url' => false,
                                        'text' => ezi18n( 'kernel/content', 'Copy Subtree' ) ) );
}
// ===========================================================================================


$contentINI =& eZINI::instance( 'content.ini' );
$versionHandling = $contentINI->variable( 'CopySettings', 'VersionHandling' );
$creatorHandling = $contentINI->variable( 'CopySettings', 'CreatorHandling' );
$timeHandling    = $contentINI->variable( 'CopySettings', 'TimeHandling' );

$chooseVersions = ( $versionHandling == 'user-defined' );
$chooseCreator  = ( $creatorHandling == 'user-defined' );
$chooseTime     = ( $timeHandling    == 'user-defined' );

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
    $newParentNodeID =& $http->postVariable( 'SelectedNodeID' );
    $result = copySubtree( $NodeID, $newParentNodeID, $allVersions, $keepCreator, $keepTime );
    return $Module->redirectToView( 'view', array( 'full', $newParentNodeID ) );
}
else if ( $Module->isCurrentAction( 'CopySubtree' ) )
{
    // we get here after a user selects target node to place the source object under
    if( $chooseVersions or $chooseCreator or $chooseTime )
    {
        // redirect to the page with choice of versions to copy
        $Result = array();
        chooseOptionsToCopy( $Module, $Result, $srcNode, $chooseVersions, $chooseCreator, $chooseTime );
    }
    else
    {
        // actually do copying of the pre-configured object version(s)
        include_once( 'kernel/classes/ezcontentbrowse.php' );
        $selectedNodeIDArray = eZContentBrowse::result( $Module->currentAction() );
        $newParentNodeID =& $selectedNodeIDArray[0];
        $result = copySubtree( $NodeID, $newParentNodeID, $allVersions, $keepCreator, $keepTime );
        return $Module->redirectToView( 'view', array( 'full', $newParentNodeID ) );
    }
}
else // default, initial action
{   //Browse for target node.
    //We get here when a user clicks "copy" button when viewing some node.
    browse( $Module, $srcNode );
}

?>

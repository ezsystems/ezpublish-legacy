<?php
//
// Created on: <20-Feb-2005 15:00:00 rl>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

// Subtree Copy Script
// file  bin/php/ezsubtreecopy.php

// script initializing
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "\nSubtree Copy Script for eZPublish 3.6\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => false,
                                      'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[src-node-id:][dst-node-id:][allversions][keepcreator]",
                                      "",
                                      array( 'src-node-id' => "Source subtree parent node ID.",
                                             'dst-node-id' => "Destination node ID.",
                                             'allversions' => "Copy all versions for each contentobject being copied.",
                                             'keepcreator' => "Keep creator of contentobjects being copied unchanged."
                                             ),
                                      false,
                                      array( 'user' => true )
                                     );
$script->initialize();

$srcNodeID   = $scriptOptions[ 'src-node-id' ] ? $scriptOptions[ 'src-node-id' ] : false;
$dstNodeID   = $scriptOptions[ 'dst-node-id' ] ? $scriptOptions[ 'dst-node-id' ] : false;
$creatorID   = $scriptOptions[ 'creator-id' ]  ? $scriptOptions[ 'creator-id' ]  : false;
$allVersions = $scriptOptions[ 'allversions' ];
$keepCreator = $scriptOptions[ 'keepcreator' ];
$siteAccess  = $scriptOptions[ 'siteaccess' ]  ? $scriptOptions[ 'siteaccess' ]  : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli =& eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

////////////////////////////////////////////////////////////////////////////////////
include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );

function &copyPublishContentObject( &$sourceObject,
                                    &$syncNodeIDListSrc, &$syncNodeIDListNew,
                                    &$syncObjectIDListSrc, &$syncObjectIDListNew,
                                    $allVersions = false, $keepCreator = false )
{
    global $cli;

    $sourceObjectID = $sourceObject->attribute( 'id' );

    $key = array_search( $sourceObjectID, $syncObjectIDListSrc );
    if ( $key !== false )
    {
        return 1; // object already copied
    }

    $srcNodeList = $sourceObject->attribute( 'assigned_nodes' );

    foreach ( $srcNodeList as $srcNode )
    {
        $sourceParentNodeID = $srcNode->attribute( 'parent_node_id' );
        $key = array_search( $sourceParentNodeID, $syncNodeIDListSrc );

        if ( $key === false )
        {
            return 2; // one of parent nodes is not published yet - have to try to publish later.
        }
        else
        {
            $newParentNodeID = $syncNodeIDListNew[ $key ];
            if ( ( $newParentNode =& eZContentObjectTreeNode::fetch( $newParentNodeID ) ) === null )
            {
                return 3; // cannot fetch one of parent nodes - must be error somewhere above.
            }
        }
    }

    // make copy of source object
    $newObject             = $sourceObject->copy( $allVersions ); // insert source and new object's ids in $syncObjectIDList

    $syncObjectIDListSrc[] = $sourceObjectID;
    $syncObjectIDListNew[] = $newObject->attribute( 'id' );

    $curVersion        = $newObject->attribute( 'current_version' );
    $curVersionObject  = $newObject->attribute( 'current' );

    // if $keepCreator == true then keep owner of contentobject being
    // copied and creator of its published version Unchaged
    if ( $keepCreator )
    {
        $srcOwnerID            = $sourceObject->attribute( 'owner_id' );
        $srcCurVersionObject   = $sourceObject->attribute( 'current' );
        $srcCurVersionCreatorID= $srcCurVersionObject->attribute( 'creator_id' );

        $newObject->setAttribute( 'owner_id', $srcOwnerID );
        $newObject->store();

        $curVersionObject->setAttribute( 'creator_id', $srcCurVersionCreatorID );
        $curVersionObject->store();
    }

    $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );
    unset( $curVersionObject );

    // copy nodeassigments
    foreach ( $newObjAssignments as $assignment )
    {
        $parentNodeID = $assignment->attribute( 'parent_node' );
        $key = array_search( $parentNodeID, $syncNodeIDListSrc );
        if ( $key === false )
        {
            return 4;
        }

        $newParentNodeID = $syncNodeIDListNew[ $key ];
        $assignment->setAttribute( 'parent_node', $newParentNodeID );
        $assignment->store();
    }

    // publish the newly created object
    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    $result = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObject->attribute( 'id' ),
                                                                        'version'   => $curVersion ) );
    $newNodeList =& $newObject->attribute( 'assigned_nodes' );
    if ( count($newNodeList) == 0 )
    {
        $newObject->purge();
        $cli->output( "Subtree Copy Error!\nCannot publish content object." );
        return 5;
    }

    foreach ( $newNodeList as $newNode )
    {
        $newParentNodeID = $newNode->attribute( 'parent_node_id' );
        $keyA = array_search( $newParentNodeID, $syncNodeIDListNew );

        if ( $keyA === false )
        {
            die( "Copy Subtree Error: Algoritm ERROR! Cannot find new parent node ID in new ID's list" );
        }

        $srcParentNodeID = $syncNodeIDListSrc[ $keyA ];

        // Update attributes
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
        $newNode->store();
    }

    // Update "is_invisible" attribute for the newly created node.
    $newNode =& $newObject->attribute( 'main_node' );
    eZContentObjectTreeNode::updateNodeVisibility( $newNode, $newParentNode ); // ??? do we need this here?

    return 0; // source object was copied successfully.

}   //function copyNode END



// 1. Copy subtree and form the arrays of accordance of the old and new nodes and content objects.

$sourceSubTreeMainNode = ( $srcNodeID ) ? eZContentObjectTreeNode::fetch( $srcNodeID ) : false;
$destinationNode = ( $dstNodeID ) ? eZContentObjectTreeNode::fetch( $dstNodeID ) : false;

if ( !$sourceSubTreeMainNode )
{
    $cli->output( "Subtree copy Error!\nCannot get subtree main node. Please check src-node-id argument and try again." );
    return 1;
}
if ( !$destinationNode )
{
    $cli->output( "Subtree copy Error!\nCannot get destination node. Please check dst-node-id argument and try again." );
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

$cli->output( "Copying subtree:" );

$k = 0;
while ( count( $sourceNodeList ) > 0 )
{
    if ( $k > $countNodeList )
    {
        $cli->output( "Subtree Copy Error! Too many loops while copying nodes." );
        return 6; //break;
    }

    for ( $i = 0; $i < count( $sourceNodeList ); $i)
    {
        $sourceNodeID = $sourceNodeList[ $i ]->attribute( 'node_id' );

        if ( in_array( $sourceNodeID, $syncNodeIDListSrc ) )
        {
            array_splice( $sourceNodeList, $i, 1 );
        }
        else
        {
            $sourceObject =& $sourceNodeList[ $i ]->object();
            $copyResult = copyPublishContentObject( $sourceObject,
                                                    $syncNodeIDListSrc, $syncNodeIDListNew,
                                                    $syncObjectIDListSrc, $syncObjectIDListNew,
                                                    $allVersions, $keepCreator );
            if ( $copyResult === 0 )
            {   // if copying successful then remove $sourceNode from $sourceNodeList
                array_splice( $sourceNodeList, $i, 1 );
                $cli->output( ".", false );
            }
            else
            {
                $i++;
            }
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
    $cli->output( "Subtree copy Error!\nCannot find new main node in array of new node_IDs." );
    return 2;
}

$newSubTreeMainNodeID = $syncNodeIDListSrc[ $key ];
$newSubTreeMainNode   = eZContentObjectTreeNode::fetch( $newSubTreeMainNodeID );

$newNodeList[] = $newSubTreeMainNode;
$newNodeList = $sourceNodeList = array_merge( $newNodeList,
                                              eZContentObjectTreeNode::subTree( false, $newSubTreeMainNodeID ) );

$cli->output( "Fixing global and local links..." );

// 3. fix local links (in ezcontentobject_link)

$db =& eZDB::instance();

if ( !$db )
{
    $cli->output( "Subtree Copy Error!\nCannot create instance of eZDB for fixing local links (related objects)." );
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

$cli->output( "Done." );

$script->shutdown();

?>

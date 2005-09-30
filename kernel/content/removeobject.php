<?php
//
//
// Created on: <08-Nov-2002 16:02:26 wy>
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

include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/common/template.php" );
include_once( 'kernel/classes/ezpreferences.php' );

$Module =& $Params["Module"];

if ( !isset( $Offset ) )
    $Offset = false;

$http =& eZHTTPTool::instance();

$viewMode = $http->sessionVariable( "CurrentViewMode" );
$deleteIDArray = $http->sessionVariable( "DeleteIDArray" );
$contentObjectID = $http->sessionVariable( 'ContentObjectID' );
$contentNodeID = $http->sessionVariable( 'ContentNodeID' );

$children_list_limit = eZPreferences::value( "remove_children_list_limit" );

switch  ( $children_list_limit )
{
    case 0:
        $pageLimit = 10;
        break;
    case 1:
        $pageLimit = 10;
        break;
    case 2:
        $pageLimit = 25;
        break;
    case 3:
        $pageLimit = 50;
        break;
    default:
        $pageLimit = 10;
        break;
}

if ( $Offset < $pageLimit )
    $Offset = 0;

$requestedURI = '';
$userRedirectURI = '';
$requestedURI =& $GLOBALS['eZRequestedURI'];
if ( get_class( $requestedURI ) == 'ezuri' )
{
    $userRedirectURI = $requestedURI->uriString( true );
}
$http->setSessionVariable( 'userRedirectURIReverseObjects', $userRedirectURI );

// Fetch number of reverse related objects for each of the items being removed.
$reverselistCountArray = array();
$totalReverseRelationsCount = 0; // total number of reverse related objects for all items.
foreach( $deleteIDArray as $nodeID )
{
    $contentObject = eZContentObject::fetchByNodeID( $nodeID );
    $contentObject_ID = $contentObject->attribute('id');
    $reverseObjectCount = $contentObject->reverseRelatedObjectCount( false, false, false );
    $reverselistCountArray[$contentObject_ID] = $reverseObjectCount;
    $totalReverseRelationsCount += $reverseObjectCount;
}

if ( $http->hasSessionVariable( 'ContentLanguage' ) )
{
    $contentLanguage = $http->sessionVariable( 'ContentLanguage' );
}
else
{
    $contentLanguage = eZContentObject::defaultLanguage();
}
if ( count( $deleteIDArray ) <= 0 )
    return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID, $contentLanguage ) );

// Cleanup and redirect back when cancel is clicked
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $http->removeSessionVariable( "CurrentViewMode" );
    $http->removeSessionVariable( "DeleteIDArray" );
    $http->removeSessionVariable( 'ContentObjectID' );
    $http->removeSessionVariable( 'ContentNodeID' );
    $http->removeSessionVariable( 'userRedirectURIReverseObjects' );
    return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID, $contentLanguage ) );
}

$moveToTrash = true;
if ( $http->hasPostVariable( 'SupportsMoveToTrash' ) )
{
    if ( $http->hasPostVariable( 'MoveToTrash' ) )
        $moveToTrash = true;
    else
        $moveToTrash = false;
}

if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    // Remove reverse relations for each item.
    foreach ( $deleteIDArray as $nodeID )
    {
        $contentObject = eZContentObject::fetchByNodeID( $nodeID );
        $contentObject_ID = $contentObject->attribute( 'id' );
        $contentObject->removeReverseRelations( $contentObject_ID );
    }
    eZContentObjectTreeNode::removeSubtrees( $deleteIDArray, $moveToTrash );
    return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID, $contentLanguage ) );
}

$moveToTrashAllowed = true;
$deleteResult = array();
$childCount = 0;
$info = eZContentObjectTreeNode::subtreeRemovalInformation( $deleteIDArray );
$deleteResult = $info['delete_list'];
if ( !$info['move_to_trash'] )
{
    $moveToTrashAllowed = false;
}
$totalChildCount = $info['total_child_count'];
$canRemoveAll = $info['can_remove_all'];

// We check if we can remove the nodes without confirmation
// to do this the following must be true:
// - The total child count must be zero
// - There must be no object removal (i.e. it is the only node for the object)
if ( $totalChildCount == 0 )
{
    $canRemove = true;
    foreach ( $deleteResult as $item )
    {
        if ( $item['object_node_count'] <= 1 )
        {
            $canRemove = false;
            break;
        }
    }
    if ( $canRemove )
    {
        eZContentObjectTreeNode::removeSubtrees( $deleteIDArray, $moveToTrash );
        return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID, $contentLanguage ) );
    }
}
$childrenList = null;
$reverselistCountChildrenArray = array();
$totalReverseRelationsChildrenCount = 0; // Total number of reverse related objects for all items.

$db =& eZDB::instance();
$contentObjectTreeNode = eZContentObjectTreeNode::fetch( $deleteIDArray );
$path_strings = '( ';
$path_strings2 = '( ';
$except_path_strings = '';
$i = 0;
// Create WHERE section
if ( is_array( $contentObjectTreeNode ) )
    foreach ( $contentObjectTreeNode as $treeNode )
    {
        $path_strings .= "tree.path_string like '$treeNode->PathString%'";
        $path_strings2 .= "tree2.path_string like '$treeNode->PathString%'";
        $except_path_strings .=  "tree.path_string <> '$treeNode->PathString'";
        ++$i;
        if ( $i < count( $contentObjectTreeNode )  )
        {
            $path_strings .=' or ';
            $except_path_strings .=' and ';
            $path_strings2 .=' or ';
        }
    }
else
{
    $path_strings .= "tree.path_string like '$contentObjectTreeNode->PathString%'";
    $path_strings2 .= "tree2.path_string like '$contentObjectTreeNode->PathString%'";
    $except_path_strings .=  "tree.path_string <> '$contentObjectTreeNode->PathString'";
}
$path_strings_where = $path_strings2.") ";
$path_strings .= ") and ( $except_path_strings ) ";

// Select all elements having reverse relations. And ignore those items that don't relate to objects other than being removed.
$rows = $db->arrayQuery( "SELECT DISTINCT( tree.node_id )
                             FROM  ezcontentobject_tree tree,  ezcontentobject obj,
                                   ezcontentobject_link link LEFT JOIN ezcontentobject_tree tree2
                                   ON link.from_contentobject_id = tree2.contentobject_id

                             WHERE $path_strings

                                   and link.to_contentobject_id = tree.contentobject_id
                                   and obj.id = link.from_contentobject_id
                                   and obj.current_version = link.from_contentobject_version
                                   and not ( $path_strings_where )

                            ", array( 'limit' => $pageLimit,
                                      'offset' => $Offset ) );

// Total count of sub items
$countOfItems = $db->arrayQuery( "SELECT COUNT( DISTINCT( tree.node_id ) ) count

                                  FROM  ezcontentobject_tree tree,  ezcontentobject obj,
                                        ezcontentobject_link link LEFT JOIN ezcontentobject_tree tree2
                                        ON link.from_contentobject_id = tree2.contentobject_id
                                  WHERE $path_strings
                                        and link.to_contentobject_id = tree.contentobject_id
                                        and obj.id = link.from_contentobject_id
                                        and obj.current_version = link.from_contentobject_version
                                        and not ( $path_strings_where )
                            " );
$rowsCount = 0;
if ( isset( $countOfItems[0] ) )
    $rowsCount = $countOfItems[0]['count'];

$childrenList = array(); // Contains children of Nodes from $deleteIDArray

// Fetch number of reverse related objects for each of the items being removed.
foreach( $rows as $child )
{
    $contentObject = eZContentObject::fetchByNodeID( $child['node_id'] );
    $contentObject_ID = $contentObject->attribute('id');
    $reverseObjectCount = $contentObject->reverseRelatedObjectCount( false, false, false );
    $reverselistCountChildrenArray[$contentObject_ID] = $reverseObjectCount;
    $totalReverseRelationsChildrenCount += $reverseObjectCount;
    $childrenList[] = eZContentObjectTreeNode::fetch( $child['node_id'] );
}

$tpl =& templateInit();

$viewParameters = array( 'offset' => $Offset );

$tpl->setVariable( "module", $Module );
$tpl->setVariable( 'moveToTrashAllowed', $moveToTrashAllowed ); // Backwards compatability
$tpl->setVariable( "ChildObjectsCount", $totalChildCount ); // Backwards compatability
$tpl->setVariable( "DeleteResult",  $deleteResult ); // Backwards compatability
$tpl->setVariable( 'move_to_trash_allowed', $moveToTrashAllowed );
$tpl->setVariable( "remove_list",  $deleteResult );
$tpl->setVariable( 'total_child_count', $totalChildCount );
$tpl->setVariable( 'remove_info', $info );
$tpl->setVariable( 'reverse_list_count_array', $reverselistCountArray );
$tpl->setVariable( 'total_reverse_relations_count', $totalReverseRelationsCount );

$tpl->setVariable( 'children_list', $childrenList );

$tpl->setVariable( 'reverse_list_count_children_array',  $reverselistCountChildrenArray );
$tpl->setVariable( 'reverse_list_count_children_array_count', count( $reverselistCountChildrenArray ) );

$tpl->setVariable( 'children_count', $rowsCount );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:node/removeobject.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/content', 'Remove object' ) ) );
?>

<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

function checkNodeCorrectness( &$module, $objectID, $editVersion )
{
    $object =& eZContentObject::fetch( $objectID );
    if ( $object === null )
        return;
    if ( $object->attribute( 'main_node_id' ) == 0 )
    {
//        $module->setViewResult( $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' ) );
//        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }
}

function checkNodeAssignments( &$module, &$class, &$object, &$version, &$contentObjectAttributes )
{
    $http =& eZHTTPTool::instance();
    $ObjectID = $object->attribute( 'id' );
    // Assign to nodes
    if ( $module->isCurrentAction( 'AddNodeAssignment' ) )
    {
        $selectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
        $assignedNodes =& $version->nodeAssignments();
        $assignedIDArray =array();
        foreach ( $assignedNodes as  $assignedNode )
        {
            $assignedNodeID = $assignedNode->attribute( 'parent_node' );
            $assignedIDArray[] = $assignedNodeID;
        }

        foreach ( $selectedNodeIDArray as $nodeID )
        {
            if ( !in_array( $nodeID, $assignedIDArray ) )
            {
                $version->assignToNode( $nodeID );
            }
        }
    }
}

function checkNodeMovements( &$module, &$class, &$object, &$version, &$contentObjectAttributes )
{
    $http =& eZHTTPTool::instance();
    $ObjectID = $object->attribute( 'id' );
    // Move to another node
    if ( $module->isCurrentAction( 'MoveNodeAssignment' ) )
    {
        $selectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
        $fromNodeID = $http->sessionVariable( "FromNodeID" );
        if ( $selectedNodeIDArray != null )
        {
            $assignedNodes =& $version->nodeAssignments();
            $assignedIDArray =array();
            foreach ( $assignedNodes as  $assignedNode )
            {
                $assignedNodeID = $assignedNode->attribute( 'parent_node' );
                $assignedIDArray[] = $assignedNodeID;
            }
            foreach ( $selectedNodeIDArray as $nodeID )
            {
                if ( !in_array( $nodeID, $assignedIDArray ) )
                {
                    $version->assignToNode( $nodeID, 0, $fromNodeID );
                }
            }
            $version->removeAssignment( $fromNodeID );
            // $version->removeAssignment( $fromNodeID );
            /* $childrens =& eZContentObjectTreeNode::subTree( null, $fromNodeID );
            foreach ( $childrens as $children )
            {
                $contentObjectID = $children->attribute('contentobject_id');
                if ( $contentObjectID != $ObjectID )
                {
                    $childObject =& eZContentObject::fetch( $contentObjectID );
                    $childVersion =& $childObject->currentVersion();
                    foreach ( $selectedNodeIDArray as $nodeID )
                    {
                        $childVersion->assignToNode( $nodeID );
                    }
                }
            }*/
        }
    }
}

function storeNodeAssignments( &$module, &$class, &$object, &$version, &$contentObjectAttributes )
{
    $http =& eZHTTPTool::instance();
    $mainNodeID = $http->postVariable( 'MainNodeID' );
    $nodesID = array();
    if ( $http->hasPostVariable( 'NodesID' ) )
        $nodesID = $http->postVariable( 'NodesID' );

    $nodeID = eZContentObjectTreeNode::findNode( $mainNodeID, $object->attribute('id') );
    eZDebug::writeNotice( $nodeID, 'nodeID' );
//    $object->setAttribute( 'main_node_id', $nodeID );
    $nodeAssignments =& eZNodeAssignment::fetchForObject( $object->attribute( 'id' ), $version->attribute( 'version' ) ) ;
    eZDebug::writeNotice( $mainNodeID, "mainNodeID" );

    $sortOrderMap = $http->postVariable( 'SortOrderMap' );
    $sortFieldMap = $http->postVariable( 'SortFieldMap' );
    $assigedNodes =& eZContentObjectTreeNode::fetchByContentObjectID( $object->attribute('id') );
    foreach ( array_keys( $nodeAssignments ) as $key )
    {
        $nodeAssignment =& $nodeAssignments[$key];
        eZDebug::writeNotice( $nodeAssignment, "nodeAssignment" );
        $nodeAssignment->setAttribute( 'sort_field', $sortFieldMap[$nodeAssignment->attribute( 'id' )] );
        $sortOrder = 0;
        if ( $sortOrderMap[$nodeAssignment->attribute( 'id' )] == 1 )
            $sortOrder = 1;
        $nodeAssignment->setAttribute( 'sort_order', $sortOrder );
        if ( $nodeAssignment->attribute( 'main' ) == 1 && $nodeAssignment->attribute( 'parent_node' ) != $mainNodeID )
        {
            $nodeAssignment->setAttribute( 'main', 0 );
        }
        elseif ( $nodeAssignment->attribute( 'main' ) == 0 && $nodeAssignment->attribute( 'parent_node' ) == $mainNodeID )
        {
            $nodeAssignment->setAttribute( 'main', 1 );
        }
        $nodeAssignment->store();
    }
//    $version->setAttribute( 'parent_node', $mainNodeID );
//    $version->store();
//         $object->store();

//    $node = eZContentObjectTreeNode::fetch( $nodeID );

//    $node->setAttribute( 'path_identification_string', $node->pathWithNames() );
//    $node->setAttribute( 'crc32_path', crc32 ( $node->attribute( 'path_identification_string' ) ) );
//    eZDebug::writeNotice( $node->attribute( 'path_identification_string' ), 'path_identification_string' );
//    eZDebug::writeNotice( $node->attribute( 'crc32_path' ), 'CRC32' );

//    $node->store();
}

function checkNodeActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion )
{
    $http =& eZHTTPTool::instance();
    if ( $module->isCurrentAction( 'BrowseForNodes' ) )
    {
        $objectID = $object->attribute( 'id' );
//         $http->setSessionVariable( 'BrowseFromPage', "/content/edit/$objectID/$editVersion/" );
        $http->setSessionVariable( 'BrowseFromPage', $module->redirectionURI( 'content', 'edit', array( $objectID, $editVersion ) ) );
        $http->setSessionVariable( 'BrowseActionName', 'AddNodeAssignment' );
        $http->setSessionVariable( 'BrowseReturnType', 'NodeID' );
        $mainParentID = $version->attribute( 'main_parent_node_id' );
        $node = eZContentObjectTreeNode::fetch( $mainParentID );
        $nodePath =  $node->attribute( 'path' );
        $rootNodeForObject = $nodePath[0];
        if ( $rootNodeForObject != null )
        {
            $nodeID = $rootNodeForObject->attribute( 'node_id' );
        }else
        {
            $nodeID = $mainParentID;
        }
        $module->redirectToView( 'browse', array( $nodeID, $objectID, $editVersion ) );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }
    if ( $module->isCurrentAction( 'DeleteNode' ) )
    {
        if ( $http->hasPostVariable( 'DeleteParentIDArray' ) )
        {
            $nodesID = $http->postVariable( 'DeleteParentIDArray' );
        }
        else
        {
            $nodesID = array();
        }
        $mainNodeID = $http->postVariable( 'MainNodeID' );
        foreach ( $nodesID as $node )
        {
            if ( $node != $mainNodeID )
            {
//                eZContentObjectTreeNode::deleteNodeWhereParent( $node, $objectID );
                $version->removeAssignment( $node );
            }
        }

    }
    if ( $module->isCurrentAction( 'MoveNode' ) )
    {
        $objectID = $object->attribute( 'id' );
        if ( $http->hasPostVariable( 'DeleteParentIDArray' ) )
        {
            $sourceNodeID = $http->postVariable( 'DeleteParentIDArray' );
        }
        $fromNodeID = $sourceNodeID[0];

        $http->setSessionVariable( 'BrowseFromPage', $module->redirectionURI( 'content', 'edit', array( $objectID, $editVersion ) ) );
        $http->setSessionVariable( 'BrowseActionName', 'MoveNodeAssignment' );
         $http->setSessionVariable( 'FromNodeID', $fromNodeID );
        $http->setSessionVariable( 'BrowseReturnType', 'NodeID' );
        $mainParentID = $version->attribute( 'main_parent_node_id' );
         eZDebug::writeDebug($mainParentID,"WWWWWWWWWWWW");
        $node = eZContentObjectTreeNode::fetch( $mainParentID );
          eZDebug::writeDebug($node,"WWWWWWWWWWWW");
        $nodePath =  $node->attribute( 'path' );
        $rootNodeForObject = $nodePath[0];
        if ( $rootNodeForObject != null )
        {
            $nodeID = $rootNodeForObject->attribute( 'node_id' );
        }else
        {
            $nodeID = $mainParentID;
        }
        $module->redirectToView( 'browse', array( $nodeID, $objectID, $editVersion, $fromNodeID ) );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }
}

function handleNodeTemplate( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, &$tpl )
{
//$nodes =& eZContentObjectTreeNode::fetchList( true, $object->attribute( 'id' ) );
    $assignedNodeArray =& $version->attribute( 'parent_nodes' );

    // just for debug should be removed
//    $publishedNodeList
//         $assignedNodeArray  =& $object->parentNodes( $editVersion  );
    $mainParentNodeID = $version->attribute( 'main_parent_node_id' );
    $tpl->setVariable( 'assigned_node_array', $assignedNodeArray );
    $tpl->setVariable( 'main_node_id', $mainParentNodeID );
}

function initializeNodeEdit( &$module )
{
    $module->addHook( 'pre_fetch', 'checkNodeCorrectness', -10 );
    $module->addHook( 'post_fetch', 'checkNodeAssignments' );
    $module->addHook( 'post_fetch', 'checkNodeMovements' );
    $module->addHook( 'pre_commit', 'storeNodeAssignments' );
    $module->addHook( 'action_check', 'checkNodeActions' );
    $module->addHook( 'pre_template', 'handleNodeTemplate' );
}

?>

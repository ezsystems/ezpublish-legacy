<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
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

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

function checkNodeAssignments( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage )
{
    $http =& eZHTTPTool::instance();
    $ObjectID = $object->attribute( 'id' );
    // Assign to nodes
    if ( $module->isCurrentAction( 'AddNodeAssignment' ) )
    {
        $selectedNodeIDArray = eZContentBrowse::result( 'AddNodeAssignment' );
        $assignedNodes =& $version->nodeAssignments();
        $assignedIDArray = array();
        $setMainNode = false;
        $hasMainNode = false;
        foreach ( $assignedNodes as $assignedNode )
        {
            $assignedNodeID = $assignedNode->attribute( 'parent_node' );
            if ( $assignedNode->attribute( 'is_main' ) )
                $hasMainNode = true;
            $assignedIDArray[] = $assignedNodeID;
        }
        if ( !$hasMainNode )
            $setMainNode = true;

        foreach ( $selectedNodeIDArray as $nodeID )
        {
            if ( !in_array( $nodeID, $assignedIDArray ) )
            {
                $isPermitted = true;
                // Check access
                $newNode =& eZContentObjectTreeNode::fetch( $nodeID );
                $newNodeObject = $newNode->attribute( 'object' );

//                 $canCreate = $newNodeObject->attribute( 'can_create' );
                $canCreate = $newNodeObject->checkAccess( 'create', $class->attribute( 'id' ), $newNodeObject->attribute( 'contentclass_id' ) ) == 1;
                if ( !$canCreate )
                    $isPermitted = false;
                else
                {
                    $canCreateClassList = $newNodeObject->attribute( 'can_create_class_list' );
                    $objectClassID = $object->attribute( 'contentclass_id' );
                    $canCreateClassIDList = array();
                    foreach ( array_keys( $canCreateClassList ) as $key )
                    {
                        $canCreateClassIDList[] = $canCreateClassList[$key]['id'];
                    }
                    if ( !in_array( $objectClassID, $canCreateClassIDList ) )
                        $isPermitted = false;
                }
                if ( !$isPermitted )
                {
                    // Error message.
                }
                else
                {
                    $isMain = 0;
                    if ( $setMainNode )
                        $isMain = 1;
                    $setMainNode = false;
                    $version->assignToNode( $nodeID, $isMain );
                }
            }
        }
    }
}

function checkNodeMovements( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage )
{
    $http =& eZHTTPTool::instance();
    $ObjectID = $object->attribute( 'id' );
    // Move to another node
    if ( $module->isCurrentAction( 'MoveNodeAssignment' ) )
    {
        $selectedNodeIDArray = eZContentBrowse::result( 'MoveNodeAssignment' );
        $fromNodeID = $http->postVariable( "FromNodeID" );
        $oldAssignmentParentID = $http->postVariable( 'OldAssignmentParentID' );

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
                    $isPermitted = true;
                    // Check access
                    $newNode =& eZContentObjectTreeNode::fetch( $nodeID );
                    $newNodeObject = $newNode->attribute( 'object' );

//                     $canCreate = $newNodeObject->attribute( 'can_create' );
                    $canCreate = $newNodeObject->checkAccess( 'create', $class->attribute( 'id' ), $newNodeObject->attribute( 'contentclass_id' ) ) == 1;
                    eZDebug::writeDebug( $canCreate,"can create");
                    if ( !$canCreate )
                        $isPermitted = false;
                    else
                    {
                        $canCreateClassList = $newNodeObject->attribute( 'can_create_class_list' );
                        $canCreateClassIDList = array();
                        foreach ( array_keys( $canCreateClassList ) as $key )
                        {
                            $canCreateClassIDList[] = $canCreateClassList[$key]['id'];
                        }
                        $objectClassID = $object->attribute( 'contentclass_id' );
                        if ( !in_array( $objectClassID, $canCreateClassIDList ) )
                             $isPermitted = false;
                    }
                    if ( !$isPermitted )
                    {
                        // Error message.
                    }
                    else
                    {
                        $oldAssignment =& eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                                           null,
                                                                           array( 'contentobject_id' => $object->attribute( 'id' ),
                                                                                  'parent_node' => $oldAssignmentParentID,
                                                                                  'contentobject_version' => $version->attribute( 'version' )
                                                                                  ),
                                                                           true );

                        $realNode =& eZContentObjectTreeNode::fetchNode( $version->attribute( 'contentobject_id' ), $oldAssignment->attribute( 'parent_node' ) );

                        if ( is_null( $realNode ) )
                        {
                            $fromNodeID = 0;
                        }
                        if ( $oldAssignment->attribute( 'is_main' ) == '1' )
                        {
                            $version->assignToNode( $nodeID, 1, $fromNodeID, $oldAssignment->attribute( 'sort_field' ), $oldAssignment->attribute( 'sort_order' ) );
                        }
                        else
                        {
                            $version->assignToNode( $nodeID, 0, $fromNodeID, $oldAssignment->attribute( 'sort_field' ), $oldAssignment->attribute( 'sort_order' ) );
                        }
                        $version->removeAssignment( $oldAssignmentParentID );
                    }
                }
            }
        }
    }
}

function storeNodeAssignments( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage )
{
    $http =& eZHTTPTool::instance();

    $setPlacementNodeIDArray = array();
    if ( $http->hasPostVariable( 'SetPlacementNodeIDArray' ) )
        $setPlacementNodeIDArray = $http->postVariable( 'SetPlacementNodeIDArray' );

    if ( $http->hasPostVariable( 'MainNodeID' ) )
        $mainNodeID = $http->postVariable( 'MainNodeID' );
    // Check if dropdown placement is used
    if ( $http->hasPostVariable( 'MainAssignmentElementNumber' ) )
    {
        $elementNumber = $http->postVariable( 'MainAssignmentElementNumber' );

        $mainNodeID = $setPlacementNodeIDArray[$elementNumber];
    }

    $nodesID = array();
    if ( $http->hasPostVariable( 'NodesID' ) )
        $nodesID = $http->postVariable( 'NodesID' );

    $nodeID = eZContentObjectTreeNode::findNode( $mainNodeID, $object->attribute('id') );
    eZDebugSetting::writeDebug( 'kernel-content-edit', $nodeID, 'nodeID' );
//    $object->setAttribute( 'main_node_id', $nodeID );
    $nodeAssignments =& eZNodeAssignment::fetchForObject( $object->attribute( 'id' ), $version->attribute( 'version' ) ) ;
    eZDebugSetting::writeDebug( 'kernel-content-edit', $mainNodeID, "mainNodeID" );


    $setPlacementNodeIDArray = array_unique( $setPlacementNodeIDArray );
    eZDebugSetting::writeDebug( 'kernel-content-edit', $setPlacementNodeIDArray, '$setPlacementNodeIDArray' );
    $remoteIDFieldMap = array();
    if ( $http->hasPostVariable( 'SetRemoteIDFieldMap' ) )
        $remoteIDFieldMap = $http->postVariable( 'SetRemoteIDFieldMap' );
    $remoteIDOrderMap = array();
    if ( $http->hasPostVariable( 'SetRemoteIDOrderMap' ) )
        $remoteIDOrderMap = $http->postVariable( 'SetRemoteIDOrderMap' );
    if ( count( $setPlacementNodeIDArray ) > 0 )
    {
        foreach ( $setPlacementNodeIDArray as $setPlacementRemoteID => $setPlacementNodeID )
        {
            $hasAssignment = false;
            foreach ( array_keys( $nodeAssignments ) as $key )
            {
                $nodeAssignment =& $nodeAssignments[$key];
                if ( $nodeAssignment->attribute( 'remote_id' ) == $setPlacementRemoteID )
                {
                    eZDebugSetting::writeDebug( 'kernel-content-edit', "Remote ID $setPlacementRemoteID already in use for node " . $nodeAssignment->attribute( 'parent_node' ), 'node_edit' );
                    if ( isset( $remoteIDFieldMap[$setPlacementRemoteID] ) )
                        $nodeAssignment->setAttribute( 'sort_field',  $remoteIDFieldMap[$setPlacementRemoteID] );
                    if ( isset( $remoteIDOrderMap[$setPlacementRemoteID] ) )
                        $nodeAssignment->setAttribute( 'sort_order', $remoteIDOrderMap[$setPlacementRemoteID] );
                    $nodeAssignment->setAttribute( 'parent_node', $setPlacementNodeID );
                    $nodeAssignment->sync();
                    $hasAssignment = true;
                    break;
                }
            }
            if ( !$hasAssignment )
            {
                eZDebugSetting::writeDebug( 'kernel-content-edit', "Adding to node $setPlacementNodeID", 'node_edit' );
                $sortField = null;
                $sortOrder = null;
                if ( isset( $remoteIDFieldMap[$setPlacementRemoteID] ) )
                    $sortField = $remoteIDFieldMap[$setPlacementRemoteID];
                if ( isset( $remoteIDOrderMap[$setPlacementRemoteID] ) )
                    $sortOrder = $remoteIDOrderMap[$setPlacementRemoteID];
                $version->assignToNode( $setPlacementNodeID, 0, 0, $sortField, $sortOrder, $setPlacementRemoteID );
            }
        }
        $nodeAssignments =& eZNodeAssignment::fetchForObject( $object->attribute( 'id' ), $version->attribute( 'version' ) );
    }

    $sortOrderMap = false;
    if ( $http->hasPostVariable( 'SortOrderMap' ) )
        $sortOrderMap = $http->postVariable( 'SortOrderMap' );
    $sortFieldMap = false;
    if ( $http->hasPostVariable( 'SortFieldMap' ) )
        $sortFieldMap = $http->postVariable( 'SortFieldMap' );

//     $assigedNodes =& eZContentObjectTreeNode::fetchByContentObjectID( $object->attribute('id') );
    foreach ( array_keys( $nodeAssignments ) as $key )
    {
        $nodeAssignment =& $nodeAssignments[$key];
        eZDebugSetting::writeDebug( 'kernel-content-edit', $nodeAssignment, "nodeAssignment" );
        if ( $sortFieldMap !== false )
        {
            if ( isset( $sortFieldMap[$nodeAssignment->attribute( 'id' )] ) )
                $nodeAssignment->setAttribute( 'sort_field', $sortFieldMap[$nodeAssignment->attribute( 'id' )] );
        }

        if ( $sortOrderMap !== false )
        {
            $sortOrder = 1;
            if ( isset( $sortOrderMap[$nodeAssignment->attribute( 'id' )] ) and
                 $sortOrderMap[$nodeAssignment->attribute( 'id' )] == 1 )
                $sortOrder = $sortOrderMap[$nodeAssignment->attribute( 'id' )];
            else
                $sortOrder = 0;

            $nodeAssignment->setAttribute( 'sort_order', $sortOrder );
        }


        if ( $nodeAssignment->attribute( 'is_main' ) == 1 and
             $nodeAssignment->attribute( 'parent_node' ) != $mainNodeID )
        {
            $nodeAssignment->setAttribute( 'is_main', 0 );
        }
        else if ( $nodeAssignment->attribute( 'is_main' ) == 0 and
                  $nodeAssignment->attribute( 'parent_node' ) == $mainNodeID )
        {
            $nodeAssignment->setAttribute( 'is_main', 1 );
        }
        $nodeAssignment->store();
    }
}

function checkNodeActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage )
{
    $http =& eZHTTPTool::instance();

    // Remove custom actions from attribute editing.
    $http->removeSessionVariable( 'BrowseCustomAction' );

    if ( $module->isCurrentAction( 'ConfirmAssignmentDelete' ) && $http->hasPostVariable( 'RemoveNodeID' ) )
    {

        $nodeID = $http->postVariable( 'RemoveNodeID' );
        $version->removeAssignment( $nodeID );
    }

    if ( $module->isCurrentAction( 'BrowseForNodes' ) )
    {
        $ignoreNodesSelect = array();
        $ignoreNodesClick  = array();
        $assigned = $version->nodeAssignments();
        $publishedAssigned = $object->assignedNodes( false );
        $isTopLevel = false;
        foreach ( $publishedAssigned as $element )
        {
            $append = false;
            if ( $element['parent_node_id'] == 1 )
                $isTopLevel = true;
            foreach ( $assigned as $ass )
            {
                if ( $ass->attribute( 'parent_node' ) == $element['parent_node_id'] )
                {
                    $append = true;
                }
            }
            if ( $append )
            {
                $ignoreNodesSelect[] = $element['node_id'];
                $ignoreNodesClick[]  = $element['node_id'];
                $ignoreNodesSelect[] = $element['parent_node_id'];
            }
        }
        if ( !$isTopLevel )
        {
            $ignoreNodesSelect = array_unique( $ignoreNodesSelect );
            $objectID = $object->attribute( 'id' );
            eZContentBrowse::browse( array( 'action_name' => 'AddNodeAssignment',
                                            'description_template' => 'design:content/browse_placement.tpl',
                                            'keys' => array( 'class' => $class->attribute( 'id' ),
                                                             'class_id' => $class->attribute( 'identifier' ),
                                                             'classgroup' => $class->attribute( 'ingroup_id_list' ),
                                                             'section' => $object->attribute( 'section_id' ) ),
                                            'ignore_nodes_select' => $ignoreNodesSelect,
                                            'ignore_nodes_click'  => $ignoreNodesClick,
                                            'content' => array( 'object_id' => $objectID,
                                                                'object_version' => $editVersion,
                                                                'object_language' => $editLanguage ),
                                            'from_page' => "/content/edit/$objectID/$editVersion/$editLanguage" ),
                                     $module );

            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }
    }
    if ( $module->isCurrentAction( 'DeleteNode' ) )
    {

        if ( $http->hasPostVariable( 'RemoveNodeID' ) )
        {
            $nodeID = $http->postVariable( 'RemoveNodeID' );
        }

        $mainNodeID = $http->postVariable( 'MainNodeID' );

//         if ( $nodeID != $mainNodeID )
        {
            $objectID = $object->attribute( 'id' );
            $publishedNode =& eZContentObjectTreeNode::fetchNode( $objectID, $nodeID );
            if ( $publishedNode != null )
            {
                $publishParentNodeID = $publishedNode->attribute( 'parent_node_id' );
                if ( $publishParentNodeID > 1 )
                {
                    $childrenCount =& $publishedNode->childrenCount();
                    if ( $childrenCount != 0 )
                    {
                        $module->redirectToView( 'removenode', array( $objectID, $editVersion, $editLanguage, $nodeID ) );
                        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
                    }
                    else
                    {
                        $version->removeAssignment( $nodeID );

                    }
                }
            }
            else
            {
                $nodeAssignment =& eZNodeAssignment::fetch( $objectID, $version->attribute( 'version' ), $nodeID );
                if ( $nodeAssignment->attribute( 'from_node_id' ) != 0 )
                {
                    $publishedNode =& eZContentObjectTreeNode::fetchNode( $objectID, $nodeAssignment->attribute( 'from_node_id' ) );
                    $childrenCount = 0;
                    if ( $publishedNode !== null )
                        $childrenCount =& $publishedNode->childrenCount();
                    if ( $childrenCount != 0 )
                    {
                        $module->redirectToView( 'removenode', array( $objectID, $editVersion, $editLanguage, $nodeID ) );
                        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
                    }
                }
                $version->removeAssignment( $nodeID );
            }
        }
    }

    if ( $module->isCurrentAction( 'MoveNode' ) )
    {
        $objectID = $object->attribute( 'id' );
        if ( $http->hasPostVariable( 'MoveNodeID' ) )
        {
            $fromNodeID = $http->postVariable( 'MoveNodeID' ); //$sourceNodeID[0];
            $oldAssignmentParentID = $fromNodeID;
            $fromNodeAssignment =& eZNodeAssignment::fetch( $objectID, $version->attribute( 'version' ), $fromNodeID );
            $publishParentNodeID = $fromNodeAssignment->attribute( 'parent_node' );
            if ( $publishParentNodeID > 1 )
            {
                if( $fromNodeAssignment->attribute( 'from_node_id' ) != 0 )
                {
                    $fromNodeID = $fromNodeAssignment->attribute( 'from_node_id' );
                    $oldAssignmentParentID = $fromNodeAssignment->attribute( 'parent_node' );
                }

                // we don't allow moving object to itself, to its descendants or parent object(s)
                $objectAssignedNodes =& $object->attribute( 'assigned_nodes' );

                // nodes that are not allowed to select (via checkbox or radiobutton) when browsing
                $ignoreNodesSelectArray = array();

                // nodes that are not allowed to click on
                $ignoreNodesClickArray  = array();
                foreach( $objectAssignedNodes as $curAN )
                {
                    // current node should be neihter selectable, nor clickable
                    $ignoreNodesClickArray[]  = $curAN->NodeID;
                    $ignoreNodesSelectArray[] = $curAN->NodeID;

                    // parent node should be only clickable, but not selectable
                    $ignoreNodesSelectArray[] = $curAN->ParentNodeID;
                }

                eZContentBrowse::browse( array( 'action_name' => 'MoveNodeAssignment',
                                                'description_template' => 'design:content/browse_move_placement.tpl',
                                                'keys' => array( 'class' => $class->attribute( 'id' ),
                                                                 'class_id' => $class->attribute( 'identifier' ),
                                                                 'classgroup' => $class->attribute( 'ingroup_id_list' ),
                                                                 'section' => $object->attribute( 'section_id' ) ),
                                                'start_node' => $fromNodeID,
                                                'persistent_data' => array( 'FromNodeID' => $fromNodeID,
                                                                            'OldAssignmentParentID' => $oldAssignmentParentID ),
						'ignore_nodes_select' => $ignoreNodesSelectArray,
						'ignore_nodes_click'  => $ignoreNodesClickArray,
                                                'content' => array( 'object_id' => $objectID,
                                                                    'previous_node_id' => $fromNodeID,
                                                                    'object_version' => $editVersion,
                                                                    'object_language' => $editLanguage ),
                                                'from_page' => "/content/edit/$objectID/$editVersion/$editLanguage" ),
                                         $module );

                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
        }
    }
}

function handleNodeTemplate( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage, &$tpl )
{
    $assignedNodeArray =& $version->attribute( 'parent_nodes' );
    eZDebugSetting::writeDebug( 'kernel-content-edit', $assignedNodeArray, "assigned nodes array" );
    $remoteMap = array();
    foreach ( array_keys( $assignedNodeArray ) as $assignedNodeKey )
    {
        $assignedNode =& $assignedNodeArray[$assignedNodeKey];
        $node =& $assignedNode->getParentNode();
        if ( $node !== null )
        {
            $remoteID = $assignedNode->attribute( 'remote_id' );
            if ( $remoteID > 0 )
            {
                if ( isset( $remoteMap[$remoteID] ) )
                {
                    if ( is_array( $remoteMap[$remoteID] ) )
                        $remoteMap[$remoteID][] =& $assignedNode;
                    else
                    {
                        $currentRemote =& $remoteMap[$remoteID];
                        unset( $remoteMap[$remoteID] );
                        $remoteMap[$remoteID] = array();
                        $remoteMap[$remoteID][] =& $currentRemote;
                        $remoteMap[$remoteID][] =& $assignedNode;
                    }
                }
                else
                    $remoteMap[$remoteID] =& $assignedNode;
            }
        }
        else
        {
            $assignedNode->remove();
            unset( $assignedNodeArray[$assignedNodeKey] );
        }
    }
    eZDebugSetting::writeDebug( 'kernel-content-edit', $assignedNodeArray, "assigned nodes array" );

    $currentVersion =& $object->version( $editVersion );
    $publishedNodeArray = array();
    if ( $currentVersion !== null )
        $publishedNodeArray =& $currentVersion->attribute( 'parent_nodes' );
    $mainParentNodeID = $version->attribute( 'main_parent_node_id' );
    $tpl->setVariable( 'assigned_node_array', $assignedNodeArray );
    $tpl->setVariable( 'assigned_remote_map', $remoteMap );
    $tpl->setVariable( 'published_node_array', $publishedNodeArray );
    $tpl->setVariable( 'main_node_id', $mainParentNodeID );
}

function initializeNodeEdit( &$module )
{
    $module->addHook( 'post_fetch', 'checkNodeAssignments' );
    $module->addHook( 'post_fetch', 'checkNodeMovements' );
    $module->addHook( 'pre_commit', 'storeNodeAssignments' );
    $module->addHook( 'action_check', 'checkNodeActions' );
    $module->addHook( 'pre_template', 'handleNodeTemplate' );
}

?>

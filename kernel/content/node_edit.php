<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
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

require_once( 'kernel/common/template.php' );


function checkNodeAssignments( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $FromLanguage, &$validation )
{
    $http = eZHTTPTool::instance();

    // If the object has been previously published we do not allow node assignment operations
    if ( $object->attribute( 'status' ) != eZContentObject::STATUS_DRAFT )
    {
        if ( !$module->isCurrentAction( 'AddPrimaryNodeAssignment' ) )
        {
            return;
        }
    }

    // If node assignment handling is diabled we return immedieately
    $useNodeAssigments = true;
    if ( $http->hasPostVariable( 'UseNodeAssigments' ) )
        $useNodeAssigments = (bool)$http->postVariable( 'UseNodeAssigments' );

    if ( !$useNodeAssigments )
        return;

    $ObjectID = $object->attribute( 'id' );
    // Assign to nodes
    if ( $module->isCurrentAction( 'AddNodeAssignment' ) ||
         $module->isCurrentAction( 'AddPrimaryNodeAssignment' ) )
    {
        $selectedNodeIDArray = eZContentBrowse::result( 'AddNodeAssignment' );
        $assignedNodes = $version->nodeAssignments();
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

        // prevent PHP warning
        if ( !isset( $selectedNodeIDArray ) || !is_array( $selectedNodeIDArray ) )
             $selectedNodeIDArray = array();

        foreach ( $selectedNodeIDArray as $nodeID )
        {
            if ( !in_array( $nodeID, $assignedIDArray ) )
            {
                $isPermitted = true;
                // Check access
                $newNode = eZContentObjectTreeNode::fetch( $nodeID );
                $newNodeObject = $newNode->attribute( 'object' );

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
                    eZDebug::writeError( $newNode->pathWithNames(), "You are not allowed to place this object under:" );
                    $validation[ 'placement' ][] = array( 'text' => eZi18n::translate( 'kernel/content', 'You are not allowed to place this object under: %1', null, array( $newNode->pathWithNames() ) ) );
                    $validation[ 'processed' ] = true;
                    // Error message.
                }
                else
                {
                    $isMain = 0;
                    if ( $setMainNode )
                        $isMain = 1;
                    $setMainNode = false;
                    $db = eZDB::instance();
                    $db->begin();
                    $version->assignToNode( $nodeID, $isMain );
                    $db->commit();
                }
            }
        }
    }
}

function checkNodeMovements( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $FromLanguage, &$validation )
{
    $http = eZHTTPTool::instance();

    // If the object has been previously published we do not allow node assignment operations
    if ( $object->attribute( 'status' ) != eZContentObject::STATUS_DRAFT )
    {
        return;
    }

    // If node assignment handling is diabled we return immedieately
    $useNodeAssigments = true;
    if ( $http->hasPostVariable( 'UseNodeAssigments' ) )
        $useNodeAssigments = (bool)$http->postVariable( 'UseNodeAssigments' );

    if ( !$useNodeAssigments )
        return;

    $ObjectID = $object->attribute( 'id' );
    // Move to another node
    if ( $module->isCurrentAction( 'MoveNodeAssignment' ) )
    {
        $selectedNodeIDArray = eZContentBrowse::result( 'MoveNodeAssignment' );
        $fromNodeID = $http->postVariable( "FromNodeID" );
        $oldAssignmentParentID = $http->postVariable( 'OldAssignmentParentID' );

        if ( $selectedNodeIDArray != null )
        {
            $assignedNodes = $version->nodeAssignments();
            $assignedIDArray = array();
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
                    $newNode = eZContentObjectTreeNode::fetch( $nodeID );
                    $newNodeObject = $newNode->attribute( 'object' );

                    $canCreate = $newNodeObject->checkAccess( 'create', $class->attribute( 'id' ), $newNodeObject->attribute( 'contentclass_id' ) ) == 1;
                    eZDebug::writeDebug( $canCreate,"can create");
                    if ( !$canCreate )
                    {
                        $isPermitted = false;
                    }
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
                        eZDebug::writeError( $newNode->pathWithNames(), "You are not allowed to place this object under:" );
                        $validation[ 'placement' ][] = array( 'text' => eZi18n::translate( 'kernel/content', "You are not allowed to place this object under: %1", null, array( $newNode->pathWithNames() ) ) );
                        $validation[ 'processed' ] = true;
                        // Error message.
                    }
                    else
                    {
                        $oldAssignment = eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                                           null,
                                                                           array( 'contentobject_id' => $object->attribute( 'id' ),
                                                                                  'parent_node' => $oldAssignmentParentID,
                                                                                  'contentobject_version' => $version->attribute( 'version' )
                                                                                  ),
                                                                           true );

                        $realNode = eZContentObjectTreeNode::fetchNode( $version->attribute( 'contentobject_id' ), $oldAssignment->attribute( 'parent_node' ) );

                        $db = eZDB::instance();
                        $db->begin();
                        // No longer remove then add assignment, instead change the existing one
                        if ( $realNode  === null )
                        {
                            $fromNodeID = 0;
                        }
                        if ( $oldAssignment->attribute( 'is_main' ) == '1' )
                        {
                            $oldAssignment->setAttribute( 'parent_node', $nodeID );
                            $oldAssignment->setAttribute( 'is_main', 1 );
                            $oldAssignment->setAttribute( 'from_node_id', $fromNodeID );
//                            $version->assignToNode( $nodeID, 1, $fromNodeID, $oldAssignment->attribute( 'sort_field' ), $oldAssignment->attribute( 'sort_order' ) );
                        }
                        else
                        {
                            $oldAssignment->setAttribute( 'parent_node', $nodeID );
                            $oldAssignment->setAttribute( 'is_main', 0 );
                            $oldAssignment->setAttribute( 'from_node_id', $fromNodeID );
//                            $version->assignToNode( $nodeID, 0, $fromNodeID, $oldAssignment->attribute( 'sort_field' ), $oldAssignment->attribute( 'sort_order' ) );
                        }
                        $oldAssignment->setAttribute( 'op_code', eZNodeAssignment::OP_CODE_MOVE );
                        $oldAssignment->store();
                        //$version->removeAssignment( $oldAssignmentParentID );
                        $db->commit();
                    }
                }
            }
        }
    }
}

function storeNodeAssignments( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage )
{
    $http = eZHTTPTool::instance();

    // If the object has been previously published we do not allow node assignment operations
    if ( $object->attribute( 'status' ) != eZContentObject::STATUS_DRAFT )
    {
        return;
    }

    // If node assignment handling is diabled we return immedieately
    $useNodeAssigments = true;
    if ( $http->hasPostVariable( 'UseNodeAssigments' ) )
        $useNodeAssigments = (bool)$http->postVariable( 'UseNodeAssigments' );

    if ( !$useNodeAssigments )
        return;

    $setPlacementNodeIDArray = array();
    if ( $http->hasPostVariable( 'SetPlacementNodeIDArray' ) )
        $setPlacementNodeIDArray = $http->postVariable( 'SetPlacementNodeIDArray' );

    // We will quit if some important POST variables are missing
    if ( !$http->hasPostVariable( 'MainNodeID' ) and
         !$http->hasPostVariable( 'SortOrderMap' ) and
         !$http->hasPostVariable( 'SortFieldMap' ) )
        return;

    $mainNodeID = false;
    if ( $http->hasPostVariable( 'MainNodeID' ) )
    {
        $mainNodeID = trim( $http->postVariable( 'MainNodeID' ) );
        if ( strlen( $mainNodeID ) == 0 )
            return;
    }

    // Check if dropdown placement is used
    if ( $http->hasPostVariable( 'MainAssignmentElementNumber' ) )
    {
        $elementNumber = $http->postVariable( 'MainAssignmentElementNumber' );

        $mainNodeID = $setPlacementNodeIDArray[$elementNumber];
    }

    $nodeID = eZContentObjectTreeNode::findNode( $mainNodeID, $object->attribute('id') );
    eZDebugSetting::writeDebug( 'kernel-content-edit', $nodeID, 'nodeID' );
//    $object->setAttribute( 'main_node_id', $nodeID );
    $nodeAssignments = eZNodeAssignment::fetchForObject( $object->attribute( 'id' ), $version->attribute( 'version' ) ) ;
    eZDebugSetting::writeDebug( 'kernel-content-edit', $mainNodeID, "mainNodeID" );


    $setPlacementNodeIDArray = array_unique( $setPlacementNodeIDArray );
    eZDebugSetting::writeDebug( 'kernel-content-edit', $setPlacementNodeIDArray, '$setPlacementNodeIDArray' );
    $remoteIDFieldMap = array();
    if ( $http->hasPostVariable( 'SetRemoteIDFieldMap' ) )
        $remoteIDFieldMap = $http->postVariable( 'SetRemoteIDFieldMap' );
    $remoteIDOrderMap = array();
    if ( $http->hasPostVariable( 'SetRemoteIDOrderMap' ) )
        $remoteIDOrderMap = $http->postVariable( 'SetRemoteIDOrderMap' );

    $db = eZDB::instance();
    $db->begin();
    if ( count( $setPlacementNodeIDArray ) > 0 )
    {
        foreach ( $setPlacementNodeIDArray as $setPlacementRemoteID => $setPlacementNodeID )
        {
            $hasAssignment = false;
            foreach ( $nodeAssignments as $nodeAssignment )
            {
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
        $nodeAssignments = eZNodeAssignment::fetchForObject( $object->attribute( 'id' ), $version->attribute( 'version' ) );
    }

    $sortOrderMap = false;
    if ( $http->hasPostVariable( 'SortOrderMap' ) )
        $sortOrderMap = $http->postVariable( 'SortOrderMap' );
    $sortFieldMap = false;
    if ( $http->hasPostVariable( 'SortFieldMap' ) )
        $sortFieldMap = $http->postVariable( 'SortFieldMap' );

//     $assigedNodes = eZContentObjectTreeNode::fetchByContentObjectID( $object->attribute('id') );
    foreach ( $nodeAssignments as $nodeAssignment )
    {
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
    $db->commit();
}

function checkNodeActions( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
{
    // If the object has been previously published we do not allow node assignment operations
    if ( $object->attribute( 'status' ) != eZContentObject::STATUS_DRAFT )
    {
        if ( !$module->isCurrentAction( 'BrowseForPrimaryNodes' ) )
        {
            return;
        }
    }

    $http = eZHTTPTool::instance();

    if ( $module->isCurrentAction( 'BrowseForNodes' ) ||
         $module->isCurrentAction( 'BrowseForPrimaryNodes' ) )
    {
        // Remove custom actions from attribute editing.
        $http->removeSessionVariable( 'BrowseCustomAction' );

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
                    $append = true;
            }

            /* If the current version (draft) has no assigned nodes then
             * we should disallow adding assignments under nodes
             * the previous published version is assignned to.
             * Thus we avoid fatal errors in eZ Publish.
             */
            if ( count($assigned) == 0 )
            {
                $ignoreNodesSelect[] = $element['node_id'];
                $ignoreNodesClick[]  = $element['node_id'];
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
            $action = 'AddNodeAssignment';
            if ( $module->isCurrentAction( 'BrowseForPrimaryNodes' ) )
            {
                $action = 'AddPrimaryNodeAssignment';
            }
            eZContentBrowse::browse( array( 'action_name' => $action,
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
                                            'from_page' => "/content/edit/$objectID/$editVersion/$editLanguage/$fromLanguage" ),
                                     $module );

            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }
    }

    // If node assignment handling is diabled we return
    $useNodeAssigments = true;
    if ( $http->hasPostVariable( 'UseNodeAssigments' ) )
        $useNodeAssigments = (bool)$http->postVariable( 'UseNodeAssigments' );

    if ( !$useNodeAssigments )
        return;

    // Remove custom actions from attribute editing.
    $http->removeSessionVariable( 'BrowseCustomAction' );

    if ( $module->isCurrentAction( 'ConfirmAssignmentDelete' ) && $http->hasPostVariable( 'RemoveNodeID' ) )
    {
        $nodeID = $http->postVariable( 'RemoveNodeID' );
        $db = eZDB::instance();
        $db->begin();
        $version->removeAssignment( $nodeID );
        $db->commit();
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
            $publishedNode = eZContentObjectTreeNode::fetchNode( $objectID, $nodeID );
            if ( $publishedNode != null )
            {
                $publishParentNodeID = $publishedNode->attribute( 'parent_node_id' );
                if ( $publishParentNodeID > 1 )
                {
                    $childrenCount = $publishedNode->childrenCount();
                    if ( $childrenCount != 0 )
                    {
                        $module->redirectToView( 'removenode', array( $objectID, $editVersion, $editLanguage, $nodeID ) );
                        return eZModule::HOOK_STATUS_CANCEL_RUN;
                    }
                    else
                    {
                        $db = eZDB::instance();
                        $db->begin();
                        $version->removeAssignment( $nodeID );
                        $db->commit();
                    }
                }
            }
            else
            {
                $nodeAssignment = eZNodeAssignment::fetch( $objectID, $version->attribute( 'version' ), $nodeID );
                if ( $nodeAssignment->attribute( 'from_node_id' ) != 0 )
                {
                    $publishedNode = eZContentObjectTreeNode::fetchNode( $objectID, $nodeAssignment->attribute( 'from_node_id' ) );
                    $childrenCount = 0;
                    if ( $publishedNode !== null )
                        $childrenCount = $publishedNode->childrenCount();
                    if ( $childrenCount != 0 )
                    {
                        $module->redirectToView( 'removenode', array( $objectID, $editVersion, $editLanguage, $nodeID ) );
                        return eZModule::HOOK_STATUS_CANCEL_RUN;
                    }
                }
                $db = eZDB::instance();
                $db->begin();
                $version->removeAssignment( $nodeID );
                $db->commit();
            }
        }
    }

    if ( $module->isCurrentAction( 'RemoveAssignments' ) )
    {
        if( $http->hasPostVariable( 'AssignmentIDSelection' ) )
        {
            $selected       = $http->postVariable( 'AssignmentIDSelection' );
            $objectID       = $object->attribute( 'id' );
            $versionInt     = $version->attribute( 'version' );
            $hasChildren    = false;
            $assignmentsIDs = array();
            $assignments    = array();

            // Determine if at least one node of ones we remove assignments for has children.
            foreach ( $selected as $parentNodeID )
            {
                $assignment = eZNodeAssignment::fetch( $objectID, $versionInt, $parentNodeID );
                if( !$assignment )
                {
                    eZDebug::writeWarning( "No assignment found for object $objectID version $versionInt, parent node $parentNodeID" );
                    continue;
                }

                $assignmentID     =  $assignment->attribute( 'id' );
                $assignmentsIDs[] =  $assignmentID;
                $assignments[]    = $assignment;
                $node             = $assignment->attribute( 'node' );

                if( !$node )
                    continue;

                if( $node->childrenCount( false ) > 0 )
                    $hasChildren = true;

                unset( $assignment );
            }

            if ( $hasChildren )
            {
                // We need user confirmation if at least one node we want to remove assignment for contains children.
                // Aactual removal is done in content/removeassignment in this case.
                $http->setSessionVariable( 'AssignmentRemoveData',
                                           array( 'remove_list'   => $assignmentsIDs,
                                                  'object_id'     => $objectID,
                                                  'edit_version'  => $versionInt,
                                                  'edit_language' => $editLanguage,
                                                  'from_language' => $fromLanguage ) );
                $module->redirectToView( 'removeassignment' );
                return eZModule::HOOK_STATUS_CANCEL_RUN;

            }
            else
            {
                // Just remove all the selected locations.
                $mainNodeChanged = false;
                $db = eZDB::instance();
                $db->begin();
                foreach ( $assignments as $assignment )
                {
                    $assignmentID = $assignment->attribute( 'id' );
                    if ( $assignment->attribute( 'is_main' ) )
                        $mainNodeChanged = true;
                    eZNodeAssignment::removeByID( $assignmentID );
                }
                if ( $mainNodeChanged )
                    eZNodeAssignment::setNewMainAssignment( $objectID, $versionInt );
                $db->commit();
                unset( $mainNodeChanged );
            }
            unset( $assignmentsIDs, $assignments );

        }
        else
        {
            eZDebug::writeNotice( 'No nodes to remove selected' );
        }
    }

    if ( $module->isCurrentAction( 'MoveNode' ) )
    {
        $objectID = $object->attribute( 'id' );
        if ( $http->hasPostVariable( 'MoveNodeID' ) )
        {
            $fromNodeID = $http->postVariable( 'MoveNodeID' ); //$sourceNodeID[0];
            $oldAssignmentParentID = $fromNodeID;
            $fromNodeAssignment = eZNodeAssignment::fetch( $objectID, $version->attribute( 'version' ), $fromNodeID );
            $publishParentNodeID = $fromNodeAssignment->attribute( 'parent_node' );
            if ( $publishParentNodeID > 1 )
            {
                if( $fromNodeAssignment->attribute( 'from_node_id' ) != 0 )
                {
                    $fromNodeID = $fromNodeAssignment->attribute( 'from_node_id' );
                    $oldAssignmentParentID = $fromNodeAssignment->attribute( 'parent_node' );
                }

                // we don't allow moving object to itself, to its descendants or parent object(s)
                $objectAssignedNodes = $object->attribute( 'assigned_nodes' );

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

                return eZModule::HOOK_STATUS_CANCEL_RUN;
            }
        }
    }
}

function handleNodeTemplate( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $tpl )
{
    // When the object has been published we will use the nodes as
    // node-assignments by faking the list, this is required since new
    // version of the object does not have node-assignments/
    // When the object is a draft we use the normal node-assignment list
    $assignedNodeArray = array();
    $versionedAssignedNodeArray = $version->attribute( 'parent_nodes' );
    $parentNodeIDMap = array();
    $nodes = $object->assignedNodes();
    $i = 0;
    foreach ( $nodes as $node )
    {
        $data = array( 'id' => null,
                       'contentobject_id' => $object->attribute( 'id' ),
                       'contentobject_version' => $version->attribute( 'version' ),
                       'parent_node' => $node->attribute( 'parent_node_id' ),
                       'sort_field' => $node->attribute( 'sort_field' ),
                       'sort_order' => $node->attribute( 'sort_order' ),
                       'is_main' => ( $node->attribute( 'main_node_id' ) == $node->attribute( 'node_id' ) ? 1 : 0 ),
                       'parent_remote_id' => $node->attribute( 'remote_id' ),
                       'op_code' => eZNodeAssignment::OP_CODE_NOP );
        $assignment = eZNodeAssignment::create( $data );
        $assignedNodeArray[$i] = $assignment;
        $parentNodeIDMap[$node->attribute( 'parent_node_id' )] = $i;
        ++$i;
    }
    foreach ( $versionedAssignedNodeArray as $nodeAssignment )
    {
        $opCode = $nodeAssignment->attribute( 'op_code' );
        if ( ( $opCode & 1 ) == eZNodeAssignment::OP_CODE_NOP ) // If the execute bit is not set it will be ignored.
        {
            continue;
        }
        // Only add assignments whose parent is not present in the published nodes.
        if ( isset( $parentNodeIDMap[$nodeAssignment->attribute( 'parent_node' )] ) )
        {
            if ( $opCode == eZNodeAssignment::OP_CODE_CREATE ) // CREATE entries are just skipped
            {
                continue;
            }
            // Or if they have an op_code (move,remove) set, in which case they overwrite the entry
            $index = $parentNodeIDMap[$nodeAssignment->attribute( 'parent_node' )];
            $assignedNodeArray[$index]->setAttribute( 'id', $nodeAssignment->attribute( 'id' ) );
            $assignedNodeArray[$index]->setAttribute( 'from_node_id', $nodeAssignment->attribute( 'from_node_id' ) );
            $assignedNodeArray[$index]->setAttribute( 'remote_id', $nodeAssignment->attribute( 'remote_id' ) );
            $assignedNodeArray[$index]->setAttribute( 'op_code', $nodeAssignment->attribute( 'op_code' ) );
            continue;
        }
        if ( $opCode == eZNodeAssignment::OP_CODE_REMOVE ||
             $opCode == eZNodeAssignment::OP_CODE_MOVE )
        {
            // The node-assignment has a remove/move operation but the node does not exist.
            // We will not show it in this case.
            continue;
        }
        $assignedNodeArray[$i] = $nodeAssignment;
        ++$i;
    }
    eZDebugSetting::writeDebug( 'kernel-content-edit', $assignedNodeArray, "assigned nodes array" );
    $remoteMap = array();

    $db = eZDB::instance();
    $db->begin();
    foreach ( $assignedNodeArray as $assignedNode )
    {
        $node = $assignedNode->getParentNode();
        if ( $node !== null )
        {
            $remoteID = $assignedNode->attribute( 'remote_id' );
            if ( $remoteID > 0 )
            {
                if ( isset( $remoteMap[$remoteID] ) )
                {
                    if ( is_array( $remoteMap[$remoteID] ) )
                    {
                        $remoteMap[$remoteID][] = $assignedNode;
                    }
                    else
                    {
                        $currentRemote = $remoteMap[$remoteID];
                        unset( $remoteMap[$remoteID] );
                        $remoteMap[$remoteID] = array();
                        $remoteMap[$remoteID][] = $currentRemote;
                        $remoteMap[$remoteID][] = $assignedNode;
                    }
                }
                else
                {
                    $remoteMap[$remoteID] = $assignedNode;
                }
            }
        }
        else
        {
            $assignedNode->purge();
            if( isset( $assignedNodeArray[$assignedNodeKey] ) )
                unset( $assignedNodeArray[$assignedNodeKey] );
        }
    }
    $db->commit();
    eZDebugSetting::writeDebug( 'kernel-content-edit', $assignedNodeArray, "assigned nodes array" );

    $currentVersion = $object->version( $editVersion );
    $publishedNodeArray = array();
    if ( $currentVersion )
        $publishedNodeArray = $currentVersion->attribute( 'parent_nodes' );
    $mainParentNodeID = $version->attribute( 'main_parent_node_id' );

    $tpl->setVariable( 'assigned_node_array', $assignedNodeArray );
    $tpl->setVariable( 'assigned_remote_map', $remoteMap );
    $tpl->setVariable( 'published_node_array', $publishedNodeArray );
    $tpl->setVariable( 'main_node_id', $mainParentNodeID );
}

function initializeNodeEdit( $module )
{
    $module->addHook( 'post_fetch', 'checkNodeAssignments' );
    $module->addHook( 'post_fetch', 'checkNodeMovements' );
    $module->addHook( 'pre_commit', 'storeNodeAssignments' );
    $module->addHook( 'action_check', 'checkNodeActions' );
    $module->addHook( 'pre_template', 'handleNodeTemplate' );
}

?>
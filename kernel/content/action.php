<?php
//
// Created on: <04-Jul-2002 13:06:30 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];

/* We retrieve the class ID for users as this is used in many places in this
 * code in order to be able to cleanup the user-policy cache. */
$userClassIDArray = eZUser::contentClassIDs();

if ( $module->hasActionParameter( 'LanguageCode' ) )
    $languageCode = $module->actionParameter( 'LanguageCode' );
else
{
    $languageCode = false;
}

$viewMode = 'full';
if ( $module->hasActionParameter( 'ViewMode' ) )
    $viewMode = $module->actionParameter( 'ViewMode' );

if ( $http->hasPostVariable( 'BrowseCancelButton' ) || $http->hasPostVariable( 'CancelButton' ) )
{
    if ( $http->hasPostVariable( 'BrowseCancelURI' ) )
    {
        return $module->redirectTo( $http->postVariable( 'BrowseCancelURI' ) );
    }
    else if ( $http->hasPostVariable( 'CancelURI' ) )
    {
        return $module->redirectTo( $http->postVariable( 'CancelURI' ) );
    }
}
// Merge post variables and variables that were used before login
if ( $http->hasSessionVariable( 'LastPostVars' ) )
{
    $post = $http->attribute( 'post' );
    $currentPostVarNames = array_keys( $post );
    foreach ( $http->sessionVariable( 'LastPostVars' ) as $var => $value )
    {
        if ( !in_array( $var, $currentPostVarNames ) )
        {
            $http->setPostVariable( $var, $value );
        }
    }

    $http->removeSessionVariable( 'LastPostVars' );
}

if ( $http->hasPostVariable( 'NewButton' ) || $module->isCurrentAction( 'NewObjectAddNodeAssignment' )  )
{
    $hasClassInformation = false;
    $contentClassID = false;
    $contentClassIdentifier = false;
    $languageCode = false;
    $class = false;

    if ( $http->hasPostVariable( 'ClassID' ) )
    {
        $contentClassID = $http->postVariable( 'ClassID' );
        if ( $contentClassID )
            $hasClassInformation = true;
    }
    else if ( $http->hasPostVariable( 'ClassIdentifier' ) )
    {
        $contentClassIdentifier = $http->postVariable( 'ClassIdentifier' );
        $class = eZContentClass::fetchByIdentifier( $contentClassIdentifier );
        if ( is_object( $class ) )
        {
            $contentClassID = $class->attribute( 'id' );
            if ( $contentClassID )
                $hasClassInformation = true;
        }
    }

    if ( $http->hasPostVariable( 'ContentLanguageCode' ) )
    {
        $languageCode = $http->postVariable( 'ContentLanguageCode' );
        $languageID = eZContentLanguage::idByLocale( $languageCode );
        if ( $languageID === false )
        {
            eZDebug::writeError( "The language code [$languageCode] specified in ContentLanguageCode does not exist in the system." );
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }
    }
    else
    {
        $allLanguages = eZContentLanguage::prioritizedLanguages();
        // Only show language selection if there are more than 1 languages.
        if ( count( $allLanguages ) > 1 &&
             $hasClassInformation )
        {
            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();

            $tpl->setVariable( 'node_id', $http->postVariable( 'NodeID' ) );
            $tpl->setVariable( 'class_id', $contentClassID );
            $tpl->setVariable( 'assignment_remote_id', $http->postVariable( 'AssignmentRemoteID', false ) );
            $tpl->setVariable( 'redirect_uri_after_publish', $http->postVariable( 'RedirectURIAfterPublish', false ) );
            $tpl->setVariable( 'redirect_uri_after_discard', $http->postVariable( 'RedirectIfDiscarded', false ) );

            $Result = array();
            $Result['content'] = $tpl->fetch( 'design:content/create_languages.tpl' );
            return $Result;
        }
    }

    if ( ( $hasClassInformation && $http->hasPostVariable( 'NodeID' ) ) || $module->isCurrentAction( 'NewObjectAddNodeAssignment' ) )
    {
        if (  $module->isCurrentAction( 'NewObjectAddNodeAssignment' ) )
        {
            $selectedNodeIDArray = eZContentBrowse::result( 'NewObjectAddNodeAssignment' );
            if ( count( $selectedNodeIDArray ) == 0 )
                return $module->redirectToView( 'view', array( 'full', 2 ) );
            $node = eZContentObjectTreeNode::fetch( $selectedNodeIDArray[0] );
        }
        else
        {
            $node = eZContentObjectTreeNode::fetch( $http->postVariable( 'NodeID' ) );
        }

        if ( is_object( $node ) )
        {
            $contentObject = eZContentObject::createWithNodeAssignment( $node,
                                                                        $contentClassID,
                                                                        $languageCode,
                                                                        $http->postVariable( 'AssignmentRemoteID', false ) );
            if ( $contentObject )
            {
                if ( $http->hasPostVariable( 'RedirectURIAfterPublish' ) )
                {
                    $http->setSessionVariable( 'RedirectURIAfterPublish', $http->postVariable( 'RedirectURIAfterPublish' ) );
                }
                if ( $http->hasPostVariable( 'RedirectIfDiscarded' ) )
                {
                    $http->setSessionVariable( 'RedirectIfDiscarded', $http->postVariable( 'RedirectIfDiscarded' ) );
                }
                $module->redirectTo( $module->functionURI( 'edit' ) . '/' . $contentObject->attribute( 'id' ) . '/' . $contentObject->attribute( 'current_version' ) );
                return;
            }
            else
            {
                // If ACCESS DENIED save current post variables for using after login
                $http->setSessionVariable( '$_POST_BeforeLogin', $http->attribute( 'post' ) );
                return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
            }
        }
        else
        {
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }
    }
    else if ( $hasClassInformation )
    {
        if ( !is_object( $class ) )
            $class = eZContentClass::fetch( $contentClassID );
        eZContentBrowse::browse( array( 'action_name' => 'NewObjectAddNodeAssignment',
                                        'description_template' => 'design:content/browse_first_placement.tpl',
                                        'keys' => array( 'class' => $class->attribute( 'id' ),
                                                         'classgroup' => $class->attribute( 'ingroup_id_list' ) ),
                                        'persistent_data' => array( 'ClassID' => $class->attribute( 'id' ), 'ContentLanguageCode' => $languageCode ),
                                        'content' => array( 'class_id' => $class->attribute( 'id' ) ),
                                        'cancel_page' => $module->redirectionURIForModule( $module, 'view', array( 'full', 2 ) ),
                                        'from_page' => "/content/action" ),
                                 $module );
    }
}
else if ( $http->hasPostVariable( 'SetSorting' ) &&
          $http->hasPostVariable( 'ContentObjectID' ) && $http->hasPostVariable( 'ContentNodeID' ) &&
          $http->hasPostVariable( 'SortingField' )    && $http->hasPostVariable( 'SortingOrder' ) )
{
    $nodeID          = $http->postVariable( 'ContentNodeID' );
    $contentObjectID = $http->postVariable( 'ContentObjectID' );
    $sortingField    = $http->postVariable( 'SortingField' );
    $sortingOrder    = $http->postVariable( 'SortingOrder' );
    $node = eZContentObjectTreeNode::fetch( $nodeID );
    $contentObject = eZContentObject::fetch( $contentObjectID );

    if ( eZContentOperationCollection::operationIsAvailable( 'content_sort' ) )
    {
        $operationResult = eZOperationHandler::execute( 'content', 'sort',
                                                         array( 'node_id' => $nodeID,
                                                                'sorting_field' => $sortingField,
                                                                'sorting_order' => $sortingOrder ), null, true );
    }
    else
    {
        eZContentOperationCollection::changeSortOrder( $nodeID, $sortingField, $sortingOrder );
    }

    if ( $http->hasPostVariable( 'RedirectURIAfterSorting' ) )
    {
        return $module->redirectTo( $http->postVariable( 'RedirectURIAfterSorting' ) );
    }
    return $module->redirectToView( 'view', array( 'full', $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'MoveNode' ) )
{
    /* This action is used through the admin interface with the "Move" button,
     * or in the pop-up menu and will move a node to a different location. */

    if ( !$module->hasActionParameter( 'NodeID' ) )
    {
        eZDebug::writeError( "Missing NodeID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    if ( $module->hasActionParameter( 'NewParentNode' ) )
    {
        $selectedNodeID = $module->actionParameter( 'NewParentNode' );
    }
    else
    {
        $selectedNodeIDArray = eZContentBrowse::result( 'MoveNode' );
        $selectedNodeID = $selectedNodeIDArray[0];
    }

    $selectedNode = eZContentObjectTreeNode::fetch( $selectedNodeID );
    if ( !$selectedNode )
    {
        eZDebug::writeWarning( "Content node with ID $selectedNodeID does not exist, cannot use that as parent node for node $nodeID",
                               'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    $nodeIDlist = $module->actionParameter( 'NodeID' );
    if ( strpos( $nodeIDlist, ',' ) !== false )
    {
        $nodeIDlist = explode( ',', $nodeIDlist );
    }
    else
    {
        $nodeIDlist = array( $nodeIDlist );
    }

    // Check that all user has access to move all selected nodes
    $nodeToMoveList = array();
    foreach( $nodeIDlist as $key => $nodeID )
    {
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !$node )
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );

        if ( !$node->canMoveFrom() )
            return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );

        $object = $node->object();
        if ( !$object )
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );

        $nodeToMoveList[] = array( 'node_id'   => $nodeID,
                                   'object_id' => $object->attribute( 'id' ) );

        $class = $object->contentClass();
        $classID = $class->attribute( 'id' );

        // check if the object can be moved to (under) the selected node
        if ( !$selectedNode->canMoveTo( $classID ) )
        {
            eZDebug::writeError( "Cannot move node $nodeID as child of parent node $selectedNodeID, the current user does not have create permission for class ID $classID",
                                 'content/action' );
            return $module->redirectToView( 'view', array( 'full', 2 ) );
        }

        // Check if we try to move the node as child of itself or one of its children
        if ( in_array( $node->attribute( 'node_id' ), $selectedNode->pathArray()  ) )
        {
            eZDebug::writeError( "Cannot move node $nodeID as child of itself or one of its own children (node $selectedNodeID).",
                                 'content/action' );
            return $module->redirectToView( 'view', array( 'full', $node->attribute( 'node_id' ) ) );
        }
    }

    // move selected nodes, this should probably be inside a transaction
    foreach( $nodeToMoveList as $nodeToMove )
    {
        if ( eZContentOperationCollection::operationIsAvailable( 'content_move' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content',
                                                            'move', array( 'node_id'            => $nodeToMove['node_id'],
                                                                           'object_id'          => $nodeToMove['object_id'],
                                                                           'new_parent_node_id' => $selectedNodeID ),
                                                            null,
                                                            true );
        }
        else
        {
            eZContentOperationCollection::moveNode( $nodeToMove['node_id'], $nodeToMove['object_id'], $selectedNodeID );
        }
    }

    return $module->redirectToView( 'view', array( $viewMode, $selectedNodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'MoveNodeRequest' ) )
{
    /* This action is started through the pop-up menu when a "Move" is
     * requested and through the use of the "Move" button. It will start the
     * browser to select where the node should be moved to. */

    if ( !$module->hasActionParameter( 'NodeID' ) )
    {
        eZDebug::writeError( "Missing NodeID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    $nodeID = $module->actionParameter( 'NodeID' );
    $node = eZContentObjectTreeNode::fetch( $nodeID );
    if ( !$node )
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );

    if ( !$node->canMoveFrom() )
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );

    $object = $node->object();
    if ( !$object )
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );
    $objectID = $object->attribute( 'id' );
    $class = $object->contentClass();

    $ignoreNodesSelect = array();
    $ignoreNodesSelectSubtree = array();
    $ignoreNodesClick = array();

    $publishedAssigned = $object->assignedNodes( false );
    foreach ( $publishedAssigned as $element )
    {
        $ignoreNodesSelect[] = $element['node_id'];
        $ignoreNodesSelectSubtree[] = $element['node_id'];
        $ignoreNodesClick[]  = $element['node_id'];
        $ignoreNodesSelect[] = $element['parent_node_id'];
    }

    $ignoreNodesSelect = array_unique( $ignoreNodesSelect );
    $ignoreNodesSelectSubtree = array_unique( $ignoreNodesSelectSubtree );
    $ignoreNodesClick = array_unique( $ignoreNodesClick );
    eZContentBrowse::browse( array( 'action_name' => 'MoveNode',
                                    'description_template' => 'design:content/browse_move_node.tpl',
                                    'keys' => array( 'class' => $class->attribute( 'id' ),
                                                     'class_id' => $class->attribute( 'identifier' ),
                                                     'classgroup' => $class->attribute( 'ingroup_id_list' ),
                                                     'section' => $object->attribute( 'section_id' ) ),
                                    'ignore_nodes_select' => $ignoreNodesSelect,
                                    'ignore_nodes_select_subtree' => $ignoreNodesSelectSubtree,
                                    'ignore_nodes_click'  => $ignoreNodesClick,
                                    'persistent_data' => array( 'ContentNodeID' => $nodeID,
                                                                'ViewMode' => $viewMode,
                                                                'ContentObjectLanguageCode' => $languageCode,
                                                                'MoveNodeAction' => '1' ),
                                    'permission' => array( 'access' => 'create',
                                                           'contentclass_id' => $class->attribute( 'id' ) ),
                                    'content' => array( 'object_id' => $objectID,
                                                        'object_version' => $object->attribute( 'current_version' ),
                                                        'object_language' => $languageCode ),
                                    'start_node' => $node->attribute( 'parent_node_id' ),
                                    'cancel_page' => $module->redirectionURIForModule( $module, 'view', array( $viewMode, $nodeID, $languageCode ) ),
                                    'from_page' => "/content/action" ),
                             $module );

    return;
}
else if ( $module->isCurrentAction( 'SwapNode' ) )
{
    if ( !$module->hasActionParameter( 'NodeID' ) )
    {
        eZDebug::writeError( "Missing NodeID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    $nodeID = $module->actionParameter( 'NodeID' );
    $node = eZContentObjectTreeNode::fetch( $nodeID );

    if ( !$node )
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );

    if ( !$node->canSwap() )
    {
        eZDebug::writeError( "Cannot swap node $nodeID (no edit permission)" );
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
    }

    $nodeParentNodeID = $node->attribute( 'parent_node_id' );

    $object = $node->object();
    if ( !$object )
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );
    $objectID = $object->attribute( 'id' );
    $objectVersion = $object->attribute( 'current_version' );

    if ( $module->hasActionParameter( 'NewNode' ) )
    {
        $selectedNodeID = $module->actionParameter( 'NewNode' );
    }
    else
    {
         $selectedNodeIDArray = eZContentBrowse::result( 'SwapNode' );
         $selectedNodeID = $selectedNodeIDArray[0];
    }

    $selectedNode = eZContentObjectTreeNode::fetch( $selectedNodeID );
    if ( !$selectedNode )
    {
        eZDebug::writeWarning( "Content node with ID $selectedNodeID does not exist, cannot use that as exchanging node for node $nodeID",
                               'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }
    if ( !$selectedNode->canSwap() )
    {
        eZDebug::writeError( "Cannot use node $selectedNodeID as the exchanging node for $nodeID, the current user does not have edit permission for it",
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    // clear cache.
    eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

    $selectedObject = $selectedNode->object();
    $selectedObjectID = $selectedObject->attribute( 'id' );
    $selectedObjectVersion = $selectedObject->attribute( 'current_version' );
    $selectedNodeParentNodeID = $selectedNode->attribute( 'parent_node_id' );


    /* In order to swap node1 and node2 a user should have the following permissions:
     * 1. move_from: move node1
     * 2. move_from: move node2
     * 3. move_to: move an object of the same class as node2 under parent of node1
     * 4. move_to: move an object of the same class as node1 under parent of node2
     *
     * The First two has already been checked. Let's check the rest.
     */
    $nodeParent            = $node->attribute( 'parent' );
    $selectedNodeParent    = $selectedNode->attribute( 'parent' );
    $objectClassID         = $object->attribute( 'contentclass_id' );
    $selectedObjectClassID = $selectedObject->attribute( 'contentclass_id' );

    if ( !$nodeParent || !$selectedNodeParent )
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );

    if ( !$nodeParent->canMoveTo( $selectedObjectClassID ) )
    {
        eZDebug::writeError( "Cannot move an object of class $selectedObjectClassID to node $nodeParentNodeID (no create permission)" );
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
    }
    if ( !$selectedNodeParent->canMoveTo( $objectClassID ) )
    {
        eZDebug::writeError( "Cannot move an object of class $objectClassID to node $selectedNodeParentNodeID (no create permission)" );
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
    }

    // exchange contentobject ids and versions.
    if ( eZContentOperationCollection::operationIsAvailable( 'content_swap' ) )
    {
        $operationResult = eZOperationHandler::execute( 'content',
                                                        'swap',
                                                         array( 'node_id'          => $nodeID,
                                                                'selected_node_id' => $selectedNodeID,
                                                                'node_id_list'        => array( $nodeID, $selectedNodeID ) ),
                                                         null,
                                                         true );

    }
    else
    {
        eZContentOperationCollection::swapNode( $nodeID, $selectedNodeID, array( $nodeID, $selectedNodeID ) );
    }

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'SwapNodeRequest' ) )
{
    /* This action brings a browse screen up to select with which the selected
     * node should be swapped. It will not actually move the nodes. */

    if ( !$module->hasActionParameter( 'NodeID' ) )
    {
        eZDebug::writeError( "Missing NodeID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    $nodeID = $module->actionParameter( 'NodeID' );
    $node = eZContentObjectTreeNode::fetch( $nodeID );
    if ( !$node )
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );

    if ( !$node->canSwap() )
    {
        eZDebug::writeError( "Cannot swap node $nodeID (no edit permission)" );
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
    }

    $object = $node->object();
    if ( !$object )
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );
    $objectID = $object->attribute( 'id' );
    $class = $object->contentClass();

    $ignoreNodesSelect = array( $nodeID );
    $ignoreNodesClick = array();

    eZContentBrowse::browse( array( 'action_name' => 'SwapNode',
                                    'description_template' => 'design:content/browse_swap_node.tpl',
                                    'keys' => array( 'class' => $class->attribute( 'id' ),
                                                     'class_id' => $class->attribute( 'identifier' ),
                                                     'classgroup' => $class->attribute( 'ingroup_id_list' ),
                                                     'section' => $object->attribute( 'section_id' ) ),
                                    'ignore_nodes_select' => $ignoreNodesSelect,
                                    'ignore_nodes_click'  => $ignoreNodesClick,
                                    'persistent_data' => array( 'ContentNodeID' => $nodeID,
                                                                'ViewMode' => $viewMode,
                                                                'ContentObjectLanguageCode' => $languageCode,
                                                                'SwapNodeAction' => '1' ),
                                    'permission' => array( 'access' => 'edit',
                                                           'contentclass_id' => $class->attribute( 'id' ) ),
                                    'content' => array( 'object_id' => $objectID,
                                                        'object_version' => $object->attribute( 'current_version' ),
                                                        'object_language' => $languageCode ),
                                    'start_node' => $node->attribute( 'parent_node_id' ),
                                    'cancel_page' => $module->redirectionURIForModule( $module, 'view', array( $viewMode, $nodeID, $languageCode ) ),
                                    'from_page' => "/content/action" ),
                             $module );

    return;
}
else if ( $module->isCurrentAction( 'UpdateMainAssignment' ) )
{
    /* This action selects a different main assignment node for the object. */

    if ( !$module->hasActionParameter( 'ObjectID' ) )
    {
        eZDebug::writeError( "Missing ObjectID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }
    if ( !$module->hasActionParameter( 'NodeID' ) )
    {
        eZDebug::writeError( "Missing NodeID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    $objectID = $module->actionParameter( 'ObjectID' );
    $nodeID = $module->actionParameter( 'NodeID' );

    if ( $module->hasActionParameter( 'MainAssignmentID' ) )
    {
        $mainAssignmentID = $module->actionParameter( 'MainAssignmentID' );

        $object = eZContentObject::fetch( $objectID );
        if ( !$object )
        {
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }

        $existingMainNodeID = false;
        $existingMainNode = $object->attribute( 'main_node' );
        if ( $existingMainNode )
            $existingMainNodeID = $existingMainNode->attribute( 'node_id' );
        if ( $existingMainNodeID === false or
             $existingMainNodeID != $mainAssignmentID )
        {
            if ( $existingMainNode and
                 !$existingMainNode->checkAccess( 'edit' ) )
            {
                return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
            }

            $newMainNode = eZContentObjectTreeNode::fetch( $mainAssignmentID );
            if ( !$newMainNode )
            {
                return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
            }

            if ( !$newMainNode->checkAccess( 'edit' ) )
            {
                return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
            }

            $mainAssignmentParentID = $newMainNode->attribute( 'parent_node_id' );
            if ( eZContentOperationCollection::operationIsAvailable( 'content_updatemainassignment' ) )
            {
                $operationResult = eZOperationHandler::execute( 'content',
                                                                'updatemainassignment', array( 'main_assignment_id' => $mainAssignmentID,
                                                                            'object_id' => $objectID,
                                                                            'main_assignment_parent_id' => $mainAssignmentParentID ),null, true );
            }
            else
            {
                eZContentOperationCollection::UpdateMainAssignment( $mainAssignmentID, $objectID, $newMainNode->attribute( 'parent_node_id' ) );
            }
        }
    }
    else
    {
        eZDebug::writeError( "No MainAssignmentID found for action " . $module->currentAction(),
                             'content/action' );
    }

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'AddAssignment' ) or
          $module->isCurrentAction( 'SelectAssignmentLocation' ) )
{
    if ( !$module->hasActionParameter( 'ObjectID' ) )
    {
        eZDebug::writeError( "Missing ObjectID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }
    if ( !$module->hasActionParameter( 'NodeID' ) )
    {
        eZDebug::writeError( "Missing NodeID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    $objectID = $module->actionParameter( 'ObjectID' );
    $nodeID = $module->actionParameter( 'NodeID' );

    $object = eZContentObject::fetch( $objectID );
    if ( !$object )
    {
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    $user = eZUser::currentUser();
    if ( !$object->checkAccess( 'edit' ) &&
         !$user->attribute( 'has_manage_locations' ) )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    $existingNode = eZContentObjectTreeNode::fetch( $nodeID );
    if ( !$existingNode )
    {
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    $class = $object->contentClass();
    if ( $module->isCurrentAction( 'AddAssignment' ) )
    {
        $selectedNodeIDArray = eZContentBrowse::result( 'AddNodeAssignment' );
        if ( !is_array( $selectedNodeIDArray ) )
            $selectedNodeIDArray = array();

        if ( eZContentOperationCollection::operationIsAvailable( 'content_addlocation' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content',
                                                            'addlocation', array( 'node_id'              => $nodeID,
                                                                                  'object_id'            => $objectID,
                                                                                  'select_node_id_array' => $selectedNodeIDArray ),
                                                            null,
                                                            true );
        }
        else
        {
            eZContentOperationCollection::addAssignment( $nodeID, $objectID, $selectedNodeIDArray );
        }
    }
    else if ( $module->isCurrentAction( 'SelectAssignmentLocation' ) )
    {
        $ignoreNodesSelect = array();
        $ignoreNodesClick  = array();

        $assigned = eZNodeAssignment::fetchForObject( $objectID, $object->attribute( 'current_version' ), 0, false );
        $publishedAssigned = $object->assignedNodes( false );
        $isTopLevel = false;
        foreach ( $publishedAssigned as $element )
        {
            $append = false;
            if ( $element['parent_node_id'] == 1 )
                $isTopLevel = true;
            foreach ( $assigned as $ass )
            {
                if ( $ass['parent_node'] == $element['parent_node_id'] )
                {
                    $append = true;
                    break;
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
                                            'persistent_data' => array( 'ContentNodeID' => $nodeID,
                                                                        'ContentObjectID' => $objectID,
                                                                        'ViewMode' => $viewMode,
                                                                        'ContentObjectLanguageCode' => $languageCode,
                                                                        'AddAssignmentAction' => '1' ),
                                            'content' => array( 'object_id' => $objectID,
                                                                'object_version' => $object->attribute( 'current_version' ),
                                                                'object_language' => $languageCode ),
                                            'cancel_page' => $module->redirectionURIForModule( $module, 'view', array( $viewMode, $nodeID, $languageCode ) ),
                                            'from_page' => "/content/action" ),
                                     $module );

            return;
        }
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'RemoveAssignment' )  )
{
    if ( !$module->hasActionParameter( 'ObjectID' ) )
    {
        eZDebug::writeError( "Missing ObjectID parameter for action RemoveAssignment",
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }
    if ( !$module->hasActionParameter( 'NodeID' ) )
    {
        eZDebug::writeError( "Missing NodeID parameter for action RemoveAssignment",
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    $objectID = $module->actionParameter( 'ObjectID' );
    $nodeID = $module->actionParameter( 'NodeID' );
    $redirectNodeID = $nodeID;

    $object = eZContentObject::fetch( $objectID );
    if ( !$object )
    {
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    $user = eZUser::currentUser();
    if ( !$object->checkAccess( 'edit' ) &&
         !$user->hasManageLocations() )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    if ( $module->hasActionParameter( 'AssignmentIDSelection' ) )
    {
        eZDebug::writeError( "Use of POST variable 'AssignmentIDSelection' is deprecated, use the node ID and put it in 'LocationIDSelection' instead" );
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    if ( !$module->hasActionParameter( 'LocationIDSelection' ) )
        return $module->redirectToView( 'view', array( $viewMode, $redirectNodeID, $languageCode ) );

    $locationIDSelection = $module->actionParameter( 'LocationIDSelection' );

    $hasChildren = false;

    $nodes = array();
    foreach ( $locationIDSelection as $locationID )
    {
        $nodes[] = eZContentObjectTreeNode::fetch( $locationID );
    }
    $removeList = array();
    $nodeRemoveList = array();
    foreach ( $nodes as $node )
    {
        if ( $node )
        {
            // Security checks, removal of current node is not allowed
            // and we require removal rights
            if ( !$node->canRemove() &&
                 !$node->canRemoveLocation() )
                continue;
            if ( $node->attribute( 'node_id' ) == $nodeID )
            {
                $redirectNodeID = $node->attribute( 'parent_node_id' );
            }

            $removeList[] = $node->attribute( 'node_id' );
            $nodeRemoveList[] = $node;
            $count = $node->childrenCount( false );

            if ( $count > 0 )
            {
                $hasChildren = true;
            }
        }
    }

    if ( $hasChildren )
    {
        $http->setSessionVariable( 'CurrentViewMode', $viewMode );
        $http->setSessionVariable( 'DeleteIDArray', $removeList );
        $http->setSessionVariable( 'ContentObjectID', $objectID );
        $http->setSessionVariable( 'ContentNodeID', $nodeID );
        $http->setSessionVariable( 'ContentLanguage', $languageCode );
        return $module->redirectToView( 'removeobject' );
    }
    else
    {
        if ( eZContentOperationCollection::operationIsAvailable( 'content_removelocation' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content',
                                                            'removelocation', array( 'node_id'       => $nodeID,
                                                                                     'object_id'     => $objectID,
                                                                                     'node_list'     => $nodeRemoveList,
                                                                                     'move_to_trash' => false ),
                                                            null,
                                                            true );
        }
        else
        {
            eZContentOperationCollection::removeAssignment( $nodeID, $objectID, $nodeRemoveList, false );
        }
    }

    return $module->redirectToView( 'view', array( $viewMode, $redirectNodeID, $languageCode ) );
}
else if ( $http->hasPostVariable( 'EditButton' )  )
{
    if ( $http->hasPostVariable( 'ContentObjectID' ) )
    {
        $parameters = array( $http->postVariable( 'ContentObjectID' ) );
        if ( $http->hasPostVariable( 'ContentObjectVersion' ) )
        {
            $parameters[] = $http->postVariable( 'ContentObjectVersion' );
            if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
            {
                $parameters[] = $http->postVariable( 'ContentObjectLanguageCode' );
            }
        }
        else
        {
            if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
            {
                $languageCode = $http->postVariable( 'ContentObjectLanguageCode' );
                if ( $languageCode == '' )
                {
                    $parameters[] = 'a'; // this will be treatead as not entering the version number and offering
                                         // list with new languages
                }
                else
                {
                    $parameters[] = 'f'; // this will be treatead as not entering the version number
                    $parameters[]= $languageCode;
                }
            }
        }

        if ( $http->hasPostVariable( 'RedirectURIAfterPublish' ) )
        {
            $http->setSessionVariable( 'RedirectURIAfterPublish', $http->postVariable( 'RedirectURIAfterPublish' ) );
        }
        if ( $http->hasPostVariable( 'RedirectIfDiscarded' ) )
        {
            $http->setSessionVariable( 'RedirectIfDiscarded', $http->postVariable( 'RedirectIfDiscarded' ) );
        }

        $module->redirectToView( 'edit', $parameters );
        return;
    }
}
else if ( $http->hasPostVariable( 'PreviewPublishButton' )  )
{
    if ( $http->hasPostVariable( 'ContentObjectID' ) )
    {
        $parameters = array( $http->postVariable( 'ContentObjectID' ) );
        if ( $http->hasPostVariable( 'ContentObjectVersion' ) )
        {
            $parameters[] = $http->postVariable( 'ContentObjectVersion' );
            if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
            {
                $parameters[] = $http->postVariable( 'ContentObjectLanguageCode' );
            }
        }
        $module->setCurrentAction( 'Publish', 'edit' );
        return $module->run( 'edit', $parameters );
    }
}
else if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    $viewMode = $http->postVariable( 'ViewMode', 'full' );

    $contentNodeID = $http->postVariable( 'ContentNodeID', 2 );
    $contentObjectID = $http->postVariable( 'ContentObjectID', 1 );

    $hideRemoveConfirm = false;
    if ( $http->hasPostVariable( 'HideRemoveConfirmation' ) )
        $hideRemoveConfirm = $http->postVariable( 'HideRemoveConfirmation' ) ? true : false;

    if ( $http->hasPostVariable( 'DeleteIDArray' ) or $http->hasPostVariable( 'SelectedIDArray' ) )
    {
        if ( $http->hasPostVariable( 'SelectedIDArray' ) )
            $deleteIDArray = $http->postVariable( 'SelectedIDArray' );
        else
            $deleteIDArray = $http->postVariable( 'DeleteIDArray' );

        if ( is_array( $deleteIDArray ) && count( $deleteIDArray ) > 0 )
        {
            $http->setSessionVariable( 'CurrentViewMode', $viewMode );
            $http->setSessionVariable( 'ContentNodeID', $contentNodeID );
            $http->setSessionVariable( 'ContentObjectID', $contentObjectID );
            $http->setSessionVariable( 'HideRemoveConfirmation', $hideRemoveConfirm );
            $http->setSessionVariable( 'DeleteIDArray', $deleteIDArray );
            $object = eZContentObject::fetch( $contentObjectID );
            eZSection::setGlobalID( $object->attribute( 'section_id' ) );
            $section = eZSection::fetch( $object->attribute( 'section_id' ) );
            if ( $section )
                $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
            else
                $navigationPartIdentifier = null;
            if ( $navigationPartIdentifier and $navigationPartIdentifier == 'ezusernavigationpart' )
            {
                $module->redirectTo( $module->functionURI( 'removeuserobject' ) . '/' );
            }
            elseif ( $navigationPartIdentifier and $navigationPartIdentifier == 'ezmedianavigationpart' )
            {
                $module->redirectTo( $module->functionURI( 'removemediaobject' ) . '/' );
            }
            else
            {
                $module->redirectTo( $module->functionURI( 'removeobject' ) . '/' );
            }
        }
        else
        {
            $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $contentNodeID . '/' );
        }
    }
    else
    {
        $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $contentNodeID . '/' );
    }
}
else if ( $http->hasPostVariable( 'MoveButton' ) )
{
    /* action for multi select move, uses same interface as RemoveButton */
    $viewMode = $http->postVariable( 'ViewMode', 'full' );

    $parentNodeID = $http->postVariable( 'ContentNodeID', 2 );
    $parentObjectID = $http->postVariable( 'ContentObjectID', 1 );

    if ( $http->hasPostVariable( 'DeleteIDArray' ) or $http->hasPostVariable( 'SelectedIDArray' ) )
    {
        if ( $http->hasPostVariable( 'SelectedIDArray' ) )
            $moveIDArray = $http->postVariable( 'SelectedIDArray' );
        else
            $moveIDArray = $http->postVariable( 'DeleteIDArray' );

        if ( is_array( $moveIDArray ) && count( $moveIDArray ) > 0 )
        {
            $ignoreNodesSelect = array();
            $ignoreNodesSelectSubtree = array();
            $ignoreNodesClick = array();
            $classIDArray = array();
            $classIdentifierArray = array();
            $classGroupArray = array();
            $sectionIDArray = array();
            $objectNameArray = array();

            foreach( $moveIDArray as $nodeID )
            {
                $node = eZContentObjectTreeNode::fetch( $nodeID );
                if ( !$node )
                    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );

                if ( !$node->canMoveFrom() )
                    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );

                $object = $node->object();
                if ( !$object )
                    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );

                $class = $object->contentClass();

                $classIDArray[] = $class->attribute( 'id' );
                $classIdentifierArray[] = $class->attribute( 'identifier' );
                $classGroupArray = array_merge( $classGroupArray, $class->attribute( 'ingroup_id_list' ) );
                $sectionIDArray[] = $object->attribute( 'section_id' );
                $objectNameArray[] = $object->attribute( 'name' );

                $publishedAssigned = $object->assignedNodes( false );
                foreach ( $publishedAssigned as $element )
                {
                    $ignoreNodesSelect[] = $element['node_id'];
                    $ignoreNodesSelectSubtree[] = $element['node_id'];
                    $ignoreNodesClick[]  = $element['node_id'];
                    $ignoreNodesSelect[] = $element['parent_node_id'];
                }
            }

            $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
            if ( !$parentNode )
                return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );

            $parentObject = $parentNode->object();
            if ( !$parentObject )
                return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel', array() );
            $parentObjectID = $parentObject->attribute( 'id' );
            $parentClass = $parentObject->contentClass();

            $ignoreNodesSelect = array_unique( $ignoreNodesSelect );
            $ignoreNodesSelectSubtree = array_unique( $ignoreNodesSelectSubtree );
            $ignoreNodesClick = array_unique( $ignoreNodesClick );

            $classIDArray = array_unique( $classIDArray );
            $classIdentifierArray = array_unique( $classIdentifierArray );
            $classGroupArray = array_unique( $classGroupArray );
            $sectionIDArray = array_unique( $sectionIDArray );

            eZContentBrowse::browse( array( 'action_name' => 'MoveNode',
                                            'description_template' => 'design:content/browse_move_node.tpl',
                                            'keys' => array( 'class' => $classIDArray,
                                                             'class_id' => $classIdentifierArray,
                                                             'classgroup' => $classGroupArray,
                                                             'section' => $sectionIDArray ),
                                            'ignore_nodes_select' => $ignoreNodesSelect,
                                            'ignore_nodes_select_subtree' => $ignoreNodesSelectSubtree,
                                            'ignore_nodes_click'  => $ignoreNodesClick,
                                            'persistent_data' => array( 'ContentNodeID' => implode( ',', $moveIDArray ),
                                                                        'ViewMode' => $viewMode,
                                                                        'ContentObjectLanguageCode' => $languageCode,
                                                                        'MoveNodeAction' => '1' ),
                                            'permission' => array( 'access' => 'create',
                                                                   'contentclass_id' => $classIDArray ),
                                            'content' => array( 'name_list' => $objectNameArray, 'node_id_list' => $moveIDArray ),
                                            'start_node' => $parentNodeID,
                                            'cancel_page' => $module->redirectionURIForModule( $module, 'view', array( $viewMode, $parentNodeID, $languageCode ) ),
                                            'from_page' => "/content/action" ),
                                     $module );
        }
        else
        {
            eZDebug::writeError( "Empty SelectedIDArray parameter for action " . $module->currentAction(),
                                 'content/action' );
            $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $parentNodeID . '/' );
        }
    }
    else
    {
        eZDebug::writeError( "Missing SelectedIDArray parameter for action " . $module->currentAction(),
                             'content/action' );
        $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $parentNodeID . '/' );
    }
}
else if ( $http->hasPostVariable( 'UpdatePriorityButton' ) )
{
    $viewMode = $http->postVariable( 'ViewMode', 'full' );

    if ( $http->hasPostVariable( 'ContentNodeID' ) )
    {
        $contentNodeID = $http->postVariable( 'ContentNodeID' );
    }
    else
    {
        eZDebug::writeError( "Variable 'ContentNodeID' can not be found in template." );
        $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $contentNodeID . '/' );
        return;
    }
    if ( $http->hasPostVariable( 'Priority' ) and $http->hasPostVariable( 'PriorityID' ) )
    {
        $contentNode = eZContentObjectTreeNode::fetch( $contentNodeID );
        if ( !$contentNode->attribute( 'can_edit' ) )
        {
            eZDebug::writeError( 'Current user can not update the priorities because he has no permissions to edit the node' );
            $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $contentNodeID . '/' );
            return;
        }
        $priorityArray = $http->postVariable( 'Priority' );
        $priorityIDArray = $http->postVariable( 'PriorityID' );

        if ( eZContentOperationCollection::operationIsAvailable( 'content_updatepriority' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content', 'updatepriority',
                                                             array( 'node_id' => $contentNodeID,
                                                                    'priority' => $priorityArray,
                                                                    'priority_id' => $priorityIDArray ), null, true );
        }
        else
        {
            eZContentOperationCollection::updatePriority( $contentNodeID, $priorityArray, $priorityIDArray );
        }
    }

    if ( $http->hasPostVariable( 'ContentObjectID' ) )
    {
        $objectID = $http->postVariable( 'ContentObjectID' );
        eZContentCacheManager::clearContentCache( $objectID );
    }

    if ( $http->hasPostVariable( 'RedirectURIAfterPriority' ) )
    {
        return $module->redirectTo( $http->postVariable( 'RedirectURIAfterPriority' ) );
    }
    $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $contentNodeID . '/' );
    return;
}
else if ( $http->hasPostVariable( "ActionAddToBookmarks" ) )
{
    $user = eZUser::currentUser();
    $nodeID = false;
    if ( $http->hasPostVariable( 'ContentNodeID' ) )
    {
        $nodeID = $http->postVariable( 'ContentNodeID' );
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        $bookmark = eZContentBrowseBookmark::createNew( $user->id(), $nodeID, $node->attribute( 'name' ) );
    }
    if ( !$nodeID )
    {
        $contentINI = eZINI::instance( 'content.ini' );
        $nodeID = $contentINI->variable( 'NodeSettings', 'RootNode' );
    }

    $viewMode = $http->postVariable( 'ViewMode', 'full' );

    $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $nodeID . '/' );
    return;
}
else if ( $http->hasPostVariable( "ActionAddToNotification" ) )
{
    $nodeID = $http->postVariable( 'ContentNodeID' );
    $module->redirectTo( 'notification/addtonotification/' . $nodeID . '/' );
    return;
}
else if ( $http->hasPostVariable( "ContentObjectID" )  )
{
    $objectID = $http->postVariable( "ContentObjectID" );
    $action = $http->postVariable( "ContentObjectID" );


    // Check which action to perform
    if ( $http->hasPostVariable( "ActionAddToBasket" ) )
    {
        $shopModule = eZModule::exists( "shop" );
        $result = $shopModule->run( "basket", array() );
        if ( isset( $result['content'] ) && $result['content'] )
        {
            return $result;
        }
        else
        {
            $module->setExitStatus( $shopModule->exitStatus() );
            $module->setRedirectURI( $shopModule->redirectURI() );
        }

    }
    else if ( $http->hasPostVariable( "ActionAddToWishList" ) )
    {
        $user = eZUser::currentUser();
        if ( !$user->isLoggedIn() )
            return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

        $shopModule = eZModule::exists( "shop" );
        $result = $shopModule->run( "wishlist", array() );
        $module->setExitStatus( $shopModule->exitStatus() );
        $module->setRedirectURI( $shopModule->redirectURI() );
    }
    else if ( $http->hasPostVariable( "ActionPreview" ) )
    {
        $user = eZUser::currentUser();
        $object = eZContentObject::fetch(  $objectID );
        $module->redirectTo( $module->functionURI( 'versionview' ) . '/' . $objectID . '/' . $object->attribute( 'current_version' ) . '/' );
        return;

    }
    else if ( $http->hasPostVariable( "ActionRemove" ) )
    {
        $viewMode = $http->postVariable( 'ViewMode', 'full' );

        $parentNodeID = 2;
        $contentNodeID = null;
        if ( $http->hasPostVariable( 'ContentNodeID' ) and is_numeric( $http->postVariable( 'ContentNodeID' ) ) )
        {
            $contentNodeID = $http->postVariable( 'ContentNodeID' );
            $node = eZContentObjectTreeNode::fetch( $contentNodeID );
            $parentNodeID = $node->attribute( 'parent_node_id' );
        }

        $contentObjectID = $http->postVariable( 'ContentObjectID', 1 );

        $hideRemoveConfirm = false;
        if ( $http->hasPostVariable( 'HideRemoveConfirmation' ) )
            $hideRemoveConfirm = $http->postVariable( 'HideRemoveConfirmation' ) ? true : false;

        if ( $contentNodeID != null )
        {
            $http->setSessionVariable( 'CurrentViewMode', $viewMode );
            $http->setSessionVariable( 'ContentNodeID', $parentNodeID );
            $http->setSessionVariable( 'ContentObjectID', $contentObjectID );
            $http->setSessionVariable( 'HideRemoveConfirmation', $hideRemoveConfirm );
            $http->setSessionVariable( 'DeleteIDArray', array( $contentNodeID ) );
            $object = eZContentObject::fetchByNodeID( $contentNodeID);
            eZSection::setGlobalID( $object->attribute( 'section_id' ) );
            $section = eZSection::fetch( $object->attribute( 'section_id' ) );
            if ( $section )
                $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
            else
                $navigationPartIdentifier = null;
            if ( $navigationPartIdentifier and $navigationPartIdentifier == 'ezusernavigationpart' )
            {
                $module->redirectTo( $module->functionURI( 'removeuserobject' ) . '/' );
            }
            elseif ( $navigationPartIdentifier and $navigationPartIdentifier == 'ezmedianavigationpart' )
            {
                $module->redirectTo( $module->functionURI( 'removemediaobject' ) . '/' );
            }
            else
            {
                $module->redirectTo( $module->functionURI( 'removeobject' ) . '/' );
            }
        }
        else
            $module->redirectToView( 'view', array( $viewMode, $parentNodeID ) );
    }
    else if ( $http->hasPostVariable( "ActionCollectInformation" ) )
    {
        return $module->run( "collectinformation", array() );
    }
    else
    {
        $baseDirectory = eZExtension::baseDirectory();
        $contentINI = eZINI::instance( 'content.ini' );
        $extensionDirectories = $contentINI->variable( 'ActionSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/actions/content_actionhandler.php';
            if ( file_exists( $extensionPath ) )
            {
                include_once( $extensionPath );
                $actionFunction = $extensionDirectory . '_ContentActionHandler';
                if ( function_exists( $actionFunction ) )
                {
                    $actionResult = $actionFunction( $module, $http, $objectID );
                    if ( $actionResult )
                        return $actionResult;
                }
            }
        }
        eZDebug::writeError( "Unknown content object action", "kernel/content/action.php" );
    }
}
else if ( $http->hasPostVariable( 'RedirectButton' ) )
{
    if ( $http->hasPostVariable( 'RedirectURI' ) )
    {
        $module->redirectTo( $http->postVariable( 'RedirectURI' ) );
        return;
    }
}
else if ( $http->hasPostVariable( 'DestinationURL' ) )
{
    $postVariables = $http->attribute( 'post' );
    $destinationURL = $http->postVariable( 'DestinationURL' );
    $additionalParams = '';

    foreach( $postVariables as $key => $value )
    {
        if ( is_array( $value ) )
        {
            $value = implode( ',', $value );
        }
        if ( strpos( $key, 'Param' ) === 0 )
        {
            $destinationURL .= '/' . $value;
        }
        else if ( $key != 'DestinationURL' &&
                  $key != 'Submit' )
        {
            $additionalParams .= "/$key/$value";
        }
    }

    $module->redirectTo( '/' . $destinationURL . $additionalParams );
    return;
}
else if ( $module->isCurrentAction( 'ClearViewCache' ) or
          $module->isCurrentAction( 'ClearViewCacheSubtree' ) )
{
    if ( !$module->hasActionParameter( 'ObjectID' ) )
    {
        eZDebug::writeError( "Missing ObjectID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }
    if ( !$module->hasActionParameter( 'NodeID' ) )
    {
        eZDebug::writeError( "Missing NodeID parameter for action " . $module->currentAction(),
                             'content/action' );
        return $module->redirectToView( 'view', array( 'full', 2 ) );
    }

    $objectID = $module->actionParameter( 'ObjectID' );
    $nodeID = $module->actionParameter( 'NodeID' );

    $object = eZContentObject::fetch( $objectID );
    if ( !$object )
    {
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    $user = eZUser::currentUser();
    $result = $user->hasAccessTo( 'setup', 'managecache' );
    if ( $result['accessWord'] != 'yes' )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    if ( $module->isCurrentAction( 'ClearViewCache' ) )
    {
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
    }
    else
    {
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !$node )
        {
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }
        $limit = 50;
        $offset = 0;
        $params = array( 'AsObject' => false,
                         'Depth' => false,
                         'Limitation' => array() ); // Empty array means no permission checking
        $subtreeCount = $node->subTreeCount( $params );
        while ( $offset < $subtreeCount )
        {
            $params['Offset'] = $offset;
            $params['Limit'] = $limit;
            $subtree = $node->subTree( $params );
            $offset += count( $subtree );
            if ( count( $subtree ) == 0 )
            {
                break;
            }
            $objectIDList = array();
            foreach ( $subtree as $subtreeNode )
            {
                $objectIDList[] = $subtreeNode['contentobject_id'];
            }
            $objectIDList = array_unique( $objectIDList );
            unset( $subtree );

            foreach ( $objectIDList as $objectID )
                eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
        }
    }

    if ( $module->hasActionParameter( 'CurrentURL' ) )
    {
        $currentURL = $module->actionParameter( 'CurrentURL' );
        return $module->redirectTo( $currentURL );
    }

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'UploadFile' ) )
{
    if ( !$module->hasActionParameter( 'UploadActionName' ) )
    {
        eZDebug::writeError( "Missing UploadActionName parameter for action " . $module->currentAction(),
                             'content/action' );
        eZRedirectManager::redirectTo( $module, 'content/view/full/2', true );
        return;
    }

    $user = eZUser::currentUser();
    $result = $user->hasAccessTo( 'content', 'create' );
    if ( $result['accessWord'] != 'yes' )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    $uploadActionName = $module->actionParameter( 'UploadActionName' );
    $parameters = array( 'action_name' => $uploadActionName );

    // Check for locations for the new object
    if ( $module->hasActionParameter( 'UploadParentNodes' ) )
    {
        $parentNodes = $module->actionParameter( 'UploadParentNodes' );
        if ( !is_array( $parentNodes ) )
            $parentNodes = array( $parentNodes );

        foreach ( $parentNodes as $parentNodeID )
        {
            $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
            if ( !is_object( $parentNode ) )
            {
                eZDebug::writeError( "Cannot upload file as child of parent node $parentNodeID, the parent does not exist",
                                     'content/action:' . $module->currentAction() );
                return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
            }
            if ( !$parentNode->canCreate() )
            {
                eZDebug::writeError( "Cannot upload file as child of parent node $parentNodeID, no permissions" . $module->currentAction(),
                                     'content/action:' . $module->currentAction() );
                return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
            }
        }
        $parameters['parent_nodes'] = $parentNodes;
    }

    // Check for redirection to current page
    if ( $module->hasActionParameter( 'UploadRedirectBack' ) )
    {
        if ( $module->actionParameter( 'UploadRedirectBack' ) == 1 )
        {
            $parameters['result_uri'] = eZRedirectManager::redirectURI( $module, 'content/view/full/2', true );
        }
        else if ( $module->actionParameter( 'UploadRedirectBack' ) == 2 )
        {
            $parameters['result_uri'] = eZRedirectManager::redirectURI( $module, 'content/view/full/2', false );
        }
    }

    // Check for redirection to specific page
    if ( $module->hasActionParameter( 'UploadRedirectURI' ) )
    {
        $parameters['result_uri'] = $module->actionParameter( 'UploadRedirectURI' );
    }

    eZContentUpload::upload( $parameters, $module );
    return;
}
/*else if ( $http->hasPostVariable( 'RemoveObject' ) )
{
    $removeObjectID = $http->postVariable( 'RemoveObject' );
    if ( is_numeric( $removeObjectID ) )
    {
        $contentObject = eZContentObject::fetch( $removeObjectID );
        if ( $contentObject->attribute( 'can_remove' ) )
        {
            $contentObject->removeThis();
        }
    }
    $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $topLevelNode . '/' );
    return;
}*/
else if ( !isset( $result ) )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}


// return module contents
$Result = array();
$Result['content'] = isset( $result ) ? $result : null;

?>
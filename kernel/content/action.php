<?php
//
// Created on: <04-Jul-2002 13:06:30 bf>
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

//include_once( 'kernel/classes/ezcontentobject.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
//include_once( 'kernel/classes/ezcontentbrowse.php' );
//include_once( 'kernel/classes/ezcontentbrowsebookmark.php' );
//include_once( 'kernel/classes/ezcontentclass.php' );
//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "lib/ezutils/classes/ezhttptool.php" );
//include_once( "lib/ezutils/classes/ezini.php" );
//include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

$http = eZHTTPTool::instance();
$module = $Params['Module'];

/* We retrieve the class ID for users as this is used in many places in this
 * code in order to be able to cleanup the user-policy cache. */
$ini = eZINI::instance();
$userClassID = $ini->variable( "UserSettings", "UserClassID" );

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
        //include_once( 'kernel/classes/ezcontentlanguage.php' );
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
        //include_once( 'kernel/classes/ezcontentlanguage.php' );
        $allLanguages = eZContentLanguage::prioritizedLanguages();
        // Only show language selection if there are more than 1 languages.
        if ( count( $allLanguages ) > 1 &&
             $hasClassInformation )
        {
            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();

            $tpl->setVariable( 'node_id', $http->postVariable( 'NodeID' ) );
            $tpl->setVariable( 'class_id', $contentClassID );
            $tpl->setVariable( 'assignment_remote_id', ( $http->hasPostVariable( 'AssignmentRemoteID' ) )? $http->postVariable( 'AssignmentRemoteID' ): false );
            $tpl->setVariable( 'redirect_uri_after_publish', ( $http->hasPostVariable( 'RedirectURIAfterPublish' ) )? $http->postVariable( 'RedirectURIAfterPublish' ): false );

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
                                                                        ( $http->hasPostVariable( 'AssignmentRemoteID' ) ?
                                                                              $http->postVariable( 'AssignmentRemoteID' ) : false ) );
            if ( $contentObject )
            {
                if ( $http->hasPostVariable( 'RedirectURIAfterPublish' ) )
                {
                    $http->setSessionVariable( 'RedirectURIAfterPublish', $http->postVariable( 'RedirectURIAfterPublish' ) );
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

    $db = eZDB::instance();
    $db->begin();
    $node->setAttribute( 'sort_field', $sortingField );
    $node->setAttribute( 'sort_order', $sortingOrder );
    $node->store();
    $db->commit();

    // invalidate node view cache
    //include_once( 'kernel/classes/ezcontentcachemanager.php' );
    eZContentCacheManager::clearContentCache( $contentObjectID );

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
    $classID = $class->attribute( 'id' );

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

    //include_once( 'kernel/classes/ezcontentobjecttreenodeoperations.php' );
    if( !eZContentObjectTreeNodeOperations::move( $nodeID, $selectedNodeID ) )
    {
        eZDebug::writeError( "Failed to move node $nodeID as child of parent node $selectedNodeID",
                             'content/action' );
    }

    eZContentObject::fixReverseRelations( $objectID, 'move' );

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
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
    $class = $object->contentClass();
    $classID = $class->attribute( 'id' );

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
    //include_once( 'kernel/classes/ezcontentcachemanager.php' );
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
    $node->setAttribute( 'contentobject_id', $selectedObjectID );
    $node->setAttribute( 'contentobject_version', $selectedObjectVersion );

    $db = eZDB::instance();
    $db->begin();
    $node->store();
    $selectedNode->setAttribute( 'contentobject_id', $objectID );
    $selectedNode->setAttribute( 'contentobject_version', $objectVersion );
    $selectedNode->store();

    // clear user policy cache if this was a user object
    if ( $object->attribute( 'contentclass_id' ) == $userClassID )
    {
        eZUser::cleanupCache();
    }

    // modify path string
    $changedOriginalNode = eZContentObjectTreeNode::fetch( $nodeID );
    $changedOriginalNode->updateSubTreePath();
    $changedTargetNode = eZContentObjectTreeNode::fetch( $selectedNodeID );
    $changedTargetNode->updateSubTreePath();

    // modify section
    if ( $changedOriginalNode->attribute( 'main_node_id' ) == $changedOriginalNode->attribute( 'node_id' ) )
    {
        $changedOriginalObject = $changedOriginalNode->object();
        $parentObject = $nodeParent->object();
        if ( $changedOriginalObject->attribute( 'section_id' ) != $parentObject->attribute( 'section_id' ) )
        {

            eZContentObjectTreeNode::assignSectionToSubTree( $changedOriginalNode->attribute( 'main_node_id' ),
                                                             $parentObject->attribute( 'section_id' ),
                                                             $changedOriginalObject->attribute( 'section_id' ) );
        }
    }
    if ( $changedTargetNode->attribute( 'main_node_id' ) == $changedTargetNode->attribute( 'node_id' ) )
    {
        $changedTargetObject = $changedTargetNode->object();
        $selectedParentObject = $selectedNodeParent->object();
        if ( $changedTargetObject->attribute( 'section_id' ) != $selectedParentObject->attribute( 'section_id' ) )
        {

            eZContentObjectTreeNode::assignSectionToSubTree( $changedTargetNode->attribute( 'main_node_id' ),
                                                             $selectedParentObject->attribute( 'section_id' ),
                                                             $changedTargetObject->attribute( 'section_id' ) );
        }
    }

    eZContentObject::fixReverseRelations( $objectID, 'swap' );
    eZContentObject::fixReverseRelations( $selectedObjectID, 'swap' );

    $db->commit();

    // clear cache for new placement.
    eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

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

            eZContentObjectTreeNode::updateMainNodeID( $mainAssignmentID, $objectID, false,
                                                       $newMainNode->attribute( 'parent_node_id' ) );

            //include_once( 'kernel/classes/ezcontentcachemanager.php' );
            eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
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

        $nodeAssignmentList = eZNodeAssignment::fetchForObject( $objectID, $object->attribute( 'current_version' ), 0, false );
        $assignedNodes = $object->assignedNodes();

        $parentNodeIDArray = array();
        $setMainNode = false;
        $hasMainNode = false;
        foreach ( $assignedNodes as $assignedNode )
        {
            if ( $assignedNode->attribute( 'is_main' ) )
                $hasMainNode = true;

            $append = false;
            foreach ( $nodeAssignmentList as $nodeAssignment )
            {
                if ( $nodeAssignment['parent_node'] == $assignedNode->attribute( 'parent_node_id' ) )
                {
                    $append = true;
                    break;
                }
            }
            if ( $append )
            {
                $parentNodeIDArray[] = $assignedNode->attribute( 'parent_node_id' );
            }
        }
        if ( !$hasMainNode )
            $setMainNode = true;

        $mainNodeID = $existingNode->attribute( 'main_node_id' );
        $objectName = $object->attribute( 'name' );

        $db = eZDB::instance();
        $db->begin();
        $locationAdded = false;
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        foreach ( $selectedNodeIDArray as $selectedNodeID )
        {
            if ( !in_array( $selectedNodeID, $parentNodeIDArray ) )
            {
                $parentNode = eZContentObjectTreeNode::fetch( $selectedNodeID );
                $parentNodeObject = $parentNode->attribute( 'object' );

                $canCreate = ( ( $parentNode->checkAccess( 'create', $class->attribute( 'id' ), $parentNodeObject->attribute( 'contentclass_id' ) ) == 1 ) ||
                               ( $parentNode->canAddLocation() && $node->canRead() ) );

                if ( $canCreate )
                {
                    $insertedNode = $object->addLocation( $selectedNodeID, true );

                    // Now set is as published and fix main_node_id
                    $insertedNode->setAttribute( 'contentobject_is_published', 1 );
                    $insertedNode->setAttribute( 'main_node_id', $node->attribute( 'main_node_id' ) );
                    $insertedNode->setAttribute( 'contentobject_version', $node->attribute( 'contentobject_version' ) );
                    // Make sure the url alias is set updated.
                    $insertedNode->updateSubTreePath();
                    $insertedNode->sync();

                    $locationAdded = true;
                }
            }
        }
        if ( $locationAdded )
        {
            if ( $object->attribute( 'contentclass_id' ) == $userClassID )
            {
                eZUser::cleanupCache();
            }
        }
        $db->commit();

        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
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
        $mainNodeChanged = false;
        $nodeAssignmentList = eZNodeAssignment::fetchForObject( $objectID, $object->attribute( 'current_version' ), 0, false );
        $nodeAssignmentIDList = array();

        $db = eZDB::instance();
        $db->begin();
        foreach ( $nodeRemoveList as $key => $node )
        {
            foreach ( $nodeAssignmentList as $nodeAssignmentKey => $nodeAssignment )
            {
                if ( $nodeAssignment['parent_node'] == $node->attribute( 'parent_node_id' ) )
                {
                    $nodeAssignmentIDList[] = $nodeAssignment['id'];
                    unset( $nodeAssignmentList[$nodeAssignmentKey] );
                }
            }

            if ( $node->attribute( 'node_id' ) == $node->attribute( 'main_node_id' ) )
                $mainNodeChanged = true;
            $node->removeThis();
        }
        eZNodeAssignment::purgeByID( array_unique( $nodeAssignmentIDList ) );

        if ( $mainNodeChanged )
        {
            $allNodes = $object->assignedNodes();
            $mainNode = $allNodes[0];
            eZContentObjectTreeNode::updateMainNodeID( $mainNode->attribute( 'node_id' ), $objectID, false, $mainNode->attribute( 'parent_node_id' ) );
        }
        $db->commit();
    }

    //include_once( 'kernel/classes/ezcontentcachemanager.php' );
    eZContentCacheManager::clearObjectViewCacheIfNeeded( $objectID );
    // clear user policy cache if this was a user object
    if ( $object->attribute( 'contentclass_id' ) == $userClassID )
    {
        eZUser::cleanupCache();
    }

    // we don't clear template block cache here since it's cleared in eZContentObjectTreeNode::removeNode()

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
    if ( $http->hasPostVariable( 'ViewMode' ) )
    {
        $viewMode = $http->postVariable( 'ViewMode' );
    }
    else
    {
        $viewMode = 'full';
    }
//     if ( $http->hasPostVariable( 'TopLevelNode' ) )
//     {
//         $topLevelNode = $http->postVariable( 'TopLevelNode' );
//     }
//     else
//     {
//         $topLevelNode = '2';
//     }
    $contentNodeID = 2;
    if ( $http->hasPostVariable( 'ContentNodeID' ) )
        $contentNodeID = $http->postVariable( 'ContentNodeID' );
    $contentObjectID = 1;
    if ( $http->hasPostVariable( 'ContentObjectID' ) )
        $contentObjectID = $http->postVariable( 'ContentObjectID' );

    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        if ( is_array( $deleteIDArray ) && count( $deleteIDArray ) > 0 )
        {
            $http->setSessionVariable( 'CurrentViewMode', $viewMode );
            $http->setSessionVariable( 'ContentNodeID', $contentNodeID );
            $http->setSessionVariable( 'ContentObjectID', $contentObjectID );
            $http->setSessionVariable( 'DeleteIDArray', $deleteIDArray );
            //include_once( 'kernel/classes/ezsection.php' );
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
else if ( $http->hasPostVariable( 'UpdatePriorityButton' ) )
{
    //include_once( 'kernel/classes/ezcontentcache.php' );
    if ( $http->hasPostVariable( 'ViewMode' ) )
    {
        $viewMode = $http->postVariable( 'ViewMode' );
    }
    else
    {
        $viewMode = 'full';
    }

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

        $db = eZDB::instance();
        $db->begin();
        for ( $i=0; $i<count( $priorityArray );$i++ )
        {
            $priority = (int) $priorityArray[$i];
            $nodeID = (int) $priorityIDArray[$i];
            $db->query( "UPDATE ezcontentobject_tree SET priority=$priority WHERE node_id=$nodeID" );
        }
        $contentNode->updateAndStoreModified();
        $db->commit();
    }

    if ( $http->hasPostVariable( 'ContentObjectID' ) )
    {
        $objectID = $http->postVariable( 'ContentObjectID' );
        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearContentCache( $objectID );
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
    if ( $http->hasPostVariable( 'ViewMode' ) )
    {
        $viewMode = $http->postVariable( 'ViewMode' );
    }
    else
    {
        $viewMode = 'full';
    }
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
        if ( $http->hasPostVariable( 'ViewMode' ) )
        {
            $viewMode = $http->postVariable( 'ViewMode' );
        }
        else
        {
            $viewMode = 'full';
        }
        $parentNodeID = 2;
        $contentNodeID = null;
        if ( $http->hasPostVariable( 'ContentNodeID' ) and is_numeric( $http->postVariable( 'ContentNodeID' ) ) )
        {
            $contentNodeID = $http->postVariable( 'ContentNodeID' );
            $node = eZContentObjectTreeNode::fetch( $contentNodeID );
            $parentNodeID = $node->attribute( 'parent_node_id' );
        }
        $contentObjectID = 1;
        if ( $http->hasPostVariable( 'ContentObjectID' ) )
            $contentObjectID = $http->postVariable( 'ContentObjectID' );

        if ( $contentNodeID != null )
        {
            $http->setSessionVariable( 'CurrentViewMode', $viewMode );
            $http->setSessionVariable( 'ContentNodeID', $parentNodeID );
            $http->setSessionVariable( 'ContentObjectID', $contentObjectID );
            $http->setSessionVariable( 'DeleteIDArray', array( $contentNodeID ) );
            $object = eZContentObject::fetchByNodeID( $contentNodeID);
            //include_once( 'kernel/classes/ezsection.php' );
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
        //include_once( 'lib/ezutils/classes/ezextension.php' );
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

    //include_once( 'kernel/classes/ezcontentcachemanager.php' );
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
        //include_once( 'kernel/classes/ezredirectmanager.php' );
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
            //include_once( 'kernel/classes/ezredirectmanager.php' );
            $parameters['result_uri'] = eZRedirectManager::redirectURI( $module, 'content/view/full/2', true );
        }
        else if ( $module->actionParameter( 'UploadRedirectBack' ) == 2 )
        {
            //include_once( 'kernel/classes/ezredirectmanager.php' );
            $parameters['result_uri'] = eZRedirectManager::redirectURI( $module, 'content/view/full/2', false );
        }
    }

    // Check for redirection to specific page
    if ( $module->hasActionParameter( 'UploadRedirectURI' ) )
    {
        $parameters['result_uri'] = $module->actionParameter( 'UploadRedirectURI' );
    }

    //include_once( 'kernel/classes/ezcontentupload.php' );
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

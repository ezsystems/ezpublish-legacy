<?php
//
//
// Created on: <08-Nov-2002 16:02:26 wy>
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/common/template.php" );

$module =& $Params["Module"];

$http =& eZHTTPTool::instance();

$removeList = array();

if ( $http->hasSessionVariable( 'AssignmentRemoveData' ) )
{
    $data = $http->sessionVariable( 'AssignmentRemoveData' );
    $removeList = $data['remove_list'];
    $objectID = $data['object_id'];
    $nodeID = $data['node_id'];
    $viewMode = $data['view_mode'];
    $languageCode = $data['language_code'];

    $object =& eZContentObject::fetch( $objectID );
    if ( !$object )
    {
        return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
    }
    if ( !$object->checkAccess( 'edit' ) )
    {
        return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
    }

}
else
{
    return $module->redirectToView( 'view', array( 'full', 2 ) );
}

if ( $module->isCurrentAction( 'ConfirmRemoval' ) )
{
    $http->removeSessionVariable( 'AssignmentRemoveData' );

    $nodeAssignments = eZNodeAssignment::fetchListByID( $removeList );
    $nodeRemoveList = array();
    foreach ( $nodeAssignments as $key => $nodeAssignment )
    {
        $nodeAssignment =& $nodeAssignments[$key];
        $node =& $nodeAssignment->fetchNode();

        if ( $node )
        {
            // Security checks, removal of current node is not allowed
            // and we require removal rights as well
            if ( $node->attribute( 'node_id' ) == $nodeID )
                continue;
            if ( !$node->canRemove() )
                continue;

            $nodeRemoveList[] =& $node;
        }
    }

    $mainNodeChanged = false;
    foreach ( $nodeRemoveList as $key => $node )
    {
        if ( $node->attribute( 'node_id' ) == $node->attribute( 'main_node_id' ) )
            $mainNodeChanged = true;
        $node->remove();
    }
    if ( $mainNodeChanged )
    {
        $allNodes =& $object->assignedNodes();
        $mainNode =& $allNodes[0];
        eZContentObjectTreeNode::updateMainNodeID( $mainNode->attribute( 'node_id' ), $objectID, false, $mainNode->attribute( 'parent_node_id' ) );
    }

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'CancelRemoval' ) )
{
    $http->removeSessionVariable( 'AssignmentRemoveData' );
    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}

$tpl =& templateInit();

$deleteIDArray = array();
$nodeAssignments = eZNodeAssignment::fetchListByID( $removeList );
foreach ( $nodeAssignments as $key => $nodeAssignment )
{
    $nodeAssignment =& $nodeAssignments[$key];
    $node =& $nodeAssignment->fetchNode();

    if ( $node )
    {
        $deleteIDArray[] = $node->attribute( 'node_id' );
    }
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

$tpl->setVariable( 'moveToTrashAllowed', $moveToTrashAllowed );
$tpl->setVariable( 'remove_list', $deleteResult );
$tpl->setVariable( 'total_child_count', $totalChildCount );
$tpl->setVariable( 'remove_info', $info );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/removelocation.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/content', 'Remove location' ) ) );
?>

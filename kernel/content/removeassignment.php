<?php
//
//
// Created on: <19-Oct-2004 18:03:49 vs>
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
$http   =& eZHTTPTool::instance();

if ( $http->hasSessionVariable( 'AssignmentRemoveData' ) )
{
    $data = $http->sessionVariable( 'AssignmentRemoveData' );
    $removeList   = $data['remove_list'];
    $objectID     = $data['object_id'];
    $editVersion  = $data['edit_version'];
    $editLanguage = $data['edit_language'];
    $fromLanguage = $data['from_language'];

    $object =& eZContentObject::fetch( $objectID );
    if ( !$object )
        return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
    if ( !$object->checkAccess( 'edit' ) )
        return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
    unset( $object );
}
else
{
    eZDebug::writeError( "No assignments passed to content/removeassignment" );
    return $module->redirectToView( 'view', array( 'full', 2 ) );
}

// process current action
if ( $module->isCurrentAction( 'ConfirmRemoval' ) )
{
    $http->removeSessionVariable( 'AssignmentRemoveData' );

    $assignments     =& eZnodeAssignment::fetchListByID( $removeList );
    $mainNodeChanged = false;

    foreach ( $assignments as $assignment )
    {
        $assignmentID = $assignment->attribute( 'id' );
        if ( $assignment->attribute( 'is_main' ) )
            $mainNodeChanged = true;
        eZNodeAssignment::removeByID( $assignmentID );
    }
    if ( $mainNodeChanged )
        eZNodeAssignment::setNewMainAssignment( $objectID, $editVersion );

    return $module->redirectToView( 'edit', array( $objectID, $editVersion, $editLanguage, $fromLanguage ) );
}
else if ( $module->isCurrentAction( 'CancelRemoval' ) )
{
    $http->removeSessionVariable( 'AssignmentRemoveData' );

    return $module->redirectToView( 'edit', array( $objectID, $editVersion, $editLanguage, $fromLanguage ) );
}

// default action: show the confirmation dialog
$assignmentsToRemove = eZNodeAssignment::fetchListByID( $removeList );
$removeList = array();
$canRemoveAll = true;
foreach ( $assignmentsToRemove as $assignment )
{
    $node =& $assignment->attribute( 'node' );

    // skip assignments which don't have associated node or node with no children
    if ( !$node )
        continue;
    $count = $node->subTreeCount( array( 'Limitation' => array() ) );
    if ( $count < 1 )
        continue;

    // Find the number of items in the subtree we are allowed to remove
    // if this differs from the total count it means we have items we cannot remove
    // We do this by fetching the limitation list for content/remove
    // and passing it to the subtre count function.
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $currentUser =& eZUser::currentUser();
    $accessResult = $currentUser->hasAccessTo( 'content', 'remove' );
    $canRemoveSubtree = true;
    if ( $accessResult['accessWord'] == 'limited' )
    {
        $limitationList =& $accessResult['policies'];
        $removeableChildCount = $node->subTreeCount( array( 'Limitation' => $limitationList ) );
        $canRemoveSubtree = ( $removeableChildCount == $count );
    }
    if ( !$canRemoveSubtree )
        $canRemoveAll = false;
    $object =& $node->object();
    $class =& $object->contentClass();

    $removeList[] = array( 'node' => $node,
                           'object' => $object,
                           'class' => $class,
                           'count' => $count,
                           'can_remove' => $canRemoveSubtree,
                           'child_count' => $count );
}
unset( $assignmentsToRemove );

$assignmentData = array( 'object_id'      => $objectID,
                         'object_version' => $editVersion,
                         'remove_list'    => $removeList );
$info = array( 'can_remove_all' => $canRemoveAll );

$tpl =& templateInit();
$tpl->setVariable( 'assignment_data', $assignmentData );
$tpl->setVariable( 'remove_info', $info );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/removeassignment.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/content', 'Remove location' ) ) );
?>

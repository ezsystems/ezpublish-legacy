<?php
//
//
// Created on: <19-Oct-2004 18:03:49 vs>
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

//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "lib/ezutils/classes/ezhttptool.php" );
require_once( "kernel/common/template.php" );

$module = $Params['Module'];
$http   = eZHTTPTool::instance();

if ( $http->hasSessionVariable( 'AssignmentRemoveData' ) )
{
    $data = $http->sessionVariable( 'AssignmentRemoveData' );
    $removeList   = $data['remove_list'];
    $objectID     = $data['object_id'];
    $editVersion  = $data['edit_version'];
    $editLanguage = $data['edit_language'];
    $fromLanguage = $data['from_language'];

    $object = eZContentObject::fetch( $objectID );
    if ( !$object )
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    if ( !$object->checkAccess( 'edit' ) )
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
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

    $assignments     = eZNodeAssignment::fetchListByID( $removeList );
    $mainNodeChanged = false;

    $db = eZDB::instance();
    $db->begin();
    foreach ( $assignments as $assignment )
    {
        $assignmentID = $assignment->attribute( 'id' );
        if ( $assignment->attribute( 'is_main' ) )
            $mainNodeChanged = true;
        eZNodeAssignment::purgeByID( $assignmentID );
    }
    if ( $mainNodeChanged )
        eZNodeAssignment::setNewMainAssignment( $objectID, $editVersion );

    $db->commit();

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
    $node = $assignment->attribute( 'node' );

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
    //include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $currentUser = eZUser::currentUser();
    $accessResult = $currentUser->hasAccessTo( 'content', 'remove' );
    $canRemoveSubtree = true;
    if ( $accessResult['accessWord'] == 'limited' )
    {
        $limitationList = $accessResult['policies'];
        $removeableChildCount = $node->subTreeCount( array( 'Limitation' => $limitationList ) );
        $canRemoveSubtree = ( $removeableChildCount == $count );
    }
    if ( !$canRemoveSubtree )
        $canRemoveAll = false;
    $object = $node->object();
    $class = $object->contentClass();

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

$tpl = templateInit();
$tpl->setVariable( 'assignment_data', $assignmentData );
$tpl->setVariable( 'remove_info', $info );

$Result = array();
$Result['content'] = $tpl->fetch( "design:content/removeassignment.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/content', 'Remove location' ) ) );
?>
